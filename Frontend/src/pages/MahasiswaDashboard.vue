<template>
  <div class="min-h-screen dashboard-gradient">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-purple-700">Dashboard Mahasiswa</h1>
        <div class="flex gap-4 items-center">
          <router-link to="/profile/mahasiswa" class="text-purple-700 hover:text-purple-900">Lihat Profil</router-link>
          <span class="text-gray-600">{{ user?.name }}</span>
          <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Logout
          </button>
        </div>
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
              :class="[ 'px-6 py-3 text-sm font-medium border-b-2',
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

      <!-- Profile Tab -->
      <div v-if="activeTab === 'profile'" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Profil Saya</h2>
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
              <input v-model="profileForm.name" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
              <input v-model="profileForm.nim" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
              <input v-model="profileForm.jurusan" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
              <input v-model="profileForm.fakultas" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
              <input v-model="profileForm.universitas" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
              <input v-model="profileForm.no_telp" type="text" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
              <input v-model="profileForm.linkedin" type="url" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">GitHub</label>
              <input v-model="profileForm.github" type="url" class="w-full border rounded-lg px-3 py-2" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Diri</label>
            <p class="text-gray-700">{{ profileForm.deskripsi_diri || 'Belum ada deskripsi' }}</p>
          </div>
          <div class="pt-4">
            <p class="text-sm text-gray-500 mb-2">Untuk mengedit profil, silakan klik "Edit Profil" di bagian list portofolio</p>
            <router-link to="/portfolio/list" class="inline-block bg-purple-700 text-white px-6 py-2 rounded-lg hover:bg-purple-800">
              Ke List Portofolio
            </router-link>
          </div>
        </div>
      </div>

      <!-- Projects Tab -->
      <div v-if="activeTab === 'projects'" class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Proyek Saya</h2>
          <button @click="openProjectModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Proyek
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="project in projects" :key="project.id" class="border rounded-lg p-4">
            <h3 class="font-bold">{{ project.judul }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ project.deskripsi }}</p>
            <div class="mt-4 flex gap-2">
              <button @click="openProjectModal(project)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteProject(project.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Skills Tab -->
      <div v-if="activeTab === 'skills'" class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Skills</h2>
          <button @click="openSkillModal()" class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
            + Tambah Skill
          </button>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="skill in skills" :key="skill.id" class="border rounded-lg p-4 text-center">
            <h3 class="font-bold">{{ skill.nama }}</h3>
            <p class="text-sm text-gray-600">{{ skill.level }}</p>
            <div class="mt-2 flex gap-2 justify-center">
              <button @click="openSkillModal(skill)" class="text-blue-600 text-sm">Edit</button>
              <button @click="deleteSkill(skill.id)" class="text-red-600 text-sm">Hapus</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Portfolio Tab -->
      <div v-if="activeTab === 'portfolio'" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Pengaturan Portofolio</h2>
        <div class="space-y-4">
          <div class="flex items-center gap-4">
            <label class="flex items-center gap-2">
              <input type="checkbox" v-model="portfolioForm.is_public" @change="updatePortfolioVisibility" />
              <span>Buat portofolio publik</span>
            </label>
          </div>
          <div v-if="portfolio?.public_link">
            <label class="block text-sm font-medium text-gray-700 mb-1">Link Publik</label>
            <div class="flex gap-2">
              <input :value="`${baseUrl}/portfolio/${portfolio.public_link}`" readonly class="flex-1 border rounded-lg px-3 py-2 bg-gray-50" />
              <button @click="copyPublicLink" class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
                Salin
              </button>
            </div>
          </div>
          <div class="flex gap-4">
            <button @click="generatePublicLink" class="bg-purple-700 text-white px-6 py-2 rounded-lg hover:bg-purple-800">
              Generate Link Publik
            </button>
            <button @click="exportPDF" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
              Export ke PDF
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Project Modal -->
    <div v-if="showProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="closeProjectModal">
      <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.stop>
        <h3 class="text-xl font-bold mb-4">{{ editingProject ? 'Edit' : 'Tambah' }} Proyek</h3>
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
    <div v-if="showSkillModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="closeSkillModal">
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
  </div>
</template>

<script setup>
defineOptions({ name: 'MahasiswaDashboardPage' })

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const user = ref(null)
const mahasiswa = ref(null)
const portfolio = ref(null)
const projects = ref([])
const skills = ref([])
const activeTab = ref('profile')
const baseUrl = window.location.origin

const tabs = [
  { id: 'profile', label: 'Profil' },
  { id: 'projects', label: 'Proyek' },
  { id: 'skills', label: 'Skills' },
  { id: 'portfolio', label: 'Portofolio' }
]

const profileForm = ref({
  name: '',
  nim: '',
  jurusan: '',
  fakultas: '',
  universitas: '',
  no_telp: '',
  linkedin: '',
  github: '',
  deskripsi_diri: ''
})

const portfolioForm = ref({
  is_public: false
})

const showProjectModal = ref(false)
const showSkillModal = ref(false)
const editingProject = ref(null)
const editingSkill = ref(null)

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

onMounted(async () => {
  // pastikan login & load data
  try {
    await loadData()
  } catch (err) {
    console.error('onMounted loadData error:', err)
    localStorage.removeItem('token')
    router.push('/login')
  }
})

