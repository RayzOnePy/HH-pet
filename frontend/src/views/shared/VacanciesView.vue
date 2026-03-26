<template>
  <div class="vacancies-page">
    <div class="page-header">
      <h1>Вакансии</h1>
      <div class="stats" v-if="meta.total > 0">
        Найдено {{ meta.total }} вакансий
      </div>
    </div>

    <div class="search-section">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          @input="debouncedSearch"
          placeholder="Должность, компания, ключевые слова..."
          class="search-input"
        >
        <button class="search-btn" @click="handleSearch">🔍</button>
      </div>

      <button class="filter-toggle" @click="showFilters = !showFilters">
        <span class="filter-icon">⚙️</span>
        <span>Фильтры</span>
        <span v-if="activeFiltersCount > 0" class="filter-badge">{{ activeFiltersCount }}</span>
      </button>
    </div>

    <Transition name="slide">
      <div v-if="showFilters" class="filters-panel">
        <div class="filters-grid">
          <div class="filter-group">
            <label>Зарплата от (₽)</label>
            <input
              type="number"
              v-model="filters.salary_from"
              placeholder="100 000"
              @input="applyFilters"
            >
          </div>
          <div class="filter-group">
            <label>Зарплата до (₽)</label>
            <input
              type="number"
              v-model="filters.salary_to"
              placeholder="300 000"
              @input="applyFilters"
            >
          </div>
          <div class="filter-group">
            <label>Опыт работы</label>
            <select v-model="filters.experience" @change="applyFilters">
              <option value="">Любой</option>
              <option value="no">Нет опыта</option>
              <option value="1-3">1-3 года</option>
              <option value="3-6">3-6 лет</option>
              <option value="6+">Более 6 лет</option>
            </select>
          </div>
          <div class="filter-group">
            <label>График работы</label>
            <select v-model="filters.work_schedule_id" @change="applyFilters">
              <option value="">Любой</option>
              <option v-for="schedule in workSchedules" :key="schedule.id" :value="schedule.id">
                {{ schedule.name }}
              </option>
            </select>
          </div>
          <div class="filter-group">
            <label>Сортировка</label>
            <select v-model="filters.sort_by" @change="applyFilters">
              <option value="created_at">По дате (новые)</option>
              <option value="salary_from">По зарплате (возрастание)</option>
              <option value="-salary_from">По зарплате (убывание)</option>
            </select>
          </div>
        </div>
        <div class="filters-actions">
          <button class="btn-outline" @click="resetFilters">Сбросить все</button>
        </div>
      </div>
    </Transition>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
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
            <h3>{{ vacancy.title }}</h3>
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
          <div class="company-info" @click.stop>
            <span class="company-icon">🏢</span>
            <a
              :href="`/companies/${vacancy.company?.id}`"
              target="_blank"
              rel="noopener noreferrer"
              class="company-link"
              @click.stop
            >
              {{ vacancy.company?.name || 'Компания не указана' }}
            </a>
            <span v-if="vacancy.company?.is_verified" class="verified-badge">✓ Проверено</span>
          </div>
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

    <div v-else class="empty-state">
      <div class="empty-icon">🔍</div>
      <h3>Ничего не найдено</h3>
      <p>Попробуйте изменить параметры поиска или фильтры</p>
      <button class="btn-outline" @click="resetAll">Сбросить фильтры</button>
    </div>

    <div v-if="meta.last_page > 1" class="pagination">
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { debounce } from 'lodash'
import api from '../../services/api.js'
import { useDictionaries } from '../../composables/useDictionaries.js'

const { workSchedules, fetchWorkSchedules } = useDictionaries()

const vacancies = ref([])
const loading = ref(true)
const searchQuery = ref('')
const showFilters = ref(false)
const meta = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0
})

const filters = ref({
  salary_from: '',
  salary_to: '',
  experience: '',
  work_schedule_id: '',
  sort_by: 'created_at'
})

