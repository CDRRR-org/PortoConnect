<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center dashboard-gradient text-white"
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
/*
  Perbaikan:
  - nama komponen multi-word -> DashboardPage
  - catch(err) digunakan dengan console.error supaya linter tidak mengeluh
*/
defineOptions({ name: 'DashboardPage' })

import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

onMounted(async () => {
  try {
    const res = await axios.get('/api/me')
    const user = res.data?.user

    // jika user not found -> redirect ke login
    if (!user) {
      router.push('/login')
      return
    }

    // Redirect berdasarkan role
    if (user.role === 'admin') {
      router.push('/dashboard/admin')
    } else if (user.role === 'mahasiswa') {
      router.push('/profile/mahasiswa') // Redirect to profile view only
    } else if (user.role === 'perusahaan') {
      router.push('/dashboard/perusahaan')
    } else {
      // fallback
      router.push('/')
    }
  } catch (err) {
    // gunakan err untuk debugging (lint friendly)
    console.error('Error loading user:', err)
    // Pastikan token dibersihkan kalau ada error auth
    try { localStorage.removeItem('token') } catch (err) {
  void err; // gunakan variabel supaya linter tidak complain
  // atau: /* eslint-disable-next-line no-unused-vars */ sebelum baris catch
}
    router.push('/login')
  }
})

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    console.error('Gagal logout:', err)
  } finally {
    // cleanup dan redirect di finally supaya pasti dieksekusi
    try { localStorage.removeItem('token') } catch (err) {
  void err; // gunakan variabel supaya linter tidak complain
  // atau: /* eslint-disable-next-line no-unused-vars */ sebelum baris catch
}
    router.push('/login')
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
