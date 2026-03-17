<template>
  <div class="home">
    <section class="hero">
      <h1 class="hero-title">Найди работу мечты</h1>
      <p class="hero-subtitle">Тысячи вакансий в IT и не только</p>

      <div class="search-large">
        <input
          type="text"
          placeholder="Должность, компания или ключевое слово"
          class="search-large-input"
        >
        <button class="search-large-btn">Найти</button>
      </div>

      <div class="stats">
        <div class="stat-item">
          <span class="stat-number">10K+</span>
          <span class="stat-label">Вакансий</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">5K+</span>
          <span class="stat-label">Компаний</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">50K+</span>
          <span class="stat-label">Резюме</span>
        </div>
      </div>
    </section>

    <section class="categories">
      <h2>Популярные категории</h2>
      <div class="category-grid">
        <div class="category-card">
          <span class="category-icon">💻</span>
          <h3>Разработка</h3>
          <p>2,345 вакансий</p>
        </div>
        <div class="category-card">
          <span class="category-icon">📊</span>
          <h3>Аналитика</h3>
          <p>1,234 вакансий</p>
        </div>
        <div class="category-card">
          <span class="category-icon">🎨</span>
          <h3>Дизайн</h3>
          <p>987 вакансий</p>
        </div>
        <div class="category-card">
          <span class="category-icon">📈</span>
          <h3>Маркетинг</h3>
          <p>1,567 вакансий</p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.home {
  animation: fadeIn 0.5s ease;
}

.hero {
  text-align: center;
  padding: var(--spacing-3xl) 0;
  background: var(--gradient-dark);
  border-radius: var(--border-radius-xl);
  margin-bottom: var(--spacing-2xl);
  position: relative;
  overflow: hidden;
}

.hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(0,255,136,0.1) 0%, transparent 50%);
  animation: rotate 20s linear infinite;
}

.hero-title {
  font-size: var(--font-size-4xl);
  color: var(--text-primary);
  margin-bottom: var(--spacing-md);
  position: relative;
}

.hero-subtitle {
  font-size: var(--font-size-xl);
  color: var(--text-secondary);
  margin-bottom: var(--spacing-2xl);
  position: relative;
}

.search-large {
  display: flex;
  max-width: 600px;
  margin: 0 auto var(--spacing-2xl);
  position: relative;
  gap: var(--spacing-sm);
}

.search-large-input {
  flex: 1;
  padding: var(--spacing-lg);
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-full);
  font-size: var(--font-size-md);
  color: var(--text-primary);
  transition: var(--transition-base);
}

.search-large-input:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
}

.search-large-btn {
  padding: var(--spacing-lg) var(--spacing-2xl);
  background: var(--gradient-primary);
  border: none;
  border-radius: var(--border-radius-full);
  color: var(--text-dark);
  font-weight: 600;
  font-size: var(--font-size-md);
  cursor: pointer;
  transition: var(--transition-base);
  box-shadow: var(--shadow-primary);
}

.search-large-btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.stats {
  display: flex;
  justify-content: center;
  gap: var(--spacing-2xl);
  position: relative;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.stat-number {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  color: var(--color-primary);
  text-shadow: 0 0 20px var(--color-primary);
}

.stat-label {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.categories {
  padding: var(--spacing-2xl) 0;
}

.categories h2 {
  text-align: center;
  margin-bottom: var(--spacing-2xl);
  font-size: var(--font-size-2xl);
  color: var(--text-primary);
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--spacing-lg);
}

.category-card {
  background-color: var(--bg-tertiary);
  padding: var(--spacing-xl);
  border-radius: var(--border-radius-lg);
  text-align: center;
  border: 1px solid var(--border-color);
  transition: var(--transition-base);
  cursor: pointer;
}

.category-card:hover {
  transform: translateY(-4px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.category-icon {
  font-size: 48px;
  margin-bottom: var(--spacing-md);
  display: block;
}

.category-card h3 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xs);
  font-size: var(--font-size-lg);
}

.category-card p {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: var(--font-size-3xl);
  }

  .hero-subtitle {
    font-size: var(--font-size-lg);
  }

  .search-large {
    flex-direction: column;
    padding: 0 var(--spacing-md);
  }

  .stats {
    flex-direction: column;
    gap: var(--spacing-lg);
  }
}
</style>
