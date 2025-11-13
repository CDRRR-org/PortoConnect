<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-b from-purple-800 to-purple-300"
  >
    <div
      class="bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-full max-w-md text-center"
    >
      <h1 class="text-2xl font-bold text-purple-700 mb-4 font-poppins">
        Satu Langkah Lagi!
      </h1>
      <p class="text-gray-600 mb-8 font-roboto">
        Beri tahu kami siapa Anda untuk melengkapi profil.
      </p>

      <div class="flex flex-col gap-4">
        <button
          @click="selectRole('mahasiswa')"
          class="bg-purple-700 hover:bg-purple-800 text-white font-semibold py-3 rounded-xl transition duration-200 shadow-md"
        >
          Saya seorang Mahasiswa
        </button>
        <button
          @click="selectRole('perusahaan')"
          class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-3 rounded-xl transition duration-200 shadow-md"
        >
          Saya mewakili Perusahaan
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const selectRole = async (role) => {
  try {
    // NANTI: Kita harus buat API endpoint '/api/set-role'
    // await axios.post('/api/set-role', { role: role })
    
    alert('Role ' + role + ' dipilih! (API belum dibuat). Mengarahkan ke Dashboard...')
    router.push('/dashboard')

  } catch (error) {
    console.error('Gagal memilih role:', error)
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