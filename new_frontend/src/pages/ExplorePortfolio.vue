<template>
  <div class="min-h-screen explore-gradient flex flex-col">
    <!-- NAVBAR -->
    <header class="fixed top-6 left-0 right-0 z-50">
      <nav class="max-w-6xl mx-auto py-3 px-6 bg-white rounded-full flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
          <span class="text-xl font-bold font-poppins text-purple-700">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="Soegijapranata Logo" class="h-8" />
            </div>

        <div class="hidden md:flex items-center gap-8 font-roboto">
          <router-link to="/" class="text-gray-700 hover:text-purple-700 transition">Home</router-link>
          <router-link 
            v-if="!currentUser || currentUser.role !== 'perusahaan'"
            to="/explore" 
            class="text-gray-700 hover:text-purple-700 transition"
          >
            Portofolio
          </router-link>
          </div>

        <div class="flex items-center gap-4 font-roboto">
          <div v-if="currentUser" class="py-1.5 px-4 rounded-full bg-black text-white hover:bg-gray-800 transition flex items-center gap-2">
              <router-link 
                v-if="currentUser.role === 'mahasiswa'"
                to="/profile/mahasiswa" 
                class="hover:underline"
              >
                Dashboard Saya
              </router-link>
              <router-link 
                v-else-if="currentUser.role === 'perusahaan'"
                to="/dashboard/perusahaan" 
                class="hover:underline"
              >
                Dashboard
              </router-link>
            <span>|</span>
            <button 
              @click="handleLogout" 
              class="hover:underline cursor-pointer"
            >
              Logout
            </button>
          </div>

          <template v-else>
            <router-link to="/login" class="py-1.5 px-4 rounded-full bg-black text-white hover:bg-gray-800 transition">Sign Up | Login</router-link>
          </template>
        </div>
      </nav>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow pt-24">
      <div class="flex gap-6">
        <!-- Sidebar Kategori -->
        <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit mt-8">
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
          <h2 class="text-3xl font-bold text-white mb-6 mt-8">Jelajahi Portofolio Mahasiswa</h2>
          
          <!-- Portfolio Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <PortfolioCard
              v-for="portfolio in paginatedPortfolios"
              :key="portfolio.id"
              :portfolio="portfolio"
              @select="viewPortfolio"
            />
          </div>

          <div v-if="filteredPortfolios.length === 0" class="text-center text-gray-500 py-12">
            <p>Tidak ada portofolio yang ditemukan</p>
          </div>

          <!-- Pagination -->
          <div v-if="filteredPortfolios.length > 6" class="mt-6 flex justify-center items-center gap-2">
            <button 
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
              :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
              class="px-4 py-2 border rounded-lg bg-white text-gray-700"
            >
              ←
            </button>
            <span class="px-4 py-2 text-gray-700 bg-white border rounded-lg text-sm">
              {{ currentPage }} / {{ totalPages }}
            </span>
            <button 
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
              :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
              class="px-4 py-2 border rounded-lg bg-white text-gray-700"
            >
              →
            </button>
          </div>
        </main>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-purple-900 text-white py-16 font-roboto">
      <div class="max-w-6xl mx-auto px-6">
        <div class="mb-12">
          <h3 class="text-2xl md:text-3xl font-bold font-poppins mb-4">Informasi Kontak</h3>
          <ul class="space-y-2 text-gray-300">
            <li>Email : <a href="mailto:unika@unika.ac.id" class="hover:text-purple-300 transition">unika@unika.ac.id</a></li>
            <li>Hotline : (024) 850 5003</li>
            <li>WhatsApp Official : <a href="https://wa.me/6281232345479" class="hover:text-purple-300 transition">08123 2345 479</a></li>
          </ul>
        </div>

        <div class="flex items-center justify-center gap-4 mb-8">
          <div class="flex flex-col text-3xl font-poppins text-white">
            <span>Porto</span>
            <span>Connect</span>
          </div>
          <span class="text-3xl text-white">×</span>
            <div class="flex items-center gap-2">
            <img src="@/assets/logo-soegija-putih.png" alt="Logo SCU" class="h-16" />
          </div>
        </div>
      </div>

      <div class="border-t border-purple-800 mt-12 pt-8 text-center text-gray-500 text-sm">&copy; 2025 PortoConnect. All rights reserved.</div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import PortfolioCard from '@/components/PortfolioCard.vue'
import { useSweetAlert } from '@/composables/useSweetAlert'

const { showError } = useSweetAlert()

const router = useRouter()
const portfolios = ref([])
const allPortfolios = ref([]) // Store all portfolios for counting
const currentUser = ref(null)
const selectedBidang = ref(null)
const currentPage = ref(1)
const itemsPerPage = 6

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
    
    // Reset to first page when loading new data
    currentPage.value = 1
    
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
      showError('Gagal memuat portofolio: ' + errorMessage)
    }
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  currentPage.value = 1 // Reset to first page when filtering
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => {
  return portfolios.value
})

const totalPortfolios = computed(() => {
  return allPortfolios.value.length
})

// Pagination computed
const totalPages = computed(() => {
  return Math.ceil(filteredPortfolios.value.length / itemsPerPage)
})

const paginatedPortfolios = computed(() => {
  if (filteredPortfolios.value.length <= itemsPerPage) {
    return filteredPortfolios.value
  }
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredPortfolios.value.slice(start, end)
})

const getCountByBidang = (bidang) => {
  return allPortfolios.value.filter(p => p.bidang === bidang).length
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    console.warn('Logout error:', err)
  } finally {
    try { localStorage.removeItem('token') } catch (e) { console.warn('localStorage remove error', e) }
    delete axios.defaults.headers.common['Authorization']
    currentUser.value = null
    router.push('/')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.explore-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}
</style>

