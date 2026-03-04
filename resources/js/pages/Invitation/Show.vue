<template>
  <Head>
    <title>{{ wedding.bride_name }} & {{ wedding.groom_name }} - Wedding Invitation</title>
    <meta name="description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" :content="`${wedding.bride_name} & ${wedding.groom_name}`" />
    <meta property="og:description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />
    <meta property="og:image" :content="wedding.cover_image ? `${appUrl}/storage/${wedding.cover_image}` : `${appUrl}/favicon.svg`" />
    <meta property="og:url" :content="`${appUrl}/invitation/${wedding.slug}`" />
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="`${wedding.bride_name} & ${wedding.groom_name}`" />
    <meta name="twitter:description" :content="`Undangan Pernikahan ${wedding.bride_name} & ${wedding.groom_name} - ${formatDate(wedding.wedding_date)}`" />
    <meta name="twitter:image" :content="wedding.cover_image ? `${appUrl}/storage/${wedding.cover_image}` : `${appUrl}/favicon.svg`" />
  </Head>
  
  <div 
    class="min-h-screen overflow-x-hidden"
    :style="{
      fontFamily: wedding.theme_font_family,
      '--primary-color': wedding.theme_primary_color,
      '--secondary-color': wedding.theme_secondary_color,
      '--accent-color': wedding.theme_accent_color,
    }"
  >
    <audio v-if="wedding.music_url" ref="audioPlayer" :src="wedding.music_url" loop />
    
    <CoverPage
      v-if="!isOpened"
      :bride-name="wedding.bride_name"
      :groom-name="wedding.groom_name"
      :guest-name="guest?.display_name || guest?.name"
      @open="openInvitation"
    />

    <div v-show="isOpened" class="relative pb-20">
      <MusicPlayer :is-playing="isMusicPlaying" @toggle="toggleMusic" />
      <BottomNav :primary-color="wedding.theme_primary_color" />

      <section ref="heroRef" class="section-animate" id="hero">
        <HeroSection
          :bride-name="wedding.bride_name"
          :groom-name="wedding.groom_name"
          :cover-image="wedding.cover_image"
          :opening-text="wedding.opening_text"
          :formatted-date="formatDate(wedding.wedding_date)"
          :wedding-date="wedding.wedding_date"
        />
      </section>

      <svg class="w-full h-16 -mt-1" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor" class="text-gray-100"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor" class="text-gray-100"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor" class="text-gray-100"></path>
      </svg>

      <section ref="coupleRef" class="section-animate" id="couple">
        <CoupleSection
          :bride-name="wedding.bride_name"
          :bride-father-name="wedding.bride_father_name"
          :bride-mother-name="wedding.bride_mother_name"
          :bride-photo="wedding.bride_photo"
          :groom-name="wedding.groom_name"
          :groom-father-name="wedding.groom_father_name"
          :groom-mother-name="wedding.groom_mother_name"
          :groom-photo="wedding.groom_photo"
        />
      </section>

      <svg class="w-full h-16 -mt-1 rotate-180" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="currentColor" class="text-white"></path>
      </svg>

      <section ref="eventRef" class="section-animate" id="event">
        <EventSection v-if="wedding.events.length > 0" :events="wedding.events" />
      </section>

      <svg class="w-full h-16 -mt-1" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="currentColor" class="text-gray-50"></path>
      </svg>

      <section ref="galleryRef" class="section-animate" id="gallery">
        <GallerySection v-if="wedding.galleries.length > 0" :galleries="wedding.galleries" />
      </section>

      <svg class="w-full h-16 -mt-1 rotate-180" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" fill="currentColor" class="text-white"></path>
      </svg>

      <section ref="wishRef" class="section-animate" id="wish">
        <WishSection
          ref="wishSectionRef"
          :wishes="wedding.wishes"
          :guest-name="guest?.name"
          :processing="wishForm.processing"
          @submit="submitWish"
        />
      </section>

      <svg class="w-full h-16 -mt-1" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" :fill="`${wedding.theme_primary_color}40`"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" :fill="`${wedding.theme_primary_color}60`"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" :fill="wedding.theme_primary_color"></path>
      </svg>

      <section ref="giftRef" class="section-animate" id="gift">
        <GiftSection
          v-if="wedding.gifts.length > 0"
          :gifts="wedding.gifts"
          @copy="copyToClipboard"
        />
      </section>

      <QuranSection
        :bride-name="wedding.bride_name"
        :groom-name="wedding.groom_name"
        :primary-color="wedding.theme_primary_color"
        :closing-text="wedding.closing_text"
      />

      <footer class="relative py-8 px-6 bg-gradient-to-b from-gray-900 to-black text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-0 left-1/4 w-64 h-64 bg-purple-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-blue-500 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto text-center">
          <div class="mb-6">
            <p class="text-sm text-gray-400 mb-2">Made with Developers by</p>
            <a 
               href="https://www.instagram.com/pramuditometra/" 
              target="_blank"
              rel="noopener noreferrer"
              class="group inline-flex items-center gap-3 px-6 py-3 bg-white text-gray-900 rounded-full hover:bg-gray-100 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-2xl"
            >
              <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span class="font-semibold">Pramudito Metra</span>
              <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
          
          <div class="pt-6 border-t border-gray-800">
            <p class="text-xs text-gray-500">© 2026 Wedding Invitation. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import CoverPage from './components/CoverPage.vue'
