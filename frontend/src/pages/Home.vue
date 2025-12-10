<template>
  <div class="home-gradient text-white min-h-screen">
    
    <header class="fixed top-6 left-0 right-0 z-50">
      <nav
        class="max-w-6xl mx-auto py-3 px-6 bg-white rounded-full flex justify-between items-center shadow-lg"
      >
        <div class="flex items-center gap-3">
          <span class="text-xl font-bold font-poppins text-purple-700"
            >Porto Connect</span
          >
          <img
          />
          <img
            src="@/assets/logo-soegija.png"
            alt="Soegijapranata Logo"
            class="h-8"
          />
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

        <div
          class="flex items-center gap-1 bg-black rounded-full py-1.5 px-2 font-roboto"
        >
          <div v-if="currentUser">
            <router-link
              v-if="currentUser.role === 'mahasiswa'"
              to="/profile/mahasiswa"
              class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition"
            >
              Dashboard Saya
            </router-link>
            <router-link
              v-else-if="currentUser.role === 'perusahaan'"
              to="/dashboard/perusahaan"
              class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition"
            >
              Dashboard
            </router-link>
            <button
              @click="handleLogout"
              class="py-1.5 px-4 rounded-full bg-white text-black hover:bg-gray-200 transition"
            >
              Logout
            </button>
          </div>
          <template v-else>
            <router-link
              to="/register"
              class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition"
            >
              Sign Up
            </router-link>
            <router-link
              to="/login"
              class="py-1.5 px-4 rounded-full bg-white text-black hover:bg-gray-200 transition"
            >
              Login
            </router-link>
          </template>
        </div>
      </nav>
    </header>

    <main
      class="pt-32 pb-32 min-h-screen flex items-center relative main-section-curved"
    >
      <div
        class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-16 px-6"
      >
        <div class="text-left">
          <h1
            class="text-5xl md:text-6xl font-bold font-poppins mb-6 leading-tight text-purple-300"
          >
            Showcase Your Potential.
            <span class="text-white">Connect with Top Employers.</span>
          </h1>
          <blockquote class="text-lg text-gray-300 italic mb-4 font-roboto">
            "I'm convinced that about half of what separates successful
            entrepreneurs from the non-successful ones is pure perseverance."
          </blockquote>
          <p class="text-gray-400 mb-8 font-roboto">
            Steve Jobs (Co-founder Apple)
          </p>
          <button
            @click="scrollToTeams"
            class="bg-white text-black font-semibold py-2 px-8 rounded-full shadow-lg hover:bg-gray-200 transition"
          >
            About
          </button>
        </div>

        <div class="flex justify-center">
          <img
            src="@/assets/logo-s-besar.png"
            alt="Soegija Logo S"
            class="w-64 h-64 md:w-96 md:h-96"
          />
        </div>
      </div>
    </main>

    <!-- Portfolio Preview Section -->
    <section class="py-24 bg-white text-black relative overflow-hidden" style="margin-top: -80px; padding-top: 104px;">
      <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-3xl font-poppins text-purple-800 mb-12 text-center">
          Jelajahi Portofolio Mahasiswa
        </h2>
        
        <div class="portfolio-scroll-container" @mouseenter="pauseScroll" @mouseleave="resumeScroll">
          <div 
            class="portfolio-scroll-wrapper"
            :class="{ 'paused': isPaused }"
          >
            <div
              v-for="(portfolio, index) in scrollingPortfolios"
              :key="`${portfolio.id}-${index}`"
              class="portfolio-preview-card cursor-pointer mb-6"
              @click="viewPortfolio(portfolio)"
            >
              <!-- Preview Card -->
              <div class="bg-white rounded-xl shadow-xl overflow-hidden border-2 border-gray-200 hover:border-purple-500 hover:shadow-2xl transition-all duration-300">
                <div class="bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 h-48 relative">
                  <div class="absolute inset-0 bg-black/20"></div>
                  <div class="absolute bottom-6 left-6 right-6">
                    <h3 class="text-white font-bold text-2xl">{{ portfolio.nama || 'Portofolio' }}</h3>
                  </div>
                </div>
                <div class="p-6">
                  <div class="flex items-start gap-4 mb-4">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                      {{ portfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="font-bold text-gray-800 text-xl mb-1">{{ portfolio.mahasiswa?.user?.name }}</h4>
                      <p class="text-gray-600">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</p>
                    </div>
                  </div>
                  <div v-if="portfolio.bidang" class="mb-4">
                    <span class="inline-block bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold">
                      {{ portfolio.bidang }}
                    </span>
                  </div>
                  <p v-if="portfolio.deskripsi" class="text-gray-600 line-clamp-3 mb-4 text-base">
                    {{ portfolio.deskripsi }}
                  </p>
                  <div v-if="portfolio.skills && portfolio.skills.length > 0" class="flex flex-wrap gap-2">
                    <span
                      v-for="skill in portfolio.skills.slice(0, 5)"
                      :key="skill.id"
                      class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full"
                    >
                      {{ skill.nama }}
                    </span>
                    <span v-if="portfolio.skills.length > 5" class="text-sm text-gray-500 self-center">
                      +{{ portfolio.skills.length - 5 }} lainnya
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="featuredPortfolios.length === 0" class="text-center py-12 text-gray-500">
          <p>Belum ada portofolio yang tersedia</p>
        </div>
      </div>
    </section>

    <section id="our-teams" class="py-24 bg-white text-black">
      <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-poppins text-purple-800 mb-8">
          "If you want to go fast, go alone. If you want to go far, go
          together."
        </h2>
        <button
          class="bg-white text-black font-semibold py-2 px-8 rounded-full border-2 border-black hover:bg-gray-100 transition mb-16"
        >
          Our Teams
        </button>

        <div class="flex justify-center items-center gap-8">
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-purple-800"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-yellow-400"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-black"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-yellow-400"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-purple-800"
          ></div>
        </div>
      </div>
    </section>

    <footer class="bg-purple-900 text-white py-16 font-roboto">
      <div
        class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center"
      >
        <div>
          <h3
            class="text-2xl md:text-3xl font-bold font-poppins mb-4"
          >
            Informasi Kontak
          </h3>
          <ul class="space-y-2 text-gray-300">
            <li>
              Email :
              <a
                href="mailto:unika@unika.ac.id"
                class="hover:text-purple-300 transition"
                >unika@unika.ac.id</a
              >
            </li>
            <li>Hotline : (024) 850 5003</li>
            <li>
              WhatsApp Official :
              <a
                href="https://wa.me/6281232345479"
                class="hover:text-purple-300 transition"
                >08123 2345 479</a
              >
            </li>
          </ul>
        </div>

        <div
          class="flex items-center justify-center md:justify-end gap-4"
        >
          <span class="text-3xl font-poppins text-white"
            >Porto Connect</span
          >
     
        </div>
      </div>
      <div class="border-t border-purple-800 mt-12 pt-8 text-center text-gray-500 text-sm">
        &copy; 2025 PortoConnect. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const currentUser = ref(null)
const featuredPortfolios = ref([])
const scrollingPortfolios = ref([])
const isPaused = ref(false)

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
  
  // Load featured portfolios
  await loadFeaturedPortfolios()
  setupScrollingPortfolios()
})

