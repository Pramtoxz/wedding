<template>
  <section ref="sectionRef" class="py-20 px-6 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#grid)" />
      </svg>
    </div>

    <div class="max-w-6xl mx-auto relative z-10">
      <div class="overflow-hidden mb-16 text-center">
        <h2 
          :class="['text-4xl md:text-5xl font-bold transform transition-all duration-1000', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0']"
          :style="{ color: 'var(--primary-color)' }"
        >
          Galeri
        </h2>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
        <div 
          v-for="(gallery, index) in galleries" 
          :key="gallery.id"
          :class="['group relative aspect-square rounded-2xl overflow-hidden shadow-lg transform transition-all duration-700', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0']"
          :style="{ transitionDelay: `${index * 0.1}s` }"
          @click="openLightbox(index)"
        >
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
          
          <img 
            :src="`/storage/${gallery.image_path}`" 
            :alt="gallery.caption"
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 cursor-pointer"
          />
          
          <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
            </svg>
          </div>
          
          <div v-if="gallery.caption" class="absolute bottom-0 left-0 right-0 p-4 text-white text-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-20">
            {{ gallery.caption }}
          </div>
        </div>
      </div>
    </div>

    <div 
      v-if="lightboxOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm"
      @click="closeLightbox"
    >
      <button 
        class="absolute top-6 right-6 text-white hover:scale-110 transition-transform"
        @click="closeLightbox"
      >
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      
      <button 
        v-if="currentImageIndex > 0"
        class="absolute left-6 text-white hover:scale-110 transition-transform"
        @click.stop="previousImage"
      >
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      
      <div class="max-w-5xl max-h-[90vh] p-4" @click.stop>
        <img 
          :src="`/storage/${galleries[currentImageIndex].image_path}`" 
          :alt="galleries[currentImageIndex].caption"
          class="max-w-full max-h-full object-contain rounded-lg shadow-2xl animate-scale-in"
        />
      </div>
      
      <button 
        v-if="currentImageIndex < galleries.length - 1"
        class="absolute right-6 text-white hover:scale-110 transition-transform"
        @click.stop="nextImage"
      >
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

defineProps<{
  galleries: any[]
}>()

const sectionRef = ref<HTMLElement | null>(null)
const isVisible = ref(false)
const lightboxOpen = ref(false)
const currentImageIndex = ref(0)

let observer: IntersectionObserver | null = null

const openLightbox = (index: number) => {
  currentImageIndex.value = index
  lightboxOpen.value = true
  document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
  lightboxOpen.value = false
  document.body.style.overflow = ''
}

const nextImage = () => {
  currentImageIndex.value++
}

const previousImage = () => {
  currentImageIndex.value--
}

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
      { threshold: 0.1 }
    )
    observer.observe(sectionRef.value)
  }
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
  document.body.style.overflow = ''
})
</script>

<style scoped>
@keyframes scale-in {
  from {
    transform: scale(0.9);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-scale-in {
  animation: scale-in 0.3s ease-out;
}
</style>

