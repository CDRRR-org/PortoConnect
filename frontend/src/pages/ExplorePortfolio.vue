<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-purple-700">Porto Connect</h1>
            <span class="text-gray-400">×</span>
            <div class="flex items-center gap-2">
              <img src="@/assets/logo-soegija.png" alt="Logo" class="h-8" />
              <span class="text-sm font-semibold">SOEGIJAPRANATA CATHOLIC UNIVERSITY</span>
            </div>
          </div>
          <div class="flex items-center gap-6">
            <router-link to="/" class="text-purple-700 font-semibold hover:text-purple-900">Home</router-link>
            <router-link to="/explore" class="text-purple-700 font-semibold bg-purple-100 px-4 py-2 rounded-lg">Portofolio</router-link>
            <div v-if="currentUser" class="flex items-center gap-2">
              <router-link 
                v-if="currentUser.role === 'mahasiswa'"
                to="/profile/mahasiswa" 
                class="text-gray-700 hover:text-purple-700"
              >
                Dashboard Saya
              </router-link>
              <router-link 
                v-else-if="currentUser.role === 'perusahaan'"
                to="/dashboard/perusahaan" 
                class="text-gray-700 hover:text-purple-700"
              >
                Dashboard
              </router-link>
            </div>
            <div v-else class="flex items-center gap-2">
              <router-link to="/login" class="text-gray-700 hover:text-purple-700">Login</router-link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow">
      <div class="flex gap-6">
        <!-- Sidebar Kategori -->
        <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit sticky top-4">
          <h3 class="font-bold text-gray-800 mb-4">Kategori Bidang</h3>
          <ul class="space-y-2">
            <li>
              <button
                @click="filterByBidang(null)"
                :class="selectedBidang === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Semua Bidang
                <span class="ml-auto text-sm text-gray-500">({{ totalPortfolios }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidang('backend')"
                :class="selectedBidang === 'backend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Backend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('backend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidang('frontend')"
                :class="selectedBidang === 'frontend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Frontend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('frontend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidang('fullstack')"
                :class="selectedBidang === 'fullstack' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Fullstack
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('fullstack') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidang('QATester')"
                :class="selectedBidang === 'QATester' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                QA Tester
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('QATester') }})</span>
              </button>
            </li>
          </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <h2 class="text-3xl font-bold text-gray-800 mb-6">Jelajahi Portofolio Mahasiswa</h2>
          
          <!-- Portfolio Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="portfolio in filteredPortfolios"
              :key="portfolio.id"
              class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer transform hover:scale-105"
              @click="viewPortfolio(portfolio)"
            >
              <!-- Header dengan gradient background -->
              <div class="h-32 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 relative">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-4 left-4 right-4">
                  <h3 class="text-white font-bold text-lg">{{ portfolio.nama || 'Portofolio' }}</h3>
                </div>
              </div>

              <!-- Content -->
              <div class="p-4">
                <div class="flex items-start gap-3 mb-3">
                  <!-- Profile Picture -->
                  <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ portfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-800 truncate">{{ portfolio.mahasiswa?.user?.name }}</h4>
                    <p class="text-sm text-gray-600">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</p>
                  </div>
                  <!-- Public Icon -->
                  <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                  </svg>
                </div>

                <!-- Bidang Badge -->
                <div v-if="portfolio.bidang" class="mb-3">
                  <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ portfolio.bidang }}
                  </span>
                </div>

                <!-- Deskripsi Singkat -->
                <p v-if="portfolio.deskripsi" class="text-sm text-gray-600 mb-3 line-clamp-2">
                  {{ portfolio.deskripsi }}
                </p>

                <!-- Skills -->
                <div v-if="portfolio.skills && portfolio.skills.length > 0" class="flex flex-wrap gap-2">
                  <span
                    v-for="skill in portfolio.skills.slice(0, 3)"
                    :key="skill.id"
                    class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
                  >
                    {{ skill.nama }}
                  </span>
                  <span v-if="portfolio.skills.length > 3" class="text-xs text-gray-500">
                    +{{ portfolio.skills.length - 3 }} lainnya
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div v-if="filteredPortfolios.length === 0" class="text-center text-gray-500 py-12">
            <p>Tidak ada portofolio yang ditemukan</p>
          </div>
        </main>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-900 py-8 mt-auto">
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const portfolios = ref([])
const allPortfolios = ref([]) // Store all portfolios for counting
const currentUser = ref(null)
const selectedBidang = ref(null)

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    try {
      const res = await axios.get('/api/me')
      currentUser.value = res.data.user
    } catch (error) {
      // Not logged in, continue
    }
  }
  await loadPortfolios()
})

const loadPortfolios = async (bidang = null) => {
  try {
    const params = bidang ? { bidang } : {}
    const response = await axios.get('/api/portfolios/public', { params })
    
    // Ensure we have the data structure
    if (response.data && Array.isArray(response.data.portfolios)) {
      portfolios.value = response.data.portfolios
    } else if (Array.isArray(response.data)) {
      portfolios.value = response.data
    } else {
      portfolios.value = []
    }
    
    // Load all portfolios for counting if not already loaded
    if (allPortfolios.value.length === 0) {
      const allResponse = await axios.get('/api/portfolios/public')
      if (allResponse.data && Array.isArray(allResponse.data.portfolios)) {
        allPortfolios.value = allResponse.data.portfolios
      } else if (Array.isArray(allResponse.data)) {
        allPortfolios.value = allResponse.data
      } else {
        allPortfolios.value = []
      }
    }
  } catch (error) {
    console.error('Error loading portfolios:', error)
    console.error('Error response:', error.response?.data)
    portfolios.value = []
    allPortfolios.value = []
    
    // Show user-friendly error message
    const errorMessage = error.response?.data?.message || error.message || 'Gagal memuat portofolio'
    if (!errorMessage.includes('Unclosed')) {
      alert('Gagal memuat portofolio: ' + errorMessage)
    }
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => {
  return portfolios.value
})

const totalPortfolios = computed(() => {
  return allPortfolios.value.length
})

const getCountByBidang = (bidang) => {
  return allPortfolios.value.filter(p => p.bidang === bidang).length
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

