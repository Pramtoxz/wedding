<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Tambah Tamu</DialogTitle>
      </DialogHeader>
      
      <form @submit.prevent="submit" class="space-y-4">
        <div class="space-y-2">
          <Label for="name">Nama Tamu *</Label>
          <Input id="name" v-model="form.name" placeholder="Budi Santoso" required />
          <InputError :message="form.errors.name" />
        </div>

        <div class="space-y-2">
          <Label for="partner_name">Nama Keluarga/Partner</Label>
          <Input id="partner_name" v-model="form.partner_name" placeholder="& Keluarga" />
          <p class="text-xs text-muted-foreground">Opsional, akan ditampilkan di undangan</p>
          <InputError :message="form.errors.partner_name" />
        </div>

        <div class="space-y-2">
          <Label for="whatsapp_number">Nomor WhatsApp *</Label>
          <Input id="whatsapp_number" v-model="form.whatsapp_number" type="tel" placeholder="08123456789" required />
          <p class="text-xs text-muted-foreground">Untuk mengirim undangan via WhatsApp</p>
          <InputError :message="form.errors.whatsapp_number" />
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="$emit('update:open', false)">Batal</Button>
          <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            Tambah Tamu
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
  name: '',
  partner_name: '',
  whatsapp_number: '',
})

const submit = () => {
  form.post(`/admin/weddings/${props.weddingId}/guests`, {
    onSuccess: () => {
      form.reset()
      emit('update:open', false)
    }
  })
}
</script>
