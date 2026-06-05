<template>
  <!-- =============================================
       VINTAGE MINANG TEMPLATE
       Dynamic version using Wedding data
  ============================================= -->
  <div class="vm-root">
    <!-- Google Fonts for Vintage Minang -->
    <component :is="'link'" rel="preconnect" href="https://fonts.googleapis.com" />
    <component :is="'link'" rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <component
      :is="'link'"
      href="https://fonts.googleapis.com/css2?family=Aboreto&family=Cinzel:wght@400;500;600&family=Pinyon+Script&family=Poppins:wght@300;400;600&family=Zen+Antique&family=Caudex&family=Belleza&family=ZCOOL+XiaoWei&family=Alike+Angular&family=Open+Sans:wght@400;600&family=Amiri:wght@400;700&display=swap"
      rel="stylesheet"
    />

    <!-- Audio -->
    <audio ref="audioRef" loop :src="musicSrc" preload="auto" muted />

    <!-- Music Toggle Button -->
    <div class="vm-music-btn" :class="{ playing: isMusicPlaying }" @click="toggleMusic">
      <span>{{ isMusicPlaying ? '♫' : '♪' }}</span>
    </div>

    <!-- ====== DESKTOP LEFT PANEL ====== -->
    <div class="vm-desk-cov">
      <div class="vm-desk-bg-overlay"></div>
      <div class="vm-desk-content">
        <p class="vm-desk-label">We Found Love</p>
        <div class="vm-desk-gold-line"></div>
        <p class="vm-desk-love-text">
          {{ wedding.love_story || 'Karena cinta bukan tentang menemukan seseorang yang sempurna, melainkan tentang melihat seseorang yang tidak sempurna dengan cara yang sempurna.' }}
        </p>
        <div class="vm-desk-gold-line"></div>
        <p class="vm-desk-couple">{{ wedding.bride_name }} &amp; {{ wedding.groom_name }}</p>
      </div>
    </div>

    <!-- ====== MAIN SCROLLABLE CONTENT ====== -->
    <div class="vm-isi">

      <!-- Cover & Video Intro -->
      <VmCoverSection
        :wedding="wedding"
        :guest="guest"
        :is-opened="isOpened"
        :is-playing-video="isPlayingVideo"
        @open="openInvitation"
        @skip="skipVideo"
        @entered="enterInvitation"
      />

      <!-- Hero -->
      <VmHeroSection v-if="isOpened" :wedding="wedding" />

      <!-- Bride & Groom -->
      <VmCoupleSection v-if="isOpened" :wedding="wedding" />

      <!-- Wedding Event & Countdown -->
      <VmEventSection v-if="isOpened" :wedding="wedding" />

      <!-- Gallery -->
      <VmGallerySection
        v-if="isOpened"
        :wedding="wedding"
        @lightbox="openLightbox"
      />

      <!-- Wedding Gift -->
      <VmGiftSection
        v-if="isOpened"
        :wedding="wedding"
        @copy="copyText"
      />

      <!-- RSVP + Wishes -->
      <VmRsvpSection v-if="isOpened" :wedding="wedding" :guest="guest" />

      <!-- Closing -->
      <VmClosingSection v-if="isOpened" :wedding="wedding" />

    </div><!-- end vm-isi -->

    <!-- Lightbox -->
    <div v-if="lightboxSrc" class="vm-lightbox" @click="lightboxSrc = ''">
      <img :src="lightboxSrc" alt="Gallery" />
    </div>

    <!-- Toast -->
    <div v-if="toastMsg" class="vm-toast">{{ toastMsg }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import type { Wedding, Guest } from './VintageMinang/types'
import VmCoverSection from './VintageMinang/VmCoverSection.vue'
import VmHeroSection from './VintageMinang/VmHeroSection.vue'
import VmCoupleSection from './VintageMinang/VmCoupleSection.vue'
import VmEventSection from './VintageMinang/VmEventSection.vue'
import VmGallerySection from './VintageMinang/VmGallerySection.vue'
import VmGiftSection from './VintageMinang/VmGiftSection.vue'
import VmRsvpSection from './VintageMinang/VmRsvpSection.vue'
import VmClosingSection from './VintageMinang/VmClosingSection.vue'

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
}>()

