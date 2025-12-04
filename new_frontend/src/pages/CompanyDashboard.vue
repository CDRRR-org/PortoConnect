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
        <!-- Sidebar Kategori -->
        <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit sticky top-4">
          <h3 class="font-bold text-gray-800 mb-4">Kategori Bidang</h3>
          <ul class="space-y-2">
            <li>
              <button
                @click="filterByBidangPortfolio(null)"
                :class="selectedBidangPortfolio === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Semua Bidang
                <span class="ml-auto text-sm text-gray-500">({{ totalPortfoliosPortfolio }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('backend')"
                :class="selectedBidangPortfolio === 'backend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Backend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('backend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('frontend')"
                :class="selectedBidangPortfolio === 'frontend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Frontend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('frontend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('fullstack')"
                :class="selectedBidangPortfolio === 'fullstack' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Fullstack
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('fullstack') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('QATester')"
                :class="selectedBidangPortfolio === 'QATester' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                QA Tester
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('QATester') }})</span>
              </button>
            </li>
          </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
              <h2 class="text-xl font-bold mb-4">Cari Mahasiswa</h2>
              <form @submit.prevent="searchStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                  <input v-model="searchForm.keyword" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Nama, NIM, dll" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Skill</label>
                  <input v-model="searchForm.skill" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="JavaScript, Python, dll" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                  <input v-model="searchForm.jurusan" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Teknik Informatika" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                  <input v-model="searchForm.fakultas" type="text" class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                  <input v-model="searchForm.universitas" type="text" class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div class="flex items-end">
                  <button type="submit" class="w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
                    Cari
                  </button>
                </div>
              </form>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-6">Jelajahi Portofolio Mahasiswa</h2>
            
            <!-- Portfolio Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="portfolio in filteredPortfoliosPortfolio"
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const currentUser = ref(null)

// Portofolio Tab Data
const portfoliosPortfolio = ref([])
const allPortfoliosPortfolio = ref([])
const selectedBidangPortfolio = ref(null)

// Search Data
const searchResults = ref([])
const searchForm = ref({
  keyword: '',
  skill: '',
  jurusan: '',
  fakultas: '',
  universitas: ''
})

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  
  // Validasi role - pastikan user adalah perusahaan
  try {
    const res = await axios.get('/api/me')
    const user = res.data?.user
    
    if (!user || user.role !== 'perusahaan') {
      // Redirect berdasarkan role
      if (user?.role === 'admin') {
        router.push('/dashboard/admin')
      } else if (user?.role === 'mahasiswa') {
        router.push('/profile/mahasiswa')
      } else {
        router.push('/dashboard')
      }
      return
    }
    
    // Jika user adalah perusahaan, lanjutkan load data
    currentUser.value = user
    await loadPortfoliosPortfolio()
  } catch (error) {
    console.error('Error validating company access:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      alert('Gagal memuat data. Silakan refresh halaman.')
    }
  }
})

// Portofolio Tab Functions
const loadPortfoliosPortfolio = async (bidang = null) => {
  try {
    const params = bidang ? { bidang } : {}
    const response = await axios.get('/api/portfolios/public', { params })
    portfoliosPortfolio.value = response.data.portfolios || []
    
    if (allPortfoliosPortfolio.value.length === 0) {
      const allResponse = await axios.get('/api/portfolios/public')
      allPortfoliosPortfolio.value = allResponse.data.portfolios || []
    }
  } catch (error) {
    console.error('Error loading portfolios:', error)
  }
}

const filterByBidangPortfolio = async (bidang) => {
  selectedBidangPortfolio.value = bidang
  
  // If there are search results, filter them by bidang
  if (searchResults.value.length > 0) {
    const studentIds = searchResults.value.map(s => s.id)
    const params = bidang ? { bidang } : {}
    const allPortfoliosRes = await axios.get('/api/portfolios/public', { params })
    const allPortfoliosList = allPortfoliosRes.data.portfolios || []
    
    portfoliosPortfolio.value = allPortfoliosList.filter(p => 
      studentIds.includes(p.mahasiswa_id)
    )
  } else {
    // If no search results, just load all portfolios with bidang filter
    await loadPortfoliosPortfolio(bidang)
  }
}

const filteredPortfoliosPortfolio = computed(() => {
  return portfoliosPortfolio.value
})

const totalPortfoliosPortfolio = computed(() => {
  return allPortfoliosPortfolio.value.length
})

const getCountByBidangPortfolio = (bidang) => {
  return allPortfoliosPortfolio.value.filter(p => p.bidang === bidang).length
}

