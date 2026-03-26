<template>
  <div class="my-resume">
    <div class="page-header">
      <h1>Моё резюме</h1>
      <div class="header-actions">
        <button
          v-if="hasResume && !isEditMode"
          @click="toggleActiveHandler"
          class="btn-outline"
          :disabled="toggling"
        >
          {{ isActive ? '📁 Скрыть резюме' : '📢 Опубликовать' }}
        </button>
        <router-link
          v-if="hasResume && !isEditMode"
          to="/applicant/resume/edit"
          class="btn-primary"
        >
          ✏️ Редактировать
        </router-link>
        <router-link
          v-if="!hasResume && !isEditMode"
          to="/applicant/resume/create"
          class="btn-primary"
        >
          + Создать резюме
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка резюме...</p>
    </div>

    <div v-else-if="!hasResume" class="empty-state">
      <div class="empty-icon">📄</div>
      <h3>У вас ещё нет резюме</h3>
      <p>Создайте резюме, чтобы работодатели могли найти вас</p>
      <router-link to="/applicant/resume/create" class="btn-primary">
        + Создать резюме
      </router-link>
    </div>

    <div v-else class="resume-preview">
      <div class="status-banner" :class="{ active: isActive, inactive: !isActive }">
        <span class="status-icon">{{ isActive ? '✅' : '🔒' }}</span>
        <span class="status-text">
          {{ isActive ? 'Резюме опубликовано и доступно работодателям' : 'Резюме скрыто. Работодатели не видят его' }}
        </span>
      </div>

      <div class="resume-header">
        <div class="candidate-avatar-large">
          {{ userInitials }}
        </div>
        <div class="candidate-title">
          <h2>{{ userFullName }}</h2>
          <p class="position">{{ resume.title }}</p>
          <div class="candidate-meta">
            <span v-if="resume.salary">💰 от {{ formatSalary(resume.salary) }} ₽</span>
            <span v-if="resume.can_business_trip">✈️ Готов к командировкам</span>
          </div>
        </div>
      </div>

      <!-- Контакты -->
      <div v-if="resume.contacts?.length" class="section">
        <h3>Контактная информация</h3>
        <div class="contacts-grid">
          <div v-for="contact in resume.contacts" :key="contact.id" class="contact-item">
            <span class="contact-icon">{{ getContactIcon(contact.type) }}</span>
            <div>
              <div class="contact-label">{{ getContactLabel(contact.type) }}</div>
              <div class="contact-value">{{ contact.value }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Навыки -->
      <div v-if="resume.skills?.length" class="section">
        <h3>Ключевые навыки</h3>
        <div class="skills-list">
          <div v-for="skill in resume.skills" :key="skill.id" class="skill-tag">
            {{ skill.skill }}
            <span class="skill-level">{{ getSkillLevelText(skill.level) }}</span>
          </div>
        </div>
      </div>

      <!-- Опыт работы -->
      <div v-if="resume.work_experiences?.length" class="section">
        <h3>Опыт работы</h3>
        <div class="experience-list">
          <div v-for="work in resume.work_experiences" :key="work.id" class="experience-item">
            <div class="experience-period">
              {{ formatDate(work.start_date) }} — {{ work.is_current ? 'настоящее время' : formatDate(work.end_date) }}
            </div>
            <div class="experience-content">
              <h4>{{ work.title }}</h4>
              <div class="experience-summary">{{ work.experience_summary }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Образование -->
      <div v-if="resume.educations?.length" class="section">
        <h3>Образование</h3>
        <div class="education-list">
          <div v-for="education in resume.educations" :key="education.id" class="education-item">
            <div class="education-period">
              {{ formatDate(education.start_date) }} — {{ education.is_current ? 'настоящее время' : formatDate(education.end_date) }}
            </div>
            <div class="education-content">
              <h4>{{ education.institution }}</h4>
              <p>{{ education.faculty }}</p>
              <p class="specialty">{{ education.specialty }}</p>
              <p class="qualification">{{ education.degree?.name }}, {{ education.qualification }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from '../../../stores/auth'
import { useResume } from '../../../composables/useResume'

const authStore = useAuthStore()
const { resume, loading, hasResume, isActive, fetchMyResume, toggleActive } = useResume()
const toggling = ref(false)

const userFullName = computed(() => {
  const user = authStore.user
  if (!user) return ''
  return `${user.last_name} ${user.first_name}`
})

const userInitials = computed(() => {
  const user = authStore.user
  if (!user) return '👤'
  return `${user.first_name?.[0]}${user.last_name?.[0]}`.toUpperCase()
})

const formatSalary = (salary) => {
  return salary?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('ru-RU')
}

const getContactIcon = (type) => {
  const icons = {
    phone: '📱',
    email: '📧',
    telegram: '💬',
    whatsapp: '💬'
  }
  return icons[type] || '📞'
}

const getContactLabel = (type) => {
  const labels = {
    phone: 'Телефон',
    email: 'Email',
    telegram: 'Telegram',
    whatsapp: 'WhatsApp'
  }
  return labels[type] || type
}

const getSkillLevelText = (level) => {
  const levels = {
    beginner: 'Начальный',
    intermediate: 'Средний',
    advanced: 'Продвинутый'
  }
  return levels[level] || level
}

const toggleActiveHandler = async () => {
  toggling.value = true
  await toggleActive()
  toggling.value = false
}

onMounted(async () => {
  await fetchMyResume()
})
</script>

<style scoped>
.my-resume {
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

.header-actions {
  display: flex;
  gap: 12px;
}

h1 {
  color: var(--text-primary);
  font-size: 28px;
}

.status-banner {
  padding: 12px 16px;
  border-radius: 12px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.status-banner.active {
  background: rgba(0, 255, 136, 0.1);
  border: 1px solid rgba(0, 255, 136, 0.3);
  color: var(--color-primary);
}

.status-banner.inactive {
  background: rgba(255, 100, 100, 0.1);
  border: 1px solid rgba(255, 100, 100, 0.3);
  color: #ff6464;
}

.status-icon {
  font-size: 20px;
}

.status-text {
  font-size: 14px;
}

.resume-preview {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.resume-header {
  display: flex;
  gap: 30px;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border-color);
}

.candidate-avatar-large {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--color-primary), #00ccff);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: bold;
  color: var(--color-black-100);
}

.candidate-title h2 {
  color: var(--text-primary);
  font-size: 24px;
  margin-bottom: 8px;
}

.position {
  color: var(--color-primary);
  font-size: 18px;
  margin-bottom: 10px;
}

.candidate-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  flex-wrap: wrap;
}

.section {
  margin-bottom: 30px;
}

.section h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 15px;
}

.contacts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  background: var(--bg-secondary);
  padding: 20px;
  border-radius: 12px;
}

.contact-item {
  display: flex;
  gap: 12px;
}

.contact-icon {
  font-size: 20px;
}

.contact-label {
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 2px;
}

.contact-value {
  color: var(--text-primary);
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.skill-tag {
  padding: 6px 14px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.skill-level {
  font-size: 11px;
  color: var(--text-secondary);
  padding-left: 6px;
  border-left: 1px solid var(--border-color);
}

.experience-list,
.education-list {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.experience-item,
.education-item {
  display: flex;
  gap: 30px;
}

.experience-period,
.education-period {
  min-width: 140px;
  color: var(--color-primary);
  font-weight: 500;
  font-size: 14px;
}

.experience-content h4,
.education-content h4 {
  color: var(--text-primary);
  margin-bottom: 5px;
}

.experience-summary {
  color: var(--text-secondary);
  line-height: 1.6;
  margin-top: 8px;
}

.education-content p {
  color: var(--text-secondary);
  margin-bottom: 4px;
}

.specialty {
  font-weight: 500;
  color: var(--text-primary);
}

.qualification {
  font-size: 13px;
  color: var(--text-secondary);
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

.btn-primary {
  padding: 10px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.btn-outline {
  padding: 10px 24px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
