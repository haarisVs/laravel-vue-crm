<script setup>
import { computed } from 'vue'
import { useLayoutStore } from '@/stores/layout.js'
import BaseLevel from '@/components/BaseLevel.vue'

const year = computed(() => new Date().getFullYear())

const isFooterBarVisible = computed(() => !useLayoutStore().isFullScreen)
</script>

<template>
  <footer
    v-show="isFooterBarVisible"
    class="bg-pavitra-400 py-2 px-6 mt-auto flex justify-center"
  >
  </footer>
</template>
