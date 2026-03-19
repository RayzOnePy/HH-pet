<template>
  <div class="dashboard">
    <h1>Моя панель</h1>
    <p class="description">Добро пожаловать, {{ user?.first_name }}!</p>

    <!-- Статус резюме -->
    <div class="resume-status" v-if="!hasResume">
      <div class="status-warning">
        <span class="icon">⚠️</span>
        <div>
          <h3>У вас ещё нет резюме</h3>
          <p>Создайте резюме, чтобы работодатели могли вас найти</p>
        </div>
        <router-link to="/applicant/resume/edit" class="btn-primary">
          Создать резюме
        </router-link>
      </div>
    </div>

    <div class="resume-status" v-else>
      <div class="status-success">
        <span class="icon">✅</span>
        <div>
          <h3>Резюме опубликовано</h3>
          <p>Ваше резюме видно работодателям</p>
        </div>
        <router-link to="/applicant/resume" class="btn-outline">
          Просмотреть
        </router-link>
      </div>
    </div>

    <!-- Статистика -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">{{ stats.responses }}</div>
        <div class="stat-label">Откликов</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ stats.invitations }}</div>
        <div class="stat-label">Приглашений</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ stats.views }}</div>
        <div class="stat-label">Просмотров</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ stats.favorites }}</div>
        <div class="stat-label">В избранном</div>
      </div>
    </div>

    <!-- Быстрые действия -->
    <div class="quick-actions">
      <h2>Быстрые действия</h2>
      <div class="actions-grid">
        <router-link to="/applicant/vacancies" class="action-card">
          <span class="action-icon">🔍</span>
          <span class="action-text">Поиск вакансий</span>
        </router-link>
        <router-link to="/applicant/responses" class="action-card">
          <span class="action-icon">✉️</span>
          <span class="action-text">Мои отклики</span>
        </router-link>
        <router-link to="/applicant/favorites" class="action-card">
          <span class="action-icon">⭐</span>
          <span class="action-text">Избранное</span>
        </router-link>
        <router-link to="/applicant/resume/edit" class="action-card">
          <span class="action-icon">📝</span>
          <span class="action-text">Редактировать резюме</span>
        </router-link>
      </div>
    </div>

    <!-- Рекомендуемые вакансии -->
    <div class="recommended">
      <h2>Рекомендуемые вакансии</h2>
      <div class="vacancies-list">
        <div v-for="i in 3" :key="i" class="vacancy-card">
          <div class="vacancy-header">
            <h3>
              <router-link to="/applicant/vacancies/1">
                Senior Frontend Developer
              </router-link>
            </h3>
            <div class="company">TechCorp</div>
          </div>
          <div class="vacancy-meta">
            <span>📍 Москва</span>
            <span>💰 от 250 000 ₽</span>
          </div>
          <div class="vacancy-footer">
            <span class="skills">Vue.js • React • TypeScript</span>
            <button class="btn-primary-small">Откликнуться</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '../../../stores/auth'

const authStore = useAuthStore()
const user = computed(() => authStore.user)

// Заглушка данных
const hasResume = ref(true) // false - если нет резюме

const stats = ref({
  responses: 12,
  invitations: 5,
  views: 124,
  favorites: 8
})
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 8px;
}

.description {
  color: var(--text-secondary);
  margin-bottom: 30px;
}

.resume-status {
  margin-bottom: 30px;
}

.status-warning,
.status-success {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
}

.status-warning {
  border-left: 4px solid #ffc107;
}

.status-success {
  border-left: 4px solid var(--color-primary);
}

.status-warning .icon,
.status-success .icon {
  font-size: 32px;
}

.status-warning h3,
.status-success h3 {
  color: var(--text-primary);
  margin-bottom: 4px;
}

.status-warning p,
.status-success p {
  color: var(--text-secondary);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.stat-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  text-align: center;
}

.stat-value {
  font-size: 36px;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: 8px;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 14px;
}

h2 {
  color: var(--text-primary);
  margin-bottom: 20px;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.action-card {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 24px;
  text-align: center;
  text-decoration: none;
  transition: var(--transition-base);
}

.action-card:hover {
  border-color: var(--color-primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary);
}

.action-icon {
  font-size: 32px;
  display: block;
  margin-bottom: 12px;
}

.action-text {
  color: var(--text-primary);
  font-weight: 500;
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
}

.vacancy-header {
  margin-bottom: 10px;
}

.vacancy-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 18px;
}

.vacancy-header h3 a:hover {
  color: var(--color-primary);
}

.company {
  color: var(--text-secondary);
  font-size: 14px;
}

.vacancy-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 15px;
}

.vacancy-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.skills {
  color: var(--color-primary);
  font-size: 14px;
}

.btn-primary-small {
  padding: 8px 20px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 500;
  cursor: pointer;
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

.btn-outline {
  padding: 12px 24px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
