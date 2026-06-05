<template>
  <!-- ====== BRIDE & GROOM ====== -->
  <section class="vm-section-couple">
    <div class="vm-couple-bg">
      <div class="vm-couple-header" data-anim="fadeUp">
        <span class="vm-tag-label">Bride &amp;</span>
        <span class="vm-tag-label">Groom</span>
      </div>
      <p class="vm-couple-intro" data-anim="fadeUp">
        Assalamualaikum Wr. Wb.<br>
        Dengan memohon Rahmat &amp; Ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan putra-putri kami:
      </p>

      <!-- Bride -->
      <div class="vm-person-block" data-anim="slideLeft">
        <div class="vm-person-photo-wrap">
          <div class="vm-person-photo vm-bride-frame">
            <img
              v-if="wedding.bride_photo"
              :src="`/storage/${wedding.bride_photo}`"
              :alt="wedding.bride_name"
              class="vm-person-img"
            />
            <div v-else class="vm-photo-letter">{{ wedding.bride_name?.charAt(0) }}</div>
          </div>
        </div>
        <div class="vm-person-details">
          <h2 class="vm-person-title-big">{{ wedding.bride_name }}</h2>
          <p v-if="wedding.bride_father_name || wedding.bride_mother_name" class="vm-person-from">Putri dari</p>
          <p v-if="wedding.bride_father_name || wedding.bride_mother_name" class="vm-person-parents">
            {{ [wedding.bride_father_name, wedding.bride_mother_name].filter(Boolean).join(' & ') }}
          </p>
        </div>
      </div>

      <!-- Groom -->
      <div class="vm-person-block vm-reverse" data-anim="slideRight">
        <div class="vm-person-photo-wrap">
          <div class="vm-person-photo vm-groom-frame">
            <img
              v-if="wedding.groom_photo"
              :src="`/storage/${wedding.groom_photo}`"
              :alt="wedding.groom_name"
              class="vm-person-img"
            />
            <div v-else class="vm-photo-letter">{{ wedding.groom_name?.charAt(0) }}</div>
          </div>
        </div>
        <div class="vm-person-details">
          <h2 class="vm-person-title-big">{{ wedding.groom_name }}</h2>
          <p v-if="wedding.groom_father_name || wedding.groom_mother_name" class="vm-person-from">Putra dari</p>
          <p v-if="wedding.groom_father_name || wedding.groom_mother_name" class="vm-person-parents">
            {{ [wedding.groom_father_name, wedding.groom_mother_name].filter(Boolean).join(' & ') }}
          </p>
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
/* ====== COUPLE ====== */
.vm-section-couple { background: var(--vm-cream); }
.vm-couple-bg {
  background-image: url('/minang-assets/paper-plos-p-1.jpg');
  background-position: center; background-size: contain;
  padding: 50px 30px;
}
.vm-couple-header {
  display: flex; justify-content: center;
  gap: 12px; margin-bottom: 20px;
}
.vm-tag-label {
  font-family: 'Aboreto', cursive;
  font-size: 54px; color: var(--vm-maroon); line-height: 1;
}
.vm-couple-intro {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon);
  line-height: 1.8; text-align: center; margin-bottom: 32px;
}
.vm-person-block {
  display: flex; align-items: center; gap: 20px;
  margin-bottom: 36px; padding: 20px;
  background: rgba(255,255,255,0.6); border-radius: 8px;
}
.vm-reverse { flex-direction: row-reverse; text-align: right; }
.vm-person-photo-wrap { flex-shrink: 0; }
.vm-person-photo {
  width: 130px; height: 160px; border-radius: 8px;
  overflow: hidden; display: flex;
  align-items: center; justify-content: center;
  position: relative;
}
.vm-bride-frame {
  background: linear-gradient(135deg, #fde8d5 0%, #f0c8a0 100%);
  border: 2px solid var(--vm-gold-warm);
  box-shadow: 0 4px 20px rgba(102,18,23,0.15);
}
.vm-groom-frame {
  background: linear-gradient(135deg, #f0e0d0 0%, #d4a870 100%);
  border: 2px solid var(--vm-gold-warm);
  box-shadow: 0 4px 20px rgba(102,18,23,0.15);
}
.vm-person-img { width: 100%; height: 100%; object-fit: cover; }
.vm-photo-letter {
  font-family: 'Poppins', cursive;
  font-size: 60px; color: var(--vm-maroon); opacity: 0.5;
}
.vm-person-details { flex: 1; }
.vm-person-title-big {
  font-family: 'ZCOOL XiaoWei,serif', cursive;
  font-size: 55px; color: var(--vm-maroon);
  line-height: 1; margin-bottom: 4px;
}
.vm-person-from {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon); margin-bottom: 2px;
}
.vm-person-parents {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 600; color: var(--vm-maroon);
  line-height: 1.5; margin-bottom: 12px;
}

/* ====== SCROLL ANIMATIONS (inherited from root) ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim="slideLeft"] { transform: translateX(-30px); }
[data-anim="slideRight"] { transform: translateX(30px); }
[data-anim].in { opacity: 1; transform: none; }
</style>
