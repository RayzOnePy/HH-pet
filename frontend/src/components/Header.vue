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

        <!-- Поиск -->
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
          <button class="btn btn-outline" @click="$emit('open-login')">
            Войти
          </button>
          <button class="btn btn-primary" @click="$emit('open-register')">
            Регистрация
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
.header {
  background-color: var(--bg-secondary);
  box-shadow: var(--shadow-md), var(--shadow-primary);
  position: sticky;
  top: 0;
  z-index: var(--z-sticky);
  height: var(--header-height);
  border-bottom: 1px solid var(--border-color);
  backdrop-filter: var(--blur-sm);
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
  transition: var(--transition-base);
  position: relative;
}

.logo:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

.logo-icon {
  font-size: 24px;
  filter: drop-shadow(var(--shadow-primary));
}

.logo-text {
  font-size: var(--font-size-xl);
  color: var(--text-primary);
  letter-spacing: -0.5px;
}

.logo-accent {
  color: var(--color-primary);
  text-shadow: 0 0 10px var(--color-primary);
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
  transition: var(--transition-base);
  white-space: nowrap;
}

.nav-link:hover {
  color: var(--color-primary);
  text-shadow: 0 0 8px var(--color-primary);
}

.nav-link.active {
  color: var(--color-primary);
  text-shadow: 0 0 10px var(--color-primary);
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
  border-radius: var(--border-radius-full);
  box-shadow: var(--shadow-primary);
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
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-full);
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
  color: var(--text-primary);
}

.search-input:focus {
  border-color: var(--color-primary);
  background-color: var(--bg-primary);
  box-shadow: var(--shadow-primary);
}

.search-input::placeholder {
  color: var(--text-muted);
  opacity: 0.7;
}

.search-input[readonly] {
  cursor: default;
  background-color: var(--bg-tertiary);
}

.search-btn {
  position: absolute;
  right: var(--spacing-xs);
  background: none;
  border: none;
  padding: var(--spacing-xs);
  cursor: default;
  font-size: 18px;
  opacity: 0.7;
  color: var(--text-secondary);
}

/* Кнопки авторизации */
.auth-buttons {
  display: flex;
  gap: var(--spacing-sm);
  align-items: center;
}

.btn {
  padding: var(--spacing-sm) var(--spacing-lg);
  border-radius: var(--border-radius-full);
  font-weight: 500;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
  cursor: default;
  white-space: nowrap;
  position: relative;
  overflow: hidden;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.btn:hover::before {
  left: 100%;
}

.btn-primary {
  background: var(--gradient-primary);
  color: var(--text-dark);
  font-weight: 600;
  box-shadow: var(--shadow-primary);
}

.btn-primary:hover {
  box-shadow: var(--shadow-primary-lg);
  transform: translateY(-2px);
}

.btn-outline {
  border: 1px solid var(--color-primary);
  color: var(--color-primary);
  background: transparent;
  box-shadow: 0 0 10px transparent;
}

.btn-outline:hover {
  background-color: rgba(0, 255, 136, 0.1);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
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
