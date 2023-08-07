import { defineStore } from 'pinia'
import axios from 'axios'

export const useEmployeesStore = defineStore('employees', {
  state: () => ({
    data: [],
    loading: false,
    notify: {
      showNotification: false,
      textNotification: '',
      typeNotification: 'warn',
    },
    searchResultCompany_id: [],

  }),
  actions: {
    async fetch(id = '', query) {
      this.startLoading()
      try {
        const result = await axios.get(`employees${query || (id ? `/${id}` : '')}`);
        this.getData(id ? result.data : result.data.rows)
      } catch (e) {
        // dispatch('snackbar/showSnackbar', e, { root: true });
      } finally {
        this.finishLoading()
      }
    },
    async deleteItem(id) {
      try {
        await axios.delete(`/employees/${id}`);
        this.showNotification('Company has been deleted', 'success');
        await this.fetch()
      } catch (e) {
        console.log('deleteItem error', e)
        // dispatch('snackbar/showSnackbar', e, { root: true });
      }
    },
    async newItem(payload) {
      this.startLoading();
      try {

        console.log(payload.company_id.id)
        const formData = new FormData();
        formData.append('first_name', payload.first_name);
        formData.append('last_name', payload.last_name);
        formData.append('company_id', payload.company_id.id);
        formData.append('email', payload.email);
        formData.append('phone', payload.phone);

        const result = await axios.post('/employees', formData);
        this.showNotification('Employee has been created', 'success');
        this.getData(result.data)
      } catch (e) {
        console.log(e)
        // dispatch('snackbar/showSnackbar', e, { root: true });
      } finally {
        this.finishLoading()
      }
    },
    async edit(payload) {
      this.startLoading();
      try {


        const isCompanyIdNotInteger = typeof payload.data.company_id !== 'number' || !Number.isInteger(payload.data.company_id);
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('first_name', payload.data.first_name);
        formData.append('last_name', payload.data.last_name);
        isCompanyIdNotInteger ? formData.append('company_id', payload.data.company_id.id) : formData.append('company_id', payload.data.company_id);
        formData.append('email', payload.data.email);
        formData.append('phone', payload.data.phone);

        const result = await axios.post(`/employees/${payload.id}`, formData)

        // dispatch('auth/findMe', null, {root: true})
        //
        this.showNotification('Company has been updated', 'success');
        this.getData(result.data)
      } catch (e) {
        this.showNotification(e, 'error');
      } finally {
        this.finishLoading()
      }
    },
    async searchCompany_id(val) {
      try {
        if (val) {
          const result = await axios(
            `/companies/autocomplete?query=${val}&limit=100`,
          );
          this.setCompany_id(result.data);
        } else {
          const result = await axios(`/companies/autocomplete?limit=100`);
          this.setCompany_id(result.data);
        }
      } catch (e) {
        this.showNotification(e, 'error')
        this.setCompany_id([]);
      }
    },
    startLoading() {
      this.loading = false;
    },
    getData(payload) {
      this.data = payload;
    },
    finishLoading() {
      this.loading = false;
    },
    showNotification(payload, type) {
      this.notify.showNotification = true
      this.notify.textNotification = payload
      this.notify.typeNotification = type
    },
    hideNotification() {
      this.notify.showNotification = false
      this.notify.textNotification = ''
    },
    setCompany_id(payload) {
      this.searchResultCompany_id = payload
    },


  }
})
