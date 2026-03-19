<template>
  <div class="create-vacancy">
    <h1>Создание вакансии</h1>

    <form class="vacancy-form" @submit.prevent="saveVacancy">
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
          <button type="button" class="btn-outline" @click="addSkill">+</button>
        </div>
        <div class="skills-list">
          <span v-for="skill in form.skills" :key="skill" class="skill-tag">
            {{ skill }}
            <button type="button" @click="removeSkill(skill)">✕</button>
          </span>
        </div>
      </div>

      <div class="form-actions">
        <router-link to="/employer/vacancies" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary">
          Опубликовать вакансию
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const form = reactive({
  title: '',
  salary_from: '',
  salary_to: '',
  city: '',
  experience: '1-3',
  description: '',
  skills: ['Vue.js', 'TypeScript']
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
}
</script>

<style scoped>
.create-vacancy {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 30px;
}

.vacancy-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

label {
  display: block;
  color: var(--text-secondary);
  margin-bottom: 8px;
  font-size: 14px;
}

.required {
  color: var(--color-danger);
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 16px;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  border-color: var(--color-primary);
  outline: none;
}

.skills-input {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
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
  padding: 6px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 14px;
}

.skill-tag button {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  padding: 0 2px;
}

.skill-tag button:hover {
  color: var(--color-danger);
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
}

.btn-primary {
  padding: 12px 30px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
}

.btn-outline {
  padding: 12px 30px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
