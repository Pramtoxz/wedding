<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold">Undangan Saya</h1>
          <p class="text-sm text-muted-foreground">Kelola undangan pernikahan Anda</p>
        </div>
        <Button as-child>
          <Link href="/admin/weddings/create">
            <Plus class="w-4 h-4 mr-2" />
            Buat Undangan
          </Link>
        </Button>
      </div>

      <div v-if="weddings.length === 0" class="text-center py-12">
        <p class="text-muted-foreground mb-4">Belum ada undangan</p>
        <Button as-child>
          <Link href="/admin/weddings/create">Buat Undangan Pertama</Link>
        </Button>
      </div>

      <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <Card v-for="wedding in weddings" :key="wedding.id" class="overflow-hidden">
          <div v-if="wedding.cover_image" class="aspect-video w-full overflow-hidden">
            <img :src="`/storage/${wedding.cover_image}`" :alt="wedding.bride_name" class="w-full h-full object-cover" />
          </div>
          <div v-else class="aspect-video w-full bg-muted flex items-center justify-center">
            <Heart class="w-12 h-12 text-muted-foreground" />
          </div>
          
          <CardHeader>
            <CardTitle class="text-lg">{{ wedding.bride_name }} & {{ wedding.groom_name }}</CardTitle>
            <CardDescription>{{ formatDate(wedding.wedding_date) }}</CardDescription>
          </CardHeader>

          <CardContent class="space-y-2">
            <div class="flex items-center gap-2 text-sm">
              <Users class="w-4 h-4 text-muted-foreground" />
              <span>{{ wedding.guests?.length || 0 }} Tamu</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
              <CheckCircle class="w-4 h-4 text-muted-foreground" />
              <span>{{ wedding.rsvps?.length || 0 }} Konfirmasi</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
              <MessageCircle class="w-4 h-4 text-muted-foreground" />
              <span>{{ wedding.wishes?.length || 0 }} Ucapan</span>
            </div>
          </CardContent>

          <CardFooter class="flex gap-2">
            <Button as-child variant="outline" size="sm" class="flex-1">
              <Link :href="`/admin/weddings/${wedding.id}`">
                <Eye class="w-4 h-4 mr-2" />
                Lihat
              </Link>
            </Button>
            <Button as-child variant="outline" size="sm" class="flex-1">
              <Link :href="`/admin/weddings/${wedding.id}/edit`">
                <Edit class="w-4 h-4 mr-2" />
                Edit
              </Link>
            </Button>
          </CardFooter>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Plus, Heart, Users, CheckCircle, MessageCircle, Eye, Edit } from 'lucide-vue-next'

interface Wedding {
  id: number
  bride_name: string
  groom_name: string
  wedding_date: string
  cover_image: string | null
  guests?: any[]
  rsvps?: any[]
  wishes?: any[]
}

defineProps<{
  weddings: Wedding[]
}>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>
