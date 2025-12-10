<template>
  <div class="min-h-screen dashboard-gradient">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-purple-700">Dashboard Admin</h1>
        <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
          Logout
        </button>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'px-6 py-3 text-sm font-medium border-b-2',
                activeTab === tab.id
                  ? 'border-purple-500 text-purple-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              {{ tab.label }}
            </button>
          </nav>
        </div>
      </div>

      <!-- Home Tab -->
      <div v-if="activeTab === 'home'">
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

      <!-- Portfolios Tab -->
      <div v-if="activeTab === 'portfolios'">
        <div class="flex gap-6">
          <!-- Sidebar Kategori -->
          <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit sticky top-4">
            <h3 class="font-bold text-gray-800 mb-4">Kategori Bidang</h3>
            <ul class="space-y-2">
              <li>
                <button
                  @click="filterByBidang(null)"
                  :class="selectedBidang === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                  class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Semua Bidang
                  <span class="ml-auto text-sm text-gray-500">({{ totalPortfolios }})</span>
                </button>
              </li>
              <li>
                <button
                  @click="filterByBidang('backend')"
                  :class="selectedBidang === 'backend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                  class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Backend
                  <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('backend') }})</span>
                </button>
              </li>
              <li>
                <button
                  @click="filterByBidang('frontend')"
                  :class="selectedBidang === 'frontend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                  class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Frontend
                  <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('frontend') }})</span>
                </button>
              </li>
              <li>
                <button
                  @click="filterByBidang('fullstack')"
                  :class="selectedBidang === 'fullstack' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                  class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Fullstack
                  <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('fullstack') }})</span>
                </button>
              </li>
              <li>
                <button
                  @click="filterByBidang('QATester')"
                  :class="selectedBidang === 'QATester' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                  class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  QA Tester
                  <span class="ml-auto text-sm text-gray-500">({{ getCountByBidang('QATester') }})</span>
                </button>
              </li>
            </ul>
          </aside>

          <!-- Main Content -->
          <main class="flex-1">
            <h2 class="text-3xl font-bold text-white mb-6">Manajemen Portofolio</h2>
            
            <!-- Portfolio Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="portfolio in filteredPortfolios"
                :key="portfolio.id"
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow transform hover:scale-105 relative"
              >
                <!-- Delete Button -->
                <button
                  @click.stop="confirmDelete(portfolio)"
                  class="absolute top-2 right-2 z-10 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg"
                  title="Hapus Portofolio"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>

                <!-- Header dengan gradient background -->
                <div class="h-32 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 relative">
                  <div class="absolute inset-0 bg-black/20"></div>
                  <div class="absolute bottom-4 left-4 right-4">
                    <h3 class="text-white font-bold text-lg">{{ portfolio.nama || 'Portofolio' }}</h3>
                  </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                  <div class="flex items-start gap-3 mb-3">
                    <!-- Profile Picture -->
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                      {{ portfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="font-bold text-gray-800 truncate">{{ portfolio.mahasiswa?.user?.name }}</h4>
                      <p class="text-sm text-gray-600">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</p>
                    </div>
                    <!-- Public Icon -->
                    <svg v-if="portfolio.is_public" class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                  </div>

                  <!-- Bidang Badge -->
                  <div v-if="portfolio.bidang" class="mb-3">
                    <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                      {{ portfolio.bidang }}
                    </span>
                  </div>

                  <!-- Deskripsi Singkat -->
                  <p v-if="portfolio.deskripsi" class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ portfolio.deskripsi }}
                  </p>

                  <!-- Skills -->
                  <div v-if="portfolio.skills && portfolio.skills.length > 0" class="flex flex-wrap gap-2">
                    <span
                      v-for="skill in portfolio.skills.slice(0, 3)"
                      :key="skill.id"
                      class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
                    >
                      {{ skill.nama }}
                    </span>
                    <span v-if="portfolio.skills.length > 3" class="text-xs text-gray-500">
                      +{{ portfolio.skills.length - 3 }} lainnya
                    </span>
                  </div>

                  <!-- View Button -->
                  <button
                    @click="viewPortfolio(portfolio)"
                    class="mt-4 w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 transition-colors"
                  >
                    Lihat Portofolio
                  </button>
                </div>
              </div>
            </div>

            <div v-if="filteredPortfolios.length === 0" class="text-center text-gray-500 py-12">
              <p>Tidak ada portofolio yang ditemukan</p>
            </div>
          </main>
        </div>
      </div>
    </div>

    <!-- Modal Create User -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Tambah User Baru</h3>
        
        <form @submit.prevent="createUser()">
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
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Minimal 8 karakter"
              />
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
              {{ loading ? 'Memproses...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4 text-red-600">Konfirmasi Hapus Portofolio</h3>
        <p class="text-gray-700 mb-4">
          Apakah Anda yakin ingin menghapus portofolio <strong>"{{ portfolioToDelete?.nama || 'Portofolio' }}"</strong> milik <strong>{{ portfolioToDelete?.mahasiswa?.user?.name || 'Mahasiswa' }}</strong>?
        </p>
        <p class="text-sm text-gray-500 mb-6">
          Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-2">
          <button
            @click="showDeleteModal = false"
            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
          >
            Batal
          </button>
          <button
            @click="deletePortfolio"
            :disabled="deleting"
            class="flex-1 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 disabled:opacity-50"
          >
            {{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Portfolio Detail Modal -->
    <div v-if="showPortfolioModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
      <div class="bg-white rounded-lg p-6 w-full max-w-4xl my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-2xl font-bold text-gray-800">Detail Portofolio</h3>
          <button
            @click="showPortfolioModal = false"
            class="text-gray-500 hover:text-gray-700"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="selectedPortfolio" class="space-y-6">
          <!-- Portfolio Header -->
          <div class="bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 rounded-lg p-6 text-white">
            <h2 class="text-3xl font-bold mb-2">{{ selectedPortfolio.nama || 'Portofolio' }}</h2>
            <p v-if="selectedPortfolio.bidang" class="text-lg opacity-90">{{ selectedPortfolio.bidang }}</p>
          </div>

          <!-- Student Info -->
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
              {{ selectedPortfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div>
              <h4 class="font-bold text-gray-800 text-lg">{{ selectedPortfolio.mahasiswa?.user?.name }}</h4>
              <p class="text-gray-600">{{ selectedPortfolio.mahasiswa?.universitas || '-' }}</p>
              <p class="text-gray-600">{{ selectedPortfolio.mahasiswa?.jurusan || '-' }}</p>
            </div>
          </div>

          <!-- Description -->
          <div v-if="selectedPortfolio.deskripsi">
            <h4 class="font-bold text-gray-800 mb-2">Deskripsi</h4>
            <p class="text-gray-700 whitespace-pre-line">{{ selectedPortfolio.deskripsi }}</p>
          </div>

          <!-- Skills -->
          <div v-if="selectedPortfolio.skills && selectedPortfolio.skills.length > 0">
            <h4 class="font-bold text-gray-800 mb-3">Skills</h4>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="skill in selectedPortfolio.skills"
                :key="skill.id"
                class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ skill.nama }} ({{ skill.level }})
              </span>
            </div>
          </div>

          <!-- Public Link -->
          <div v-if="selectedPortfolio.is_public && selectedPortfolio.public_link" class="p-4 bg-gray-50 rounded-lg">
            <h4 class="font-bold text-gray-800 mb-2">Link Publik</h4>
            <div class="flex gap-2">
              <input
                :value="getPublicUrl(selectedPortfolio.public_link)"
                readonly
                class="flex-1 border rounded-lg px-3 py-2 bg-white text-sm"
              />
              <button
                @click="copyToClipboard(getPublicUrl(selectedPortfolio.public_link))"
                class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 text-sm"
              >
                Salin Link
              </button>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2 pt-4 border-t">
            <button
              @click="showPortfolioModal = false"
              class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Kembali
            </button>
            <button
              v-if="selectedPortfolio.public_link"
              @click="window.open(`/portfolio/${selectedPortfolio.public_link}`, '_blank')"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800"
            >
              Buka di Tab Baru
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const users = ref([])
const stats = ref({})
const searchQuery = ref('')
const roleFilter = ref('')
const showCreateModal = ref(false)
const loading = ref(false)
const activeTab = ref('home')
const portfolios = ref([])
const allPortfolios = ref([])
const selectedBidang = ref(null)
const showDeleteModal = ref(false)
const portfolioToDelete = ref(null)
const deleting = ref(false)
const showPortfolioModal = ref(false)
const selectedPortfolio = ref(null)

const userForm = ref({
  id: null,
  name: '',
  email: '',
  password: '',
  role: ''
})

const tabs = [
  { id: 'home', label: 'Home' },
  { id: 'portfolios', label: 'Portofolios' }
]

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  
  // Validasi role - pastikan user adalah admin
  try {
    const response = await axios.get('/api/me')
    const user = response.data?.user
    
    if (!user || user.role !== 'admin') {
      // Redirect berdasarkan role
      if (user?.role === 'mahasiswa') {
        router.push('/profile/mahasiswa')
      } else if (user?.role === 'perusahaan') {
        router.push('/dashboard/perusahaan')
      } else {
        router.push('/dashboard')
      }
      return
    }
    
    // Jika user adalah admin, lanjutkan load data
    await loadStats()
    await loadUsers()
    await loadPortfolios()
  } catch (error) {
    console.error('Error validating admin access:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      alert('Gagal memuat data. Silakan refresh halaman.')
    }
  }
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

const loadPortfolios = async (bidang = null) => {
  try {
    const params = bidang ? { bidang } : {}
    const response = await axios.get('/api/admin/portfolios', { params })
    portfolios.value = response.data.portfolios || []
    
    // Load all portfolios for counting if not already loaded
    if (allPortfolios.value.length === 0) {
      const allResponse = await axios.get('/api/admin/portfolios')
      allPortfolios.value = allResponse.data.portfolios || []
    }
  } catch (error) {
    console.error('Error loading portfolios:', error)
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => {
  return portfolios.value
})

const totalPortfolios = computed(() => {
  return allPortfolios.value.length
})

const getCountByBidang = (bidang) => {
  return allPortfolios.value.filter(p => p.bidang === bidang).length
}

const viewPortfolio = (portfolio) => {
  selectedPortfolio.value = portfolio
  showPortfolioModal.value = true
}

const confirmDelete = (portfolio) => {
  portfolioToDelete.value = portfolio
  showDeleteModal.value = true
}

const deletePortfolio = async () => {
  if (!portfolioToDelete.value) return
  
  deleting.value = true
  try {
    await axios.delete(`/api/admin/portfolios/${portfolioToDelete.value.id}`)
    alert('Portofolio berhasil dihapus')
    showDeleteModal.value = false
    portfolioToDelete.value = null
    await loadPortfolios(selectedBidang.value)
    // Reload all portfolios for count
    const allResponse = await axios.get('/api/admin/portfolios')
    allPortfolios.value = allResponse.data.portfolios || []
  } catch (error) {
    alert('Gagal menghapus portofolio: ' + (error.response?.data?.message || 'Unknown error'))
  } finally {
    deleting.value = false
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
  userForm.value = {
    id: null,
    name: '',
    email: '',
    password: '',
    role: ''
  }
}

const getPublicUrl = (publicLink) => {
  return `${window.location.origin}/portfolio/${publicLink}`
}

const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text)
    alert('Link berhasil disalin ke clipboard!')
  } catch (error) {
    console.error('Failed to copy:', error)
    alert('Gagal menyalin link')
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

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
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
