<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
      <div class="mb-6">
        <h1 class="text-2xl font-bold">Kelola Tamu</h1>
        <p class="text-sm text-muted-foreground">Kelola semua tamu undangan Anda</p>
      </div>

      <div v-if="weddings.length === 0" class="text-center py-12">
        <p class="text-muted-foreground mb-4">Belum ada undangan. Buat undangan terlebih dahulu.</p>
        <Button as-child>
          <Link href="/admin/weddings/create">Buat Undangan</Link>
        </Button>
      </div>

      <div v-else class="space-y-6">
        <div class="flex gap-2">
          <div class="relative flex-1">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
              v-model="searchQuery" 
              placeholder="Cari nama atau nomor WhatsApp..." 
              class="pl-9"
              @input="handleSearch"
            />
          </div>
          <select 
            v-model="statusFilter"
            @change="handleFilterChange"
            class="px-3 py-2 border rounded-md text-sm bg-background"
          >
            <option value="all">Semua Status</option>
            <option value="sent">Terkirim</option>
            <option value="not_sent">Belum Terkirim</option>
          </select>
        </div>

        <Card v-for="wedding in weddings" :key="wedding.id">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div>
                <CardTitle>{{ wedding.bride_name }} & {{ wedding.groom_name }}</CardTitle>
                <CardDescription>{{ formatDate(wedding.wedding_date) }}</CardDescription>
              </div>
              <Button size="sm" @click="openDialog(wedding.id)">
                <Plus class="w-4 h-4 mr-2" />
                Tambah Tamu
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div v-if="wedding.guests.length === 0" class="text-center py-8 text-muted-foreground">
              {{ searchQuery ? 'Tidak ada tamu yang ditemukan' : 'Belum ada tamu' }}
            </div>
            <div v-else class="space-y-3">
              <div v-for="guest in paginatedGuests(wedding.guests)" :key="guest.id" class="p-3 border rounded-lg">
                <div class="flex items-start justify-between gap-3">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                      <h3 class="font-medium">{{ guest.name }}</h3>
                      <span 
                        v-if="guest.status_kirim" 
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
                      >
                        Terkirim
                      </span>
                    </div>
                    <p class="text-sm text-muted-foreground">{{ guest.whatsapp_number }}</p>
                  </div>
                  <div class="flex items-center gap-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input 
                        type="checkbox" 
                        :checked="guest.status_kirim"
                        @change="toggleStatus(wedding.id, guest.id)"
                        class="sr-only peer"
                      >
                      <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                    </label>
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="sendWhatsapp(wedding.id, guest.id)"
                    >
                      <MessageCircle class="w-4 h-4" />
                    </Button>
                    <Button variant="ghost" size="sm" @click="deleteGuest(wedding.id, guest.id)">
                      <Trash2 class="w-4 h-4 text-destructive" />
                    </Button>
                  </div>
                </div>
              </div>
              
              <div v-if="getTotalFilteredGuests(wedding.guests) > itemsPerPage" class="flex items-center justify-between pt-4 border-t">
                <p class="text-sm text-muted-foreground">
                  Menampilkan {{ getStartIndex(wedding.id) + 1 }} - {{ Math.min(getEndIndex(wedding.id), getTotalFilteredGuests(wedding.guests)) }} dari {{ getTotalFilteredGuests(wedding.guests) }} tamu
                </p>
                <Pagination>
                  <PaginationList>
                    <PaginationListItem>
                      <PaginationPrev 
                        :disabled="currentPages[wedding.id] === 1"
                        @click="previousPage(wedding.id)"
                      />
                    </PaginationListItem>
                    
                    <PaginationListItem v-for="page in getPageNumbers(wedding.id, getTotalFilteredGuests(wedding.guests))" :key="page">
                      <PaginationLink
                        v-if="page !== '...'"
                        :is-active="currentPages[wedding.id] === page"
                        @click="goToPage(wedding.id, page as number)"
                      >
                        {{ page }}
                      </PaginationLink>
                      <PaginationEllipsis v-else />
                    </PaginationListItem>
                    
                    <PaginationListItem>
                      <PaginationNext 
                        :disabled="getEndIndex(wedding.id) >= getTotalFilteredGuests(wedding.guests)"
                        @click="nextPage(wedding.id)"
                      />
                    </PaginationListItem>
                  </PaginationList>
                </Pagination>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <GuestDialog v-model:open="showDialog" :wedding-id="selectedWeddingId" />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Plus, Trash2, MessageCircle, Search } from 'lucide-vue-next'
