<template>
  <!-- =============================================
       VINTAGE MINANG TEMPLATE
       Dynamic version using Wedding data
  ============================================= -->
  <div class="vm-root">
    <!-- Google Fonts for Vintage Minang -->
    <component :is="'link'" rel="preconnect" href="https://fonts.googleapis.com" />
    <component :is="'link'" rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <component :is="'link'" href="https://fonts.googleapis.com/css2?family=Aboreto&family=Cinzel:wght@400;500;600&family=Pinyon+Script&family=Poppins:wght@300;400;600&family=Zen+Antique&family=Caudex&family=Belleza&family=ZCOOL+XiaoWei&family=Alike+Angular&family=Open+Sans:wght@400;600&family=Amiri:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Audio -->
    <audio ref="audioRef" loop :src="musicSrc" preload="auto" muted />

    <!-- Music Toggle Button -->
    <div
      class="vm-music-btn"
      :class="{ playing: isMusicPlaying }"
      @click="toggleMusic"
    >
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
          <button class="vm-btn-buka" @click="openInvitation">
            ♡ Buka Undangan
          </button>
          <div class="vm-cover-spacer"></div>
        </div>
      </section>

      <!-- ====== VIDEO INTRO (muncul setelah klik Buka Undangan) ====== -->
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
        <div class="vm-video-caption" :class="{ visible: videoCaptionVisible }">
          <p class="vm-vc-label">The Wedding Of</p>
          <h2 class="vm-vc-names">{{ wedding.bride_name }} &amp; {{ wedding.groom_name }}</h2>
          <div class="vm-vc-line"></div>
          <p class="vm-vc-date">{{ formatDateDots(wedding.wedding_date) }}</p>
        </div>

        <button class="vm-btn-skip" @click="skipVideo">Skip ›</button>
      </section>

      <!-- ====== HERO / HOME SECTION ====== -->
      <section v-if="isOpened" class="vm-section-hero" :style="heroStyle">
        <div class="vm-hero-particles" ref="particlesRef"></div>

        <div class="vm-hero-content">
          <div class="vm-hero-spacer"></div>
          <p class="vm-hero-sub">We Found Love</p>
          <div class="vm-hero-gold-line"></div>
          <p class="vm-hero-love-text">
            {{ wedding.love_story || 'Karena cinta bukan tentang menemukan seseorang yang sempurna, melainkan tentang melihat seseorang yang tidak sempurna dengan cara yang sempurna.' }}
          </p>
          <div class="vm-hero-gold-line"></div>
          <p class="vm-hero-couple-small">{{ wedding.bride_name }} &amp; {{ wedding.groom_name }}</p>
          <p class="vm-hero-date">{{ formatDateDots(wedding.wedding_date) }}</p>
          <div class="vm-hero-spacer"></div>
        </div>
      </section>
