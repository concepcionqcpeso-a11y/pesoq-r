import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const api = axios.create({ baseURL: 'http://127.0.0.1:8000/api' })

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  const token = ref(localStorage.getItem('token') || null)

  const isLoggedIn = computed(() => !!token.value)

  api.interceptors.request.use(config => {
    if (token.value) config.headers.Authorization = `Bearer ${token.value}`
    return config
  })

  async function login(form) {
    const { data } = await api.post('/login', form)
    setSession(data)
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout failed', error)
    }
    clearSession()
  }

  function setSession(data) {
    user.value  = data.user
    token.value = data.token
    localStorage.setItem('user',  JSON.stringify(data.user))
    localStorage.setItem('token', data.token)
  }

  function clearSession() {
    user.value  = null
    token.value = null
    localStorage.removeItem('user')
    localStorage.removeItem('token')
  }

  return { user, token, isLoggedIn, login, logout, api }
})
