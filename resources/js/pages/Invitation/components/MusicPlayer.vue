<template>
  <button
    @click="$emit('toggle')"
    class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-[var(--primary-color)] text-white shadow-lg flex items-center justify-center hover:scale-110 transition-transform"
  >
    <Volume2 v-if="isPlaying" class="w-6 h-6" />
    <VolumeX v-else class="w-6 h-6" />
  </button>
</template>

<script setup lang="ts">
import { Volume2, VolumeX } from 'lucide-vue-next'

defineProps<{
  isPlaying: boolean
}>()

defineEmits<{
  toggle: []
}>()
</script>
