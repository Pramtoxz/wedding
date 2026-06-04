<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage()
</script>

<template>
  <AppLayout>
    <slot />
  </AppLayout>
</template>
