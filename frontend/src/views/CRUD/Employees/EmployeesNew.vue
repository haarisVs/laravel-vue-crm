<script setup>
import { ref, reactive, computed, watch, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
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

const employeesStore = useEmployeesStore()
const router = useRouter();

const notification = computed(() => employeesStore.notify)
const titleStack = ref(['Admin', 'Employees'])
const optionsCompany_id = computed(() => employeesStore.searchResultCompany_id);


const form = reactive({

  first_name: '',
  last_name: '',
  company_id: '',
  email: '',
  phone: '',

})

onBeforeMount(async () => {

    await searchCompany_id();
})

const submit = async () => {
  try {
    await employeesStore.newItem({ ...form })
    router.push('/employees');
  } catch (e) {
    console.log(e);
  }
}

const reset = () => {

  form.first_name = '';
  form.last_name = '';
  form.company_id = '';
  form.email = '';
  form.phone = '';
}

const cancel = () => {
  router.push('/employees')
}

async function searchCompany_id(val) {
  await employeesStore.searchCompany_id(val);
}

watch(() => employeesStore.notify.showNotification, (newValue, oldValue) => {
  if(newValue){
    notify({
      title: "Employees notification",
      text: notification.value.textNotification,
      type: notification.value.typeNotification,
    });
    employeesStore.hideNotification()
  }
});

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>New Employee</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="New Employee"
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
          type="reset"
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
