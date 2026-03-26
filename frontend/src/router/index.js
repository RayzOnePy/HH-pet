import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import HomeView from '../views/shared/HomeView.vue'

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
      path: '/about',
      name: 'about',
      component: () => import('../views/shared/AboutView.vue'),
      meta: { title: 'О проекте' }
    },

    {
      path: '/vacancies',
      name: 'vacancies',
      component: () => import('../views/shared/VacanciesView.vue'),
      meta: { title: 'Вакансии' }
    },
    {
      path: '/vacancies/:id',
      name: 'vacancy',
      component: () => import('../views/shared/VacancyView.vue'),
      meta: { title: 'Вакансия' }
    },

    // РАБОТОДАТЕЛЬ
    {
      path: '/employer',
      redirect: '/employer/dashboard',
      meta: { requiresEmployer: true }
    },
    {
      path: '/employer/dashboard',
      name: 'employerDashboard',
      component: () => import('../views/employer/dashboard/DashboardView.vue'),
      meta: { title: 'Панель управления', requiresEmployer: true }
    },
    {
      path: '/employer/vacancies',
      name: 'myVacancies',
      component: () => import('../views/employer/vacancies/MyVacanciesView.vue'),
      meta: { title: 'Мои вакансии', requiresEmployer: true }
    },
    {
      path: '/employer/vacancies/create',
      name: 'createVacancy',
      component: () => import('../views/employer/vacancies/CreateVacancyView.vue'),
      meta: { title: 'Создание вакансии', requiresEmployer: true }
    },
    {
      path: '/employer/vacancies/:id/edit',
      name: 'editVacancy',
      component: () => import('../views/employer/vacancies/EditVacancyView.vue'),
      meta: { title: 'Редактирование вакансии', requiresEmployer: true }
    },
    {
      path: '/employer/resumes',
      name: 'employerResumes',
      component: () => import('../views/employer/resumes/ResumesView.vue'),
      meta: { title: 'Поиск резюме', requiresEmployer: true }
    },
    {
      path: '/employer/resumes/:id',
      name: 'employerResumeDetail',
      component: () => import('../views/employer/resumes/ResumeView.vue'),
      meta: { title: 'Резюме', requiresEmployer: true }
    },
    {
      path: '/employer/responses',
      name: 'employerResponses',
      component: () => import('../views/employer/responses/ResponsesView.vue'),
      meta: { title: 'Отклики', requiresEmployer: true }
    },
    {
      path: '/employer/company/create',
      name: 'createCompany',
      component: () => import('../views/employer/company/CreateCompanyView.vue'),
      meta: { title: 'Создание компании', requiresEmployer: true }
    },
    {
      path: '/employer/company/edit',
      name: 'editCompany',
      component: () => import('../views/employer/company/EditCompanyView.vue'),
      meta: { title: 'Редактирование компании', requiresEmployer: true }
    },
    {
      path: '/employer/company',
      name: 'myCompany',
      component: () => import('../views/employer/company/CompanyView.vue'),
      meta: { title: 'Моя компания', requiresEmployer: true }
    },

    // СОИСКАТЕЛЬ
    {
      path: '/applicant',
      redirect: '/applicant/dashboard',
      meta: { requiresApplicant: true }
    },
    {
      path: '/applicant/dashboard',
      name: 'applicantDashboard',
      component: () => import('../views/applicant/dashboard/DashboardView.vue'),
      meta: { title: 'Панель соискателя', requiresApplicant: true }
    },
    {
      path: '/applicant/vacancies',
      name: 'applicantVacancies',
      component: () => import('../views/applicant/vacancies/VacanciesView.vue'),
      meta: { title: 'Поиск вакансий', requiresApplicant: true }
    },
    {
      path: '/applicant/vacancies/:id',
      name: 'applicantVacancy',
      component: () => import('../views/applicant/vacancies/VacancyView.vue'),
      meta: { title: 'Вакансия', requiresApplicant: true }
    },
    {
      path: '/applicant/responses',
      name: 'applicantResponses',
      component: () => import('../views/applicant/responses/ResponsesView.vue'),
      meta: { title: 'Мои отклики', requiresApplicant: true }
    },
    {
      path: '/applicant/favorites-vacancies',
      name: 'applicantFavorites',
      component: () => import('../views/applicant/vacancies/FavoritesView.vue'),
      meta: { title: 'Избранное', requiresApplicant: true }
    },
    {
      path: '/applicant/resume',
      name: 'applicantResume',
      component: () => import('../views/applicant/resume/MyResumeView.vue'),
      meta: { title: 'Моё резюме', requiresApplicant: true }
    },
    {
      path: '/applicant/resume/create',
      name: 'applicantResumeCreate',
      component: () => import('../views/applicant/resume/CreateResumeView.vue'),
      meta: { title: 'Создание резюме', requiresApplicant: true }
    },
    {
      path: '/applicant/resume/edit',
      name: 'applicantResumeEdit',
      component: () => import('../views/applicant/resume/EditResumeView.vue'),
      meta: { title: 'Редактирование резюме', requiresApplicant: true }
    }

  ]
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = authStore.isLoggedIn
  const userRole = authStore.userRole

  document.title = `${to.meta.title || 'HHPet'} | HHPet`

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'home' })
    return
  }

  if (to.meta.requiresApplicant && (!isAuthenticated || userRole !== 'applicant')) {
    next({ name: 'home' })
    return
  }

  if (to.meta.requiresEmployer && (!isAuthenticated || userRole !== 'employer')) {
    next({ name: 'home' })
    return
  }

  next()
})

export default router
