<template>
  <div class="fixed bottom-0 left-0 right-0 z-40 pointer-events-none">
    <div class="relative mx-auto max-w-md px-3 pb-3">
      <nav class="pointer-events-auto backdrop-blur-xl bg-white/90 dark:bg-gray-900/90 rounded-full shadow-xl border border-gray-200/50 dark:border-gray-700/50 px-2 py-2">
        <div class="flex items-center justify-around">
          <button
            v-for="item in navItems"
            :key="item.id"
            @click="handleNavClick(item.id)"
            :class="[
              'group relative flex flex-col items-center justify-center gap-0.5 px-2 py-1.5 rounded-xl transition-all duration-300',
              activeSection === item.id 
                ? 'scale-105' 
                : 'hover:scale-105'
            ]"
          >
            <div 
              :class="[
                'relative flex items-center justify-center w-8 h-8 rounded-full transition-all duration-300',
                activeSection === item.id 
                  ? 'shadow-md' 
                  : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800'
              ]"
              :style="activeSection === item.id ? { 
                backgroundColor: primaryColor,
                boxShadow: `0 2px 12px ${primaryColor}40`
              } : {}"
            >
              <component 
                :is="item.icon" 
                :class="[
                  'w-4 h-4 transition-colors duration-300',
                  activeSection === item.id 
                    ? 'text-white' 
                    : 'text-gray-600 dark:text-gray-400'
                ]"
              />
            </div>
            
            <span 
              :class="[
                'text-[9px] font-medium transition-all duration-300 whitespace-nowrap',
                activeSection === item.id 
                  ? 'opacity-100 font-semibold' 
                  : 'opacity-60'
              ]"
              :style="activeSection === item.id ? { color: primaryColor } : {}"
            >
              {{ item.label }}
            </span>
          </button>
        </div>
      </nav>
    </div>

    <!-- Page Transition Animation -->
    <Transition name="page-flip">
      <div 
        v-if="showTransition" 
        class="fixed inset-0 z-50 pointer-events-none"
        :style="{ transformOrigin: 'bottom right' }"
      >
        <div 
          ref="lottieContainer" 
          class="absolute bottom-0 right-0 w-full h-full"
          :style="transitionStyle"
        ></div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Home, Users, Calendar, Image, MessageCircle, Gift } from 'lucide-vue-next'
import lottie from 'lottie-web'
import paperPlaneAnimation from '@/assets/lottie/paper-plane.json'

defineProps<{
  primaryColor: string
}>()

const activeSection = ref('hero')
const showTransition = ref(false)
const lottieContainer = ref<HTMLElement | null>(null)
let lottieAnimation: any = null

const navItems = [
  { id: 'hero', label: 'Home', icon: Home },
  { id: 'couple', label: 'Couple', icon: Users },
  { id: 'event', label: 'Event', icon: Calendar },
  { id: 'gallery', label: 'Gallery', icon: Image },
  { id: 'wish', label: 'Wishes', icon: MessageCircle },
  { id: 'gift', label: 'Gift', icon: Gift },
]

const transitionStyle = computed(() => ({
  animation: 'pageFlip 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards'
}))

const handleNavClick = (sectionId: string) => {
  // Trigger animation
  showTransition.value = true
  
  // Initialize lottie animation
  setTimeout(() => {
    if (lottieContainer.value && !lottieAnimation) {
      lottieAnimation = lottie.loadAnimation({
        container: lottieContainer.value,
        renderer: 'svg',
        loop: false,
        autoplay: true,
        animationData: paperPlaneAnimation,
      })
    } else if (lottieAnimation) {
      lottieAnimation.goToAndPlay(0)
    }
  }, 50)
  
  // Scroll to section
  setTimeout(() => {
    scrollToSection(sectionId)
  }, 200)
  
  // Hide animation
  setTimeout(() => {
    showTransition.value = false
    if (lottieAnimation) {
      lottieAnimation.destroy()
      lottieAnimation = null
    }
  }, 800)
}

const scrollToSection = (sectionId: string) => {
  const element = document.getElementById(sectionId)
  if (element) {
    const offset = 80
    const elementPosition = element.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - offset

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    })
  }
}

const handleScroll = () => {
  const sections = navItems.map(item => ({
    id: item.id,
    element: document.getElementById(item.id)
  })).filter(s => s.element)

  const scrollPosition = window.scrollY + 200

  for (const section of sections) {
    if (section.element) {
      const top = section.element.offsetTop
      const bottom = top + section.element.offsetHeight

      if (scrollPosition >= top && scrollPosition < bottom) {
        activeSection.value = section.id
        break
      }
    }
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  handleScroll()
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  if (lottieAnimation) {
    lottieAnimation.destroy()
  }
})
</script>

<style scoped>
@keyframes pageFlip {
  0% {
    transform: translate(0, 0) scale(0.3) rotate(0deg);
    opacity: 0.8;
  }
  50% {
    transform: translate(-30vw, -30vh) scale(1.5) rotate(-15deg);
    opacity: 1;
  }
  100% {
    transform: translate(-80vw, -80vh) scale(3) rotate(-30deg);
    opacity: 0;
  }
}

.page-flip-enter-active,
.page-flip-leave-active {
  transition: opacity 0.3s ease;
}

.page-flip-enter-from,
.page-flip-leave-to {
  opacity: 0;
}
</style>
