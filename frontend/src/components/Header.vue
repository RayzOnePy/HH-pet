<template>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <!-- Логотип -->
        <router-link to="/" class="logo">
          <span class="logo-icon">💼</span>
          <span class="logo-text">HH<span class="logo-accent">Pet</span></span>
        </router-link>

        <!-- Навигация -->
        <nav class="nav">
          <router-link to="/" class="nav-link" active-class="active">
            Вакансии
          </router-link>
          <router-link to="/companies" class="nav-link" active-class="active">
            Компании
          </router-link>
          <router-link to="/resumes" class="nav-link" active-class="active">
            Резюме
          </router-link>
          <router-link to="/about" class="nav-link" active-class="active">
            О проекте
          </router-link>
        </nav>

        <!-- Поиск (просто для дизайна) -->
        <div class="search">
          <input
            type="text"
            placeholder="Поиск вакансий..."
            class="search-input"
            readonly
          >
          <button class="search-btn">
            🔍
          </button>
        </div>

        <!-- Кнопки входа -->
        <div class="auth-buttons">
          <button class="btn btn-outline">Войти</button>
          <button class="btn btn-primary">Регистрация</button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
// Пока пусто
</script>

<style scoped>
.header {
  background-color: var(--bg-primary);
  box-shadow: var(--shadow-sm);
  position: sticky;
  top: 0;
  z-index: 100;
  height: var(--header-height);
  border-bottom: 1px solid var(--border-color);
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: var(--header-height);
  gap: var(--spacing-xl);
}

/* Логотип */
.logo {
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
  font-weight: 700;
  transition: opacity 0.2s;
}

.logo:hover {
  opacity: 0.9;
}

.logo-icon {
  font-size: 24px;
}

.logo-text {
  font-size: var(--font-size-xl);
  color: var(--color-gray-600);
}

.logo-accent {
  color: var(--color-primary);
}

/* Навигация */
.nav {
  display: flex;
  gap: var(--spacing-lg);
  flex: 1;
  justify-content: center;
}

.nav-link {
  color: var(--text-secondary);
  font-weight: 500;
  padding: var(--spacing-sm) var(--spacing-xs);
  position: relative;
  transition: color 0.2s;
  white-space: nowrap;
}

.nav-link:hover {
  color: var(--color-primary);
}

.nav-link.active {
  color: var(--color-primary);
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background-color: var(--color-primary);
  border-radius: var(--border-radius-sm);
}

/* Поиск */
.search {
  display: flex;
  align-items: center;
  position: relative;
  min-width: 250px;
}

.search-input {
  width: 100%;
  padding: var(--spacing-sm) var(--spacing-md);
  padding-right: 40px;
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  font-size: var(--font-size-sm);
  transition: all 0.2s;
  color: var(--text-primary);
}

.search-input:focus {
  border-color: var(--color-primary);
  background-color: var(--bg-primary);
  box-shadow: var(--shadow-sm);
}

.search-input::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.search-input[readonly] {
  cursor: default;
  background-color: var(--bg-secondary);
}

.search-btn {
  position: absolute;
  right: var(--spacing-xs);
  background: none;
  border: none;
  padding: var(--spacing-xs);
  cursor: default;
  font-size: 18px;
  opacity: 0.5;
}

/* Кнопки авторизации */
.auth-buttons {
  display: flex;
  gap: var(--spacing-sm);
  align-items: center;
}

.btn {
  padding: var(--spacing-sm) var(--spacing-lg);
  border-radius: var(--border-radius-md);
  font-weight: 500;
  font-size: var(--font-size-sm);
  transition: all 0.2s;
  cursor: default; /* Пока просто для дизайна */
  white-space: nowrap;
}

.btn-primary {
  background-color: var(--color-primary);
  color: white;
}

.btn-primary:hover {
  background-color: var(--color-primary-dark);
}

.btn-outline {
  border: 1px solid var(--color-primary);
  color: var(--color-primary);
  background: transparent;
}

.btn-outline:hover {
  background-color: var(--color-primary-light);
}

/* Адаптивность */
@media (max-width: 1024px) {
  .search {
    min-width: 200px;
  }
}

@media (max-width: 768px) {
  .header-content {
    gap: var(--spacing-md);
  }

  .logo-text {
    display: none;
  }

  .nav {
    gap: var(--spacing-sm);
  }

  .search {
    display: none;
  }
}
</style>
