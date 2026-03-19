<template>
  <Modal :show="show" :title="modalTitle" @close="handleClose" :width="'450px'">
    <!-- ШАГ 1: Выбор роли (только для регистрации) -->
    <div v-if="!isLogin && step === 1" class="role-selection">
      <h3 class="step-title">Кто вы?</h3>
      <div class="role-cards">
        <div
          class="role-card"
          :class="{ active: selectedRole === 'employee', error: errors.role }"
          @click="selectedRole = 'employee'; errors.role = null"
        >
          <div class="role-icon">👨‍💻</div>
          <h4>Ищу работу</h4>
          <p>Хочу найти работу мечты</p>
        </div>
        <div
          class="role-card"
          :class="{ active: selectedRole === 'employer', error: errors.role }"
          @click="selectedRole = 'employer'; errors.role = null"
        >
          <div class="role-icon">🏢</div>
          <h4>Работодатель</h4>
          <p>Ищу сотрудников в компанию</p>
        </div>
      </div>
      <div v-if="errors.role" class="error-message-field">
        {{ errors.role }}
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
      <div class="form-group">
        <label>Фамилия <span class="required">*</span></label>
        <input
          v-model="formData.last_name"
          type="text"
          placeholder="Иванов"
          class="form-input"
          :class="{ error: errors.last_name }"
          @input="errors.last_name = null"
        >
        <Transition name="fade-slide">
          <div v-if="errors.last_name" class="error-message-field">
            {{ errors.last_name }}
          </div>
        </Transition>
      </div>

      <div class="form-group">
        <label>Имя <span class="required">*</span></label>
        <input
          v-model="formData.first_name"
          type="text"
          placeholder="Иван"
          class="form-input"
          :class="{ error: errors.first_name }"
          @input="errors.first_name = null"
        >
        <Transition name="fade-slide">
          <div v-if="errors.first_name" class="error-message-field">
            {{ errors.first_name }}
          </div>
        </Transition>
      </div>

      <div class="form-group">
        <label>Отчество</label>
        <input
          v-model="formData.middle_name"
          type="text"
          placeholder="Иванович"
          class="form-input"
          :class="{ error: errors.middle_name }"
          @input="errors.middle_name = null"
        >
        <Transition name="fade-slide">
          <div v-if="errors.middle_name" class="error-message-field">
            {{ errors.middle_name }}
          </div>
        </Transition>
      </div>

      <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input
          v-model="formData.email"
          type="email"
          placeholder="hello@example.com"
          class="form-input"
          :class="{ error: errors.email }"
          @input="errors.email = null"
        >
        <Transition name="fade-slide">
          <div v-if="errors.email" class="error-message-field">
            {{ errors.email }}
          </div>
        </Transition>
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
      <p class="step-description">
        Мы отправили код на <strong>{{ formData.email }}</strong>
      </p>

      <div class="form-group">
        <label>Введите код из письма</label>
        <div class="verification-inputs">
          <input
            v-for="(digit, index) in 6"
            :key="index"
            ref="codeInputs"
            v-model="verificationCode[index]"
            type="text"
            maxlength="1"
            class="verification-digit"
            :class="{ error: errors.code }"
            @input="handleCodeInput(index, $event); errors.code = null"
            @keydown.delete="handleCodeDelete(index, $event)"
            @paste="handleCodePaste"
          >
        </div>
        <Transition name="fade-slide">
          <div v-if="errors.code" class="error-message-field">
            {{ errors.code }}
          </div>
        </Transition>
      </div>

      <div class="timer">
        <template v-if="timer > 0">
          <span class="timer-icon">⏳</span>
          <span>Повторная отправка через {{ timer }} сек</span>
        </template>
        <button
          v-else
          class="resend-btn"
          @click="resendCode"
          :disabled="loading"
        >
          <span class="resend-icon">↻</span>
          Отправить снова
        </button>
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
        <label>Пароль <span class="required">*</span></label>
        <div class="password-wrapper">
          <input
            v-model="formData.password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            class="form-input password-input"
            :class="{ error: errors.password }"
            @input="errors.password = null; errors.password_confirmation = null"
          >
          <button
            type="button"
            class="password-toggle"
            @click="showPassword = !showPassword"
            tabindex="-1"
          >
            <span v-if="showPassword">👁️</span>
            <span v-else>👁️‍🗨️</span>
          </button>
        </div>
        <Transition name="fade-slide">
          <div v-if="errors.password" class="error-message-field">
            {{ errors.password }}
          </div>
        </Transition>
      </div>

      <div class="password-strength" v-if="formData.password">
        <div class="strength-bar">
          <div :class="['strength-fill', strengthClass]" :style="{ width: strengthPercentage + '%' }"></div>
        </div>
        <span class="strength-text">{{ strengthText }}</span>
      </div>

      <div class="form-group">
        <label>Подтверждение пароля <span class="required">*</span></label>
        <div class="password-wrapper">
          <input
            v-model="formData.password_confirmation"
            :type="showPasswordConfirm ? 'text' : 'password'"
            placeholder="••••••••"
            class="form-input password-input"
            :class="{ error: errors.password_confirmation }"
            @input="errors.password_confirmation = null"
          >
          <button
            type="button"
            class="password-toggle"
            @click="showPasswordConfirm = !showPasswordConfirm"
            tabindex="-1"
          >
            <span v-if="showPasswordConfirm">👁️</span>
            <span v-else>👁️‍🗨️</span>
          </button>
        </div>
        <Transition name="fade-slide">
          <div v-if="errors.password_confirmation" class="error-message-field">
            {{ errors.password_confirmation }}
          </div>
        </Transition>
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
      <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input
          v-model="loginForm.email"
          type="email"
          placeholder="hello@example.com"
          class="form-input"
          :class="{ error: errors.email }"
          @input="errors.email = null"
        >
        <Transition name="fade-slide">
          <div v-if="errors.email" class="error-message-field">
            {{ errors.email }}
          </div>
        </Transition>
      </div>

      <div class="form-group">
        <label>Пароль <span class="required">*</span></label>
        <div class="password-wrapper">
          <input
            v-model="loginForm.password"
            :type="showLoginPassword ? 'text' : 'password'"
            placeholder="••••••••"
            class="form-input password-input"
            :class="{ error: errors.password }"
            @input="errors.password = null"
          >
          <button
            type="button"
            class="password-toggle"
            @click="showLoginPassword = !showLoginPassword"
            tabindex="-1"
          >
            <span v-if="showLoginPassword">👁️</span>
            <span v-else>👁️‍🗨️</span>
          </button>
        </div>
        <Transition name="fade-slide">
          <div v-if="errors.password" class="error-message-field">
            {{ errors.password }}
          </div>
        </Transition>
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

    <!-- Общая ошибка (например, сервер не отвечает) -->
    <Transition name="fade-slide">
      <div v-if="generalError" class="error-message-general">
        {{ generalError }}
      </div>
    </Transition>
  </Modal>
