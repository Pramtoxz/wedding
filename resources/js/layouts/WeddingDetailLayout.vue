<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage()
const wedding = computed(() => page.props.wedding as any)
</script>

<template>
  <AppLayout>
    <slot />
  </AppLayout>
</template>
