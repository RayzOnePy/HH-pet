<template>
  <div class="app">
    <Header
      :user="user"
      @open-login="openLoginModal"
      @open-register="openRegisterModal"
      @logout="handleLogout"
    />

    <main class="main-content">
      <div class="container">
        <router-view v-slot="{ Component }">
          <Transition name="page" mode="out-in">
            <component :is="Component" />
          </Transition>
        </router-view>
      </div>
    </main>

    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <p>&copy; 2026 HHPet</p>
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
      :initial-mode="authModalMode"
      @close="closeAuthModal"
      @login-success="handleLoginSuccess"
      @register-success="handleRegisterSuccess"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from './stores/auth'
import Header from './components/Header.vue'
import AuthModal from './components/AuthModal.vue'

const authStore = useAuthStore()
const showAuthModal = ref(false)
const authModalMode = ref('login')

const user = computed(() => authStore.user)

onMounted(() => {
  authStore.init()
})

const openLoginModal = () => {
  authModalMode.value = 'login'
  showAuthModal.value = true
}

const openRegisterModal = () => {
  authModalMode.value = 'register'
  showAuthModal.value = true
}

const closeAuthModal = () => {
  showAuthModal.value = false
}

const handleLoginSuccess = (userData) => {
  closeAuthModal()
}

const handleRegisterSuccess = (userData) => {
  closeAuthModal()
}

const handleLogout = async () => {
  await authStore.logout()
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: var(--font-family);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: var(--color-black-100);
  color: var(--text-primary);
  line-height: 1.5;
  position: relative;
}

body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--bg-gradient-primary);
  pointer-events: none;
  z-index: -2;
}

body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--gradient-glow);
  pointer-events: none;
  z-index: -1;
}

.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  position: relative;
  backdrop-filter: var(--blur-sm);
}

.main-content {
  flex: 1;
  padding: var(--spacing-2xl) 0;
  position: relative;
}

.main-content::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 150px;
  background: var(--gradient-glow-bottom);
  pointer-events: none;
  z-index: 0;
}

.container {
  max-width: var(--container-width);
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  width: 100%;
  position: relative;
  z-index: 1;
}

.footer {
  background: var(--bg-card-gradient);
  color: var(--text-secondary);
  padding: var(--spacing-xl) 0;
  margin-top: auto;
  border-top: 1px solid rgba(0, 255, 136, 0.1);
  position: relative;
  backdrop-filter: var(--blur-sm);
}

.footer::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--color-primary), transparent);
  opacity: 0.3;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: var(--spacing-md);
  position: relative;
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
  position: relative;
}

.footer-links a::after,
.footer-links router-link::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: var(--gradient-primary);
  transition: var(--transition-base);
}

.footer-links a:hover::after,
.footer-links router-link:hover::after {
  width: 100%;
}

.footer-links a:hover,
.footer-links router-link:hover {
  color: var(--color-primary);
}

.page-enter-active,
.page-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    text-align: center;
  }

  .footer-links {
    justify-content: center;
  }

  .main-content {
    padding: var(--spacing-lg) 0;
  }
}
</style>
