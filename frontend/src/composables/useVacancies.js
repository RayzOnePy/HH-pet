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

  const fetchMyVacancies = async (page = 1, status = null) => {
    loading.value = true
    try {
      const params = { page, per_page: 15 }
      if (status) params.status = status

      const response = await api.get('/my-vacancies', { params })
      vacancies.value = response.data.data
      meta.value = response.data.meta
    } catch (error) {
      console.error('Error fetching vacancies:', error)
    } finally {
      loading.value = false
    }
  }

  const createVacancy = async (data) => {
    try {
      const response = await api.post('/vacancies', data)
      return { success: true, data: response.data.data }
    } catch (error) {
      console.error('Error creating vacancy:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка создания вакансии'
      }
    }
  }

  const updateVacancy = async (id, data) => {
    try {
      const response = await api.put(`/vacancies/${id}`, data)
      return { success: true, data: response.data.data }
    } catch (error) {
      console.error('Error updating vacancy:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка обновления вакансии'
      }
    }
  }

  const deleteVacancy = async (id) => {
    try {
      await api.delete(`/vacancies/${id}`)
      return { success: true }
    } catch (error) {
      console.error('Error deleting vacancy:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка удаления вакансии'
      }
    }
  }

  const toggleStatus = async (id) => {
    try {
      const response = await api.patch(`/vacancies/${id}`)
      return { success: true, data: response.data.data }
    } catch (error) {
      console.error('Error toggling status:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка изменения статуса'
      }
    }
  }

  return {
    vacancies,
    loading,
    meta,
    fetchMyVacancies,
    createVacancy,
    updateVacancy,
    deleteVacancy,
    toggleStatus
  }
}
