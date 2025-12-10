<template>
  <div class="page-portfolio">
    <!-- NAVBAR -->
    <header class="nav-wrap">
      <div class="nav-bar">
        <div class="nav-left">
          <span class="brand">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="soegija" class="nav-logo" />
        </div>

        <div class="nav-center">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link
            v-if="!currentUser || (currentUser.role !== 'perusahaan' && currentUser.role !== 'admin')"
            to="/explore"
            class="nav-link active"
          >
            Portofolio
          </router-link>
        </div>

        <div class="nav-right">
          <template v-if="currentUser">
            <router-link
              v-if="currentUser.role === 'admin'"
              to="/dashboard/admin"
              class="user-name"
            >
              Dashboard
            </router-link>
            <router-link
              v-else-if="currentUser.role === 'mahasiswa'"
              to="/profile/mahasiswa"
              class="user-name"
            >
              Dashboard
            </router-link>
            <router-link
              v-else
              to="/dashboard/perusahaan"
              class="user-name"
            >
              Dashboard
            </router-link>
            <span class="sep">|</span>
            <button class="user-name logout" @click="handleLogout">Logout</button>
          </template>
          <template v-else>
            <router-link to="/login" class="user-name">Sign Up | Login</router-link>
          </template>
        </div>
      </div>
    </header>

    <!-- SEARCH ROW (simple pill style) -->
    <section class="search-row">
      <div class="search-inner">
        <button class="search-pill" @click="filterByBidang(null)">
          Filter
        </button>
        <div class="search-input">
          <span>CV</span>
        </div>
      </div>
    </section>

    <!-- MAIN -->
    <main class="main-wrap">
      <!-- Sidebar -->
      <aside class="left-sidebar">
        <div class="sidebar-scroll">
          <div
            class="sidebar-item"
            :class="{ active: selectedBidang === null }"
            @click="filterByBidang(null)"
          >
            <span class="si-text">Semua</span>
            <span class="si-count">{{ totalPortfolios }}</span>
          </div>
          <div
            class="sidebar-item"
            :class="{ active: selectedBidang === 'backend' }"
            @click="filterByBidang('backend')"
          >
            <span class="si-text">Backend</span>
            <span class="si-count">{{ getCountByBidang('backend') }}</span>
          </div>
          <div
            class="sidebar-item"
            :class="{ active: selectedBidang === 'frontend' }"
            @click="filterByBidang('frontend')"
          >
            <span class="si-text">Frontend</span>
            <span class="si-count">{{ getCountByBidang('frontend') }}</span>
          </div>
          <div
            class="sidebar-item"
            :class="{ active: selectedBidang === 'fullstack' }"
            @click="filterByBidang('fullstack')"
          >
            <span class="si-text">Fullstack</span>
            <span class="si-count">{{ getCountByBidang('fullstack') }}</span>
          </div>
          <div
            class="sidebar-item"
            :class="{ active: selectedBidang === 'QATester' }"
            @click="filterByBidang('QATester')"
          >
            <span class="si-text">QA Tester</span>
            <span class="si-count">{{ getCountByBidang('QATester') }}</span>
          </div>
        </div>
      </aside>

      <!-- Cards panel -->
      <section class="cards-panel">
        <div class="cards-inner">
          <div class="cards-grid">
            <article
              v-for="portfolio in paginatedPortfolios"
              :key="portfolio.id"
              class="card"
              @click="viewPortfolio(portfolio)"
            >
              <div
                class="card-thumb"
                :style="{ backgroundImage: `url(${portfolio.thumbnail || fallbackImage})` }"
              ></div>
              <div class="card-footer">
                <div class="card-avatar">
                  <span>{{ (portfolio.mahasiswa?.user?.name || 'U').charAt(0) }}</span>
                </div>
                <div class="card-texts">
                  <div class="card-name">{{ portfolio.mahasiswa?.user?.name || portfolio.nama }}</div>
                  <div class="card-sub">{{ portfolio.nama }}</div>
                </div>
                <div class="card-icon">●</div>
              </div>
            </article>
          </div>

          <div v-if="filteredPortfolios.length === 0" class="empty-state">
            Tidak ada portofolio yang ditemukan
          </div>

          <!-- Pagination -->
          <div v-if="filteredPortfolios.length > itemsPerPage" class="pagination">
            <button
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
            >
              ←
            </button>
            <span>{{ currentPage }} / {{ totalPages }}</span>
            <button
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
            >
              →
            </button>
          </div>
        </div>
      </section>
    </main>

    <!-- FOOTER -->
    <footer class="page-footer">
      <div class="footer-inner">
        <div class="contact">
          <h3>Informasi Kontak</h3>
          <div class="c-line">Email : <a href="mailto:unika@unika.ac.id">unika@unika.ac.id</a></div>
          <div class="c-line">Hotline : (024) 850 5003</div>
          <div class="c-line">WhatsApp Official : <a href="https://wa.me/6281232345479">08123 2345 479</a></div>
        </div>

        <div class="footer-logos">
          <div class="porto">Porto<br/>Connect</div>
          <div class="x">✕</div>
          <div class="uni">
            <img src="@/assets/logo-soegija-putih.png" alt="SCU" />
          </div>
        </div>

        <div class="copy">© 2025 PortoConnect. All rights reserved.</div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useSweetAlert } from '@/composables/useSweetAlert'
