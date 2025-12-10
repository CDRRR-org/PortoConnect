<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-purple-700">{{ portfolio?.nama || 'Portofolio' }}</h1>
        <div class="flex gap-4 items-center">
          <button @click="togglePublic" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            {{ portfolio?.is_public ? 'Set Private' : 'Show Public' }}
          </button>
          <button @click="downloadPDF" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Download PDF
          </button>
          <button @click="goBack" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            Kembali
          </button>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" v-if="portfolio">
      <!-- Portfolio Info (Status & Link Publik) -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div>
          <label class="text-sm font-medium text-gray-500">Status</label>
          <p :class="portfolio.is_public ? 'text-green-600' : 'text-gray-600'">
            {{ portfolio.is_public ? 'Public' : 'Private' }}
          </p>
        </div>
        
        <!-- Public Link Section -->
        <div v-if="portfolio.is_public && portfolio.public_link" class="mt-4 pt-4 border-t">
          <label class="block text-sm font-medium text-gray-700 mb-2">Link Publik</label>
          <div class="flex gap-2">
            <input 
              :value="publicUrl" 
              readonly 
              class="flex-1 border rounded-lg px-3 py-2 bg-gray-50 text-sm"
            />
            <button 
              @click="copyPublicLink" 
              class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm"
            >
              Salin
            </button>
            <button 
              @click="regenerateLink" 
              class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-sm"
            >
              Generate Baru
            </button>
          </div>
          <p class="text-xs text-gray-500 mt-2">Bagikan link ini untuk memberikan akses ke portofolio Anda</p>
        </div>
        <div v-else-if="portfolio.is_public && !portfolio.public_link" class="mt-4 pt-4 border-t">
          <p class="text-sm text-gray-600 mb-2">Portofolio sudah public, tapi belum ada link. Klik "Show Public" untuk generate link.</p>
        </div>
      </div>

      <!-- Detail Portofolio -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Detail Portofolio</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Portofolio</label>
            <input
              v-model="portfolioForm.nama"
              type="text"
              class="w-full border rounded-lg px-3 py-2"
              placeholder="Nama portofolio"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang</label>
            <select v-model="portfolioForm.bidang" class="w-full border rounded-lg px-3 py-2">
              <option value="">Pilih Bidang</option>
              <option value="backend">Backend</option>
              <option value="frontend">Frontend</option>
              <option value="fullstack">Fullstack</option>
              <option value="QATester">QA Tester</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Portofolio</label>
            <textarea
              v-model="portfolioForm.deskripsi"
              rows="3"
              class="w-full border rounded-lg px-3 py-2"
              placeholder="Deskripsi tentang portofolio ini..."
            ></textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Education</label>
              <textarea
                v-model="portfolioForm.education"
                rows="4"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Masukkan informasi pendidikan"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
              <textarea
                v-model="portfolioForm.language"
                rows="4"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Masukkan bahasa yang dikuasai"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Projects Section -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Projects</h2>
          <button @click="openProjectModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Project
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="project in portfolio.projects" :key="project.id" class="border rounded-lg p-4">
            <h3 class="font-bold">{{ project.judul }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ project.deskripsi }}</p>
            <div class="mt-4 flex gap-2">
              <button @click="openProjectModal(project)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteProject(project.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
        <div v-if="portfolio.projects?.length === 0" class="text-center py-8 text-gray-500">
          Belum ada project
        </div>
      </div>

      <!-- Skills Section -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Skills</h2>
          <button @click="openSkillModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Skill
          </button>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="skill in portfolio.skills" :key="skill.id" class="border rounded-lg p-4 text-center">
            <h3 class="font-bold">{{ skill.nama }}</h3>
            <p class="text-sm text-gray-600">{{ skill.level }}</p>
            <div class="mt-2 flex gap-2 justify-center">
              <button @click="openSkillModal(skill)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteSkill(skill.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
        <div v-if="portfolio.skills?.length === 0" class="text-center py-8 text-gray-500">
          Belum ada skill
        </div>
      </div>

      <!-- Certificates Section -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Certificates</h2>
          <button @click="openCertificateModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Certificate
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="cert in portfolio.certificates" :key="cert.id" class="border rounded-lg p-4">
            <h3 class="font-bold">{{ cert.nama }}</h3>
            <p class="text-sm text-gray-600">{{ cert.penerbit }}</p>
            <div class="mt-4 flex gap-2">
              <button @click="openCertificateModal(cert)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteCertificate(cert.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
        <div v-if="portfolio.certificates?.length === 0" class="text-center py-8 text-gray-500">
          Belum ada certificate
        </div>
      </div>

      <!-- Experiences Section -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Experiences</h2>
          <button @click="openExperienceModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Experience
          </button>
        </div>
        <div class="space-y-4">
          <div v-for="exp in portfolio.experiences" :key="exp.id" class="border rounded-lg p-4">
            <h3 class="font-bold">{{ exp.judul }}</h3>
            <p class="text-sm text-gray-600">{{ exp.perusahaan }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ exp.deskripsi }}</p>
            <div class="mt-4 flex gap-2">
              <button @click="openExperienceModal(exp)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteExperience(exp.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
        <div v-if="portfolio.experiences?.length === 0" class="text-center py-8 text-gray-500">
          Belum ada experience
        </div>
      </div>

      <!-- Simpan Perubahan Button -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-center">
          <button @click="updatePortfolio" :disabled="loading" class="bg-purple-700 text-white px-8 py-3 rounded-lg hover:bg-purple-800 font-semibold disabled:opacity-50">
            {{ loading ? 'Menyimpan...' : 'Simpan Perubahan' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Project Modal -->
    <div v-if="showProjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closeProjectModal">
      <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.stop>
        <h3 class="text-xl font-bold mb-4">{{ editingProject ? 'Edit' : 'Tambah' }} Project</h3>
        <form @submit.prevent="saveProject" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
            <input v-model="projectForm.judul" type="text" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea v-model="projectForm.deskripsi" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
            <input v-model="projectForm.link" type="url" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teknologi</label>
            <input v-model="projectForm.teknologi" type="text" placeholder="JavaScript, Vue.js, Laravel" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Simpan
            </button>
            <button type="button" @click="closeProjectModal" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Skill Modal -->
    <div v-if="showSkillModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closeSkillModal">
      <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.stop>
        <h3 class="text-xl font-bold mb-4">{{ editingSkill ? 'Edit' : 'Tambah' }} Skill</h3>
        <form @submit.prevent="saveSkill" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Skill</label>
            <input v-model="skillForm.nama" type="text" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
            <select v-model="skillForm.level" required class="w-full border rounded-lg px-3 py-2">
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
              <option value="expert">Expert</option>
            </select>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Simpan
            </button>
            <button type="button" @click="closeSkillModal" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Certificate Modal -->
    <div v-if="showCertificateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closeCertificateModal">
      <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.stop>
        <h3 class="text-xl font-bold mb-4">{{ editingCertificate ? 'Edit' : 'Tambah' }} Certificate</h3>
        <form @submit.prevent="saveCertificate" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input v-model="certificateForm.nama" type="text" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
            <input v-model="certificateForm.penerbit" type="text" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>
            <input v-model="certificateForm.tanggal_terbit" type="date" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Simpan
            </button>
            <button type="button" @click="closeCertificateModal" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Experience Modal -->
    <div v-if="showExperienceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closeExperienceModal">
      <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto" @click.stop>
        <h3 class="text-xl font-bold mb-4">{{ editingExperience ? 'Edit' : 'Tambah' }} Experience</h3>
        <form @submit.prevent="saveExperience" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
            <input v-model="experienceForm.judul" type="text" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
            <input v-model="experienceForm.perusahaan" type="text" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea v-model="experienceForm.deskripsi" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
            <select v-model="experienceForm.tipe" required class="w-full border rounded-lg px-3 py-2">
              <option value="kerja">Kerja</option>
              <option value="magang">Magang</option>
              <option value="organisasi">Organisasi</option>
              <option value="volunteer">Volunteer</option>
              <option value="lainnya">Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input v-model="experienceForm.tanggal_mulai" type="date" required class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
            <input v-model="experienceForm.tanggal_selesai" type="date" class="w-full border rounded-lg px-3 py-2" />
          </div>
          <div>
            <label class="flex items-center gap-2">
              <input v-model="experienceForm.masih_berlangsung" type="checkbox" class="rounded" />
              <span class="text-sm text-gray-700">Masih berlangsung</span>
            </label>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
              Simpan
            </button>
            <button type="button" @click="closeExperienceModal" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const portfolio = ref(null)
const loading = ref(false)
const autoSaveTimer = ref(null)
const isInitialLoad = ref(true)

const portfolioForm = ref({
  nama: '',
  bidang: '',
  education: '',
  language: '',
  deskripsi: ''
})

const showProjectModal = ref(false)
const showSkillModal = ref(false)
const showCertificateModal = ref(false)
const showExperienceModal = ref(false)
const editingProject = ref(null)
const editingSkill = ref(null)
const editingCertificate = ref(null)
const editingExperience = ref(null)

const projectForm = ref({
  judul: '',
  deskripsi: '',
  link: '',
  teknologi: ''
})

const skillForm = ref({
  nama: '',
  level: 'beginner'
})

const certificateForm = ref({
  nama: '',
  penerbit: '',
  tanggal_terbit: ''
})

const experienceForm = ref({
  judul: '',
  perusahaan: '',
  deskripsi: '',
  tipe: 'kerja',
  tanggal_mulai: '',
  tanggal_selesai: '',
  masih_berlangsung: false
})

onMounted(async () => {
  await loadPortfolio()
  // Set flag setelah load selesai
  setTimeout(() => {
    isInitialLoad.value = false
  }, 500)
})

const loadPortfolio = async (preserveFormData = false) => {
  try {
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const res = await axios.get(`/api/mahasiswa/portfolios/${route.params.id}`)
    portfolio.value = res.data.portofolio
    
    // Only update form if not preserving form data
    if (!preserveFormData) {
      portfolioForm.value = {
        nama: portfolio.value.nama || '',
        bidang: portfolio.value.bidang || '',
        education: portfolio.value.education || '',
        language: portfolio.value.language || '',
        deskripsi: portfolio.value.deskripsi || ''
      }
    } else {
      // Update portfolio object but keep form data
      if (portfolio.value) {
        // Merge form data with loaded data to preserve user input
        portfolio.value.nama = portfolioForm.value.nama || portfolio.value.nama || ''
        portfolio.value.bidang = portfolioForm.value.bidang || portfolio.value.bidang || ''
        portfolio.value.education = portfolioForm.value.education || portfolio.value.education || ''
        portfolio.value.language = portfolioForm.value.language || portfolio.value.language || ''
        portfolio.value.deskripsi = portfolioForm.value.deskripsi || portfolio.value.deskripsi || ''
      }
    }
  } catch (error) {
    console.error('Error loading portfolio:', error)
    if (error.response?.status === 401) {
      router.push('/login')
    }
  }
}

// Auto-save function dengan debounce
const autoSavePortfolio = async () => {
  if (isInitialLoad.value || !portfolio.value) return
  
  try {
    // Clear previous timer
    if (autoSaveTimer.value) {
      clearTimeout(autoSaveTimer.value)
    }
    
    // Set new timer (save after 1 second of no changes)
    autoSaveTimer.value = setTimeout(async () => {
      try {
        await axios.put(`/api/mahasiswa/portfolios/${route.params.id}`, portfolioForm.value)
        console.log('Auto-saved portfolio changes')
        // Update portfolio object to reflect changes
        if (portfolio.value) {
          portfolio.value.nama = portfolioForm.value.nama
          portfolio.value.bidang = portfolioForm.value.bidang
          portfolio.value.education = portfolioForm.value.education
          portfolio.value.language = portfolioForm.value.language
          portfolio.value.deskripsi = portfolioForm.value.deskripsi
        }
      } catch (error) {
        console.error('Error auto-saving portfolio:', error)
      }
    }, 1000) // Wait 1 second after last change
  } catch (error) {
    console.error('Error in auto-save:', error)
  }
}

// Watch portfolioForm changes for auto-save
watch(
  () => portfolioForm.value,
  () => {
    if (!isInitialLoad.value) {
      autoSavePortfolio()
    }
  },
  { deep: true }
)

const togglePublic = async () => {
  try {
    if (!portfolio.value.is_public) {
      // If setting to public, generate link first
      await axios.post(`/api/mahasiswa/portfolios/${route.params.id}/generate-link`)
      await loadPortfolio(true) // Preserve form data
      alert('Portofolio berhasil dijadikan public dan link telah dibuat')
    } else {
      // If setting to private, just update status
      await axios.put(`/api/mahasiswa/portfolios/${route.params.id}`, {
        is_public: false
      })
      portfolio.value.is_public = false
      alert('Portofolio berhasil dijadikan private')
    }
  } catch (error) {
    alert('Gagal mengubah status portofolio')
  }
}

const publicUrl = computed(() => {
  if (portfolio.value?.public_link) {
    return `${window.location.origin}/portfolio/${portfolio.value.public_link}`
  }
  return ''
})

const copyPublicLink = () => {
  if (publicUrl.value) {
    navigator.clipboard.writeText(publicUrl.value)
    alert('Link berhasil disalin ke clipboard!')
  }
}

const regenerateLink = async () => {
  try {
    await axios.post(`/api/mahasiswa/portfolios/${route.params.id}/generate-link`)
    await loadPortfolio(true) // Preserve form data
    alert('Link publik berhasil dibuat ulang')
  } catch (error) {
    alert('Gagal membuat link baru')
  }
}

const updatePortfolio = async () => {
  loading.value = true
  try {
    await axios.put(`/api/mahasiswa/portfolios/${route.params.id}`, portfolioForm.value)
    alert('Portofolio berhasil diperbarui')
    await loadPortfolio()
  } catch (error) {
    alert('Gagal memperbarui portofolio')
  } finally {
    loading.value = false
  }
}

const downloadPDF = async () => {
  try {
    const res = await axios.post(`/api/mahasiswa/export-pdf`, {
      portofolio_id: route.params.id
    }, {
      responseType: 'blob'
    })
    
    // Create blob URL and trigger download
    const blob = new Blob([res.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `portfolio_${portfolio.value?.nama || 'portfolio'}_${Date.now()}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
    
    alert('PDF berhasil diunduh')
  } catch (error) {
    alert('Gagal membuat PDF: ' + (error.response?.data?.message || 'Unknown error'))
  }
}

const goBack = () => {
  router.go(-1)
}

// Project functions
const openProjectModal = (project = null) => {
  editingProject.value = project
  if (project) {
    projectForm.value = { ...project }
  } else {
    projectForm.value = { judul: '', deskripsi: '', link: '', teknologi: '' }
  }
  showProjectModal.value = true
}

const closeProjectModal = () => {
  showProjectModal.value = false
  editingProject.value = null
}

const saveProject = async () => {
  try {
    const data = { 
      ...projectForm.value, 
      portofolio_id: parseInt(route.params.id)
    }
    
    // Remove empty link if not a valid URL
    if (data.link && !data.link.startsWith('http://') && !data.link.startsWith('https://')) {
      // If link doesn't start with http/https, prepend https://
      if (data.link.trim() !== '') {
        data.link = 'https://' + data.link
      }
    }
    
    // Remove empty fields
    if (!data.link || data.link.trim() === '') {
      delete data.link
    }
    
    if (editingProject.value) {
      await axios.put(`/api/mahasiswa/projects/${editingProject.value.id}`, data)
      alert('Project berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/projects', data)
      alert('Project berhasil ditambahkan')
    }
                closeProjectModal()
                await loadPortfolio(true) // Preserve form data
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'Gagal menyimpan project'
    const errors = error.response?.data?.errors
    if (errors) {
      const errorList = Object.values(errors).flat().join(', ')
      alert(`${errorMessage}: ${errorList}`)
    } else {
      alert(errorMessage)
    }
    console.error('Error saving project:', error)
  }
}

const deleteProject = async (id) => {
  if (!confirm('Yakin ingin menghapus project ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/projects/${id}`)
    await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menghapus project')
  }
}

// Skill functions
const openSkillModal = (skill = null) => {
  editingSkill.value = skill
  if (skill) {
    skillForm.value = { ...skill }
  } else {
    skillForm.value = { nama: '', level: 'beginner' }
  }
  showSkillModal.value = true
}

const closeSkillModal = () => {
  showSkillModal.value = false
  editingSkill.value = null
}

const saveSkill = async () => {
  try {
    const data = { ...skillForm.value, portofolio_id: route.params.id }
    if (editingSkill.value) {
      await axios.put(`/api/mahasiswa/skills/${editingSkill.value.id}`, data)
      alert('Skill berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/skills', data)
      alert('Skill berhasil ditambahkan')
    }
                closeSkillModal()
                await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menyimpan skill')
  }
}

const deleteSkill = async (id) => {
  if (!confirm('Yakin ingin menghapus skill ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/skills/${id}`)
    await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menghapus skill')
  }
}

// Certificate functions
const openCertificateModal = (cert = null) => {
  editingCertificate.value = cert
  if (cert) {
    certificateForm.value = { ...cert }
  } else {
    certificateForm.value = { nama: '', penerbit: '', tanggal_terbit: '' }
  }
  showCertificateModal.value = true
}

const closeCertificateModal = () => {
  showCertificateModal.value = false
  editingCertificate.value = null
}

const saveCertificate = async () => {
  try {
    const data = { ...certificateForm.value, portofolio_id: route.params.id }
    if (editingCertificate.value) {
      await axios.put(`/api/mahasiswa/certificates/${editingCertificate.value.id}`, data)
      alert('Certificate berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/certificates', data)
      alert('Certificate berhasil ditambahkan')
    }
                closeCertificateModal()
                await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menyimpan certificate')
  }
}

const deleteCertificate = async (id) => {
  if (!confirm('Yakin ingin menghapus certificate ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/certificates/${id}`)
    await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menghapus certificate')
  }
}

// Experience functions
const openExperienceModal = (exp = null) => {
  editingExperience.value = exp
  if (exp) {
    experienceForm.value = {
      judul: exp.judul || '',
      perusahaan: exp.perusahaan || '',
      deskripsi: exp.deskripsi || '',
      tipe: exp.tipe || 'kerja',
      tanggal_mulai: exp.tanggal_mulai ? exp.tanggal_mulai.split('T')[0] : '',
      tanggal_selesai: exp.tanggal_selesai ? exp.tanggal_selesai.split('T')[0] : '',
      masih_berlangsung: exp.masih_berlangsung || false
    }
  } else {
    experienceForm.value = {
      judul: '',
      perusahaan: '',
      deskripsi: '',
      tipe: 'kerja',
      tanggal_mulai: '',
      tanggal_selesai: '',
      masih_berlangsung: false
    }
  }
  showExperienceModal.value = true
}

const closeExperienceModal = () => {
  showExperienceModal.value = false
  editingExperience.value = null
  experienceForm.value = {
    judul: '',
    perusahaan: '',
    deskripsi: '',
    tipe: 'kerja',
    tanggal_mulai: '',
    tanggal_selesai: '',
    masih_berlangsung: false
  }
}

const saveExperience = async () => {
  try {
    const data = { 
      ...experienceForm.value, 
      portofolio_id: parseInt(route.params.id)
    }
    
    // Remove empty tanggal_selesai if masih_berlangsung is true
    if (data.masih_berlangsung) {
      data.tanggal_selesai = null
    }
    
    if (editingExperience.value) {
      await axios.put(`/api/mahasiswa/experiences/${editingExperience.value.id}`, data)
      alert('Experience berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/experiences', data)
      alert('Experience berhasil ditambahkan')
    }
                closeExperienceModal()
                await loadPortfolio(true) // Preserve form data
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'Gagal menyimpan experience'
    const errors = error.response?.data?.errors
    if (errors) {
      const errorList = Object.values(errors).flat().join(', ')
      alert(`${errorMessage}: ${errorList}`)
    } else {
      alert(errorMessage)
    }
    console.error('Error saving experience:', error)
  }
}

const deleteExperience = async (id) => {
  if (!confirm('Yakin ingin menghapus experience ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/experiences/${id}`)
    await loadPortfolio(true) // Preserve form data
  } catch (error) {
    alert('Gagal menghapus experience')
  }
}
</script>

