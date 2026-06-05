<template>
  <!-- ====== RSVP + UCAPAN ====== -->
  <section class="vm-section-rsvp">
    <div class="vm-rsvp-bg">
      <div class="vm-rsvp-inner" data-anim="fadeUp">
        <p class="vm-rsvp-tag">Rsvp</p>
        <p class="vm-rsvp-desc">Konfirmasi kehadiran Anda dengan mengisi form berikut:</p>
        <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>

        <form class="vm-rsvp-form" @submit.prevent="submitWish">
          <div class="vm-form-row">
            <label>Nama*</label>
            <input
              v-model="form.guest_name"
              type="text"
              placeholder="Nama Lengkap..."
              required
              :readonly="!!guest"
            />
          </div>
          <div class="vm-form-row">
            <label>Ucapan &amp; Doa*</label>
            <textarea
              v-model="form.message"
              placeholder="Tulis ucapan dan doa terbaik Anda..."
              rows="3"
              required
            ></textarea>
          </div>
          <div class="vm-form-row vm-radio-row">
            <label class="vm-r-opt">
              <input type="radio" v-model="form.attendance_status" value="attending" /> Hadir
            </label>
            <label class="vm-r-opt">
              <input type="radio" v-model="form.attendance_status" value="not_attending" /> Tidak hadir
            </label>
            <label class="vm-r-opt">
              <input type="radio" v-model="form.attendance_status" value="maybe" /> Masih Ragu
            </label>
          </div>
          <button type="submit" class="vm-btn-submit" :disabled="form.processing">
            {{ form.processing ? 'Mengirim...' : 'Kirim Ucapan' }}
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- ====== BEST WISHES / UCAPAN LIST ====== -->
  <section class="vm-section-wishes">
    <div class="vm-wishes-inner" data-anim="fadeUp">
      <p class="vm-wishes-tag">Best Wishes</p>
      <h2 class="vm-wishes-title">Sampaikan doa dan ucapan terbaik Anda</h2>
      <div class="vm-gallery-ornament"><img src="/minang-assets/minang-ornamen.png" alt="" /></div>

      <div class="vm-wishes-list">
        <div v-for="wish in wedding.wishes" :key="wish.id" class="vm-wish-item">
          <div class="vm-wish-nama">💌 {{ wish.guest_name }}</div>
          <div class="vm-wish-pesan">{{ wish.message }}</div>
          <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
            <span
              class="vm-wish-hadir"
              :class="{
                'vm-hadir-yes': wish.attendance_status === 'attending',
                'vm-hadir-no': wish.attendance_status === 'not_attending',
                'vm-hadir-maybe': wish.attendance_status === 'maybe',
              }"
            >
              {{ wish.attendance_status === 'attending' ? 'Hadir' : wish.attendance_status === 'not_attending' ? 'Tidak Hadir' : 'Masih Ragu' }}
            </span>
            <span class="vm-wish-waktu">{{ timeAgo(wish.created_at) }}</span>
          </div>
        </div>
        <div v-if="!wedding.wishes || wedding.wishes.length === 0" class="vm-wish-item">
          <div class="vm-wish-pesan" style="text-align:center;opacity:0.6;">Belum ada ucapan. Jadilah yang pertama! 💕</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import type { Wedding, Guest } from './types'
import { timeAgo } from './helpers'

const props = defineProps<{
  wedding: Wedding
  guest: Guest | null
}>()

const form = useForm({
  guest_name: props.guest?.name || '',
  message: '',
  attendance_status: '',
})

function submitWish() {
  form.post(`/invitation/${props.wedding.slug}/wish`, {
    onSuccess: () => {
      form.message = ''
      form.attendance_status = ''
      Swal.fire({
        title: 'Terima Kasih!',
        text: 'Ucapan Anda telah terkirim',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#661217',
        background: '#FFFBE9',
        color: '#661217',
      })
    },
    onError: () => {
      Swal.fire({
        title: 'Oops!',
        text: 'Terjadi kesalahan, silakan coba lagi',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#661217',
      })
    },
  })
}
</script>

