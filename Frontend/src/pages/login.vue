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
    class="min-h-screen flex items-center justify-center bg-gradient-to-b from-purple-800 to-purple-300"
  >
    <div
      class="bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-full max-w-md text-center"
    >
      <div class="mb-4 text-left">
        <router-link to="/" class="text-purple-700 hover:text-purple-900 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali
        </router-link>
      </div>
      <h1 class="text-3xl font-bold text-purple-700 mb-2 font-poppins tracking-wide">
        Welcome to <span class="text-purple-900">PortoConnect</span>
      </h1>
      <p class="text-gray-600 mb-8 font-roboto">Silakan login terlebih dahulu</p>

      <a
        :href="googleLoginUrl"
        class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-xl transition duration-200 shadow-md flex items-center justify-center gap-3"
      >
        <img src="@/assets/logo_google.png" alt="Google Logo" class="w-6 h-6" />
        <span>Login dengan Google</span>
      </a>

      <div class="my-6 flex items-center">
        <hr class="flex-grow border-gray-300" />
        <span class="mx-4 text-gray-500 text-sm">ATAU</span>
        <hr class="flex-grow border-gray-300" />
      </div>

      <form @submit.prevent="handleLogin" class="flex flex-col gap-3">
        <input
          v-model="loginForm.email"
          type="email"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
          placeholder="Email"
        />

        <input
          v-model="loginForm.password"
          type="password"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
          placeholder="Password"
        />

        <div
          v-if="loginError"
          class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm"
        >
          {{ loginError }}
        </div>

        <button
          type="submit"
          :disabled="loginLoading"
          class="bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-xl transition duration-200 shadow-md disabled:opacity-50"
        >
          {{ loginLoading ? 'Masuk...' : 'Login' }}
        </button>
      </form>

      <p class="mt-6 text-sm text-gray-600">
        Belum punya akun?
        <router-link to="/register" class="text-purple-700 hover:underline font-semibold">
          Daftar sekarang
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
defineOptions({ name: 'LoginPage' })

import { ref, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const googleLoginUrl = ref('http://localhost:8000/auth/google')
const isProcessingCallback = ref(false)

const loginForm = ref({
  email: '',
  password: ''
})

const loginLoading = ref(false)
const loginError = ref('')
const route = useRoute()
const router = useRouter()

const handleLogin = async () => {
  loginLoading.value = true
  loginError.value = ''

  try {
    const res = await axios.post('/api/login', loginForm.value)
    const token = res.data.token
    const user = res.data.user

    localStorage.setItem('token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    if (user.role === 'admin') router.push('/dashboard/admin')
    else if (user.role === 'mahasiswa') router.push('/profile/mahasiswa')
    else if (user.role === 'perusahaan') router.push('/dashboard/perusahaan')
    else router.push('/pilih-role')
  } catch (error) {
    loginError.value = error.response?.data?.message || 'Login gagal.'
  } finally {
    loginLoading.value = false
  }
}

const handleCallbackCheck = async (path) => {
  if (path !== '/auth/callback') return (isProcessingCallback.value = false)

  isProcessingCallback.value = true

  try {
    const params = new URLSearchParams(window.location.search)
    const token = params.get('token')
    if (!token) return router.push('/login?error=no_token')

    localStorage.setItem('token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    const res = await axios.get('/api/me')
    const user = res.data.user

    if (!user) throw new Error('User tidak ditemukan')

    if (user.role === 'admin') router.push('/dashboard/admin')
    else if (user.role === 'mahasiswa') router.push('/profile/mahasiswa')
    else if (user.role === 'perusahaan') router.push('/dashboard/perusahaan')
    else router.push('/pilih-role')
  } catch (error) {
    console.warn('Login callback error:', error)
    localStorage.removeItem('token')
    delete axios.defaults.headers.common['Authorization']
    router.push('/login?error=fetch_user_failed')
  }
}

onMounted(() => handleCallbackCheck(route.path))
watch(() => route.path, handleCallbackCheck)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }
</style>
