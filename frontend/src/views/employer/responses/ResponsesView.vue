<template>
  <div class="responses">
    <h1>Отклики на вакансии</h1>

    <!-- Фильтр по вакансиям -->
    <div class="vacancy-filter">
      <select v-model="selectedVacancy" class="filter-select">
        <option value="all">Все вакансии</option>
        <option v-for="i in 5" :key="i" :value="i">
          Senior Frontend Developer {{ i }}
        </option>
      </select>
    </div>

    <!-- Табы по статусам -->
    <div class="status-tabs">
      <button
        v-for="tab in statusTabs"
        :key="tab.value"
        class="status-tab"
        :class="{ active: activeStatus === tab.value }"
        @click="activeStatus = tab.value"
      >
        {{ tab.label }}
        <span class="count">{{ tab.count }}</span>
      </button>
    </div>

    <!-- Список откликов -->
    <div class="responses-list">
      <div v-for="i in 8" :key="i" class="response-card">
        <div class="response-main">
          <div class="candidate-avatar">👤</div>
          <div class="response-info">
            <div class="response-header">
              <h3>
                <router-link to="/employer/resumes/1">
                  Александр Петров
                </router-link>
              </h3>
              <span class="response-date">2 часа назад</span>
            </div>
            <p class="vacancy-title">
              Отклик на вакансию: <strong>Senior Frontend Developer</strong>
            </p>
            <div class="candidate-details">
              <span>5 лет опыта</span>
              <span>📍 Москва</span>
              <span>💰 от 250 000 ₽</span>
            </div>
            <div class="skills">
              <span class="skill">Vue.js</span>
              <span class="skill">React</span>
              <span class="skill">TypeScript</span>
            </div>
          </div>
        </div>

        <div class="response-actions">
          <div class="status-badge" :class="getStatusClass(i)">
            {{ getStatusText(i) }}
          </div>
          <div class="action-buttons">
            <button class="btn-outline btn-small" @click="changeStatus(i, 'interview')">
              ✅ Пригласить
            </button>
            <button class="btn-outline btn-small" @click="changeStatus(i, 'rejected')">
              ❌ Отказ
            </button>
            <button class="icon-btn" @click="messageCandidate(i)">✉️</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Пагинация -->
    <div class="pagination">
      <button class="page-btn" :disabled="currentPage === 1">←</button>
      <button
        v-for="page in 5"
        :key="page"
        class="page-btn"
        :class="{ active: currentPage === page }"
        @click="currentPage = page"
      >
        {{ page }}
      </button>
      <button class="page-btn" :disabled="currentPage === 5">→</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const selectedVacancy = ref('all')
const activeStatus = ref('new')
const currentPage = ref(1)

const statusTabs = [
  { value: 'new', label: 'Новые', count: 12 },
  { value: 'viewed', label: 'Просмотренные', count: 8 },
  { value: 'interview', label: 'Собеседование', count: 5 },
  { value: 'rejected', label: 'Отказ', count: 4 },
  { value: 'accepted', label: 'Приняты', count: 2 }
]

const getStatusClass = (i) => {
  const statuses = ['new', 'viewed', 'interview', 'rejected', 'accepted']
  return statuses[i % 5]
}

const getStatusText = (i) => {
  const texts = ['Новый', 'Просмотрен', 'Собеседование', 'Отказ', 'Принят']
  return texts[i % 5]
}

const changeStatus = (id, status) => {
  console.log(`Change status for response ${id} to ${status}`)
}

const messageCandidate = (id) => {
  console.log(`Message candidate from response ${id}`)
}
</script>

<style scoped>
.responses {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 30px;
}

.vacancy-filter {
  margin-bottom: 20px;
}

.filter-select {
  padding: 12px 20px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  min-width: 300px;
  font-size: 16px;
}

.status-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 10px;
  overflow-x: auto;
}

.status-tab {
  padding: 8px 20px;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  position: relative;
  white-space: nowrap;
}

.status-tab.active {
  color: var(--color-primary);
}

.status-tab.active::after {
  content: '';
  position: absolute;
  bottom: -11px;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
}

.count {
  margin-left: 8px;
  padding: 2px 8px;
  background: var(--bg-secondary);
  border-radius: 20px;
  font-size: 12px;
}

.responses-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.response-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
}

.response-main {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.candidate-avatar {
  width: 60px;
  height: 60px;
  background: var(--bg-secondary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  border: 2px solid var(--color-primary);
}

.response-info {
  flex: 1;
}

.response-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.response-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
}

.response-header h3 a:hover {
  color: var(--color-primary);
}

.response-date {
  color: var(--text-secondary);
  font-size: 14px;
}

.vacancy-title {
  color: var(--text-secondary);
  margin-bottom: 10px;
}

.vacancy-title strong {
  color: var(--text-primary);
}

.candidate-details {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}

.skills {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.skill {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 12px;
  color: var(--text-secondary);
}

.response-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid var(--border-color);
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.status-badge.new {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.status-badge.viewed {
  background: rgba(100, 100, 100, 0.1);
  color: var(--text-secondary);
}

.status-badge.interview {
  background: rgba(255, 193, 7, 0.1);
  color: #ffc107;
}

.status-badge.rejected {
  background: rgba(244, 67, 54, 0.1);
  color: var(--color-danger);
}

.status-badge.accepted {
  background: rgba(76, 175, 80, 0.1);
  color: #4caf50;
}

.action-buttons {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn-small {
  padding: 6px 16px;
  font-size: 13px;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  opacity: 0.7;
  padding: 8px;
  border-radius: 8px;
}

.icon-btn:hover {
  opacity: 1;
  background: var(--bg-secondary);
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 40px;
}

.page-btn {
  width: 40px;
  height: 40px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-secondary);
  cursor: pointer;
}

.page-btn.active {
  background: var(--gradient-primary);
  color: var(--text-dark);
  border: none;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
