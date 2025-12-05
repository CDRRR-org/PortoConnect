<!-- src/components/FolderCard.vue -->
<template>
  <section class="folder-section relative -mt-24 md:-mt-28 pb-12">
    <div class="container max-w-5xl mx-auto px-6">
      <div class="relative">
        <svg viewBox="0 0 1000 260" preserveAspectRatio="xMinYMin meet" class="w-full folder-svg" aria-hidden="true">
          <path ref="outline" class="outline-path" d="M24 220 H860 L896 190 L896 56 H700 L648 112 H296 L254 56 H24 Z" fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-width="12" />
          <rect x="36" y="42" width="820" height="168" rx="6" fill="white" />
        </svg>

        <div class="folder-content absolute left-0 right-0 top-0 pointer-events-none">
          <div class="px-8 py-10 md:py-14 max-w-3xl">
            <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-poppins">Porto Connect</h3>
            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
              Kumpulkan karya dan pencapaian terbaikmu dalam satu portofolio digital profesional — mudah diakses oleh recruiter.
            </p>
          </div>
        </div>
      </div>

      <div class="mt-6 flex items-center gap-4">
        <!-- only the create CTA + helper text (no "Pelajari Lebih Lanjut") -->
        <button @click="handleCreate" class="z-10 relative inline-flex items-center gap-2 px-4 py-2 rounded-full btn-primary shadow-sm focus:outline-none">
          Buat Portofolio
        </button>
        <span class="text-sm text-gray-600">(Hanya untuk mahasiswa — buat portofolio di profilmu)</span>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const outlineRef = ref(null)

// handleCreate: emit a create event via window CustomEvent (parent listens via @create),
// fallback: call API directly if parent didn't handle the event.
const handleCreate = async () => {
  // Try dispatching a window event so parent can intercept
  try {
    const ev = new CustomEvent('create')
    window.dispatchEvent(ev)
    return
  } catch (emitErr) {
    console.warn('emit create failed:', emitErr)
  }

  // fallback: direct create
  try {
    const token = localStorage.getItem('token')
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const res = await axios.post('/api/mahasiswa/portfolios', { nama: 'Portofolio Baru', bidang: '', deskripsi: '' })
    const id = res.data.portofolio?.id || res.data.portfolio?.id
    if (id) router.push(`/portfolio/preview/${id}`)
    else router.push('/portfolio/list')
  } catch (err) {
    console.error('Gagal membuat portofolio (fallback):', err)
    router.push('/portfolio/list')
  }
}

onMounted(() => {
  outlineRef.value = document.querySelector('.outline-path')
  if (outlineRef.value && typeof outlineRef.value.getTotalLength === 'function') {
    const len = outlineRef.value.getTotalLength()
    outlineRef.value.style.strokeDasharray = len
    outlineRef.value.style.strokeDashoffset = len
    outlineRef.value.style.stroke = '#4c1d95'
  }
})
</script>

<style scoped>
.folder-section { z-index: 2; }
.folder-svg { height: 220px; display: block; }
.outline-path { stroke: #4c1d95; fill: none; stroke-width: 10; filter: drop-shadow(0 3px 0 rgba(0,0,0,0.06)); }
.folder-content { top: 0; left: 36px; right: 36px; height: 220px; display: flex; align-items: center; pointer-events: none; }
.btn-primary { background: linear-gradient(90deg,#6f2a7b,#4d1a5d); color: white; border: none; padding: 0.5rem 1rem; border-radius: 9999px; }
@media (min-width: 768px) { .folder-svg { height: 260px; } .folder-content { height: 260px; } }
</style>
