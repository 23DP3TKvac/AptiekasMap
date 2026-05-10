import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createVuetify } from 'vuetify'
import { createRouter, createWebHistory } from 'vue-router'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import App from './App.vue'
import HomeView from './views/HomeView.vue'
import PharmaciesView from './views/PharmaciesView.vue'
import ProfileView from './views/ProfileView.vue'
import StatisticsView from './views/StatisticsView.vue'
import AdminView from './views/AdminView.vue'

const vuetify = createVuetify({
  components, directives,
  theme: {
    defaultTheme: 'dark',
    themes: {
      light: { colors: { primary:'#16a34a', secondary:'#0f172a', accent:'#22c55e', error:'#dc2626', background:'#f8fafc', surface:'#ffffff' } },
      dark:  { colors: { primary:'#22c55e', secondary:'#1e293b', accent:'#4ade80', error:'#f87171', background:'#0f172a', surface:'#1e293b' } },
    },
  },
})

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/',           component: HomeView,       name: 'home' },
    { path: '/pharmacies', component: PharmaciesView, name: 'pharmacies' },
    { path: '/profile',    component: ProfileView,    name: 'profile' },
    { path: '/statistics', component: StatisticsView, name: 'statistics' },
    { path: '/admin',      component: AdminView,      name: 'admin' },
  ],
})

const app = createApp(App)
app.use(createPinia())
app.use(vuetify)
app.use(router)
app.mount('#app')
