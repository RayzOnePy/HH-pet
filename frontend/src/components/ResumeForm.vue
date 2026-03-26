<template>
  <form class="resume-form" @submit.prevent="$emit('submit', formData)">
    <div class="form-section">
      <h3>Основная информация</h3>

      <div class="form-group">
        <label>Название резюме <span class="required">*</span></label>
        <input
          v-model="formData.title"
          type="text"
          placeholder="Например: Senior Frontend Developer"
          class="form-input"
          :class="{ error: getFieldError('title') }"
        >
        <span v-if="getFieldError('title')" class="error-text">{{ getFieldError('title') }}</span>
      </div>

      <div class="form-group">
        <label>Желаемая зарплата</label>
        <input
          v-model.number="formData.salary"
          type="number"
          placeholder="150 000"
          class="form-input"
          :class="{ error: getFieldError('salary') }"
        >
        <span v-if="getFieldError('salary')" class="error-text">{{ getFieldError('salary') }}</span>
      </div>
    </div>

    <!-- График работы -->
    <div class="form-section">
      <h3>График работы</h3>
      <div class="schedule-grid">
        <div
          v-for="schedule in workSchedules"
          :key="schedule.id"
          class="schedule-card"
          :class="{ active: isScheduleSelected(schedule.id) }"
          @click="toggleSchedule(schedule.id)"
        >
          <div class="schedule-icon">{{ getScheduleIcon(schedule.name) }}</div>
          <div class="schedule-name">{{ schedule.name }}</div>
          <div class="schedule-check">
            <svg v-if="isScheduleSelected(schedule.id)" width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Контакты -->
    <div class="form-section">
      <div class="section-header">
        <h3>Контакты</h3>
        <button type="button" class="btn-add" @click="addContact">
          + Добавить контакт
        </button>
      </div>

      <div v-if="formData.contacts.length === 0" class="empty-section">
        <p class="empty-text">Нет добавленных контактов</p>
      </div>

      <div v-for="(contact, index) in formData.contacts" :key="`contact-${index}`" class="contact-row">
        <div class="form-group">
          <select v-model="contact.type" class="form-select">
            <option value="phone">📱 Телефон</option>
            <option value="email">📧 Email</option>
            <option value="telegram">💬 Telegram</option>
            <option value="whatsapp">💬 WhatsApp</option>
          </select>
        </div>
        <div class="form-group flex-grow">
          <input
            v-model="contact.value"
            :type="getInputType(contact.type)"
            class="form-input"
            :class="{ error: getNestedError('contacts', index, 'value') }"
            :placeholder="getContactPlaceholder(contact.type)"
          >
          <span v-if="getNestedError('contacts', index, 'value')" class="error-text">
            {{ getNestedError('contacts', index, 'value') }}
          </span>
          <small class="hint-text">{{ getContactHint(contact.type) }}</small>
        </div>
        <button type="button" class="btn-remove" @click="removeContact(index)" title="Удалить">
          🗑️
        </button>
      </div>
    </div>

    <!-- Навыки -->
    <div class="form-section">
      <div class="section-header">
        <h3>Навыки</h3>
        <button type="button" class="btn-add" @click="addSkill">
          + Добавить навык
        </button>
      </div>

      <div v-if="formData.skills.length === 0" class="empty-section">
        <p class="empty-text">Нет добавленных навыков</p>
      </div>

      <div v-for="(skill, index) in formData.skills" :key="`skill-${index}`" class="skill-row">
        <div class="form-group flex-grow">
          <input
            v-model="skill.skill"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('skills', index, 'skill') }"
            placeholder="Название навыка, например: PHP, Laravel, Vue.js"
          >
          <span v-if="getNestedError('skills', index, 'skill')" class="error-text">
            {{ getNestedError('skills', index, 'skill') }}
          </span>
        </div>
        <div class="form-group skill-level-select">
          <select v-model="skill.level" class="form-select">
            <option value="beginner">🌱 Начальный</option>
            <option value="intermediate">📚 Средний</option>
            <option value="advanced">🚀 Продвинутый</option>
          </select>
          <span v-if="getNestedError('skills', index, 'level')" class="error-text">
            {{ getNestedError('skills', index, 'level') }}
          </span>
        </div>
        <button type="button" class="btn-remove" @click="removeSkill(index)" title="Удалить">
          🗑️
        </button>
      </div>
    </div>

    <!-- Опыт работы -->
    <div class="form-section">
      <div class="section-header">
        <h3>Опыт работы</h3>
        <button type="button" class="btn-add" @click="addWork">
          + Добавить место работы
        </button>
      </div>

      <div v-if="formData.work_experiences.length === 0" class="empty-section">
        <p class="empty-text">Нет добавленных мест работы</p>
      </div>

      <div v-for="(work, index) in formData.work_experiences" :key="`work-${index}`" class="work-card">
        <div class="card-header">
          <h4>Место работы #{{ index + 1 }}</h4>
          <button type="button" class="btn-remove-card" @click="removeWork(index)" title="Удалить">
            🗑️ Удалить
          </button>
        </div>

        <div class="form-group">
          <label>Должность <span class="required">*</span></label>
          <input
            v-model="work.title"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('work_experiences', index, 'title') }"
            placeholder="Например: Senior PHP Developer"
          >
          <span v-if="getNestedError('work_experiences', index, 'title')" class="error-text">
            {{ getNestedError('work_experiences', index, 'title') }}
          </span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Дата начала</label>
            <input
              v-model="work.start_date"
              type="date"
              class="form-input"
              :class="{ error: getNestedError('work_experiences', index, 'start_date') }"
            >
            <span v-if="getNestedError('work_experiences', index, 'start_date')" class="error-text">
              {{ getNestedError('work_experiences', index, 'start_date') }}
            </span>
          </div>
          <div class="form-group">
            <label>Дата окончания</label>
            <input
              v-model="work.end_date"
              type="date"
              class="form-input"
              :disabled="work.is_current"
              :class="{ error: getNestedError('work_experiences', index, 'end_date') }"
            >
            <span v-if="getNestedError('work_experiences', index, 'end_date')" class="error-text">
              {{ getNestedError('work_experiences', index, 'end_date') }}
            </span>
          </div>
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input v-model="work.is_current" type="checkbox" @change="onCurrentWorkChange(work)">
            <span>Работаю по настоящее время</span>
          </label>
        </div>

        <div class="form-group">
          <label>Обязанности и достижения</label>
          <textarea
            v-model="work.experience_summary"
            rows="4"
            class="form-textarea"
            :class="{ error: getNestedError('work_experiences', index, 'experience_summary') }"
            placeholder="Опишите ваши обязанности, достижения, технологии, с которыми работали..."
          ></textarea>
          <span v-if="getNestedError('work_experiences', index, 'experience_summary')" class="error-text">
            {{ getNestedError('work_experiences', index, 'experience_summary') }}
          </span>
        </div>
      </div>
    </div>

    <!-- Образование -->
    <div class="form-section">
      <div class="section-header">
        <h3>Образование</h3>
        <button type="button" class="btn-add" @click="addEducation">
          + Добавить образование
        </button>
      </div>

      <div v-if="formData.educations.length === 0" class="empty-section">
        <p class="empty-text">Нет добавленных образований</p>
      </div>

      <div v-for="(edu, index) in formData.educations" :key="`edu-${index}`" class="education-card">
        <div class="card-header">
          <h4>Образование #{{ index + 1 }}</h4>
          <button type="button" class="btn-remove-card" @click="removeEducation(index)" title="Удалить">
            🗑️ Удалить
          </button>
        </div>

        <div class="form-group">
          <label>Учебное заведение <span class="required">*</span></label>
          <input
            v-model="edu.institution"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('educations', index, 'institution') }"
            placeholder="Например: Московский государственный университет"
          >
          <span v-if="getNestedError('educations', index, 'institution')" class="error-text">
            {{ getNestedError('educations', index, 'institution') }}
          </span>
        </div>

        <div class="form-group">
          <label>Факультет / Специальность</label>
          <input
            v-model="edu.faculty"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('educations', index, 'faculty') }"
            placeholder="Например: Факультет вычислительной математики и кибернетики"
          >
          <span v-if="getNestedError('educations', index, 'faculty')" class="error-text">
            {{ getNestedError('educations', index, 'faculty') }}
          </span>
        </div>

        <div class="form-group">
          <label>Специальность</label>
          <input
            v-model="edu.specialty"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('educations', index, 'specialty') }"
            placeholder="Например: Программная инженерия"
          >
          <span v-if="getNestedError('educations', index, 'specialty')" class="error-text">
            {{ getNestedError('educations', index, 'specialty') }}
          </span>
        </div>

        <div class="form-group">
          <label>Квалификация</label>
          <input
            v-model="edu.qualification"
            type="text"
            class="form-input"
            :class="{ error: getNestedError('educations', index, 'qualification') }"
            placeholder="Например: Инженер-программист"
          >
          <span v-if="getNestedError('educations', index, 'qualification')" class="error-text">
            {{ getNestedError('educations', index, 'qualification') }}
          </span>
        </div>

        <div class="form-group">
          <label>Степень</label>
          <select
            v-model="edu.degree_id"
            class="form-select"
            :class="{ error: getNestedError('educations', index, 'degree_id') }"
          >
            <option :value="null">Не указано</option>
            <option v-for="degree in degrees" :key="degree.id" :value="degree.id">
              {{ degree.name }}
            </option>
          </select>
          <span v-if="getNestedError('educations', index, 'degree_id')" class="error-text">
            {{ getNestedError('educations', index, 'degree_id') }}
          </span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Год начала</label>
            <input
              v-model="edu.start_date"
              type="date"
              class="form-input"
              :class="{ error: getNestedError('educations', index, 'start_date') }"
            >
            <span v-if="getNestedError('educations', index, 'start_date')" class="error-text">
              {{ getNestedError('educations', index, 'start_date') }}
            </span>
          </div>
          <div class="form-group">
            <label>Год окончания</label>
            <input
              v-model="edu.end_date"
              type="date"
              class="form-input"
              :disabled="edu.is_current"
              :class="{ error: getNestedError('educations', index, 'end_date') }"
            >
            <span v-if="getNestedError('educations', index, 'end_date')" class="error-text">
              {{ getNestedError('educations', index, 'end_date') }}
            </span>
          </div>
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input v-model="edu.is_current" type="checkbox" @change="onCurrentEducationChange(edu)">
            <span>Учусь по настоящее время</span>
          </label>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <router-link to="/applicant/resume" class="btn-outline">
        Отмена
      </router-link>
      <button type="submit" class="btn-primary" :disabled="saving">
        {{ saving ? 'Сохранение...' : submitButtonText }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({
      title: '',
      salary: null,
      work_schedule_ids: [],
      contacts: [],
      skills: [],
      work_experiences: [],
      educations: []
    })
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  saving: {
    type: Boolean,
    default: false
  },
  submitButtonText: {
    type: String,
    default: 'Сохранить'
  },
  degrees: {
    type: Array,
    default: () => []
  },
  workSchedules: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['submit', 'update:initialData'])

