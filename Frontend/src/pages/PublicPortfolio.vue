<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header with Back Button -->
    <header class="bg-white shadow-sm mb-4">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <button
          @click="goBack"
          class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali
        </button>
        <button
          @click="viewProfile"
          class="bg-white border-2 border-purple-700 text-purple-700 px-6 py-2 rounded-lg hover:bg-purple-50 font-semibold"
        >
          Lihat Profil
        </button>
      </div>
    </header>

    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <p>Memuat portofolio...</p>
    </div>
    <div v-else-if="portfolio" class="max-w-4xl mx-auto px-4 py-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Portfolio Header -->
        <div v-if="portfolio.nama" class="text-center mb-6 pb-4 border-b">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ portfolio.nama }}</h2>
          <span v-if="portfolio.bidang" class="inline-block bg-purple-100 text-purple-700 px-4 py-1 rounded-full text-sm">
            {{ portfolio.bidang }}
          </span>
        </div>

        <!-- Student Header -->
        <div class="text-center mb-8">
          <h1 class="text-4xl font-bold text-purple-700 mb-2">{{ portfolio.mahasiswa?.user?.name }}</h1>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.nim }}</p>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.jurusan }} - {{ portfolio.mahasiswa?.fakultas }}</p>
          <p class="text-gray-600">{{ portfolio.mahasiswa?.universitas }}</p>
        </div>

        <!-- Portfolio Description -->
        <div v-if="portfolio.deskripsi" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Deskripsi</h2>
          <p class="text-gray-700 whitespace-pre-line">{{ portfolio.deskripsi }}</p>
        </div>

        <!-- Education -->
        <div v-if="portfolio.education" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Pendidikan</h2>
          <div class="text-gray-700 whitespace-pre-line">{{ formatEducation(portfolio.education) }}</div>
        </div>

        <!-- Language -->
        <div v-if="portfolio.language" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Bahasa</h2>
          <div class="text-gray-700 whitespace-pre-line">{{ formatLanguage(portfolio.language) }}</div>
        </div>

        <!-- Alamat -->
        <div v-if="portfolio.mahasiswa?.alamat" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Alamat</h2>
          <p class="text-gray-700 whitespace-pre-line">{{ portfolio.mahasiswa.alamat }}</p>
        </div>

        <!-- Skills -->
        <div v-if="portfolio.skills && portfolio.skills.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Skills</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="skill in portfolio.skills"
              :key="skill.id"
              class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg"
            >
              {{ skill.nama }} ({{ skill.level }})
            </span>
          </div>
        </div>

        <!-- Projects -->
        <div v-if="portfolio.projects && portfolio.projects.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Proyek</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="project in portfolio.projects"
              :key="project.id"
              class="border rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <h3 class="font-bold text-lg mb-2">{{ project.judul }}</h3>
              <p class="text-gray-600 text-sm mb-2">{{ project.deskripsi }}</p>
              <div v-if="project.teknologi" class="mb-2">
                <span class="text-xs text-gray-500">Teknologi: {{ project.teknologi }}</span>
              </div>
              <div v-if="project.tanggal_mulai || project.tanggal_selesai" class="mb-2 text-xs text-gray-500">
                <span v-if="project.tanggal_mulai">
                  {{ new Date(project.tanggal_mulai).toLocaleDateString() }}
                </span>
                <span v-if="project.tanggal_mulai && !project.masih_berlangsung && project.tanggal_selesai"> - </span>
                <span v-if="project.tanggal_selesai">
                  {{ new Date(project.tanggal_selesai).toLocaleDateString() }}
                </span>
              </div>
              <a v-if="project.link" :href="project.link" target="_blank" class="text-blue-600 text-sm mt-2 inline-block hover:underline">
                Lihat Proyek →
              </a>
            </div>
          </div>
        </div>

        <!-- Experiences -->
        <div v-if="portfolio.experiences && portfolio.experiences.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Pengalaman</h2>
          <div class="space-y-4">
            <div
              v-for="exp in portfolio.experiences"
              :key="exp.id"
              class="border-l-4 border-purple-500 pl-4"
            >
              <h3 class="font-bold text-lg">{{ exp.judul }}</h3>
              <p class="text-gray-600 font-medium">{{ exp.perusahaan }}</p>
              <p class="text-sm text-gray-500 mb-2">
                <span v-if="exp.tanggal_mulai">
                  {{ new Date(exp.tanggal_mulai).toLocaleDateString() }}
                </span>
                <span v-if="exp.tanggal_mulai && !exp.masih_berlangsung && exp.tanggal_selesai"> - </span>
                <span v-if="!exp.masih_berlangsung && exp.tanggal_selesai">
                  {{ new Date(exp.tanggal_selesai).toLocaleDateString() }}
                </span>
                <span v-if="exp.masih_berlangsung"> - Sekarang</span>
                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                  {{ exp.tipe === 'kerja' ? 'Kerja' : 'Magang' }}
                </span>
              </p>
              <p class="text-gray-700 mt-2">{{ exp.deskripsi }}</p>
            </div>
          </div>
        </div>

        <!-- Certificates -->
        <div v-if="portfolio.certificates && portfolio.certificates.length > 0" class="mb-8">
          <h2 class="text-2xl font-bold mb-4">Sertifikat</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="cert in portfolio.certificates"
              :key="cert.id"
              class="border rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <h3 class="font-bold text-lg mb-1">{{ cert.nama }}</h3>
              <p class="text-gray-600 text-sm mb-2">{{ cert.penerbit }}</p>
              <p v-if="cert.tanggal_terbit" class="text-xs text-gray-500">
                {{ new Date(cert.tanggal_terbit).toLocaleDateString() }}
              </p>
            </div>
          </div>
        </div>

        <!-- Contact -->
        <div class="border-t pt-4 mt-8">
          <h2 class="text-2xl font-bold mb-4">Kontak</h2>
          <div class="space-y-2">
            <div v-if="portfolio.mahasiswa?.no_telp" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span class="text-gray-700">{{ portfolio.mahasiswa.no_telp }}</span>
            </div>
            <div v-if="portfolio.mahasiswa?.user?.email" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span class="text-gray-700">{{ portfolio.mahasiswa.user.email }}</span>
            </div>
            <div v-if="portfolio.mahasiswa?.linkedin" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
              <a :href="portfolio.mahasiswa.linkedin" target="_blank" class="text-blue-600 hover:underline">
                LinkedIn
              </a>
            </div>
            <div v-if="portfolio.mahasiswa?.github" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
              <a :href="portfolio.mahasiswa.github" target="_blank" class="text-gray-800 hover:underline">
                GitHub
              </a>
            </div>
            <div v-if="portfolio.mahasiswa?.website" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
              </svg>
              <a :href="portfolio.mahasiswa.website" target="_blank" class="text-purple-600 hover:underline">
                Website
              </a>
            </div>
          </div>
        </div>

        <!-- Action Buttons (Edit & Download PDF) -->
        <div class="border-t pt-6 mt-8">
          <div class="flex justify-center gap-4 flex-wrap">
            <button v-if="isOwner" @click="goToEdit" class="bg-purple-700 text-white px-8 py-3 rounded-lg hover:bg-purple-800 font-semibold">
              Edit
            </button>
            <button @click="downloadPDF" class="bg-purple-700 text-white px-8 py-3 rounded-lg hover:bg-purple-800 font-semibold">
              Download PDF
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center min-h-screen">
      <p class="text-red-600">Portofolio tidak ditemukan</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const portfolio = ref(null)