import GuestDialog from '@/pages/Admin/Weddings/components/GuestDialog.vue'
import Pagination from '@/components/ui/pagination/Pagination.vue'
import PaginationList from '@/components/ui/pagination/PaginationList.vue'
import PaginationListItem from '@/components/ui/pagination/PaginationListItem.vue'
import PaginationLink from '@/components/ui/pagination/PaginationLink.vue'
import PaginationPrev from '@/components/ui/pagination/PaginationPrev.vue'
import PaginationNext from '@/components/ui/pagination/PaginationNext.vue'
import PaginationEllipsis from '@/components/ui/pagination/PaginationEllipsis.vue'

interface Guest {
  id: number
  name: string
  whatsapp_number: string
  status_kirim: boolean
}

interface Wedding {
  id: number
  bride_name: string
  groom_name: string
  wedding_date: string
  guests: Guest[]
}

const props = defineProps<{
  weddings: Wedding[]
  search?: string
}>()

const showDialog = ref(false)
const selectedWeddingId = ref(0)
const searchQuery = ref(props.search || '')
const statusFilter = ref('all')
const itemsPerPage = 10
const currentPages = reactive<Record<number, number>>({})

props.weddings.forEach(wedding => {
  currentPages[wedding.id] = 1
})

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

const handleSearch = () => {
  router.get('/admin/guests', { search: searchQuery.value }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const handleFilterChange = () => {
  // Filter will be applied client-side
}

const filteredGuests = (guests: Guest[]) => {
  if (statusFilter.value === 'all') return guests
  if (statusFilter.value === 'sent') return guests.filter(g => g.status_kirim)
  if (statusFilter.value === 'not_sent') return guests.filter(g => !g.status_kirim)
  return guests
}

const paginatedGuests = (guests: Guest[]) => {
  const weddingId = props.weddings.find(w => w.guests === guests)?.id
  if (!weddingId) return guests
  
  const filtered = filteredGuests(guests)
  const page = currentPages[weddingId] || 1
  const start = (page - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filtered.slice(start, end)
}

const getStartIndex = (weddingId: number) => {
  const page = currentPages[weddingId] || 1
  return (page - 1) * itemsPerPage
}

const getEndIndex = (weddingId: number) => {
  const page = currentPages[weddingId] || 1
  return page * itemsPerPage
}

const getTotalFilteredGuests = (guests: Guest[]) => {
  return filteredGuests(guests).length
}

const nextPage = (weddingId: number) => {
  currentPages[weddingId] = (currentPages[weddingId] || 1) + 1
}

const previousPage = (weddingId: number) => {
  if (currentPages[weddingId] > 1) {
    currentPages[weddingId]--
  }
}

const goToPage = (weddingId: number, page: number) => {
  currentPages[weddingId] = page
}

const getPageNumbers = (weddingId: number, totalGuests: number) => {
  const totalPages = Math.ceil(totalGuests / itemsPerPage)
  const currentPage = currentPages[weddingId] || 1
  const pages: (number | string)[] = []
  
  if (totalPages <= 7) {
    for (let i = 1; i <= totalPages; i++) {
      pages.push(i)
    }
  } else {
    if (currentPage <= 3) {
      pages.push(1, 2, 3, 4, '...', totalPages)
    } else if (currentPage >= totalPages - 2) {
      pages.push(1, '...', totalPages - 3, totalPages - 2, totalPages - 1, totalPages)
    } else {
      pages.push(1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages)
    }
  }
  
  return pages
}

const sendWhatsapp = async (weddingId: number, guestId: number) => {
  try {
    const response = await fetch(`/admin/weddings/${weddingId}/guests/${guestId}/whatsapp`)
    const data = await response.json()
    if (data.whatsapp_link) {
      window.open(data.whatsapp_link, '_blank')
      // Reload to update status
      router.reload({ only: ['weddings'] })
    }
  } catch (error) {
    console.error('Error sending WhatsApp:', error)
  }
}

const toggleStatus = (weddingId: number, guestId: number) => {
  router.patch(`/admin/weddings/${weddingId}/guests/${guestId}/toggle-status`, {}, {
    preserveScroll: true,
  })
}

const deleteGuest = (weddingId: number, guestId: number) => {
  Swal.fire({
    title: 'Hapus Tamu?',
    text: 'Data tamu akan dihapus permanen',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/weddings/${weddingId}/guests/${guestId}`, {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: 'Tamu berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
          })
        }
      })
    }
  })
}
</script>
