<template>
  <nav role="navigation" aria-label="pagination" :class="cn('mx-auto flex w-full justify-center', $attrs.class ?? '')">
    <slot />
  </nav>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils'
</script>
