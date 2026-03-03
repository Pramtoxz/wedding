<template>
  <section class="relative py-20 px-6 bg-gradient-to-b from-white via-gray-50 to-white overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <svg class="w-full h-full">
        <defs>
          <pattern id="event-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
            <circle cx="40" cy="40" r="1.5" fill="currentColor" :style="{ color: 'var(--primary-color)' }"/>
            <circle cx="0" cy="0" r="1" fill="currentColor" :style="{ color: 'var(--secondary-color)' }"/>
            <circle cx="80" cy="80" r="1" fill="currentColor" :style="{ color: 'var(--accent-color)' }"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#event-pattern)"/>
      </svg>
    </div>

    <div class="relative max-w-4xl mx-auto">
      <div ref="titleRef" class="text-center mb-16 transform translate-y-8 opacity-0 transition-all duration-1000">
        <div class="inline-block">
          <h2 class="text-4xl md:text-5xl font-bold mb-4" :style="{ color: 'var(--primary-color)' }">
            Acara
          </h2>
          <div class="h-1 bg-gradient-to-r from-transparent via-[var(--accent-color)] to-transparent rounded-full"></div>
        </div>
      </div>
      
      <div class="space-y-8">
        <div 
          v-for="(event, index) in events" 
          :key="event.id"
          ref="eventRefs"
          class="group relative p-8 rounded-3xl shadow-xl border-2 bg-white/80 backdrop-blur-sm transform translate-y-12 opacity-0 transition-all duration-1000 hover:shadow-2xl hover:-translate-y-2"
          :style="{ 
            borderColor: 'var(--accent-color)',
            transitionDelay: `${index * 150}ms`
          }"
        >
          <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-10 blur-2xl transition-all duration-500 group-hover:scale-150"
               :style="{ backgroundColor: 'var(--primary-color)' }"></div>
          
          <div class="relative">
            <h3 class="text-2xl md:text-3xl font-bold mb-6 flex items-center gap-3" :style="{ color: 'var(--primary-color)' }">
              <div class="w-2 h-8 rounded-full" :style="{ backgroundColor: 'var(--accent-color)' }"></div>
              {{ event.event_name }}
            </h3>
            
            <div class="space-y-4 text-gray-700">
              <div class="flex items-center gap-4 p-3 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-gray-100 transition-colors">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" 
                     :style="{ backgroundColor: 'var(--secondary-color) + 20' }">
                  <Calendar class="w-6 h-6" :style="{ color: 'var(--secondary-color)' }" />
                </div>
                <span class="text-lg">{{ formatDate(event.event_date) }}</span>
              </div>
              
              <div class="flex items-center gap-4 p-3 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-gray-100 transition-colors">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" 
                     :style="{ backgroundColor: 'var(--secondary-color) + 20' }">
                  <Clock class="w-6 h-6" :style="{ color: 'var(--secondary-color)' }" />
                </div>
                <span class="text-lg">{{ event.event_time }} WIB</span>
              </div>
              
              <div class="flex items-start gap-4 p-3 rounded-xl bg-gradient-to-r from-gray-50 to-transparent hover:from-gray-100 transition-colors">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" 
                     :style="{ backgroundColor: 'var(--secondary-color) + 20' }">
                  <MapPin class="w-6 h-6" :style="{ color: 'var(--secondary-color)' }" />
                </div>
                <div>
                  <p class="font-semibold text-lg">{{ event.location_name }}</p>
                  <p class="text-sm text-gray-600 mt-1">{{ event.address }}</p>
                </div>
              </div>
            </div>
            
            <a 
              v-if="event.map_url"
              :href="event.map_url"
              target="_blank"
              class="mt-6 inline-flex items-center gap-3 px-8 py-4 rounded-full text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group/btn"
              :style="{ backgroundColor: 'var(--primary-color)' }"
            >
              <MapPin class="w-5 h-5" />
              <span>Lihat Lokasi</span>
              <svg class="w-5 h-5 transition-transform duration-300 group-hover/btn:translate-x-1" 
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Calendar, Clock, MapPin } from 'lucide-vue-next'

defineProps<{
  events: any[]
}>()

const titleRef = ref<HTMLElement | null>(null)
const eventRefs = ref<HTMLElement[]>([])

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

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

  if (titleRef.value) {
    observer.observe(titleRef.value)
  }

  eventRefs.value.forEach(el => {
    if (el) observer?.observe(el)
  })
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>
