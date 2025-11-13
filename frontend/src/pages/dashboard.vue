<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-purple-700 to-purple-300 text-white"
  >
    <h1 class="text-5xl font-bold mb-4 font-poppins">Dashboard</h1>
    <p class="text-lg font-roboto mb-8">
      Selamat datang di PortoConnect 🎉
    </p>

    <button
      @click="handleLogout"
      class="bg-white text-purple-700 px-5 py-2 rounded-lg font-semibold hover:bg-purple-100 transition"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    router.push('/')
  } catch (error) {
    console.error('Gagal logout:', error)
    alert('Logout gagal, coba lagi.')
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
</style>