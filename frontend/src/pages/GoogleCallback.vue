<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
      <h1 class="text-2xl font-semibold">Memproses login Anda...</h1>
      <p class="text-gray-600">Mohon tunggu sebentar.</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

onMounted(() => {
  alert('DEBUG: Halaman GoogleCallback.vue BERHASIL DIMUAT.')

  const route = useRoute()
  const router = useRouter()
  const token = route.query.token

  if (token) {
    localStorage.setItem('auth_token', token)
    router.push('/dashboard')
  } else {
    console.error('Login Google gagal: Token tidak ditemukan.')
    router.push('/?error=token_not_found')
  }
})
</script>