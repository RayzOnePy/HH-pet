import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Добавляем токен к каждому запросу
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Интерсептор для обработки ошибок
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      // Если неавторизован - чистим localStorage
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }

    if (error.response) {
      return Promise.reject(error.response.data)
    } else if (error.request) {
      return Promise.reject({ message: 'Сервер не отвечает' })
    } else {
      return Promise.reject({ message: 'Ошибка при отправке запроса' })
    }
  }
)

export default api
