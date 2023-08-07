<script setup>
import { ref, reactive, computed, watch, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { useCategoriesStore } from "@/stores/Categories/categories";
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

const categoriesStore = useCategoriesStore()
const router = useRouter();

const notification = computed(() => categoriesStore.notify)
const titleStack = ref(['Admin', 'Categories'])

const form = reactive({

      title: '',

    content: '',

      status: false,

})

onBeforeMount(async () => {

})

const submit = async () => {
  try {

    await categoriesStore.newItem({ ...form })
    router.push('/categories');
  } catch (e) {
    console.log(e);
  }
}

const reset = () => {

        form.title = '';

      form.content = '';

        form.status = false;

}

const cancel = () => {
  router.push('/users')
}

watch(() => categoriesStore.notify.showNotification, (newValue, oldValue) => {
  if(newValue){
    notify({
      title: "Categories notification",
      text: notification.value.textNotification,
      type: notification.value.typeNotification,
    });
    categoriesStore.hideNotification()
  }
});

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>New Categories</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="New Categories"
      form
      @submit.prevent="submit"
    >

    <FormField
      label="Title"
    >
      <FormControl
        v-model="form.title"
        placeholder="Your Title"
      />
    </FormField>

    <label class="block font-bold mb-2 text-pavitra-700 text-sm">Content</label>
    <Editor
      api-key="s0bs8snu2u6qo8skn5r3kurkerhbaagpsgm9cdkbxnbo8nj4"
      cloudChannel="6"
      v-model="form.content"
      />

    <FormField
      label="Status"
      wrap-body
    >
      <FormCheckRadioPicker
        v-model="form.status"
        name="status"
        type="radio"

        :options="{ 'Active': 'Active', 'In Active': 'In Active', }"

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
