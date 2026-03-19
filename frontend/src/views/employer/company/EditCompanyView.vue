<template>
  <div class="edit-company">
    <div class="page-header">
      <h1>Редактирование компании</h1>
      <router-link to="/employer/company" class="back-link">
        ← Назад
      </router-link>
    </div>

    <form class="company-form" @submit.prevent="saveCompany">
      <!-- Логотип -->
      <div class="form-group">
        <label>Логотип компании</label>
        <div class="logo-upload">
          <div class="logo-preview">
            <span>🏢</span>
          </div>
          <button type="button" class="btn-outline">
            Загрузить логотип
          </button>
        </div>
      </div>

      <div class="form-group">
        <label>Название компании <span class="required">*</span></label>
        <input
          v-model="form.name"
          type="text"
          placeholder="ООО 'ТехноКорп'"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label>Описание компании</label>
        <textarea
          v-model="form.description"
          rows="6"
          placeholder="Расскажите о компании..."
          class="form-textarea"
        ></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Город</label>
          <input
            v-model="form.city"
            type="text"
            placeholder="Москва"
            class="form-input"
          >
        </div>
        <div class="form-group">
          <label>Количество сотрудников</label>
          <select v-model="form.employee_count" class="form-select">
            <option value="1-10">1-10</option>
            <option value="11-50">11-50</option>
            <option value="51-100">51-100</option>
            <option value="101-500">101-500</option>
            <option value="500+">500+</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Email для связи</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="hr@company.ru"
            class="form-input"
          >
        </div>
        <div class="form-group">
          <label>Телефон</label>
          <input
            v-model="form.phone"
            type="tel"
            placeholder="+7 (495) 123-45-67"
            class="form-input"
          >
        </div>
      </div>

      <div class="form-group">
        <label>Сайт</label>
        <input
          v-model="form.website"
          type="url"
          placeholder="https://www.company.ru"
          class="form-input"
        >
      </div>

      <div class="form-group">
        <label>Год основания</label>
        <input
          v-model="form.founded_year"
          type="number"
          placeholder="2015"
          class="form-input"
        >
      </div>

      <div class="form-actions">
        <router-link to="/employer/company" class="btn-outline">
          Отмена
        </router-link>
        <button type="submit" class="btn-primary">
          Сохранить изменения
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const form = reactive({
  name: 'ООО "ТехноКорп"',
  description: 'IT-компания, специализирующаяся на разработке высоконагруженных проектов',
  city: 'Москва',
  employee_count: '51-100',
  email: 'hr@techcorp.ru',
  phone: '+7 (495) 123-45-67',
  website: 'https://www.techcorp.ru',
  founded_year: '2015'
})

const saveCompany = () => {
  console.log('Saving company:', form)
  // Здесь будет сохранение
}
</script>

<style scoped>
.edit-company {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.company-form {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

label {
  display: block;
  color: var(--text-secondary);
  margin-bottom: 8px;
  font-size: 14px;
}

.required {
  color: var(--color-danger);
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 16px;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  border-color: var(--color-primary);
  outline: none;
}

.logo-upload {
  display: flex;
  align-items: center;
  gap: 30px;
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
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 30px;
}

.btn-primary {
  padding: 12px 30px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  cursor: pointer;
}

.btn-outline {
  padding: 12px 30px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
}

.btn-outline:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}
</style>
