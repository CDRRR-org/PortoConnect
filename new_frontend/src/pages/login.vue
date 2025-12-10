<template>
  <div
    v-if="isProcessingCallback"
    class="min-h-screen flex items-center justify-center bg-gray-100"
  >
    <div class="text-center">
      <h1 class="text-2xl font-semibold">Memproses login Anda...</h1>
      <p class="text-gray-600">Mohon tunggu sebentar, mengecek status...</p>
    </div>
  </div>

  <div
    v-else
    class="min-h-screen relative overflow-hidden login-gradient"
  >
    <!-- Background decorative S shapes -->
    <div class="absolute right-0 top-0 bottom-0 w-1/3 flex flex-col items-end justify-center gap-8 pr-8 opacity-20">
      <img src="@/assets/logo-s-besar.png" alt="S" class="w-48 h-48 object-contain" />
      <img src="@/assets/logo-s-besar.png" alt="S" class="w-48 h-48 object-contain" />
      <img src="@/assets/logo-s-besar.png" alt="S" class="w-48 h-48 object-contain" />
    </div>

    <!-- Back button top left -->
    <div class="absolute top-8 left-8">
      <router-link 
        to="/" 
        class="bg-white rounded-full px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition shadow-md"
      >
        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="text-gray-700 font-medium">Back</span>
      </router-link>
    </div>

    <!-- Main card centered -->
    <div class="min-h-screen flex items-center justify-center px-4">
      <div class="bg-purple-200 rounded-3xl p-12 shadow-2xl w-full max-w-md relative z-10">
        <h2 class="text-4xl font-bold text-black mb-8 font-poppins leading-tight">
          Welcome to<br>Porto Connect
        </h2>
        
        <a
          :href="googleLoginUrl"
          class="bg-purple-700 hover:bg-purple-800 text-white font-semibold py-4 px-6 rounded-full transition duration-200 shadow-lg flex items-center justify-center gap-3 text-lg"
        >
          <svg class="w-6 h-6" viewBox="0 0 24 24">
            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          <span>Sign Up or Login with Google</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useSweetAlert } from '@/composables/useSweetAlert'

const { showError } = useSweetAlert()

const googleLoginUrl = ref('http://localhost:8000/auth/google')
const isProcessingCallback = ref(false)

const route = useRoute()
const router = useRouter()

const handleCallbackCheck = async (path) => {
  if (path === '/auth/callback') {
    isProcessingCallback.value = true

    try {
      // Get token from URL if available
      const urlParams = new URLSearchParams(window.location.search)
      const token = urlParams.get('token')
      
      if (!token) {
        router.push('/login?error=no_token')
        return
      }
      
      // Store token
      localStorage.setItem('token', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      
      // Get user data
      const response = await axios.get('/api/me', {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      })
      
      if (!response.data?.user) {
        throw new Error('User data tidak ditemukan')
      }
      
      const user = response.data.user

      // Redirect based on role
      if (user.role === 'admin') {
        router.push('/dashboard/admin')
      } else if (user.role === 'mahasiswa') {
        router.push('/profile/mahasiswa')
      } else if (user.role === 'perusahaan') {
        router.push('/dashboard/perusahaan')
      } else {
        router.push('/pilih-role')
      }
    } catch (error) {
      // Clear invalid token
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
      
      // Show error message
      let errorMsg = 'Gagal mengambil data user'
      if (error.response?.data?.message) {
        errorMsg = error.response.data.message
      } else if (error.message) {
        errorMsg = error.message
      }
      
      showError('Error: ' + errorMsg + '\nStatus: ' + (error.response?.status || 'Unknown') + '\nSilakan login ulang.')
      
      router.push('/login?error=fetch_user_failed')
    }
  } else {
    isProcessingCallback.value = false
  }
}

onMounted(() => {
  handleCallbackCheck(route.path)
})

watch(
  () => route.path,
  (newPath) => {
    handleCallbackCheck(newPath)
  }
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins {
  font-family: 'Poppins', sans-serif;
}
.font-roboto {
  font-family: 'Roboto', sans-serif;
}

.login-gradient {
  background: linear-gradient(
    to right,
    #ffffff 0%,
    #e9d5ff 20%,
    #d8b4fe 40%,
    #c084fc 60%,
    #9333ea 80%,
    #000000 100%
  );
}
</style>
