<template>
  <!-- ====== COVER / BUKA UNDANGAN ====== -->
  <section v-if="!isPlayingVideo && !isOpened" class="vm-section-cover">
    <div class="vm-cover-bg-overlay"></div>
    <div class="vm-cover-inner show">
      <div class="vm-cover-spacer"></div>
      <p class="vm-cover-label">WEDDING INVITATION</p>
      <h2 class="vm-cover-names-big">{{ wedding.bride_name }} &amp; {{ wedding.groom_name }}</h2>
      <div class="vm-cover-spacer-sm"></div>
      <p class="vm-cover-kepada">Kepada Yth.</p>
      <h3 class="vm-cover-guest">{{ guest?.display_name || guest?.name || 'Tamu Undangan' }}</h3>
      <div class="vm-cover-spacer-sm"></div>
      <button class="vm-btn-buka" @click="$emit('open')">♡ Buka Undangan</button>
      <div class="vm-cover-spacer"></div>
    </div>
  </section>

  <!-- ====== VIDEO INTRO ====== -->
  <section v-if="isPlayingVideo" class="vm-section-video-intro">
    <video
      ref="videoRef"
      class="vm-intro-video"
      autoplay
      playsinline
      webkit-playsinline
      muted
      preload="auto"
    >
      <source src="/minang-assets/3D-minang-motion.mp4" type="video/mp4" />
    </video>
    <div class="vm-video-intro-overlay"></div>

    <!-- Teks nama & tanggal — muncul fade-in di detik ke-14 -->
    <div class="vm-video-caption" :class="{ visible: captionVisible }">
      <p class="vm-vc-label">The Wedding Of</p>
      <h2 class="vm-vc-names">{{ wedding.bride_name }} &amp; {{ wedding.groom_name }}</h2>
      <div class="vm-vc-line"></div>
      <p class="vm-vc-date">{{ formatDateDots(wedding.wedding_date) }}</p>
    </div>

    <button class="vm-btn-skip" @click="$emit('skip')">Skip ›</button>
  </section>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import type { Wedding, Guest } from './types'
import { formatDateDots } from './helpers'

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
  isOpened: boolean
  isPlayingVideo: boolean
}>()

const emit = defineEmits<{
  open: []
  skip: []
  entered: []
}>()

const videoRef = ref<HTMLVideoElement | null>(null)
const captionVisible = ref(false)

// Inisialisasi video saat isPlayingVideo berubah jadi true
watch(
  () => props.isPlayingVideo,
  (val) => {
    if (val) {
      setTimeout(() => initVideo(), 100)
    } else {
      captionVisible.value = false
    }
  }
)

function initVideo() {
  const video = videoRef.value
  if (!video) return

  video.muted = true
  video.playsInline = true
  video.loop = false

  const tryPlay = () => {
    video.play().catch(() => emit('entered'))
  }

  tryPlay()
  video.addEventListener('canplay', tryPlay, { once: true })

  video.addEventListener('timeupdate', () => {
    if (video.currentTime >= 14 && !captionVisible.value) {
      captionVisible.value = true
    }
  })

  video.addEventListener('ended', () => {
    emit('entered')
  })

  video.addEventListener('error', () => emit('entered'))
}
</script>

