<script setup>
import SectionFullScreen from '@/components/SectionFullScreen.vue'
import CardBox from '@/components/CardBox.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'

</script>

<template>
  <SectionFullScreen
    v-slot="{ cardClass, cardRounded }"
    bg="login"
  >
    <CardBox
      :class="[cardClass, 'text-center p-10']"
      :rounded="cardRounded"
      form
      @submit.prevent="submit"
    >

      <h2 class="text-2xl mb-5">
      </h2>


      <BaseButtons class="justify-center mb-4">
        <BaseButton
          to="/login"
          color="default"
          label="Login"
          full
        />
      </BaseButtons>

    </CardBox>
  </SectionFullScreen>
</template>
