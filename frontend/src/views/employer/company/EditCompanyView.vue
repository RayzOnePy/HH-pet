<template>
  <div class="edit-company">
    <div class="page-header">
      <h1>Редактирование компании</h1>
      <router-link to="/employer/company" class="back-link">
        ← Назад
      </router-link>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка...</p>
    </div>

    <form v-else class="company-form" @submit.prevent="updateCompany">
      <div class="form-group">
        <label>Название компании <span class="required">*</span></label>
        <input
          v-model="form.name"
          type="text"
          placeholder="ООО 'ТехноКорп'"
          class="form-input"
          :class="{ error: errors.name }"
          required
        >
        <span v-if="errors.name" class="error-text">{{ errors.name }}</span>
      </div>

      <div class="form-group">
        <label>Описание компании <span class="required">*</span></label>
        <textarea
          v-model="form.description"
          rows="6"
          placeholder="Расскажите о своей компании..."
          class="form-textarea"
          :class="{ error: errors.description }"
          required
        ></textarea>
        <span v-if="errors.description" class="error-text">{{ errors.description }}</span>
      </div>

      <div class="form-group">
        <label>Логотип компании</label>
        <div class="logo-upload">
          <div class="logo-preview">
            <img v-if="previewUrl" :src="previewUrl" alt="Логотип">
            <span v-else>🏢</span>
          </div>
          <div class="logo-actions">
            <input
              type="file"
              ref="fileInput"
              accept="image/*"
              @change="onFileChange"
              style="display: none"
            >
            <button type="button" class="btn-outline" @click="triggerFileInput">
              Заменить логотип
            </button>
            <span v-if="form.logo_file" class="file-name">
              {{ form.logo_file.name }}
            </span>
          </div>
        </div>
        <span v-if="errors.logo" class="error-text">{{ errors.logo }}</span>
      </div>

      <div class="form-actions">
        <router-link to="/employer/company" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary" :disabled="saving">
          {{ saving ? 'Сохранение...' : 'Сохранить изменения' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../../services/api'

const router = useRouter()
const loading = ref(true)
const saving = ref(false)
const fileInput = ref(null)
const previewUrl = ref('')
const companyId = ref(null)
const errors = ref({})

const form = reactive({
  name: '',
  description: '',
  logo_file: null
})

const loadCompany = async () => {
  try {
    const response = await api.get('/employer/company')
    const company = response.data.data

    companyId.value = company.id
    form.name = company.name
    form.description = company.description

    if (company.logo_url) {
      previewUrl.value = company.logo_url
    }
  } catch (error) {
    if (error.response?.status === 404) {
      router.push('/employer/company')
    } else {
      console.error('Error loading company:', error)
    }
  } finally {
    loading.value = false
  }
}

const triggerFileInput = () => {
  fileInput.value.click()
}

const onFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.logo_file = file
    previewUrl.value = URL.createObjectURL(file)
    errors.value.logo = null
  }
}

const updateCompany = async () => {
  saving.value = true
  errors.value = {}

  try {
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('description', form.description)

    if (form.logo_file) {
      formData.append('logo', form.logo_file)
    }

    // Для PUT запроса с FormData нужно добавить _method
    formData.append('_method', 'PUT')

    const response = await api.post(`/companies/${companyId.value}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    router.push('/employer/company')

  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else if (error.response?.data?.message) {
      alert(error.response.data.message)
    } else {
      alert('Ошибка при обновлении компании')
    }
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  loadCompany()
})
</script>

<style scoped>
.edit-company {
  max-width: 600px;
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
  font-size: 28px;
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
}

.loading-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.company-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
}

.form-group {
  margin-bottom: 24px;
}

label {
  display: block;
  color: var(--text-secondary);
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 500;
}

.required {
  color: var(--color-danger);
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  color: var(--text-primary);
  font-size: 16px;
  transition: var(--transition-base);
}

.form-input:focus,
.form-textarea:focus {
  border-color: var(--color-primary);
  outline: none;
  box-shadow: var(--shadow-primary);
}

.form-input.error,
.form-textarea.error {
  border-color: var(--color-danger);
}

.form-textarea {
  resize: vertical;
  min-height: 150px;
}

.error-text {
  display: block;
  color: var(--color-danger);
  font-size: 12px;
  margin-top: 5px;
}

.logo-upload {
  display: flex;
  gap: 30px;
  align-items: center;
  flex-wrap: wrap;
}

.logo-preview {
  width: 100px;
  height: 100px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 50px;
  overflow: hidden;
}

.logo-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.logo-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.file-name {
  font-size: 12px;
  color: var(--text-secondary);
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

.btn-primary {
  padding: 12px 30px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 40px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-outline {
  padding: 12px 30px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition-base);
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
