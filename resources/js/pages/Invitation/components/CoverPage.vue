<template>
  <section 
    class="fixed inset-0 z-50 flex items-center justify-center overflow-hidden"
  >
    <div class="absolute inset-0 bg-gradient-to-br from-[var(--primary-color)] to-[var(--secondary-color)]">
      <div class="absolute inset-0 opacity-10">
        <svg class="absolute inset-0 w-full h-full">
          <defs>
            <pattern id="hearts" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
              <path d="M50,25 C50,15 35,10 25,20 C15,10 0,15 0,25 C0,40 25,50 25,50 C25,50 50,40 50,25 Z" 
                    fill="white" opacity="0.1"/>
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#hearts)"/>
        </svg>
      </div>
      
      <div class="absolute inset-0 opacity-20">
        <div v-for="i in 30" :key="i" 
          class="absolute rounded-full bg-white/30 blur-xl"
          :style="{
            width: `${Math.random() * 200 + 100}px`,
            height: `${Math.random() * 200 + 100}px`,
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            animation: `float ${Math.random() * 20 + 15}s ease-in-out infinite`,
            animationDelay: `${Math.random() * 5}s`
          }"
        ></div>
      </div>
    </div>

    <div class="relative z-10 text-center text-white px-4 max-w-2xl w-full">
      <div ref="lottieContainer" class="w-32 h-32 md:w-48 md:h-48 mx-auto mb-6 transform hover:scale-110 transition-transform duration-500"></div>
      
      <div class="mb-8 space-y-6">
        <div class="opacity-0 animate-fade-in" style="animation-delay: 0.2s">
          <h1 class="text-2xl md:text-4xl font-bold tracking-wide" 
              style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3)">
            Undangan Pernikahan
          </h1>
        </div>
        
        <div class="relative py-4">
          <div class="mb-3 opacity-0 animate-fade-in" style="animation-delay: 0.5s">
            <p class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight" 
               style="text-shadow: 3px 3px 6px rgba(0,0,0,0.4)">
              {{ brideName }}
            </p>
          </div>
          
          <div class="flex items-center justify-center my-4">
            <div class="h-px w-16 md:w-24 bg-white/60"></div>
            <svg class="w-8 h-8 md:w-12 md:h-12 mx-3 md:mx-4 opacity-0 animate-scale-in flex-shrink-0" 
                 style="animation-delay: 0.8s; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3))" 
                 viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <div class="h-px w-16 md:w-24 bg-white/60"></div>
          </div>
          
          <div class="mt-3 opacity-0 animate-fade-in" style="animation-delay: 3.1s">
            <p class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight" 
               style="text-shadow: 3px 3px 6px rgba(0,0,0,0.4)">
              {{ groomName }}
            </p>
          </div>
        </div>
      </div>
      
      <div v-if="guestName" class="mb-6 opacity-0 animate-fade-scale" style="animation-delay: 1.4s">
        <div class="inline-block px-6 md:px-8 py-3 md:py-4 bg-white/15 backdrop-blur-lg rounded-xl md:rounded-2xl border border-white/30 shadow-xl">
          <p class="text-xs md:text-sm tracking-wider uppercase opacity-90 mb-1">Kepada Yth.</p>
          <p class="text-lg md:text-2xl font-semibold">{{ guestName }}</p>
        </div>
        
      <p class="mt-4 text-xs md:text-sm opacity-70 animate-fade-scale">Mohon Maaf bila Ada Kesalahan dalam Penulisan Nama/Gelar</p>
      </div>
      
      <div class="opacity-0 animate-fade-scale" style="animation-delay: 1.7s">
        <button
          @click="$emit('open')"
          class="group relative px-8 md:px-10 py-3 md:py-4 bg-white text-[var(--primary-color)] rounded-full font-semibold text-base md:text-lg overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-105"
        >
          <span class="relative z-10 flex items-center gap-2 md:gap-3">
            <span>Buka Undangan</span>
            <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform duration-300 group-hover:translate-x-2" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
          </span>
          <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
        </button>
      </div>
      
      <p class="mt-4 text-xs md:text-sm opacity-70 animate-pulse-subtle">Sentuh untuk membuka</p>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import lottie from 'lottie-web'
import paperPlaneAnimation from '@/assets/lottie/email.json'

defineProps<{
  brideName: string
  groomName: string
  guestName?: string
}>()

defineEmits<{
  open: []
}>()

const lottieContainer = ref<HTMLElement | null>(null)
let lottieAnimation: any = null

onMounted(() => {
  if (lottieContainer.value) {
    lottieAnimation = lottie.loadAnimation({
      container: lottieContainer.value,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      animationData: paperPlaneAnimation,
    })
  }
})

onUnmounted(() => {
  if (lottieAnimation) {
    lottieAnimation.destroy()
  }
})
</script>

<style scoped>
@keyframes float {
  0%, 100% { 
    transform: translate(0, 0) scale(1);
    opacity: 0.3;
  }
  33% { 
    transform: translate(30px, -30px) scale(1.1);
    opacity: 0.5;
  }
  66% { 
    transform: translate(-20px, 20px) scale(0.9);
    opacity: 0.4;
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scale-in {
  from {
    transform: scale(0);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes fade-scale {
  from {
    transform: scale(0.95);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes pulse-subtle {
  0%, 100% { opacity: 0.6; }
  50% { opacity: 0.4; }
}

.animate-fade-in {
  animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-scale-in {
  animation: scale-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.animate-fade-scale {
  animation: fade-scale 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-pulse-subtle {
  animation: pulse-subtle 3s ease-in-out infinite;
}
</style>


