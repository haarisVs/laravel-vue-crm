<script setup>
import { ref, reactive, computed, watch, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { useCompaniesStore } from "@/stores/Companies/companies";
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
import Editor from '@tinymce/tinymce-vue'
import { notify } from "@kyvg/vue3-notification";

const companiesStore = useCompaniesStore()
const router = useRouter();

const notification = computed(() => companiesStore.notify)
const titleStack = ref(['Admin', 'Users'])

const form = reactive({

  name: '',
  email: '',
  website: '',
  logo: []
})

onBeforeMount(async () => {

})

const submit = async () => {
  try {

    console.log(form)
    await companiesStore.newItem({ ...form })
    router.push('/companies');
  } catch (e) {
    console.log(e);
  }
}

const reset = () => {

  form.name = '';

  form.email = '';

  form.website = '';

  form.logo = [];
}

const cancel = () => {
  router.push('/companies')
}

watch(() => companiesStore.notify.showNotification, (newValue, oldValue) => {
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
  <SectionHeroBar>New Company</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="New Company"
      form
      @submit.prevent="submit"
    >

      <FormField
        label="Company Name"
      >
        <FormControl
          v-model="form.name"
          placeholder="Your  Name"
        />
      </FormField>

      <FormField
        label="E-Mail"
      >
        <FormControl
          v-model="form.email"
          placeholder="Your E-Mail"
        />
      </FormField>

      <FormField
        label="Website"
      >
        <FormControl
          v-model="form.website"
          placeholder="Website"
        />
      </FormField>

      <FormField
        label="Logo"
      >
        <FormFilePicker v-model="form.logo" url="company/logo"/>
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
