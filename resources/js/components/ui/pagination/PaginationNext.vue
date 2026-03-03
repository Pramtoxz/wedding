<template>
  <PaginationLink
    aria-label="Go to next page"
    :size="size"
    :class="cn($attrs.class ?? '')"
    v-bind="$attrs"
  >
    <slot>
      <span>Next</span>
      <ChevronRight class="h-4 w-4" />
    </slot>
  </PaginationLink>
</template>

<script setup lang="ts">
import { ChevronRight } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import PaginationLink from './PaginationLink.vue'

interface Props {
  size?: 'default' | 'sm' | 'lg' | 'icon'
}

withDefaults(defineProps<Props>(), {
  size: 'default',
})
</script>
