<template>
  <div class="min-h-screen bg-gradient-to-b from-purple-800 via-purple-600 to-purple-400 flex flex-col">
    <!-- Header -->
    <header class="bg-gray-100 rounded-b-lg shadow-sm mb-8">
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
            <router-link to="/" class="text-purple-700 font-semibold">Home</router-link>
            <router-link to="/explore" class="text-purple-700 font-semibold">Portofolio</router-link>
            <div v-if="currentUser" class="flex items-center gap-2">
              <router-link 
                v-if="currentUser.role === 'mahasiswa'"
                to="/dashboard/mahasiswa" 
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 flex-grow">
      <h2 class="text-3xl font-bold text-white mb-8 text-center">Jelajahi Portofolio Mahasiswa</h2>
      
      <!-- Portfolio Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="portfolio in portfolios"
          :key="portfolio.id"
          class="bg-white/20 backdrop-blur-sm rounded-lg p-6 hover:bg-white/30 transition cursor-pointer transform hover:scale-105"
          @click="viewPortfolio(portfolio)"
        >
          <div class="w-20 h-20 mx-auto mb-4 bg-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
            {{ portfolio.mahasiswa?.user?.name?.charAt(0) || 'U' }}
          </div>
          <h3 class="text-xl font-bold text-white text-center mb-2">{{ portfolio.mahasiswa?.user?.name }}</h3>
          <p class="text-white/80 text-center text-sm mb-4">{{ portfolio.mahasiswa?.jurusan || '-' }}</p>
          <div class="flex flex-wrap gap-2 justify-center">
            <span
              v-for="skill in portfolio.mahasiswa?.skills?.slice(0, 3)"
              :key="skill.id"
              class="bg-purple-700 text-white text-xs px-2 py-1 rounded"
            >
              {{ skill.nama }}
            </span>
          </div>
        </div>
      </div>

      <div v-if="portfolios.length === 0" class="text-center text-white/80 py-12">
        <p>Tidak ada portofolio yang ditemukan</p>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-900 py-8 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h4 class="text-white font-bold mb-4">Informasi Kontak</h4>
            <p class="text-white/80">Email : contact@portoconnect.com</p>
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
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const portfolios = ref([])
const currentUser = ref(null)

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

const loadPortfolios = async () => {
  try {
    // Get all public portfolios
    const response = await axios.get('/api/portfolios/public')
    portfolios.value = response.data.portfolios || []
  } catch (error) {
    console.error('Error loading portfolios:', error)
  }
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    window.open(`/portfolio/${portfolio.public_link}`, '_blank')
  }
}
</script>
