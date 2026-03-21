<template>
  <div class="vacancies-page">
    <h1>Вакансии</h1>
    <p class="page-description">Найдите работу мечты</p>

    <!-- Поиск и фильтры -->
    <div class="search-section">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Должность, компания, ключевые слова..."
          class="search-input"
          @input="handleSearch"
        >
        <button class="search-btn">🔍</button>
      </div>
    </div>

    <!-- Список вакансий -->
    <div class="vacancies-list">
      <div v-for="vacancy in vacancies" :key="vacancy.id" class="vacancy-card">
        <div class="vacancy-header">
          <div>
            <h3>
              <router-link :to="`/vacancies/${vacancy.id}`">
                {{ vacancy.title }}
              </router-link>
            </h3>
            <div class="company-name">{{ vacancy.company?.name || 'Компания' }}</div>
          </div>
          <div class="salary">
            {{ formatSalary(vacancy.salary_from) }}
            {{ vacancy.salary_to ? `- ${formatSalary(vacancy.salary_to)}` : '' }} ₽
          </div>
        </div>

        <div class="vacancy-meta">
          <span>📍 {{ vacancy.city || 'Не указан' }}</span>
          <span>💼 {{ getExperienceText(vacancy.experience) }}</span>
          <span>📅 {{ formatDate(vacancy.created_at) }}</span>
        </div>

        <div class="vacancy-description">
          {{ truncateText(vacancy.description, 150) }}
        </div>

        <div class="vacancy-footer">
          <router-link :to="`/vacancies/${vacancy.id}`" class="btn-outline">
            Подробнее
          </router-link>
        </div>
      </div>
    </div>

    <!-- Пагинация -->
    <div class="pagination" v-if="meta.last_page > 1">
      <button
        class="page-btn"
        :disabled="meta.current_page === 1"
        @click="changePage(meta.current_page - 1)"
      >
        ←
      </button>
      <button
        v-for="page in visiblePages"
        :key="page"
        class="page-btn"
        :class="{ active: meta.current_page === page }"
        @click="changePage(page)"
      >
        {{ page }}
      </button>
      <button
        class="page-btn"
        :disabled="meta.current_page === meta.last_page"
        @click="changePage(meta.current_page + 1)"
      >
        →
      </button>
    </div>

    <!-- Загрузка -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка вакансий...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'

const vacancies = ref([])
const loading = ref(true)
const searchQuery = ref('')
const meta = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0
})

const visiblePages = computed(() => {
  const total = meta.value.last_page
  const current = meta.value.current_page
  const pages = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

const fetchVacancies = async () => {
  loading.value = true
  try {
    const params = {
      page: meta.value.current_page,
      per_page: 15
    }

    if (searchQuery.value) {
      params.search = searchQuery.value
    }

    const response = await api.get('/vacancies', { params })
    vacancies.value = response.data.data
    meta.value = response.data.meta
  } catch (error) {
    console.error('Error fetching vacancies:', error)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  meta.value.current_page = 1
  fetchVacancies()
}

const changePage = (page) => {
  if (page < 1 || page > meta.value.last_page) return
  meta.value.current_page = page
  fetchVacancies()
}

const formatSalary = (salary) => {
  if (!salary) return 'не указана'
  return salary.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
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
  return new Date(date).toLocaleDateString('ru-RU')
}

const truncateText = (text, length) => {
  if (!text) return ''
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}

onMounted(() => {
  fetchVacancies()
})
</script>

<style scoped>
.vacancies-page {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 8px;
}

.page-description {
  color: var(--text-secondary);
  margin-bottom: 30px;
}

.search-section {
  margin-bottom: 30px;
}

.search-box {
  position: relative;
  max-width: 500px;
}

.search-input {
  width: 100%;
  padding: 14px 20px;
  padding-right: 50px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  font-size: 16px;
  transition: var(--transition-base);
}

.search-input:focus {
  border-color: var(--color-primary);
  outline: none;
  box-shadow: var(--shadow-primary);
}

.search-btn {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  font-size: 18px;
  color: var(--text-secondary);
}

.search-btn:hover {
  color: var(--color-primary);
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  transition: var(--transition-base);
}

.vacancy-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  transform: translateX(4px);
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.vacancy-header h3 {
  margin-bottom: 4px;
}

.vacancy-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 20px;
}

.vacancy-header h3 a:hover {
  color: var(--color-primary);
}

.company-name {
  color: var(--text-secondary);
  font-size: 14px;
}

.salary {
  color: var(--color-primary);
  font-size: 18px;
  font-weight: 600;
}

.vacancy-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 12px;
  flex-wrap: wrap;
}

.vacancy-description {
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 16px;
}

.vacancy-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 16px;
  border-top: 1px solid var(--border-color);
}

.btn-outline {
  padding: 8px 20px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  transition: var(--transition-base);
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 40px;
  flex-wrap: wrap;
}

.page-btn {
  width: 40px;
  height: 40px;
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

.loading-state {
  text-align: center;
  padding: 60px 20px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
