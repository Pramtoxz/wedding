<template>
  <Head>
    <title>{{ wedding.bride_name }} & {{ wedding.groom_name }} - Wedding Invitation</title>
    <meta name="description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" :content="`${wedding.bride_name} & ${wedding.groom_name}`" />
    <meta property="og:description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />
    <meta property="og:image" :content="wedding.cover_image ? `${appUrl}/storage/${wedding.cover_image}` : `${appUrl}/favicon.svg`" />
    <meta property="og:url" :content="`${appUrl}/invitation/${wedding.slug}`" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="`${wedding.bride_name} & ${wedding.groom_name}`" />
    <meta name="twitter:description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />
    <meta name="twitter:image" :content="wedding.cover_image ? `${appUrl}/storage/${wedding.cover_image}` : `${appUrl}/favicon.svg`" />
  </Head>

  <!-- Dynamic template rendering based on wedding.template -->
  <component
    :is="activeTemplate"
    :wedding="wedding"
    :guest="guest"
  />
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed, defineAsyncComponent } from 'vue'
import DefaultTemplate from './templates/DefaultTemplate.vue'

interface Wedding {
  id: number
  bride_name: string
  bride_father_name: string | null
  bride_mother_name: string | null
  bride_photo: string | null
  groom_name: string
  groom_father_name: string | null
  groom_mother_name: string | null
  groom_photo: string | null
  wedding_date: string
  cover_image: string | null
  cover_page_image: string | null
  opening_text: string | null
  closing_text: string | null
  music_url: string | null
  theme_primary_color: string
  theme_secondary_color: string
  theme_accent_color: string
  theme_font_family: string
  slug: string
  template: string
  events: any[]
  galleries: any[]
  gifts: any[]
  wishes: any[]
  rsvps: any[]
}

interface Guest {
  id: number
  name: string
  partner_name?: string
  display_name?: string
  invitation_code: string
}

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
}>()

const appUrl = computed(() => window.location.origin)

const VintageMinangTemplate = defineAsyncComponent(() => import('./templates/VintageMinangTemplate.vue'))

// Template registry - daftarkan semua template di sini
const templateRegistry: Record<string, any> = {
  default: DefaultTemplate,
  'vintage-minang': VintageMinangTemplate,
}

const activeTemplate = computed(() => {
  const templateKey = props.wedding.template || 'default'
  return templateRegistry[templateKey] || DefaultTemplate
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>
