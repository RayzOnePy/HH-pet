<template>
  <div class="responses-page">
    <div class="page-header">
      <h1>Мои отклики</h1>
      <div class="stats" v-if="total > 0">
        {{ total }} {{ declension(total, ['отклик', 'отклика', 'откликов']) }}
      </div>
    </div>

    <div class="tabs">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        class="tab"
        :class="{ active: activeTab === tab.value }"
        @click="changeTab(tab.value)"
      >
        {{ tab.label }}
        <span class="tab-count" :class="tab.value">{{ tab.count }}</span>
      </button>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка откликов...</p>
    </div>

    <div v-else-if="responses.length > 0" class="responses-list">
      <div v-for="response in responses" :key="response.id" class="response-card">
        <div class="response-header">
          <div class="response-info">
            <h3>
              <router-link :to="`/applicant/vacancies/${response.vacancy?.id}`">
                {{ response.vacancy?.title }}
              </router-link>
            </h3>
            <div class="company-info">
              <span class="company-icon">🏢</span>
              <a
                :href="`/companies/${response.vacancy?.company?.id}`"
                target="_blank"
                rel="noopener noreferrer"
                class="company-link"
                @click.stop
              >
                {{ response.vacancy?.company?.name || 'Компания не указана' }}
              </a>
            </div>
          </div>
          <div class="response-status" :class="response.status">
            {{ getStatusText(response.status) }}
          </div>
        </div>

        <div class="response-meta">
          <span>📅 {{ formatDateFull(response.created_at) }}</span>
          <span>💰 {{ formatSalary(response.vacancy?.salary_from, response.vacancy?.salary_to) }}</span>
          <span>📍 {{ response.vacancy?.city || 'Город не указан' }}</span>
        </div>

        <div class="response-actions" v-if="response.status === 'pending'">
          <button class="btn-outline-small danger" @click="cancelResponse(response.id)">
            Отменить отклик
          </button>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <div class="empty-icon">📨</div>
      <h3>У вас пока нет откликов</h3>
      <p>Откликайтесь на вакансии, чтобы работодатели могли вас заметить</p>
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

const responses = ref([])
const loading = ref(false)
const total = ref(0)
const currentPage = ref(1)
const perPage = ref(15)
const totalPages = ref(1)
const activeTab = ref('all')
const counts = ref({
  all: 0,
  pending: 0,
  invited: 0,
  rejected: 0
})

const tabs = computed(() => [
  { value: 'all', label: 'Все', count: counts.value.all },
  { value: 'pending', label: 'На рассмотрении', count: counts.value.pending },
  { value: 'invited', label: 'Приглашения', count: counts.value.invited },
  { value: 'rejected', label: 'Отклонены', count: counts.value.rejected }
])

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

const declension = (number, words) => {
  const cases = [2, 0, 1, 1, 1, 2]
  const index = (number % 100 > 4 && number % 100 < 20) ? 2 : cases[Math.min(number % 10, 5)]
  return words[index]
}

const getStatusText = (status) => {
  const map = {
    'pending': 'На рассмотрении',
    'invited': 'Приглашение',
    'rejected': 'Отклонен'
  }
  return map[status] || status
}

const formatSalary = (from, to) => {
  if (!from && !to) return 'з/п не указана'
  if (from && to) return `${from.toLocaleString()} — ${to.toLocaleString()} ₽`
  if (from) return `от ${from.toLocaleString()} ₽`
  if (to) return `до ${to.toLocaleString()} ₽`
  return 'з/п не указана'
}

const formatDateFull = (date) => {
  if (!date) return ''
  const d = new Date(date)
  const now = new Date()
  const diff = Math.floor((now - d) / (1000 * 60 * 60 * 24))

  if (diff === 0) return 'сегодня'
  if (diff === 1) return 'вчера'
  if (diff < 7) return `${diff} дня назад`
  return d.toLocaleDateString('ru-RU')
}

const fetchResponses = async () => {
  loading.value = true

  try {
    const params = {
      page: currentPage.value,
      per_page: perPage.value
    }

    if (activeTab.value !== 'all') {
      params.status = activeTab.value
    }

    const response = await api.get('/applicant/responses', { params })
    responses.value = response.data.data
    total.value = response.data.meta.total
    totalPages.value = response.data.meta.last_page

    // Подсчет количества по статусам (можно добавить отдельный эндпоинт или вычислять на фронте)
    // Пока используем заглушку, в реальном проекте нужно добавить эндпоинт /applicant/responses/counts
  } catch (error) {
    console.error('Error fetching responses:', error)
  } finally {
    loading.value = false
  }
}

const fetchCounts = async () => {
  try {
    const response = await api.get('/applicant/responses/counts')
    counts.value = response.data.counts
  } catch (error) {
    console.error('Error fetching counts:', error)
  }
}

const changeTab = (tab) => {
  activeTab.value = tab
  currentPage.value = 1
  fetchResponses()
}

const cancelResponse = async (responseId) => {
  if (!confirm('Вы уверены, что хотите отменить отклик?')) return

  try {
    await api.delete(`/applicant/responses/${responseId}`)
    alert('Отклик отменен')
    fetchResponses()
    fetchCounts()
  } catch (error) {
    console.error('Error canceling response:', error)
    alert(error.response?.data?.message || 'Ошибка при отмене отклика')
  }
}

const goToPage = (page) => {
  currentPage.value = page
  fetchResponses()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  fetchResponses()
  fetchCounts()
})
</script>

<style scoped>
.responses-page {
  max-width: 800px;
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

.tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 10px;
  flex-wrap: wrap;
}

.tab {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  position: relative;
  font-size: 14px;
  transition: var(--transition-base);
}

.tab:hover {
  color: var(--color-primary);
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

.tab-count {
  padding: 2px 6px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
}

.tab-count.pending {
  background: rgba(255, 193, 7, 0.1);
  color: #ffc107;
}

.tab-count.invited {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.tab-count.rejected {
  background: rgba(244, 67, 54, 0.1);
  color: var(--color-danger);
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

.responses-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.response-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 20px;
  transition: var(--transition-base);
}

.response-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
}

.response-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 15px;
}

.response-info h3 {
  margin: 0 0 8px 0;
}

.response-info h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 18px;
  font-weight: 600;
}

.response-info h3 a:hover {
  color: var(--color-primary);
}

.company-info {
  display: flex;
  align-items: center;
  gap: 6px;
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

.response-status {
  padding: 4px 12px;
  border-radius: 30px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

.response-status.pending {
  background: rgba(255, 193, 7, 0.1);
  color: #ffc107;
}

.response-status.invited {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.response-status.rejected {
  background: rgba(244, 67, 54, 0.1);
  color: var(--color-danger);
}

.response-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 13px;
  margin-bottom: 16px;
  flex-wrap: wrap;
}

.response-actions {
  display: flex;
  gap: 12px;
  padding-top: 16px;
  border-top: 1px solid var(--border-color);
}

.btn-outline-small {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
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
  .responses-page {
    padding: 16px;
  }

  .response-header {
    flex-direction: column;
  }

  .response-meta {
    gap: 12px;
  }

  .tabs {
    gap: 4px;
  }

  .tab {
    padding: 6px 12px;
    font-size: 12px;
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