// ============ STATE ============
const isOpened = ref(false)
const isPlayingVideo = ref(false)
const isMusicPlaying = ref(false)
const hasUserInteracted = ref(false)
const audioRef = ref<HTMLAudioElement | null>(null)
const lightboxSrc = ref('')
const toastMsg = ref('')
let animObserver: IntersectionObserver | null = null

// ============ MUSIC ============
const musicSrc = computed(() =>
  props.wedding.music_url || '/minang-assets/Paradise-of-Minangkabau-128-kbps.mp3'
)

// ============ LIFECYCLE ============
onMounted(() => initScrollAnim())
onUnmounted(() => {
  if (animObserver) animObserver.disconnect()
  if (audioRef.value) audioRef.value.pause()
})

// ============ OPEN INVITATION (klik Buka Undangan → putar video dulu) ============
function openInvitation() {
  if (!hasUserInteracted.value && audioRef.value) {
    hasUserInteracted.value = true
    audioRef.value.muted = false
    audioRef.value.volume = 0.5
    audioRef.value.play().then(() => {
      isMusicPlaying.value = true
    }).catch((err) => {
      console.error('Audio play failed:', err)
    })
  }
  isPlayingVideo.value = true
}

// ============ SKIP VIDEO ============
function skipVideo() {
  isPlayingVideo.value = false
  enterInvitation()
}

// ============ ENTER INVITATION (setelah video) ============
function enterInvitation() {
  isPlayingVideo.value = false
  isOpened.value = true
  nextTick(() => {
    // Scroll ke atas konten undangan
    window.scrollTo({ top: 0, behavior: 'instant' })
    const isi = document.querySelector('.vm-isi') as HTMLElement | null
    if (isi) isi.scrollTop = 0
    setTimeout(() => initScrollAnim(), 150)
  })
}

// ============ MUSIC TOGGLE ============
function toggleMusic() {
  if (!audioRef.value) return
  if (isMusicPlaying.value) {
    audioRef.value.pause()
    isMusicPlaying.value = false
  } else {
    audioRef.value.play().then(() => {
      isMusicPlaying.value = true
    }).catch(() => {})
  }
}

// ============ SCROLL ANIMATIONS ============
function initScrollAnim() {
  if (animObserver) animObserver.disconnect()

  animObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in')
        animObserver?.unobserve(e.target)
      }
    })
  }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' })

  document.querySelectorAll('[data-anim]').forEach(el => {
    animObserver?.observe(el)
  })
}

// ============ LIGHTBOX ============
function openLightbox(src: string) {
  lightboxSrc.value = src
}

// ============ COPY ============
function copyText(text: string) {
  navigator.clipboard.writeText(text).then(() => {
    showToast('✅ Nomor rekening berhasil disalin!')
  }).catch(() => {
    showToast('✅ Disalin!')
  })
}

