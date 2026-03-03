<template>
  <AppLayout>
    <div class="container mx-auto px-4 py-6 max-w-3xl">
      <div class="mb-6">
        <Button as-child variant="ghost" size="sm" class="mb-4">
          <Link href="/admin/weddings">
            <ArrowLeft class="w-4 h-4 mr-2" />
            Kembali
          </Link>
        </Button>
        <h1 class="text-2xl font-bold">Buat Undangan Pernikahan</h1>
        <p class="text-sm text-muted-foreground">Isi detail undangan pernikahan Anda</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle>Informasi Mempelai</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="space-y-2">
              <Label for="bride_name">Nama Mempelai Wanita *</Label>
              <Input id="bride_name" v-model="form.bride_name" required />
              <InputError :message="form.errors.bride_name" />
            </div>

            <div class="space-y-2">
              <Label for="bride_photo">Foto Mempelai Wanita</Label>
              <Input id="bride_photo" type="file" accept="image/*" @change="handleBridePhotoChange" />
              <InputError :message="form.errors.bride_photo" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="space-y-2">
                <Label for="bride_father_name">Nama Ayah Mempelai Wanita</Label>
                <Input id="bride_father_name" v-model="form.bride_father_name" />
              </div>
              <div class="space-y-2">
                <Label for="bride_mother_name">Nama Ibu Mempelai Wanita</Label>
                <Input id="bride_mother_name" v-model="form.bride_mother_name" />
              </div>
            </div>

            <div class="space-y-2">
              <Label for="groom_name">Nama Mempelai Pria *</Label>
              <Input id="groom_name" v-model="form.groom_name" required />
              <InputError :message="form.errors.groom_name" />
            </div>

            <div class="space-y-2">
              <Label for="groom_photo">Foto Mempelai Pria</Label>
              <Input id="groom_photo" type="file" accept="image/*" @change="handleGroomPhotoChange" />
              <InputError :message="form.errors.groom_photo" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="space-y-2">
                <Label for="groom_father_name">Nama Ayah Mempelai Pria</Label>
                <Input id="groom_father_name" v-model="form.groom_father_name" />
              </div>
              <div class="space-y-2">
                <Label for="groom_mother_name">Nama Ibu Mempelai Pria</Label>
                <Input id="groom_mother_name" v-model="form.groom_mother_name" />
              </div>
            </div>

            <div class="space-y-2">
              <Label for="wedding_date">Tanggal Pernikahan *</Label>
              <Input id="wedding_date" v-model="form.wedding_date" type="date" required />
              <InputError :message="form.errors.wedding_date" />
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Konten Undangan</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="space-y-2">
              <Label for="cover_image">Foto Cover</Label>
              <Input id="cover_image" type="file" accept="image/*" @change="handleFileChange" />
              <InputError :message="form.errors.cover_image" />
            </div>

            <div class="space-y-2">
              <Label for="opening_text">Teks Pembuka</Label>
              <textarea 
                id="opening_text" 
                v-model="form.opening_text" 
                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Bismillahirrahmanirrahim..."
              />
            </div>

            <div class="space-y-2">
              <Label for="closing_text">Teks Penutup</Label>
              <textarea 
                id="closing_text" 
                v-model="form.closing_text" 
                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Wassalamualaikum Wr. Wb."
              />
            </div>

            <div class="space-y-2">
              <Label for="music_url">Musik Latar</Label>
              <div class="flex gap-2">
                <select 
                  id="music_url" 
                  v-model="form.music_url" 
                  class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                >
                  <option value="">Pilih Musik</option>
                  <option value="/music/wedding-1.mp3">Die With A Smile</option>
                  <option value="/music/wedding-2.mp3">The 1975</option>
                  <option value="/music/wedding-3.mp3">Blue</option>
                  <option value="/music/wedding-4.mp3">Lover</option>
                  <option value="/music/wedding-5.mp3">A Thousand Years</option>
                  <option value="/music/wedding-6.mp3">Until I Found You</option>
                </select>
                <Button 
                  type="button" 
                  variant="outline" 
                  size="sm"
                  @click="toggleMusic"
                  :disabled="!form.music_url"
                >
                  {{ isPlaying ? 'Stop' : 'Test' }}
                </Button>
              </div>
              <audio ref="audioPlayer" :src="form.music_url" @ended="isPlaying = false" />
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Kustomisasi Tema</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-3">
              <div class="space-y-2">
                <Label for="theme_primary_color">Warna Utama</Label>
                <div class="flex gap-2">
                  <Input id="theme_primary_color" v-model="form.theme_primary_color" type="color" class="w-16 h-10" />
                  <Input v-model="form.theme_primary_color" placeholder="#8B4513" />
                </div>
              </div>
              <div class="space-y-2">
                <Label for="theme_secondary_color">Warna Sekunder</Label>
                <div class="flex gap-2">
                  <Input id="theme_secondary_color" v-model="form.theme_secondary_color" type="color" class="w-16 h-10" />
                  <Input v-model="form.theme_secondary_color" placeholder="#D2691E" />
                </div>
              </div>
              <div class="space-y-2">
                <Label for="theme_accent_color">Warna Aksen</Label>
                <div class="flex gap-2">
                  <Input id="theme_accent_color" v-model="form.theme_accent_color" type="color" class="w-16 h-10" />
                  <Input v-model="form.theme_accent_color" placeholder="#F4A460" />
                </div>
              </div>
            </div>

            <div class="space-y-2">
              <Label for="theme_font_family">Font</Label>
              <select 
                id="theme_font_family" 
                v-model="form.theme_font_family" 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              >
                <option value="Inter">Inter</option>
                <option value="Poppins">Poppins</option>
                <option value="Playfair Display">Playfair Display</option>
                <option value="Great Vibes">Great Vibes</option>
                <option value="Cormorant Garamond">Cormorant Garamond</option>
                <option value="Crimson Text">Crimson Text</option>
                <option value="Lora">Lora</option>
                <option value="Merriweather">Merriweather</option>
              </select>
              <div 
                class="p-4 border rounded-lg text-center"
                :style="{ fontFamily: form.theme_font_family }"
              >
                <p class="text-2xl mb-2">{{ form.bride_name || 'Nama Mempelai Wanita' }} & {{ form.groom_name || 'Nama Mempelai Pria' }}</p>
                <p class="text-sm text-muted-foreground">Preview font yang dipilih</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="flex gap-2">
          <Button type="submit" :disabled="form.processing" class="flex-1">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            Buat Undangan
          </Button>
          <Button as-child variant="outline" type="button">
            <Link href="/admin/weddings">Batal</Link>
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import { ArrowLeft, Loader2 } from 'lucide-vue-next'

const form = useForm({
  bride_name: '',
  bride_father_name: '',
  bride_mother_name: '',
  bride_photo: null as File | null,
  groom_name: '',
  groom_father_name: '',
  groom_mother_name: '',
  groom_photo: null as File | null,
  wedding_date: '',
  cover_image: null as File | null,
  opening_text: '',
  closing_text: '',
  music_url: '',
  theme_primary_color: '#8B4513',
  theme_secondary_color: '#D2691E',
  theme_accent_color: '#F4A460',
  theme_font_family: 'Inter',
})

const audioPlayer = ref<HTMLAudioElement | null>(null)
const isPlaying = ref(false)

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.cover_image = target.files[0]
  }
}

const handleBridePhotoChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.bride_photo = target.files[0]
  }
}

const handleGroomPhotoChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.groom_photo = target.files[0]
  }
}

const toggleMusic = () => {
  if (!audioPlayer.value) return
  
  if (isPlaying.value) {
    audioPlayer.value.pause()
    audioPlayer.value.currentTime = 0
    isPlaying.value = false
  } else {
    audioPlayer.value.play()
    isPlaying.value = true
  }
}

const submit = () => {
  form.post('/admin/weddings')
}
</script>
