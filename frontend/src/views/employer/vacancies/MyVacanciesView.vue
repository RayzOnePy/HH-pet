<template>
  <div class="my-vacancies">
    <div class="page-header">
      <h1>Мои вакансии</h1>
      <router-link
        v-if="authStore.hasCompany"
        to="/employer/vacancies/create"
        class="btn-primary"
      >
        + Создать вакансию
      </router-link>
    </div>

    <!-- Если нет компании -->
    <div v-if="!authStore.hasCompany && !authStore.loading" class="no-company-block">
      <div class="no-company-icon">🏢</div>
      <h3>Сначала создайте компанию</h3>
      <p>Чтобы размещать вакансии, у вас должна быть компания.</p>
      <router-link to="/employer/company/create" class="btn-primary">
        Создать компанию
      </router-link>
    </div>

    <!-- Табы статусов (только если есть компания) -->
    <div v-else-if="authStore.hasCompany" class="tabs">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        class="tab"
        :class="{ active: vacancyStore.currentStatus === tab.value }"
        @click="vacancyStore.changeTab(tab.value)"
      >
        {{ tab.label }}
        <span class="count">{{ tab.count }}</span>
      </button>
    </div>

    <!-- Список вакансий -->
    <div
      v-if="authStore.hasCompany && !vacancyStore.loading && vacancyStore.vacancies.length > 0"
      class="vacancies-list"
    >
      <div v-for="vacancy in vacancyStore.vacancies" :key="vacancy.id" class="vacancy-card">
        <div class="vacancy-header">
          <div class="vacancy-info">
            <h3>
              <router-link :to="`/employer/vacancies/${vacancy.id}/edit`">
                {{ vacancy.title }}
              </router-link>
            </h3>
            <div class="vacancy-meta">
              <span>💰 {{ formatSalary(vacancy.salary_from) }} ₽</span>
              <span v-if="vacancy.salary_to"> - {{ formatSalary(vacancy.salary_to) }} ₽</span>
              <span>📍 {{ vacancy.city || 'Не указан' }}</span>
              <span>💼 {{ getExperienceText(vacancy.experience) }}</span>
              <span>📅 {{ formatDate(vacancy.created_at) }}</span>
            </div>
          </div>
          <div class="vacancy-stats">
            <span class="stat">👁️ {{ vacancy.views_count || 0 }}</span>
            <span class="stat">✉️ {{ vacancy.responses_count || 0 }}</span>
            <span class="stat">⭐ {{ vacancy.favorites_count || 0 }}</span>
          </div>
        </div>

        <div class="vacancy-description">
          {{ truncateText(vacancy.description, 120) }}
        </div>

        <div class="vacancy-footer">
          <span class="status-badge" :class="vacancy.status">
            {{ vacancy.status === 'active' ? 'Активна' : 'В архиве' }}
          </span>
          <div class="actions">
            <button
              class="btn-outline-small"
              @click="toggleStatusHandler(vacancy.id)"
            >
              {{ vacancy.status === 'active' ? '📦 В архив' : '📤 Активировать' }}
            </button>
            <router-link
              :to="`/employer/vacancies/${vacancy.id}/edit`"
              class="btn-outline-small"
            >
              ✏️ Редактировать
            </router-link>
            <button
              class="btn-outline-small danger"
              @click="confirmDelete(vacancy.id)"
            >
              🗑️ Удалить
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Пустое состояние -->
    <div
      v-else-if="authStore.hasCompany && !vacancyStore.loading && vacancyStore.vacancies.length === 0"
      class="empty-state"
    >
      <div class="empty-icon">📋</div>
      <h3>{{ emptyStateTitle }}</h3>
      <p>{{ emptyStateMessage }}</p>
      <div
        class="empty-actions"
        v-if="vacancyStore.currentStatus === 'all' || vacancyStore.currentStatus === 'active'"
      >
        <router-link to="/employer/vacancies/create" class="btn-primary">
          + Создать вакансию
        </router-link>
      </div>
    </div>

    <!-- Загрузка -->
    <div v-if="vacancyStore.loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка вакансий...</p>
    </div>

    <!-- Пагинация -->
    <div v-if="authStore.hasCompany && vacancyStore.meta.last_page > 1" class="pagination">
      <button
        class="page-btn"
        :disabled="vacancyStore.meta.current_page === 1"
        @click="vacancyStore.changePage(vacancyStore.meta.current_page - 1)"
      >
        ←
      </button>
      <button
        v-for="page in visiblePages"
        :key="page"
        class="page-btn"
        :class="{ active: vacancyStore.meta.current_page === page }"
        @click="vacancyStore.changePage(page)"
      >
        {{ page }}
      </button>
      <button
        class="page-btn"
        :disabled="vacancyStore.meta.current_page === vacancyStore.meta.last_page"
        @click="vacancyStore.changePage(vacancyStore.meta.current_page + 1)"
      >
        →
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '../../../stores/auth'
import { useVacancyStore } from '../../../stores/vacancyStore'

