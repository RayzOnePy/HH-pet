<template>
  <div class="resume-page">
    <!-- Кнопка назад -->
    <div class="back-nav">
      <router-link to="/employer/resumes" class="back-link">
        ← Вернуться к поиску резюме
      </router-link>
    </div>

    <!-- Шапка резюме -->
    <div class="resume-header">
      <div class="candidate-main">
        <div class="candidate-avatar-large">👤</div>
        <div class="candidate-title">
          <h1>Александр Петров</h1>
          <p class="position">Senior Frontend Developer</p>
          <div class="candidate-meta">
            <span>📍 Москва</span>
            <span>💼 5 лет</span>
            <span>💰 от 250 000 ₽</span>
          </div>
        </div>
      </div>
      <div class="header-actions">
        <button class="btn-primary" @click="showInviteModal = true">
          ✉️ Пригласить на вакансию
        </button>
        <button class="btn-outline">⭐ В избранное</button>
      </div>
    </div>

    <!-- Контактная информация -->
    <div class="section">
      <h2>Контактная информация</h2>
      <div class="contacts-grid">
        <div class="contact-item">
          <span class="contact-icon">📧</span>
          <div>
            <div class="contact-label">Email</div>
            <div class="contact-value">alexandr.petrov@example.com</div>
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
            <div class="contact-value">@alex_dev</div>
          </div>
        </div>
      </div>
    </div>

    <!-- О себе -->
    <div class="section">
      <h2>О себе</h2>
      <p class="about-text">
        Опытный Frontend разработчик с 5-летним стажем. Специализируюсь на Vue.js и React.
        Имею опыт построения архитектуры SPA приложений, оптимизации производительности,
        внедрения лучших практик. Легко нахожу общий язык в команде, участвую в code review,
        менторю junior разработчиков.
      </p>
    </div>

    <!-- Навыки -->
    <div class="section">
      <h2>Ключевые навыки</h2>
      <div class="skills-grid">
        <span v-for="skill in skills" :key="skill" class="skill-tag-large">
          {{ skill }}
        </span>
      </div>
    </div>

    <!-- Опыт работы -->
    <div class="section">
      <h2>Опыт работы</h2>
      <div class="experience-list">
        <div v-for="i in 2" :key="i" class="experience-item">
          <div class="experience-period">
            {{ i === 1 ? '2022 — настоящее время' : '2019 — 2022' }}
          </div>
          <div class="experience-content">
            <h3>Senior Frontend Developer</h3>
            <p class="company-name">TechCorp, Москва</p>
            <ul class="experience-description">
              <li>Разработка SPA приложений на Vue 3</li>
              <li>Оптимизация производительности (ускорение загрузки на 40%)</li>
              <li>Внедрение TypeScript и Code Review процессов</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Образование -->
    <div class="section">
      <h2>Образование</h2>
      <div class="education-item">
        <div class="education-period">2015 — 2019</div>
        <div class="education-content">
          <h3>Московский государственный университет</h3>
          <p class="education-degree">Факультет вычислительной математики и кибернетики, Прикладная математика и информатика</p>
        </div>
      </div>
    </div>

    <!-- Модалка приглашения -->
    <Transition name="fade">
      <div v-if="showInviteModal" class="modal-overlay" @click.self="showInviteModal = false">
        <div class="modal-container">
          <h3>Пригласить на вакансию</h3>
          <p>Выберите вакансию для приглашения кандидата</p>

          <div class="vacancies-list">
            <label v-for="i in 3" :key="i" class="vacancy-option">
              <input type="radio" name="vacancy" :value="i" v-model="selectedVacancy">
              <div>
                <strong>Senior Frontend Developer {{ i }}</strong>
                <span>от 250 000 ₽</span>
              </div>
            </label>
          </div>

          <div class="modal-actions">
            <button class="btn-outline" @click="showInviteModal = false">Отмена</button>
            <button class="btn-primary" @click="sendInvite">Отправить приглашение</button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const showInviteModal = ref(false)
const selectedVacancy = ref(null)

const skills = [
  'Vue.js', 'React', 'TypeScript', 'JavaScript', 'Pinia', 'Vuex',
  'Webpack', 'Vite', 'Jest', 'Cypress', 'HTML5', 'CSS3', 'SASS'
]

const sendInvite = () => {
  if (selectedVacancy.value) {
    console.log('Invite sent for vacancy:', selectedVacancy.value)
    showInviteModal.value = false
  }
}
</script>

<style scoped>
.resume-page {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.back-nav {
  margin-bottom: 20px;
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
  font-size: 14px;
}

.back-link:hover {
  text-decoration: underline;
}

.resume-header {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 30px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.candidate-main {
  display: flex;
  gap: 30px;
}

.candidate-avatar-large {
  width: 100px;
  height: 100px;
  background: var(--bg-secondary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 50px;
  border: 2px solid var(--color-primary);
}

.candidate-title h1 {
  color: var(--text-primary);
  font-size: 32px;
  margin-bottom: 8px;
}

.position {
  color: var(--color-primary);
  font-size: 18px;
  margin-bottom: 12px;
}

.candidate-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
}

.header-actions {
  display: flex;
  gap: 15px;
}

.section {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 30px;
}

.section h2 {
  color: var(--text-primary);
  font-size: 20px;
  margin-bottom: 20px;
}

.contacts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.contact-item {
  display: flex;
  gap: 15px;
  align-items: center;
}

.contact-icon {
  font-size: 24px;
}

.contact-label {
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 4px;
}

.contact-value {
  color: var(--text-primary);
  font-weight: 500;
}

.about-text {
  color: var(--text-secondary);
  line-height: 1.8;
}

.skills-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.skill-tag-large {
  padding: 8px 16px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 14px;
}

.experience-list {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.experience-item {
  display: flex;
  gap: 30px;
}

.experience-period {
  min-width: 150px;
  color: var(--color-primary);
  font-weight: 500;
}

.experience-content h3 {
  color: var(--text-primary);
  margin-bottom: 5px;
}

.company-name {
  color: var(--text-secondary);
  margin-bottom: 10px;
}

.experience-description {
  color: var(--text-secondary);
  padding-left: 20px;
  line-height: 1.6;
}

.experience-description li {
  margin-bottom: 5px;
}

.education-item {
  display: flex;
  gap: 30px;
}

.education-period {
  min-width: 150px;
  color: var(--color-primary);
  font-weight: 500;
}

.education-content h3 {
  color: var(--text-primary);
  margin-bottom: 5px;
}

.education-degree {
  color: var(--text-secondary);
}

/* Модальное окно */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-container {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
  max-width: 500px;
  width: 90%;
}

.modal-container h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.modal-container p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.vacancies-list {
  margin-bottom: 20px;
}

.vacancy-option {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  margin-bottom: 10px;
  cursor: pointer;
}

.vacancy-option:hover {
  border-color: var(--color-primary);
}

.vacancy-option input[type="radio"] {
  accent-color: var(--color-primary);
  width: 18px;
  height: 18px;
}

.vacancy-option div {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.vacancy-option strong {
  color: var(--text-primary);
}

.vacancy-option span {
  color: var(--color-primary);
  font-size: 14px;
}

.modal-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
}

.btn-primary {
  padding: 12px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@media (max-width: 768px) {
  .resume-header {
    flex-direction: column;
    gap: 20px;
  }

  .candidate-main {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .experience-item,
  .education-item {
    flex-direction: column;
    gap: 10px;
  }

  .experience-period,
  .education-period {
    min-width: auto;
  }
}
</style>
