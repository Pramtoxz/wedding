<template>
  <!-- ====== CLOSING ====== -->
  <section class="vm-section-closing">
    <div class="vm-closing-overlay"></div>
    <div class="vm-closing-inner" data-anim="fadeUp">
      <div class="vm-gallery-ornament">
        <img src="/minang-assets/minang-ornamen.png" alt="" style="filter:invert(90%) sepia(30%) saturate(80%); opacity:0.6" />
      </div>
      <p class="vm-closing-text">
        {{ wedding.closing_text || 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restunya untuk pernikahan kami.' }}<br><br>
        Atas do'a &amp; restunya,<br>
        kami ucapkan terima kasih.
      </p>
      <h2 class="vm-closing-names">{{ wedding.bride_name?.toUpperCase() }} &amp; {{ wedding.groom_name?.toUpperCase() }}</h2>
      <p class="vm-closing-by">Made with love by Aldo Aditya Putra</p>
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
.vm-gallery-ornament { text-align: center; margin: 12px 0 20px; }
.vm-gallery-ornament img { width: 160px; opacity: 0.7; filter: sepia(30%) saturate(80%); }
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
.vm-closing-by {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.5); margin-bottom: 8px;
}

/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
