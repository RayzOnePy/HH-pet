<template>
  <main class="contacts">
    <h1>Контакты</h1>
    <p class="description">
      Свяжитесь с нами любым удобным способом
    </p>

    <div class="contact-info">
      <div class="contact-item">
        <h3>📍 Адрес</h3>
        <p>г. Москва, ул. Программистов, д. 1</p>
      </div>

      <div class="contact-item">
        <h3>📧 Email</h3>
        <p>info@myfirstvue.ru</p>
      </div>

      <div class="contact-item">
        <h3>📱 Телефон</h3>
        <p>+7 (999) 123-45-67</p>
      </div>

      <div class="contact-item">
        <h3>🕒 Время работы</h3>
        <p>Пн-Пт: 9:00 - 18:00</p>
      </div>
    </div>

    <div class="map-placeholder">
      <p>🗺️ Здесь могла быть карта</p>
    </div>
  </main>
</template>

<style scoped>
.contacts {
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
  color: #4a5568;
  margin-bottom: 3rem;
}

.contact-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.contact-item {
  padding: 1.5rem;
  background: #f7fafc;
  border-radius: 8px;
  text-align: center;
}

.contact-item h3 {
  color: #2c3e50;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.contact-item p {
  color: #4a5568;
  line-height: 1.5;
}

.map-placeholder {
  background: #e2e8f0;
  height: 200px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #4a5568;
  font-size: 1.2rem;
}
</style>