const authStore = useAuthStore()
const vacancyStore = useVacancyStore()

const tabs = computed(() => [
  { value: 'all', label: 'Все', count: vacancyStore.counts.total || 0 },
  { value: 'active', label: 'Активные', count: vacancyStore.counts.active || 0 },
  { value: 'inactive', label: 'В архиве', count: vacancyStore.counts.inactive || 0 }
])

const emptyStateTitle = computed(() => {
  if (vacancyStore.currentStatus === 'all') return 'У вас пока нет вакансий'
  if (vacancyStore.currentStatus === 'active') return 'Нет активных вакансий'
  return 'Нет вакансий в архиве'
})

const emptyStateMessage = computed(() => {
  if (vacancyStore.currentStatus === 'all') return 'Создайте первую вакансию, чтобы начать поиск сотрудников'
  if (vacancyStore.currentStatus === 'active') return 'У вас нет активных вакансий. Активируйте вакансии из архива или создайте новую'
  return 'У вас нет вакансий в архиве'
})

const visiblePages = computed(() => {
  const total = vacancyStore.meta.last_page
  const current = vacancyStore.meta.current_page
  const pages = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

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

const toggleStatusHandler = async (id) => {
  await vacancyStore.toggleStatus(id)
}

const confirmDelete = async (id) => {
  if (confirm('Вы уверены, что хотите удалить эту вакансию?')) {
    await vacancyStore.deleteVacancy(id)
  }
}

onMounted(async () => {
  if (!authStore.hasCompany) {
    return
  }

  if (vacancyStore.vacancies.length === 0) {
    await vacancyStore.fetchVacancies(1, 'active')
  }
})
</script>


<style scoped>
.my-vacancies {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

h1 {
  color: var(--text-primary);
  font-size: 28px;
}

.btn-primary {
  padding: 10px 20px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
  transition: var(--transition-base);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.no-company-block {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
  margin: 40px auto;
  max-width: 450px;
}

.no-company-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.no-company-block h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.no-company-block p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 10px;
}

.tab {
  padding: 8px 20px;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  position: relative;
  font-size: 16px;
  transition: var(--transition-base);
}

.tab.active {
  color: var(--color-primary);
}

.tab.active::after {
  content: '';
  position: absolute;
  bottom: -11px;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
}

.count {
  margin-left: 8px;
  padding: 2px 8px;
  background: var(--bg-secondary);
  border-radius: 20px;
  font-size: 12px;
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 20px;
  transition: var(--transition-base);
}

.vacancy-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.vacancy-info h3 {
  margin-bottom: 8px;
}

.vacancy-info h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 18px;
}

.vacancy-info h3 a:hover {
  color: var(--color-primary);
}

.vacancy-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  flex-wrap: wrap;
}

.vacancy-stats {
  display: flex;
  gap: 15px;
  color: var(--text-secondary);
  font-size: 14px;
}

.stat {
  background: var(--bg-secondary);
  padding: 4px 10px;
  border-radius: 20px;
}

.vacancy-description {
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 16px;
}

.vacancy-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 16px;
  border-top: 1px solid var(--border-color);
}

.status-badge {
  padding: 4px 12px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 500;
}

.status-badge.active {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.status-badge.inactive {
  background: var(--bg-secondary);
  color: var(--text-secondary);
}

.actions {
  display: flex;
  gap: 10px;
}

.btn-outline-small {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  font-size: 13px;
  transition: var(--transition-base);
}

.btn-outline-small:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.btn-outline-small.danger:hover {
  border-color: var(--color-danger);
  color: var(--color-danger);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
  margin: 40px auto;
  max-width: 450px;
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

.empty-actions {
  margin-top: 24px;
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
</style>