</template>

<script setup>
import {ref, computed, watch, nextTick} from 'vue'
import {useAuthStore} from '../stores/auth'
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

// Подключаем store
const authStore = useAuthStore()

// Общие состояния
const isLogin = ref(props.initialMode === 'login')
const loading = ref(false)
const loginLoading = ref(false)
const generalError = ref('')
const errors = ref({})

// Состояния для показа паролей
const showPassword = ref(false)
const showPasswordConfirm = ref(false)
const showLoginPassword = ref(false)

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
    case 1:
      return 'Регистрация'
    case 2:
      return 'Личные данные'
    case 3:
      return 'Подтверждение email'
    case 4:
      return 'Создание пароля'
    default:
      return 'Регистрация'
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

// Методы
const clearTimer = () => {
  clearInterval(timerInterval)
  timer.value = 0
}

const resetForm = () => {
  step.value = 1
  selectedRole.value = null
  formData.value = {
    first_name: '',
    last_name: '',
    middle_name: '',
    email: '',
    password: '',
    password_confirmation: ''
  }
  loginForm.value = {
    email: '', password: '', remember: false
  }
  verificationCode.value = ['', '', '', '', '', '']
  errors.value = {}
  generalError.value = ''
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
  generalError.value = ''
  loading.value = true

  // Базовая валидация на фронте
  if (!formData.value.first_name || !formData.value.last_name || !formData.value.email) {
    generalError.value = 'Заполните все обязательные поля'
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

    const response = await api.post('/auth/send-verification', requestData)

    // Успешная отправка - переходим на шаг 3 и запускаем таймер на 60 секунд
    step.value = 3
    startTimer(60)

  } catch (error) {
    if (error.errors) {
      // Ошибки валидации от Laravel
      const formattedErrors = {}
      Object.keys(error.errors).forEach(field => {
        formattedErrors[field] = error.errors[field][0]
      })
      errors.value = formattedErrors

    } else if (error.data?.seconds) {
      // Бэкенд вернул 429 с количеством секунд ожидания
      generalError.value = error.message || `Повторная отправка через ${error.data.seconds} секунд`
      startTimer(error.data.seconds)

    } else if (error.message) {
      // Другие ошибки с сообщением
      generalError.value = error.message
    } else {
      generalError.value = 'Ошибка при отправке кода'
    }
  } finally {
    loading.value = false
  }
}

