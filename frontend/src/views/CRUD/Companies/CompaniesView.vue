<script setup>
import { ref, computed, watch } from 'vue'
import SectionMain from '@/components/SectionMain.vue'
import TableSampleCompanies from "@/components/Companies/TableSampleCompanies.vue";
import CardBox from '@/components/CardBox.vue'
import SectionTitleBar from '@/components/SectionTitleBar.vue'
import SectionHeroBar from '@/components/SectionHeroBar.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import { notify } from "@kyvg/vue3-notification";
import FormField from '@/components/FormField.vue';
import FormControl from '@/components/FormControl.vue';

import { useCompaniesStore } from "@/stores/Companies/companies";
import axios from "axios";
const companiesStore = useCompaniesStore()

const notification = computed(() => companiesStore.notify)

const titleStack = ref(['Admin', 'Company'])

const filters = ref([]);
const filtersList = [{label: 'First Name', title: 'firstName'},{label: 'Last Name', title: 'lastName'},{label: 'Phone Number', title: 'phoneNumber'},{label: 'E-Mail', title: 'email'},];
const showFilters = ref(false);

const query = ref('');
const sortTitle = ref('');
const sortDirection = ref('');

const generateQuery = () => {
  query.value = '?';
  filters.value.forEach((f) => {
    if (f.filter && f.filter.title) {
      let {filter, value, from, to} = f;
      filter.number
        ? (query.value += `${filter.title}Range[]=${from}&${filter.title}Range=${to}&`)
        : (query.value += `${filter.title}=${value}&`);
    }
  });

  if (sortTitle.value) {
    query.value += `field=${sortTitle.value}&sort=${sortDirection.value}&`;
  }
}

const addFilter = () => {
  filters.value.push({});
  if (!showFilters.value) {
    showFilters.value = true;
  }
}

const getUsersCSV = async () => {
  const response = await axios({url: '/companies?filetype=csv', method: 'GET',responseType: 'blob'});
  const type = response.headers['content-type']
  const blob = new Blob([response.data], { type: type, encoding: 'UTF-8' })
  const link = document.createElement('a')
  link.href = window.URL.createObjectURL(blob)
  link.download = 'usersCSV.csv'
  link.click()
}

const deleteFilter = (index) => {
  filters.value.splice(index, 1);
  generateQuery();
  companiesStore.fetch(null, query.value);
}

const cancelFilter = () => {
  filters.value = [];
  showFilters.value = false;
  generateQuery();
  companiesStore.fetch(null, query.value);
}

const updateFilter = (index, filter) => {
  filters.value[index] = {
    filter,
    value: null,
    from: null,
    to: null
  };
}

const updateValue = (index, name, event) => {
  filters.value[index][name]= event.target.value;
}

const applyFilters = () => {
  generateQuery();
  companiesStore.fetch(null, query.value);
}

const sortTable = (data) => {
  sortTitle.value = data.sortTitle;
  sortDirection.value = data.sortDirection;
  generateQuery();
  companiesStore.fetch(null, query.value);
}

watch(() => notification.value.showNotification, (newValue, oldValue) => {
  if(newValue){
    notify({
      title: "Company notification",
      text: notification.value.textNotification,
      type: notification.value.typeNotification,
    });
    companiesStore.hideNotification()
  }
});
</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>Companies</SectionHeroBar>
  <SectionMain>
    <BaseButtons>
      <BaseButton
        color="default"
        label="New"
        to="/companies/new"
      />

    </BaseButtons>
  </SectionMain>

  <SectionMain v-show="showFilters" class="mx-12 bg-white">
    <div class="flex" v-for="(filter, index) in filters" :key="filter + index">
      <FormField label="Filter" class="mr-3 basis-96 min-w-96 max-w-96">
        <v-select :v-model="filter.filter" :options="filtersList" @option:selected="updateFilter(index, $event)"/>
      </FormField>

      <template v-if="filter.filter && filter.filter.number">
        <FormField label="From" class="mr-3 basis-48 min-w-48 max-w-48">
          <FormControl v-model="filter.from" @input="updateValue(index, 'from', $event)"/>
        </FormField>
        <FormField label="To" class="mr-3 basis-48 min-w-48 max-w-48">
          <FormControl v-model="filter.to" @input="updateValue(index, 'to', $event)"/>
        </FormField>
      </template>
      <FormField v-else label="Contains" class="mr-3 basis-96 min-w-96 max-w-96">
        <FormControl v-model="filter.value" @input="updateValue(index, 'value', $event)"/>
      </FormField>

      <FormField label="Action">
        <BaseButtons>
          <BaseButton color="danger" class="h-12" label="Delete" @click="deleteFilter(index)"/>
        </BaseButtons>
      </FormField>
    </div>
    <BaseButtons>
      <BaseButton color="info" class="h-12" label="Apply" @click="applyFilters()"/>
      <BaseButton color="info" outline class="h-12" label="Cancel" @click="cancelFilter()"/>
    </BaseButtons>
  </SectionMain>

  <SectionMain>
    <CardBox
      class="mb-6"
      title="Company table"
      has-table
    >
      <TableSampleCompanies checkable :sortTitle="sortTitle" :sortDirection="sortDirection" @sort="sortTable($event)" />
    </CardBox>

  </SectionMain>

</template>
