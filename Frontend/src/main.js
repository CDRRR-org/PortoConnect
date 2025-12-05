// src/main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/main.css' // jika ada, kalau tidak ada ok juga

createApp(App).use(router).mount('#app')
