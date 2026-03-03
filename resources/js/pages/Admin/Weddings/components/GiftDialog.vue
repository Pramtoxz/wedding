<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Tambah Rekening Hadiah</DialogTitle>
      </DialogHeader>
      
      <form @submit.prevent="submit" class="space-y-4">
        <div class="space-y-2">
          <Label for="bank_name">Nama Bank / E-Wallet *</Label>
          <Input id="bank_name" v-model="form.bank_name" placeholder="BCA / GoPay / OVO" required />
          <InputError :message="form.errors.bank_name" />
        </div>

        <div class="space-y-2">
          <Label for="account_number">Nomor Rekening *</Label>
          <Input id="account_number" v-model="form.account_number" placeholder="1234567890" required />
        </div>

        <div class="space-y-2">
          <Label for="account_name">Nama Pemilik Rekening *</Label>
          <Input id="account_name" v-model="form.account_name" placeholder="Budi Santoso" required />
        </div>

        <div class="space-y-2">
          <Label for="qr_code">QR Code (Opsional)</Label>
          <Input id="qr_code" type="file" accept="image/*" @change="handleFileChange" />
          <p class="text-xs text-muted-foreground">Upload QR code untuk kemudahan pembayaran</p>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="$emit('update:open', false)">Batal</Button>
          <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            Tambah Rekening
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
  bank_name: '',
  account_number: '',
  account_name: '',
  qr_code: null as File | null,
  order: 0,
})

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.qr_code = target.files[0]
  }
}

const submit = () => {
  form.post(`/admin/weddings/${props.weddingId}/gifts`, {
    onSuccess: () => {
      form.reset()
      emit('update:open', false)
    }
  })
}
</script>
