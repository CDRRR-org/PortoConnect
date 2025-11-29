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
            <button @click="showCreateModal = true" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
              + Tambah User
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
                    <button @click="editUser(user)" class="text-yellow-600 text-sm hover:underline">
                      Edit
                    </button>
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

    <!-- Modal Create/Edit User -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">{{ showEditModal ? 'Edit User' : 'Tambah User Baru' }}</h3>
        
        <form @submit.prevent="showEditModal ? updateUserData() : createUser()">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
              <input
                v-model="userForm.name"
                type="text"
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Nama lengkap"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input
                v-model="userForm.email"
                type="email"
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="email@example.com"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input
                v-model="userForm.password"
                type="password"
                :required="!showEditModal"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Minimal 8 karakter"
              />
              <p v-if="showEditModal" class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
              <select v-model="userForm.role" required class="w-full border rounded-lg px-3 py-2">
                <option value="">Pilih Role</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="perusahaan">Perusahaan</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          
          <div class="flex gap-2 mt-6">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 disabled:opacity-50"
            >
              {{ loading ? 'Memproses...' : (showEditModal ? 'Update' : 'Simpan') }}
            </button>
          </div>
        </form>
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
const showCreateModal = ref(false)
const showEditModal = ref(false)
const loading = ref(false)
const userForm = ref({
  id: null,
  name: '',
  email: '',
  password: '',
  role: ''
})

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

const createUser = async () => {
  loading.value = true
  try {
    await axios.post('/api/admin/users', {
      name: userForm.value.name,
      email: userForm.value.email,
      password: userForm.value.password,
      role: userForm.value.role
    })
    alert('User berhasil dibuat')
    closeModal()
    await loadUsers()
    await loadStats()
  } catch (error) {
    const message = error.response?.data?.message || 'Gagal membuat user'
    const errors = error.response?.data?.errors
    if (errors) {
      alert(message + ': ' + Object.values(errors).flat().join(', '))
    } else {
      alert(message)
    }
  } finally {
    loading.value = false
  }
}

const editUser = (user) => {
  userForm.value = {
    id: user.id,
    name: user.name,
    email: user.email,
    password: '',
    role: user.role
  }
  showEditModal.value = true
}

const updateUserData = async () => {
  loading.value = true
  try {
    const data = {
      name: userForm.value.name,
      email: userForm.value.email,
      role: userForm.value.role
    }
    if (userForm.value.password) {
      data.password = userForm.value.password
    }
    
    await axios.put(`/api/admin/users/${userForm.value.id}`, data)
    alert('User berhasil diperbarui')
    closeModal()
    await loadUsers()
    await loadStats()
  } catch (error) {
    const message = error.response?.data?.message || 'Gagal memperbarui user'
    const errors = error.response?.data?.errors
    if (errors) {
      alert(message + ': ' + Object.values(errors).flat().join(', '))
    } else {
      alert(message)
    }
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id) => {
  if (!confirm('Yakin ingin menghapus user ini?')) return
  try {
    await axios.delete(`/api/admin/users/${id}`)
    alert('User berhasil dihapus')
    await loadUsers()
    await loadStats()
  } catch (error) {
    alert('Gagal menghapus user')
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  userForm.value = {
    id: null,
    name: '',
    email: '',
    password: '',
    role: ''
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