// Проверка кода
const verifyCode = async () => {
  errors.value = {}
  generalError.value = ''
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
      const formattedErrors = {}
      Object.keys(error.errors).forEach(field => {
        formattedErrors[field] = error.errors[field][0]
      })
      errors.value = formattedErrors
    } else if (error.message) {
      generalError.value = error.message
    } else {
      generalError.value = 'Неверный код подтверждения'
    }
  } finally {
    loading.value = false
  }
}

// Создание пользователя
const createUser = async () => {
  errors.value = {}
  generalError.value = ''
  loading.value = true

  try {
    const code = verificationCode.value.join('')

    const result = await authStore.register({
      email: formData.value.email,
      password: formData.value.password,
      password_confirmation: formData.value.password_confirmation,
      code: code
    })

    if (result.success) {
      emit('register-success', result.data)
      handleClose()
    } else {
      generalError.value = result.message
    }
  } catch (error) {
    generalError.value = 'Ошибка при создании пользователя'
  } finally {
    loading.value = false
  }
}

// Вход в систему
const handleLogin = async (skipRedirect = false) => {
  errors.value = {}
  generalError.value = ''
  loginLoading.value = true

  try {
    const result = await authStore.login({
      email: loginForm.value.email,
      password: loginForm.value.password,
      remember: loginForm.value.remember
    })

    if (result.success) {
      emit('login-success', result.data)
      if (!skipRedirect) handleClose()
    } else {
      generalError.value = result.message
    }
  } catch (error) {
    generalError.value = 'Ошибка при входе'
  } finally {
    loginLoading.value = false
  }
}

// Повторная отправка кода
const resendCode = async () => {
  if (timer.value > 0) {
    generalError.value = `Подождите ${timer.value} секунд перед повторной отправкой`
    return
  }
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

// WATCH
watch(() => props.initialMode, (newMode) => {
  isLogin.value = newMode === 'login'
  resetForm()
}, {immediate: true})

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

.step-description strong {
  color: var(--color-primary);
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
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-lg);
  padding: var(--spacing-lg);
  text-align: center;
  cursor: pointer;
  transition: all var(--transition-base);
  position: relative;
  overflow: hidden;
}

.role-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
}

.role-card:hover::before {
  transform: translateX(0);
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

.role-card.active::before {
  transform: translateX(0);
}

.role-card.error {
  border-color: var(--color-danger);
  animation: shake 0.3s ease;
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
  position: relative;
}

.form-group label {
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
}

.required {
  color: var(--color-danger);
  font-size: var(--font-size-md);
}

.form-input {
  padding: var(--spacing-md);
  background-color: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  font-size: var(--font-size-sm);
  transition: all var(--transition-base);
}

.form-input:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
  background-color: var(--bg-tertiary);
}

