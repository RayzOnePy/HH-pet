import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost/api', // или твой URL
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Интерсептор для обработки ошибок
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      // Сервер вернул ошибку
      return Promise.reject(error.response.data)
    } else if (error.request) {
      // Запрос был отправлен, но нет ответа
      return Promise.reject({ message: 'Сервер не отвечает' })
    } else {
      // Что-то пошло не так при настройке запроса
      return Promise.reject({ message: 'Ошибка при отправке запроса' })
    }
  }
)

export default api
