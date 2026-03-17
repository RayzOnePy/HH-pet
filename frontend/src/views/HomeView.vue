<template>
  <main class="home">
    <h1>Добро пожаловать на главную страницу!</h1>
    <p class="description">
      Это мой первый проект на Vue.js. Здесь я учусь создавать интерфейсы
      и работать с компонентами.
    </p>
    <div class="content">
      <h2>Что я уже умею:</h2>
      <ul>
        <li>✓ Создавать компоненты</li>
        <li>✓ Настраивать маршрутизацию</li>
        <li>✓ Работать с шаблонами</li>
        <li>✓ Стилизовать компоненты</li>
      </ul>
    </div>
  </main>
</template>

<script setup>
// Логика страницы (пока пусто)
</script>

<style scoped>
.home {
  max-width: 800px;
  margin: 2rem auto;
  padding: 0 2rem;
}

h1 {
  color: #2c3e50;
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.description {
  font-size: 1.2rem;
  line-height: 1.6;
  color: #4a5568;
  margin-bottom: 2rem;
}

.content {
  background-color: #f7fafc;
  padding: 2rem;
  border-radius: 8px;
  border-left: 4px solid #42b883;
}

.content h2 {
  color: #2c3e50;
  margin-bottom: 1rem;
}

.content ul {
  list-style: none;
  padding: 0;
}

.content li {
  padding: 0.5rem 0;
  color: #4a5568;
  font-size: 1.1rem;
}
</style>
