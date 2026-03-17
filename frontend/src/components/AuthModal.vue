<template>
  <Modal :show="show" :title="isLogin ? 'Вход' : 'Регистрация'" @close="$emit('close')">
    <!-- Форма входа -->
    <form v-if="isLogin" @submit.prevent="handleSubmit" class="auth-form">
      <div class="form-group">
        <label>Email</label>
        <input
          type="email"
          placeholder="hello@example.com"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label>Пароль</label>
        <input
          type="password"
          placeholder="••••••••"
          class="form-input"
        >
      </div>

      <div class="form-options">
        <label class="checkbox">
          <input type="checkbox">
          <span>Запомнить меня</span>
        </label>
        <a href="#" class="forgot-link">Забыли пароль?</a>
      </div>

      <button type="submit" class="submit-btn">
        Войти
      </button>
    </form>

    <!-- Форма регистрации -->
    <form v-else @submit.prevent="handleSubmit" class="auth-form">
      <div class="form-group">
        <label>Имя</label>
        <input
          type="text"
          placeholder="Александр"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label>Email</label>
        <input
          type="email"
          placeholder="hello@example.com"
          class="form-input"
        >
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Пароль</label>
          <input
            type="password"
            placeholder="••••••••"
            class="form-input"
          >
        </div>
        <div class="form-group">
          <label>Подтверждение</label>
          <input
            type="password"
            placeholder="••••••••"
            class="form-input"
          >
        </div>
      </div>

      <div class="form-group">
        <label>Телефон (необязательно)</label>
        <input
          type="tel"
          placeholder="+7 (999) 123-45-67"
          class="form-input"
        >
      </div>

      <div class="form-options">
        <label class="checkbox">
          <input type="checkbox">
          <span>Я принимаю <a href="#">условия использования</a></span>
        </label>
      </div>

      <button type="submit" class="submit-btn">
        Создать аккаунт
      </button>
    </form>

    <!-- Переключатель между формами -->
    <div class="auth-switch">
      <p>
        {{ isLogin ? 'Нет аккаунта?' : 'Уже есть аккаунт?' }}
        <button @click="toggleMode" class="switch-btn">
          {{ isLogin ? 'Зарегистрироваться' : 'Войти' }}
        </button>
      </p>
    </div>

    <!-- Разделитель -->
    <div class="auth-divider">
      <span>или войдите через</span>
    </div>

    <!-- Соц сети -->
    <div class="social-auth">
      <button class="social-btn github">
        <span class="social-icon">🐙</span>
        GitHub
      </button>
      <button class="social-btn google">
        <span class="social-icon">📧</span>
        Google
      </button>
      <button class="social-btn telegram">
        <span class="social-icon">✈️</span>
        Telegram
      </button>
    </div>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from './Modal.vue'

defineProps({
  show: Boolean
})

defineEmits(['close'])

const isLogin = ref(true)

const toggleMode = () => {
  isLogin.value = !isLogin.value
}

const handleSubmit = () => {
  // TODO: добавить логику
  console.log('submit', isLogin.value ? 'login' : 'register')
}
</script>

<style scoped>
.auth-form {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-xs);
}

.form-group label {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  font-weight: 500;
}

.form-input {
  padding: var(--spacing-md);
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
}

.form-input:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
}

.form-input::placeholder {
  color: var(--text-muted);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: var(--font-size-sm);
}

.checkbox {
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
  color: var(--text-secondary);
  cursor: pointer;
}

.checkbox input[type="checkbox"] {
  width: 16px;
  height: 16px;
  accent-color: var(--color-primary);
}

.forgot-link {
  color: var(--color-primary);
  text-decoration: none;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
}

.forgot-link:hover {
  text-decoration: underline;
  filter: brightness(1.2);
}

.submit-btn {
  padding: var(--spacing-md);
  background: var(--gradient-primary);
  border: none;
  border-radius: var(--border-radius-md);
  color: var(--text-dark);
  font-weight: 600;
  font-size: var(--font-size-md);
  cursor: pointer;
  transition: var(--transition-base);
  position: relative;
  overflow: hidden;
}

.submit-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.submit-btn:hover::before {
  left: 100%;
}

.submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.auth-switch {
  text-align: center;
  margin-top: var(--spacing-lg);
  padding-top: var(--spacing-lg);
  border-top: 1px solid var(--border-color);
}

.auth-switch p {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.switch-btn {
  background: none;
  border: none;
  color: var(--color-primary);
  font-weight: 600;
  cursor: pointer;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
}

.switch-btn:hover {
  text-decoration: underline;
  filter: brightness(1.2);
}

.auth-divider {
  position: relative;
  text-align: center;
  margin: var(--spacing-xl) 0;
}

.auth-divider::before,
.auth-divider::after {
  content: '';
  position: absolute;
  top: 50%;
  width: calc(50% - 70px);
  height: 1px;
  background-color: var(--border-color);
}

.auth-divider::before {
  left: 0;
}

.auth-divider::after {
  right: 0;
}

.auth-divider span {
  background-color: var(--bg-tertiary);
  padding: 0 var(--spacing-md);
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.social-auth {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--spacing-sm);
}

.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-xs);
  padding: var(--spacing-sm);
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  cursor: pointer;
  transition: var(--transition-base);
}

.social-btn:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  transform: translateY(-2px);
}

.social-icon {
  font-size: 18px;
}

.social-btn.github:hover {
  border-color: #fff;
  color: #fff;
}

.social-btn.google:hover {
  border-color: #4285f4;
  color: #4285f4;
}

.social-btn.telegram:hover {
  border-color: #0088cc;
  color: #0088cc;
}

@media (max-width: 480px) {
  .form-row {
    grid-template-columns: 1fr;
  }

  .social-auth {
    grid-template-columns: 1fr;
  }
}
</style>
