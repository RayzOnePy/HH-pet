<template>
  <main class="about">
    <h1>О нас</h1>
    <p class="description">
      Мы начинающая команда разработчиков, изучающих Vue.js.
      Этот проект создан в обучающих целях.
    </p>

    <div class="cards">
      <div class="card">
        <h3>Наша миссия</h3>
        <p>Создавать качественные и современные веб-приложения</p>
      </div>

      <div class="card">
        <h3>Наши ценности</h3>
        <p>Обучение, развитие и обмен опытом</p>
      </div>

      <div class="card">
        <h3>Наши планы</h3>
        <p>Освоить Vue.js и создать полезный пет-проект</p>
      </div>
    </div>
  </main>
</template>

<style scoped>
.about {
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
  margin-bottom: 3rem;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}

.card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border: 1px solid #e2e8f0;
  transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card h3 {
  color: #2c3e50;
  margin-bottom: 1rem;
  font-size: 1.3rem;
}

.card p {
  color: #4a5568;
  line-height: 1.5;
}
</style>
