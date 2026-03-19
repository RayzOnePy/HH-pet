<template>
  <div class="my-resume">
    <div class="page-header">
      <h1>Моё резюме</h1>
      <router-link to="/applicant/resume/edit" class="btn-primary">
        ✏️ Редактировать
      </router-link>
    </div>

    <div class="resume-preview">
      <div class="resume-header">
        <div class="candidate-avatar-large">👤</div>
        <div class="candidate-title">
          <h2>{{ user?.first_name }} {{ user?.last_name }}</h2>
          <p class="position">Senior Frontend Developer</p>
          <div class="candidate-meta">
            <span>📍 Москва</span>
            <span>💼 5 лет</span>
            <span>💰 от 250 000 ₽</span>
          </div>
        </div>
      </div>

      <div class="section">
        <h3>Контактная информация</h3>
        <div class="contacts-grid">
          <div class="contact-item">
            <span class="contact-icon">📧</span>
            <div>
              <div class="contact-label">Email</div>
              <div class="contact-value">{{ user?.email }}</div>
            </div>
          </div>
          <div class="contact-item">
            <span class="contact-icon">📱</span>
            <div>
              <div class="contact-label">Телефон</div>
              <div class="contact-value">+7 (999) 123-45-67</div>
            </div>
          </div>
          <div class="contact-item">
            <span class="contact-icon">✈️</span>
            <div>
              <div class="contact-label">Telegram</div>
              <div class="contact-value">@username</div>
            </div>
          </div>
        </div>
      </div>

      <div class="section">
        <h3>О себе</h3>
        <p class="about-text">
          Опытный Frontend разработчик с 5-летним стажем. Специализируюсь на Vue.js и React.
          Имею опыт построения архитектуры SPA приложений, оптимизации производительности,
          внедрения лучших практик. Легко нахожу общий язык в команде, участвую в code review,
          менторю junior разработчиков.
        </p>
      </div>

      <div class="section">
        <h3>Ключевые навыки</h3>
        <div class="skills-list">
          <span class="skill-tag">Vue.js</span>
          <span class="skill-tag">React</span>
          <span class="skill-tag">TypeScript</span>
          <span class="skill-tag">Pinia</span>
          <span class="skill-tag">Webpack</span>
          <span class="skill-tag">Vite</span>
        </div>
      </div>

      <div class="section">
        <h3>Опыт работы</h3>
        <div class="experience-list">
          <div class="experience-item">
            <div class="experience-period">2022 — настоящее время</div>
            <div class="experience-content">
              <h4>Senior Frontend Developer</h4>
              <p class="company-name">TechCorp, Москва</p>
              <ul>
                <li>Разработка SPA приложений на Vue 3</li>
                <li>Оптимизация производительности</li>
                <li>Code review и менторство</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="section">
        <h3>Образование</h3>
        <div class="education-item">
          <div class="education-period">2015 — 2019</div>
          <div class="education-content">
            <h4>Московский государственный университет</h4>
            <p>Факультет вычислительной математики и кибернетики</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
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
}

h1 {
  color: var(--text-primary);
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
  background: var(--bg-secondary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  border: 2px solid var(--color-primary);
}

.candidate-title h2 {
  color: var(--text-primary);
  font-size: 24px;
  margin-bottom: 8px;
}

.position {
  color: var(--color-primary);
  margin-bottom: 10px;
}

.candidate-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
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

.about-text {
  color: var(--text-secondary);
  line-height: 1.8;
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
}

.experience-list,
.education-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.experience-item,
.education-item {
  display: flex;
  gap: 30px;
}

.experience-period,
.education-period {
  min-width: 150px;
  color: var(--color-primary);
  font-weight: 500;
}

.experience-content h4,
.education-content h4 {
  color: var(--text-primary);
  margin-bottom: 5px;
}

.company-name {
  color: var(--text-secondary);
  margin-bottom: 10px;
  font-size: 14px;
}

.experience-content ul {
  color: var(--text-secondary);
  padding-left: 20px;
  line-height: 1.6;
}

.experience-content li {
  margin-bottom: 4px;
}

.education-content p {
  color: var(--text-secondary);
}

.btn-primary {
  padding: 12px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
}
</style>