<!-- 
      ====== LOVE STORY (dari dashboard) ======
      <section v-if="isOpened && wedding.love_story" class="vm-section-love">
        <div class="vm-love-bg">
          <div class="vm-love-content" data-anim="fadeUp">
            <p class="vm-love-label">Our Love Story</p>
            <div class="vm-gold-line"></div>
            <p class="vm-love-story-text">{{ wedding.love_story }}</p>
            <div class="vm-gold-line"></div>
          </div>
        </div>
      </section> -->

      <!-- ====== BRIDE & GROOM ====== -->
      <section v-if="isOpened" class="vm-section-couple">
        <div class="vm-couple-bg">
          <div class="vm-couple-header" data-anim="fadeUp">
            <span class="vm-tag-label">Bride &amp;</span>
            <span class="vm-tag-label">Groom</span>
          </div>
          <p class="vm-couple-intro" data-anim="fadeUp">
            Assalamualaikum Wr. Wb.<br>
            Dengan memohon Rahmat &amp; Ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan putra-putri kami:
          </p>

          <!-- Bride -->
          <div class="vm-person-block" data-anim="slideLeft">
            <div class="vm-person-photo-wrap">
              <div class="vm-person-photo vm-bride-frame">
                <img
                  v-if="wedding.bride_photo"
                  :src="`/storage/${wedding.bride_photo}`"
                  :alt="wedding.bride_name"
                  class="vm-person-img"
                />
                <div v-else class="vm-photo-letter">{{ wedding.bride_name?.charAt(0) }}</div>
              </div>
            </div>
            <div class="vm-person-details">
              <h2 class="vm-person-title-big">{{ wedding.bride_name }}</h2>
              <p v-if="wedding.bride_father_name || wedding.bride_mother_name" class="vm-person-from">Putri dari</p>
              <p v-if="wedding.bride_father_name || wedding.bride_mother_name" class="vm-person-parents">
                {{ [wedding.bride_father_name, wedding.bride_mother_name].filter(Boolean).join(' & ') }}
              </p>
            </div>
          </div>

          <!-- Groom -->
          <div class="vm-person-block vm-reverse" data-anim="slideRight">
            <div class="vm-person-photo-wrap">
              <div class="vm-person-photo vm-groom-frame">
                <img
                  v-if="wedding.groom_photo"
                  :src="`/storage/${wedding.groom_photo}`"
                  :alt="wedding.groom_name"
                  class="vm-person-img"
                />
                <div v-else class="vm-photo-letter">{{ wedding.groom_name?.charAt(0) }}</div>
              </div>
            </div>
            <div class="vm-person-details">
              <h2 class="vm-person-title-big">{{ wedding.groom_name }}</h2>
              <p v-if="wedding.groom_father_name || wedding.groom_mother_name" class="vm-person-from">Putra dari</p>
              <p v-if="wedding.groom_father_name || wedding.groom_mother_name" class="vm-person-parents">
                {{ [wedding.groom_father_name, wedding.groom_mother_name].filter(Boolean) .join(' & ') }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== WEDDING EVENT / COUNTDOWN ====== -->
      <section v-if="isOpened" class="vm-section-event-bg">
        <div class="vm-event-bg-overlay"></div>
        <div class="vm-event-wrap" data-anim="fadeUp">
          <p class="vm-event-tag">Wedding Event</p>

          <!-- Countdown -->
          <div class="vm-countdown-row">
            <div class="vm-cd-item">
              <span class="vm-cd-num">{{ countdown.d }}</span>
              <span class="vm-cd-lbl">Days</span>
            </div>
            <span class="vm-cd-sep">:</span>
            <div class="vm-cd-item">
              <span class="vm-cd-num">{{ countdown.h }}</span>
              <span class="vm-cd-lbl">Hours</span>
            </div>
            <span class="vm-cd-sep">:</span>
            <div class="vm-cd-item">
              <span class="vm-cd-num">{{ countdown.m }}</span>
              <span class="vm-cd-lbl">Minutes</span>
            </div>
            <span class="vm-cd-sep">:</span>
            <div class="vm-cd-item">
              <span class="vm-cd-num">{{ countdown.s }}</span>
              <span class="vm-cd-lbl">Seconds</span>
            </div>
          </div>

          <!-- Dynamic Events from DB -->
          <div
            v-for="event in wedding.events"
            :key="event.id"
            class="vm-acara-card"
            data-anim="fadeUp"
          >
            <h3 class="vm-acara-title">{{ event.name }}</h3>
            <p class="vm-acara-will">Akan dilaksanakan pada:</p>
            <p class="vm-acara-day">{{ formatDayName(event.date || wedding.wedding_date) }}</p>
            <p class="vm-acara-date">{{ formatDateDots(event.date || wedding.wedding_date) }}</p>
            <p class="vm-acara-time">Pukul {{ event.start_time }} WIB</p>
            <p class="vm-acara-at">Bertempat di:</p>
            <p class="vm-acara-venue">{{ event.location }}</p>
            <p v-if="event.address" class="vm-acara-addr">{{ event.address }}</p>
            <a
              v-if="event.maps_url"
              class="vm-btn-maps"
              :href="event.maps_url"
              target="_blank"
              rel="noopener"
            >Open Maps</a>
          </div>

          <!-- Fallback if no events -->
          <div v-if="!wedding.events || wedding.events.length === 0" class="vm-acara-card" data-anim="fadeUp">
            <h3 class="vm-acara-title">Pernikahan</h3>
            <p class="vm-acara-will">Akan dilaksanakan pada:</p>
            <p class="vm-acara-day">{{ formatDayName(wedding.wedding_date) }}</p>
            <p class="vm-acara-date">{{ formatDateDots(wedding.wedding_date) }}</p>
          </div>
        </div>
      </section>

      <!-- ====== GALLERY (Dynamic from wedding galleries) ====== -->
      <section v-if="isOpened && wedding.galleries && wedding.galleries.length > 0" class="vm-section-gallery">
        <div class="vm-gallery-inner" data-anim="fadeUp">
          <p class="vm-gallery-tag">Our Gallery</p>
          <h2 class="vm-gallery-title">Constantly, consistently,<br>continually, You.</h2>
          <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>

          <!-- Carousel (first 6 galleries) -->
          <div v-if="carouselGalleries.length > 0" class="vm-gallery-carousel">
            <div class="vm-gc-track" :style="{ transform: `translateX(-${carouselIdx * 100}%)` }">
              <div
                v-for="(g, i) in carouselGalleries"
                :key="g.id"
                class="vm-gc-slide"
                :style="{ backgroundImage: `url('/storage/${g.image_path}')` }"
              ></div>
            </div>
            <button class="vm-gc-btn vm-gc-prev" @click="moveCarousel(-1)">&#8249;</button>
            <button class="vm-gc-btn vm-gc-next" @click="moveCarousel(1)">&#8250;</button>
            <div class="vm-gc-dots">
              <div
                v-for="(g, i) in carouselGalleries"
                :key="i"
                class="vm-gc-dot"
                :class="{ active: carouselIdx === i }"
                @click="carouselIdx = i"
              ></div>
            </div>
          </div>

          <!-- Grid (remaining galleries) -->
          <div v-if="gridGalleries.length > 0" class="vm-gallery-grid">
            <div
              v-for="g in gridGalleries"
              :key="g.id"
              class="vm-gg-item"
              :style="{ backgroundImage: `url('/storage/${g.image_path}')` }"
              @click="openLightbox(`/storage/${g.image_path}`)"
            ></div>
          </div>
        </div>
      </section>

      <!-- ====== WEDDING GIFT ====== -->
      <section v-if="isOpened && wedding.gifts && wedding.gifts.length > 0" class="vm-section-gift">
        <div class="vm-gift-inner" data-anim="fadeUp">
          <p class="vm-gift-tag">Wedding Gift</p>
          <h2 class="vm-gift-title">Hadiah Pernikahan</h2>
          <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>
          <p class="vm-gift-desc">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.</p>

          <div class="vm-gift-cards">
            <div v-for="gift in wedding.gifts" :key="gift.id" class="vm-gift-card">
              <p class="vm-gift-bank">{{ gift.bank_name }}</p>
              <p class="vm-gift-rek">No. Rekening {{ gift.account_number }}</p>
              <p class="vm-gift-an">a.n&nbsp;{{ gift.account_name }}</p>
              <button class="vm-btn-salin" @click="copyText(gift.account_number)">Salin Nomor</button>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== RSVP + UCAPAN ====== -->
      <section v-if="isOpened" class="vm-section-rsvp">
        <div class="vm-rsvp-bg">
          <div class="vm-rsvp-inner" data-anim="fadeUp">
            <p class="vm-rsvp-tag">Rsvp</p>
            <p class="vm-rsvp-desc">Konfirmasi kehadiran Anda dengan mengisi form berikut:</p>
            <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>

            <form class="vm-rsvp-form" @submit.prevent="submitWish">
              <div class="vm-form-row">
                <label>Nama*</label>
                <input
                  v-model="wishForm.guest_name"
                  type="text"
                  placeholder="Nama Lengkap..."
                  required
                  :readonly="!!guest"
                />
              </div>
              <div class="vm-form-row">
                <label>Ucapan &amp; Doa*</label>
                <textarea
                  v-model="wishForm.message"
                  placeholder="Tulis ucapan dan doa terbaik Anda..."
                  rows="3"
                  required
                ></textarea>
              </div>
              <div class="vm-form-row vm-radio-row">
                <label class="vm-r-opt">
                  <input type="radio" v-model="wishForm.attendance_status" value="attending" /> Hadir
                </label>
                <label class="vm-r-opt">
                  <input type="radio" v-model="wishForm.attendance_status" value="not_attending" /> Tidak hadir
                </label>
                <label class="vm-r-opt">
                  <input type="radio" v-model="wishForm.attendance_status" value="maybe" /> Masih Ragu
                </label>
              </div>
              <button type="submit" class="vm-btn-submit" :disabled="wishForm.processing">
                {{ wishForm.processing ? 'Mengirim...' : 'Kirim Ucapan' }}
              </button>
            </form>
          </div>
        </div>
      </section>

      <!-- ====== BEST WISHES / UCAPAN LIST ====== -->
      <section v-if="isOpened" class="vm-section-wishes">
        <div class="vm-wishes-inner" data-anim="fadeUp">
          <p class="vm-wishes-tag">Best Wishes</p>
          <h2 class="vm-wishes-title">Sampaikan doa dan ucapan terbaik Anda</h2>
          <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>

          <!-- Wishes List from DB -->
          <div class="vm-wishes-list">
            <div v-for="wish in wedding.wishes" :key="wish.id" class="vm-wish-item">
              <div class="vm-wish-nama">💌 {{ wish.guest_name }}</div>
              <div class="vm-wish-pesan">{{ wish.message }}</div>
              <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                <span
                  class="vm-wish-hadir"
                  :class="{
                    'vm-hadir-yes': wish.attendance_status === 'attending',
                    'vm-hadir-no': wish.attendance_status === 'not_attending',
                    'vm-hadir-maybe': wish.attendance_status === 'maybe',
                  }"
                >
                  {{ wish.attendance_status === 'attending' ? 'Hadir' : wish.attendance_status === 'not_attending' ? 'Tidak Hadir' : 'Masih Ragu' }}
                </span>
                <span class="vm-wish-waktu">{{ timeAgo(wish.created_at) }}</span>
              </div>
            </div>
            <div v-if="!wedding.wishes || wedding.wishes.length === 0" class="vm-wish-item">
              <div class="vm-wish-pesan" style="text-align:center;opacity:0.6;">Belum ada ucapan. Jadilah yang pertama! 💕</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== CLOSING ====== -->
      <section v-if="isOpened" class="vm-section-closing">
        <div class="vm-closing-overlay"></div>
        <div class="vm-closing-inner" data-anim="fadeUp">
          <div class="vm-gallery-ornament">
            <img src="/minang-assets/minang-ornamen.png" alt="" style="filter:invert(90%) sepia(30%) saturate(80%); opacity:0.6" />
          </div>
          <p class="vm-closing-text">
            {{ wedding.closing_text || 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restunya untuk pernikahan kami.' }}<br><br>
            Atas do'a &amp; restunya,<br>
            kami ucapkan terima kasih.
          </p>
          <h2 class="vm-closing-names">{{ wedding.bride_name?.toUpperCase() }} &amp; {{ wedding.groom_name?.toUpperCase() }}</h2>
          <p class="vm-closing-by">Made with love by Aldo Aditya Putra</p>
        </div>
      </section>

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
import { useForm } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Swal from 'sweetalert2'

// ============ INTERFACES ============
interface WeddingEvent {
  id: number
  name: string
  date?: string
  start_time?: string
  end_time?: string
  location: string
  address?: string
  maps_url?: string
  order: number
}

interface Gallery {
  id: number
  image_path: string
  order: number
}

interface Gift {
  id: number
  bank_name: string
  account_number: string
  account_name: string
  order: number
}

interface Wish {
  id: number
  guest_name: string
  message: string
  attendance_status?: string
  created_at: string
}

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
  cover_page_image: string | null
  opening_text: string | null
  closing_text: string | null
  love_story: string | null
  music_url: string | null
  slug: string
  events: WeddingEvent[]
  galleries: Gallery[]
  gifts: Gift[]
  wishes: Wish[]
  rsvps: any[]
}

interface Guest {
  id: number
  name: string
  display_name?: string
  invitation_code: string
}

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
}>()

