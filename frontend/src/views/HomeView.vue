<template>
  <div class="home">
    <h1>HH.ru Clone</h1>
    <button @click="testApi" :disabled="loading">
      {{ loading ? 'Загрузка...' : 'Проверить API' }}
    </button>

    <div v-if="result" class="result">
      <h3>Ответ:</h3>
      <pre>{{ JSON.stringify(result, null, 2) }}</pre>
    </div>

    <div v-if="error" class="error">
      <h3>Ошибка:</h3>
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const loading = ref(false)
const result = ref(null)
const error = ref(null)

const testApi = async () => {
  loading.value = true
  error.value = null
  result.value = null

  try {
    const response = await fetch('http://localhost/api/test')
    const data = await response.json()
    result.value = data
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.home { padding: 20px; }
button {
  padding: 10px 20px;
  font-size: 16px;
  background: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:disabled { background: #ccc; }
.result {
  margin-top: 20px;
  padding: 20px;
  background: #f0f9f0;
  border: 1px solid #42b983;
  border-radius: 5px;
}
.error {
  margin-top: 20px;
  padding: 20px;
  background: #fff0f0;
  border: 1px solid #ff4444;
  border-radius: 5px;
}
pre { background: #f5f5f5; padding: 10px; border-radius: 3px; }
</style>
