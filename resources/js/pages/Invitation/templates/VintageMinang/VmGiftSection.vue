<template>
  <!-- ====== WEDDING GIFT ====== -->
  <section v-if="wedding.gifts && wedding.gifts.length > 0" class="vm-section-gift">
    <div class="vm-gift-inner" data-anim="fadeUp">
      <p class="vm-gift-tag">Wedding Gift</p>
      <h2 class="vm-gift-title">Hadiah Pernikahan</h2>
      <p class="vm-gift-desc">
        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.
      </p>

      <div class="vm-gift-cards">
        <div v-for="gift in wedding.gifts" :key="gift.id" class="vm-gift-card">
          <p class="vm-gift-bank">{{ gift.bank_name }}</p>
          <p class="vm-gift-rek">No. Rekening {{ gift.account_number }}</p>
          <p class="vm-gift-an">a.n&nbsp;{{ gift.account_name }}</p>
          <button class="vm-btn-salin" @click="$emit('copy', gift.account_number)">Salin Nomor</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { Wedding } from './types'

defineProps<{ wedding: Wedding }>()
defineEmits<{ copy: [text: string] }>()
</script>

<style scoped>
/* ====== GIFT ====== */
.vm-section-gift {
  background: linear-gradient(165deg, #E1BF7C 0%, #A17036 100%);
}
.vm-gift-inner { padding: 60px 24px; text-align: center; }
.vm-gift-tag {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; color: var(--vm-maroon);
  letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
}
.vm-gift-title {
  font-family: 'Aboreto', cursive;
  font-size: 49px; color: var(--vm-maroon);
  line-height: 1.1; margin-bottom: 12px;
}
.vm-gift-desc {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-maroon);
  line-height: 1.8; margin-bottom: 28px; opacity: 0.85;
}
.vm-gift-cards { display: flex; flex-direction: column; gap: 16px; }
.vm-gift-card {
  background: rgba(255,255,255,0.85);
  border: 1px solid rgba(102,18,23,0.2);
  border-radius: 8px; padding: 24px; text-align: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.vm-gift-bank {
  font-family: 'Aboreto', cursive;
  font-size: 22px; color: var(--vm-maroon); margin-bottom: 6px;
}
.vm-gift-rek {
  font-family: 'Poppins', sans-serif;
  font-size: 15px; font-weight: 600;
  color: var(--vm-maroon); letter-spacing: 2px; margin-bottom: 4px;
}
.vm-gift-an {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-text-brown); margin-bottom: 16px;
}
.vm-btn-salin {
  background: var(--vm-maroon); color: var(--vm-white);
  border: none; font-family: 'ZCOOL XiaoWei', serif;
  font-size: 16px; padding: 10px 28px;
  border-radius: 4px; cursor: pointer; transition: all 0.3s;
}
.vm-btn-salin:hover { background: var(--vm-maroon-dark); }

/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
