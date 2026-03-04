<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="mb-6">
        <h1 class="text-2xl font-bold">Kelola Rekening Hadiah</h1>
        <p class="text-sm text-muted-foreground">Kelola semua rekening bank/e-wallet untuk hadiah</p>
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
                Tambah Rekening
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div v-if="wedding.gifts.length === 0" class="text-center py-8 text-muted-foreground">
              Belum ada rekening
            </div>
            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
              <div v-for="gift in wedding.gifts" :key="gift.id" class="p-4 border rounded-lg">
                <div class="flex items-start justify-between mb-3">
                  <div class="flex-1">
                    <h3 class="font-medium">{{ gift.bank_name }}</h3>
                    <p class="text-sm text-muted-foreground mt-1">{{ gift.account_number }}</p>
                    <p class="text-sm text-muted-foreground">{{ gift.account_name }}</p>
                  </div>
                  <Button variant="ghost" size="sm" @click="deleteGift(wedding.id, gift.id)">
                    <Trash2 class="w-4 h-4 text-destructive" />
                  </Button>
                </div>
                <div v-if="gift.qr_code_path" class="mt-3">
                  <img :src="`/storage/${gift.qr_code_path}`" class="w-32 h-32 object-cover rounded-lg" />
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <GiftDialog v-model:open="showDialog" :wedding-id="selectedWeddingId" />
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
import GiftDialog from '@/pages/Admin/Weddings/components/GiftDialog.vue'

interface Wedding {
  id: number
  bride_name: string
  groom_name: string
  wedding_date: string
  gifts: any[]
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

const deleteGift = (weddingId: number, giftId: number) => {
  Swal.fire({
    title: 'Hapus Rekening?',
    text: 'Data rekening akan dihapus permanen',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/weddings/${weddingId}/gifts/${giftId}`, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Rekening berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
          })
        }
      })
    }
  })
}
</script>