// ============ STATE ============
const isOpened = ref(false)
const isPlayingVideo = ref(false)
const videoCaptionVisible = ref(false)
const isMusicPlaying = ref(false)
const hasUserInteracted = ref(false)
const audioRef = ref<HTMLAudioElement | null>(null)
const videoRef = ref<HTMLVideoElement | null>(null)
const particlesRef = ref<HTMLElement | null>(null)
const lightboxSrc = ref('')
const toastMsg = ref('')
const carouselIdx = ref(0)
let countdownTimer: ReturnType<typeof setInterval> | null = null
let carouselTimer: ReturnType<typeof setInterval> | null = null
let animObserver: IntersectionObserver | null = null

const countdown = ref({ d: '00', h: '00', m: '00', s: '00' })

// ============ MUSIC ============
// Use wedding music_url if provided, else fallback to minang default
const musicSrc = computed(() =>
  props.wedding.music_url || '/minang-assets/Paradise-of-Minangkabau-128-kbps.mp3'
)

// Hero background - use cover_image if set, else minang default
const heroStyle = computed(() => ({
  backgroundImage: props.wedding.cover_image
    ? `url('/storage/${props.wedding.cover_image}')`
    : `url('/minang-assets/home-minang.jpg')`,
}))

// ============ GALLERY SPLIT ============
const carouselGalleries = computed(() => props.wedding.galleries.slice(0, 6))
const gridGalleries = computed(() => props.wedding.galleries.slice(6, 12))

