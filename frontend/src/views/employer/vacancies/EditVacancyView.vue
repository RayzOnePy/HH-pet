<template>
  <div class="edit-vacancy">
    <div class="page-header">
      <h1>Редактирование вакансии</h1>
      <router-link to="/employer/vacancies" class="back-link">
        ← К списку вакансий
      </router-link>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка вакансии...</p>
    </div>

    <form v-else class="vacancy-form" @submit.prevent="submitForm">
      <div class="form-group">
        <label>Название вакансии <span class="required">*</span></label>
        <input
          v-model="form.title"
          type="text"
          placeholder="Например: Senior Frontend Developer"
          class="form-input"
          :class="{ error: errors.title }"
        >
        <span v-if="errors.title" class="error-text">{{ errors.title }}</span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Зарплата от</label>
          <input
            v-model="form.salary_from"
            type="number"
            placeholder="100 000"
            class="form-input"
          >
        </div>
        <div class="form-group">
          <label>Зарплата до</label>
          <input
            v-model="form.salary_to"
            type="number"
            placeholder="300 000"
            class="form-input"
          >
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Город <span class="required">*</span></label>
          <input
            v-model="form.city"
            type="text"
            placeholder="Москва"
            class="form-input"
            :class="{ error: errors.city }"
          >
          <span v-if="errors.city" class="error-text">{{ errors.city }}</span>
        </div>
        <div class="form-group">
          <label>Опыт работы <span class="required">*</span></label>
          <select v-model="form.experience" class="form-select" :class="{ error: errors.experience }">
            <option value="no">Нет опыта</option>
            <option value="1-3">1-3 года</option>
            <option value="3-6">3-6 лет</option>
            <option value="6+">Более 6 лет</option>
          </select>
          <span v-if="errors.experience" class="error-text">{{ errors.experience }}</span>
        </div>
      </div>

      <div class="form-group">
        <label>Статус вакансии</label>
        <div class="status-options">
          <label class="status-option">
            <input type="radio" v-model="form.status" value="active">
            <span class="status-badge active">Активна</span>
          </label>
          <label class="status-option">
            <input type="radio" v-model="form.status" value="inactive">
            <span class="status-badge inactive">В архиве</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label>Описание вакансии <span class="required">*</span></label>
        <textarea
          v-model="form.description"
          rows="8"
          placeholder="Подробное описание вакансии, требования, условия..."
          class="form-textarea"
          :class="{ error: errors.description }"
        ></textarea>
        <span v-if="errors.description" class="error-text">{{ errors.description }}</span>
      </div>

      <div class="stats-section" v-if="vacancyStats">
        <h3>Статистика вакансии</h3>
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-label">Просмотров</span>
            <span class="stat-value">{{ vacancyStats.views_count || 0 }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Откликов</span>
            <span class="stat-value">{{ vacancyStats.responses_count || 0 }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">В избранном</span>
            <span class="stat-value">{{ vacancyStats.favorites_count || 0 }}</span>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <router-link to="/employer/vacancies" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary" :disabled="saving">
          {{ saving ? 'Сохранение...' : 'Сохранить изменения' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../../../services/api'

const router = useRouter()
const route = useRoute()
const vacancyId = route.params.id

const loading = ref(true)
const saving = ref(false)
const errors = ref({})
const vacancyStats = ref(null)

const form = reactive({
  title: '',
  description: '',
  salary_from: '',
  salary_to: '',
  experience: '1-3',
  city: '',
  status: 'active'
})

const loadVacancy = async () => {
  try {
    const response = await api.get(`/vacancies/${vacancyId}`)
    const vacancy = response.data.data

    form.title = vacancy.title
    form.description = vacancy.description
    form.salary_from = vacancy.salary_from
    form.salary_to = vacancy.salary_to
    form.experience = vacancy.experience
    form.city = vacancy.city
    form.status = vacancy.status

    vacancyStats.value = {
      views_count: vacancy.views_count,
      responses_count: vacancy.responses_count,
      favorites_count: vacancy.favorites_count
    }
  } catch (error) {
    console.error('Error loading vacancy:', error)
    router.push('/employer/vacancies')
  } finally {
    loading.value = false
  }
}

const submitForm = async () => {
  saving.value = true
  errors.value = {}

  const data = {
    ...form,
    salary_from: form.salary_from ? Number(form.salary_from) : null,
    salary_to: form.salary_to ? Number(form.salary_to) : null
  }

  try {
    await api.put(`/vacancies/${vacancyId}`, data)
    router.push('/employer/vacancies')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      alert(error.response?.data?.message || 'Ошибка при сохранении')
    }
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  loadVacancy()
})
</script>

<style scoped>
.edit-vacancy {
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

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
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

.vacancy-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.form-group {
  margin-bottom: 24px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 24px;
}

label {
  display: block;
  color: var(--text-secondary);
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 500;
}

.required {
  color: var(--color-danger);
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  color: var(--text-primary);
  font-size: 16px;
  transition: var(--transition-base);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  border-color: var(--color-primary);
  outline: none;
  box-shadow: var(--shadow-primary);
}

.form-input.error,
.form-select.error,
.form-textarea.error {
  border-color: var(--color-danger);
}

.form-textarea {
  resize: vertical;
  min-height: 150px;
}

.error-text {
  display: block;
  color: var(--color-danger);
  font-size: 12px;
  margin-top: 5px;
}

.status-options {
  display: flex;
  gap: 20px;
}

.status-option {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.status-option input[type="radio"] {
  accent-color: var(--color-primary);
  width: 18px;
  height: 18px;
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

.stats-section {
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  margin: 30px 0;
}

.stats-section h3 {
  color: var(--text-primary);
  margin-bottom: 15px;
  font-size: 16px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 15px;
}

.stat-item {
  background: var(--bg-tertiary);
  border-radius: 8px;
  padding: 15px;
  text-align: center;
}

.stat-label {
  display: block;
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 5px;
}

.stat-value {
  display: block;
  color: var(--color-primary);
  font-size: 24px;
  font-weight: 600;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

.btn-primary {
  padding: 12px 30px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 40px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-outline {
  padding: 12px 30px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
