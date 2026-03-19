<template>
  <div class="edit-resume">
    <div class="page-header">
      <h1>Редактирование резюме</h1>
      <router-link to="/applicant/resume" class="back-link">
        ← Назад
      </router-link>
    </div>

    <form class="resume-form" @submit.prevent="saveResume">
      <!-- Личная информация -->
      <div class="form-section">
        <h2>Личная информация</h2>

        <div class="form-row">
          <div class="form-group">
            <label>Имя <span class="required">*</span></label>
            <input v-model="form.first_name" type="text" class="form-input">
          </div>
          <div class="form-group">
            <label>Фамилия <span class="required">*</span></label>
            <input v-model="form.last_name" type="text" class="form-input">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Город</label>
            <input v-model="form.city" type="text" class="form-input">
          </div>
          <div class="form-group">
            <label>Желаемая зарплата</label>
            <input v-model="form.salary" type="number" class="form-input">
          </div>
        </div>
      </div>

      <!-- Контакты -->
      <div class="form-section">
        <h2>Контакты</h2>

        <div class="form-row">
          <div class="form-group">
            <label>Телефон</label>
            <input v-model="form.phone" type="tel" class="form-input">
          </div>
          <div class="form-group">
            <label>Telegram</label>
            <input v-model="form.telegram" type="text" class="form-input">
          </div>
        </div>
      </div>

      <!-- О себе -->
      <div class="form-section">
        <h2>О себе</h2>
        <div class="form-group">
          <label>Краткое описание</label>
          <textarea v-model="form.about" rows="4" class="form-textarea"></textarea>
        </div>
      </div>

      <!-- Навыки -->
      <div class="form-section">
        <h2>Навыки</h2>
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

      <!-- Опыт работы -->
      <div class="form-section">
        <h2>Опыт работы</h2>
        <div v-for="(exp, index) in form.experience" :key="index" class="experience-item">
          <div class="form-row">
            <div class="form-group">
              <label>Должность</label>
              <input v-model="exp.title" type="text" class="form-input">
            </div>
            <div class="form-group">
              <label>Компания</label>
              <input v-model="exp.company" type="text" class="form-input">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>С</label>
              <input v-model="exp.start_date" type="month" class="form-input">
            </div>
            <div class="form-group">
              <label>По</label>
              <input v-model="exp.end_date" type="month" class="form-input">
            </div>
          </div>
          <div class="form-group">
            <label>Обязанности и достижения</label>
            <textarea v-model="exp.description" rows="3" class="form-textarea"></textarea>
          </div>
          <button type="button" class="btn-outline-small" @click="removeExperience(index)">
            Удалить
          </button>
        </div>
        <button type="button" class="btn-outline" @click="addExperience">
          + Добавить опыт работы
        </button>
      </div>

      <!-- Образование -->
      <div class="form-section">
        <h2>Образование</h2>
        <div v-for="(edu, index) in form.education" :key="index" class="education-item">
          <div class="form-row">
            <div class="form-group">
              <label>Учебное заведение</label>
              <input v-model="edu.institution" type="text" class="form-input">
            </div>
            <div class="form-group">
              <label>Степень</label>
              <input v-model="edu.degree" type="text" class="form-input">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Год окончания</label>
              <input v-model="edu.year" type="number" class="form-input">
            </div>
          </div>
          <button type="button" class="btn-outline-small" @click="removeEducation(index)">
            Удалить
          </button>
        </div>
        <button type="button" class="btn-outline" @click="addEducation">
          + Добавить образование
        </button>
      </div>

      <div class="form-actions">
        <router-link to="/applicant/resume" class="btn-outline">
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

const newSkill = ref('')

const form = reactive({
  first_name: 'Александр',
  last_name: 'Петров',
  city: 'Москва',
  salary: 250000,
  phone: '+7 (999) 123-45-67',
  telegram: '@alex_dev',
  about: 'Опытный Frontend разработчик...',
  skills: ['Vue.js', 'React', 'TypeScript'],
  experience: [
    {
      title: 'Senior Frontend Developer',
      company: 'TechCorp',
      start_date: '2022-01',
      end_date: '',
      description: 'Разработка SPA приложений...'
    }
  ],
  education: [
    {
      institution: 'МГУ',
      degree: 'Бакалавр прикладной математики',
      year: 2019
    }
  ]
})

const addSkill = () => {
  if (newSkill.value && !form.skills.includes(newSkill.value)) {
    form.skills.push(newSkill.value)
    newSkill.value = ''
  }
}

const removeSkill = (skill) => {
  form.skills = form.skills.filter(s => s !== skill)
}

const addExperience = () => {
  form.experience.push({
    title: '',
    company: '',
    start_date: '',
    end_date: '',
    description: ''
  })
}

const removeExperience = (index) => {
  form.experience.splice(index, 1)
}

const addEducation = () => {
  form.education.push({
    institution: '',
    degree: '',
    year: ''
  })
}

const removeEducation = (index) => {
  form.education.splice(index, 1)
}

const saveResume = () => {
  console.log('Saving resume:', form)
}
</script>

<style scoped>
.edit-resume {
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

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.resume-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.form-section {
  margin-bottom: 40px;
  padding-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
}

.form-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.form-section h2 {
  color: var(--text-primary);
  font-size: 20px;
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  color: var(--text-secondary);
  margin-bottom: 8px;
  font-size: 14px;
}

.required {
  color: var(--color-danger);
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 16px;
}

.form-input:focus,
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
  border: 1px solid var(--color-primary);
  border-radius: 30px;
  color: var(--text-primary);
}

.skill-tag button {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
}

.skill-tag button:hover {
  color: var(--color-danger);
}

.experience-item,
.education-item {
  background: var(--bg-secondary);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  position: relative;
}

.btn-outline-small {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  cursor: pointer;
  margin-top: 10px;
}

.btn-outline {
  padding: 12px 24px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  cursor: pointer;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
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
</style>
