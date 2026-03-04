<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="mb-6">
        <h1 class="text-2xl font-bold">Kelola Acara</h1>
        <p class="text-sm text-muted-foreground">Kelola semua acara pernikahan Anda</p>
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
                Tambah Acara
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div v-if="wedding.events.length === 0" class="text-center py-8 text-muted-foreground">
              Belum ada acara
            </div>
            <div v-else class="space-y-3">
              <div v-for="event in wedding.events" :key="event.id" class="p-3 border rounded-lg">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="font-medium">{{ event.event_name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ formatDateTime(event.event_date, event.event_time) }}</p>
                    <p class="text-sm text-muted-foreground">{{ event.location_name }}</p>
                    <p class="text-sm text-muted-foreground">{{ event.address }}</p>
                  </div>
                  <Button variant="ghost" size="sm" @click="deleteEvent(wedding.id, event.id)">
                    <Trash2 class="w-4 h-4 text-destructive" />
                  </Button>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <EventDialog v-model:open="showDialog" :wedding-id="selectedWeddingId" />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Plus, Trash2 } from 'lucide-vue-next'
import EventDialog from '@/pages/Admin/Weddings/components/EventDialog.vue'

interface Wedding {
  id: number
  bride_name: string
  groom_name: string
  wedding_date: string
  events: any[]
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

const formatDateTime = (date: string, time: string) => {
  return `${formatDate(date)} - ${time}`
}

const openDialog = (weddingId: number) => {
  selectedWeddingId.value = weddingId
  showDialog.value = true
}

const deleteEvent = (weddingId: number, eventId: number) => {
  Swal.fire({
    title: 'Hapus Acara?',
    text: 'Data acara akan dihapus permanen',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/weddings/${weddingId}/events/${eventId}`, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Acara berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
          })
        }
      })
    }
  })
}
</script>
