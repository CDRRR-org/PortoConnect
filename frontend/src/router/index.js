import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import Login from '@/pages/login.vue'
import Dashboard from '@/pages/dashboard.vue'
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
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
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