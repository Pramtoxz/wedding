<template>
  <section ref="sectionRef" class="py-20 px-6 bg-gradient-to-b from-white to-gray-50 relative overflow-hidden">
    <svg class="absolute top-0 left-0 w-full h-32 text-gray-100" preserveAspectRatio="none" viewBox="0 0 1200 120">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
    </svg>

    <div class="max-w-4xl mx-auto text-center relative z-10">
      <div class="overflow-hidden mb-16">
        <h2 
          :class="['text-4xl md:text-5xl font-bold mb-4 transform transition-all duration-1000', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0']"
          :style="{ color: 'var(--primary-color)', transitionDelay: '0.1s' }"
        >
          Mempelai
        </h2>
      </div>
      
      <div class="grid md:grid-cols-2 gap-16 md:gap-12">
        <div 
          :class="['space-y-6 transform transition-all duration-1000', isVisible ? 'translate-x-0 opacity-100' : '-translate-x-20 opacity-0']"
          style="transition-delay: 0.3s"
        >
          <div class="relative inline-block group">
            <div class="absolute inset-0 bg-gradient-to-br from-[var(--primary-color)] to-[var(--secondary-color)] rounded-full blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div v-if="bridePhoto" class="relative w-40 h-40 mx-auto rounded-full overflow-hidden shadow-2xl transform group-hover:scale-110 transition-transform duration-500 border-4 border-white">
              <img :src="`/storage/${bridePhoto}`" :alt="brideName" class="w-full h-full object-cover">
            </div>
            <div v-else class="relative w-40 h-40 mx-auto rounded-full bg-gradient-to-br from-[var(--primary-color)] to-[var(--secondary-color)] flex items-center justify-center text-white text-6xl font-bold shadow-2xl transform group-hover:scale-110 transition-transform duration-500">
              {{ brideName.charAt(0) }}
            </div>
          </div>
          
          <div class="space-y-3">
            <h3 class="text-3xl md:text-4xl font-bold">{{ brideName }}</h3>
            <div class="h-px w-24 mx-auto bg-gradient-to-r from-transparent via-[var(--primary-color)] to-transparent"></div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Putri dari</p>
            <div class="space-y-1">
              <p v-if="brideFatherName" class="font-semibold text-lg">{{ brideFatherName }}</p>
              <p v-if="brideMotherName" class="font-semibold text-lg">{{ brideMotherName }}</p>
            </div>
          </div>
        </div>

        <div 
          :class="['space-y-6 transform transition-all duration-1000', isVisible ? 'translate-x-0 opacity-100' : 'translate-x-20 opacity-0']"
          style="transition-delay: 0.5s"
        >
          <div class="relative inline-block group">
            <div class="absolute inset-0 bg-gradient-to-br from-[var(--secondary-color)] to-[var(--accent-color)] rounded-full blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div v-if="groomPhoto" class="relative w-40 h-40 mx-auto rounded-full overflow-hidden shadow-2xl transform group-hover:scale-110 transition-transform duration-500 border-4 border-white">
              <img :src="`/storage/${groomPhoto}`" :alt="groomName" class="w-full h-full object-cover">
            </div>
            <div v-else class="relative w-40 h-40 mx-auto rounded-full bg-gradient-to-br from-[var(--secondary-color)] to-[var(--accent-color)] flex items-center justify-center text-white text-6xl font-bold shadow-2xl transform group-hover:scale-110 transition-transform duration-500">
              {{ groomName.charAt(0) }}
            </div>
          </div>
          
          <div class="space-y-3">
            <h3 class="text-3xl md:text-4xl font-bold">{{ groomName }}</h3>
            <div class="h-px w-24 mx-auto bg-gradient-to-r from-transparent via-[var(--secondary-color)] to-transparent"></div>
            <p class="text-gray-600 text-sm uppercase tracking-wider">Putra dari</p>
            <div class="space-y-1">
              <p v-if="groomFatherName" class="font-semibold text-lg">{{ groomFatherName }}</p>
              <p v-if="groomMotherName" class="font-semibold text-lg">{{ groomMotherName }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <svg class="absolute bottom-0 left-0 w-full h-32 text-white rotate-180" preserveAspectRatio="none" viewBox="0 0 1200 120">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
    </svg>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

defineProps<{
  brideName: string
  brideFatherName: string | null
  brideMotherName: string | null
  bridePhoto: string | null
  groomName: string
  groomFatherName: string | null
  groomMotherName: string | null
  groomPhoto: string | null
}>()

const sectionRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)

let observer: IntersectionObserver | null = null

onMounted(() => {
  if (sectionRef.value) {
    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            isVisible.value = true
          }
        })
      },
      { threshold: 0.2 }
    )
    observer.observe(sectionRef.value)
  }
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>

