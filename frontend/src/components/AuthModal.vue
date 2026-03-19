<template>
  <Modal :show="show" :title="modalTitle" @close="handleClose" :width="'450px'">
    <!-- ШАГ 1: Выбор роли (только для регистрации) -->
    <div v-if="!isLogin && step === 1" class="role-selection">
      <h3 class="step-title">Кто вы?</h3>
      <div class="role-cards">
        <div
          class="role-card"
          :class="{ active: selectedRole === 'employee' }"
          @click="selectedRole = 'employee'"
        >
          <div class="role-icon">👨‍💻</div>
          <h4>Ищу работу</h4>
          <p>Хочу найти работу мечты</p>
        </div>
        <div
          class="role-card"
          :class="{ active: selectedRole === 'employer' }"
          @click="selectedRole = 'employer'"
        >
          <div class="role-icon">🏢</div>
          <h4>Работодатель</h4>
          <p>Ищу сотрудников в компанию</p>
        </div>
      </div>
      <button
        class="submit-btn"
        :disabled="!selectedRole"
        @click="step = 2"
      >
        Продолжить
      </button>
    </div>

    <!-- ШАГ 2: Ввод личных данных (для регистрации) -->
    <div v-else-if="!isLogin && step === 2" class="auth-form">
      <h3 class="step-title">Личные данные</h3>

      <div class="form-group">
        <label>Фамилия</label>
        <input
          v-model="formData.last_name"
          type="text"
          placeholder="Иванов"
          class="form-input"
          :class="{ error: errors.last_name }"
        >
        <span v-if="errors.last_name" class="error-text">{{ errors.last_name }}</span>
      </div>

      <div class="form-group">
        <label>Имя</label>
        <input
          v-model="formData.first_name"
          type="text"
          placeholder="Иван"
          class="form-input"
          :class="{ error: errors.first_name }"
        >
        <span v-if="errors.first_name" class="error-text">{{ errors.first_name }}</span>
      </div>

      <div class="form-group">
        <label>Отчество (необязательно)</label>
        <input
          v-model="formData.middle_name"
          type="text"
          placeholder="Иванович"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label>Email</label>
        <input
          v-model="formData.email"
          type="email"
          placeholder="hello@example.com"
          class="form-input"
          :class="{ error: errors.email }"
        >
        <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
      </div>

      <div class="form-actions">
        <button class="btn-outline" @click="step = 1">Назад</button>
        <button
          class="submit-btn"
          @click="sendVerificationCode"
          :disabled="loading"
        >
          <span v-if="loading">Отправка...</span>
          <span v-else>Отправить код</span>
        </button>
      </div>
    </div>

    <!-- ШАГ 3: Подтверждение email (для регистрации) -->
    <div v-else-if="!isLogin && step === 3" class="auth-form">
      <h3 class="step-title">Подтверждение email</h3>
      <p class="step-description">
        Мы отправили код на {{ formData.email }}
      </p>

      <div class="verification-inputs">
        <input
          v-for="(digit, index) in 6"
          :key="index"
          ref="codeInputs"
          v-model="verificationCode[index]"
          type="text"
          maxlength="1"
          class="verification-digit"
          @input="handleCodeInput(index, $event)"
          @keydown.delete="handleCodeDelete(index, $event)"
          @paste="handleCodePaste"
        >
      </div>
      <span v-if="errors.code" class="error-text">{{ errors.code }}</span>

      <div class="timer">
        <span v-if="timer > 0">Получить новый код через {{ timer }} сек</span>
        <button v-else class="resend-btn" @click="resendCode">Отправить снова</button>
      </div>

      <div class="form-actions">
        <button class="btn-outline" @click="step = 2">Назад</button>
        <button
          class="submit-btn"
          @click="verifyCode"
          :disabled="loading || !isCodeComplete"
        >
          <span v-if="loading">Проверка...</span>
          <span v-else>Подтвердить</span>
        </button>
      </div>
    </div>

    <!-- ШАГ 4: Установка пароля (для регистрации) -->
    <div v-else-if="!isLogin && step === 4" class="auth-form">
      <h3 class="step-title">Придумайте пароль</h3>

      <div class="form-group">
        <label>Пароль</label>
        <input
          v-model="formData.password"
          type="password"
          placeholder="••••••••"
          class="form-input"
          :class="{ error: errors.password }"
        >
        <span v-if="errors.password" class="error-text">{{ errors.password }}</span>
      </div>

      <div class="form-group">
        <label>Подтверждение пароля</label>
        <input
          v-model="formData.password_confirmation"
          type="password"
          placeholder="••••••••"
          class="form-input"
          :class="{ error: errors.password_confirmation }"
        >
        <span v-if="errors.password_confirmation" class="error-text">{{ errors.password_confirmation }}</span>
      </div>

      <div class="password-strength" v-if="formData.password">
        <div class="strength-bar">
          <div :class="['strength-fill', strengthClass]" :style="{ width: strengthPercentage + '%' }"></div>
        </div>
        <span class="strength-text">{{ strengthText }}</span>
      </div>

      <div class="form-actions">
        <button class="btn-outline" @click="step = 3">Назад</button>
        <button
          class="submit-btn"
          @click="createUser"
          :disabled="loading || !isPasswordValid"
        >
          <span v-if="loading">Создание...</span>
          <span v-else>Зарегистрироваться</span>
        </button>
      </div>
    </div>

    <!-- ФОРМА ВХОДА -->
    <form v-if="isLogin" @submit.prevent="handleLogin" class="auth-form">
      <h3 class="step-title">Вход в аккаунт</h3>

      <div class="form-group">
        <label>Email</label>
        <input
          v-model="loginForm.email"
          type="email"
          placeholder="hello@example.com"
          class="form-input"
          :class="{ error: errors.email }"
        >
        <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
      </div>

      <div class="form-group">
        <label>Пароль</label>
        <input
          v-model="loginForm.password"
          type="password"
          placeholder="••••••••"
          class="form-input"
          :class="{ error: errors.password }"
        >
        <span v-if="errors.password" class="error-text">{{ errors.password }}</span>
      </div>

      <div class="form-options">
        <label class="checkbox">
          <input type="checkbox" v-model="loginForm.remember">
          <span>Запомнить меня</span>
        </label>
        <a href="#" class="forgot-link">Забыли пароль?</a>
      </div>

      <button type="submit" class="submit-btn" :disabled="loginLoading">
        <span v-if="loginLoading">Вход...</span>
        <span v-else>Войти</span>
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

    <!-- Сообщение об ошибке -->
    <Transition name="fade">
      <div v-if="errorMessage" class="error-message">
        {{ errorMessage }}
      </div>
    </Transition>
  </Modal>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue'
