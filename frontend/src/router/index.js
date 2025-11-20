import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import Login from '@/pages/login.vue'
import Register from '@/pages/register.vue'
import Dashboard from '@/pages/dashboard.vue'
import MahasiswaDashboard from '@/pages/MahasiswaDashboard.vue'
import MahasiswaProfile from '@/pages/MahasiswaProfile.vue'
import ExplorePortfolio from '@/pages/ExplorePortfolio.vue'
import AdminDashboard from '@/pages/AdminDashboard.vue'
import CompanyDashboard from '@/pages/CompanyDashboard.vue'
import PublicPortfolio from '@/pages/PublicPortfolio.vue'
import PilihRole from '@/pages/PilihRole.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
  },
  {
    path: '/dashboard/mahasiswa',
    name: 'MahasiswaDashboard',
    component: MahasiswaDashboard,
  },
  {
    path: '/profile/mahasiswa',
    name: 'MahasiswaProfile',
    component: MahasiswaProfile,
  },
  {
    path: '/explore',
    name: 'ExplorePortfolio',
    component: ExplorePortfolio,
  },
  {
    path: '/dashboard/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
  },
  {
    path: '/dashboard/perusahaan',
    name: 'CompanyDashboard',
    component: CompanyDashboard,
  },
  {
    path: '/portfolio/:publicLink',
    name: 'PublicPortfolio',
    component: PublicPortfolio,
  },
  {
    path: '/auth/callback',
    name: 'GoogleCallback',
    component: Login,
  },
  {
    path: '/pilih-role',
    name: 'PilihRole',
    component: PilihRole,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router