const formData = reactive({ ...props.initialData })

watch(() => props.initialData, (newVal) => {
  Object.keys(newVal).forEach(key => {
    if (formData[key] !== newVal[key]) {
      formData[key] = newVal[key]
    }
  })
}, { deep: true })

const isScheduleSelected = (id) => {
  return formData.work_schedule_ids?.includes(id) || false
}

const toggleSchedule = (id) => {
  const index = formData.work_schedule_ids.indexOf(id)
  if (index === -1) {
    formData.work_schedule_ids.push(id)
  } else {
    formData.work_schedule_ids.splice(index, 1)
  }
}

const getScheduleIcon = (name) => {
  const icons = {
    'Полная занятость': '💼',
    'Частичная занятость': '⏱️',
    'Гибрид': '🏢💻',
    'Удаленная работа': '🏠',
    'Вахты': '⛏️'
  }
  return icons[name] || '📅'
}

const getFieldError = (field) => {
  return props.errors[field]?.[0]
}

const getNestedError = (arrayName, index, field) => {
  const key = `${arrayName}.${index}.${field}`
  return props.errors[key]?.[0]
}

const getInputType = (type) => {
  if (type === 'email') return 'email'
  if (type === 'phone' || type === 'whatsapp') return 'tel'
  return 'text'
}

