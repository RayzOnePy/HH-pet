// frontend/src/composables/useVacancies.js
import { ref } from 'vue'
import api from '../services/api'

export function useVacancies() {
  const vacancies = ref([])
  const loading = ref(false)
  const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0
  })
  const counts = ref({
    total: 0,
    active: 0,
    inactive: 0
  })

  const fetchMyVacancies = async (page = 1, status = null) => {
    loading.value = true
    try {
      const params = { page, per_page: 15 }
      if (status && status !== 'all') {
        params.status = status
      }

      // ОДИН ЗАПРОС — получаем и список, и счетчики
      const response = await api.get('/employer/vacancies', { params })
      vacancies.value = response.data.data
      meta.value = response.data.meta
      counts.value = response.data.counts  // счетчики приходят вместе со списком
    } catch (error) {
      console.error('Error fetching vacancies:', error)
    } finally {
      loading.value = false
    }
  }

  const createVacancy = async (data) => {
    try {
      const response = await api.post('/employer/vacancies', data)
      return { success: true, data: response.data.data }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка создания вакансии'
      }
    }
  }

  const updateVacancy = async (id, data) => {
    try {
      const response = await api.put(`/employer/vacancies/${id}`, data)
      return { success: true, data: response.data.data }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка обновления вакансии'
      }
    }
  }

  const deleteVacancy = async (id) => {
    try {
      await api.delete(`/employer/vacancies/${id}`)
      return { success: true }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка удаления вакансии'
      }
    }
  }

  const toggleStatus = async (id) => {
    try {
      const response = await api.patch(`/employer/vacancies/${id}/toggle-status`)
      return { success: true, data: response.data.data }
    } catch (error) {
      return {
        success: false,
        message: error.message || 'Ошибка изменения статуса'
      }
    }
  }

  return {
    vacancies,
    loading,
    meta,
    counts,
    fetchMyVacancies,
    createVacancy,
    updateVacancy,
    deleteVacancy,
    toggleStatus
  }
}
