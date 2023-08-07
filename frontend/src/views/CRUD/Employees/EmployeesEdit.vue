<script setup>
import { ref, reactive, onBeforeMount, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useEmployeesStore } from "@/stores/Employees/employees";
import SectionMain from '@/components/SectionMain.vue'
import SectionTitleBar from '@/components/SectionTitleBar.vue'
import CardBox from '@/components/CardBox.vue'
import FormCheckRadioPicker from '@/components/FormCheckRadioPicker.vue'
import FormFilePicker from '@/components/FormFilePicker.vue'
import SectionHeroBar from '@/components/SectionHeroBar.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import { notify } from "@kyvg/vue3-notification";
import Editor from '@tinymce/tinymce-vue';
import dataFormatter from '@/helpers/dataFormatter';

const router = useRouter();
const route = useRoute();
const employeesStore = useEmployeesStore()

const titleStack = ref(['Admin', 'Employees'])
const notification = computed(() => employeesStore.notify)

const optionsCompany_id = computed(() => employeesStore.searchResultCompany_id);

const form = reactive({

  first_name: '',
  last_name: '',
  company_id: '',
  email: '',
  phone: '',
})

const employeeItem = computed(() => employeesStore.data);

const submit = async () => {
  try {

    form.user_id = form.user_id?.id;

    await employeesStore.edit({id: route.params.id, data: {...form} })
    router.push('/employees');
  } catch (e) {
    console.log(e);
  }
}

onBeforeMount(async () => {
  try {

    await searchCompany_id();

    await employeesStore.fetch(route.params.id)
    formatData();
  } catch (e) {
    console.log(e)
    employeesStore.showNotification(e, 'error');
  }
})

async function searchCompany_id(val) {
  await employeesStore.searchCompany_id(val);
}

const formatData = () => {
  form.first_name = employeeItem.value.first_name
  form.last_name = employeeItem.value.last_name
  form.company_id = employeeItem.value.company_id
  form.email = employeeItem.value.email
  form.phone = employeeItem.value.phone
}

watch(() => employeesStore.notify.showNotification, (newValue, oldValue) => {
  if(newValue){
    notify({
      title: "Employee notification",
      text: notification.value.textNotification,
      type: notification.value.typeNotification,
    });
    employeesStore.hideNotification()
  }
});

const reset = () => {
  formatData();
}

const cancel = () => {
  router.push('/employees')
}

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>Edit Employee</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="Edit Employee"
      form
      @submit.prevent="submit"
    >

      <FormField
        label="First Name"
      >
        <FormControl
          v-model="form.first_name"
          placeholder="First Name"
        />
      </FormField>

      <FormField
        label="Last Name"
      >
        <FormControl
          v-model="form.last_name"
          placeholder="Last Name"
        />
      </FormField>

      <FormField
        label="Company"
      >
        <v-select
          v-model="form.company_id"
          :options="optionsCompany_id"
          @input="searchCompany_id($event.target.value)"
        />
      </FormField>


      <FormField
        label="Email"
      >
        <FormControl
          v-model="form.email"
          placeholder="Email"
        />
      </FormField>

      <FormField
        label="Phone"
      >
        <FormControl
          v-model="form.phone"
          placeholder="Phone"
        />
      </FormField>

      <BaseDivider />

      <BaseButtons>
        <BaseButton
          type="submit"
          color="info"
          label="Submit"
        />
        <BaseButton
          type="button"
          color="info"
          outline
          label="Reset"
          @click="reset"
        />
        <BaseButton
          type="cancel"
          color="danger"
          outline
          label="Cancel"
          @click="cancel"
        />
      </BaseButtons>
    </CardBox>
  </SectionMain>

</template>