.form-input::placeholder {
  color: var(--text-muted);
}

.form-input.error {
  border-color: var(--color-danger);
  background-color: rgba(244, 67, 54, 0.05);
}

.form-input.error:focus {
  box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
}

/* Password toggle */
.password-wrapper {
  position: relative;
  width: 100%;
}

.password-input {
  width: 100%;
  padding-right: 50px !important;
}

.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  color: var(--text-secondary);
  font-size: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color var(--transition-base);
  z-index: 2;
}

.password-toggle:hover {
  color: var(--color-primary);
}

.password-toggle:focus {
  outline: none;
  color: var(--color-primary);
}

/* Поля ввода кода */
.verification-inputs {
  display: flex;
  gap: var(--spacing-xs);
  justify-content: center;
  margin-top: var(--spacing-xs);
}

.verification-digit {
  width: 50px;
  height: 50px;
  background-color: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  color: var(--text-primary);
  font-size: var(--font-size-xl);
  text-align: center;
  transition: all var(--transition-base);
}

.verification-digit:focus {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
  outline: none;
  transform: scale(1.05);
}

.verification-digit.error {
  border-color: var(--color-danger);
  animation: shake 0.3s ease;
}

/* Таймер */
.timer {
  text-align: center;
  margin-bottom: var(--spacing-lg);
  color: var(--text-secondary);
  font-size: var(--font-size-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-xs);
}

.timer-icon {
  font-size: var(--font-size-md);
  animation: pulse 1s infinite;
}

.resend-btn {
  background: none;
  border: none;
  color: var(--color-primary);
  cursor: pointer;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
  padding: var(--spacing-xs) var(--spacing-md);
  border-radius: var(--border-radius-sm);
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
}

.resend-btn:hover:not(:disabled) {
  filter: brightness(1.2);
  text-shadow: 0 0 8px var(--color-primary);
  background-color: rgba(0, 255, 136, 0.1);
}

.resend-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.resend-icon {
  font-size: var(--font-size-md);
  transition: transform 0.3s ease;
}

.resend-btn:hover:not(:disabled) .resend-icon {
  transform: rotate(180deg);
}

/* Сообщения об ошибках */
.error-message-field {
  color: var(--color-danger);
  font-size: var(--font-size-xs);
  margin-top: 2px;
  padding-left: var(--spacing-xs);
  animation: slideIn 0.2s ease;
}

.error-message-general {
  margin-top: var(--spacing-lg);
  padding: var(--spacing-md);
  background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
  border: 1px solid var(--color-danger);
  border-radius: var(--border-radius-md);
  color: var(--color-danger);
  font-size: var(--font-size-sm);
  text-align: center;
  backdrop-filter: var(--blur-sm);
  animation: slideIn 0.3s ease;
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
  border: 2px solid var(--border-color);
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
  transform: translateY(-1px);
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
  cursor: pointer;
}

.forgot-link {
  color: var(--color-primary);
  text-decoration: none;
  font-size: var(--font-size-sm);
  transition: var(--transition-base);
  padding: var(--spacing-xs) var(--spacing-sm);
  border-radius: var(--border-radius-sm);
}

.forgot-link:hover {
  text-decoration: underline;
  filter: brightness(1.2);
  background-color: rgba(0, 255, 136, 0.1);
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

.strength-fill.weak {
  background-color: var(--color-danger);
}

.strength-fill.fair {
  background-color: var(--color-warning);
}

.strength-fill.good {
  background-color: var(--color-primary-soft);
}

.strength-fill.strong {
  background-color: var(--color-primary);
}

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
  padding: var(--spacing-xs) var(--spacing-sm);
  border-radius: var(--border-radius-sm);
}

.switch-btn:hover {
  text-decoration: underline;
  filter: brightness(1.2);
  background-color: rgba(0, 255, 136, 0.1);
}

/* Анимации */
@keyframes shake {
  0%, 100% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-5px);
  }
  75% {
    transform: translateX(5px);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.2s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Адаптивность */
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
