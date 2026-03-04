<template>
  <section class="relative py-20 px-6 bg-gradient-to-b from-white to-gray-50 overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
        <pattern id="quran-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
          <circle cx="10" cy="10" r="1" fill="currentColor" :style="{ color: primaryColor }" />
        </pattern>
        <rect width="100" height="100" fill="url(#quran-pattern)" />
      </svg>
    </div>

    <div class="relative max-w-4xl mx-auto text-center">
      <div class="mb-8">
        <div class="inline-block px-6 py-2 rounded-full border-2 mb-6" :style="{ borderColor: primaryColor, color: primaryColor }">
          <span class="text-sm md:text-base font-semibold">QS. Ar-Rum: 21</span>
        </div>
        
        <p class="text-2xl md:text-3xl lg:text-4xl font-arabic leading-relaxed mb-8 text-gray-800" dir="rtl">
          وَمِنْ اٰيٰتِهٖٓ اَنْ خَلَقَ لَكُمْ مِّنْ اَنْفُسِكُمْ اَزْوَاجًا لِّتَسْكُنُوْٓا اِلَيْهَا وَجَعَلَ بَيْنَكُمْ مَّوَدَّةً وَّرَحْمَةً ۗاِنَّ فِيْ ذٰلِكَ لَاٰيٰتٍ لِّقَوْمٍ يَّتَفَكَّرُوْنَ
        </p>

        <div class="max-w-3xl mx-auto mb-12">
          <p class="text-base md:text-lg text-gray-600 leading-relaxed italic">
            "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."
          </p>
        </div>
      </div>

      <div ref="lottieContainer" class="w-full h-full md:w-64 md:h-64 mx-auto mb-8"></div>

      <div class="flex items-center justify-center gap-4 mb-6">
        <div class="h-px flex-1 max-w-32 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
        <svg class="w-8 h-8 flex-shrink-0" :style="{ color: primaryColor }" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        <div class="h-px flex-1 max-w-32 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
      </div>

      <div class="space-y-2 mb-12">
        <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ brideName }} & {{ groomName }}</p>
        <p class="text-sm md:text-base text-gray-500">Kami yang berbahagia</p>
      </div>

      <div class="py-12 px-6 rounded-2xl" :style="{ backgroundColor: primaryColor }">
        <p class="text-white text-lg mb-4">{{ closingText }}</p>
        <p class="text-white/80">{{ brideName }} & {{ groomName }}</p>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import lottie from 'lottie-web'
import paperPlaneAnimation from '@/assets/lottie/wedding.json'

defineProps<{
  brideName: string
  groomName: string
  primaryColor: string
  closingText: string | null
}>()

const lottieContainer = ref<HTMLElement | null>(null)
let lottieAnimation: any = null

onMounted(() => {
  if (lottieContainer.value) {
    lottieAnimation = lottie.loadAnimation({
      container: lottieContainer.value,
      renderer: 'svg',
      loop: false,
      autoplay: true,
      animationData: paperPlaneAnimation,
    })

    lottieAnimation.addEventListener('complete', () => {
      setTimeout(() => {
        lottieAnimation.goToAndPlay(0)
      }, 2000) 
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
@import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');

.font-arabic {
  font-family: 'Amiri', serif;
}
</style>
