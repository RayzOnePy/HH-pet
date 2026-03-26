<template>
  <div class="about-page">
    <div class="hero">
      <h1>О проекте HHPet</h1>
      <p class="subtitle">Создано для тех, кто ищет работу мечты и талантливых сотрудников</p>
    </div>

    <div class="content">
      <div class="mission-section">
        <div class="mission-icon">🎯</div>
        <h2>Наша миссия</h2>
        <p>
          Помогать талантливым людям находить работу, а компаниям — находить таланты.
          Мы создаем удобный и современный инструмент для поиска работы в IT сфере.
        </p>
      </div>

      <div class="features-section">
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
      </div>

      <div class="stats-section">
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

      <div class="contact-section">
        <h2>Свяжитесь с нами</h2>
        <div class="contact-links">
          <a href="mailto:team@hhpet.ru" class="contact-link">
            📧 team@hhpet.ru
          </a>
          <a href="#" class="contact-link">
            📱 @hhpet_team
          </a>
          <a href="#" class="contact-link">
            🐙 GitHub
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.about-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.hero {
  text-align: center;
  margin-bottom: 48px;
}

h1 {
  color: var(--text-primary);
  font-size: 48px;
  margin-bottom: 16px;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.subtitle {
  color: var(--text-secondary);
  font-size: 18px;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

.content {
  display: flex;
  flex-direction: column;
  gap: 60px;
}

.mission-section {
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.mission-icon {
  font-size: 64px;
  margin-bottom: 20px;
  filter: drop-shadow(var(--shadow-primary));
}

.mission-section h2 {
  color: var(--text-primary);
  font-size: 28px;
  margin-bottom: 16px;
}

.mission-section p {
  color: var(--text-secondary);
  line-height: 1.8;
  font-size: 18px;
}

.features-section h2 {
  text-align: center;
  color: var(--text-primary);
  font-size: 28px;
  margin-bottom: 40px;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 24px;
}

.feature-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 32px 24px;
  text-align: center;
  transition: var(--transition-base);
}

.feature-card:hover {
  border-color: var(--color-primary);
  transform: translateY(-4px);
  box-shadow: var(--shadow-primary);
}

.feature-icon {
  font-size: 48px;
  margin-bottom: 16px;
  display: block;
}

.feature-card h3 {
  color: var(--text-primary);
  margin-bottom: 12px;
  font-size: 20px;
}

.feature-card p {
  color: var(--text-secondary);
  line-height: 1.6;
}

.stats-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 40px;
}

.stat-card {
  text-align: center;
}

.stat-value {
  display: block;
  font-size: 32px;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: 8px;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 14px;
}

.contact-section {
  text-align: center;
}

.contact-section h2 {
  color: var(--text-primary);
  font-size: 28px;
  margin-bottom: 30px;
}

.contact-links {
  display: flex;
  justify-content: center;
  gap: 30px;
  flex-wrap: wrap;
}

.contact-link {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 18px;
  padding: 12px 24px;
  border: 1px solid var(--border-color);
  border-radius: 40px;
  transition: var(--transition-base);
}

.contact-link:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  h1 {
    font-size: 32px;
  }

  .subtitle {
    font-size: 16px;
  }

  .features-grid {
    grid-template-columns: 1fr;
  }

  .stats-section {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .contact-links {
    flex-direction: column;
    align-items: center;
  }

  .contact-link {
    width: 100%;
    text-align: center;
  }
}
</style>
