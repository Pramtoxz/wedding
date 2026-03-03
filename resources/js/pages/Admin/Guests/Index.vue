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
                    <h3 class="font-medium">{{ guest.name }}</h3>
                    <p class="text-sm text-muted-foreground mt-1">{{ guest.whatsapp_number }}</p>
                  </div>
                  <div class="flex gap-2">
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
              
              <div v-if="wedding.guests.length > itemsPerPage" class="flex items-center justify-between pt-4 border-t">
                <p class="text-sm text-muted-foreground">
                  Menampilkan {{ getStartIndex(wedding.id) + 1 }} - {{ Math.min(getEndIndex(wedding.id), wedding.guests.length) }} dari {{ wedding.guests.length }} tamu
                </p>
                <Pagination>
                  <PaginationList>
                    <PaginationListItem>
                      <PaginationPrev 
                        :disabled="currentPages[wedding.id] === 1"
                        @click="previousPage(wedding.id)"
                      />
                    </PaginationListItem>
                    
                    <PaginationListItem v-for="page in getPageNumbers(wedding.id, wedding.guests.length)" :key="page">
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
                        :disabled="getEndIndex(wedding.id) >= wedding.guests.length"
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

const paginatedGuests = (guests: Guest[]) => {
  const weddingId = props.weddings.find(w => w.guests === guests)?.id
  if (!weddingId) return guests
  
  const page = currentPages[weddingId] || 1
  const start = (page - 1) * itemsPerPage
  const end = start + itemsPerPage
  return guests.slice(start, end)
}

const getStartIndex = (weddingId: number) => {
  const page = currentPages[weddingId] || 1
  return (page - 1) * itemsPerPage
}

const getEndIndex = (weddingId: number) => {
  const page = currentPages[weddingId] || 1
  return page * itemsPerPage
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
    }
  } catch (error) {
    console.error('Error sending WhatsApp:', error)
  }
}

const deleteGuest = (weddingId: number, guestId: number) => {
  if (confirm('Hapus tamu ini?')) {
    router.delete(`/admin/weddings/${weddingId}/guests/${guestId}`)
  }
}
</script>
