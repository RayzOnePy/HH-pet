import { ref } from 'vue'
import api from '../services/api'

export function useDictionaries() {
  const educationDegrees = ref([])
  const employmentTypes = ref([])
  const workSchedules = ref([])
  const loading = ref(false)

  const fetchEducationDegrees = async () => {
    try {
      const response = await api.get('/dictionaries/education-degrees')
      educationDegrees.value = response.data.data
      return educationDegrees.value
    } catch (error) {
      console.error('Error fetching education degrees:', error)
      return []
    }
  }

  const fetchEmploymentTypes = async () => {
    try {
      const response = await api.get('/dictionaries/employment-types')
      employmentTypes.value = response.data.data
      return employmentTypes.value
    } catch (error) {
      console.error('Error fetching employment types:', error)
      return []
    }
  }

  const fetchWorkSchedules = async () => {
    try {
      const response = await api.get('/dictionaries/work-schedules')
      workSchedules.value = response.data.data
      return workSchedules.value
    } catch (error) {
      console.error('Error fetching work schedules:', error)
      return []
    }
  }

  const fetchAll = async () => {
    loading.value = true
    await Promise.all([
      fetchEducationDegrees(),
      fetchEmploymentTypes(),
      fetchWorkSchedules()
    ])
    loading.value = false
  }

  return {
    educationDegrees,
    employmentTypes,
    workSchedules,
    loading,
    fetchEducationDegrees,
    fetchEmploymentTypes,
    fetchWorkSchedules,
    fetchAll
  }
}
