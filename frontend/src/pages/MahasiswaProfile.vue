<template>
  <div class="min-h-screen bg-gradient-to-b from-purple-800 via-purple-600 to-purple-400">
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
            <router-link to="/explore" class="text-gray-700 hover:text-purple-700">Home</router-link>
            <router-link to="/explore" class="text-gray-700 hover:text-purple-700">Portofolio</router-link>
            <div class="flex items-center gap-2 bg-gray-200 px-4 py-2 rounded-lg">
              <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white">
                {{ user?.name?.charAt(0) || 'U' }}
              </div>
              <span class="font-semibold">{{ user?.name || 'User' }}</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
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

            <!-- My Document Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">My document</h3>
              <div class="flex gap-4">
                <div class="w-16 h-16 border-2 border-gray-400 rounded-lg flex items-center justify-center cursor-pointer hover:border-purple-600">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="w-16 h-16 border-2 border-gray-400 rounded-lg flex items-center justify-center cursor-pointer hover:border-purple-600" @click="showAddDocument = true">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Edit Profile Button -->
            <button
              @click="goToEdit"
              class="w-full bg-purple-700 text-white px-6 py-3 rounded-lg hover:bg-purple-800 font-semibold transition"
            >
              Edit Profil
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-900 mt-12 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h4 class="text-white font-bold mb-4">Informasi Kontak</h4>
            <p class="text-white/80">Email : {{ user?.email || 'contact@portoconnect.com' }}</p>
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
const user = ref(null)
const mahasiswa = ref(null)

onMounted(async () => {
  await loadData()
})

const loadData = async () => {
  try {
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const res = await axios.get('/api/mahasiswa/profile')
    user.value = res.data.mahasiswa.user
    mahasiswa.value = res.data.mahasiswa
  } catch (error) {
    console.error('Error loading profile:', error)
    if (error.response?.status === 401) {
      router.push('/login')
    }
  }
}

const goToEdit = () => {
  router.push('/dashboard/mahasiswa?tab=profile')
}
</script>

