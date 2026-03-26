<!-- frontend/src/views/shared/CompanyView.vue -->
<template>
  <div class="company-page">
    <div class="container">
      <button class="back-btn" @click="goBack">
        ← Назад
      </button>

      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Загрузка информации о компании...</p>
      </div>

      <div v-else-if="company" class="company-content">
        <div class="company-header">
          <div class="company-logo">
            <span class="logo-icon">{{ company.logo_url ? '🖼️' : '🏢' }}</span>
          </div>
          <div class="company-title">
            <h1>{{ company.name }}</h1>
            <div class="company-badges">
              <span v-if="company.is_verified" class="verified-badge">✓ Проверенная компания</span>
            </div>
          </div>
        </div>

        <div class="company-description">
          <h3>О компании</h3>
          <p>{{ company.description || 'Информация о компании отсутствует' }}</p>
        </div>

        <div class="company-vacancies">
          <h3>Вакансии компании</h3>
          <div v-if="vacanciesLoading" class="vacancies-loading">
            <div class="spinner-small"></div>
            <p>Загрузка вакансий...</p>
          </div>
          <div v-else-if="vacancies.length > 0" class="vacancies-list">
            <div
              v-for="vacancy in vacancies"
              :key="vacancy.id"
              class="vacancy-card"
              @click="openVacancy(vacancy.id)"
            >
              <div class="vacancy-header">
                <div class="vacancy-info">
                  <h4>{{ vacancy.title }}</h4>
                </div>
                <div class="salary">
                  {{ formatSalary(vacancy.salary_from, vacancy.salary_to) }}
                </div>
              </div>
              <div class="vacancy-tags">
                <span class="tag">{{ getExperienceText(vacancy.experience) }}</span>
                <span class="tag">{{ vacancy.city || 'Город не указан' }}</span>
                <span v-if="vacancy.work_schedules?.length" class="tag">
                  🕒 {{ vacancy.work_schedules.map(s => s.name).join(', ') }}
                </span>
              </div>
              <div class="vacancy-meta">
                <span>📅 {{ formatDate(vacancy.created_at) }}</span>
                <span>👁️ {{ vacancy.views_count || 0 }} просмотров</span>
              </div>
              <div class="vacancy-footer">
                <a
                  :href="`/vacancies/${vacancy.id}`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="btn-outline"
                  @click.stop
                >
                  Подробнее →
                </a>
              </div>
            </div>
          </div>
          <div v-else class="no-vacancies">
            <p>У компании пока нет активных вакансий</p>
          </div>

          <div v-if="vacanciesMeta.last_page > 1" class="pagination">
            <button
              class="page-btn"
              :disabled="vacanciesCurrentPage === 1"
              @click="changeVacanciesPage(vacanciesCurrentPage - 1)"
            >
              ←
            </button>
            <button
              v-for="page in vacanciesVisiblePages"
              :key="page"
              class="page-btn"
              :class="{ active: vacanciesCurrentPage === page }"
              @click="changeVacanciesPage(page)"
            >
              {{ page }}
            </button>
            <button
              class="page-btn"
              :disabled="vacanciesCurrentPage === vacanciesMeta.last_page"
              @click="changeVacanciesPage(vacanciesCurrentPage + 1)"
            >
              →
            </button>
          </div>
        </div>
      </div>

      <div v-else class="not-found">
        <div class="empty-icon">🏢</div>
        <h3>Компания не найдена</h3>
        <p>Возможно, она была удалена или не существует</p>
        <router-link to="/" class="btn-primary">
          Вернуться на главную
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'

const route = useRoute()
const router = useRouter()

const company = ref(null)
const loading = ref(false)
const vacancies = ref([])
const vacanciesLoading = ref(false)
const vacanciesCurrentPage = ref(1)
const vacanciesPerPage = ref(10)
const vacanciesMeta = ref({
  current_page: 1,
  last_page: 1,
  total: 0
})

