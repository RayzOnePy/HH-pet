<template>
  <div class="vacancies">
    <h1>Вакансии</h1>
    <div class="filters">
      <input
        type="text"
        placeholder="Поиск вакансий..."
        class="filter-input"
      >
      <select class="filter-select">
        <option>Все города</option>
        <option>Москва</option>
        <option>СПб</option>
      </select>
    </div>

    <div class="vacancies-grid">
      <div v-for="i in 6" :key="i" class="vacancy-card">
        <h3>Frontend Developer</h3>
        <p class="company">TechCorp</p>
        <p class="salary">от 150 000 ₽</p>
        <div class="tags">
          <span class="tag">Vue.js</span>
          <span class="tag">JavaScript</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.vacancies {
  animation: fadeIn 0.5s ease;
}

h1 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xl);
  font-size: var(--font-size-2xl);
}

.filters {
  display: flex;
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-xl);
}

.filter-input {
  flex: 1;
  padding: var(--spacing-md);
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
}

.filter-select {
  padding: var(--spacing-md);
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  min-width: 150px;
}

.vacancies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: var(--spacing-lg);
}

.vacancy-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.vacancy-card:hover {
  transform: translateY(-2px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.vacancy-card h3 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xs);
}

.company {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  margin-bottom: var(--spacing-sm);
}

.salary {
  color: var(--color-primary);
  font-weight: 600;
  font-size: var(--font-size-lg);
  margin-bottom: var(--spacing-md);
}

.tags {
  display: flex;
  gap: var(--spacing-xs);
  flex-wrap: wrap;
}

.tag {
  background-color: var(--bg-secondary);
  color: var(--text-secondary);
  padding: var(--spacing-xs) var(--spacing-sm);
  border-radius: var(--border-radius-sm);
  font-size: var(--font-size-xs);
  border: 1px solid var(--border-color);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
