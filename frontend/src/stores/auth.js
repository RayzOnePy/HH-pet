import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const company = ref(null)
  const token = ref(localStorage.getItem('auth_token'))
  const loading = ref(false)
  const initialized = ref(false)

  const isLoggedIn = computed(() => !!token.value)
  const hasCompany = computed(() => company.value !== null)
  const userName = computed(() => {
    if (!user.value) return ''
    return `${user.value.last_name} ${user.value.first_name}`
  })
  const userRole = computed(() => user.value?.role || null)

  async function init() {
    if (initialized.value) return

    const savedToken = localStorage.getItem('auth_token')
    if (!savedToken) {
      initialized.value = true
      return
    }

    token.value = savedToken
    loading.value = true

    try {
      const response = await api.get('/auth/me')
      user.value = response.data.data.user
      company.value = response.data.data.company // может быть null
      localStorage.setItem('user', JSON.stringify(user.value))
    } catch (error) {
      console.error('Ошибка загрузки данных:', error)
      logout()
    } finally {
      loading.value = false
      initialized.value = true
    }
  }

  async function login(credentials) {
    loading.value = true
    try {
      const response = await api.post('/auth/login', credentials)
      const data = response.data

      user.value = data.data.user
      token.value = data.data.token

      localStorage.setItem('auth_token', data.data.token)
      localStorage.setItem('user', JSON.stringify(user.value))

      const meResponse = await api.get('/auth/me')
      company.value = meResponse.data.data.company

      return { success: true, data: data.data }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка входа'
      }
    } finally {
      loading.value = false
    }
  }

  async function register(userData) {
    loading.value = true
    try {
      const response = await api.post('/auth/create-user', userData)
      const data = response.data

      user.value = data.data.user
      token.value = data.data.token

      localStorage.setItem('auth_token', data.data.token)
      localStorage.setItem('user', JSON.stringify(user.value))

      company.value = null

      return { success: true, data: data.data }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка регистрации'
      }
    } finally {
      loading.value = false
    }
  }

  async function refreshCompany() {
    try {
      const response = await api.get('/auth/me')
      company.value = response.data.data.company
      return company.value
    } catch (error) {
      console.error('Ошибка обновления компании:', error)
      return null
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await api.post('/auth/logout')
      }
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      user.value = null
      company.value = null
      token.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }
  }

  return {
    // State
    user,
    company,
    token,
    loading,
    initialized,
    // Getters
    isLoggedIn,
    hasCompany,
    userName,
    userRole,
    // Actions
    init,
    login,
    register,
    logout,
    refreshCompany
  }
})
