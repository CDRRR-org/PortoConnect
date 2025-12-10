<!-- src/pages/Home.vue -->
<template>
  <div class="home-gradient text-white min-h-screen">
    <!-- NAVBAR -->
    <header class="fixed top-6 left-0 right-0 z-50">
      <nav class="max-w-6xl mx-auto py-3 px-6 bg-white rounded-full flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
          <span class="text-xl font-bold font-poppins text-purple-700">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="Soegijapranata Logo" class="h-8" />
        </div>

        <div class="hidden md:flex items-center gap-8 font-roboto">
          <router-link to="/" class="text-gray-700 hover:text-purple-700 transition">Home</router-link>
          <router-link v-if="!currentUser || currentUser.role !== 'perusahaan'" to="/explore" class="text-gray-700 hover:text-purple-700 transition">Portofolio</router-link>
        </div>

        <div class="flex items-center gap-1 bg-black rounded-full py-1.5 px-2 font-roboto">
          <div v-if="currentUser">
            <router-link v-if="currentUser.role === 'mahasiswa'" to="/profile/mahasiswa" class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition">Dashboard Saya</router-link>
            <router-link v-else-if="currentUser.role === 'perusahaan'" to="/dashboard/perusahaan" class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition">Dashboard</router-link>
            <button @click="handleLogout" class="py-1.5 px-4 rounded-full bg-white text-black hover:bg-gray-200 transition">Logout</button>
          </div>

          <template v-else>
            <router-link to="/register" class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition">Sign Up</router-link>
            <router-link to="/login" class="py-1.5 px-4 rounded-full bg-white text-black hover:bg-gray-200 transition">Login</router-link>
          </template>
        </div>
      </nav>
    </header>

    <!-- HERO -->
    <main class="pt-32 pb-32 min-h-screen flex items-center relative main-section-curved">
      <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-16 px-6">
        <div class="text-left">
          <h1 class="hero-title text-5xl md:text-6xl font-poppins mb-6 leading-tight">
            Showcase Your Potential.
            <span class="text-white">Connect with Top Employers.</span>
          </h1>
          <blockquote class="text-lg text-white/80 italic mb-4 font-roboto">
            "I'm convinced that about half of what separates successful entrepreneurs from the non-successful ones is pure perseverance."
          </blockquote>
          <p class="text-white mb-8 font-roboto">Steve Jobs (Co-founder Apple)</p>
          <button @click="scrollToTeams" class="bg-white text-black font-semibold py-2 px-8 rounded-full shadow-lg hover:bg-gray-200 transition">About</button>
        </div>

        <div class="flex justify-center">
          <img src="@/assets/logo-s-besar.png" alt="Logo S" class="w-64 h-64 md:w-96 md:h-96" />
        </div>
      </div>
    </main>

    <!-- FOLDER GRIP (3 folder shapes) -->
    <FolderGrip />

    <!-- TEAMS -->
    <section id="our-teams" class="py-24 bg-white text-black">
      <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-poppins text-purple-800 mb-8">"If you want to go fast, go alone. If you want to go far, go together."</h2>
        <button class="bg-white text-black font-semibold py-2 px-8 rounded-full border-2 border-black hover:bg-gray-100 transition mb-16">Our Teams</button>

        <!-- FOTO DI DALAM LINGKARAN -->
        <div class="flex justify-center items-center gap-8">
          <div class="circle-img border-4 border-purple-800">
            <img src="@/assets/team1.jpg" alt="Team 1" />
          </div>

          <div class="circle-img border-4 border-yellow-400">
            <img src="@/assets/team2.jpg" alt="Team 2" />
          </div>

          <div class="circle-img border-4 border-black">
            <img src="@/assets/team3.jpg" alt="Team 3" />
          </div>

          <div class="circle-img border-4 border-yellow-400">
            <img src="@/assets/team4.jpg" alt="Team 4" />
          </div>

          <div class="circle-img border-4 border-purple-800">
            <img src="@/assets/team5.jpg" alt="Team 5" />
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-purple-900 text-white py-16 font-roboto">
      <div class="max-w-6xl mx-auto px-6 flex flex-col items-center justify-center gap-12">

        <!-- LOGO BAR SEPERTI GAMBAR -->
        <div class="flex items-center justify-center gap-10 md:gap-14">

          <!-- Porto Connect -->
          <div class="text-center">
            <p class="text-2xl font-poppins font-semibold leading-tight">Porto<br />Connect</p>
          </div>

          <!-- Icon X (garis silang) -->
          <div>
            <svg width="55" height="55" viewBox="0 0 100 100" fill="none" aria-hidden="true">
              <path d="M20 20 L80 80 M80 20 L20 80" stroke="white" stroke-width="8" stroke-linecap="round" />
            </svg>
          </div>

          <!-- Logo S + Text Universitas -->
          <div class="flex items-center gap-4">
            <img src="@/assets/logo-soegija-putih.png" class="h-14 w-auto" alt="Logo Soegija">
            <div class="leading-tight">
              <p class="text-xl font-poppins font-semibold">SOEGIJAPRANATA</p>
              <p class="text-lg font-poppins tracking-wide">CATHOLIC UNIVERSITY</p>
            </div>
          </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="text-center">
          <h3 class="text-2xl font-bold font-poppins mb-4">Informasi Kontak</h3>
          <ul class="space-y-1 text-gray-300">
            <li>Email : <a href="mailto:unika@unika.ac.id" class="hover:text-purple-300 transition">unika@unika.ac.id</a></li>
            <li>Hotline : (024) 850 5003</li>
            <li>WhatsApp Official : <a href="https://wa.me/6281232345479" class="hover:text-purple-300 transition">08123 2345 479</a></li>
          </ul>
        </div>

        <div class="border-t border-purple-800 pt-6 text-center text-gray-400 text-sm w-full">
          © 2025 PortoConnect. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
/* multi-word name to satisfy lint rules */
defineOptions({ name: 'HomePage' })

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import FolderGrip from '@/components/FolderGrip.vue'

const router = useRouter()
const currentUser = ref(null)

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    try {
      const res = await axios.get('/api/me')
      currentUser.value = res.data.user
    } catch (err) {
      console.warn('Error fetching /api/me:', err)
    }
  }
})

const scrollToTeams = () => {
  const el = document.getElementById('our-teams')
  if (el) el.scrollIntoView({ behavior: 'smooth' })
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
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;900&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }

/* HERO */
.hero-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 900;
  font-size: clamp(48px, 8.5vw, 70px);
  line-height: 0.94;
  letter-spacing: -1px;
  -webkit-text-fill-color: #50145C;
  color: #50145C;
  -webkit-text-stroke: 4px #ffffff;
}

/* PAGE BG */
.home-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}

/* curved section */
.main-section-curved {
  border-bottom-left-radius: 150px;
  border-bottom-right-radius: 150px;
  overflow: hidden;
  margin-bottom: -80px;
  padding-bottom: 100px;
  position: relative;
  z-index: 1;
}

/* circle images (team) */
.circle-img {
  width: 120px;
  height: 120px;
  border-radius: 999px;
  overflow: hidden;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.circle-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* responsive tweaks */
@media (min-width: 768px) {
  .circle-img { width: 140px; height: 140px; }
  .hero-title { -webkit-text-stroke: 5px #ffffff; }
}

@media (max-width: 640px) {
  .circle-img { width: 88px; height: 88px; }
  .hero-title { font-size: 36px; -webkit-text-stroke: 3px #ffffff; }
}
</style>
