// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'Home', component: () => import('@/pages/Home.vue') },
  { path: '/explore', name: 'Explore', component: () => import('@/pages/Explore.vue') },

  { path: '/profile/mahasiswa', name: 'MahasiswaProfile', component: () => import('@/pages/MahasiswaProfile.vue') },
  { path: '/dashboard/perusahaan', name: 'CompanyDashboard', component: () => import('@/pages/CompanyDashboard.vue') },
  { path: '/about', name: 'About', component: () => import('@/pages/About.vue') },
  { path: '/pilih-role', name: 'PilihRole', component: () => import('@/pages/PilihRole.vue') },
  { path: '/dashboard/admin', name: 'DashboardAdmin', component: () => import('@/pages/DashboardAdmin.vue') },

  // fallback -> home
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