const loading = ref(true)
const isOwner = ref(false)

const formatEducation = (education) => {
  if (typeof education === 'string') {
    try {
      const parsed = JSON.parse(education)
      if (Array.isArray(parsed)) {
        return parsed.map(edu => {
          if (typeof edu === 'string') return edu
          return `${edu.instansi || ''} - ${edu.jurusan || ''} (${edu.tahun || ''})`
        }).join('\n')
      }
      return education
    } catch {
      return education
    }
  }
  if (Array.isArray(education)) {
    return education.map(edu => {
      if (typeof edu === 'string') return edu
      return `${edu.instansi || ''} - ${edu.jurusan || ''} (${edu.tahun || ''})`
    }).join('\n')
  }
  return education
}

const formatLanguage = (language) => {
  if (typeof language === 'string') {
    try {
      const parsed = JSON.parse(language)
      if (Array.isArray(parsed)) {
        return parsed.map(lang => {
          if (typeof lang === 'string') return lang
          return `${lang.nama || ''} - ${lang.level || ''}`
        }).join('\n')
      }
      return language
    } catch {
      return language
    }
  }
  if (Array.isArray(language)) {
    return language.map(lang => {
      if (typeof lang === 'string') return lang
      return `${lang.nama || ''} - ${lang.level || ''}`
    }).join('\n')
  }
  return language
}

onMounted(async () => {
  try {
    const publicLink = route.params.publicLink
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const res = await axios.get(`/api/portfolio/${publicLink}`)
    portfolio.value = res.data.portofolio
    isOwner.value = res.data.is_owner || false
  } catch (err) {
    console.error('Error loading portfolio:', err)
  } finally {
    loading.value = false
  }
})

const goToEdit = () => {
  if (portfolio.value && portfolio.value.id) {
    router.push(`/portfolio/preview/${portfolio.value.id}`)
  }
}

const viewProfile = () => {
  if (portfolio.value && portfolio.value.mahasiswa && portfolio.value.mahasiswa.id) {
    // Jika owner, arahkan ke halaman profil sendiri (bisa edit)
    if (isOwner.value) {
      router.push('/profile/mahasiswa')
    } else {
      // Jika bukan owner, arahkan ke halaman view-only
      router.push(`/mahasiswa/${portfolio.value.mahasiswa.id}/profile`)
    }
  }
}

const goBack = () => {
  router.go(-1)
}

const downloadPDF = async () => {
  try {
    const publicLink = route.params.publicLink
    const token = localStorage.getItem('token')

    let res = null

    // Try with auth first if user is logged in
    if (token && isOwner.value) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      try {
        res = await axios.post('/api/mahasiswa/export-pdf', {
          portofolio_id: portfolio.value.id
        }, {
          responseType: 'blob'
        })
      } catch (err) {
        // If auth fails, try public endpoint (log error for debugging)
        console.error('export with auth failed, falling back to public endpoint:', err)
        res = await axios.post(`/api/portfolio/${publicLink}/export-pdf`, {}, {
          responseType: 'blob'
        })
      }
    } else {
      // Use public endpoint
      res = await axios.post(`/api/portfolio/${publicLink}/export-pdf`, {}, {
        responseType: 'blob'
      })
    }

    // Create blob URL and trigger download
    const blob = new Blob([res.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `portfolio_${portfolio.value?.nama || portfolio.value?.mahasiswa?.user?.name || 'portfolio'}_${Date.now()}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)

    alert('PDF berhasil diunduh')
  } catch (err) {
    console.error('downloadPDF error:', err)
    alert('Gagal membuat PDF: ' + (err.response?.data?.message || 'Unknown error'))
  }
}
</script>
