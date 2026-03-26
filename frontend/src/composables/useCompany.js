import { ref, computed } from 'vue'
import api from '../services/api'

export function useCompany() {
  const company = ref(null)
  const loading = ref(true)

  const hasCompany = computed(() => !!company.value)

  const fetchCompany = async () => {
    loading.value = true
    try {
      const response = await api.get('/employer/company')
      company.value = response.data.data
    } catch (error) {
      if (error.response?.status === 404) {
        company.value = null
      } else {
        console.error('Error fetching company:', error)
      }
    } finally {
      loading.value = false
    }
  }

  return {
    company,
    loading,
    hasCompany,
    fetchCompany
  }
}
