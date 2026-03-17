<template>
  <div class="app">
    <Header
      @open-login="openLoginModal"
      @open-register="openRegisterModal"
    />

    <main class="main-content">
      <div class="container">
        <router-view />
      </div>
    </main>

    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <p>&copy; 2026 HHPet. Сделано с 💚</p>
          <div class="footer-links">
            <router-link to="/about">О проекте</router-link>
            <a href="#">Пользовательское соглашение</a>
            <a href="#">Конфиденциальность</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Модальные окна -->
    <AuthModal
      :show="showAuthModal"
      @close="closeAuthModal"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Header from './components/Header.vue'
import AuthModal from './components/AuthModal.vue'

const showAuthModal = ref(false)

const openLoginModal = () => {
  showAuthModal.value = true
}

const openRegisterModal = () => {
  showAuthModal.value = true
}

const closeAuthModal = () => {
  showAuthModal.value = false
}
</script>

<style>
/* Глобальные стили */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: var(--font-family);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: var(--bg-primary);
  color: var(--text-primary);
  line-height: 1.5;
}

.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding: var(--spacing-2xl) 0;
  background-color: var(--bg-secondary);
}

.container {
  max-width: var(--container-width);
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  width: 100%;
}

.footer {
  background-color: var(--bg-tertiary);
  color: var(--text-secondary);
  padding: var(--spacing-xl) 0;
  margin-top: auto;
  border-top: 1px solid var(--border-color);
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: var(--spacing-md);
}

.footer-links {
  display: flex;
  gap: var(--spacing-lg);
  flex-wrap: wrap;
}

.footer-links a,
.footer-links router-link {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
}

.footer-links a:hover,
.footer-links router-link:hover {
  color: var(--color-primary);
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    text-align: center;
  }

  .footer-links {
    justify-content: center;
  }
}
</style>