// Search Functions
const searchStudents = async () => {
  try {
    // Check if at least one search field is filled
    const hasSearchCriteria = searchForm.value.keyword || 
                              searchForm.value.skill || 
                              searchForm.value.jurusan || 
                              searchForm.value.fakultas || 
                              searchForm.value.universitas
    
    if (!hasSearchCriteria) {
      alert('Silakan isi minimal satu field pencarian')
      return
    }

    console.log('Searching with params:', searchForm.value) // Debug log

    const res = await axios.get('/api/company/search', { params: searchForm.value })
    
    console.log('Search API response:', res.data) // Debug log
    
    // Handle pagination response - Laravel pagination returns data in 'data' key
    const students = res.data.data || []
    searchResults.value = students
    
    console.log('Found students:', students.length) // Debug log
    
    // Get all portfolios from searched students
    if (students.length > 0) {
      const studentIds = students.map(s => s.id)
      console.log('Student IDs:', studentIds) // Debug log
      
      // Load portfolios for these students with bidang filter
      const params = selectedBidangPortfolio.value ? { bidang: selectedBidangPortfolio.value } : {}
      const allPortfoliosRes = await axios.get('/api/portfolios/public', { params })
      const allPortfoliosList = allPortfoliosRes.data.portfolios || []
      
      console.log('All portfolios:', allPortfoliosList.length) // Debug log
      console.log('Portfolio mahasiswa_ids:', allPortfoliosList.map(p => p.mahasiswa_id)) // Debug log
      
      // Filter portfolios to show only from searched students
      let filteredPortfolios = allPortfoliosList.filter(p => 
        studentIds.includes(p.mahasiswa_id)
      )
      
      // Additional filter: if keyword is searched, filter portfolios by keyword (nama, deskripsi, bidang)
      if (searchForm.value.keyword && searchForm.value.keyword.trim() !== '') {
        const searchKeyword = searchForm.value.keyword.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioNama = (portfolio.nama || '').toLowerCase()
          const portfolioDeskripsi = (portfolio.deskripsi || '').toLowerCase()
          const portfolioBidang = (portfolio.bidang || '').toLowerCase()
          const mahasiswaName = (portfolio.mahasiswa?.user?.name || '').toLowerCase()
          const mahasiswaNim = (portfolio.mahasiswa?.nim || '').toLowerCase()
          
          return portfolioNama.includes(searchKeyword) ||
                 portfolioDeskripsi.includes(searchKeyword) ||
                 portfolioBidang.includes(searchKeyword) ||
                 mahasiswaName.includes(searchKeyword) ||
                 mahasiswaNim.includes(searchKeyword)
        })
        console.log('After keyword filter:', filteredPortfolios.length) // Debug log
      }
      
      // Additional filter: if skill is searched, filter portfolios by skill
      if (searchForm.value.skill && searchForm.value.skill.trim() !== '') {
        const searchSkill = searchForm.value.skill.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          // Check if portfolio has skills loaded
          if (portfolio.skills && portfolio.skills.length > 0) {
            return portfolio.skills.some(skill => 
              skill.nama && skill.nama.toLowerCase().includes(searchSkill)
            )
          }
          return false
        })
        console.log('After skill filter:', filteredPortfolios.length) // Debug log
      }
      
      // Additional filter: if jurusan is searched, filter portfolios by jurusan
      if (searchForm.value.jurusan && searchForm.value.jurusan.trim() !== '') {
        const searchJurusan = searchForm.value.jurusan.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioJurusan = (portfolio.mahasiswa?.jurusan || '').toLowerCase()
          return portfolioJurusan.includes(searchJurusan)
        })
        console.log('After jurusan filter:', filteredPortfolios.length) // Debug log
      }
      
      // Additional filter: if fakultas is searched, filter portfolios by fakultas
      if (searchForm.value.fakultas && searchForm.value.fakultas.trim() !== '') {
        const searchFakultas = searchForm.value.fakultas.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioFakultas = (portfolio.mahasiswa?.fakultas || '').toLowerCase()
          return portfolioFakultas.includes(searchFakultas)
        })
        console.log('After fakultas filter:', filteredPortfolios.length) // Debug log
      }
      
      // Additional filter: if universitas is searched, filter portfolios by universitas
      if (searchForm.value.universitas && searchForm.value.universitas.trim() !== '') {
        const searchUniversitas = searchForm.value.universitas.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioUniversitas = (portfolio.mahasiswa?.universitas || '').toLowerCase()
          return portfolioUniversitas.includes(searchUniversitas)
        })
        console.log('After universitas filter:', filteredPortfolios.length) // Debug log
      }
      
      portfoliosPortfolio.value = filteredPortfolios
      
      console.log('Filtered portfolios:', portfoliosPortfolio.value.length) // Debug log
      
      if (portfoliosPortfolio.value.length === 0) {
        alert(`Ditemukan ${students.length} mahasiswa, tapi tidak ada portofolio yang sesuai dengan kriteria pencarian`)
      } else {
        // Success - portfolios are already updated
        console.log('Search successful, showing portfolios')
      }
    } else {
      // If no search results, reload with current bidang filter
      await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
      alert('Tidak ada mahasiswa yang ditemukan dengan kriteria pencarian tersebut')
    }
  } catch (error) {
    console.error('Error searching students:', error)
    console.error('Error response:', error.response) // Debug log
    
    const errorMessage = error.response?.data?.message || 
                        error.response?.data?.errors || 
                        'Gagal melakukan pencarian. Silakan coba lagi.'
    
    if (typeof errorMessage === 'object') {
      const errorList = Object.values(errorMessage).flat().join(', ')
      alert(`Error: ${errorList}`)
    } else {
      alert(`Error: ${errorMessage}`)
    }
    
    // If error, reload all portfolios
    await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
  }
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('token')
    router.push('/login')
  } catch (error) {
    localStorage.removeItem('token')
    router.push('/login')
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