<style scoped>
/* ====== COVER ====== */
.vm-section-cover {
  position: relative;
  width: 100%; min-height: 100vh;
  background-image: url('/minang-assets/COVER-BUKA-UNDANGAN-MINANG-4.jpg');
  background-position: center; background-size: cover;
  display: flex; align-items: stretch;
  overflow: hidden; border-radius: 10px;
  z-index: 999;
}
.vm-cover-bg-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(102,18,23,0.45) 0%, rgba(2,1,1,0.65) 100%);
  border-radius: 10px; z-index: 1;
}
.vm-cover-inner {
  position: relative; z-index: 2;
  width: 100%; padding: 50px 50px 30px;
  display: flex; flex-direction: column;
  align-items: center; text-align: center;
  opacity: 0.2;
  transition: opacity 0.5s ease;
}
.vm-cover-inner.show { opacity: 1; }
.vm-cover-spacer { height: 220px; }
.vm-cover-spacer-sm { height: 25px; }
.vm-cover-label {
  font-family: 'Cinzel', serif;
  font-size: 14px; font-weight: 500; letter-spacing: 2px;
  color: var(--vm-gold); text-transform: uppercase;
  text-shadow: 0 1px 6px rgba(0,0,0,0.6);
}
.vm-cover-names-big {
  font-family: 'Aboreto', cursive;
  font-size: 52px; font-weight: 500; color: var(--vm-white);
  text-shadow: 0 2px 16px rgba(0,0,0,0.6);
}
.vm-cover-kepada {
  font-family: 'Open Sans', sans-serif;
  font-size: 15px; font-weight: 600; letter-spacing: 1.5px;
  color: var(--vm-cream);
  text-shadow: 0 1px 6px rgba(0,0,0,0.5);
}
.vm-cover-guest {
  font-family: 'Open Sans', sans-serif;
  font-size: 15px; font-weight: 600; letter-spacing: 1.5px;
  color: var(--vm-gold); margin-top: 4px;
  text-shadow: 0 1px 6px rgba(0,0,0,0.5);
}
.vm-btn-buka {
  background: transparent;
  border: 2px solid var(--vm-gold);
  color: var(--vm-gold);
  font-family: 'Cinzel', serif;
  font-size: 13px; font-weight: 600; letter-spacing: 2px;
  text-transform: uppercase;
  padding: 12px 30px; cursor: pointer;
  border-radius: 4px; transition: all 0.3s;
  margin-top: 10px;
  animation: vmBtnPulse 2s ease infinite;
  text-shadow: 0 1px 4px rgba(0,0,0,0.4);
}
@keyframes vmBtnPulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(223,181,132,0.5); }
  50% { box-shadow: 0 0 0 10px rgba(223,181,132,0); }
}
.vm-btn-buka:hover { background: var(--vm-gold); color: var(--vm-black); }

/* ====== VIDEO INTRO SECTION ====== */
.vm-section-video-intro {
  position: relative;
  width: 100%; height: 100vh;
  overflow: hidden;
  background: #000;
  z-index: 999;
  display: flex; align-items: center; justify-content: center;
}
.vm-intro-video {
  position: absolute; top: 0; left: 0;
  width: 100%; height: 100%;
  object-fit: cover; z-index: 0;
}
.vm-video-intro-overlay {
  position: absolute; inset: 0;
  background: rgba(0,0,0,0.15);
  z-index: 1;
}
.vm-btn-skip {
  position: absolute;
  bottom: 28px; right: 24px;
  z-index: 10;
  background: rgba(0,0,0,0.45);
  border: 1.5px solid rgba(223,181,132,0.7);
  color: var(--vm-gold);
  font-family: 'Cinzel', serif;
  font-size: 13px; font-weight: 600; letter-spacing: 2px;
  padding: 9px 22px; cursor: pointer;
  border-radius: 4px;
  transition: all 0.25s;
  backdrop-filter: blur(4px);
  text-transform: uppercase;
}
.vm-btn-skip:hover {
  background: rgba(223,181,132,0.2);
  border-color: var(--vm-gold);
}

/* ====== VIDEO CAPTION ====== */
.vm-video-caption {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, calc(-50% + 20px));
  z-index: 10;
  text-align: center;
  width: 88%;
  opacity: 0;
  transition: opacity 0.9s ease, transform 0.9s ease;
  pointer-events: none;
}
.vm-video-caption.visible {
  opacity: 1;
  transform: translate(-50%, -50%);
}
.vm-vc-label {
  font-family: 'Cinzel', serif;
  font-size: 11px; font-weight: 500; letter-spacing: 3px;
  color: var(--vm-gold); text-transform: uppercase;
  text-shadow: 0 1px 8px rgba(0,0,0,0.8);
  margin-bottom: 10px;
}
.vm-vc-names {
  font-family: 'Aboreto', cursive;
  font-size: 34px; font-weight: 400;
  color: var(--vm-white);
  line-height: 1.2;
  text-shadow: 0 2px 20px rgba(0,0,0,0.85), 0 0 40px rgba(0,0,0,0.5);
  margin: 0 0 14px;
}
.vm-vc-line {
  width: 80px; height: 1px;
  background: linear-gradient(90deg, transparent, var(--vm-gold), transparent);
  margin: 0 auto 14px;
}
.vm-vc-date {
  font-family: 'Cinzel', serif;
  font-size: 14px; font-weight: 500; letter-spacing: 5px;
  color: var(--vm-gold);
  text-shadow: 0 1px 8px rgba(0,0,0,0.8);
}
@media (max-width: 480px) {
  .vm-vc-names { font-size: 26px; }
  .vm-video-caption { top: 50%; }
}
</style>