const getContactPlaceholder = (type) => {
  const placeholders = {
    phone: '+7 (999) 123-45-67',
    email: 'user@example.com',
    telegram: '@username или username',
    whatsapp: '+7 (999) 123-45-67'
  }
  return placeholders[type] || 'Введите значение'
}

const getContactHint = (type) => {
  const hints = {
    phone: 'Формат: +7 (999) 123-45-67, 10-15 цифр',
    email: 'Формат: user@example.com',
    telegram: 'Только латиница, цифры, _. 5-32 символа',
    whatsapp: 'Формат: +7 (999) 123-45-67, 10-15 цифр'
  }
  return hints[type] || ''
}

const addContact = () => {
  formData.contacts.push({ type: 'phone', value: '' })
}

const removeContact = (index) => {
  formData.contacts.splice(index, 1)
}

const addSkill = () => {
  formData.skills.push({ skill: '', level: 'intermediate' })
}

const removeSkill = (index) => {
  formData.skills.splice(index, 1)
}

const addWork = () => {
  formData.work_experiences.push({
    title: '',
    experience_summary: '',
    start_date: '',
    end_date: null,
    is_current: false
  })
}

const removeWork = (index) => {
  formData.work_experiences.splice(index, 1)
}

const onCurrentWorkChange = (work) => {
  if (work.is_current) {
    work.end_date = null
  }
}

