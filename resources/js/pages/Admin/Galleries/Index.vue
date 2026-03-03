<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="mb-6">
        <h1 class="text-2xl font-bold">Kelola Galeri</h1>
        <p class="text-sm text-muted-foreground">Kelola semua foto pernikahan Anda</p>
      </div>

      <div v-if="weddings.length === 0" class="text-center py-12">
        <p class="text-muted-foreground mb-4">Belum ada undangan. Buat undangan terlebih dahulu.</p>
        <Button as-child>
          <Link href="/admin/weddings/create">Buat Undangan</Link>
        </Button>
      </div>

      <div v-else class="space-y-6">
        <Card v-for="wedding in weddings" :key="wedding.id">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div>
                <CardTitle>{{ wedding.bride_name }} & {{ wedding.groom_name }}</CardTitle>
                <CardDescription>{{ formatDate(wedding.wedding_date) }}</CardDescription>
              </div>
              <Button size="sm" @click="openDialog(wedding.id)">
                <Plus class="w-4 h-4 mr-2" />
                Tambah Foto
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div v-if="wedding.galleries.length === 0" class="text-center py-8 text-muted-foreground">
              Belum ada foto
            </div>
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
              <div v-for="gallery in wedding.galleries" :key="gallery.id" class="relative aspect-square group">
                <img :src="`/storage/${gallery.image_path}`" class="w-full h-full object-cover rounded-lg" />
                <Button 
                  variant="destructive" 
                  size="sm" 
                  class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity h-8 w-8 p-0"
                  @click="deleteGallery(wedding.id, gallery.id)"
                >
                  <Trash2 class="w-4 h-4" />
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <GalleryDialog v-model:open="showDialog" :wedding-id="selectedWeddingId" />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Plus, Trash2 } from 'lucide-vue-next'
import GalleryDialog from '@/pages/Admin/Weddings/components/GalleryDialog.vue'

interface Wedding {
  id: number
  bride_name: string
  groom_name: string
  wedding_date: string
  galleries: any[]
}

defineProps<{
  weddings: Wedding[]
}>()

const showDialog = ref(false)
const selectedWeddingId = ref(0)

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const openDialog = (weddingId: number) => {
  selectedWeddingId.value = weddingId
  showDialog.value = true
}

const deleteGallery = (weddingId: number, galleryId: number) => {
  if (confirm('Hapus foto ini?')) {
    router.delete(`/admin/weddings/${weddingId}/galleries/${galleryId}`)
  }
}
</script>
