<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Tambah Acara</DialogTitle>
      </DialogHeader>
      
      <form @submit.prevent="submit" class="space-y-4">
        <div class="space-y-2">
          <Label for="event_name">Nama Acara *</Label>
          <Input id="event_name" v-model="form.event_name" placeholder="Akad Nikah" required />
          <InputError :message="form.errors.event_name" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div class="space-y-2">
            <Label for="event_date">Tanggal *</Label>
            <Input id="event_date" v-model="form.event_date" type="date" required />
          </div>
          <div class="space-y-2">
            <Label for="event_time">Waktu *</Label>
            <Input id="event_time" v-model="form.event_time" type="time" required />
          </div>
        </div>

        <div class="space-y-2">
          <Label for="location_name">Nama Lokasi *</Label>
          <Input id="location_name" v-model="form.location_name" placeholder="Masjid Al-Ikhlas" required />
        </div>

        <div class="space-y-2">
          <Label for="address">Alamat *</Label>
          <textarea 
            id="address" 
            v-model="form.address" 
            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            required
          />
        </div>

        <div class="space-y-2">
          <Label for="map_url">URL Google Maps</Label>
          <Input id="map_url" v-model="form.map_url" type="url" placeholder="https://maps.google.com/..." />
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="$emit('update:open', false)">Batal</Button>
          <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            Tambah Acara
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import { Loader2 } from 'lucide-vue-next'

const props = defineProps<{
  open: boolean
  weddingId: number
}>()

const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

const form = useForm({
  event_name: '',
  event_date: '',
  event_time: '',
  location_name: '',
  address: '',
  map_url: '',
  order: 0,
})

const submit = () => {
  form.post(`/admin/weddings/${props.weddingId}/events`, {
    onSuccess: () => {
      form.reset()
      emit('update:open', false)
    }
  })
}
</script>
