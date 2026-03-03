<template>
  <div v-if="show" class="fixed top-4 right-4 z-50 animate-in slide-in-from-top-2">
    <div class="bg-background border rounded-lg shadow-lg p-4 max-w-md">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <CheckCircle v-if="type === 'success'" class="w-5 h-5 text-green-600" />
          <AlertCircle v-else-if="type === 'error'" class="w-5 h-5 text-red-600" />
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium">{{ message }}</p>
        </div>
        <button @click="close" class="flex-shrink-0 text-muted-foreground hover:text-foreground">
          <X class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { CheckCircle, AlertCircle, X } from 'lucide-vue-next'

const page = usePage()
const show = ref(false)
const message = ref('')
const type = ref<'success' | 'error'>('success')

watch(() => page.props.flash, (flash: any) => {
  if (flash?.success) {
    message.value = flash.success
    type.value = 'success'
    show.value = true
    setTimeout(() => {
      show.value = false
    }, 3000)
  } else if (flash?.error) {
    message.value = flash.error
    type.value = 'error'
    show.value = true
    setTimeout(() => {
      show.value = false
    }, 3000)
  }
}, { deep: true, immediate: true })

const close = () => {
  show.value = false
}
</script>
