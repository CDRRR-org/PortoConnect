<template>
  <div class="min-h-screen flex items-center justify-center dashboard-gradient">
    <div class="text-center bg-white/90 backdrop-blur-sm rounded-lg p-8 shadow-lg">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto mb-4"></div>
      <h1 class="text-2xl font-semibold text-gray-800">Memproses login Anda...</h1>
      <p class="text-gray-600 mt-2">Mohon tunggu sebentar.</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const token = route.query.token

  if (!token) {
    router.push('/login?error=token_not_found')
    return
  }

  try {
    // Store token immediately
    localStorage.setItem('token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    // Get user data quickly
    const response = await axios.get('/api/me', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })

    const user = response.data?.user
    if (!user) {
      throw new Error('User data tidak ditemukan')
    }

    // Redirect based on role immediately
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
    console.error('Login error:', error)
    localStorage.removeItem('token')
    router.push('/login?error=login_failed')
  }
})
</script>

<style scoped>
.dashboard-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}
</style>