<style scoped>
/* ====== RSVP ====== */
.vm-section-rsvp {
  background-image: url('/minang-assets/COVER-ACARA-MINANG-4.jpg');
  background-size: cover; background-position: center;
}
.vm-rsvp-bg {
  background: linear-gradient(123deg, rgba(0,0,0,0.76) 0%, rgba(102,18,23,0.9) 100%);
  min-height: 100%;
}
.vm-rsvp-inner { padding: 60px 24px; text-align: center; }
.vm-rsvp-tag {
  font-family: 'Aboreto', cursive;
  font-size: 56px; color: var(--vm-gold); margin-bottom: 10px;
}
.vm-rsvp-desc {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: rgba(255,251,233,0.8); margin-bottom: 20px;
}
.vm-gallery-ornament { text-align: center; margin: 12px 0 20px; }
.vm-gallery-ornament img { width: 160px; opacity: 0.7; filter: sepia(30%) saturate(80%); }
.vm-rsvp-form {
  display: flex; flex-direction: column;
  gap: 14px; text-align: left;
}
.vm-form-row { display: flex; flex-direction: column; gap: 6px; }
.vm-form-row label {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: var(--vm-gold);
  font-weight: 600; letter-spacing: 0.5px;
}
.vm-form-row input[type="text"],
.vm-form-row select,
.vm-form-row textarea {
  width: 100%; padding: 10px 14px;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(223,181,132,0.4);
  color: var(--vm-cream);
  font-family: 'Poppins', sans-serif;
  font-size: 14px; border-radius: 4px;
  outline: none; transition: border-color 0.3s;
  resize: vertical;
}
.vm-form-row input::placeholder,
.vm-form-row textarea::placeholder { color: rgba(255,251,233,0.4); }
.vm-form-row input:focus,
.vm-form-row textarea:focus { border-color: var(--vm-gold); }
.vm-radio-row { flex-direction: row; gap: 16px; flex-wrap: wrap; }
.vm-r-opt {
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--vm-cream);
  display: flex; align-items: center;
  gap: 6px; cursor: pointer;
}
.vm-r-opt input { accent-color: var(--vm-gold); }
.vm-btn-submit {
  background: var(--vm-gold-grad);
  color: var(--vm-white); border: none;
  font-family: 'ZCOOL XiaoWei', serif;
  font-size: 18px; padding: 13px;
  border-radius: 4px; cursor: pointer;
  width: 100%; transition: opacity 0.3s;
  margin-top: 8px;
  box-shadow: 0 4px 16px rgba(161,112,54,0.5);
}
.vm-btn-submit:hover { opacity: 0.9; }
.vm-btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

/* ====== WISHES ====== */
.vm-section-wishes {
  background-image: url('/minang-assets/BG-UTAMA-MINANG-4.jpg');
  background-size: cover; background-position: center;
  padding: 60px 20px;
}
.vm-wishes-inner {
  padding: 36px 24px;
  text-align: center;
  max-width: 440px;
  margin: 0 auto;
  background: rgba(102, 18, 23, 0.88);
  border-radius: 12px;
  border: 1px solid rgba(223, 181, 132, 0.3);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(4px);
}
.vm-wishes-tag {
  font-family: 'Aboreto', cursive;
  font-size: 56px; color: var(--vm-gold); margin-bottom: 8px;
}
.vm-wishes-title {
  font-family: 'Poppins', sans-serif;
  font-size: 16px; color: rgba(255,251,233,0.7);
  font-weight: 300; margin-bottom: 20px;
}
.vm-wishes-list {
  display: flex; flex-direction: column;
  gap: 12px; text-align: left;
  max-height: 500px; overflow-y: auto; padding-right: 4px;
}
.vm-wish-item {
  background: rgba(255,255,255,0.06);
  border-left: 3px solid var(--vm-gold);
  padding: 14px 16px; border-radius: 6px;
  animation: vmWishIn 0.4s ease;
}
@keyframes vmWishIn {
  from { opacity: 0; transform: translateX(-16px); }
  to { opacity: 1; transform: none; }
}
.vm-wish-nama {
  font-family: 'Alike Angular', serif;
  font-size: 14px; color: var(--vm-gold); margin-bottom: 4px;
}
.vm-wish-pesan {
  font-family: 'Poppins', sans-serif;
  font-size: 13px; color: rgba(255,251,233,0.8);
  line-height: 1.6; font-style: italic;
}
.vm-wish-waktu {
  font-size: 11px; color: rgba(255,251,233,0.4); margin-top: 6px;
}
.vm-wish-hadir {
  display: inline-block; font-size: 10px;
  padding: 2px 8px; border-radius: 20px;
  margin-top: 4px; font-family: 'Poppins', sans-serif;
}
.vm-hadir-yes { background: rgba(102,18,23,0.5); color: #FFE6C8; }
.vm-hadir-no { background: rgba(50,50,50,0.5); color: #778495; }
.vm-hadir-maybe { background: rgba(20,20,100,0.4); color: #9C8594; }

/* ====== SCROLL ANIMATIONS ====== */
[data-anim] {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
[data-anim].in { opacity: 1; transform: none; }
</style>
