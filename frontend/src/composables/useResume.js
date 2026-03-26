import { ref, computed } from 'vue'
import api from '../services/api'

export function useResume() {
  const resume = ref(null)
  const loading = ref(false)
  const saving = ref(false)
  const error = ref(null)

  const hasResume = computed(() => !!resume.value)
  const isActive = computed(() => resume.value?.is_active ?? false)

  const fetchMyResume = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get('/applicant/resume')
      resume.value = response.data.data
      return { success: true, data: resume.value }
    } catch (err) {
      if (err.response?.status === 404) {
        resume.value = null
        return { success: false, notFound: true }
      }
      error.value = err.message || 'Ошибка загрузки резюме'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const createResume = async (data) => {
    saving.value = true
    error.value = null

    try {
      const response = await api.post('/applicant/resume', data)
      resume.value = response.data.data
      return { success: true, data: resume.value }
    } catch (err) {
      error.value = err.message || 'Ошибка создания резюме'
      return { success: false, error: error.value, errors: err.errors }
    } finally {
      saving.value = false
    }
  }

  const updateResume = async (data) => {
    saving.value = true
    error.value = null

    try {
      const response = await api.put('/applicant/resume', data)
      resume.value = response.data.data
      return { success: true, data: resume.value }
    } catch (err) {
      error.value = err.message || 'Ошибка обновления резюме'
      return { success: false, error: error.value, errors: err.errors }
    } finally {
      saving.value = false
    }
  }

  const toggleActive = async () => {
    try {
      const response = await api.patch('/applicant/resume/toggle-active')
      if (resume.value) {
        resume.value.is_active = response.data.data.is_active
      }
      return { success: true, is_active: response.data.data.is_active }
    } catch (err) {
      error.value = err.message || 'Ошибка изменения статуса'
      return { success: false, error: error.value }
    }
  }

  const clearResume = () => {
    resume.value = null
    error.value = null
  }

  return {
    resume,
    loading,
    saving,
    error,
    hasResume,
    isActive,
    fetchMyResume,
    createResume,
    updateResume,
    toggleActive,
    clearResume
  }
}
