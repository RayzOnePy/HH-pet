<template>
  <div class="favorites-page">
    <div class="page-header">
      <h1>Избранные вакансии</h1>
      <div class="stats" v-if="total > 0">
        {{ total }} {{ declension(total, ['вакансия', 'вакансии', 'вакансий']) }}
      </div>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка избранных вакансий...</p>
    </div>

    <div v-else-if="vacancies.length > 0" class="favorites-list">
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
          <span>✉️ {{ vacancy.responses_count || 0 }} откликов</span>
          <span>⭐ Добавлено {{ formatDate(vacancy.pivot?.created_at || vacancy.created_at) }}</span>
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
          <div class="actions" @click.stop>
            <button
              class="icon-btn"
              :class="{ active: true }"
              @click="removeFromFavorites(vacancy)"
              :disabled="removingId === vacancy.id"
              title="Удалить из избранного"
            >
              <span v-if="removingId === vacancy.id" class="loading-spinner-small"></span>
              <span v-else>⭐</span>
            </button>
            <button class="btn-primary-small" @click="respondToVacancy(vacancy.id)">
              Откликнуться
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <div class="empty-icon">⭐</div>
      <h3>У вас пока нет избранных вакансий</h3>
      <p>Добавляйте вакансии в избранное, чтобы не потерять интересные предложения</p>
      <router-link to="/applicant/vacancies" class="btn-primary">
        Найти вакансии
      </router-link>
    </div>

    <div v-if="totalPages > 1" class="pagination">
      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
      >
        ←
      </button>
      <button
        v-for="page in visiblePages"
        :key="page"
        class="page-btn"
        :class="{ active: currentPage === page }"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>
      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="goToPage(currentPage + 1)"
      >
        →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../../services/api'
import { useAuthStore } from '../../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const vacancies = ref([])
const loading = ref(false)
const total = ref(0)
const currentPage = ref(1)
const perPage = ref(15)
const totalPages = ref(1)
const removingId = ref(null)

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)

  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

const openVacancy = (id) => {
  window.open(`/applicant/vacancies/${id}`, '_blank')
}

const declension = (number, words) => {
  const cases = [2, 0, 1, 1, 1, 2]
  const index = (number % 100 > 4 && number % 100 < 20) ? 2 : cases[Math.min(number % 10, 5)]
  return words[index]
}

const formatSalary = (from, to) => {
  if (!from && !to) return 'з/п не указана'
  if (from && to) return `${from.toLocaleString()} - ${to.toLocaleString()} ₽`
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
  if (!date) return ''
  const d = new Date(date)
  const now = new Date()
  const diff = Math.floor((now - d) / (1000 * 60 * 60 * 24))

  if (diff === 0) return 'сегодня'
  if (diff === 1) return 'вчера'
  if (diff < 7) return `${diff} дня назад`
  return d.toLocaleDateString('ru-RU')
}

const fetchFavorites = async () => {
  loading.value = true

  try {
    const params = {
      page: currentPage.value,
      per_page: perPage.value
    }

    const response = await api.get('/applicant/favorites', { params })
    vacancies.value = response.data.data
    total.value = response.data.meta.total
    totalPages.value = response.data.meta.last_page
  } catch (error) {
    console.error('Error fetching favorites:', error)
  } finally {
    loading.value = false
  }
}

const removeFromFavorites = async (vacancy) => {
  removingId.value = vacancy.id

  try {
    await api.delete(`/applicant/favorites/${vacancy.id}`)

    const index = vacancies.value.findIndex(v => v.id === vacancy.id)
    if (index !== -1) {
      vacancies.value.splice(index, 1)
      total.value--
    }
  } catch (error) {
    console.error('Error removing from favorites:', error)
    alert(error.response?.data?.message || 'Ошибка при удалении из избранного')
  } finally {
    removingId.value = null
  }
}

const respondToVacancy = async (vacancyId) => {
  if (!authStore.isLoggedIn) {
    alert('Войдите в систему, чтобы откликнуться на вакансию')
    return
  }

  try {
    await api.post('/applicant/responses', { vacancy_id: vacancyId })
    alert('Отклик успешно отправлен!')
  } catch (error) {
    console.error('Error responding to vacancy:', error)
    alert(error.response?.data?.message || 'Ошибка при отправке отклика')
  }
}

const goToPage = (page) => {
  currentPage.value = page
  fetchFavorites()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  fetchFavorites()
})
</script>

<style scoped>
.favorites-page {
  max-width: 900px;
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

.loading-spinner-small {
  width: 20px;
  height: 20px;
  border: 2px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  display: inline-block;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
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

.favorites-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
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
  margin-bottom: 12px;
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
  margin-bottom: 12px;
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

.actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  opacity: 0.8;
  transition: var(--transition-base);
  padding: 4px;
  min-width: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-btn:hover:not(:disabled) {
  opacity: 1;
  transform: scale(1.1);
}

.icon-btn.active {
  opacity: 1;
  color: #ffc107;
}

.icon-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.btn-primary-small {
  padding: 8px 20px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-primary-small:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
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

@media (max-width: 768px) {
  .favorites-page {
    padding: 16px;
  }

  .vacancy-header {
    flex-direction: column;
  }

  .salary {
    white-space: normal;
  }

  .vacancy-meta {
    gap: 12px;
  }

  .vacancy-footer {
    flex-direction: column;
    align-items: stretch;
  }

  .actions {
    justify-content: flex-end;
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
