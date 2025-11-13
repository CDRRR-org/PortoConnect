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
      <h1
        class="text-3xl font-bold text-purple-700 mb-2 font-poppins tracking-wide"
      >
        Welcome to <span class="text-purple-900">PortoConnect</span>
      </h1>
      <p class="text-gray-600 mb-8 font-roboto">
        Silakan login terlebih dahulu
      </p>

      <a
        :href="googleLoginUrl"
        class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-xl transition duration-200 shadow-md flex items-center justify-center gap-3"
      >
        <img
          src="@/assets/logo_google.png"
          alt="Google Logo"
          class="w-6 h-6"
        />
        <span>Login dengan Google</span>
      </a>

      <div class="my-6 flex items-center">
        <hr class="flex-grow border-gray-300" />
        <span class="mx-4 text-gray-500 text-sm">ATAU</span>
        <hr class="flex-grow border-gray-300" />
      </div>

      <div class="flex flex-col gap-3">
        <p class="text-gray-600 mb-2">Login manual (belum berfungsi):</p>
        <router-link
          to="#"
          class="bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-xl transition duration-200 shadow-md text-center opacity-50 cursor-not-allowed"
        >
          Login sebagai Mahasiswa
        </router-link>
      </div>

      <p class="mt-6 text-sm text-gray-600">
        Belum punya akun?
        <a href="#" class="text-purple-700 hover:underline font-semibold">
          Daftar sekarang
        </a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const googleLoginUrl = ref('http://localhost:8000/auth/google')
const isProcessingCallback = ref(false)

const route = useRoute()
const router = useRouter()

const handleCallbackCheck = async (path) => {
  if (path === '/auth/callback') {
    isProcessingCallback.value = true

    try {
      const response = await axios.get('/api/me')
      const user = response.data

      if (user.role === 'admin') {
        window.location.href = '/admin-dashboard'
      } else if (user.role === 'mahasiswa' || user.role === 'perusahaan') {
        window.location.href = '/dashboard'
      } else {
        window.location.href = '/pilih-role'
      }
    } catch (error) {
      console.error('Gagal mengambil data user:', error)
      window.location.href = '/?error=fetch_user_failed'
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
</style>