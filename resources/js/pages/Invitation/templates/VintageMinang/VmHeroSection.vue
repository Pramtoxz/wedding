<template>
  <!-- ====== HERO / HOME SECTION ====== -->
  <section class="vm-section-hero" :style="heroStyle">
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
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { Wedding } from './types'
import { formatDateDots } from './helpers'

const props = defineProps<{ wedding: Wedding }>()

const particlesRef = ref<HTMLElement | null>(null)

const heroStyle = computed(() => ({
  backgroundImage: props.wedding.cover_image
    ? `url('/storage/${props.wedding.cover_image}')`
    : `url('/minang-assets/home-minang.jpg')`,
}))

onMounted(() => initParticles())

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
</script>

<style scoped>
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
  top: -210px;
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
</style>
