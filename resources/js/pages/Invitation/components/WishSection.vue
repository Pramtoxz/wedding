<template>
  <section class="relative py-20 px-6 bg-gradient-to-b from-gray-50 via-white to-gray-50 overflow-hidden">
    <div class="absolute inset-0 opacity-5">
      <svg class="w-full h-full">
        <defs>
          <pattern id="wish-pattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
            <path d="M30,15 L32,21 L38,21 L33,25 L35,31 L30,27 L25,31 L27,25 L22,21 L28,21 Z" 
                  fill="currentColor" :style="{ color: 'var(--accent-color)' }" opacity="0.3"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#wish-pattern)"/>
      </svg>
    </div>

    <div class="relative max-w-2xl mx-auto">
      <div ref="titleRef" class="text-center mb-12 transform translate-y-8 opacity-0 transition-all duration-1000">
        <div class="inline-block">
          <h2 class="text-4xl md:text-5xl font-bold mb-4" :style="{ color: 'var(--primary-color)' }">
            Ucapan & Doa
          </h2>
          <div class="h-1 bg-gradient-to-r from-transparent via-[var(--accent-color)] to-transparent rounded-full"></div>
        </div>
        <p class="mt-6 text-gray-600 text-lg">Berikan ucapan dan doa terbaik untuk mempelai</p>
      </div>
      
      <form 
        ref="formRef"
        @submit.prevent="$emit('submit', form)" 
        class="mb-12 p-8 rounded-3xl shadow-xl border-2 bg-white/80 backdrop-blur-sm transform translate-y-12 opacity-0 transition-all duration-1000"
        :style="{ borderColor: 'var(--accent-color)' }"
      >
        <div class="space-y-6">
          <div class="relative">
            <input
              v-model="form.guest_name"
              type="text"
              placeholder="Nama Anda"
              required
              class="w-full px-6 py-4 rounded-xl border-2 focus:outline-none transition-all duration-300 bg-white"
              :class="{ 'border-[var(--primary-color)] shadow-lg': form.guest_name }"
              :style="{ 
                borderColor: form.guest_name ? 'var(--primary-color)' : '#e5e7eb'
              }"
            />
          </div>
          
          <div class="relative">
            <textarea
              v-model="form.message"
              placeholder="Tulis ucapan & doa untuk mempelai..."
              required
              rows="5"
              class="w-full px-6 py-4 rounded-xl border-2 focus:outline-none transition-all duration-300 resize-none bg-white"
              :class="{ 'border-[var(--primary-color)] shadow-lg': form.message }"
              :style="{ 
                borderColor: form.message ? 'var(--primary-color)' : '#e5e7eb'
              }"
            ></textarea>
          </div>
          
          <button
            type="submit"
            :disabled="processing"
            class="group w-full py-4 rounded-xl text-white font-semibold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden"
            :style="{ backgroundColor: 'var(--primary-color)' }"
          >
            <span class="relative z-10 flex items-center justify-center gap-3">
              <svg v-if="!processing" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
              <svg v-else class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span>{{ processing ? 'Mengirim...' : 'Kirim Ucapan' }}</span>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
          </button>
        </div>
      </form>

      <div ref="wishesRef" class="space-y-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar transform translate-y-12 opacity-0 transition-all duration-1000">
        <div 
          v-for="(wish, index) in wishes" 
          :key="wish.id"
          class="group p-6 rounded-2xl bg-white border-l-4 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slide-in"
          :style="{ 
            borderColor: 'var(--accent-color)',
            animationDelay: `${index * 100}ms`
          }"
        >
          <div class="flex items-start gap-4">
            <div class="relative flex-shrink-0">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg transform group-hover:scale-110 transition-transform duration-300"
                   :style="{ backgroundColor: 'var(--secondary-color)' }">
                {{ wish.guest_name.charAt(0).toUpperCase() }}
              </div>
              <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full animate-ping"
                   :style="{ backgroundColor: 'var(--accent-color)', opacity: 0.6 }"></div>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <p class="font-semibold text-lg" :style="{ color: 'var(--primary-color)' }">
                  {{ wish.guest_name }}
                </p>
                <svg class="w-5 h-5 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </div>
              <p class="text-gray-700 leading-relaxed group-hover:text-gray-900 transition-colors">{{ wish.message }}</p>
              <div class="mt-3 flex items-center gap-4 text-sm text-gray-400">
                <button class="flex items-center gap-1 hover:text-red-500 transition-colors group/like">
                  <svg class="w-4 h-4 group-hover/like:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  <span>Suka</span>
                </button>
                <span class="text-xs">{{ formatDate(wish.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="wishes.length === 0" class="text-center py-12 text-gray-400">
          <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <p>Belum ada ucapan. Jadilah yang pertama!</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps<{
  wishes: any[]
  guestName?: string
  processing?: boolean
}>()

defineEmits<{
  submit: [form: { guest_name: string; message: string }]
}>()

const form = reactive({
  guest_name: props.guestName || '',
  message: '',
})

const titleRef = ref<HTMLElement | null>(null)
const formRef = ref<HTMLElement | null>(null)
const wishesRef = ref<HTMLElement | null>(null)

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = Math.floor((now.getTime() - date.getTime()) / 1000)
  
  if (diff < 60) return 'Baru saja'
  if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`
  if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`
  if (diff < 604800) return `${Math.floor(diff / 86400)} hari lalu`
  
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
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

  if (titleRef.value) observer.observe(titleRef.value)
  if (formRef.value) observer.observe(formRef.value)
  if (wishesRef.value) observer.observe(wishesRef.value)
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})

defineExpose({ form })
</script>

<style scoped>
@keyframes slide-in {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-slide-in {
  animation: slide-in 0.5s ease-out forwards;
  opacity: 0;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: var(--accent-color);
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: var(--primary-color);
}
</style>
