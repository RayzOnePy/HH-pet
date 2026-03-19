<template>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <!-- Логотип -->
        <router-link to="/" class="logo">
          <span class="logo-icon">💼</span>
          <span class="logo-text">HH<span class="logo-accent">Pet</span></span>
        </router-link>

        <!-- Навигация для неавторизованных -->
        <nav v-if="!user" class="nav">
          <router-link to="/vacancies" class="nav-link" active-class="active">
            Вакансии
          </router-link>
          <router-link to="/about" class="nav-link" active-class="active">
            О проекте
          </router-link>
        </nav>

        <!-- Навигация для соискателя -->
        <nav v-else-if="user?.role === 'applicant'" class="nav">
          <router-link to="/applicant/dashboard" class="nav-link" active-class="active">
            Панель
          </router-link>
          <router-link to="/applicant/vacancies" class="nav-link" active-class="active">
            Вакансии
          </router-link>
          <router-link to="/applicant/responses" class="nav-link" active-class="active">
            Отклики
          </router-link>
          <router-link to="/applicant/vacancies/favorites" class="nav-link" active-class="active">
            Избранное
          </router-link>
          <router-link to="/applicant/resume" class="nav-link" active-class="active">
            Резюме
          </router-link>
        </nav>

        <!-- Навигация для работодателя -->
        <nav v-else-if="user?.role === 'employer'" class="nav">
          <router-link to="/employer/dashboard" class="nav-link" active-class="active">
            Панель
          </router-link>
          <router-link to="/employer/vacancies" class="nav-link" active-class="active">
            Мои вакансии
          </router-link>
          <router-link to="/employer/resumes" class="nav-link" active-class="active">
            Резюме
          </router-link>
          <router-link to="/employer/responses" class="nav-link" active-class="active">
            Отклики
          </router-link>
          <router-link to="/employer/company" class="nav-link" active-class="active">
            Моя компания
          </router-link>
        </nav>

        <!-- Поиск (для всех) -->
        <div class="search">
          <input
              type="text"
              placeholder="Поиск вакансий..."
              class="search-input"
              readonly
          >
          <button class="search-btn">🔍</button>
        </div>

        <!-- Блок для авторизованных пользователей -->
        <div v-if="user" class="user-actions">
          <!-- Уведомления -->
          <button class="icon-btn">
            <span class="icon">🔔</span>
            <span class="badge">3</span>
          </button>

          <!-- Чаты -->
          <button class="icon-btn">
            <span class="icon">✉️</span>
            <span class="badge">2</span>
          </button>

          <!-- Профиль -->
          <div class="user-menu">
            <button class="user-button" @click="showUserMenu = !showUserMenu">
              <span class="user-avatar">👤</span>
              <span class="user-name">{{ user.first_name || 'Пользователь' }}</span>
              <span class="user-arrow">▼</span>
            </button>

            <Transition name="dropdown">
              <div v-if="showUserMenu" class="user-dropdown">
                <router-link
                    :to="user?.role === 'applicant' ? '/applicant/resume' : '/employer/company'"
                    class="dropdown-item"
                    @click="showUserMenu = false"
                >
                  <span>👤 Мой профиль</span>
                </router-link>
                <router-link to="/settings" class="dropdown-item" @click="showUserMenu = false">
                  <span>⚙️ Настройки</span>
                </router-link>
                <div class="dropdown-divider"></div>
                <button @click="handleLogout" class="dropdown-item logout">
                  <span>🚪 Выйти</span>
                </button>
              </div>
            </Transition>
          </div>
        </div>

        <!-- Кнопки входа для неавторизованных -->
        <div v-else class="auth-buttons">
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

<script setup>
import { ref, onUnmounted } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['open-login', 'open-register', 'logout'])

const showUserMenu = ref(false)

const handleLogout = () => {
  showUserMenu.value = false
  emit('logout')
}

