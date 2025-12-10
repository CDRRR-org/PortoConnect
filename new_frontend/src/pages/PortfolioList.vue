<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button 
            @click="goToProfile" 
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Profil
          </button>
          <h1 class="text-2xl font-bold text-purple-700">Daftar Portofolio</h1>
        </div>
        <div class="flex gap-4 items-center">
          <button @click="showEditProfileModal = true" class="text-purple-700 hover:text-purple-900">
            Edit Profil
          </button>
          <span class="text-gray-600">{{ user?.name }}</span>
          <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Logout
          </button>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Add Portfolio Button -->
      <div class="mb-6 flex justify-end">
        <button @click="goToCreatePortfolio" class="bg-white border-2 border-purple-700 text-purple-700 px-6 py-3 rounded-lg hover:bg-purple-50 font-semibold">
          + Buat Portofolio Baru
        </button>
      </div>

      <!-- Portfolio List -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="portfolio in paginatedPortfolios"
          :key="portfolio.id"
          class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition"
          @click="viewPortfolio(portfolio)"
        >
          <h3 class="text-xl font-bold text-gray-800 mb-2">{{ portfolio.nama || 'Portofolio' }}</h3>
          <p class="text-sm text-gray-600 mb-2">
            <span v-if="portfolio.bidang" class="inline-block bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs mr-2">
              {{ portfolio.bidang }}
            </span>
            <span :class="portfolio.is_public ? 'text-green-600' : 'text-gray-500'">
              {{ portfolio.is_public ? 'Public' : 'Private' }}
            </span>
          </p>
          
          <!-- Public Link -->
          <div v-if="portfolio.is_public && portfolio.public_link" class="mb-4 p-2 bg-gray-50 rounded border">
            <label class="block text-xs font-medium text-gray-600 mb-1">Link Publik:</label>
            <div class="flex gap-1">
              <input 
                :value="getPublicUrl(portfolio.public_link)" 
                readonly 
                class="flex-1 border rounded px-2 py-1 text-xs bg-white"
              />
              <button 
                @click.stop="copyLink(portfolio.public_link)"
                class="bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300 text-xs"
              >
                Salin
              </button>
            </div>
          </div>
          
          <div class="flex gap-2">
            <button
              @click.stop="viewPortfolio(portfolio)"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 text-sm"
            >
              Lihat
            </button>
            <button
              @click.stop="deletePortfolio(portfolio.id)"
              class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>

      <div v-if="portfolios.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">Belum ada portofolio. Buat portofolio pertama Anda!</p>
      </div>

      <!-- Pagination -->
      <div v-if="portfolios.length > 10" class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
          Menampilkan {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ Math.min(currentPage * itemsPerPage, portfolios.length) }} dari {{ portfolios.length }} portofolio
        </div>
        <div class="flex gap-2">
          <button 
            @click="currentPage = Math.max(1, currentPage - 1)"
            :disabled="currentPage === 1"
            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
            class="px-4 py-2 border rounded-lg bg-white"
          >
            Sebelumnya
          </button>
          <span class="px-4 py-2 text-gray-700 bg-white border rounded-lg">
            Halaman {{ currentPage }} dari {{ totalPages }}
          </span>
          <button 
            @click="currentPage = Math.min(totalPages, currentPage + 1)"
            :disabled="currentPage === totalPages"
            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
            class="px-4 py-2 border rounded-lg bg-white"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div v-if="showEditProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl my-8 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Edit Profil</h3>
        <form @submit.prevent="updateProfile">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
              <input
                v-model="profileForm.name"
                type="text"
                required
                class="w-full border rounded-lg px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
              <input
                v-model="profileForm.email"
                type="email"
                required
                class="w-full border rounded-lg px-3 py-2"
              />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                <input
                  v-model="profileForm.nim"
                  type="text"
                  required
                  class="w-full border rounded-lg px-3 py-2"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                <input
                  v-model="profileForm.no_telp"
                  type="text"
                  required
                  class="w-full border rounded-lg px-3 py-2"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Universitas <span class="text-red-500">*</span></label>
              <input
                v-model="profileForm.universitas"
                type="text"
                required
                class="w-full border rounded-lg px-3 py-2"
              />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas <span class="text-red-500">*</span></label>
                <input
                  v-model="profileForm.fakultas"
                  type="text"
                  required
                  class="w-full border rounded-lg px-3 py-2"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                <input
                  v-model="profileForm.jurusan"
                  type="text"
                  required
                  class="w-full border rounded-lg px-3 py-2"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
              <input
                v-model="profileForm.tanggal_lahir"
                type="date"
                required
                class="w-full border rounded-lg px-3 py-2"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
              <textarea
                v-model="profileForm.alamat"
                rows="3"
                required
                class="w-full border rounded-lg px-3 py-2"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Bio (Deskripsi Diri) <span class="text-red-500">*</span></label>
              <textarea
                v-model="profileForm.deskripsi_diri"
                rows="4"
                required
                class="w-full border rounded-lg px-3 py-2"
              ></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                <input
                  v-model="profileForm.linkedin"
                  type="url"
                  class="w-full border rounded-lg px-3 py-2"
                  placeholder="https://linkedin.com/in/..."
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GitHub</label>
                <input
                  v-model="profileForm.github"
                  type="url"
                  class="w-full border rounded-lg px-3 py-2"
                  placeholder="https://github.com/..."
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input
                  v-model="profileForm.website"
                  type="url"
                  class="w-full border rounded-lg px-3 py-2"
                  placeholder="https://..."
                />
              </div>
            </div>
          </div>
          <div class="flex gap-2 mt-6">
            <button
              type="button"
              @click="showEditProfileModal = false"
              class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 disabled:opacity-50"
            >
              {{ loading ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useSweetAlert } from '@/composables/useSweetAlert'

const { showSuccess, showError, showWarning, showInfo, showConfirm } = useSweetAlert()

const router = useRouter()
const route = useRoute()
const user = ref(null)
const portfolios = ref([])
const currentPage = ref(1)
const itemsPerPage = 10

// Pagination computed
const totalPages = computed(() => {
  return Math.ceil(portfolios.value.length / itemsPerPage)
})

const paginatedPortfolios = computed(() => {
  if (portfolios.value.length <= itemsPerPage) {
    return portfolios.value
  }
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return portfolios.value.slice(start, end)
})
const showEditProfileModal = ref(false)
const loading = ref(false)

const profileForm = ref({
  name: '',
  email: '',
  no_telp: '',
  alamat: '',
  deskripsi_diri: '',
  nim: '',
  jurusan: '',
  fakultas: '',
  universitas: '',
  tanggal_lahir: '',
  linkedin: '',
  github: '',
  website: ''
})

onMounted(async () => {
  await loadData()
})

const loadData = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }
    
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    // Validasi role - pastikan user adalah mahasiswa
    const userRes = await axios.get('/api/me')
    const userRole = userRes.data?.user?.role
    
    if (userRole !== 'mahasiswa') {
      // Redirect berdasarkan role
      if (userRole === 'admin') {
        router.push('/dashboard/admin')
      } else if (userRole === 'perusahaan') {
        router.push('/dashboard/perusahaan')
      } else {
        router.push('/dashboard')
      }
      return
    }
    
    user.value = userRes.data.user

    // Load profile
    const profileRes = await axios.get('/api/mahasiswa/profile')
    const mahasiswa = profileRes.data.mahasiswa
    profileForm.value = {
      name: user.value.name,
      email: user.value.email,
      no_telp: mahasiswa.no_telp || '',
      alamat: mahasiswa.alamat || '', // Load alamat dari response
      deskripsi_diri: mahasiswa.deskripsi_diri || '',
      nim: mahasiswa.nim || '',
      jurusan: mahasiswa.jurusan || '',
      fakultas: mahasiswa.fakultas || '',
      universitas: mahasiswa.universitas || '',
      tanggal_lahir: mahasiswa.tanggal_lahir ? mahasiswa.tanggal_lahir.split('T')[0] : '',
      linkedin: mahasiswa.linkedin || '',
      github: mahasiswa.github || '',
      website: mahasiswa.website || ''
    }

    // Load portfolios
    await loadPortfolios()
  } catch (error) {
    console.error('Error loading data:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      showError('Gagal memuat data. Silakan refresh halaman.')
    }
  }
}

