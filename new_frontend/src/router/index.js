import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import Home from '@/pages/Home.vue'
import Login from '@/pages/login.vue'
import Register from '@/pages/register.vue'
import Dashboard from '@/pages/dashboard.vue'
import MahasiswaProfile from '@/pages/MahasiswaProfile.vue'
import ExplorePortfolio from '@/pages/ExplorePortfolio.vue'
import AdminDashboard from '@/pages/AdminDashboard.vue'
import CompanyDashboard from '@/pages/CompanyDashboard.vue'
import PublicPortfolio from '@/pages/PublicPortfolio.vue'
import PilihRole from '@/pages/PilihRole.vue'
import PortfolioList from '@/pages/PortfolioList.vue'
import PortfolioPreview from '@/pages/PortfolioPreview.vue'
import ViewMahasiswaProfile from '@/pages/ViewMahasiswaProfile.vue'

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
    path: '/profile/mahasiswa',
    name: 'MahasiswaProfile',
    component: MahasiswaProfile,
  },
  {
    path: '/dashboard/mahasiswa',
    redirect: '/profile/mahasiswa',
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
  {
    path: '/portfolio/list',
    name: 'PortfolioList',
    component: PortfolioList,
  },
  {
    path: '/portfolio/preview/:id',
    name: 'PortfolioPreview',
    component: PortfolioPreview,
  },
  {
    path: '/mahasiswa/:id/profile',
    name: 'ViewMahasiswaProfile',
    component: ViewMahasiswaProfile,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guard untuk proteksi route berdasarkan role
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token')
  
  // Jika route memerlukan auth tapi tidak ada token
  const publicRoutes = ['/', '/login', '/register', '/explore', '/portfolio/:publicLink', '/auth/callback']
  const isPublicRoute = publicRoutes.some(route => {
    if (route.includes(':')) {
      const routePattern = route.replace(/:[^/]+/g, '[^/]+')
      const regex = new RegExp('^' + routePattern.replace(/\//g, '\\/') + '$')
      return regex.test(to.path)
    }
    return to.path === route
  })
  
  if (!token && !isPublicRoute && to.path !== '/login' && to.path !== '/register') {
    next('/login')
    return
  }
  
  // Proteksi route admin
  if (to.path === '/dashboard/admin') {
    if (!token) {
      next('/login')
      return
    }
    
    try {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      const response = await axios.get('/api/me')
      
      if (response.data?.user?.role !== 'admin') {
        // Redirect berdasarkan role
        if (response.data?.user?.role === 'mahasiswa') {
          next('/profile/mahasiswa')
        } else if (response.data?.user?.role === 'perusahaan') {
          next('/dashboard/perusahaan')
        } else {
          next('/dashboard')
        }
        return
      }
    } catch (error) {
      console.error('Error checking user role:', error)
      localStorage.removeItem('token')
      next('/login')
      return
    }
  }
  
  // Proteksi route perusahaan
  if (to.path === '/dashboard/perusahaan') {
    if (!token) {
      next('/login')
      return
    }
    
    try {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      const response = await axios.get('/api/me')
      
      if (response.data?.user?.role !== 'perusahaan') {
        // Redirect berdasarkan role
        if (response.data?.user?.role === 'admin') {
          next('/dashboard/admin')
        } else if (response.data?.user?.role === 'mahasiswa') {
          next('/profile/mahasiswa')
        } else {
          next('/dashboard')
        }
        return
      }
    } catch (error) {
      console.error('Error checking user role:', error)
      localStorage.removeItem('token')
      next('/login')
      return
    }
  }
  
  // Proteksi route mahasiswa (profile)
  if (to.path === '/profile/mahasiswa' || to.path === '/portfolio/list') {
    if (!token) {
      next('/login')
      return
    }
    
    try {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      const response = await axios.get('/api/me')
      
      if (response.data?.user?.role !== 'mahasiswa') {
        // Redirect berdasarkan role
        if (response.data?.user?.role === 'admin') {
          next('/dashboard/admin')
        } else if (response.data?.user?.role === 'perusahaan') {
          next('/dashboard/perusahaan')
        } else {
          next('/dashboard')
        }
        return
      }
    } catch (error) {
      console.error('Error checking user role:', error)
      localStorage.removeItem('token')
      next('/login')
      return
    }
  }
  
  next()
})

export default router