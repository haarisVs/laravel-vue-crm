import { defineStore } from 'pinia'
import axios from 'axios'

export const useCompaniesStore = defineStore('companies', {
  state: () => ({
    data: [],
    loading: false,
    notify: {
      showNotification: false,
      textNotification: '',
      typeNotification: 'warn',
    },

  }),
  actions: {
    async fetch(id = '', query) {
      this.startLoading()
      try {
        const result = await axios.get(`companies${query || (id ? `/${id}` : '')}`);
        this.getData(id ? result.data : result.data.rows)
      } catch (e) {
        // dispatch('snackbar/showSnackbar', e, { root: true });
      } finally {
        this.finishLoading()
      }
    },
    async deleteItem(id) {
      try {
        await axios.delete(`/companies/${id}`);
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

        console.log('payload', payload)
        const formData = new FormData();
        formData.append('name', payload.name);
        formData.append('email', payload.email);
        formData.append('website', payload.website);
        for (const key in payload.logo[0]) {
          if (Object.prototype.hasOwnProperty.call(payload.logo[0], key)) {
            formData.append(`logo[${key}]`, payload.logo[0][key]);
          }
        }

        const result = await axios.post('/companies', formData);
        this.showNotification('Company has been created', 'success');
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

        console.log('payload', payload);
        const formData = new FormData();

        formData.append('_method', 'PUT');
        formData.append('name', payload.data.name);
        formData.append('email', payload.data.email);
        formData.append('website', payload.data.website);
        if(typeof payload.data.logo !== 'number' || !Number.isInteger(payload.data.logo))
        {
          for (const key in payload.logo[0]) {
            if (Object.prototype.hasOwnProperty.call(payload.logo[0], key)) {
              formData.append(`logo[${key}]`, payload.logo[0][key]);
            }
          }
        }
        else
        {
          formData.append('logo', payload.data.logo);
        }

        const result = await axios.post(`/companies/${payload.id}`, formData)
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

  }
})