import { logger } from '@/utils/logger'

const { showError } = useSweetAlert()
const fallbackImage = new URL('@/assets/card-sample.jpg', import.meta.url).href

const router = useRouter()
const portfolios = ref([])
const allPortfolios = ref([]) // Store all portfolios for counting
const currentUser = ref(null)
const selectedBidang = ref(null)
const currentPage = ref(1)
const itemsPerPage = 6

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    try {
      const res = await axios.get('/api/me')
      currentUser.value = res.data.user
    } catch (error) {
      // Not logged in, continue
    }
  }
  await loadPortfolios()
})

const ensureAllPortfolios = async () => {
  if (allPortfolios.value.length) return
  const response = await axios.get('/api/portfolios/public')
  if (response.data && Array.isArray(response.data.portfolios)) {
    allPortfolios.value = response.data.portfolios
  } else if (Array.isArray(response.data)) {
    allPortfolios.value = response.data
  } else {
    allPortfolios.value = []
  }
}

const loadPortfolios = async (bidang = null) => {
  try {
    await ensureAllPortfolios()
    portfolios.value = bidang
      ? allPortfolios.value.filter(p => p.bidang === bidang)
      : allPortfolios.value
    currentPage.value = 1
  } catch (error) {
    logger.error('Error loading portfolios:', error)
    logger.debug('Error response:', error.response?.data)
    portfolios.value = []
    allPortfolios.value = []
    
    const errorMessage = error.response?.data?.message || error.message || 'Gagal memuat portofolio'
    if (!errorMessage.includes('Unclosed')) {
      showError('Gagal memuat portofolio: ' + errorMessage)
    }
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  currentPage.value = 1 // Reset to first page when filtering
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => {
  return portfolios.value
})

const totalPortfolios = computed(() => {
  return allPortfolios.value.length
})

// Pagination computed
const totalPages = computed(() => {
  return Math.ceil(filteredPortfolios.value.length / itemsPerPage)
})

const paginatedPortfolios = computed(() => {
  if (filteredPortfolios.value.length <= itemsPerPage) {
    return filteredPortfolios.value
  }
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredPortfolios.value.slice(start, end)
})

const getCountByBidang = (bidang) => {
  return allPortfolios.value.filter(p => p.bidang === bidang).length
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    logger.warn('Logout error:', err)
  } finally {
    try { localStorage.removeItem('token') } catch (e) { logger.warn('localStorage remove error', e) }
    delete axios.defaults.headers.common['Authorization']
    currentUser.value = null
    router.push('/')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }
.page-portfolio {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: radial-gradient( circle at 50% -20%,
    #000 2%,
    #50145c 18%,
    #50145c 36%,
    #50145c 50%,
    #ffffff 100%
  );
  color: #111;
  font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

/* NAVBAR */
.nav-wrap { padding: 28px 0; }
.nav-bar {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  align-items:center;
  justify-content:space-between;
  background: rgba(255,255,255,0.95);
  border-radius: 26px;
  padding: 12px 28px;
  box-shadow: 0 8px 30px rgba(6,6,10,0.12);
  height: 64px;
}
.nav-left, .nav-center, .nav-right { display:flex; align-items:center; gap:14px; }
.brand { font-weight:700; color:#5e1f62; }
.nav-logo { height:26px; }
.nav-center { gap:28px; margin-left: 14px;}
.nav-link { color:#222; opacity:0.9; }
.nav-link.active { font-weight:700; }
.user-name { color:#222; font-weight:600; }
.sep { color:#999; }
.logout { border:none; background:transparent; cursor:pointer; }

/* search row */
.search-row { margin-top: 10px; }
.search-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  gap:18px;
  align-items:center;
  padding: 4px 12px;
}
.search-pill {
  min-width:120px;
  padding:10px 18px;
  border-radius:999px;
  background: #ffffff;
  border:none;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
  cursor: pointer;
}
.search-input {
  flex:1;
  min-height: 42px;
  border-radius: 999px;
  padding: 0 20px;
  border: 2px solid rgba(0,0,0,0.8);
  background: white;
  color: #222;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
  display:flex;
  align-items:center;
}

/* MAIN LAYOUT */
.main-wrap {
  width: min(1200px, 94%);
  margin: 28px auto;
  display:grid;
  grid-template-columns: 220px 1fr;
  gap: 28px;
  align-items:start;
}

/* Sidebar */
.left-sidebar {
  border-radius: 18px;
  padding: 14px;
  background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
  box-shadow: 0 20px 60px rgba(6,6,10,0.06);
  height: calc(100vh - 360px);
  overflow: hidden;
}
.sidebar-scroll {
  height:100%;
  overflow:auto;
  padding-right:8px;
}
.sidebar-item {
  display:flex;
  align-items:center;
  gap:10px;
  padding: 12px;
  margin-bottom: 12px;
  color: rgba(255,255,255,0.9);
  opacity: 0.92;
  border-radius: 12px;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.15s ease;
}
.sidebar-item.active, .sidebar-item:hover {
  background: rgba(255,255,255,0.12);
  transform: translateY(-2px);
}
.si-text { flex:1; }
.si-count { font-weight:700; }

/* Cards panel */
.cards-panel {
  border-radius: 26px;
  background: #50145c;
  padding: 28px;
  box-shadow: 0 30px 80px rgba(6,6,10,0.12);
}
.cards-inner {
  background: transparent;
  padding: 0;
  border-radius: 0;
}
.cards-grid {
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
}
.card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 16px 40px rgba(6,6,10,0.08);
  display:flex;
  flex-direction:column;
  min-height: 220px;
  cursor: pointer;
  transition: transform 0.16s ease, box-shadow 0.16s ease;
}
.card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px rgba(6,6,10,0.14); }
.card-thumb {
  height: 140px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
.card-footer {
  display:flex;
  align-items:center;
  gap:12px;
  padding: 12px 16px;
}
.card-avatar {
  width:44px; height:44px; border-radius:999px; background:#5e1f62; color:#fff;
  display:flex; align-items:center; justify-content:center; font-weight:700; border:3px solid #fff;
}
.card-texts .card-name { font-weight:700; color:#111; }
.card-sub { font-size:13px; color:#777; margin-top:4px; }
.card-icon { margin-left:auto; color:#bbb; }

.empty-state {
  margin-top: 16px;
  color:#fff;
  text-align:center;
}

.pagination {
  margin-top: 18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
}
.pagination button {
  padding:10px 14px;
  border-radius:12px;
  border:none;
  background:#fff;
  cursor:pointer;
  box-shadow: 0 6px 16px rgba(6,6,10,0.12);
}
.pagination span {
  color:#fff;
  font-weight:700;
  letter-spacing: 1px;
}

/* Footer */
.page-footer {
  margin-top: 48px;
  background: #50145c;
  color:#fff;
  padding: 48px 0;
}
.footer-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  flex-direction:column;
  gap:28px;
  align-items:center;
}
.contact { align-self:flex-start; }
.contact h3 { margin:0 0 6px 0; font-weight:700; }
.contact a { color:#fff; text-decoration: underline; }
.footer-logos {
  width:100%;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:36px;
  padding-top: 8px;
}
.porto { font-weight:700; font-size:20px; text-align:center; }
.x { font-size:34px; color:#fff; transform:translateY(6px); }
.uni img { height:48px; margin-right:12px; }
.copy { border-top:1px solid rgba(255,255,255,0.2); padding-top:12px; color:rgba(255,255,255,0.8); width:100%; text-align:center; }

/* responsive */
@media (max-width: 1000px) {
  .cards-grid { grid-template-columns: repeat(2, 1fr); }
  .main-wrap { grid-template-columns: 160px 1fr; }
}
@media (max-width: 720px) {
  .nav-bar { padding: 8px 14px; height:58px; }
  .main-wrap { grid-template-columns: 1fr; }
  .left-sidebar { display:none; }
  .cards-grid { grid-template-columns: 1fr; }
  .search-inner { flex-direction:column; gap:12px; align-items:stretch; }
}
</style>

