<template>
  <div class="vacancy-page">
    <div class="back-nav">
      <router-link to="/applicant/vacancies" class="back-link">
        ← Вернуться к поиску
      </router-link>
    </div>

    <div class="vacancy-card detailed">
      <div class="vacancy-header">
        <div>
          <h1>Senior Frontend Developer</h1>
          <div class="company-large">
            <span class="company-logo">🏢</span>
            <span>TechCorp</span>
            <span class="badge">IT, 50-100 сотрудников</span>
          </div>
        </div>
        <div class="salary-large">от 250 000 ₽</div>
      </div>

      <div class="vacancy-actions">
        <button class="btn-primary btn-large" @click="showResponseModal = true">
          ✉️ Откликнуться
        </button>
        <button class="btn-outline btn-large" @click="toggleFavorite">
          {{ isFavorite ? '⭐ В избранном' : '☆ В избранное' }}
        </button>
      </div>

      <div class="vacancy-details">
        <div class="detail-item">
          <span class="detail-icon">📍</span>
          <div>
            <div class="detail-label">Город</div>
            <div class="detail-value">Москва</div>
          </div>
        </div>
        <div class="detail-item">
          <span class="detail-icon">💼</span>
          <div>
            <div class="detail-label">Опыт работы</div>
            <div class="detail-value">3-6 лет</div>
          </div>
        </div>
        <div class="detail-item">
          <span class="detail-icon">⏰</span>
          <div>
            <div class="detail-label">График</div>
            <div class="detail-value">Полный день, удаленно</div>
          </div>
        </div>
        <div class="detail-item">
          <span class="detail-icon">📅</span>
          <div>
            <div class="detail-label">Опубликовано</div>
            <div class="detail-value">19 марта 2026</div>
          </div>
        </div>
      </div>

      <div class="section">
        <h2>Описание вакансии</h2>
        <p class="description-text">
          Мы ищем опытного Frontend разработчика для работы над крупным проектом в сфере финтеха.
          Наша команда занимается разработкой высоконагруженных систем, и нам нужен специалист,
          который поможет улучшить существующие решения и создавать новые.
        </p>
      </div>

      <div class="section">
        <h2>Требования</h2>
        <ul class="requirements-list">
          <li>Опыт работы с Vue.js от 3 лет</li>
          <li>Хорошее знание TypeScript</li>
          <li>Опыт работы с Pinia/Vuex</li>
          <li>Понимание принципов REST API</li>
          <li>Опыт оптимизации производительности</li>
        </ul>
      </div>

      <div class="section">
        <h2>Условия работы</h2>
        <ul class="conditions-list">
          <li>Удаленная работа или офис в Москве</li>
          <li>Гибкий график</li>
          <li>Конкурентная зарплата</li>
          <li>ДМС со стоматологией</li>
          <li>Обучение за счет компании</li>
        </ul>
      </div>

      <div class="section">
        <h2>Ключевые навыки</h2>
        <div class="skills-list">
          <span class="skill-tag">Vue.js</span>
          <span class="skill-tag">TypeScript</span>
          <span class="skill-tag">Pinia</span>
          <span class="skill-tag">Vite</span>
          <span class="skill-tag">Jest</span>
        </div>
      </div>

      <div class="company-section">
        <h2>О компании</h2>
        <div class="company-profile">
          <div class="company-logo-large">🏢</div>
          <div class="company-info">
            <h3>TechCorp</h3>
            <p>IT-компания, специализирующаяся на разработке высоконагруженных проектов в сфере финтеха и e-commerce.</p>
            <div class="company-meta">
              <span>📍 Москва</span>
              <span>👥 50-100 сотрудников</span>
              <span>🌐 www.techcorp.ru</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Модалка отклика -->
    <Transition name="fade">
      <div v-if="showResponseModal" class="modal-overlay" @click.self="showResponseModal = false">
        <div class="modal-container">
          <h3>Отклик на вакансию</h3>

          <div class="response-form">
            <div class="form-group">
              <label>Ваше резюме</label>
              <select v-model="selectedResume" class="form-select">
                <option value="1">Frontend Developer (актуальное)</option>
                <option value="2">Vue.js Developer</option>
              </select>
            </div>

            <div class="form-group">
              <label>Сопроводительное письмо (необязательно)</label>
              <textarea
                v-model="coverLetter"
                rows="4"
                placeholder="Расскажите, почему вы подходите на эту вакансию..."
                class="form-textarea"
              ></textarea>
            </div>
          </div>

          <div class="modal-actions">
            <button class="btn-outline" @click="showResponseModal = false">
              Отмена
            </button>
            <button class="btn-primary" @click="sendResponse">
              Отправить отклик
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isFavorite = ref(false)
const showResponseModal = ref(false)
const selectedResume = ref('1')
const coverLetter = ref('')

const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
}

const sendResponse = () => {
  console.log('Response sent:', {
    resumeId: selectedResume.value,
    coverLetter: coverLetter.value
  })
  showResponseModal.value = false
}
</script>

<style scoped>
.vacancy-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.back-nav {
  margin-bottom: 20px;
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.vacancy-card.detailed {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.vacancy-header h1 {
  color: var(--text-primary);
  font-size: 32px;
  margin-bottom: 10px;
}

.company-large {
  display: flex;
  align-items: center;
  gap: 12px;
}

.company-logo {
  font-size: 24px;
}

.badge {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border-radius: 30px;
  font-size: 13px;
  color: var(--text-secondary);
}

.salary-large {
  color: var(--color-primary);
  font-size: 28px;
  font-weight: 600;
}

.vacancy-actions {
  display: flex;
  gap: 15px;
  margin-bottom: 30px;
}

.btn-large {
  padding: 14px 32px;
  font-size: 16px;
}

.vacancy-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px;
  background: var(--bg-secondary);
  border-radius: 16px;
  margin-bottom: 30px;
}

.detail-item {
  display: flex;
  gap: 15px;
  align-items: center;
}

.detail-icon {
  font-size: 24px;
}

.detail-label {
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 2px;
}

.detail-value {
  color: var(--text-primary);
  font-weight: 500;
}

.section {
  margin-bottom: 30px;
}

.section h2 {
  color: var(--text-primary);
  font-size: 20px;
  margin-bottom: 15px;
}

.description-text {
  color: var(--text-secondary);
  line-height: 1.8;
}

.requirements-list,
.conditions-list {
  color: var(--text-secondary);
  padding-left: 20px;
  line-height: 1.8;
}

.requirements-list li,
.conditions-list li {
  margin-bottom: 8px;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.skill-tag {
  padding: 8px 16px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 14px;
}

.company-section {
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

.company-profile {
  display: flex;
  gap: 30px;
  align-items: center;
}

.company-logo-large {
  width: 80px;
  height: 80px;
  background: var(--bg-secondary);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  border: 2px solid var(--color-primary);
}

.company-info h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.company-info p {
  color: var(--text-secondary);
  margin-bottom: 10px;
  line-height: 1.6;
}

.company-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
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
  border-radius: 24px;
  padding: 30px;
  max-width: 500px;
  width: 90%;
}

.modal-container h3 {
  color: var(--text-primary);
  margin-bottom: 20px;
}

.response-form {
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

.form-select,
.form-textarea {
  width: 100%;
  padding: 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
}

.modal-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
}
</style>
