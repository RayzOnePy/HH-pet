// frontend/src/stores/vacancyStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useVacancyStore = defineStore('vacancy', () => {
  // State
  const vacancies = ref([])
  const loading = ref(false)
  const meta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 15
  })
  const counts = ref({
    total: 0,
    active: 0,
    inactive: 0
  })
  const currentStatus = ref('active')
  const currentPage = ref(1)

  // Получить список вакансий (один запрос)
  const fetchVacancies = async (page = null, status = null) => {
    try {
      loading.value = true

      const targetPage = page !== null ? page : currentPage.value
      const targetStatus = status !== null ? status : currentStatus.value

      const params = { page: targetPage, per_page: 15 }
      if (targetStatus && targetStatus !== 'all') {
        params.status = targetStatus
      }

      const response = await api.get('/employer/vacancies', { params })

      vacancies.value = response.data.data
      meta.value = response.data.meta
      counts.value = response.data.counts  // счетчики приходят в ответе

      currentPage.value = targetPage
      currentStatus.value = targetStatus

      return { success: true }
    } catch (error) {
      console.error('Ошибка загрузки вакансий:', error)
      return { success: false }
    } finally {
      loading.value = false
    }
  }

  // Сменить таб
  const changeTab = async (status) => {
    await fetchVacancies(1, status)
  }

  // Сменить страницу
  const changePage = async (page) => {
    await fetchVacancies(page, currentStatus.value)
  }

  // Изменить статус вакансии
  const toggleStatus = async (id) => {
    try {
      await api.patch(`/employer/vacancies/${id}/toggle-status`)
      await fetchVacancies(currentPage.value, currentStatus.value)
      return { success: true }
    } catch (error) {
      console.error('Ошибка изменения статуса:', error)
      return { success: false }
    }
  }

  // Удалить вакансию
  const deleteVacancy = async (id) => {
    try {
      await api.delete(`/employer/vacancies/${id}`)
      await fetchVacancies(currentPage.value, currentStatus.value)
      return { success: true }
    } catch (error) {
      console.error('Ошибка удаления вакансии:', error)
      return { success: false }
    }
  }

  // Сбросить состояние
  const reset = () => {
    vacancies.value = []
    meta.value = { current_page: 1, last_page: 1, total: 0, per_page: 15 }
    counts.value = { total: 0, active: 0, inactive: 0 }
    currentStatus.value = 'active'
    currentPage.value = 1
  }

  return {
    vacancies,
    loading,
    meta,
    counts,
    currentStatus,
    fetchVacancies,
    changeTab,
    changePage,
    toggleStatus,
    deleteVacancy,
    reset
  }
})
