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
              <router-link v-if="currentUser.role === 'mahasiswa'" to="/profile/mahasiswa" class="text-gray-700 hover:text-purple-700">Dashboard Saya</router-link>
              <router-link v-else-if="currentUser.role === 'perusahaan'" to="/dashboard/perusahaan" class="text-gray-700 hover:text-purple-700">Dashboard</router-link>
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
                :class="selectedBidang === null ? activeClass : inactiveClass"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Semua Bidang <span class="ml-auto text-sm text-gray-500">({{ totalPortfolios }})</span>
              </button>
            </li>

            <li v-for="b in bidangList" :key="b.value">
              <button
                @click="filterByBidang(b.value)"
                :class="selectedBidang === b.value ? activeClass : inactiveClass"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ b.label }} <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang(b.value) }})</span>
              </button>
            </li>
          </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <h2 class="text-3xl font-bold text-gray-800 mb-6">Jelajahi Portofolio Mahasiswa</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="portfolio in filteredPortfolios"
              :key="portfolio.id || portfolio._id || portfolio.nama"
              class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer transform hover:scale-105"
              @click="viewPortfolio(portfolio)"
            >
              <div class="h-32 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 relative">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-4 left-4 right-4">
                  <h3 class="text-white font-bold text-lg">{{ portfolio.nama || 'Portofolio' }}</h3>
                </div>
              </div>

              <div class="p-4">
                <div class="flex items-start gap-3 mb-3">
                  <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ (portfolio.mahasiswa?.user?.name || 'U').charAt(0).toUpperCase() }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-800 truncate">{{ portfolio.mahasiswa?.user?.name || '-' }}</h4>
                    <p class="text-sm text-gray-600">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</p>
                  </div>
                  <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </div>

                <div v-if="portfolio.bidang" class="mb-3">
                  <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">{{ portfolio.bidang }}</span>
                </div>

                <p v-if="portfolio.deskripsi" class="text-sm text-gray-600 mb-3 line-clamp-2">{{ portfolio.deskripsi }}</p>

                <div v-if="portfolio.skills && portfolio.skills.length > 0" class="flex flex-wrap gap-2">
                  <span v-for="skill in portfolio.skills.slice(0,3)" :key="skill.id || skill.nama" class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ skill.nama }}</span>
                  <span v-if="portfolio.skills.length > 3" class="text-xs text-gray-500">+{{ portfolio.skills.length - 3 }} lainnya</span>
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
defineOptions({ name: 'ExplorePage' })

import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const portfolios = ref([])
const allPortfolios = ref([])
const currentUser = ref(null)
const selectedBidang = ref(null)

const bidangList = [
  { label: 'Backend', value: 'backend' },
  { label: 'Frontend', value: 'frontend' },
  { label: 'Fullstack', value: 'fullstack' },
  { label: 'QA Tester', value: 'QATester' }
]

const activeClass = 'bg-purple-100 text-purple-700 font-semibold'
const inactiveClass = 'text-gray-700 hover:bg-gray-100'

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    try {
      const res = await axios.get('/api/me')
      currentUser.value = res.data.user
    } catch (error) {
      // ignore if can't fetch user
      console.warn('Could not fetch /me:', error?.message || error)
    }
  }
  await loadPortfolios()
})

const loadPortfolios = async (bidang = null) => {
  try {
    const params = bidang ? { bidang } : {}
    const res = await axios.get('/api/portfolios/public', { params })
    if (res.data && Array.isArray(res.data.portfolios)) portfolios.value = res.data.portfolios
    else if (Array.isArray(res.data)) portfolios.value = res.data
    else portfolios.value = []

    // always ensure allPortfolios for counts
    const allRes = await axios.get('/api/portfolios/public')
    if (allRes.data && Array.isArray(allRes.data.portfolios)) allPortfolios.value = allRes.data.portfolios
    else if (Array.isArray(allRes.data)) allPortfolios.value = allRes.data
    else allPortfolios.value = []
  } catch (error) {
    console.error('Error loading portfolios:', error)
    portfolios.value = []
    allPortfolios.value = []
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => portfolios.value)
const totalPortfolios = computed(() => allPortfolios.value.length)
const getCountByBidang = (bidang) => allPortfolios.value.filter(p => p.bidang === bidang).length

const viewPortfolio = (portfolio) => {
  if (portfolio?.public_link) router.push(`/portfolio/${portfolio.public_link}`)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
