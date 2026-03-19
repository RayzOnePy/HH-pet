<template>
  <div class="edit-vacancy">
    <div class="page-header">
      <h1>Редактирование вакансии</h1>
      <router-link to="/employer/vacancies" class="back-link">
        ← К списку вакансий
      </router-link>
    </div>

    <form class="vacancy-form" @submit.prevent="saveVacancy">
      <!-- Статус вакансии -->
      <div class="form-group">
        <label>Статус вакансии</label>
        <div class="status-toggles">
          <label class="status-option">
            <input type="radio" v-model="form.status" value="active">
            <span class="status-badge active">Активная</span>
          </label>
          <label class="status-option">
            <input type="radio" v-model="form.status" value="inactive">
            <span class="status-badge inactive">В архиве</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label>Название вакансии <span class="required">*</span></label>
        <input
          v-model="form.title"
          type="text"
          placeholder="Например: Senior Frontend Developer"
          class="form-input"
        >
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
          <label>Город</label>
          <input
            v-model="form.city"
            type="text"
            placeholder="Москва"
            class="form-input"
          >
        </div>
        <div class="form-group">
          <label>Опыт работы</label>
          <select v-model="form.experience" class="form-select">
            <option value="no">Нет опыта</option>
            <option value="1-3">1-3 года</option>
            <option value="3-6">3-6 лет</option>
            <option value="6+">Более 6 лет</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Описание вакансии <span class="required">*</span></label>
        <textarea
          v-model="form.description"
          rows="8"
          placeholder="Подробное описание вакансии, требования, условия..."
          class="form-textarea"
        ></textarea>
      </div>

      <div class="form-group">
        <label>Навыки</label>
        <div class="skills-input">
          <input
            v-model="newSkill"
            type="text"
            placeholder="Vue.js"
            class="form-input"
            @keydown.enter.prevent="addSkill"
          >
          <button type="button" class="btn-outline" @click="addSkill">+ Добавить</button>
        </div>
        <div class="skills-list">
          <span v-for="skill in form.skills" :key="skill" class="skill-tag">
            {{ skill }}
            <button type="button" @click="removeSkill(skill)">✕</button>
          </span>
        </div>
      </div>

      <!-- Дополнительные параметры -->
      <div class="form-group">
        <label>Дополнительные параметры</label>
        <div class="checkbox-group">
          <label class="checkbox">
            <input type="checkbox" v-model="form.remote">
            <span>Удаленная работа</span>
          </label>
          <label class="checkbox">
            <input type="checkbox" v-model="form.business_trip">
            <span>Возможны командировки</span>
          </label>
          <label class="checkbox">
            <input type="checkbox" v-model="form.relocation">
            <span>Готовы к переезду</span>
          </label>
        </div>
      </div>

      <!-- Статистика (только для просмотра) -->
      <div class="stats-section">
        <h3>Статистика вакансии</h3>
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-label">Просмотров</span>
            <span class="stat-value">1,234</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Откликов</span>
            <span class="stat-value">48</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">В избранном</span>
            <span class="stat-value">23</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Приглашений</span>
            <span class="stat-value">12</span>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <router-link to="/employer/vacancies" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary">
          Сохранить изменения
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Имитация загрузки данных вакансии
const form = reactive({
  status: 'active',
  title: 'Senior Frontend Developer',
  salary_from: '250000',
  salary_to: '350000',
  city: 'Москва',
  experience: '3-6',
  description: 'Мы ищем опытного Frontend разработчика для работы над крупным проектом...',
  skills: ['Vue.js', 'TypeScript', 'Pinia', 'Vite'],
  remote: true,
  business_trip: false,
  relocation: false
})

const newSkill = ref('')

const addSkill = () => {
  if (newSkill.value && !form.skills.includes(newSkill.value)) {
    form.skills.push(newSkill.value)
    newSkill.value = ''
  }
}

const removeSkill = (skill) => {
  form.skills = form.skills.filter(s => s !== skill)
}

const saveVacancy = () => {
  console.log('Saving vacancy:', form)
  // Здесь будет сохранение
  router.push('/employer/vacancies')
}
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
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
}

.vacancy-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
}

.form-group {
  margin-bottom: 25px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 25px;
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

.form-textarea {
  resize: vertical;
  min-height: 150px;
}

/* Status toggles */
.status-toggles {
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
  padding: 6px 16px;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 500;
}

.status-badge.active {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
  border: 1px solid var(--color-primary);
}

.status-badge.inactive {
  background: var(--bg-secondary);
  color: var(--text-secondary);
  border: 1px solid var(--border-color);
}

/* Skills */
.skills-input {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.skills-input .form-input {
  flex: 1;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.skill-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: var(--bg-secondary);
  border: 1px solid var(--color-primary);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 14px;
  transition: var(--transition-base);
}

.skill-tag:hover {
  background: var(--bg-tertiary);
  box-shadow: var(--shadow-primary);
}

.skill-tag button {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  font-size: 16px;
  padding: 0 4px;
  transition: var(--transition-base);
}

.skill-tag button:hover {
  color: var(--color-danger);
  transform: scale(1.2);
}

/* Checkbox group */
.checkbox-group {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.checkbox {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  color: var(--text-primary);
}

.checkbox input[type="checkbox"] {
  accent-color: var(--color-primary);
  width: 18px;
  height: 18px;
}

/* Statistics section */
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
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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

/* Buttons */
.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

.btn-primary {
  padding: 14px 32px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 40px;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: var(--transition-base);
  text-decoration: none;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.btn-outline {
  padding: 14px 32px;
  background: transparent;
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 16px;
  cursor: pointer;
  transition: var(--transition-base);
  text-decoration: none;
  text-align: center;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .skills-input {
    flex-direction: column;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-primary,
  .btn-outline {
    width: 100%;
    text-align: center;
  }
}
</style>