const activeFiltersCount = computed(() => {
  let count = 0
  if (filters.value.salary_from) count++
  if (filters.value.salary_to) count++
  if (filters.value.experience) count++
  if (filters.value.work_schedule_id) count++
  if (filters.value.sort_by !== 'created_at') count++
  return count
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

const openVacancy = (id) => {
  window.open(`/vacancies/${id}`, '_blank')
}

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

    if (filters.value.salary_from) {
      params.salary_from = filters.value.salary_from
    }

    if (filters.value.salary_to) {
      params.salary_to = filters.value.salary_to
    }

    if (filters.value.experience) {
      params.experience = filters.value.experience
    }

    if (filters.value.work_schedule_id) {
      params.work_schedule_id = filters.value.work_schedule_id
    }

    if (filters.value.sort_by) {
      const sortField = filters.value.sort_by === '-salary_from' ? 'salary_from' : filters.value.sort_by
      const sortOrder = filters.value.sort_by === '-salary_from' ? 'desc' : (filters.value.sort_by === 'salary_from' ? 'asc' : 'desc')
      params.sort_by = sortField
      params.sort_order = sortOrder
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

const applyFilters = () => {
  meta.value.current_page = 1
  fetchVacancies()
}

const resetFilters = () => {
  filters.value = {
    salary_from: '',
    salary_to: '',
    experience: '',
    work_schedule_id: '',
    sort_by: 'created_at'
  }
  applyFilters()
}

const resetAll = () => {
  searchQuery.value = ''
  resetFilters()
}

const handleSearch = () => {
  meta.value.current_page = 1
  fetchVacancies()
}

const debouncedSearch = debounce(() => {
  handleSearch()
}, 500)

const changePage = (page) => {
  if (page < 1 || page > meta.value.last_page) return
  meta.value.current_page = page
  fetchVacancies()
  window.scrollTo({ top: 0, behavior: 'smooth' })
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

onMounted(async () => {
  await fetchWorkSchedules()
  fetchVacancies()
})
</script>

<style scoped>
.vacancies-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

h1 {
  color: var(--text-primary);
  font-size: 28px;
  margin: 0;
}

.stats {
  color: var(--text-secondary);
  font-size: 14px;
  background: var(--bg-secondary);
  padding: 6px 12px;
  border-radius: 20px;
}

.search-section {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.search-box {
  flex: 1;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 14px 24px;
  padding-right: 60px;
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
  padding: 12px 20px;
  cursor: pointer;
  font-size: 20px;
  color: var(--text-secondary);
  transition: var(--transition-base);
}

.search-btn:hover {
  color: var(--color-primary);
}

.filter-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0 24px;
  background: var(--bg-tertiary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  cursor: pointer;
  transition: var(--transition-base);
  position: relative;
}

.filter-toggle:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.filter-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: var(--color-primary);
  color: var(--text-dark);
  font-size: 10px;
  font-weight: bold;
  min-width: 18px;
  height: 18px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 4px;
}

.filters-panel {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 30px;
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  color: var(--text-secondary);
  font-size: 13px;
  font-weight: 500;
}

.filter-group input,
.filter-group select {
  padding: 10px 14px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  color: var(--text-primary);
  font-size: 14px;
  transition: var(--transition-base);
}

.filter-group input:focus,
.filter-group select:focus {
  border-color: var(--color-primary);
  outline: none;
}

.filters-actions {
  display: flex;
  justify-content: flex-end;
  padding-top: 12px;
  border-top: 1px solid var(--border-color);
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 20px;
}

.vacancy-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 24px;
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
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}

.vacancy-info h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: var(--text-primary);
}

.salary {
  color: var(--color-primary);
  font-size: 20px;
  font-weight: 700;
  white-space: nowrap;
}

.vacancy-tags {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 16px;
}

.tag {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 12px;
  color: var(--text-secondary);
}

.vacancy-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 13px;
  margin-bottom: 16px;
  flex-wrap: wrap;
}

.vacancy-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 16px;
  border-top: 1px solid var(--border-color);
  flex-wrap: wrap;
  gap: 15px;
}

.company-info {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.company-icon {
  font-size: 14px;
  color: var(--text-secondary);
}

.company-link {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 14px;
  transition: var(--transition-base);
}

.company-link:hover {
  color: var(--color-primary);
  text-decoration: underline;
}

.verified-badge {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 500;
}

.btn-outline {
  padding: 8px 20px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  transition: var(--transition-base);
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
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

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
  margin: 40px auto;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.empty-state h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 40px;
  flex-wrap: wrap;
}

.page-btn {
  min-width: 40px;
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

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (max-width: 768px) {
  .vacancies-page {
    padding: 16px;
  }

  .vacancy-header {
    flex-direction: column;
    margin-bottom: 16px;
  }

  .salary {
    white-space: normal;
  }

  .filters-grid {
    grid-template-columns: 1fr;
  }

  .search-section {
    flex-direction: column;
  }

  .filter-toggle {
    justify-content: center;
  }

  .vacancy-footer {
    flex-direction: column;
    align-items: stretch;
  }

  .company-info {
    justify-content: center;
  }

  .btn-outline {
    justify-content: center;
  }

  .pagination {
    gap: 4px;
  }

  .page-btn {
    min-width: 36px;
    height: 36px;
  }
}
</style>
