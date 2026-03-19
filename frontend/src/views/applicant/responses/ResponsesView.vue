<template>
  <div class="responses-page">
    <h1>Мои отклики</h1>

    <div class="tabs">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        class="tab"
        :class="{ active: activeTab === tab.value }"
        @click="activeTab = tab.value"
      >
        {{ tab.label }} ({{ tab.count }})
      </button>
    </div>

    <div class="responses-list">
      <div v-for="i in 5" :key="i" class="response-card">
        <div class="response-header">
          <div>
            <h3>
              <router-link to="/applicant/vacancies/1">
                Senior Frontend Developer
              </router-link>
            </h3>
            <p class="company">TechCorp</p>
          </div>
          <div class="response-status" :class="getStatusClass(i)">
            {{ getStatusText(i) }}
          </div>
        </div>

        <div class="response-meta">
          <span>📅 Откликнулся 19.03.2026</span>
          <span>⏰ 2 дня назад</span>
        </div>

        <div class="response-actions">
          <button class="btn-outline-small" @click="showMessage = i">
            ✉️ Написать
          </button>
          <button class="btn-outline-small" @click="cancelResponse(i)">
            Отменить отклик
          </button>
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
  { value: 'invited', label: 'Приглашения', count: 2 },
  { value: 'rejected', label: 'Отказ', count: 1 },
  { value: 'archived', label: 'Архив', count: 3 }
]

const getStatusClass = (i) => {
  const statuses = ['pending', 'invited', 'rejected', 'accepted']
  return statuses[i % 4]
}

const getStatusText = (i) => {
  const texts = ['На рассмотрении', 'Приглашение', 'Отказ', 'Принят']
  return texts[i % 4]
}

const cancelResponse = (id) => {
  console.log('Cancel response:', id)
}
</script>

<style scoped>
.responses-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 30px;
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

.responses-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.response-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 20px;
}

.response-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 10px;
}

.response-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
}

.response-header h3 a:hover {
  color: var(--color-primary);
}

.company {
  color: var(--text-secondary);
  font-size: 14px;
  margin-top: 4px;
}

.response-status {
  padding: 4px 12px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 500;
}

.response-status.pending {
  background: rgba(255, 193, 7, 0.1);
  color: #ffc107;
}

.response-status.invited {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.response-status.rejected {
  background: rgba(244, 67, 54, 0.1);
  color: var(--color-danger);
}

.response-status.accepted {
  background: rgba(76, 175, 80, 0.1);
  color: #4caf50;
}

.response-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 15px;
}

.response-actions {
  display: flex;
  gap: 15px;
  padding-top: 15px;
  border-top: 1px solid var(--border-color);
}

.btn-outline-small {
  padding: 6px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  cursor: pointer;
  font-size: 13px;
}

.btn-outline-small:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
