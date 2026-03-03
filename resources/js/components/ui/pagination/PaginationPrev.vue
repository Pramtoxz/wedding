<template>
  <PaginationLink
    aria-label="Go to previous page"
    :size="size"
    :class="cn($attrs.class ?? '')"
    v-bind="$attrs"
  >
    <slot>
      <ChevronLeft class="h-4 w-4" />
      <span>Previous</span>
    </slot>
  </PaginationLink>
</template>

<script setup lang="ts">
import { ChevronLeft } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import PaginationLink from './PaginationLink.vue'

interface Props {
  size?: 'default' | 'sm' | 'lg' | 'icon'
}

withDefaults(defineProps<Props>(), {
  size: 'default',
})
</script>
