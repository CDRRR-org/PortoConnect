<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-purple-800 to-purple-300">
    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-full max-w-md">

      <div class="mb-4">
        <router-link to="/login" class="text-purple-700 hover:text-purple-900 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali
        </router-link>
      </div>

      <h1 class="text-3xl font-bold text-purple-700 mb-2 font-poppins tracking-wide text-center">
        Daftar Akun Baru
      </h1>
      <p class="text-gray-600 mb-6 font-roboto text-center">
        Buat akun untuk mulai membangun portofolio
      </p>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">
            {{ form.role === 'perusahaan' ? 'Nama Perusahaan' : 'Nama Lengkap' }}
          </label>

          <input
            v-model="form.name"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500"
            :placeholder="form.role === 'perusahaan' ? 'Masukkan nama perusahaan' : 'Masukkan nama lengkap'"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            minlength="8"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Daftar Sebagai</label>
          <select
            v-model="form.role"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500"
          >
            <option value="">Pilih Role</option>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="perusahaan">Perusahaan</option>
          </select>
        </div>

        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-xl transition shadow-md disabled:opacity-50"
        >
          {{ loading ? 'Mendaftar...' : 'Daftar' }}
        </button>
      </form>

      <p class="mt-6 text-sm text-gray-600 text-center">
        Sudah punya akun?
        <router-link to="/login" class="text-purple-700 hover:underline font-semibold">
          Login sekarang
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
defineOptions({ name: 'RegisterPage' })

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: ''
})

const handleRegister = async () => {
  loading.value = true
  error.value = ''

  try {
    const res = await axios.post('/api/register', form.value)

    const token = res.data.token
    const user = res.data.user

    localStorage.setItem('token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    if (user.role === 'mahasiswa') router.push('/profile/mahasiswa')
    else if (user.role === 'perusahaan') router.push('/dashboard/perusahaan')
    else router.push('/dashboard')
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).flat().join(', ')
    } else {
      error.value = err.response?.data?.message || 'Registrasi gagal.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');

.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }
</style>