// Закрытие меню при клике вне
const handleClickOutside = (event) => {
  if (!event.target.closest('.user-menu')) {
    showUserMenu.value = false
  }
}

window.addEventListener('click', handleClickOutside)
onUnmounted(() => window.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.header {
  background-color: var(--bg-tertiary);
  box-shadow: var(--shadow-md);
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
  text-decoration: none;
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
  text-decoration: none;
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
  background-color: var(--bg-secondary);
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
  outline: none;
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

/* Блок действий пользователя */
.user-actions {
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
}

.icon-btn {
  position: relative;
  background: none;
  border: none;
  cursor: pointer;
  padding: var(--spacing-xs);
  color: var(--text-secondary);
  transition: var(--transition-base);
}

.icon-btn:hover {
  color: var(--color-primary);
  transform: translateY(-2px);
}

.icon {
  font-size: 20px;
}

.badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: var(--color-danger);
  color: white;
  font-size: 10px;
  font-weight: bold;
  min-width: 16px;
  height: 16px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 4px;
  border: 2px solid var(--bg-tertiary);
}

/* Меню пользователя */
.user-menu {
  position: relative;
}

.user-button {
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
  padding: var(--spacing-xs) var(--spacing-md);
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-full);
  color: var(--text-primary);
  cursor: pointer;
  transition: var(--transition-base);
  height: 40px;
}

.user-button:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  background-color: var(--bg-tertiary);
}

.user-avatar {
  font-size: 20px;
  filter: drop-shadow(var(--shadow-primary));
}

.user-name {
  font-weight: 500;
  font-size: var(--font-size-sm);
  max-width: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.user-arrow {
  font-size: 12px;
  transition: transform 0.3s ease;
  color: var(--text-secondary);
}

.user-button:hover .user-arrow {
  transform: translateY(2px);
  color: var(--color-primary);
}

.user-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  min-width: 200px;
  background-color: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-lg), var(--shadow-primary);
  z-index: 1000;
  overflow: hidden;
  backdrop-filter: var(--blur-sm);
}

.dropdown-item {
  display: block;
  padding: var(--spacing-md) var(--spacing-lg);
  color: var(--text-secondary);
  text-decoration: none;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
  text-align: left;
  width: 100%;
  border: none;
  background: none;
  cursor: pointer;
  white-space: nowrap;
}

.dropdown-item:hover {
  background-color: var(--bg-secondary);
  color: var(--color-primary);
}

.dropdown-item.logout:hover {
  color: var(--color-danger);
}

.dropdown-divider {
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border-color), transparent);
  margin: var(--spacing-xs) 0;
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
  cursor: pointer;
  white-space: nowrap;
}

.btn-primary {
  background: var(--gradient-primary);
  color: var(--text-dark);
  font-weight: 600;
  box-shadow: var(--shadow-primary);
  border: none;
}

.btn-primary:hover {
  box-shadow: var(--shadow-primary-lg);
  transform: translateY(-2px);
}

.btn-outline {
  border: 1px solid var(--color-primary);
  color: var(--color-primary);
  background: transparent;
}

.btn-outline:hover {
  background-color: rgba(0, 255, 136, 0.1);
  box-shadow: var(--shadow-primary);
  transform: translateY(-2px);
}

/* Анимация для дропдауна */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Адаптивность */
@media (max-width: 1024px) {
  .search {
    min-width: 200px;
  }

  .user-name {
    max-width: 80px;
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

  .btn {
    padding: var(--spacing-sm) var(--spacing-md);
  }

  .user-name {
    display: none;
  }

  .icon-btn .badge {
    top: -8px;
    right: -8px;
  }
}

@media (max-width: 480px) {
  .nav-link {
    font-size: var(--font-size-xs);
    padding: var(--spacing-xs);
  }

  .user-button {
    padding: var(--spacing-xs);
  }

  .user-actions {
    gap: var(--spacing-xs);
  }
}
</style>
