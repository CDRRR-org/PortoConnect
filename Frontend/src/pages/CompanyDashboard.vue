<template>
  <div class="min-h-screen dashboard-gradient flex flex-col">
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
            <router-link to="/" class="text-gray-700 hover:text-purple-700">Home</router-link>

            <div class="flex items-center gap-2 bg-gray-200 px-4 py-2 rounded-lg">
              <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white">
                {{ currentUser?.name?.charAt(0) || 'C' }}
              </div>
              <span class="font-semibold">{{ currentUser?.name || 'Company' }}</span>
            </div>

            <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
              Logout
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow">
      <div class="flex gap-6">
        <!-- Sidebar -->
        <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit sticky top-4">
          <h3 class="font-bold text-gray-800 mb-4">Kategori Bidang</h3>
          <ul class="space-y-2">
            <li>
              <button @click="filterByBidangPortfolio(null)" :class="selectedBidangPortfolio === null ? activeClass : inactiveClass" class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition">
                Semua Bidang <span class="ml-auto text-sm text-gray-500">({{ totalPortfoliosPortfolio }})</span>
              </button>
            </li>
            <li v-for="b in bidangList" :key="b.value">
              <button @click="filterByBidangPortfolio(b.value)" :class="selectedBidangPortfolio === b.value ? activeClass : inactiveClass" class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition">
                {{ b.label }} <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio(b.value) }})</span>
              </button>
            </li>
          </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Cari Mahasiswa</h2>
            <form @submit.prevent="searchStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <input v-model="searchForm.keyword" placeholder="Nama, NIM, dll" class="border rounded-lg px-3 py-2" />
              <input v-model="searchForm.skill" placeholder="Skill (JavaScript, Python)" class="border rounded-lg px-3 py-2" />
              <input v-model="searchForm.jurusan" placeholder="Jurusan" class="border rounded-lg px-3 py-2" />
              <input v-model="searchForm.fakultas" placeholder="Fakultas" class="border rounded-lg px-3 py-2" />
              <input v-model="searchForm.universitas" placeholder="Universitas" class="border rounded-lg px-3 py-2" />
              <div class="flex items-end">
                <button class="w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">Cari</button>
              </div>
            </form>
          </div>

          <h2 class="text-3xl font-bold text-gray-800 mb-6">Jelajahi Portofolio Mahasiswa</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="portfolio in filteredPortfoliosPortfolio" :key="portfolio.id || portfolio._id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer transform hover:scale-105" @click="viewPortfolio(portfolio)">
              <div class="h-32 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 relative">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-4 left-4 right-4">
                  <h3 class="text-white font-bold text-lg">{{ portfolio.nama || 'Portofolio' }}</h3>
                </div>
              </div>
              <div class="p-4">
                <div class="flex items-start gap-3 mb-3">
                  <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">{{ (portfolio.mahasiswa?.user?.name || 'U').charAt(0).toUpperCase() }}</div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-800 truncate">{{ portfolio.mahasiswa?.user?.name }}</h4>
                    <p class="text-sm text-gray-600">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</p>
                  </div>
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

          <div v-if="filteredPortfoliosPortfolio.length === 0" class="text-center text-gray-500 py-12">
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
defineOptions({ name: 'CompanyDashboardPage' })

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const currentUser = ref(null)

// portfolios
const portfoliosPortfolio = ref([])
const allPortfoliosPortfolio = ref([])
const selectedBidangPortfolio = ref(null)

const bidangList = [
  { label: 'Backend', value: 'backend' },
  { label: 'Frontend', value: 'frontend' },
  { label: 'Fullstack', value: 'fullstack' },
  { label: 'QA Tester', value: 'QATester' }
]

const activeClass = 'bg-purple-100 text-purple-700 font-semibold'
const inactiveClass = 'text-gray-700 hover:bg-gray-100'

// search
const searchForm = ref({
  keyword: '',
  skill: '',
  jurusan: '',
  fakultas: '',
  universitas: ''
})

const loadPortfoliosPortfolio = async (bidang = null) => {
  try {
    const params = bidang ? { bidang } : {}
    const res = await axios.get('/api/portfolios/public', { params })
    portfoliosPortfolio.value = res.data.portfolios || []
    if (allPortfoliosPortfolio.value.length === 0) {
      const allRes = await axios.get('/api/portfolios/public')
      allPortfoliosPortfolio.value = allRes.data.portfolios || []
    }
  } catch (error) {
    console.error('Error loading portfolios:', error)
  }
}

const filterByBidangPortfolio = async (bidang) => {
  selectedBidangPortfolio.value = bidang
  await loadPortfoliosPortfolio(bidang)
}

const filteredPortfoliosPortfolio = () => portfoliosPortfolio.value
const totalPortfoliosPortfolio = () => allPortfoliosPortfolio.value.length
const getCountByBidangPortfolio = (bidang) => allPortfoliosPortfolio.value.filter(p => p.bidang === bidang).length

const searchStudents = async () => {
  try {
    const hasSearchCriteria = searchForm.value.keyword || searchForm.value.skill || searchForm.value.jurusan || searchForm.value.fakultas || searchForm.value.universitas
    if (!hasSearchCriteria) {
      alert('Silakan isi minimal satu field pencarian')
      return
    }

    const res = await axios.get('/api/company/search', { params: searchForm.value })
    const students = res.data.data || []
    if (students.length > 0) {
      const studentIds = students.map(s => s.id)
      const params = selectedBidangPortfolio.value ? { bidang: selectedBidangPortfolio.value } : {}
      const allPortfoliosRes = await axios.get('/api/portfolios/public', { params })
      const allPortfoliosList = allPortfoliosRes.data.portfolios || []
      const filtered = allPortfoliosList.filter(p => studentIds.includes(p.mahasiswa_id))
      portfoliosPortfolio.value = filtered
      if (portfoliosPortfolio.value.length === 0) {
        alert(`Ditemukan ${students.length} mahasiswa, tapi tidak ada portofolio yang sesuai dengan kriteria pencarian`)
      }
    } else {
      await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
      alert('Tidak ada mahasiswa yang ditemukan dengan kriteria pencarian tersebut')
    }
  } catch (error) {
    console.error('Error searching students:', error)
    await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
    alert('Gagal melakukan pencarian. Silakan coba lagi.')
  }
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) router.push(`/portfolio/${portfolio.public_link}`)
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (error) {
    console.warn('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    router.push('/login')
  }
}

// run initial load & auth check
;(async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  try {
    const meRes = await axios.get('/api/me')
    const user = meRes.data?.user
    if (!user || user.role !== 'perusahaan') {
      // redirect not allowed
      router.push('/')
      return
    }
    currentUser.value = user
    await loadPortfoliosPortfolio()
  } catch (error) {
    console.error('Auth check error:', error)
    localStorage.removeItem('token')
    router.push('/login')
  }
})()
</script>

<style scoped>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2; }

.dashboard-gradient {
  background:
    radial-gradient(ellipse 150% 80% at 50% 0%,
      #4c1d95 0%,
      #5b21b6 10%,
      #6b21a8 20%,
      #7c3aed 35%,
      #9333ea 50%,
      #a855f7 65%,
      #c084fc 80%,
      #ddd6fe 92%,
      #f3e8ff 98%,
      #ffffff 100%
    );
}
</style>
