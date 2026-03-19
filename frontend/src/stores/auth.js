import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token'))
  const loading = ref(false)

  // Геттеры
  const isLoggedIn = computed(() => !!token.value)
  const userName = computed(() => {
    if (!user.value) return ''
    return `${user.value.last_name} ${user.value.first_name}`
  })
  const userRole = computed(() => user.value?.role || null)

  // Инициализация - загружаем пользователя из localStorage
  function init() {
    const savedUser = localStorage.getItem('user')
    if (savedUser && token.value) {
      try {
        user.value = JSON.parse(savedUser)
        fetchUser() // проверяем актуальность данных на сервере
      } catch (e) {
        logout()
      }
    }
  }

  // Вход
  async function login(credentials) {
    loading.value = true
    try {
      const response = await api.post('/auth/login', credentials)
      const data = response.data

      // Сохраняем данные
      user.value = data.data.user
      token.value = data.data.token

      localStorage.setItem('auth_token', data.data.token)
      localStorage.setItem('user', JSON.stringify(data.data.user))

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

  // Регистрация (последний шаг)
  async function register(userData) {
    loading.value = true
    try {
      const response = await api.post('/auth/create-user', userData)
      const data = response.data

      // Сразу логиним после регистрации
      user.value = data.data.user
      token.value = data.data.token

      localStorage.setItem('auth_token', data.data.token)
      localStorage.setItem('user', JSON.stringify(data.data.user))

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

  // Выход
  async function logout() {
    try {
      if (token.value) {
        await api.post('/auth/logout')
      }
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      user.value = null
      token.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }
  }

  // Получить текущего пользователя (обновить данные)
  async function fetchUser() {
    if (!token.value) return

    try {
      const response = await api.get('/auth/me')
      user.value = response.data.data.user
      localStorage.setItem('user', JSON.stringify(response.data.data.user))
    } catch (error) {
      logout()
    }
  }

  return {
    user,
    token,
    loading,
    isLoggedIn,
    userName,
    userRole,
    init,
    login,
    register,
    logout,
    fetchUser
  }
})