function showToast(msg: string) {
  toastMsg.value = msg
  setTimeout(() => { toastMsg.value = '' }, 3000)
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Aboreto&family=Cinzel:wght@400;500;600&family=Pinyon+Script&family=Poppins:wght@300;400;600&family=Zen+Antique&family=Caudex&family=Belleza&family=ZCOOL+XiaoWei&family=Alike+Angular&family=Open+Sans:wght@400;600&family=Amiri:wght@400;700&display=swap');

/* Design Tokens */
.vm-root {
  --vm-maroon:       #661217;
  --vm-maroon-dark:  #900009;
  --vm-maroon-grad:  radial-gradient(at center center, #8A3F43 0%, #661217 100%);
  --vm-gold:         #DFB584;
  --vm-gold-warm:    #B58B59;
  --vm-gold-grad:    linear-gradient(165deg, #E1BF7C 0%, #A17036 100%);
  --vm-cream:        #FFFBE9;
  --vm-cream-2:      #FFECD7;
  --vm-cream-3:      #EBE5D7;
  --vm-cream-4:      #FFF1CF;
  --vm-text-brown:   #69463A;
  --vm-white:        #FFFFFF;
  --vm-black:        #020101;
  font-family: 'Poppins', sans-serif;
  background: var(--vm-cream);
  color: var(--vm-maroon);
  overflow-x: hidden;
}

/* ====== DESKTOP SPLIT ====== */
.vm-desk-cov {
  position: fixed;
  left: 0; top: 0;
  width: calc(100% - 500px);
  height: 100vh;
  overflow: hidden;
  background-image: url('/minang-assets/halaman-awal-minang-compressed.jpg');
  background-position: bottom center;
  background-size: cover;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  z-index: 1;
}
.vm-desk-bg-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.72) 100%);
}
.vm-desk-content {
  position: relative; z-index: 2;
  text-align: center;
  padding: 50px 40px;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.vm-desk-label {
  font-family: 'Cinzel', serif;
  font-size: 13px; font-weight: 500; letter-spacing: 3px;
  color: var(--vm-gold); text-transform: uppercase;
  margin-bottom: 18px;
}
.vm-desk-gold-line {
  width: 100px; height: 1px;
  background: linear-gradient(90deg, transparent, var(--vm-gold), transparent);
  margin: 0 auto 20px;
}
.vm-desk-love-text {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; font-weight: 300;
  color: rgba(255,251,233,0.92);
  line-height: 1.9; font-style: italic;
  text-align: center;
  white-space: pre-line;
  margin-bottom: 20px;
  max-width: 380px;
}
.vm-desk-couple {
  font-family: 'Aboreto', cursive;
  font-size: 22px; font-weight: 400;
  color: var(--vm-gold);
  letter-spacing: 1px;
  text-shadow: 0 2px 12px rgba(0,0,0,0.5);
}

.vm-isi {
  margin-left: calc(100% - 500px);
  width: 500px;
  min-height: 100vh;
  position: relative;
}

@media (max-width: 900px) and (min-width: 768px) {
  .vm-desk-cov { width: 50%; }
  .vm-isi { margin-left: 50%; width: 50%; }
}
@media (max-width: 768px) {
  .vm-desk-cov { display: none; }
  .vm-isi { margin-left: auto; margin-right: auto; width: 100%; }
}

/* ====== MUSIC BUTTON ====== */
.vm-music-btn {
  position: fixed; bottom: 20px; right: 20px;
  z-index: 9999; width: 44px; height: 44px;
  background: var(--vm-maroon); color: var(--vm-gold);
  border-radius: 50%; display: flex;
  align-items: center; justify-content: center;
  cursor: pointer; font-size: 1.2rem;
  box-shadow: 0 4px 16px rgba(102,18,23,0.5);
  transition: transform 0.2s;
  border: 2px solid var(--vm-gold-warm);
}
.vm-music-btn:hover { transform: scale(1.1); }
.vm-music-btn.playing { animation: vmMusicPulse 1.5s ease infinite; }
@keyframes vmMusicPulse {
  0%, 100% { box-shadow: 0 4px 16px rgba(102,18,23,0.5), 0 0 0 0 rgba(102,18,23,0.4); }
  50% { box-shadow: 0 4px 16px rgba(102,18,23,0.5), 0 0 0 8px rgba(102,18,23,0); }
}

/* ====== LIGHTBOX ====== */
.vm-lightbox {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.92);
  z-index: 10000; display: flex;
  align-items: center; justify-content: center;
  cursor: zoom-out;
  animation: vmLbIn 0.3s ease;
}
@keyframes vmLbIn { from { opacity: 0; } to { opacity: 1; } }
.vm-lightbox img {
  max-width: 92vw; max-height: 88vh;
  object-fit: contain; border-radius: 4px;
  box-shadow: 0 0 60px rgba(0,0,0,0.8);
}

/* ====== TOAST ====== */
.vm-toast {
  position: fixed; bottom: 72px; left: 50%;
  transform: translateX(-50%);
  background: var(--vm-maroon); color: var(--vm-gold);
  padding: 10px 22px; border-radius: 4px;
  font-family: 'Poppins', sans-serif; font-size: 13px;
  z-index: 9999;
  animation: vmToastIn 0.3s ease;
  border: 1px solid var(--vm-gold-warm);
}
@keyframes vmToastIn {
  from { opacity: 0; transform: translateX(-50%) translateY(8px); }
  to { opacity: 1; transform: translateX(-50%) translateY(0); }
}
</style>
