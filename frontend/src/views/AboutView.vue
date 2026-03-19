<template>
  <div class="about">
    <div class="about-header">
      <h1>О проекте HHPet</h1>
      <p class="subtitle">Создано для тех, кто ищет работу мечты</p>
    </div>

    <div class="about-content">
      <section class="mission-section">
        <div class="mission-icon">🎯</div>
        <h2>Наша миссия</h2>
        <p>Помогать талантливым людям находить работу, а компаниям — находить таланты. Мы создаем удобный и современный инструмент для поиска работы в IT сфере.</p>
      </section>

      <div class="stats-grid">
        <div class="stat-card">
          <span class="stat-value">2026</span>
          <span class="stat-label">Год основания</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">10K+</span>
          <span class="stat-label">Пользователей</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">5K+</span>
          <span class="stat-label">Компаний</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">24/7</span>
          <span class="stat-label">Поддержка</span>
        </div>
      </div>

      <section class="features-section">
        <h2>Почему выбирают нас</h2>
        <div class="features-grid">
          <div class="feature-card">
            <span class="feature-icon">🚀</span>
            <h3>Быстрый поиск</h3>
            <p>Умный алгоритм подбирает релевантные вакансии</p>
          </div>
          <div class="feature-card">
            <span class="feature-icon">🛡️</span>
            <h3>Проверенные компании</h3>
            <p>Только валидные вакансии от реальных работодателей</p>
          </div>
          <div class="feature-card">
            <span class="feature-icon">💬</span>
            <h3>Прямой контакт</h3>
            <p>Общайтесь напрямую с нанимателями</p>
          </div>
          <div class="feature-card">
            <span class="feature-icon">📱</span>
            <h3>Удобный интерфейс</h3>
            <p>Современный дизайн и интуитивная навигация</p>
          </div>
        </div>
      </section>

      <section class="team-section">
        <h2>Наша команда</h2>
        <div class="team-grid">
          <div class="team-card">
            <div class="team-avatar">👨‍💻</div>
            <h3>Алексей</h3>
            <p>Fullstack разработчик</p>
          </div>
          <div class="team-card">
            <div class="team-avatar">👩‍🎨</div>
            <h3>Анна</h3>
            <p>UI/UX дизайнер</p>
          </div>
          <div class="team-card">
            <div class="team-avatar">👨‍💼</div>
            <h3>Дмитрий</h3>
            <p>Project Manager</p>
          </div>
        </div>
      </section>

      <section class="tech-section">
        <h2>Технологии</h2>
        <div class="tech-stack">
          <span class="tech-item">Vue.js</span>
          <span class="tech-item">Laravel</span>
          <span class="tech-item">Docker</span>
          <span class="tech-item">PostgreSQL</span>
          <span class="tech-item">Redis</span>
          <span class="tech-item">Pinia</span>
        </div>
      </section>

      <section class="contact-section">
        <h2>Свяжитесь с нами</h2>
        <div class="contact-links">
          <a href="#" class="contact-link">📧 team@hhpet.ru</a>
          <a href="#" class="contact-link">📱 @hhpet_team</a>
          <a href="#" class="contact-link">🐙 GitHub</a>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
.about {
  animation: fadeIn 0.5s ease;
  max-width: 1000px;
  margin: 0 auto;
}

.about-header {
  text-align: center;
  margin-bottom: var(--spacing-3xl);
  padding: var(--spacing-2xl) 0;
  background: var(--gradient-dark);
  border-radius: var(--border-radius-xl);
  border: 1px solid var(--border-color);
}

.about-header h1 {
  font-size: var(--font-size-3xl);
  color: var(--text-primary);
  margin-bottom: var(--spacing-md);
}

.subtitle {
  color: var(--text-secondary);
  font-size: var(--font-size-lg);
}

.about-content {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-3xl);
}

.mission-section {
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.mission-icon {
  font-size: 64px;
  margin-bottom: var(--spacing-md);
  filter: drop-shadow(var(--shadow-primary));
}

.mission-section h2 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-md);
  font-size: var(--font-size-xl);
}

.mission-section p {
  color: var(--text-secondary);
  line-height: 1.8;
  font-size: var(--font-size-md);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-lg);
}

.stat-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  text-align: center;
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.stat-card:hover {
  transform: translateY(-4px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.stat-value {
  display: block;
  font-size: var(--font-size-2xl);
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: var(--spacing-xs);
  text-shadow: 0 0 20px var(--color-primary);
}

.stat-label {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.features-section h2,
.team-section h2,
.tech-section h2,
.contact-section h2 {
  text-align: center;
  color: var(--text-primary);
  margin-bottom: var(--spacing-2xl);
  font-size: var(--font-size-xl);
}

.features-grid,
.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--spacing-lg);
}

.feature-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  text-align: center;
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.feature-card:hover {
  transform: translateY(-4px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.feature-icon {
  font-size: 48px;
  margin-bottom: var(--spacing-md);
  display: block;
}

.feature-card h3 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-sm);
  font-size: var(--font-size-lg);
}

.feature-card p {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  line-height: 1.6;
}

.team-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  text-align: center;
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.team-card:hover {
  transform: translateY(-4px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.team-avatar {
  width: 100px;
  height: 100px;
  margin: 0 auto var(--spacing-lg);
  background-color: var(--bg-secondary);
  border-radius: var(--border-radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 50px;
  border: 2px solid var(--color-primary);
}

.team-card h3 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xs);
  font-size: var(--font-size-lg);
}

.team-card p {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.tech-stack {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-md);
  justify-content: center;
}

.tech-item {
  background-color: var(--bg-tertiary);
  color: var(--text-secondary);
  padding: var(--spacing-sm) var(--spacing-lg);
  border-radius: var(--border-radius-full);
  font-size: var(--font-size-sm);
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
}

.tech-item:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  transform: translateY(-2px);
}

.contact-links {
  display: flex;
  justify-content: center;
  gap: var(--spacing-xl);
  flex-wrap: wrap;
}

.contact-link {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: var(--font-size-lg);
  padding: var(--spacing-sm) var(--spacing-xl);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-full);
  transition: var(--transition-base);
}

.contact-link:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
  .contact-links {
    flex-direction: column;
    align-items: center;
  }

  .contact-link {
    width: 100%;
    text-align: center;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
