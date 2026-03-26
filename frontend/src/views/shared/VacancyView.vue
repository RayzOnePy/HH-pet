<template>
  <div class="vacancy-page">
    <div class="container">
      <!-- Кнопка назад -->
      <button class="back-btn" @click="goBack">
        ← Назад к списку
      </button>

      <!-- Состояние загрузки -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Загрузка вакансии...</p>
      </div>

      <!-- Вакансия -->
      <div v-else-if="vacancy" class="vacancy-card">
        <!-- Хедер -->
        <div class="vacancy-header">
          <div class="vacancy-title-section">
            <h1>{{ vacancy.title }}</h1>
            <div class="company-info">
              <span class="company-name">{{ vacancy.company?.name || 'Компания не указана' }}</span>
              <span v-if="vacancy.company?.is_verified" class="verified-badge">✓ Проверено</span>
            </div>
          </div>
        </div>

        <!-- Зарплата -->
        <div class="salary-block">
          <div class="salary">{{ formatSalary(vacancy.salary_from, vacancy.salary_to) }}</div>
        </div>

        <!-- Информационная сетка -->
        <div class="info-grid">
          <div class="info-item">
            <span class="info-icon">📍</span>
            <div>
              <div class="info-label">Город</div>
              <div class="info-value">{{ vacancy.city || 'Не указан' }}</div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">💼</span>
            <div>
              <div class="info-label">Опыт работы</div>
              <div class="info-value">{{ getExperienceText(vacancy.experience) }}</div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">📅</span>
            <div>
              <div class="info-label">Дата публикации</div>
              <div class="info-value">{{ formatDateFull(vacancy.created_at) }}</div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">👁️</span>
            <div>
              <div class="info-label">Просмотры</div>
              <div class="info-value">{{ vacancy.views_count || 0 }}</div>
            </div>
          </div>
        </div>

        <!-- График работы -->
        <div v-if="vacancy.work_schedules?.length" class="section">
          <h3>График работы</h3>
          <div class="tags">
            <span v-for="schedule in vacancy.work_schedules" :key="schedule.id" class="tag">
              {{ schedule.name }}
            </span>
          </div>
        </div>

        <!-- Описание вакансии -->
        <div class="section">
          <h3>Описание вакансии</h3>
          <div class="description">{{ vacancy.description }}</div>
        </div>

        <!-- Информация о компании -->
        <div v-if="vacancy.company" class="company-section">
          <h3>О компании</h3>
          <div class="company-info-block">
            <div class="company-logo">{{ vacancy.company.logo_url ? '🖼️' : '🏢' }}</div>
            <div class="company-details">
              <h4>{{ vacancy.company.name }}</h4>
              <p v-if="vacancy.company.description">{{ truncateText(vacancy.company.description, 200) }}</p>
              <router-link :to="`/companies/${vacancy.company.id}`" class="company-link">
                Подробнее о компании →
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Вакансия не найдена -->
      <div v-else class="not-found">
        <div class="empty-icon">🔍</div>
        <h3>Вакансия не найдена</h3>
        <p>Возможно, она была удалена или скрыта</p>
        <router-link to="/vacancies" class="btn-primary">
          Вернуться к списку
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api.js'

const route = useRoute()
const router = useRouter()

const vacancy = ref(null)
const loading = ref(false)

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

const formatDateFull = (date) => {
  if (!date) return ''
  const d = new Date(date)
  return d.toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const truncateText = (text, length) => {
  if (!text) return ''
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}

const fetchVacancy = async () => {
  const id = route.params.id
  if (!id) return

  loading.value = true

  try {
    const response = await api.get(`/vacancies/${id}`)
    vacancy.value = response.data.data
  } catch (error) {
    console.error('Error fetching vacancy:', error)
    if (error.response?.status === 404) {
      vacancy.value = null
    }
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  fetchVacancy()
})
</script>

<style scoped>
.vacancy-page {
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

@keyframes spin {
  to { transform: rotate(360deg); }
}

.vacancy-card {
  background: var(--bg-card);
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.vacancy-header {
  margin-bottom: 24px;
}

.vacancy-title-section h1 {
  color: var(--text-primary);
  font-size: 28px;
  margin: 0 0 12px 0;
}

.company-info {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.company-name {
  color: var(--text-secondary);
  font-size: 16px;
}

.verified-badge {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.salary-block {
  background: var(--bg-secondary);
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 24px;
  text-align: center;
}

.salary {
  font-size: 32px;
  font-weight: 700;
  color: var(--color-primary);
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
  padding: 20px;
  background: var(--bg-secondary);
  border-radius: 16px;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.info-icon {
  font-size: 24px;
}

.info-label {
  font-size: 12px;
  color: var(--text-secondary);
  margin-bottom: 2px;
}

.info-value {
  font-size: 16px;
  font-weight: 500;
  color: var(--text-primary);
}

.section {
  margin-bottom: 32px;
}

.section h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 16px;
}

.tags {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.tag {
  padding: 6px 14px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  font-size: 13px;
  color: var(--text-secondary);
}

.description {
  color: var(--text-secondary);
  line-height: 1.8;
  font-size: 15px;
  white-space: pre-wrap;
}

.company-section {
  margin-top: 32px;
  padding-top: 32px;
  border-top: 1px solid var(--border-color);
}

.company-section h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 16px;
}

.company-info-block {
  display: flex;
  gap: 20px;
  padding: 20px;
  background: var(--bg-secondary);
  border-radius: 16px;
}

.company-logo {
  width: 64px;
  height: 64px;
  background: var(--bg-tertiary);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  flex-shrink: 0;
}

.company-details h4 {
  color: var(--text-primary);
  font-size: 18px;
  margin: 0 0 8px 0;
}

.company-details p {
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 12px;
}

.company-link {
  color: var(--color-primary);
  text-decoration: none;
  font-size: 14px;
}

.company-link:hover {
  text-decoration: underline;
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
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

@media (max-width: 768px) {
  .vacancy-page {
    padding: 20px 0;
  }

  .vacancy-card {
    padding: 20px;
  }

  .info-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .company-info-block {
    flex-direction: column;
    text-align: center;
  }

  .company-logo {
    margin: 0 auto;
  }

  .salary {
    font-size: 24px;
  }
}
</style>
