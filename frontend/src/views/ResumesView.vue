<template>
  <div class="resumes">
    <h1>Резюме</h1>

    <div class="filters">
      <input
        type="text"
        placeholder="Поиск кандидатов..."
        class="filter-input"
      >
      <select class="filter-select">
        <option>Все специальности</option>
        <option>Разработка</option>
        <option>Дизайн</option>
        <option>Аналитика</option>
      </select>
      <select class="filter-select">
        <option>Опыт любой</option>
        <option>Junior</option>
        <option>Middle</option>
        <option>Senior</option>
      </select>
    </div>

    <div class="resumes-grid">
      <div v-for="i in 6" :key="i" class="resume-card">
        <div class="resume-header">
          <div class="candidate-avatar">
            <span class="avatar-placeholder">👤</span>
          </div>
          <div class="candidate-info">
            <h3>Александр Петров</h3>
            <p class="position">Frontend Developer</p>
          </div>
          <div class="resume-price">от 150 000 ₽</div>
        </div>

        <div class="resume-details">
          <div class="details-row">
            <span class="detail-item">📍 Москва</span>
            <span class="detail-item">💼 3 года</span>
            <span class="detail-item">🎓 Высшее</span>
          </div>

          <div class="skills">
            <span class="skill">Vue.js</span>
            <span class="skill">React</span>
            <span class="skill">TypeScript</span>
            <span class="skill">Pinia</span>
            <span class="skill">Webpack</span>
          </div>

          <p class="resume-description">
            Ищу интересные проекты в современной команде. Есть опыт разработки SPA и PWA приложений...
          </p>
        </div>

        <div class="resume-footer">
          <span class="update-date">Обновлено сегодня</span>
          <button class="contact-btn">Контакты</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.resumes {
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
  flex-wrap: wrap;
}

.filter-input {
  flex: 2;
  min-width: 250px;
  padding: var(--spacing-md);
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  transition: var(--transition-base);
}

.filter-input:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
}

.filter-select {
  flex: 1;
  min-width: 150px;
  padding: var(--spacing-md);
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  transition: var(--transition-base);
}

.filter-select:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
}

.resumes-grid {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.resume-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.resume-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
}

.resume-header {
  display: flex;
  align-items: center;
  gap: var(--spacing-lg);
  margin-bottom: var(--spacing-lg);
  padding-bottom: var(--spacing-lg);
  border-bottom: 1px solid var(--border-color);
}

.candidate-avatar {
  width: 60px;
  height: 60px;
  background-color: var(--bg-secondary);
  border-radius: var(--border-radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid var(--color-primary);
}

.avatar-placeholder {
  font-size: 30px;
}

.candidate-info {
  flex: 1;
}

.candidate-info h3 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xs);
  font-size: var(--font-size-lg);
}

.position {
  color: var(--color-primary);
  font-size: var(--font-size-sm);
  font-weight: 500;
}

.resume-price {
  color: var(--text-primary);
  font-weight: 600;
  font-size: var(--font-size-lg);
  background: var(--gradient-primary);
  padding: var(--spacing-xs) var(--spacing-lg);
  border-radius: var(--border-radius-full);
  color: var(--text-dark);
}

.resume-details {
  margin-bottom: var(--spacing-lg);
}

.details-row {
  display: flex;
  gap: var(--spacing-xl);
  margin-bottom: var(--spacing-lg);
  flex-wrap: wrap;
}

.detail-item {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
}

.skills {
  display: flex;
  gap: var(--spacing-xs);
  flex-wrap: wrap;
  margin-bottom: var(--spacing-lg);
}

.skill {
  background-color: var(--bg-secondary);
  color: var(--text-secondary);
  padding: var(--spacing-xs) var(--spacing-md);
  border-radius: var(--border-radius-full);
  font-size: var(--font-size-xs);
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.skill:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.resume-description {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  line-height: 1.6;
}

.resume-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: var(--spacing-lg);
  padding-top: var(--spacing-lg);
  border-top: 1px solid var(--border-color);
}

.update-date {
  color: var(--text-muted);
  font-size: var(--font-size-xs);
}

.contact-btn {
  padding: var(--spacing-sm) var(--spacing-xl);
  background: transparent;
  border: 1px solid var(--color-primary);
  border-radius: var(--border-radius-full);
  color: var(--color-primary);
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition-base);
}

.contact-btn:hover {
  background-color: var(--color-primary);
  color: var(--text-dark);
  box-shadow: var(--shadow-primary);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
  .filters {
    flex-direction: column;
  }

  .filter-input,
  .filter-select {
    width: 100%;
  }

  .resume-header {
    flex-direction: column;
    text-align: center;
  }

  .details-row {
    flex-direction: column;
    gap: var(--spacing-xs);
    align-items: center;
  }

  .skills {
    justify-content: center;
  }

  .resume-footer {
    flex-direction: column;
    gap: var(--spacing-md);
  }
}
</style>
