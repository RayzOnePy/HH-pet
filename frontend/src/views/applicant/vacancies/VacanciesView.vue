<template>
  <div class="vacancies-page">
    <h1>Поиск вакансий</h1>

    <!-- Поисковая строка -->
    <div class="search-section">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Должность, компания, ключевые слова..."
          class="search-input"
        >
        <button class="search-btn">🔍</button>
      </div>

      <button class="filter-toggle" @click="showFilters = !showFilters">
        ⚙️ Фильтры
      </button>
    </div>

    <!-- Фильтры -->
    <Transition name="slide">
      <div v-if="showFilters" class="filters-panel">
        <div class="filters-grid">
          <div class="filter-group">
            <label>Зарплата от</label>
            <input type="number" v-model="filters.salary_from" placeholder="100 000">
          </div>
          <div class="filter-group">
            <label>Зарплата до</label>
            <input type="number" v-model="filters.salary_to" placeholder="300 000">
          </div>
          <div class="filter-group">
            <label>Город</label>
            <input type="text" v-model="filters.city" placeholder="Москва">
          </div>
          <div class="filter-group">
            <label>Опыт работы</label>
            <select v-model="filters.experience">
              <option value="">Любой</option>
              <option value="no">Нет опыта</option>
              <option value="1-3">1-3 года</option>
              <option value="3-6">3-6 лет</option>
              <option value="6+">Более 6 лет</option>
            </select>
          </div>
        </div>
        <div class="filters-actions">
          <button class="btn-outline" @click="resetFilters">Сбросить</button>
          <button class="btn-primary" @click="applyFilters">Применить</button>
        </div>
      </div>
    </Transition>

    <!-- Список вакансий -->
    <div class="vacancies-list">
      <div v-for="i in 5" :key="i" class="vacancy-card">
        <div class="vacancy-header">
          <div>
            <h3>
              <router-link :to="`/applicant/vacancies/${i}`">
                Senior Frontend Developer {{ i }}
              </router-link>
            </h3>
            <div class="company">TechCorp</div>
          </div>
          <div class="salary">от 250 000 ₽</div>
        </div>

        <div class="vacancy-tags">
          <span class="tag">Vue.js</span>
          <span class="tag">React</span>
          <span class="tag">TypeScript</span>
        </div>

        <div class="vacancy-meta">
          <span>📍 Москва</span>
          <span>💼 Опыт 3-6 лет</span>
          <span>⏰ Опубликовано сегодня</span>
        </div>

        <div class="vacancy-footer">
          <div class="company-info">
            <span class="company-logo">🏢</span>
            <span>TechCorp • IT</span>
          </div>
          <div class="actions">
            <button class="icon-btn" @click="toggleFavorite(i)">
              {{ favorites[i] ? '⭐' : '☆' }}
            </button>
            <button class="btn-primary-small">Откликнуться</button>
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
import { ref, reactive } from 'vue'

const searchQuery = ref('')
const showFilters = ref(false)
const currentPage = ref(1)
const favorites = ref({})

const filters = reactive({
  salary_from: '',
  salary_to: '',
  city: '',
  experience: ''
})

const resetFilters = () => {
  filters.salary_from = ''
  filters.salary_to = ''
  filters.city = ''
  filters.experience = ''
}

const applyFilters = () => {
  showFilters.value = false
  console.log('Applied filters:', filters)
}

const toggleFavorite = (id) => {
  favorites.value[id] = !favorites.value[id]
}
</script>

<style scoped>
.vacancies-page {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: var(--text-primary);
  margin-bottom: 30px;
}

.search-section {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.search-box {
  flex: 1;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 16px 24px;
  padding-right: 60px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  font-size: 16px;
}

.search-input:focus {
  border-color: var(--color-primary);
  outline: none;
}

.search-btn {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 20px;
  color: var(--text-secondary);
}

.filter-toggle {
  padding: 0 30px;
  background: var(--bg-tertiary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  cursor: pointer;
}

.filters-panel {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 30px;
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  color: var(--text-secondary);
  font-size: 14px;
}

.filter-group input,
.filter-group select {
  padding: 10px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
}

.filters-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
}

.vacancies-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 20px;
}

.vacancy-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  transition: var(--transition-base);
}

.vacancy-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.vacancy-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.vacancy-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 20px;
}

.vacancy-header h3 a:hover {
  color: var(--color-primary);
}

.company {
  color: var(--text-secondary);
  margin-top: 4px;
}

.salary {
  color: var(--color-primary);
  font-size: 20px;
  font-weight: 600;
}

.vacancy-tags {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

.tag {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 13px;
  color: var(--text-secondary);
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
  padding-top: 15px;
  border-top: 1px solid var(--border-color);
}

.company-info {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--text-secondary);
}

.company-logo {
  font-size: 20px;
}

.actions {
  display: flex;
  gap: 15px;
  align-items: center;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  opacity: 0.7;
  transition: var(--transition-base);
}

.icon-btn:hover {
  opacity: 1;
  transform: scale(1.1);
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
