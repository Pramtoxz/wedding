<template>
  <component
    :is="isActive ? 'span' : 'button'"
    :aria-current="isActive ? 'page' : undefined"
    :class="cn(
      buttonVariants({
        variant: isActive ? 'outline' : 'ghost',
        size: size,
      }),
      $attrs.class ?? ''
    )"
    v-bind="$attrs"
  >
    <slot />
  </component>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/components/ui/button'

interface Props {
  isActive?: boolean
  size?: 'default' | 'sm' | 'lg' | 'icon'
}

withDefaults(defineProps<Props>(), {
  isActive: false,
  size: 'icon',
})
</script>
