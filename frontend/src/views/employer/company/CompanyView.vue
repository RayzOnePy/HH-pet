<template>
  <div class="company-page">
    <div class="page-header">
      <h1>Моя компания</h1>
      <router-link
        v-if="company && !loading"
        to="/employer/company/edit"
        class="btn-outline"
      >
        ✏️ Редактировать
      </router-link>
    </div>

    <!-- Состояние загрузки -->
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка...</p>
    </div>

    <!-- Состояние: компании нет -->
    <div v-else-if="!company" class="no-company">
      <div class="no-company-icon">🏢</div>
      <h2>У вас ещё нет компании</h2>
      <p class="no-company-description">
        Чтобы размещать вакансии, вам необходимо создать компанию.
        Это займёт всего несколько минут.
      </p>

      <div class="no-company-actions">
        <router-link to="/employer/company/create" class="btn-primary btn-large">
          + Создать компанию
        </router-link>
      </div>

      <div class="hint">
        <span class="hint-icon">💡</span>
        <span class="hint-text">
          После создания компании вы сможете размещать вакансии и искать сотрудников
        </span>
      </div>
    </div>

    <!-- Состояние: компания есть -->
    <div v-else class="company-content">
      <div class="company-header">
        <div class="company-logo">
          <img v-if="company.logo_url" :src="company.logo_url" alt="Логотип">
          <span v-else>🏢</span>
        </div>
        <div class="company-info">
          <h2>{{ company.name }}</h2>
          <p class="company-description">{{ company.description }}</p>
          <div class="company-status">
            <span class="status-badge" :class="{ verified: company.is_verified }">
              {{ company.is_verified ? '✅ Верифицирована' : '⏳ На модерации' }}
            </span>
          </div>
        </div>
      </div>

      <div class="company-stats">
        <div class="stat-card">
          <div class="stat-value">{{ company.vacancies_count || 0 }}</div>
          <div class="stat-label">Вакансий</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../../services/api'

const company = ref(null)
const loading = ref(true)

const fetchCompany = async () => {
  loading.value = true
  try {
    const response = await api.get('/my-company')
    company.value = response.data.data
  } catch (error) {
    if (error.response?.status === 404) {
      company.value = null
    } else {
      console.error('Ошибка загрузки компании:', error)
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCompany()
})
</script>

<style scoped>
.company-page {
  max-width: 800px;
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

.btn-outline {
  padding: 10px 24px;
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

.loading-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
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

.no-company {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
  max-width: 500px;
  margin: 40px auto;
}

.no-company-icon {
  font-size: 80px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.no-company h2 {
  color: var(--text-primary);
  font-size: 24px;
  margin-bottom: 15px;
}

.no-company-description {
  color: var(--text-secondary);
  margin-bottom: 30px;
  line-height: 1.6;
}

.btn-primary {
  padding: 14px 32px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 40px;
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

.btn-large {
  padding: 16px 40px;
  font-size: 18px;
}

.hint {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  color: var(--text-secondary);
  font-size: 14px;
  background: var(--bg-secondary);
  padding: 12px 20px;
  border-radius: 40px;
  max-width: fit-content;
  margin: 20px auto 0;
}

.company-content {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.company-header {
  display: flex;
  gap: 30px;
  margin-bottom: 30px;
  padding-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
}

.company-logo {
  width: 100px;
  height: 100px;
  background: var(--bg-secondary);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 50px;
  border: 2px solid var(--color-primary);
  overflow: hidden;
}

.company-logo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.company-info {
  flex: 1;
}

.company-info h2 {
  color: var(--text-primary);
  font-size: 24px;
  margin-bottom: 10px;
}

.company-description {
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 15px;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  background: var(--bg-secondary);
  border-radius: 30px;
  font-size: 13px;
  color: var(--text-secondary);
}

.status-badge.verified {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.company-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
}

.stat-card {
  background: var(--bg-secondary);
  border-radius: 12px;
  padding: 20px;
  text-align: center;
}

.stat-value {
  font-size: 32px;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: 5px;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 14px;
}
</style>
