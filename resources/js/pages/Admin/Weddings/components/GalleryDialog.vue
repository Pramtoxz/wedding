<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Tambah Foto</DialogTitle>
      </DialogHeader>
      
      <form @submit.prevent="submit" class="space-y-4">
        <div class="space-y-2">
          <Label for="images">Pilih Foto *</Label>
          <Input id="images" type="file" accept="image/*" multiple @change="handleFileChange" required />
          <p class="text-xs text-muted-foreground">Anda bisa pilih beberapa foto sekaligus</p>
          <InputError :message="form.errors.images" />
        </div>

        <div v-if="previews.length > 0" class="grid grid-cols-3 gap-2">
          <div v-for="(preview, index) in previews" :key="index" class="aspect-square">
            <img :src="preview" class="w-full h-full object-cover rounded-lg" />
          </div>
        </div>

        <DialogFooter>
          <Button type="button" variant="outline" @click="$emit('update:open', false)">Batal</Button>
          <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            Upload Foto
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
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
  images: [] as File[],
  captions: [] as string[],
})

const previews = ref<string[]>([])

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.images = Array.from(target.files)
    
    previews.value = []
    Array.from(target.files).forEach(file => {
      const reader = new FileReader()
      reader.onload = (e) => {
        if (e.target?.result) {
          previews.value.push(e.target.result as string)
        }
      }
      reader.readAsDataURL(file)
    })
  }
}

const submit = () => {
  form.post(`/admin/weddings/${props.weddingId}/galleries`, {
    onSuccess: () => {
      form.reset()
      previews.value = []
      emit('update:open', false)
    }
  })
}
</script>
