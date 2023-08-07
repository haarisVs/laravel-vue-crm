<script setup>
import { ref, reactive, computed, watch, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { usePostsStore } from "@/stores/Posts/posts";
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

const postsStore = usePostsStore()
const router = useRouter();

const notification = computed(() => postsStore.notify)
const titleStack = ref(['Admin', 'Posts'])

        const optionsUser_id = computed(() => postsStore.searchResultUser_id);

const form = reactive({

      title: '',

      image: [],

    content: '',

      user_id: '',

})

onBeforeMount(async () => {

  await searchUser_id();

})

const submit = async () => {
  try {

            form.user_id = form.user_id.id;

    await postsStore.newItem({ ...form })
    router.push('/posts');
  } catch (e) {
    console.log(e);
  }
}

const reset = () => {

        form.title = '';

        form.image = [];

      form.content = '';

        form.user_id = '';

}

const cancel = () => {
  router.push('/users')
}

    async function searchUser_id(val) {
      await postsStore.searchUser_id(val);
    }

watch(() => postsStore.notify.showNotification, (newValue, oldValue) => {
  if(newValue){
    notify({
      title: "Posts notification",
      text: notification.value.textNotification,
      type: notification.value.typeNotification,
    });
    postsStore.hideNotification()
  }
});

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>New Posts</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="New Posts"
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

    <FormField
      label="Image"
    >
      <FormFilePicker v-model="form.image" url="posts/image"/>
    </FormField>

    <label class="block font-bold mb-2 text-pavitra-700 text-sm">Content</label>
    <Editor
      api-key="s0bs8snu2u6qo8skn5r3kurkerhbaagpsgm9cdkbxnbo8nj4"
      cloudChannel="6"
      v-model="form.content"
      />

  <FormField
      label="User Id"
    >
        <v-select
          v-model="form.user_id"
          :options="optionsUser_id"
          @input="searchUser_id($event.target.value)"
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
