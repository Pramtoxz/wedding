<template>
  <section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div 
      class="absolute inset-0 bg-cover bg-center"
      :style="{ 
        backgroundImage: coverImage ? `url(/storage/${coverImage})` : 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))'
      }"
    ></div>
    <div class="absolute inset-0 bg-black/20"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-5xl w-full">
      <div class="backdrop-blur-md bg-gradient-to-b from-white/10 to-white/5 rounded-2xl md:rounded-3xl p-6 md:p-16 border border-white/20 shadow-2xl">
        <div v-if="openingText" class="mb-6 opacity-0 animate-fade-in-smooth" style="animation-delay: 0.2s">
          <p class="text-sm md:text-lg opacity-90 leading-relaxed px-2">
            {{ openingText }}
          </p>
        </div>
        
        <div class="space-y-4 md:space-y-6 mb-8">
          <div>
            <h1 class="text-4xl md:text-7xl lg:text-8xl font-bold opacity-0 animate-fade-in-smooth leading-tight" 
                style="text-shadow: 3px 3px 6px rgba(0,0,0,0.4); animation-delay: 0.4s">
              {{ brideName }}
            </h1>
          </div>
          
          <div class="flex items-center justify-center gap-4 md:gap-6 my-6">
            <div class="h-px flex-1 max-w-[60px] md:max-w-none bg-gradient-to-r from-transparent via-white/60 to-transparent opacity-0 animate-fade-in-smooth" style="animation-delay: 0.6s"></div>
            <svg class="w-8 h-8 md:w-10 md:h-10 opacity-0 animate-scale-bounce flex-shrink-0" 
                 style="animation-delay: 0.8s" 
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <div class="h-px flex-1 max-w-[60px] md:max-w-none bg-gradient-to-r from-transparent via-white/60 to-transparent opacity-0 animate-fade-in-smooth" style="animation-delay: 0.6s"></div>
          </div>
          
          <div>
            <h1 class="text-4xl md:text-7xl lg:text-8xl font-bold opacity-0 animate-fade-in-smooth leading-tight"
                style="text-shadow: 3px 3px 6px rgba(0,0,0,0.4); animation-delay: 1s">
              {{ groomName }}
            </h1>
          </div>
        </div>
        
        <div class="inline-block px-6 md:px-8 py-3 md:py-4 bg-white/15 backdrop-blur-lg rounded-full border border-white/30 opacity-0 animate-fade-scale-smooth mb-6" style="animation-delay: 1.2s">
          <p class="text-base md:text-2xl font-light tracking-wide">{{ formattedDate }}</p>
        </div>
        
        <div class="grid grid-cols-4 gap-2 md:gap-4 max-w-md mx-auto opacity-0 animate-fade-up-smooth" style="animation-delay: 1.4s">
          <div v-for="(value, label) in countdown" :key="label" 
               class="bg-white/10 backdrop-blur-lg rounded-xl md:rounded-2xl p-3 md:p-4 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
            <div class="text-2xl md:text-4xl lg:text-5xl font-bold mb-1">{{ value }}</div>
            <div class="text-[10px] md:text-sm opacity-75 uppercase tracking-wider">{{ label }}</div>
          </div>
        </div>
      </div>
      
      <div class="mt-8 md:mt-10 animate-bounce-gentle">
        <svg class="w-6 h-6 md:w-8 md:h-8 mx-auto text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps<{
  brideName: string
  groomName: string
  coverImage: string | null
  openingText: string | null
  formattedDate: string
  weddingDate: string
}>()

const scrollY = ref(0)
const parallaxBg = ref<HTMLElement | null>(null)
const countdown = ref<{ hari: number; jam: number; menit: number; detik: number } | null>(null)

const handleScroll = () => {
  scrollY.value = window.scrollY
}

const updateCountdown = () => {
  const now = new Date().getTime()
  const weddingTime = new Date(props.weddingDate).getTime()
  const distance = weddingTime - now

  if (distance > 0) {
    countdown.value = {
      hari: Math.floor(distance / (1000 * 60 * 60 * 24)),
      jam: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
      menit: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
      detik: Math.floor((distance % (1000 * 60)) / 1000)
    }
  }
}

let countdownInterval: number

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  updateCountdown()
  countdownInterval = window.setInterval(updateCountdown, 1000)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  if (countdownInterval) {
    clearInterval(countdownInterval)
  }
})
</script>

<style scoped>
@keyframes fade-in-smooth {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scale-bounce {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes fade-scale-smooth {
  from {
    transform: scale(0.9);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes fade-up-smooth {
  from {
    transform: translateY(30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes bounce-gentle {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-fade-in-smooth {
  animation: fade-in-smooth 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-scale-bounce {
  animation: scale-bounce 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-fade-scale-smooth {
  animation: fade-scale-smooth 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-up-smooth {
  animation: fade-up-smooth 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-bounce-gentle {
  animation: bounce-gentle 2s ease-in-out infinite;
}
</style>