const loadData = async () => {
  try {
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const userRes = await axios.get('/api/me')
    user.value = userRes.data.user

    const profileRes = await axios.get('/api/mahasiswa/profile')
    mahasiswa.value = profileRes.data.mahasiswa
    user.value = profileRes.data.user

    // Load projects and skills
    const projectsRes = await axios.get('/api/mahasiswa/projects')
    projects.value = projectsRes.data.projects || []
    const skillsRes = await axios.get('/api/mahasiswa/skills')
    skills.value = skillsRes.data.skills || []

    if (mahasiswa.value) {
      profileForm.value = {
        name: user.value.name,
        nim: mahasiswa.value.nim || '',
        jurusan: mahasiswa.value.jurusan || '',
        fakultas: mahasiswa.value.fakultas || '',
        universitas: mahasiswa.value.universitas || '',
        no_telp: mahasiswa.value.no_telp || '',
        linkedin: mahasiswa.value.linkedin || '',
        github: mahasiswa.value.github || '',
        deskripsi_diri: mahasiswa.value.deskripsi_diri || ''
      }
    }
  } catch (err) {
    console.error('Error loading data:', err)
    if (err.response?.status === 401) {
      localStorage.removeItem('token')
      router.push('/login')
    }
    throw err
  }
}

const updatePortfolioVisibility = async () => {
  try {
    await axios.put('/api/mahasiswa/portfolio', portfolioForm.value)
  } catch (err) {
    console.error('Error updating portfolio visibility:', err)
  }
}

const generatePublicLink = async () => {
  try {
    const res = await axios.post('/api/mahasiswa/portfolio/generate-link')
    portfolio.value = { ...portfolio.value, public_link: res.data.public_link }
    alert('Link publik berhasil dibuat')
  } catch (err) {
    console.error('generatePublicLink error:', err)
    alert('Gagal membuat link publik. Silakan coba lagi.')
  }
}

const copyPublicLink = () => {
  const link = `${baseUrl}/portfolio/${portfolio.value?.public_link || ''}`
  navigator.clipboard.writeText(link)
  alert('Link berhasil disalin')
}

const exportPDF = async () => {
  try {
    const res = await axios.post('/api/mahasiswa/export-pdf', {}, {
      responseType: 'blob'
    })

    const blob = new Blob([res.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `portfolio_${user.value?.name || 'portfolio'}_${Date.now()}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)

    alert('PDF berhasil diunduh')
  } catch (err) {
    console.error('exportPDF error:', err)
    alert('Gagal membuat PDF: ' + (err.response?.data?.message || 'Unknown error'))
  }
}

const openProjectModal = (project = null) => {
  editingProject.value = project
  if (project) {
    projectForm.value = {
      judul: project.judul || '',
      deskripsi: project.deskripsi || '',
      link: project.link || '',
      teknologi: project.teknologi || ''
    }
  } else {
    projectForm.value = { judul: '', deskripsi: '', link: '', teknologi: '' }
  }
  showProjectModal.value = true
}

const closeProjectModal = () => {
  showProjectModal.value = false
  editingProject.value = null
  projectForm.value = { judul: '', deskripsi: '', link: '', teknologi: '' }
}

const saveProject = async () => {
  try {
    if (editingProject.value) {
      await axios.put(`/api/mahasiswa/projects/${editingProject.value.id}`, projectForm.value)
      alert('Proyek berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/projects', projectForm.value)
      alert('Proyek berhasil ditambahkan')
    }
    closeProjectModal()
    await loadData()
  } catch (err) {
    console.error('saveProject error:', err)
    alert('Gagal menyimpan proyek: ' + (err.response?.data?.message || 'Unknown error'))
  }
}

const deleteProject = async (id) => {
  if (!confirm('Yakin ingin menghapus proyek ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/projects/${id}`)
    await loadData()
  } catch (err) {
    console.error('deleteProject error:', err)
    alert('Gagal menghapus proyek')
  }
}

const openSkillModal = (skill = null) => {
  editingSkill.value = skill
  if (skill) {
    skillForm.value = { nama: skill.nama || '', level: skill.level || 'beginner' }
  } else {
    skillForm.value = { nama: '', level: 'beginner' }
  }
  showSkillModal.value = true
}

const closeSkillModal = () => {
  showSkillModal.value = false
  editingSkill.value = null
  skillForm.value = { nama: '', level: 'beginner' }
}

const saveSkill = async () => {
  try {
    if (editingSkill.value) {
      await axios.put(`/api/mahasiswa/skills/${editingSkill.value.id}`, skillForm.value)
      alert('Skill berhasil diperbarui')
    } else {
      await axios.post('/api/mahasiswa/skills', skillForm.value)
      alert('Skill berhasil ditambahkan')
    }
    closeSkillModal()
    await loadData()
  } catch (err) {
    console.error('saveSkill error:', err)
    alert('Gagal menyimpan skill: ' + (err.response?.data?.message || 'Unknown error'))
  }
}

const deleteSkill = async (id) => {
  if (!confirm('Yakin ingin menghapus skill ini?')) return
  try {
    await axios.delete(`/api/mahasiswa/skills/${id}`)
    await loadData()
  } catch (err) {
    console.error('deleteSkill error:', err)
    alert('Gagal menghapus skill')
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    console.error('Gagal logout:', err)
  } finally {
    try { localStorage.removeItem('token') } catch (storageErr) { console.warn('localStorage remove error:', storageErr) }
    router.push('/login')
  }
}
</script>

<style scoped>
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
