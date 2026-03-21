<template>
  <div class="create-vacancy">
    <div class="page-header">
      <h1>Создание вакансии</h1>
      <router-link to="/employer/vacancies" class="back-link">
        ← К списку вакансий
      </router-link>
    </div>

    <div v-if="!hasCompany" class="no-company-block">
      <div class="no-company-icon">🏢</div>
      <h3>Сначала создайте компанию</h3>
      <p>Чтобы размещать вакансии, у вас должна быть компания.</p>
      <router-link to="/employer/company/create" class="btn-primary">
        Создать компанию
      </router-link>
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

      <div class="form-actions">
        <router-link to="/employer/vacancies" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Публикация...' : 'Опубликовать вакансию' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useVacancies } from '../../../composables/useVacancies'
import { useCompany } from '../../../composables/useCompany'

const router = useRouter()
const { createVacancy } = useVacancies()
const { hasCompany, fetchCompany } = useCompany()

const loading = ref(false)
const errors = ref({})

const form = reactive({
  title: '',
  description: '',
  salary_from: '',
  salary_to: '',
  experience: '1-3',
  city: ''
})

const submitForm = async () => {
  loading.value = true
  errors.value = {}

  // Очищаем пустые значения
  const data = {
    ...form,
    salary_from: form.salary_from ? Number(form.salary_from) : null,
    salary_to: form.salary_to ? Number(form.salary_to) : null
  }

  const result = await createVacancy(data)

  if (result.success) {
    router.push('/employer/vacancies')
  } else {
    if (result.message) {
      alert(result.message)
    }
  }

  loading.value = false
}

onMounted(() => {
  fetchCompany()
})
</script>

<style scoped>
.create-vacancy {
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
