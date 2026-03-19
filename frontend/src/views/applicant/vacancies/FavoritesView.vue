<template>
  <div class="favorites-page">
    <h1>Избранные вакансии</h1>

    <div v-if="favorites.length === 0" class="empty-state">
      <div class="empty-icon">⭐</div>
      <h3>У вас пока нет избранных вакансий</h3>
      <p>Добавляйте вакансии в избранное, чтобы не потерять</p>
      <router-link to="/applicant/vacancies" class="btn-primary">
        Найти вакансии
      </router-link>
    </div>

    <div v-else class="favorites-list">
      <div v-for="i in 3" :key="i" class="vacancy-card">
        <div class="vacancy-header">
          <div>
            <h3>
              <router-link to="/applicant/vacancies/1">
                Senior Frontend Developer
              </router-link>
            </h3>
            <div class="company">TechCorp</div>
          </div>
          <button class="favorite-btn active" @click="removeFavorite(i)">
            ⭐
          </button>
        </div>

        <div class="vacancy-meta">
          <span>📍 Москва</span>
          <span>💰 от 250 000 ₽</span>
        </div>

        <div class="vacancy-tags">
          <span class="tag">Vue.js</span>
          <span class="tag">TypeScript</span>
        </div>

        <div class="vacancy-footer">
          <span class="date">Добавлено 19.03.2026</span>
          <button class="btn-primary-small">Откликнуться</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const favorites = ref([1, 2, 3]) // заглушка

const removeFavorite = (id) => {
  console.log('Remove favorite:', id)
}
</script>

<style scoped>
.favorites-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 30px;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-tertiary);
  border-radius: 16px;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-state h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.favorites-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 20px;
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 10px;
}

.vacancy-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
}

.vacancy-header h3 a:hover {
  color: var(--color-primary);
}

.company {
  color: var(--text-secondary);
  font-size: 14px;
  margin-top: 4px;
}

.favorite-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  opacity: 0.7;
  transition: var(--transition-base);
}

.favorite-btn:hover,
.favorite-btn.active {
  opacity: 1;
  transform: scale(1.1);
}

.vacancy-meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}

.vacancy-tags {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

.tag {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 12px;
  color: var(--text-secondary);
}

.vacancy-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid var(--border-color);
}

.date {
  color: var(--text-secondary);
  font-size: 13px;
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
</style>
