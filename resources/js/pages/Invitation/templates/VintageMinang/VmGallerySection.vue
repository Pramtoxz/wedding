<template>
  <!-- ====== GALLERY ====== -->
  <section v-if="wedding.galleries && wedding.galleries.length > 0" class="vm-section-gallery">
    <div class="vm-gallery-inner" data-anim="fadeUp">
      <p class="vm-gallery-tag">Our Gallery</p>
      <h2 class="vm-gallery-title">Constantly, consistently,<br>continually, You.</h2>

      <!-- Carousel (first 6) -->
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

      <!-- Grid (remaining) -->
      <div v-if="gridGalleries.length > 0" class="vm-gallery-grid">
        <div
          v-for="g in gridGalleries"
          :key="g.id"
          class="vm-gg-item"
          :style="{ backgroundImage: `url('/storage/${g.image_path}')` }"
          @click="$emit('lightbox', `/storage/${g.image_path}`)"
        ></div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import type { Wedding } from './types'

const props = defineProps<{ wedding: Wedding }>()
defineEmits<{ lightbox: [src: string] }>()

const carouselIdx = ref(0)
let carouselTimer: ReturnType<typeof setInterval> | null = null

const carouselGalleries = computed(() => props.wedding.galleries.slice(0, 6))
const gridGalleries = computed(() => props.wedding.galleries.slice(6, 12))

onMounted(() => {
  if (carouselGalleries.value.length > 0) startCarouselTimer()
})
onUnmounted(() => { if (carouselTimer) clearInterval(carouselTimer) })

function moveCarousel(dir: number) {
  const total = carouselGalleries.value.length
  if (!total) return
  carouselIdx.value = (carouselIdx.value + dir + total) % total
}

function startCarouselTimer() {
  if (carouselTimer) clearInterval(carouselTimer)
  carouselTimer = setInterval(() => moveCarousel(1), 4000)
}
</script>

<style scoped>
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

/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
