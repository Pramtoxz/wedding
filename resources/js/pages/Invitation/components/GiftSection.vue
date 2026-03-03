<template>
  <section class="relative py-20 px-6 bg-gradient-to-b from-white via-gray-50 to-white overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <svg class="w-full h-full">
        <defs>
          <pattern id="gift-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
            <rect x="40" y="30" width="20" height="25" fill="currentColor" :style="{ color: 'var(--primary-color)' }" opacity="0.3"/>
            <rect x="35" y="30" width="30" height="5" fill="currentColor" :style="{ color: 'var(--accent-color)' }" opacity="0.3"/>
            <path d="M50,25 Q45,20 45,15 Q45,10 50,10 Q55,10 55,15 Q55,20 50,25" 
                  fill="currentColor" :style="{ color: 'var(--secondary-color)' }" opacity="0.3"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#gift-pattern)"/>
      </svg>
    </div>

    <div class="relative max-w-2xl mx-auto">
      <div ref="titleRef" class="text-center mb-12 transform translate-y-8 opacity-0 transition-all duration-1000">
        <div class="inline-block">
          <svg class="w-16 h-16 mx-auto mb-4" :style="{ color: 'var(--primary-color)' }" 
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                  d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
          </svg>
          <h2 class="text-4xl md:text-5xl font-bold mb-4" :style="{ color: 'var(--primary-color)' }">
            Amplop Digital
          </h2>
          <div class="h-1 bg-gradient-to-r from-transparent via-[var(--accent-color)] to-transparent rounded-full"></div>
        </div>
        <p class="mt-6 text-gray-600 text-lg max-w-xl mx-auto leading-relaxed">
          Doa restu Anda adalah karunia terindah bagi kami. Namun jika memberi adalah ungkapan kasih, Anda dapat mengirimkan melalui:
        </p>
      </div>
      
      <div class="space-y-6">
        <div 
          v-for="(gift, index) in gifts" 
          :key="gift.id"
          ref="giftRefs"
          class="group relative p-8 rounded-3xl shadow-xl border-2 bg-white/80 backdrop-blur-sm transform translate-y-12 opacity-0 transition-all duration-1000 hover:shadow-2xl hover:-translate-y-2"
          :style="{ 
            borderColor: 'var(--accent-color)',
            transitionDelay: `${index * 150}ms`
          }"
        >
          <div class="absolute -top-4 -left-4 w-24 h-24 rounded-full opacity-10 blur-2xl transition-all duration-500 group-hover:scale-150"
               :style="{ backgroundColor: 'var(--secondary-color)' }"></div>
          
          <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex-1 space-y-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                     :style="{ backgroundColor: 'var(--primary-color) + 20' }">
                  <svg class="w-6 h-6" :style="{ color: 'var(--primary-color)' }" 
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                  </svg>
                </div>
                <p class="font-bold text-xl md:text-2xl" :style="{ color: 'var(--primary-color)' }">
                  {{ gift.bank_name }}
                </p>
              </div>
              
              <div class="pl-15">
                <p class="text-2xl md:text-3xl font-mono font-bold mb-2 tracking-wider">
                  {{ gift.account_number }}
                </p>
                <p class="text-gray-600 text-sm md:text-base">
                  a.n <span class="font-semibold">{{ gift.account_name }}</span>
                </p>
              </div>
            </div>
            
            <button
              @click="$emit('copy', gift.account_number)"
              class="group/btn flex-shrink-0 flex items-center gap-3 px-6 py-4 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
              :style="{ backgroundColor: 'var(--secondary-color)' }"
            >
              <Copy class="w-5 h-5 transition-transform duration-300 group-hover/btn:scale-110" />
              <span class="hidden md:inline">Salin</span>
            </button>
          </div>
        </div>
      </div>

      <div ref="noteRef" class="mt-12 text-center transform translate-y-8 opacity-0 transition-all duration-1000">
        <div class="inline-block p-6 rounded-2xl bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200">
          <svg class="w-8 h-8 mx-auto mb-3" :style="{ color: 'var(--accent-color)' }" 
               fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <p class="text-gray-600 text-sm md:text-base">
            Terima kasih atas perhatian dan kehadirannya
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Copy } from 'lucide-vue-next'

defineProps<{
  gifts: any[]
}>()

defineEmits<{
  copy: [text: string]
}>()

const titleRef = ref<HTMLElement | null>(null)
const giftRefs = ref<HTMLElement[]>([])
const noteRef = ref<HTMLElement | null>(null)

const observerCallback = (entries: IntersectionObserverEntry[]) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.remove('translate-y-8', 'translate-y-12', 'opacity-0')
      entry.target.classList.add('translate-y-0', 'opacity-100')
    }
  })
}

let observer: IntersectionObserver | null = null

onMounted(() => {
  observer = new IntersectionObserver(observerCallback, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  })

  if (titleRef.value) observer.observe(titleRef.value)
  if (noteRef.value) observer.observe(noteRef.value)
  
  giftRefs.value.forEach(el => {
    if (el) observer?.observe(el)
  })
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>
