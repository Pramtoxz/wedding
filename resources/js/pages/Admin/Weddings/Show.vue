<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="mb-6">
        <Button as-child variant="ghost" size="sm" class="mb-4">
          <Link href="/admin/weddings">
            <ArrowLeft class="w-4 h-4 mr-2" />
            Kembali
          </Link>
        </Button>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold">{{ wedding.bride_name }} & {{ wedding.groom_name }}</h1>
            <p class="text-sm text-muted-foreground">{{ formatDate(wedding.wedding_date) }}</p>
          </div>
          <div class="flex gap-2">
            <Button as-child variant="outline" size="sm">
              <Link :href="`/admin/weddings/${wedding.id}/edit`">
                <Edit class="w-4 h-4 mr-2" />
                Edit
              </Link>
            </Button>
            <Button variant="outline" size="sm" @click="shareWhatsapp">
              <Share2 class="w-4 h-4 mr-2" />
              Bagikan
            </Button>
          </div>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-6">
        <Card>
          <CardHeader class="pb-3">
            <CardTitle class="text-sm font-medium text-muted-foreground">Total Acara</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ wedding.events.length }}</div>
            <Button as-child variant="link" size="sm" class="px-0 mt-2">
              <Link href="/admin/events">Kelola Acara →</Link>
            </Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="pb-3">
            <CardTitle class="text-sm font-medium text-muted-foreground">Total Foto</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ wedding.galleries.length }}</div>
            <Button as-child variant="link" size="sm" class="px-0 mt-2">
              <Link href="/admin/galleries">Kelola Galeri →</Link>
            </Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="pb-3">
            <CardTitle class="text-sm font-medium text-muted-foreground">Rekening Hadiah</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ wedding.gifts.length }}</div>
            <Button as-child variant="link" size="sm" class="px-0 mt-2">
              <Link href="/admin/gifts">Kelola Rekening →</Link>
            </Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="pb-3">
            <CardTitle class="text-sm font-medium text-muted-foreground">Konfirmasi</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ wedding.rsvps.length }}</div>
            <p class="text-xs text-muted-foreground mt-2">Total tamu yang konfirmasi</p>
          </CardContent>
        </Card>
      </div>

      <div class="grid gap-6 lg:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>Informasi Undangan</CardTitle>
          </CardHeader>
          <CardContent class="space-y-3">
            <div>
              <p class="text-sm font-medium text-muted-foreground">Link Undangan</p>
              <div class="flex gap-2 mt-1">
                <Input :value="invitationUrl" readonly class="flex-1" />
                <Button variant="outline" size="sm" @click="copyLink">
                  <Copy class="w-4 h-4" />
                </Button>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Status</p>
              <Badge :variant="wedding.is_active ? 'default' : 'secondary'" class="mt-1">
                {{ wedding.is_active ? 'Aktif' : 'Nonaktif' }}
              </Badge>
            </div>
            <div v-if="wedding.cover_image">
              <p class="text-sm font-medium text-muted-foreground mb-2">Foto Cover</p>
              <img :src="`/storage/${wedding.cover_image}`" class="w-full max-w-xs rounded-lg" />
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
              <div v-if="wedding.bride_photo">
                <p class="text-sm font-medium text-muted-foreground mb-2">Foto Mempelai Wanita</p>
                <img :src="`/storage/${wedding.bride_photo}`" class="w-full rounded-lg" />
              </div>
              <div v-if="wedding.groom_photo">
                <p class="text-sm font-medium text-muted-foreground mb-2">Foto Mempelai Pria</p>
                <img :src="`/storage/${wedding.groom_photo}`" class="w-full rounded-lg" />
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Ucapan & Doa ({{ wedding.wishes.length }})</CardTitle>
          </CardHeader>
          <CardContent>
            <div v-if="wedding.wishes.length === 0" class="text-center py-8 text-muted-foreground">
              Belum ada ucapan
            </div>
            <div v-else class="space-y-3 max-h-96 overflow-y-auto">
              <div v-for="wish in wedding.wishes" :key="wish.id" class="p-3 border rounded-lg">
                <p class="font-medium text-sm">{{ wish.guest_name }}</p>
                <p class="text-sm text-muted-foreground mt-1">{{ wish.message }}</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { ArrowLeft, Edit, Share2, Copy } from 'lucide-vue-next'

interface Wedding {
  id: number
  slug: string
  bride_name: string
  groom_name: string
  wedding_date: string
  cover_image: string | null
  bride_photo: string | null
  groom_photo: string | null
  is_active: boolean
  events: any[]
  galleries: any[]
  rsvps: any[]
  wishes: any[]
  gifts: any[]
}

const props = defineProps<{
  wedding: Wedding
}>()

const invitationUrl = computed(() => {
  return `${window.location.origin}/invitation/${props.wedding.slug}`
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const shareWhatsapp = async () => {
  const response = await fetch(`/admin/weddings/${props.wedding.id}/whatsapp`)
  const data = await response.json()
  window.open(data.whatsapp_link, '_blank')
}

const copyLink = () => {
  navigator.clipboard.writeText(invitationUrl.value)
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Link berhasil disalin!',
    timer: 2000,
    showConfirmButton: false,
    toast: true,
    position: 'top-end'
  })
}
</script>
