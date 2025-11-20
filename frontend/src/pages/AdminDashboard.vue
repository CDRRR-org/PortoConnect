<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-purple-700">Dashboard Admin</h1>
        <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
          Logout
        </button>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-gray-600 text-sm">Total Users</h3>
          <p class="text-2xl font-bold">{{ stats.total_users || 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-gray-600 text-sm">Mahasiswa</h3>
          <p class="text-2xl font-bold">{{ stats.total_mahasiswa || 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-gray-600 text-sm">Perusahaan</h3>
          <p class="text-2xl font-bold">{{ stats.total_perusahaan || 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-gray-600 text-sm">Admin</h3>
          <p class="text-2xl font-bold">{{ stats.total_admin || 0 }}</p>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
          <h2 class="text-xl font-bold">Manajemen Users</h2>
          <div class="flex gap-2">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari user..."
              class="border rounded-lg px-3 py-2"
            />
            <select v-model="roleFilter" class="border rounded-lg px-3 py-2">
              <option value="">Semua Role</option>
              <option value="mahasiswa">Mahasiswa</option>
              <option value="perusahaan">Perusahaan</option>
              <option value="admin">Admin</option>
            </select>
            <button @click="loadUsers" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Cari
            </button>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="user in users" :key="user.id">
                <td class="px-4 py-3">{{ user.name }}</td>
                <td class="px-4 py-3">{{ user.email }}</td>
                <td class="px-4 py-3">{{ user.role }}</td>
                <td class="px-4 py-3">
                  <span :class="user.email_verified_at ? 'text-green-600' : 'text-red-600'">
                    {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex gap-2">
                    <button @click="verifyUser(user.id)" class="text-blue-600 text-sm hover:underline">
                      Verify
                    </button>
                    <button @click="deleteUser(user.id)" class="text-red-600 text-sm hover:underline">
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
const users = ref([])
const stats = ref({})
const searchQuery = ref('')
const roleFilter = ref('')

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
  await loadStats()
  await loadUsers()
})

const loadStats = async () => {
  try {
    const res = await axios.get('/api/admin/dashboard-stats')
    stats.value = res.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const loadUsers = async () => {
  try {
    const params = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (roleFilter.value) params.role = roleFilter.value
    
    const res = await axios.get('/api/admin/users', { params })
    users.value = res.data.data || res.data
  } catch (error) {
    console.error('Error loading users:', error)
  }
}

const verifyUser = async (id) => {
  try {
    await axios.post(`/api/admin/users/${id}/verify`)
    alert('User berhasil diverifikasi')
    await loadUsers()
  } catch (error) {
    alert('Gagal memverifikasi user')
  }
}

const deleteUser = async (id) => {
  if (!confirm('Yakin ingin menghapus user ini?')) return
  try {
    await axios.delete(`/api/admin/users/${id}`)
    alert('User berhasil dihapus')
    await loadUsers()
  } catch (error) {
    alert('Gagal menghapus user')
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

