<template>
  <div class="my-vacancies">
    <div class="page-header">
      <h1>Мои вакансии</h1>
      <router-link to="/employer/vacancies/create" class="btn-primary">
        + Создать вакансию
      </router-link>
    </div>

    <!-- Табы -->
    <div class="tabs">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        class="tab"
        :class="{ active: activeTab === tab.value }"
        @click="activeTab = tab.value"
      >
        {{ tab.label }}
        <span class="count">{{ tab.count }}</span>
      </button>
    </div>

    <!-- Список вакансий -->
    <div class="vacancies-list">
      <div v-for="i in 5" :key="i" class="vacancy-item">
        <div class="vacancy-info">
          <h3>
            <router-link :to="`/employer/vacancies/${i}/candidates`">
              Senior Frontend Developer {{ i }}
            </router-link>
          </h3>
          <div class="meta">
            <span>📍 Москва</span>
            <span>💰 от 250 000 ₽</span>
            <span>📅 Опубликовано 19.03.2026</span>
          </div>
          <div class="stats">
            <span class="stat">👁️ 124 просмотра</span>
            <span class="stat">✉️ 8 откликов</span>
          </div>
        </div>
        <div class="vacancy-actions">
          <button class="icon-btn" @click="editVacancy(i)">✏️</button>
          <button class="icon-btn" @click="toggleArchive(i)">
            {{ i === 1 ? '📦' : '🗑️' }}
          </button>
          <button class="icon-btn" @click="copyLink(i)">🔗</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('active')
const tabs = [
  { value: 'active', label: 'Активные', count: 5 },
  { value: 'archived', label: 'В архиве', count: 2 },
  { value: 'drafts', label: 'Черновики', count: 1 }
]

const editVacancy = (id) => {
  console.log('Edit vacancy:', id)
}

const toggleArchive = (id) => {
  console.log('Toggle archive:', id)
}

const copyLink = (id) => {
  console.log('Copy link:', id)
}
</script>

<style scoped>
.my-vacancies {
  max-width: 1000px;
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

.btn-primary {
  padding: 12px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
  transition: var(--transition-base);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 10px;
}

.tab {
  padding: 8px 20px;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  position: relative;
  font-size: 16px;
}

.tab.active {
  color: var(--color-primary);
}

.tab.active::after {
  content: '';
  position: absolute;
  bottom: -11px;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
}

.count {
  margin-left: 6px;
  padding: 2px 8px;
  background: var(--bg-secondary);
  border-radius: 20px;
  font-size: 12px;
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.vacancy-item {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.vacancy-info h3 {
  margin-bottom: 10px;
}

.vacancy-info h3 a {
  color: var(--text-primary);
  text-decoration: none;
}

.vacancy-info h3 a:hover {
  color: var(--color-primary);
}

.meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}

.stats {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
}

.vacancy-actions {
  display: flex;
  gap: 10px;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  opacity: 0.7;
  transition: var(--transition-base);
  padding: 8px;
  border-radius: 8px;
}

.icon-btn:hover {
  opacity: 1;
  background: var(--bg-secondary);
  transform: scale(1.1);
}
</style>
