<template>
  <div class="min-h-screen bg-gradient-to-b from-purple-800 via-purple-600 to-purple-400 flex flex-col">
    <!-- Header -->
    <header class="bg-gray-100 rounded-b-lg shadow-sm mb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-4">
            <button 
              @click="goBack" 
              class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Kembali
            </button>
            <h1 class="text-2xl font-bold text-purple-700">Porto Connect</h1>
            <span class="text-gray-400">×</span>
            <div class="flex items-center gap-2">
              <img src="@/assets/logo-soegija.png" alt="Logo" class="h-8" />
              <span class="text-sm font-semibold">SOEGIJAPRANATA CATHOLIC UNIVERSITY</span>
            </div>
          </div>
          <div class="flex items-center gap-6">
            <router-link to="/" class="text-gray-700 hover:text-purple-700">Home</router-link>
            <router-link to="/explore" class="text-gray-700 hover:text-purple-700">Portofolio</router-link>
          </div>
        </div>
      </div>
    </header>

    <div v-if="loading" class="flex items-center justify-center flex-grow">
      <p class="text-white text-lg">Memuat profil...</p>
    </div>

    <div v-else-if="error" class="flex items-center justify-center flex-grow">
      <p class="text-white text-lg">{{ error }}</p>
    </div>

    <div v-else-if="user && mahasiswa" class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel - Profile Card -->
        <div class="lg:col-span-1">
          <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 text-center shadow-lg">
            <div class="w-32 h-32 mx-auto mb-4 bg-purple-600 rounded-full flex items-center justify-center text-white text-5xl font-bold">
              {{ user?.name?.charAt(0) || 'U' }}
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">{{ user?.name || 'Nama User' }}</h2>
            <p class="text-white/80 text-lg">{{ mahasiswa?.nim || 'NIM' }}</p>
            <p class="text-white/80">{{ mahasiswa?.jurusan || 'Jurusan' }} - {{ mahasiswa?.fakultas || 'Fakultas' }}</p>
            <p v-if="mahasiswa?.universitas" class="text-white/80 mt-2">{{ mahasiswa.universitas }}</p>
          </div>
        </div>

        <!-- Right Panel - Details -->
        <div class="lg:col-span-2">
          <div class="bg-gray-100 rounded-2xl p-6 shadow-lg">
            <!-- Bio Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Bio</h3>
              <p class="text-gray-700">{{ mahasiswa?.deskripsi_diri || 'Belum ada deskripsi' }}</p>
            </div>

            <!-- Email Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Email address</h3>
              <p class="text-gray-700">{{ user?.email || '-' }}</p>
            </div>

            <!-- Phone Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Nomer HP</h3>
              <p class="text-gray-700">{{ mahasiswa?.no_telp || '-' }}</p>
            </div>

            <!-- Additional Info -->
            <div v-if="mahasiswa?.tanggal_lahir" class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Tanggal Lahir</h3>
              <p class="text-gray-700">{{ formatDate(mahasiswa.tanggal_lahir) }}</p>
            </div>

            <!-- Social Links -->
            <div v-if="mahasiswa?.linkedin || mahasiswa?.github || mahasiswa?.website" class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">Media Sosial & Website</h3>
              <div class="flex flex-wrap gap-4">
                <a 
                  v-if="mahasiswa.linkedin" 
                  :href="mahasiswa.linkedin" 
                  target="_blank"
                  class="flex items-center gap-2 text-blue-600 hover:text-blue-800"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                  </svg>
                  LinkedIn
                </a>
                <a 
                  v-if="mahasiswa.github" 
                  :href="mahasiswa.github" 
                  target="_blank"
                  class="flex items-center gap-2 text-gray-800 hover:text-gray-900"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                  </svg>
                  GitHub
                </a>
                <a 
                  v-if="mahasiswa.website" 
                  :href="mahasiswa.website" 
                  target="_blank"
                  class="flex items-center gap-2 text-purple-600 hover:text-purple-800"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                  </svg>
                  Website
                </a>
              </div>
            </div>

            <!-- Documents/Portfolios Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">Dokumen</h3>
              
              <div v-if="portfoliosLoading" class="text-center py-4">
                <p class="text-gray-600 text-sm">Memuat dokumen...</p>
              </div>
              
              <div v-else-if="portfolios.length === 0" class="text-center py-4">
                <p class="text-gray-600 text-sm">Belum ada dokumen yang dipublikasikan</p>
              </div>
              
              <div v-else class="flex flex-wrap gap-4">
                <div
                  v-for="portfolio in portfolios"
                  :key="portfolio.id"
                  @click="viewPortfolio(portfolio)"
                  class="relative group cursor-pointer"
                >
                  <!-- Document Icon -->
                  <div class="w-16 h-20 bg-white border-2 border-gray-300 rounded-lg shadow-md hover:shadow-lg hover:border-purple-500 transition-all flex flex-col items-center justify-center">
                    <svg class="w-10 h-10 text-purple-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="w-8 h-1 bg-purple-600 rounded mt-1"></div>
                  </div>
                  
                  <!-- Tooltip on Hover -->
                  <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                    {{ portfolio.nama || 'Portofolio' }}
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                      <div class="border-4 border-transparent border-t-gray-800"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-900 mt-auto py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h4 class="text-white font-bold mb-4">Informasi Kontak</h4>
            <p class="text-white/80">Email : unika@unika.ac.id</p>
            <p class="text-white/80">WhatsApp Official : 08123-2345-479</p>
          </div>
          <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-white">Porto Connect</h1>
            <span class="text-white">×</span>
            <div class="flex items-center gap-2">
              <img src="@/assets/logo-soegija-putih.png" alt="Logo" class="h-8" />
              <span class="text-white text-sm font-semibold">SOEGIJAPRANATA CATHOLIC UNIVERSITY</span>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const user = ref(null)
const mahasiswa = ref(null)
const portfolios = ref([])
const loading = ref(true)
const portfoliosLoading = ref(false)
const error = ref(null)

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const goBack = () => {
  router.go(-1)
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const loadPortfolios = async () => {
  portfoliosLoading.value = true
  try {
    const mahasiswaId = route.params.id
    const res = await axios.get(`/api/mahasiswa/${mahasiswaId}/portfolios`)
    portfolios.value = res.data.portfolios || []
  } catch (err) {
    console.error('Error loading portfolios:', err)
    portfolios.value = []
  } finally {
    portfoliosLoading.value = false
  }
}

onMounted(async () => {
  try {
    const mahasiswaId = route.params.id
    const res = await axios.get(`/api/mahasiswa/${mahasiswaId}/profile`)
    user.value = res.data.user
    mahasiswa.value = res.data.mahasiswa
    
    // Load portfolios after profile is loaded
    await loadPortfolios()
  } catch (err) {
    console.error('Error loading profile:', err)
    error.value = err.response?.data?.message || 'Gagal memuat profil'
  } finally {
    loading.value = false
  }
})
</script>