const addEducation = () => {
  formData.educations.push({
    institution: '',
    faculty: '',
    specialty: '',
    qualification: '',
    degree_id: null,
    start_date: '',
    end_date: null,
    is_current: false
  })
}

const removeEducation = (index) => {
  formData.educations.splice(index, 1)
}

const onCurrentEducationChange = (edu) => {
  if (edu.is_current) {
    edu.end_date = null
  }
}
</script>

<style scoped>
.hint-text {
  display: block;
  color: var(--text-secondary);
  font-size: 11px;
  margin-top: 4px;
}

/* Стили для графиков работы */
.schedule-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 16px;
  margin-top: 16px;
}

.schedule-card {
  position: relative;
  padding: 20px 12px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 16px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
}

.schedule-card:hover {
  border-color: var(--color-primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary);
}

.schedule-card.active {
  border-color: var(--color-primary);
  background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 204, 102, 0.05));
}

.schedule-icon {
  font-size: 32px;
  margin-bottom: 12px;
}

.schedule-name {
  font-size: 14px;
  font-weight: 500;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.schedule-check {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.schedule-card.active .schedule-check svg {
  color: var(--color-primary);
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

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin: 0;
}

.empty-section {
  padding: 30px;
  text-align: center;
  background: var(--bg-secondary);
  border-radius: 12px;
  border: 1px dashed var(--border-color);
}

.empty-text {
  color: var(--text-secondary);
  font-size: 14px;
  margin: 0;
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
  font-weight: 500;
}

.required {
  color: var(--color-danger);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.checkbox-label input {
  width: auto;
  margin: 0;
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
  font-family: inherit;
}

.error-text {
  display: block;
  color: var(--color-danger);
  font-size: 12px;
  margin-top: 5px;
}

.contact-row,
.skill-row {
  display: flex;
  gap: 12px;
  align-items: flex-start;
  margin-bottom: 15px;
}

.contact-row .form-group,
.skill-row .form-group {
  margin-bottom: 0;
}

.skill-level-select {
  min-width: 140px;
}

.flex-grow {
  flex-grow: 1;
}

.work-card,
.education-card {
  background: var(--bg-secondary);
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 20px;
  border: 1px solid var(--border-color);
}

.work-card:last-child,
.education-card:last-child {
  margin-bottom: 0;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--border-color);
}

.card-header h4 {
  color: var(--color-primary);
  font-size: 16px;
  margin: 0;
}

.btn-add {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--color-primary);
  border-radius: 20px;
  color: var(--color-primary);
  cursor: pointer;
  font-size: 13px;
  transition: var(--transition-base);
}

.btn-add:hover {
  background: var(--color-primary);
  color: var(--text-dark);
  transform: translateY(-1px);
}

.btn-remove {
  padding: 8px 12px;
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 18px;
  opacity: 0.5;
  transition: var(--transition-base);
  border-radius: 8px;
}

.btn-remove:hover {
  opacity: 1;
  background: rgba(255, 100, 100, 0.1);
}

.btn-remove-card {
  padding: 6px 12px;
  background: transparent;
  border: 1px solid var(--color-danger);
  border-radius: 20px;
  color: var(--color-danger);
  cursor: pointer;
  font-size: 12px;
  transition: var(--transition-base);
}

.btn-remove-card:hover {
  background: var(--color-danger);
  color: white;
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
  text-align: center;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

@media (max-width: 768px) {
  .resume-form {
    padding: 20px;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .contact-row,
  .skill-row {
    flex-direction: column;
  }

  .skill-level-select {
    min-width: auto;
  }

  .card-header {
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .btn-primary,
  .btn-outline {
    width: 100%;
    text-align: center;
  }

  .schedule-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .schedule-card {
    padding: 16px 8px;
  }

  .schedule-icon {
    font-size: 24px;
  }
}
</style>