import Modal from './Modal.vue'
import api from '../services/api'

const props = defineProps({
  show: Boolean,
  initialMode: {
    type: String,
    default: 'login'
  }
})

const emit = defineEmits(['close', 'login-success', 'register-success'])

// Общие состояния
const isLogin = ref(props.initialMode === 'login')
const loading = ref(false)
const loginLoading = ref(false)
const errorMessage = ref('')
const errors = ref({})

// Данные для регистрации
const step = ref(1)
const selectedRole = ref(null)
const formData = ref({
  first_name: '',
  last_name: '',
  middle_name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

// Данные для входа
const loginForm = ref({
  email: '',
  password: '',
  remember: false
})

// Код подтверждения
const verificationCode = ref(['', '', '', '', '', ''])
const codeInputs = ref([])
const timer = ref(0)
let timerInterval = null

// Вычисляемые свойства
const modalTitle = computed(() => {
  if (isLogin.value) return 'Вход'
  switch (step.value) {
    case 1: return 'Регистрация'
    case 2: return 'Личные данные'
    case 3: return 'Подтверждение email'
    case 4: return 'Создание пароля'
    default: return 'Регистрация'
  }
})

const isCodeComplete = computed(() => {
  return verificationCode.value.every(digit => digit && digit.trim() !== '')
})

const isPasswordValid = computed(() => {
  return formData.value.password &&
    formData.value.password.length >= 8 &&
    formData.value.password === formData.value.password_confirmation
})

// Оценка сложности пароля
const strengthPercentage = computed(() => {
  const pass = formData.value.password
  if (!pass) return 0

  let strength = 0
  if (pass.length >= 8) strength += 25
  if (pass.match(/[a-z]/)) strength += 25
  if (pass.match(/[A-Z]/)) strength += 25
  if (pass.match(/[0-9]/)) strength += 25
  if (pass.match(/[^a-zA-Z0-9]/)) strength += 25

  return Math.min(100, strength)
})

const strengthClass = computed(() => {
  const percent = strengthPercentage.value
  if (percent < 25) return 'weak'
  if (percent < 50) return 'fair'
  if (percent < 75) return 'good'
  return 'strong'
})

const strengthText = computed(() => {
  const percent = strengthPercentage.value
  if (percent < 25) return 'Слишком простой'
  if (percent < 50) return 'Слабый'
  if (percent < 75) return 'Средний'
  return 'Надёжный'
})

// Методы - ОБЪЯВЛЯЕМ ПЕРЕД ИХ ИСПОЛЬЗОВАНИЕМ
const clearTimer = () => {
  clearInterval(timerInterval)
  timer.value = 0
}

const resetForm = () => {
  step.value = 1
  selectedRole.value = null
  formData.value = {
    first_name: '', last_name: '', middle_name: '', email: '', password: '', password_confirmation: ''
  }
  loginForm.value = {
    email: '', password: '', remember: false
  }
  verificationCode.value = ['', '', '', '', '', '']
  errors.value = {}
  errorMessage.value = ''
  clearTimer()
}

const toggleMode = () => {
  isLogin.value = !isLogin.value
  resetForm()
}

const handleClose = () => {
  resetForm()
  emit('close')
}

// Отправка кода подтверждения
const sendVerificationCode = async () => {
  errors.value = {}
  loading.value = true
  errorMessage.value = ''

  // Базовая валидация на фронте
  if (!formData.value.first_name || !formData.value.last_name || !formData.value.email) {
    errorMessage.value = 'Заполните все обязательные поля'
    loading.value = false
    return
  }

  try {
    const requestData = {
      first_name: formData.value.first_name.trim(),
      last_name: formData.value.last_name.trim(),
      middle_name: formData.value.middle_name.trim() || null,
      email: formData.value.email.trim().toLowerCase(),
      role: selectedRole.value === 'employee' ? 'applicant' : 'employer'
    }

    console.log('Sending data:', requestData)

    const response = await api.post('/auth/send-verification', requestData)

    console.log('Response:', response)
    step.value = 3
    startTimer(30)

  } catch (error) {
    console.error('Full error:', error)
    console.error('Error response:', error.response?.data)

    if (error.response?.data?.errors) {
      // Ошибки валидации от Laravel
      errors.value = error.response.data.errors

      // Покажем первую ошибку
      const firstErrorField = Object.keys(error.response.data.errors)[0]
      if (firstErrorField) {
        errorMessage.value = error.response.data.errors[firstErrorField][0]
      }
    } else if (error.response?.data?.message) {
      // Общее сообщение об ошибке
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Ошибка при отправке кода'
    }
  } finally {
    loading.value = false
  }
}

// Проверка кода
const verifyCode = async () => {
  errors.value = {}
  loading.value = true

  try {
    const code = verificationCode.value.join('')

    await api.post('/auth/check-verification-code', {
      email: formData.value.email,
      code: code
    })

    step.value = 4

  } catch (error) {
    if (error.errors) {
      errors.value = error.errors
    } else {
      errorMessage.value = error.message || 'Неверный код подтверждения'
    }
  } finally {
    loading.value = false
  }
}

// Создание пользователя
const createUser = async () => {
  errors.value = {}
  loading.value = true

  try {
    const code = verificationCode.value.join('')

    const response = await api.post('/auth/create-user', {
      email: formData.value.email,
      password: formData.value.password,
      password_confirmation: formData.value.password_confirmation,
      code: code
    })

    // Автоматический вход после регистрации
    await handleLogin(true)

    emit('register-success', response.data)
    handleClose()

  } catch (error) {
    if (error.errors) {
      errors.value = error.errors
    } else {
      errorMessage.value = error.message || 'Ошибка при создании пользователя'
    }
  } finally {
    loading.value = false
  }
}

// Вход в систему
const handleLogin = async (skipRedirect = false) => {
  errors.value = {}
  loginLoading.value = true

  try {
    const response = await api.post('/auth/login', {
      email: loginForm.value.email,
      password: loginForm.value.password
    })

    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }

    emit('login-success', response.data)
    if (!skipRedirect) handleClose()

  } catch (error) {
    if (error.errors) {
      errors.value = error.errors
    } else {
      errorMessage.value = error.message || 'Неверный email или пароль'
    }
  } finally {
    loginLoading.value = false
  }
}

// Повторная отправка кода
const resendCode = async () => {
  await sendVerificationCode()
}

// Таймер для повторной отправки
const startTimer = (seconds) => {
  timer.value = seconds
  clearInterval(timerInterval)
  timerInterval = setInterval(() => {
    if (timer.value > 0) {
      timer.value--
    } else {
      clearInterval(timerInterval)
    }
  }, 1000)
}

// Обработка ввода кода
const handleCodeInput = (index, event) => {
  if (event.target.value && index < 5) {
    codeInputs.value[index + 1].focus()
  }
}

const handleCodeDelete = (index, event) => {
  if (event.key === 'Backspace' && !verificationCode.value[index] && index > 0) {
    codeInputs.value[index - 1].focus()
  }
}

const handleCodePaste = (event) => {
  event.preventDefault()
  const pastedData = event.clipboardData.getData('text/plain').slice(0, 6).split('')
  verificationCode.value = [...pastedData, ...Array(6 - pastedData.length).fill('')]

  const nextEmptyIndex = verificationCode.value.findIndex(d => !d)
  if (nextEmptyIndex !== -1 && nextEmptyIndex < 6) {
    codeInputs.value[nextEmptyIndex].focus()
  }
}

// WATCH - ТЕПЕРЬ ПОСЛЕ ОБЪЯВЛЕНИЯ ВСЕХ ФУНКЦИЙ
watch(() => props.initialMode, (newMode) => {
  isLogin.value = newMode === 'login'
  resetForm()
}, { immediate: true })

// Сброс при закрытии
watch(() => props.show, (newVal) => {
  if (!newVal) {
    resetForm()
    clearTimer()
  }
})

// Фокус на первый input кода при переходе на шаг 3
watch(step, (newStep) => {
  if (newStep === 3) {
    nextTick(() => {
      if (codeInputs.value[0]) {
        codeInputs.value[0].focus()
      }
    })
  }
})
</script>

<style scoped>
.step-title {
  text-align: center;
  margin-bottom: var(--spacing-lg);
  color: var(--text-primary);
  font-size: var(--font-size-lg);
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.step-description {
  text-align: center;
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  margin-bottom: var(--spacing-xl);
}

/* Карточки выбора роли */
.role-selection {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.role-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.role-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-lg);
  padding: var(--spacing-lg);
  text-align: center;
  cursor: pointer;
  transition: var(--transition-base);
}

.role-card:hover {
  border-color: var(--color-primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary);
}

.role-card.active {
  border-color: var(--color-primary);
  background: linear-gradient(145deg, var(--color-black-300) 0%, var(--color-dark-200) 100%);
  box-shadow: var(--shadow-primary);
}

.role-icon {
  font-size: 40px;
  margin-bottom: var(--spacing-sm);
  filter: drop-shadow(var(--shadow-primary));
}

.role-card h4 {
  color: var(--text-primary);
  margin-bottom: var(--spacing-xs);
  font-size: var(--font-size-md);
}

.role-card p {
  color: var(--text-secondary);
  font-size: var(--font-size-xs);
}

/* Формы */
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

.form-input.error {
  border-color: var(--color-danger);
}

.error-text {
  color: var(--color-danger);
  font-size: var(--font-size-xs);
  margin-top: 2px;
}

/* Поля ввода кода */
.verification-inputs {
  display: flex;
  gap: var(--spacing-xs);
  justify-content: center;
  margin-bottom: var(--spacing-lg);
}

.verification-digit {
  width: 50px;
  height: 50px;
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  font-size: var(--font-size-xl);
  text-align: center;
  transition: var(--transition-base);
}

.verification-digit:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
}

