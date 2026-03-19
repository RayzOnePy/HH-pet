import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: 'Главная' }
    },
    {
      path: '/vacancies',
      name: 'vacancies',
      component: () => import('../views/VacanciesView.vue'),
      meta: { title: 'Вакансии' }
    },
    {
      path: '/companies',
      name: 'companies',
      component: () => import('../views/CompaniesView.vue'),
      meta: { title: 'Компании' }
    },
    {
      path: '/resumes',
      name: 'resumes',
      component: () => import('../views/ResumesView.vue'),
      meta: { title: 'Резюме' }
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
      meta: { title: 'О проекте' }
    }
  ]
})

// Динамическое изменение заголовка страницы
router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} | HHPet`
  next()
})

export default router
