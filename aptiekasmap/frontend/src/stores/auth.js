import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const token      = ref(localStorage.getItem('token') || '')
  const isAdmin    = ref(localStorage.getItem('role') === 'admin')
  const isLoggedIn = ref(!!localStorage.getItem('token'))

  async function login(tokenValue, role) {
    token.value      = tokenValue
    isLoggedIn.value = true
    isAdmin.value    = role === 'admin'
    localStorage.setItem('token', tokenValue)
    localStorage.setItem('role', role || 'user')
    axios.defaults.headers.common['Authorization'] = `Bearer ${tokenValue}`
  }

  function logout() {
    token.value      = ''
    isLoggedIn.value = false
    isAdmin.value    = false
    localStorage.removeItem('token')
    localStorage.removeItem('role')
    delete axios.defaults.headers.common['Authorization']
  }

  return { token, isAdmin, isLoggedIn, login, logout }
})
