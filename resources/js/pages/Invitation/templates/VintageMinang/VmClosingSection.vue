<template>
  <!-- ====== CLOSING ====== -->
  <section class="vm-section-closing">
    <div class="vm-closing-overlay"></div>
    <div class="vm-closing-inner" data-anim="fadeUp">
      <div class="vm-gallery-ornament">
        <!-- <img src="/minang-assets/minang-ornamen.png" alt="" style="filter:invert(90%) sepia(30%) saturate(80%); opacity:0.6" /> -->
      </div>
      <p class="vm-closing-text">
        {{ wedding.closing_text || 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restunya untuk pernikahan kami.' }}<br><br>
        Atas do'a &amp; restunya,<br>
        kami ucapkan terima kasih.
      </p>
      <h2 class="vm-closing-names">{{ wedding.bride_name?.toUpperCase() }} &amp; {{ wedding.groom_name?.toUpperCase() }}</h2>
      
      <div class="relative max-w-4xl mx-auto text-center">
        <div class="mb-6">
          <p class="text-sm text-gray-400 mb-2">Made with Developers by</p>
          <a 
            href="https://www.instagram.com/aldo6816/" 
            target="_blank" 
            rel="noopener noreferrer" 
            class="group inline-flex items-center gap-3 px-6 py-3 bg-white text-gray-900 rounded-full hover:bg-gray-100 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-2xl"
          >
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="font-semibold">Aldo Aditya Putra</span>
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { Wedding } from './types'

defineProps<{ wedding: Wedding }>()
</script>

<style scoped>
/* ====== CLOSING ====== */
.vm-section-closing {
  position: relative;
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center; overflow: hidden;
}
.vm-closing-overlay {
  position: absolute; inset: 0;
  background: var(--vm-maroon-grad); opacity: 0.88;
}
.vm-closing-inner {
  position: relative; z-index: 2;
  padding: 60px 30px; text-align: center;
}
/* .vm-gallery-ornament { text-align: center; margin: 12px 0 20px; }
.vm-gallery-ornament img { width: 160px; opacity: 0.7; filter: sepia(30%) saturate(80%); } */
.vm-closing-text {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.8);
  line-height: 1.9; margin-bottom: 24px;
}
.vm-closing-names {
  font-family: 'Aboreto', cursive;
  font-size: 49px; color: var(--vm-gold);
  margin-bottom: 12px; letter-spacing: 2px;
}


/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