const loadPortfolios = async () => {
  try {
    const res = await axios.get('/api/mahasiswa/portfolios')
    console.log('Portfolio response:', res.data) // Debug log
    portfolios.value = res.data.portofolios || res.data.portfolios || []
    // Reset to first page when loading new data
    currentPage.value = 1
  } catch (error) {
    console.error('Error loading portfolios:', error)
    if (error.response?.status === 401) {
      router.push('/login')
    } else {
      showError('Gagal memuat portofolio: ' + (error.response?.data?.message || 'Unknown error'))
    }
  }
}

const viewPortfolio = (portfolio) => {
  // Jika portofolio public dan punya public_link, arahkan ke halaman public
  if (portfolio.is_public && portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  } else {
    // Jika belum public, arahkan ke preview (untuk owner)
    router.push(`/portfolio/preview/${portfolio.id || portfolio}`)
  }
}

const deletePortfolio = async (id) => {
  const result = await showConfirm('Yakin ingin menghapus portofolio ini?', 'Ya, Hapus', 'Batal')
  if (!result.isConfirmed) return
  
  try {
    await axios.delete(`/api/mahasiswa/portfolios/${id}`)
    showSuccess('Portofolio berhasil dihapus')
    await loadPortfolios()
  } catch (error) {
    showError('Gagal menghapus portofolio')
  }
}