// ============ WISH FORM ============
const wishForm = useForm({
  guest_name: props.guest?.name || '',
  message: '',
  attendance_status: '',
})

// ============ LIFECYCLE ============
onMounted(() => {
  initCountdown()
  initScrollAnim()
})

onUnmounted(() => {
  if (countdownTimer) clearInterval(countdownTimer)
  if (carouselTimer) clearInterval(carouselTimer)
  if (animObserver) animObserver.disconnect()
  if (audioRef.value) audioRef.value.pause()
})

// ============ VIDEO INTRO INIT ============
function initVideoIntro() {
  const video = videoRef.value
  if (!video) return

  video.muted = true
  video.playsInline = true
  video.loop = false

  const tryPlay = () => {
    video.play().catch(() => {
      // Jika video tidak bisa diputar, langsung masuk undangan
      enterInvitation()
    })
  }

  tryPlay()
  video.addEventListener('canplay', tryPlay, { once: true })

  // Munculkan caption nama & tanggal di detik ke-14
  video.addEventListener('timeupdate', () => {
    if (video.currentTime >= 14 && !videoCaptionVisible.value) {
      videoCaptionVisible.value = true
    }
  })

  // Video selesai → freeze di frame terakhir, buka konten bawah
  video.addEventListener('ended', () => {
    // Biarkan video diam di frame terakhir (tidak di-loop)
    // Langsung tampilkan konten undangan agar bisa di-scroll
    isOpened.value = true
    setTimeout(() => {
      initParticles()
      initScrollAnim()
      startCarouselTimer()
    }, 100)
  })

  video.addEventListener('error', () => { enterInvitation() })
}

// ============ SKIP VIDEO ============
function skipVideo() {
  if (videoRef.value) videoRef.value.pause()
  enterInvitation()
}

// ============ ENTER INVITATION (setelah video) ============
function enterInvitation() {
  isPlayingVideo.value = false
  videoCaptionVisible.value = false
  isOpened.value = true
  setTimeout(() => {
    initParticles()
    initScrollAnim()
    startCarouselTimer()
  }, 100)
}