const loadFeaturedPortfolios = async () => {
  try {
    const res = await axios.get('/api/portfolios/public')
    // Take only 5 portfolios for preview
    featuredPortfolios.value = (res.data.portfolios || []).slice(0, 5)
  } catch (error) {
    console.error('Error loading portfolios:', error)
  }
}

const setupScrollingPortfolios = () => {
  // Duplicate portfolios untuk infinite scroll effect
  scrollingPortfolios.value = [...featuredPortfolios.value, ...featuredPortfolios.value]
}

const pauseScroll = () => {
  isPaused.value = true
}

const resumeScroll = () => {
  isPaused.value = false
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const scrollToTeams = () => {
  const teamsSection = document.getElementById('our-teams')
  if (teamsSection) {
    teamsSection.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('token')
    delete axios.defaults.headers.common['Authorization']
    currentUser.value = null
    router.push('/')
  } catch (error) {
    localStorage.removeItem('token')
    currentUser.value = null
    router.push('/')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins {
  font-family: 'Poppins', sans-serif;
}
.font-roboto {
  font-family: 'Roboto', sans-serif;
}

/* Portfolio Preview Styles */
.portfolio-scroll-container {
  position: relative;
  height: 600px;
  overflow: hidden;
  mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
  -webkit-mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
}

.portfolio-scroll-wrapper {
  animation: scrollUp 20s linear infinite;
  will-change: transform;
}

.portfolio-scroll-wrapper.paused {
  animation-play-state: paused;
}

@keyframes scrollUp {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(-50%);
  }
}

.portfolio-preview-card {
  transition: all 0.3s ease;
  width: 100%;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.home-gradient {
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

.main-section-curved {
  border-bottom-left-radius: 150px;
  border-bottom-right-radius: 150px;
  overflow: hidden;
  margin-bottom: -80px;
  padding-bottom: 100px;
  position: relative;
  z-index: 1;
}
</style>