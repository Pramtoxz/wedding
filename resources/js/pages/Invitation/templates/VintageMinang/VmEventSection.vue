<template>
  <!-- ====== WEDDING EVENT / COUNTDOWN ====== -->
  <section class="vm-section-event-bg">
    <div class="vm-event-bg-overlay"></div>
    <div class="vm-event-wrap" data-anim="fadeUp">
      <p class="vm-event-tag">Wedding Event</p>

      <!-- Countdown -->
      <div class="vm-countdown-row">
        <div class="vm-cd-item">
          <span class="vm-cd-num">{{ countdown.d }}</span>
          <span class="vm-cd-lbl">Days</span>
        </div>
        <span class="vm-cd-sep">:</span>
        <div class="vm-cd-item">
          <span class="vm-cd-num">{{ countdown.h }}</span>
          <span class="vm-cd-lbl">Hours</span>
        </div>
        <span class="vm-cd-sep">:</span>
        <div class="vm-cd-item">
          <span class="vm-cd-num">{{ countdown.m }}</span>
          <span class="vm-cd-lbl">Minutes</span>
        </div>
        <span class="vm-cd-sep">:</span>
        <div class="vm-cd-item">
          <span class="vm-cd-num">{{ countdown.s }}</span>
          <span class="vm-cd-lbl">Seconds</span>
        </div>
      </div>

      <!-- Dynamic Events from DB -->
      <div
        v-for="event in wedding.events"
        :key="event.id"
        class="vm-acara-card"
        data-anim="fadeUp"
      >
        <h3 class="vm-acara-title">{{ event.event_name }}</h3>
        <p class="vm-acara-will">Akan dilaksanakan pada:</p>
        <p class="vm-acara-day">{{ formatDayName(event.event_date || wedding.wedding_date) }}</p>
        <p class="vm-acara-date">{{ formatDateDots(event.event_date || wedding.wedding_date) }}</p>
        <p class="vm-acara-time">Pukul {{ event.event_time }} WIB</p>
        <p class="vm-acara-at">Bertempat di:</p>
        <p class="vm-acara-venue">{{ event.location_name }}</p>
        <p v-if="event.address" class="vm-acara-addr">{{ event.address }}</p>
        <a
          v-if="event.map_url"
          class="vm-btn-maps"
          :href="event.map_url"
          target="_blank"
          rel="noopener"
        >Open Maps</a>
      </div>

      <!-- Fallback if no events -->
      <div v-if="!wedding.events || wedding.events.length === 0" class="vm-acara-card" data-anim="fadeUp">
        <h3 class="vm-acara-title">Pernikahan</h3>
        <p class="vm-acara-will">Akan dilaksanakan pada:</p>
        <p class="vm-acara-day">{{ formatDayName(wedding.wedding_date) }}</p>
        <p class="vm-acara-date">{{ formatDateDots(wedding.wedding_date) }}</p>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import type { Wedding } from './types'
import { formatDateDots, formatDayName } from './helpers'

const props = defineProps<{ wedding: Wedding }>()

const countdown = ref({ d: '00', h: '00', m: '00', s: '00' })
let countdownTimer: ReturnType<typeof setInterval> | null = null

onMounted(() => initCountdown())
onUnmounted(() => { if (countdownTimer) clearInterval(countdownTimer) })

function initCountdown() {
  const dateOnly = props.wedding.wedding_date.split(/[ T]/)[0]
  const target = new Date(dateOnly + 'T08:00:00')

  function tick() {
    const now = new Date()
    const diff = target.getTime() - now.getTime()

    if (diff <= 0) {
      countdown.value = { d: '00', h: '00', m: '00', s: '00' }
      return
    }

    const pad = (n: number) => String(n).padStart(2, '0')
    countdown.value = {
      d: pad(Math.floor(diff / 86400000)),
      h: pad(Math.floor((diff % 86400000) / 3600000)),
      m: pad(Math.floor((diff % 3600000) / 60000)),
      s: pad(Math.floor((diff % 60000) / 1000)),
    }
  }

  tick()
  countdownTimer = setInterval(tick, 1000)
}
</script>

<style scoped>
/* ====== EVENT ====== */
.vm-section-event-bg {
  position: relative;
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center; padding: 0;
}
.vm-event-bg-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(235,229,215,0) 0%, #FFECD7 100%);
}
.vm-event-wrap { position: relative; z-index: 2; padding: 50px 24px; }
.vm-event-tag {
  font-family: 'Aboreto', cursive;
  font-size: 52px; color: var(--vm-maroon);
  text-align: center; margin-bottom: 24px; line-height: 1.1;
}
.vm-countdown-row {
  display: flex; justify-content: center;
  align-items: center; gap: 0; margin-bottom: 40px;
}
.vm-cd-item {
  display: flex; flex-direction: column; align-items: center;
  background: rgba(102,18,23,0.08);
  border: 1px solid rgba(102,18,23,0.2);
  padding: 14px 16px; min-width: 70px;
  border-radius: 4px; margin: 0 4px;
}
.vm-cd-num {
  font-family: 'Aboreto', cursive;
  font-size: 43px; color: var(--vm-maroon);
  line-height: 1; font-weight: 400;
}
.vm-cd-lbl {
  font-family: 'Poppins', sans-serif;
  font-size: 11px; color: var(--vm-maroon);
  opacity: 0.7; text-transform: uppercase;
  letter-spacing: 1px; margin-top: 4px;
}
.vm-cd-sep {
  font-size: 2rem; color: var(--vm-maroon);
  font-weight: 300; padding: 0 2px;
  align-self: flex-start; padding-top: 12px;
}
.vm-acara-card {
  background: rgba(255,255,255,0.88);
  border: 1px solid rgba(102,18,23,0.15);
  border-radius: 8px; padding: 28px 24px;
  margin-bottom: 20px; text-align: center;
  box-shadow: 0 4px 20px rgba(102,18,23,0.08);
}
.vm-acara-title {
  font-family: 'Aboreto', cursive;
  font-size: 33px; color: var(--vm-maroon); margin-bottom: 12px;
}
.vm-acara-will {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-maroon); opacity: 0.7; margin-bottom: 4px;
}
.vm-acara-day {
  font-family: 'Caudex', serif;
  font-size: 20px; color: var(--vm-maroon);
  font-style: italic; margin-bottom: 2px;
}
.vm-acara-date {
  font-family: 'Aboreto', cursive;
  font-size: 26px; color: var(--vm-maroon);
  letter-spacing: 3px; margin-bottom: 4px;
}
.vm-acara-time {
  font-family: 'Poppins', sans-serif;
  font-size: 16px; font-weight: 600; color: var(--vm-maroon); margin-bottom: 16px;
}
.vm-acara-at {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon); opacity: 0.7; margin-bottom: 4px;
}
.vm-acara-venue {
  font-family: 'Aboreto', cursive;
  font-size: 22px; color: var(--vm-maroon); margin-bottom: 6px;
}
.vm-acara-addr {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-maroon);
  line-height: 1.6; opacity: 0.8; margin-bottom: 16px;
}
.vm-btn-maps {
  display: inline-block;
  background: var(--vm-gold-grad);
  color: var(--vm-white);
  font-family: 'ZCOOL XiaoWei', serif;
  font-size: 16px; padding: 10px 28px;
  border-radius: 4px; text-decoration: none;
  transition: all 0.3s;
  box-shadow: 0 2px 12px rgba(161,112,54,0.4);
}
.vm-btn-maps:hover { opacity: 0.9; transform: translateY(-1px); }

/* ====== SCROLL ANIMATIONS (inherited from root) ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