// ============ OPEN INVITATION (klik Buka Undangan → putar video dulu) ============
function openInvitation() {
  // Mulai musik saat user klik
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

  // Tampilkan section video intro
  isPlayingVideo.value = true

  // Tunggu DOM render lalu inisialisasi video
  setTimeout(() => {
    initVideoIntro()
  }, 100)
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

// ============ COUNTDOWN ============
function initCountdown() {
  const dateOnly = props.wedding.wedding_date.split(/[ T]/)[0]
  const target = new Date(dateOnly + 'T08:00:00')

  function tick() {
    const now = new Date()
    const diff = target.getTime() - now.getTime()

    if (diff <= 0) {
      countdown.value = { d: '00', h: '00', m: '00', s: '00' }
      return
    }

    const pad = (n: number) => String(n).padStart(2, '0')
    countdown.value = {
      d: pad(Math.floor(diff / 86400000)),
      h: pad(Math.floor((diff % 86400000) / 3600000)),
      m: pad(Math.floor((diff % 3600000) / 60000)),
      s: pad(Math.floor((diff % 60000) / 1000)),
    }
  }

  tick()
  countdownTimer = setInterval(tick, 1000)
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

// ============ PARTICLES ============
function initParticles() {
  const container = particlesRef.value
  if (!container) return
  container.innerHTML = ''

  for (let i = 0; i < 35; i++) {
    const p = document.createElement('div')
    p.className = 'vm-particle'
    const size = Math.random() * 5 + 2
    const x = Math.random() * 100
    const dur = Math.random() * 7 + 5
    const delay = Math.random() * 10
    const dx = (Math.random() - 0.5) * 80
    const colors = ['#DFB584', '#E1BF7C', '#B58B59', '#ffffff']
    const color = colors[Math.floor(Math.random() * colors.length)]

    p.style.cssText = `left:${x}%;top:0;width:${size}px;height:${size}px;background:${color};animation-duration:${dur}s;animation-delay:${delay}s;--dx:${dx}px;opacity:0;`
    container.appendChild(p)
  }
}

// ============ CAROUSEL ============
function moveCarousel(dir: number) {
  const total = carouselGalleries.value.length
  if (!total) return
  carouselIdx.value = (carouselIdx.value + dir + total) % total
}

function startCarouselTimer() {
  if (carouselTimer) clearInterval(carouselTimer)
  carouselTimer = setInterval(() => moveCarousel(1), 4000)
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

// ============ SUBMIT WISH ============
function submitWish() {
  wishForm.post(`/invitation/${props.wedding.slug}/wish`, {
    onSuccess: () => {
      wishForm.message = ''
      wishForm.attendance_status = ''
      Swal.fire({
        title: 'Terima Kasih!',
        text: 'Ucapan Anda telah terkirim',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#661217',
        background: '#FFFBE9',
        color: '#661217',
      })
    },
    onError: () => {
      Swal.fire({
        title: 'Oops!',
        text: 'Terjadi kesalahan, silakan coba lagi',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#661217',
      })
    },
  })
}

// ============ DATE HELPERS ============
function formatDateShort(date: string) {
  if (!date) return ''
  const safeDate = date.replace(' ', 'T')
  return new Date(safeDate).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).toUpperCase()
}

function formatDateDots(date: string) {
  if (!date) return ''
  const safeDate = date.replace(' ', 'T')
  const d = new Date(safeDate)
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${pad(d.getDate())} . ${pad(d.getMonth() + 1)} . ${d.getFullYear()}`
}

function formatDayName(date: string) {
  if (!date) return ''
  const safeDate = date.replace(' ', 'T')
  return new Date(safeDate).toLocaleDateString('id-ID', { weekday: 'long' })
}

function timeAgo(dateStr: string) {
  if (!dateStr) return ''
  const safeDate = dateStr.replace(' ', 'T')
  const diff = Date.now() - new Date(safeDate).getTime()
  const m = Math.floor(diff / 60000)
  const h = Math.floor(diff / 3600000)
  const d = Math.floor(diff / 86400000)
  if (m < 1) return 'baru saja'
  if (m < 60) return `${m} menit lalu`
  if (h < 24) return `${h} jam lalu`
  return `${d} hari lalu`
}
</script>

<style scoped>
/* ============================================================
   VINTAGE MINANG TEMPLATE - Scoped Styles
   All classes prefixed with vm- to avoid collisions
   ============================================================ */

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

.vm-cover-video {
  position: absolute; top: 0; left: 0;
  width: 100%; height: 100%;
  object-fit: cover; z-index: 0;
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

/* ====== VIDEO CAPTION (muncul di detik ke-14) ====== */
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

/* ====== HERO ====== */
.vm-section-hero {
  position: relative;
  width: 100%; min-height: 100vh;
  background-position: 50% 50%;
  background-size: cover;
  background-repeat: no-repeat;
  overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}

.vm-hero-particles {
  position: absolute; inset: 0;
  z-index: 0; pointer-events: none;
}



.vm-hero-content {
  position: relative; z-index: 2;
  text-align: center; width: 100%; padding: 50px 20px;
}
.vm-hero-spacer { height: 60px; }

.vm-hero-sub {
  font-family: 'Cinzel', serif;
  font-size: 14px; font-weight: 500; color: var(--vm-gold);
  text-transform: uppercase; letter-spacing: 4px; margin-bottom: 20px;
}
.vm-hero-gold-line {
  width: 100px; height: 1px;
  background: linear-gradient(90deg, transparent, var(--vm-gold), transparent);
  margin: 0 auto 20px;
}
.vm-hero-love-text {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; font-weight: 300;
  color: rgba(255,251,233,0.92);
  line-height: 2; font-style: italic;
  white-space: pre-line;
  text-align: center;
  max-width: 400px; margin: 0 auto 20px;
  text-shadow: 0 1px 8px rgba(0,0,0,0.7);
}
.vm-hero-couple-small {
  font-family: 'Aboreto', cursive;
  font-size: 28px; font-weight: 400; color: var(--vm-gold);
  text-shadow: 0 2px 16px rgba(0,0,0,0.6);
  margin-bottom: 6px;
}
.vm-hero-date {
  font-family: 'Cinzel', serif;
  font-size: 14px; font-weight: 500; color: var(--vm-gold);
  letter-spacing: 5px; margin-top: 8px;
  text-shadow: 0 1px 8px rgba(0,0,0,0.6);
}

/* ====== WE FOUND LOVE ====== */
.vm-section-love { background: var(--vm-maroon-grad); padding: 0; }
.vm-love-bg {
  background-image: url('/minang-assets/BG-UTAMA-MINANG-4.jpg');
  background-size: cover; background-position: center;
  padding: 60px 20px;
}
.vm-love-content {
  text-align: center;
  max-width: 440px;
  margin: 0 auto;
  background: rgba(102, 18, 23, 0.88); /* Semi-transparent maroon card */
  padding: 36px 28px;
  border-radius: 12px;
  border: 1px solid rgba(223, 181, 132, 0.3); /* Premium gold border */
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(4px);
}
.vm-love-label {
  font-family: 'Aboreto', cursive;
  font-size: 36px; color: var(--vm-gold);
  margin-bottom: 12px; line-height: 1.2;
}
.vm-gold-line {
  width: 120px; height: 1px;
  background: linear-gradient(90deg, transparent, var(--vm-gold), transparent);
  margin: 16px auto;
}
.vm-love-arabic {
  display: none;
}
.vm-love-ayat {
  display: none;
}
.vm-love-source {
  display: none;
}
.vm-love-story-text {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; font-weight: 300;
  color: rgba(255,251,233,0.9);
  line-height: 2; font-style: italic;
  white-space: pre-line;
  text-align: center;
}

/* ====== COUPLE ====== */
.vm-section-couple { background: var(--vm-cream); }
.vm-couple-bg {
  background-image: url('/minang-assets/paper-plos-p-1.jpg');
  background-position: center; background-size: contain;
  padding: 50px 30px;
}
.vm-couple-header {
  display: flex; justify-content: center;
  gap: 12px; margin-bottom: 20px;
}
.vm-tag-label {
  font-family: 'Aboreto', cursive;
  font-size: 54px; color: var(--vm-maroon); line-height: 1;
}
.vm-couple-intro {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon);
  line-height: 1.8; text-align: center; margin-bottom: 32px;
}
.vm-person-block {
  display: flex; align-items: center; gap: 20px;
  margin-bottom: 36px; padding: 20px;
  background: rgba(255,255,255,0.6); border-radius: 8px;
}
.vm-reverse { flex-direction: row-reverse; text-align: right; }
.vm-person-photo-wrap { flex-shrink: 0; }
.vm-person-photo {
  width: 130px; height: 160px; border-radius: 8px;
  overflow: hidden; display: flex;
  align-items: center; justify-content: center;
  position: relative;
}
.vm-bride-frame {
  background: linear-gradient(135deg, #fde8d5 0%, #f0c8a0 100%);
  border: 2px solid var(--vm-gold-warm);
  box-shadow: 0 4px 20px rgba(102,18,23,0.15);
}
.vm-groom-frame {
  background: linear-gradient(135deg, #f0e0d0 0%, #d4a870 100%);
  border: 2px solid var(--vm-gold-warm);
  box-shadow: 0 4px 20px rgba(102,18,23,0.15);
}
.vm-person-img { width: 100%; height: 100%; object-fit: cover; }
.vm-photo-letter {
  font-family: 'Aboreto', cursive;
  font-size: 60px; color: var(--vm-maroon); opacity: 0.5;
}
.vm-person-details { flex: 1; }
.vm-person-title-big {
  font-family: 'Pinyon Script', cursive;
  font-size: 69px; color: var(--vm-maroon);
  line-height: 1; margin-bottom: 4px;
}
.vm-person-from {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon); margin-bottom: 2px;
}
.vm-person-parents {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 600; color: var(--vm-maroon);
  line-height: 1.5; margin-bottom: 12px;
}

/* ====== EVENT ====== */
.vm-section-event-bg {
  position: relative;
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center; padding: 0;
}
.vm-event-bg-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(235,229,215,0) 0%, #FFECD7 100%);
}
.vm-event-wrap { position: relative; z-index: 2; padding: 50px 24px; }
.vm-event-tag {
  font-family: 'Aboreto', cursive;
  font-size: 52px; color: var(--vm-maroon);
  text-align: center; margin-bottom: 24px; line-height: 1.1;
}
.vm-countdown-row {
  display: flex; justify-content: center;
  align-items: center; gap: 0; margin-bottom: 40px;
}
.vm-cd-item {
  display: flex; flex-direction: column; align-items: center;
  background: rgba(102,18,23,0.08);
  border: 1px solid rgba(102,18,23,0.2);
  padding: 14px 16px; min-width: 70px;
  border-radius: 4px; margin: 0 4px;
}
.vm-cd-num {
  font-family: 'Aboreto', cursive;
  font-size: 43px; color: var(--vm-maroon);
  line-height: 1; font-weight: 400;
}
.vm-cd-lbl {
  font-family: 'Poppins', sans-serif;
  font-size: 11px; color: var(--vm-maroon);
  opacity: 0.7; text-transform: uppercase;
  letter-spacing: 1px; margin-top: 4px;
}
.vm-cd-sep {
  font-size: 2rem; color: var(--vm-maroon);
  font-weight: 300; padding: 0 2px;
  align-self: flex-start; padding-top: 12px;
}
.vm-acara-card {
  background: rgba(255,255,255,0.88);
  border: 1px solid rgba(102,18,23,0.15);
  border-radius: 8px; padding: 28px 24px;
  margin-bottom: 20px; text-align: center;
  box-shadow: 0 4px 20px rgba(102,18,23,0.08);
}
.vm-acara-title {
  font-family: 'Aboreto', cursive;
  font-size: 33px; color: var(--vm-maroon); margin-bottom: 12px;
}
.vm-acara-will {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-maroon); opacity: 0.7; margin-bottom: 4px;
}
.vm-acara-day {
  font-family: 'Caudex', serif;
  font-size: 20px; color: var(--vm-maroon);
  font-style: italic; margin-bottom: 2px;
}
.vm-acara-date {
  font-family: 'Aboreto', cursive;
  font-size: 26px; color: var(--vm-maroon);
  letter-spacing: 3px; margin-bottom: 4px;
}
.vm-acara-time {
  font-family: 'Poppins', sans-serif;
  font-size: 16px; font-weight: 600; color: var(--vm-maroon); margin-bottom: 16px;
}
.vm-acara-at {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon); opacity: 0.7; margin-bottom: 4px;
}
.vm-acara-venue {
  font-family: 'Aboreto', cursive;
  font-size: 22px; color: var(--vm-maroon); margin-bottom: 6px;
}
.vm-acara-addr {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon);
  line-height: 1.6; opacity: 0.8; margin-bottom: 16px;
}
.vm-btn-maps {
  display: inline-block;
  background: var(--vm-gold-grad);
  color: var(--vm-white);
  font-family: 'ZCOOL XiaoWei', serif;
  font-size: 16px; padding: 10px 28px;
  border-radius: 4px; text-decoration: none;
  transition: all 0.3s;
  box-shadow: 0 2px 12px rgba(161,112,54,0.4);
}
.vm-btn-maps:hover { opacity: 0.9; transform: translateY(-1px); }

/* ====== GALLERY ====== */
.vm-section-gallery {
  background: linear-gradient(165deg, #E1BF7C 0%, #A17036 100%);
}
.vm-gallery-inner { padding: 50px 24px; text-align: center; }
.vm-gallery-tag {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; color: var(--vm-maroon);
  letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
}
.vm-gallery-title {
  font-family: 'ZCOOL XiaoWei', serif;
  font-size: 23px; color: var(--vm-maroon);
  font-weight: 400; line-height: 1.5; margin-bottom: 16px;
}
.vm-gallery-ornament { text-align: center; margin: 12px 0 20px; }
.vm-gallery-ornament img { width: 160px; opacity: 0.7; filter: sepia(30%) saturate(80%); }

/* Carousel */
.vm-gallery-carousel {
  position: relative; width: 100%; aspect-ratio: 4/3;
  overflow: hidden; border-radius: 8px;
  margin-bottom: 16px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}
.vm-gc-track {
  display: flex; height: 100%;
  transition: transform 0.5s ease;
}
.vm-gc-slide {
  min-width: 100%; height: 100%;
  background-size: cover; background-position: center;
  background-color: #c8a070;
}
.vm-gc-btn {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(102,18,23,0.6);
  color: var(--vm-white); border: none;
  width: 36px; height: 36px;
  font-size: 1.4rem; cursor: pointer;
  border-radius: 50%; display: flex;
  align-items: center; justify-content: center;
  transition: background 0.2s; z-index: 10;
}
.vm-gc-btn:hover { background: var(--vm-maroon); }
.vm-gc-prev { left: 10px; }
.vm-gc-next { right: 10px; }
.vm-gc-dots {
  position: absolute; bottom: 10px; left: 50%;
  transform: translateX(-50%);
  display: flex; gap: 6px; z-index: 10;
}
.vm-gc-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: rgba(255,255,255,0.5);
  cursor: pointer; transition: background 0.2s;
}
.vm-gc-dot.active { background: var(--vm-white); }

/* Gallery Grid */
.vm-gallery-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 8px; margin-top: 8px;
}
.vm-gg-item {
  aspect-ratio: 1; background-size: cover;
  background-position: center; background-color: #c8a070;
  border-radius: 4px; overflow: hidden;
  transition: transform 0.3s; cursor: zoom-in;
}
.vm-gg-item:hover { transform: scale(1.02); }

/* ====== GIFT ====== */
.vm-section-gift {
  background: linear-gradient(165deg, #E1BF7C 0%, #A17036 100%);
}
.vm-gift-inner { padding: 60px 24px; text-align: center; }
.vm-gift-tag {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; color: var(--vm-maroon);
  letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
}
.vm-gift-title {
  font-family: 'Aboreto', cursive;
  font-size: 49px; color: var(--vm-maroon);
  line-height: 1.1; margin-bottom: 12px;
}
.vm-gift-desc {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-maroon);
  line-height: 1.8; margin-bottom: 28px; opacity: 0.85;
}
.vm-gift-cards { display: flex; flex-direction: column; gap: 16px; }
.vm-gift-card {
  background: rgba(255,255,255,0.85);
  border: 1px solid rgba(102,18,23,0.2);
  border-radius: 8px; padding: 24px; text-align: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.vm-gift-bank {
  font-family: 'Aboreto', cursive;
  font-size: 22px; color: var(--vm-maroon); margin-bottom: 6px;
}
.vm-gift-rek {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; font-weight: 600;
  color: var(--vm-maroon); letter-spacing: 2px; margin-bottom: 4px;
}
.vm-gift-an {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-text-brown); margin-bottom: 16px;
}
.vm-btn-salin {
  background: var(--vm-maroon); color: var(--vm-white);
  border: none; font-family: 'ZCOOL XiaoWei', serif;
  font-size: 16px; padding: 10px 28px;
  border-radius: 4px; cursor: pointer; transition: all 0.3s;
}
.vm-btn-salin:hover { background: var(--vm-maroon-dark); }

/* ====== RSVP ====== */
.vm-section-rsvp {
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center;
}
.vm-rsvp-bg {
  background: linear-gradient(123deg, rgba(0,0,0,0.76) 0%, rgba(102,18,23,0.9) 100%);
  min-height: 100%;
}
.vm-rsvp-inner { padding: 60px 24px; text-align: center; }
.vm-rsvp-tag {
  font-family: 'Aboreto', cursive;
  font-size: 56px; color: var(--vm-gold); margin-bottom: 10px;
}
.vm-rsvp-desc {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.8); margin-bottom: 20px;
}
.vm-rsvp-form {
  display: flex; flex-direction: column;
  gap: 14px; text-align: left;
}
.vm-form-row { display: flex; flex-direction: column; gap: 6px; }
.vm-form-row label {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-gold);
  font-weight: 600; letter-spacing: 0.5px;
}
.vm-form-row input[type="text"],
.vm-form-row select,
.vm-form-row textarea {
  width: 100%; padding: 10px 14px;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(223,181,132,0.4);
  color: var(--vm-cream);
  font-family: 'Poppins', sans-serif;
  font-size: 14px; border-radius: 4px;
  outline: none; transition: border-color 0.3s;
  resize: vertical;
}
.vm-form-row input::placeholder,
.vm-form-row textarea::placeholder { color: rgba(255,251,233,0.4); }
.vm-form-row input:focus,
.vm-form-row textarea:focus { border-color: var(--vm-gold); }
.vm-radio-row { flex-direction: row; gap: 16px; flex-wrap: wrap; }
.vm-r-opt {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-cream);
  display: flex; align-items: center;
  gap: 6px; cursor: pointer;
}
.vm-r-opt input { accent-color: var(--vm-gold); }
.vm-btn-submit {
  background: var(--vm-gold-grad);
  color: var(--vm-white); border: none;
  font-family: 'ZCOOL XiaoWei', serif;
  font-size: 18px; padding: 13px;
  border-radius: 4px; cursor: pointer;
  width: 100%; transition: opacity 0.3s;
  margin-top: 8px;
  box-shadow: 0 4px 16px rgba(161,112,54,0.5);
}
.vm-btn-submit:hover { opacity: 0.9; }
.vm-btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

/* ====== WISHES ====== */
.vm-section-wishes {
  background-image: url('/minang-assets/BG-UTAMA-MINANG-4.jpg');
  background-size: cover; background-position: center;
  padding: 60px 20px;
}
.vm-wishes-inner {
  padding: 36px 24px;
  text-align: center;
  max-width: 440px;
  margin: 0 auto;
  background: rgba(102, 18, 23, 0.88); /* Semi-transparent maroon card */
  border-radius: 12px;
  border: 1px solid rgba(223, 181, 132, 0.3); /* Premium gold border */
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(4px);
}
.vm-wishes-tag {
  font-family: 'Aboreto', cursive;
  font-size: 56px; color: var(--vm-gold); margin-bottom: 8px;
}
.vm-wishes-title {
  font-family: 'Poppins', sans-serif;
  font-size: 16px; color: rgba(255,251,233,0.7);
  font-weight: 300; margin-bottom: 20px;
}
.vm-wishes-list {
  display: flex; flex-direction: column;
  gap: 12px; text-align: left;
  max-height: 500px; overflow-y: auto; padding-right: 4px;
}
.vm-wish-item {
  background: rgba(255,255,255,0.06);
  border-left: 3px solid var(--vm-gold);
  padding: 14px 16px; border-radius: 6px;
  animation: vmWishIn 0.4s ease;
}
@keyframes vmWishIn {
  from { opacity: 0; transform: translateX(-16px); }
  to { opacity: 1; transform: none; }
}
.vm-wish-nama {
  font-family: 'Alike Angular', serif;
  font-size: 14px; color: var(--vm-gold); margin-bottom: 4px;
}
.vm-wish-pesan {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: rgba(255,251,233,0.8);
  line-height: 1.6; font-style: italic;
}
.vm-wish-waktu {
  font-size: 11px; color: rgba(255,251,233,0.4); margin-top: 6px;
}
.vm-wish-hadir {
  display: inline-block; font-size: 10px;
  padding: 2px 8px; border-radius: 20px;
  margin-top: 4px; font-family: 'Poppins', sans-serif;
}
.vm-hadir-yes { background: rgba(102,18,23,0.5); color: #FFE6C8; }
.vm-hadir-no { background: rgba(50,50,50,0.5); color: #778495; }
.vm-hadir-maybe { background: rgba(20,20,100,0.4); color: #9C8594; }

/* ====== CLOSING ====== */
.vm-section-closing {
  position: relative;
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center; overflow: hidden;
}
.vm-closing-overlay {
  position: absolute; inset: 0;
  background: var(--vm-maroon-grad); opacity: 0.88;
}
.vm-closing-inner {
  position: relative; z-index: 2;
  padding: 60px 30px; text-align: center;
}
.vm-closing-text {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.8);
  line-height: 1.9; margin-bottom: 24px;
}
.vm-closing-names {
  font-family: 'Aboreto', cursive;
  font-size: 49px; color: var(--vm-gold);
  margin-bottom: 12px; letter-spacing: 2px;
}
.vm-closing-by {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.5); margin-bottom: 8px;
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

/* ====== PARTICLES ====== */
.vm-particle {
  position: absolute; border-radius: 50%;
  pointer-events: none;
  animation: vmPfall linear infinite; opacity: 0;
}
@keyframes vmPfall {
  0% { opacity: 0.7; transform: translateY(-5px); }
  100% { opacity: 0; transform: translateY(100vh) translateX(var(--dx, 0px)); }
}

/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim="slideLeft"] { transform: translateX(-30px); }
[data-anim="slideRight"] { transform: translateX(30px); }
[data-anim].in { opacity: 1; transform: none; }

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
