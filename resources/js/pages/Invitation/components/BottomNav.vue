<template>
  <div class="fixed bottom-0 left-0 right-0 z-40 pointer-events-none">
    <div class="relative mx-auto max-w-md px-3 pb-3">
      <nav class="pointer-events-auto backdrop-blur-xl bg-white/90 dark:bg-gray-900/90 rounded-full shadow-xl border border-gray-200/50 dark:border-gray-700/50 px-2 py-2">
        <div class="flex items-center justify-around">
          <button
            v-for="item in navItems"
            :key="item.id"
            @click="scrollToSection(item.id)"
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
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Home, Users, Calendar, Image, MessageCircle, Gift } from 'lucide-vue-next'

defineProps<{
  primaryColor: string
}>()

const activeSection = ref('hero')

const navItems = [
  { id: 'hero', label: 'Home', icon: Home },
  { id: 'couple', label: 'Couple', icon: Users },
  { id: 'event', label: 'Event', icon: Calendar },
  { id: 'gallery', label: 'Gallery', icon: Image },
  { id: 'wish', label: 'Wishes', icon: MessageCircle },
  { id: 'gift', label: 'Gift', icon: Gift },
]

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
})
</script>
