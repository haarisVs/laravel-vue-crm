<script setup>
import { ref, reactive, onBeforeMount, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
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
import { notify } from "@kyvg/vue3-notification";
import Editor from '@tinymce/tinymce-vue';
import dataFormatter from '@/helpers/dataFormatter';

const router = useRouter();
const route = useRoute();
const postsStore = usePostsStore()

const titleStack = ref(['Admin', 'Posts'])
const notification = computed(() => postsStore.notify)

        const optionsUser_id = computed(() => postsStore.searchResultUser_id);

const form = reactive({

    title: '',

      image: [],

    content: [''],

      user_id: '',

})

const postsItem = computed(() => postsStore.data);

const submit = async () => {
  try {

            form.user_id = form.user_id?.id;

    await postsStore.edit({id: route.params.id, data: {...form} })
    router.push('/posts');
  } catch (e) {
    console.log(e);
  }
}

onBeforeMount(async () => {
  try {

  await searchUser_id();

    await postsStore.fetch(route.params.id)
    formatData();
  } catch (e) {
    console.log(e)
    postsStore.showNotification(e, 'error');
  }
})

    async function searchUser_id(val) {
      await postsStore.searchUser_id(val);
    }

const formatData = () => {

    form.title = postsItem.value.title

    form.image = postsItem.value.image

    form.content = postsItem.value.content

    form.user_id = dataFormatter.usersOneListFormatterEdit(postsItem.value.user_id)

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

const reset = () => {
  formatData();
}

const cancel = () => {
  router.push('/posts')
}

</script>

<template>
  <SectionTitleBar :title-stack="titleStack" />
  <SectionHeroBar>Edit Posts</SectionHeroBar>

  <SectionMain>
    <CardBox
      title="Edit Posts"
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