/* Таймер */
.timer {
  text-align: center;
  margin-bottom: var(--spacing-lg);
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
}

.resend-btn {
  background: none;
  border: none;
  color: var(--color-primary);
  cursor: pointer;
  font-size: var(--font-size-sm);
  text-decoration: underline;
  transition: var(--transition-base);
}

.resend-btn:hover {
  filter: brightness(1.2);
  text-shadow: 0 0 8px var(--color-primary);
}

/* Кнопки */
.form-actions {
  display: flex;
  gap: var(--spacing-md);
  margin-top: var(--spacing-lg);
}

.form-actions .btn-outline {
  flex: 1;
  padding: var(--spacing-md);
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-secondary);
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition-base);
}

.form-actions .btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  background-color: rgba(0, 255, 136, 0.05);
}

.form-actions .submit-btn {
  flex: 2;
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

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

/* Опции формы */
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

/* Индикатор сложности пароля */
.password-strength {
  margin-top: var(--spacing-xs);
  margin-bottom: var(--spacing-md);
}

.strength-bar {
  height: 4px;
  background-color: var(--bg-secondary);
  border-radius: var(--border-radius-full);
  overflow: hidden;
  margin-bottom: var(--spacing-xs);
}

.strength-fill {
  height: 100%;
  transition: width 0.3s ease;
}

.strength-fill.weak { background-color: var(--color-danger); }
.strength-fill.fair { background-color: var(--color-warning); }
.strength-fill.good { background-color: var(--color-primary-soft); }
.strength-fill.strong { background-color: var(--color-primary); }

.strength-text {
  font-size: var(--font-size-xs);
  color: var(--text-secondary);
}

/* Переключатель между формами */
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
  margin-left: var(--spacing-xs);
}

.switch-btn:hover {
  text-decoration: underline;
  filter: brightness(1.2);
}

/* Сообщение об ошибке */
.error-message {
  margin-top: var(--spacing-lg);
  padding: var(--spacing-sm) var(--spacing-md);
  background-color: rgba(244, 67, 54, 0.1);
  border: 1px solid var(--color-danger);
  border-radius: var(--border-radius-md);
  color: var(--color-danger);
  font-size: var(--font-size-sm);
  text-align: center;
}

/* Анимации */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@media (max-width: 480px) {
  .role-cards {
    grid-template-columns: 1fr;
  }

  .verification-digit {
    width: 40px;
    height: 40px;
    font-size: var(--font-size-lg);
  }

  .form-actions {
    flex-direction: column;
  }

  .form-options {
    flex-direction: column;
    gap: var(--spacing-sm);
    align-items: flex-start;
  }
}
</style>
