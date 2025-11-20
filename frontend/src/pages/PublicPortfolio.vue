<template>
  <div class="min-h-screen bg-gray-50">
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <p>Memuat portofolio...</p>
    </div>
    <div v-else-if="portfolio" class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <h1 class="text-4xl font-bold text-purple-700 mb-2">{{ portfolio.mahasiswa?.user?.name }}</h1>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.nim }}</p>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.jurusan }} - {{ portfolio.mahasiswa?.fakultas }}</p>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.universitas }}</p>
        </div>

        <!-- Description -->
        <div v-if="portfolio.mahasiswa?.deskripsi_diri" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Tentang Saya</h2>
          <p class="text-gray-700">{{ portfolio.mahasiswa.deskripsi_diri }}</p>
        </div>

        <!-- Skills -->
        <div v-if="portfolio.mahasiswa?.skills?.length" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Skills</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="skill in portfolio.mahasiswa.skills"
              :key="skill.id"
              class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg"
            >
              {{ skill.nama }} ({{ skill.level }})
            </span>
          </div>
        </div>

        <!-- Projects -->
        <div v-if="portfolio.mahasiswa?.projects?.length" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Proyek</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="project in portfolio.mahasiswa.projects"
              :key="project.id"
              class="border rounded-lg p-4"
            >
              <h3 class="font-bold text-lg">{{ project.judul }}</h3>
              <p class="text-gray-600 text-sm mt-2">{{ project.deskripsi }}</p>
              <a v-if="project.link" :href="project.link" target="_blank" class="text-blue-600 text-sm mt-2 inline-block">
                Lihat Proyek →
              </a>
            </div>
          </div>
        </div>

        <!-- Experiences -->
        <div v-if="portfolio.mahasiswa?.experiences?.length" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Pengalaman</h2>
          <div class="space-y-4">
            <div
              v-for="exp in portfolio.mahasiswa.experiences"
              :key="exp.id"
              class="border-l-4 border-purple-500 pl-4"
            >
              <h3 class="font-bold">{{ exp.judul }}</h3>
              <p class="text-gray-600">{{ exp.perusahaan }}</p>
              <p class="text-sm text-gray-500">
                {{ new Date(exp.tanggal_mulai).toLocaleDateString() }} -
                {{ exp.masih_berlangsung ? 'Sekarang' : new Date(exp.tanggal_selesai).toLocaleDateString() }}
              </p>
              <p class="text-gray-700 mt-2">{{ exp.deskripsi }}</p>
            </div>
          </div>
        </div>

        <!-- Contact -->
        <div class="border-t pt-4">
          <h2 class="text-2xl font-bold mb-4">Kontak</h2>
          <div class="space-y-2">
            <div v-if="portfolio.mahasiswa?.no_telp" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span class="text-gray-700">{{ portfolio.mahasiswa.no_telp }}</span>
            </div>
            <div v-if="portfolio.mahasiswa?.linkedin" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
              <a :href="portfolio.mahasiswa.linkedin" target="_blank" class="text-blue-600 hover:underline">
                LinkedIn
              </a>
            </div>
            <div v-if="portfolio.mahasiswa?.github" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
              <a :href="portfolio.mahasiswa.github" target="_blank" class="text-gray-800 hover:underline">
                GitHub
              </a>
            </div>
            <div v-if="portfolio.mahasiswa?.website" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
              </svg>
              <a :href="portfolio.mahasiswa.website" target="_blank" class="text-purple-600 hover:underline">
                Website
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center min-h-screen">
      <p class="text-red-600">Portofolio tidak ditemukan</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const portfolio = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const publicLink = route.params.publicLink
    const res = await axios.get(`/api/portfolio/${publicLink}`)
    portfolio.value = res.data.portofolio
  } catch (error) {
    console.error('Error loading portfolio:', error)
  } finally {
    loading.value = false
  }
})
</script>

