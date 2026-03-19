<template>
  <div class="company-page">
    <div class="page-header">
      <h1>Моя компания</h1>
      <router-link to="/employer/company/edit" class="btn-outline">
        ✏️ Редактировать
      </router-link>
    </div>

    <!-- Шапка компании -->
    <div class="company-header">
      <div class="company-logo-large">
        <span>🏢</span>
      </div>
      <div class="company-info">
        <h2>ООО "ТехноКорп"</h2>
        <p class="company-description">
          IT-компания, специализирующаяся на разработке высоконагруженных проектов
        </p>
        <div class="company-meta">
          <span>📍 Москва</span>
          <span>👥 50-100 сотрудников</span>
          <span>📅 Основана в 2015</span>
        </div>
      </div>
    </div>

    <!-- Контакты -->
    <div class="section">
      <h3>Контактная информация</h3>
      <div class="contacts-grid">
        <div class="contact-item">
          <span class="contact-icon">📧</span>
          <div>
            <div class="contact-label">Email для связи</div>
            <div class="contact-value">hr@techcorp.ru</div>
          </div>
        </div>
        <div class="contact-item">
          <span class="contact-icon">📱</span>
          <div>
            <div class="contact-label">Телефон</div>
            <div class="contact-value">+7 (495) 123-45-67</div>
          </div>
        </div>
        <div class="contact-item">
          <span class="contact-icon">🌐</span>
          <div>
            <div class="contact-label">Сайт</div>
            <div class="contact-value">www.techcorp.ru</div>
          </div>
        </div>
      </div>
    </div>

    <!-- О компании -->
    <div class="section">
      <h3>О компании</h3>
      <p class="about-text">
        Мы разрабатываем инновационные решения для бизнеса уже более 8 лет.
        Наша команда состоит из высококлассных специалистов, которые любят свое дело.
        Мы ценим каждого сотрудника и создаем комфортные условия для работы и развития.
      </p>
    </div>

    <!-- Преимущества -->
    <div class="section">
      <h3>Преимущества работы у нас</h3>
      <div class="benefits-grid">
        <div class="benefit-item">
          <span class="benefit-icon">💻</span>
          <span>Удаленная работа</span>
        </div>
        <div class="benefit-item">
          <span class="benefit-icon">💰</span>
          <span>Конкурентная зарплата</span>
        </div>
        <div class="benefit-item">
          <span class="benefit-icon">🏥</span>
          <span>ДМС</span>
        </div>
        <div class="benefit-item">
          <span class="benefit-icon">📚</span>
          <span>Обучение за счет компании</span>
        </div>
      </div>
    </div>

    <!-- Статистика -->
    <div class="section">
      <h3>Статистика</h3>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">12</div>
          <div class="stat-label">Активных вакансий</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">48</div>
          <div class="stat-label">Всего сотрудников</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">156</div>
          <div class="stat-label">Откликов за месяц</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.company-page {
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

.company-header {
  display: flex;
  gap: 30px;
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 30px;
}

.company-logo-large {
  width: 120px;
  height: 120px;
  background: var(--bg-secondary);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 60px;
  border: 2px solid var(--color-primary);
}

.company-info h2 {
  color: var(--text-primary);
  font-size: 28px;
  margin-bottom: 10px;
}

.company-description {
  color: var(--text-secondary);
  margin-bottom: 15px;
  line-height: 1.6;
}

.company-meta {
  display: flex;
  gap: 30px;
  color: var(--text-secondary);
  font-size: 14px;
}

.section {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 30px;
}

.section h3 {
  color: var(--text-primary);
  font-size: 18px;
  margin-bottom: 20px;
}

.contacts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.contact-item {
  display: flex;
  gap: 15px;
  align-items: center;
}

.contact-icon {
  font-size: 24px;
}

.contact-label {
  color: var(--text-secondary);
  font-size: 12px;
  margin-bottom: 4px;
}

.contact-value {
  color: var(--text-primary);
  font-weight: 500;
}

.about-text {
  color: var(--text-secondary);
  line-height: 1.8;
}

.benefits-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
}

.benefit-icon {
  font-size: 20px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
}

.stat-card {
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  text-align: center;
}

.stat-value {
  font-size: 32px;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: 8px;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 14px;
}

.btn-outline {
  padding: 10px 24px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

@media (max-width: 768px) {
  .company-header {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .company-meta {
    flex-direction: column;
    gap: 10px;
  }
}
</style>
