<script setup>
import { ref, reactive, onBeforeMount, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
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
import { notify } from "@kyvg/vue3-notification";
import Editor from '@tinymce/tinymce-vue';
import dataFormatter from '@/helpers/dataFormatter';

const router = useRouter();
const route = useRoute();
const companiesStore = useCompaniesStore()

const titleStack = ref(['Admin', 'Company'])
const notification = computed(() => companiesStore.notify)

const form = reactive({

  name: '',

  email: '',

  website: '',

  logo: []

})

const companiesItem = computed(() => companiesStore.data);

const submit = async () => {
  try {
    await companiesStore.edit({id: route.params.id, data: {...form} })
    router.push('/companies');
  } catch (e) {
    console.log(e);
  }
}

onBeforeMount(async () => {
  try {
    await companiesStore.fetch(route.params.id)
    formatData();
  } catch (e) {
    console.log(e)
    companiesStore.showNotification(e, 'error');
  }
})

const formatData = () => {

  form.name = companiesItem.value.name

  form.email = companiesItem.value.email

  form.website = companiesItem.value.website

  form.logo = companiesItem.value.logo
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

const reset = () => {
  formatData();
}

const cancel = () => {
  router.push('/companies')
}

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>Edit Company</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="Edit Users"
      form
      @submit.prevent="submit"
    >

      <FormField
        label="Name"
      >
        <FormControl
          v-model="form.name"
          placeholder="Name"
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
          placeholder="website"
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