const updateProfile = async () => {
  // Validasi field wajib diisi
  const requiredFields = {
    'Nama': profileForm.value.name,
    'Email': profileForm.value.email,
    'NIM': profileForm.value.nim,
    'No. Telepon': profileForm.value.no_telp,
    'Universitas': profileForm.value.universitas,
    'Fakultas': profileForm.value.fakultas,
    'Jurusan': profileForm.value.jurusan,
    'Tanggal Lahir': profileForm.value.tanggal_lahir,
    'Alamat': profileForm.value.alamat,
    'Bio (Deskripsi Diri)': profileForm.value.deskripsi_diri
  }

  // Cek field yang kosong
  const emptyFields = []
  for (const [fieldName, fieldValue] of Object.entries(requiredFields)) {
    if (!fieldValue || (typeof fieldValue === 'string' && fieldValue.trim() === '')) {
      emptyFields.push(fieldName)
    }
  }

  // Tampilkan alert jika ada field yang kosong
  if (emptyFields.length > 0) {
    showWarning('Mohon lengkapi semua field yang wajib diisi:\n\n' + emptyFields.join('\n'))
    return
  }

  loading.value = true
  try {
    await axios.put('/api/mahasiswa/profile', profileForm.value)
    showSuccess('Profil berhasil diperbarui')
    showEditProfileModal.value = false
    await loadData()
  } catch (error) {
    showError('Gagal memperbarui profil: ' + (error.response?.data?.message || 'Unknown error'))
  } finally {
    loading.value = false
  }
}

const goToProfile = () => {
  router.push('/profile/mahasiswa')
}

const getPublicUrl = (publicLink) => {
  return `${window.location.origin}/portfolio/${publicLink}`
}

const copyLink = (publicLink) => {
  const url = getPublicUrl(publicLink)
  navigator.clipboard.writeText(url)
  showSuccess('Link berhasil disalin ke clipboard!')
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

const goToCreatePortfolio = () => {
  // Langsung redirect ke halaman preview dengan ID "new"
  router.push('/portfolio/preview/new')
}
</script>