const vacanciesVisiblePages = computed(() => {
  const total = vacanciesMeta.value.last_page
  const current = vacanciesCurrentPage.value
  const pages = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

const fetchCompany = async () => {
  const id = route.params.id
  if (!id) return

  loading.value = true

  try {
    const response = await api.get(`/companies/${id}`)
    company.value = response.data.data
  } catch (error) {
    console.error('Error fetching company:', error)
    if (error.response?.status === 404) {
      company.value = null
    }
  } finally {
    loading.value = false
  }
}

const fetchCompanyVacancies = async () => {
  if (!company.value) return

  vacanciesLoading.value = true

  try {
    const params = {
      company_id: company.value.id,
      page: vacanciesCurrentPage.value,
      per_page: vacanciesPerPage.value,
      status: 'active'
    }

    const response = await api.get('/vacancies', { params })
    vacancies.value = response.data.data
    vacanciesMeta.value = response.data.meta
  } catch (error) {
    console.error('Error fetching company vacancies:', error)
  } finally {
    vacanciesLoading.value = false
  }
}

const changeVacanciesPage = (page) => {
  if (page < 1 || page > vacanciesMeta.value.last_page) return
  vacanciesCurrentPage.value = page
  fetchCompanyVacancies()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const openVacancy = (id) => {
  window.open(`/vacancies/${id}`, '_blank')
}

const formatSalary = (from, to) => {
  if (!from && !to) return 'з/п не указана'
  if (from && to) return `${from.toLocaleString()} — ${to.toLocaleString()} ₽`
  if (from) return `от ${from.toLocaleString()} ₽`
  if (to) return `до ${to.toLocaleString()} ₽`
  return 'з/п не указана'
}

const getExperienceText = (experience) => {
  const map = {
    'no': 'Нет опыта',
    '1-3': '1-3 года',
    '3-6': '3-6 лет',
    '6+': 'Более 6 лет'
  }
  return map[experience] || 'Не указан'
}

const formatDate = (date) => {
  const d = new Date(date)
  const now = new Date()
  const diff = Math.floor((now - d) / (1000 * 60 * 60 * 24))

  if (diff === 0) return 'сегодня'
  if (diff === 1) return 'вчера'
  if (diff < 7) return `${diff} дня назад`
  return d.toLocaleDateString('ru-RU')
}

const goBack = () => {
  router.back()
}

onMounted(async () => {
  await fetchCompany()
  if (company.value) {
    await fetchCompanyVacancies()
  }
})
</script>

<style scoped>
.company-page {
  min-height: 100vh;
  padding: 40px 0;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 20px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  font-size: 14px;
  margin-bottom: 24px;
  padding: 8px 0;
  transition: var(--transition-base);
}

.back-btn:hover {
  color: var(--color-primary);
}

.loading-state {
  text-align: center;
  padding: 80px 20px;
}

.spinner {
  width: 48px;
  height: 48px;
  border: 3px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 20px;
}

.spinner-small {
  width: 32px;
  height: 32px;
  border: 2px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  margin: 0 auto 12px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.company-content {
  background: var(--bg-card);
  border-radius: 24px;
  padding: 32px;
}

.company-header {
  display: flex;
  gap: 24px;
  align-items: center;
  margin-bottom: 32px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border-color);
}

.company-logo {
  width: 80px;
  height: 80px;
  background: var(--bg-secondary);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  flex-shrink: 0;
}

.company-title h1 {
  color: var(--text-primary);
  font-size: 28px;
  margin: 0 0 8px 0;
}

.company-badges {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.verified-badge {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.company-description {
  margin-bottom: 40px;
}

.company-description h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 16px;
}

.company-description p {
  color: var(--text-secondary);
  line-height: 1.8;
}

.company-vacancies h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 20px;
}

.vacancies-loading {
  text-align: center;
  padding: 40px;
  background: var(--bg-secondary);
  border-radius: 16px;
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.vacancy-card {
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 20px;
  transition: var(--transition-base);
  cursor: pointer;
}

.vacancy-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 10px;
}

.vacancy-info h4 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: var(--text-primary);
}

.salary {
  color: var(--color-primary);
  font-size: 16px;
  font-weight: 600;
  white-space: nowrap;
}

.vacancy-tags {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 12px;
}

.tag {
  padding: 4px 10px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 11px;
  color: var(--text-secondary);
}

.vacancy-meta {
  display: flex;
  gap: 16px;
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 12px;
  flex-wrap: wrap;
}

.vacancy-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 12px;
  border-top: 1px solid var(--border-color);
}

.btn-outline {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 13px;
  transition: var(--transition-base);
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.no-vacancies {
  text-align: center;
  padding: 40px;
  background: var(--bg-secondary);
  border-radius: 16px;
  color: var(--text-secondary);
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 24px;
  flex-wrap: wrap;
}

.page-btn {
  min-width: 36px;
  height: 36px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-secondary);
  cursor: pointer;
  transition: var(--transition-base);
}

.page-btn:hover:not(:disabled) {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.page-btn.active {
  background: var(--gradient-primary);
  color: var(--text-dark);
  border: none;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.not-found {
  text-align: center;
  padding: 80px 20px;
  background: var(--bg-card);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.not-found h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.not-found p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.btn-primary {
  padding: 12px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  transition: var(--transition-base);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

@media (max-width: 768px) {
  .company-page {
    padding: 20px 0;
  }

  .company-content {
    padding: 20px;
  }

  .company-header {
    flex-direction: column;
    text-align: center;
  }

  .vacancy-header {
    flex-direction: column;
  }

  .salary {
    white-space: normal;
  }
}
</style>
