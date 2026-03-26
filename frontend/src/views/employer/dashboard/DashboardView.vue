<template>
  <div class="dashboard">
    <h1>Панель управления</h1>
    <p class="description">Добро пожаловать в панель работодателя</p>

    <!-- Статистика -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">12</div>
        <div class="stat-label">Активных вакансий</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">48</div>
        <div class="stat-label">Новых откликов</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">1,234</div>
        <div class="stat-label">Просмотров</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">18</div>
        <div class="stat-label">Приглашений</div>
      </div>
    </div>

    <!-- Быстрые действия -->
    <div class="quick-actions">
      <h2>Быстрые действия</h2>
      <div class="actions-grid">
        <router-link to="/employer/vacancies/create" class="action-card">
          <span class="action-icon">➕</span>
          <span class="action-text">Создать вакансию</span>
        </router-link>
        <router-link to="/employer/resumes" class="action-card">
          <span class="action-icon">🔍</span>
          <span class="action-text">Поиск резюме</span>
        </router-link>
        <router-link to="/employer/responses" class="action-card">
          <span class="action-icon">✉️</span>
          <span class="action-text">Отклики</span>
        </router-link>
        <router-link to="/employer/company" class="action-card">
          <span class="action-icon">🏢</span>
          <span class="action-text">Моя компания</span>
        </router-link>
      </div>
    </div>

    <!-- Последние отклики -->
    <div class="recent-responses">
      <h2>Последние отклики</h2>
      <div class="responses-list">
        <div v-for="i in 3" :key="i" class="response-item">
          <div class="response-header">
            <span class="candidate-name">Александр Петров</span>
            <span class="vacancy-title">Senior Frontend Developer</span>
          </div>
          <div class="response-meta">
            <span>⏰ 2 часа назад</span>
            <span class="status new">Новый</span>
          </div>
        </div>
      </div>
      <router-link to="/employer/responses" class="view-all">
        Все отклики →
      </router-link>
    </div>
  </div>
</template>

<script setup>
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

.responses-list {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.response-item {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border-color);
}

.response-item:last-child {
  border-bottom: none;
}

.response-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.candidate-name {
  color: var(--text-primary);
  font-weight: 600;
}

.vacancy-title {
  color: var(--text-secondary);
}

.response-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: var(--text-secondary);
  font-size: 14px;
}

.status {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.status.new {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.view-all {
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 500;
  float: right;
}

.view-all:hover {
  text-decoration: underline;
}
</style>
