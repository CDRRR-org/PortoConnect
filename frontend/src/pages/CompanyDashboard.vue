<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-purple-700">Dashboard Perusahaan</h1>
        <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
          Logout
        </button>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Search Form -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Cari Mahasiswa</h2>
        <form @submit.prevent="searchStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
            <input v-model="searchForm.keyword" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Nama, NIM, dll" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Skill</label>
            <input v-model="searchForm.skill" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="JavaScript, Python, dll" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
            <input v-model="searchForm.jurusan" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Teknik Informatika" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
            <input v-model="searchForm.fakultas" type="text" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
            <input v-model="searchForm.universitas" type="text" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div class="flex items-end">
            <button type="submit" class="w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Cari
            </button>
          </div>
        </form>
      </div>

      <!-- Results -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Hasil Pencarian ({{ results.length }} hasil)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="student in results" :key="student.id" class="border rounded-lg p-4 hover:shadow-lg transition">
            <h3 class="font-bold text-lg">{{ student.user?.name }}</h3>
            <p class="text-sm text-gray-600">{{ student.nim }}</p>
            <p class="text-sm text-gray-600">{{ student.jurusan }} - {{ student.fakultas }}</p>
            <div class="mt-2">
              <p class="text-sm font-medium">Skills:</p>
              <div class="flex flex-wrap gap-1 mt-1">
                <span v-for="skill in student.skills?.slice(0, 3)" :key="skill.id" class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                  {{ skill.nama }}
                </span>
              </div>
            </div>
            <button
              @click="viewPortfolio(student)"
              class="mt-4 w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800"
            >
              Lihat Portofolio
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const results = ref([])

const searchForm = ref({
  keyword: '',
  skill: '',
  jurusan: '',
  fakultas: '',
  universitas: ''
})

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
  await searchStudents()
})

const searchStudents = async () => {
  try {
    const res = await axios.get('/api/company/search', { params: searchForm.value })
    results.value = res.data.data || res.data
  } catch (error) {
    console.error('Error searching students:', error)
  }
}

const viewPortfolio = (student) => {
  if (student.portofolio?.public_link) {
    window.open(`/portfolio/${student.portofolio.public_link}`, '_blank')
  } else {
    alert('Portofolio tidak tersedia')
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('token')
    router.push('/login')
  } catch (error) {
    localStorage.removeItem('token')
    router.push('/login')
  }
}
</script>

