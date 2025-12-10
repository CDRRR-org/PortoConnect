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
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

onMounted(async () => {
  try {
    const res = await axios.get('/api/me')
    const user = res.data.user
    
    // Redirect based on role
    if (user.role === 'admin') {
      router.push('/dashboard/admin')
    } else if (user.role === 'mahasiswa') {
      router.push('/profile/mahasiswa') // Redirect to profile view only
    } else if (user.role === 'perusahaan') {
      router.push('/dashboard/perusahaan')
    }
  } catch (error) {
    console.error('Error loading user:', error)
    router.push('/login')
  }
})

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('token')
    router.push('/login')
  } catch (error) {
    console.error('Gagal logout:', error)
    localStorage.removeItem('token')
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
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}
</style>