import HeroSection from './components/HeroSection.vue'
import CoupleSection from './components/CoupleSection.vue'
import EventSection from './components/EventSection.vue'
import GallerySection from './components/GallerySection.vue'
import WishSection from './components/WishSection.vue'
import GiftSection from './components/GiftSection.vue'
import MusicPlayer from './components/MusicPlayer.vue'
import QuranSection from './components/QuranSection.vue'
import BottomNav from './components/BottomNav.vue'

gsap.registerPlugin(ScrollTrigger)

interface Wedding {
  id: number
  bride_name: string
  bride_father_name: string | null
  bride_mother_name: string | null
  bride_photo: string | null
  groom_name: string
  groom_father_name: string | null
  groom_mother_name: string | null
  groom_photo: string | null
  wedding_date: string
  cover_image: string | null
  opening_text: string | null
  closing_text: string | null
  music_url: string | null
  theme_primary_color: string
  theme_secondary_color: string
  theme_accent_color: string
  theme_font_family: string
  slug: string
  events: any[]
  galleries: any[]
  gifts: any[]
  wishes: any[]
}

interface Guest {
  id: number
  name: string
  partner_name?: string
  display_name?: string
  invitation_code: string
}

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
}>()

const appUrl = computed(() => window.location.origin)

const isOpened = ref(false)
const isMusicPlaying = ref(false)
const audioPlayer = ref<HTMLAudioElement | null>(null)
const wishSectionRef = ref<InstanceType<typeof WishSection> | null>(null)

const heroRef = ref<HTMLElement | null>(null)
const coupleRef = ref<HTMLElement | null>(null)
const eventRef = ref<HTMLElement | null>(null)
const galleryRef = ref<HTMLElement | null>(null)
const wishRef = ref<HTMLElement | null>(null)
const giftRef = ref<HTMLElement | null>(null)

const wishForm = useForm({
  guest_name: props.guest?.name || '',
  message: '',
})

onMounted(() => {
  const sections = [heroRef, coupleRef, eventRef, galleryRef, wishRef, giftRef]
  
  sections.forEach((sectionRef) => {
    if (sectionRef.value) {
      gsap.fromTo(
        sectionRef.value,
        {
          opacity: 0,
          y: 100,
        },
        {
          opacity: 1,
          y: 0,
          duration: 1.2,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: sectionRef.value,
            start: 'top 80%',
            end: 'top 20%',
            toggleActions: 'play none none reverse',
          },
        }
      )
    }
  })
})

onUnmounted(() => {
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())
  if (audioPlayer.value) {
    audioPlayer.value.pause()
  }
})

const openInvitation = () => {
  isOpened.value = true
  if (audioPlayer.value && props.wedding.music_url) {
    audioPlayer.value.play()
    isMusicPlaying.value = true
  }
}

const toggleMusic = () => {
  if (!audioPlayer.value) return
  
  if (isMusicPlaying.value) {
    audioPlayer.value.pause()
  } else {
    audioPlayer.value.play()
  }
  isMusicPlaying.value = !isMusicPlaying.value
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const submitWish = (form: { guest_name: string; message: string }) => {
  wishForm.guest_name = form.guest_name
  wishForm.message = form.message
  
  wishForm.post(`/invitation/${props.wedding.slug}/wish`, {
    onSuccess: () => {
      if (wishSectionRef.value) {
        wishSectionRef.value.form.message = ''
      }
      Swal.fire({
        title: 'Terima Kasih!',
        text: 'Ucapan Anda telah terkirim',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: props.wedding.theme_primary_color,
        background: '#fff',
        color: props.wedding.theme_primary_color,
        backdrop: `
          rgba(0,0,0,0.4)
          left top
          no-repeat
        `
      })
    },
    onError: () => {
      Swal.fire({
        title: 'Oops!',
        text: 'Terjadi kesalahan, silakan coba lagi',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: props.wedding.theme_primary_color,
      })
    }
  })
}

const copyToClipboard = (text: string) => {
  navigator.clipboard.writeText(text).then(() => {
    Swal.fire({
      title: 'Berhasil!',
      text: 'Nomor rekening berhasil disalin',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end',
      background: props.wedding.theme_primary_color,
      color: '#fff',
      iconColor: '#fff'
    })
  })
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

section {
  animation: fadeIn 0.8s ease-out;
}
</style>
