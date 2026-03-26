<template>
  <div class="resumes-search">
    <div class="page-header">
      <h1>Поиск резюме</h1>
      <div class="header-actions">
        <span class="results-count">Найдено {{ filteredResumes.length }} резюме</span>
      </div>
    </div>

    <!-- Поисковая строка -->
    <div class="search-section">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Должность, навыки, ключевые слова..."
          class="search-input"
          @input="handleSearch"
        >
        <button class="search-btn">🔍</button>
      </div>

      <button class="filter-toggle" @click="showFilters = !showFilters">
        <span class="filter-icon">⚙️</span>
        {{ showFilters ? 'Скрыть фильтры' : 'Показать фильтры' }}
      </button>
    </div>

    <!-- Фильтры -->
    <Transition name="slide">
      <div v-if="showFilters" class="filters-panel">
        <div class="filters-grid">
          <div class="filter-group">
            <label>Зарплата от</label>
            <input
              type="number"
              v-model="filters.salary_from"
              placeholder="100 000"
              class="filter-input"
            >
          </div>
          <div class="filter-group">
            <label>Зарплата до</label>
            <input
              type="number"
              v-model="filters.salary_to"
              placeholder="300 000"
              class="filter-input"
            >
          </div>
          <div class="filter-group">
            <label>Город</label>
            <input
              type="text"
              v-model="filters.city"
              placeholder="Москва"
              class="filter-input"
            >
          </div>
          <div class="filter-group">
            <label>Опыт работы</label>
            <select v-model="filters.experience" class="filter-select">
              <option value="">Любой</option>
              <option value="no">Нет опыта</option>
              <option value="1-3">1-3 года</option>
              <option value="3-6">3-6 лет</option>
              <option value="6+">Более 6 лет</option>
            </select>
          </div>
        </div>

        <div class="filter-group full-width">
          <label>Ключевые навыки</label>
          <div class="skills-filter">
            <input
              type="text"
              v-model="newSkill"
              placeholder="Vue.js, React, TypeScript..."
              class="filter-input"
              @keydown.enter.prevent="addSkillFilter"
            >
            <button type="button" class="btn-outline-small" @click="addSkillFilter">
              Добавить
            </button>
          </div>
          <div class="filter-skills-list">
            <span v-for="skill in filters.skills" :key="skill" class="filter-skill-tag">
              {{ skill }}
              <button @click="removeSkillFilter(skill)">✕</button>
            </span>
          </div>
        </div>

        <div class="filters-actions">
          <button class="btn-outline" @click="resetFilters">
            Сбросить все
          </button>
          <button class="btn-primary" @click="applyFilters">
            Применить фильтры
          </button>
        </div>
      </div>
    </Transition>

    <!-- Сортировка и вид -->
    <div class="results-toolbar">
      <div class="sort-section">
        <span>Сортировать:</span>
        <button
          v-for="option in sortOptions"
          :key="option.value"
          class="sort-btn"
          :class="{ active: sortBy === option.value }"
          @click="sortBy = option.value"
        >
          {{ option.label }}
          <span v-if="sortBy === option.value" class="sort-arrow">
            {{ sortOrder === 'asc' ? '↑' : '↓' }}
          </span>
        </button>
      </div>

      <div class="view-toggle">
        <button
          class="view-btn"
          :class="{ active: viewMode === 'list' }"
          @click="viewMode = 'list'"
        >
          📋 Список
        </button>
        <button
          class="view-btn"
          :class="{ active: viewMode === 'grid' }"
          @click="viewMode = 'grid'"
        >
          🔲 Сетка
        </button>
      </div>
    </div>

    <!-- Список резюме -->
    <div class="resumes-list" :class="{ 'grid-view': viewMode === 'grid' }">
      <div
        v-for="resume in paginatedResumes"
        :key="resume.id"
        class="resume-card"
        :class="{ 'favorite': resume.isFavorite }"
      >
        <div class="resume-main">
          <div class="candidate-avatar">
            <span>👤</span>
          </div>
          <div class="candidate-info">
            <div class="candidate-header">
              <h3>
                <router-link :to="`/employer/resumes/${resume.id}`">
                  {{ resume.name }}
                </router-link>
              </h3>
              <div class="candidate-status" v-if="resume.status">
                <span class="status-badge" :class="resume.status">
                  {{ getStatusText(resume.status) }}
                </span>
              </div>
            </div>
            <p class="position">{{ resume.position }}</p>
            <div class="meta">
              <span>📍 {{ resume.city }}</span>
              <span>💼 {{ resume.experience }}</span>
              <span>💰 от {{ formatSalary(resume.salary) }} ₽</span>
            </div>
            <div class="skills">
              <span v-for="skill in resume.skills.slice(0, 5)" :key="skill" class="skill">
                {{ skill }}
              </span>
              <span v-if="resume.skills.length > 5" class="skill-more">
                +{{ resume.skills.length - 5 }}
              </span>
            </div>
          </div>
        </div>

        <div class="resume-actions">
          <button
            class="action-btn favorite-btn"
            :class="{ active: resume.isFavorite }"
            @click="toggleFavorite(resume.id)"
          >
            {{ resume.isFavorite ? '⭐' : '☆' }}
          </button>
          <button class="btn-primary-small" @click="inviteCandidate(resume.id)">
            ✉️ Пригласить
          </button>
          <router-link :to="`/employer/resumes/${resume.id}`" class="btn-outline-small">
            Подробнее
          </router-link>
        </div>
      </div>
    </div>

    <!-- Пустое состояние -->
    <div v-if="paginatedResumes.length === 0" class="empty-state">
      <div class="empty-icon">🔍</div>
      <h3>Ничего не найдено</h3>
      <p>Попробуйте изменить параметры поиска</p>
      <button class="btn-outline" @click="resetFilters">
        Сбросить фильтры
      </button>
    </div>

    <!-- Пагинация -->
    <div class="pagination">
      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="currentPage--"
      >
        ←
      </button>
      <button
        v-for="page in totalPages"
        :key="page"
        class="page-btn"
        :class="{ active: currentPage === page }"
        @click="currentPage = page"
      >
        {{ page }}
      </button>
      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
      >
        →
      </button>
    </div>

    <!-- Модалка приглашения -->
    <Transition name="fade">
      <div v-if="showInviteModal" class="modal-overlay" @click.self="showInviteModal = false">
        <div class="modal-container">
          <h3>Пригласить кандидата</h3>
          <p>Выберите вакансию для приглашения</p>

          <div class="vacancies-list">
            <label
              v-for="vacancy in myVacancies"
              :key="vacancy.id"
              class="vacancy-option"
            >
              <input
                type="radio"
                name="vacancy"
                :value="vacancy.id"
                v-model="selectedVacancy"
              >
              <div class="vacancy-info">
                <strong>{{ vacancy.title }}</strong>
                <span>{{ vacancy.salary }} ₽</span>
              </div>
            </label>
          </div>

          <div class="modal-actions">
            <button class="btn-outline" @click="showInviteModal = false">
              Отмена
            </button>
            <button class="btn-primary" @click="sendInvite">
              Отправить приглашение
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

const searchQuery = ref('')
const showFilters = ref(false)
const sortBy = ref('date')
const sortOrder = ref('desc')
const viewMode = ref('list')
const currentPage = ref(1)
const itemsPerPage = 10

const filters = reactive({
  salary_from: '',
  salary_to: '',
  city: '',
  experience: '',
  skills: []
})

const newSkill = ref('')

const showInviteModal = ref(false)
const selectedCandidateId = ref(null)
const selectedVacancy = ref(null)

const sortOptions = [
  { value: 'date', label: 'По дате' },
  { value: 'salary', label: 'По зарплате' },
  { value: 'experience', label: 'По опыту' }
]

const myVacancies = [
  { id: 1, title: 'Senior Frontend Developer', salary: '250 000 - 350 000' },
  { id: 2, title: 'Vue.js Developer', salary: '200 000 - 300 000' },
  { id: 3, title: 'Fullstack Developer', salary: '300 000 - 400 000' }
]

// Моковые данные резюме
const allResumes = ref([
  {
    id: 1,
    name: 'Александр Петров',
    position: 'Senior Frontend Developer',
    city: 'Москва',
    experience: '5 лет',
    salary: 250000,
    skills: ['Vue.js', 'React', 'TypeScript', 'Pinia', 'Webpack', 'Vite'],
    isFavorite: true,
    status: 'active',
    date: '2026-03-19'
  },
  {
    id: 2,
    name: 'Елена Соколова',
    position: 'Vue.js Developer',
    city: 'Санкт-Петербург',
    experience: '3 года',
    salary: 180000,
    skills: ['Vue.js', 'Vuex', 'JavaScript', 'HTML', 'CSS'],
    isFavorite: false,
    status: 'active',
    date: '2026-03-18'
  },
  {
    id: 3,
    name: 'Дмитрий Иванов',
    position: 'React Developer',
    city: 'Казань',
    experience: '4 года',
    salary: 200000,
    skills: ['React', 'Redux', 'TypeScript', 'Next.js'],
    isFavorite: false,
    status: 'active',
    date: '2026-03-17'
  },
  {
    id: 4,
    name: 'Анна Козлова',
    position: 'Frontend Team Lead',
    city: 'Москва',
    experience: '7 лет',
    salary: 350000,
    skills: ['Vue.js', 'React', 'Angular', 'TypeScript', 'Webpack', 'Team Lead'],
    isFavorite: true,
    status: 'active',
    date: '2026-03-16'
  },
  {
    id: 5,
    name: 'Павел Смирнов',
    position: 'Junior Frontend Developer',
    city: 'Новосибирск',
    experience: '1 год',
    salary: 80000,
    skills: ['HTML', 'CSS', 'JavaScript', 'Vue.js basics'],
    isFavorite: false,
    status: 'active',
    date: '2026-03-15'
  }
])

// Фильтрация и поиск
const filteredResumes = computed(() => {
  return allResumes.value.filter(resume => {
    // Поиск по тексту
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      const matchesSearch =
        resume.name.toLowerCase().includes(query) ||
        resume.position.toLowerCase().includes(query) ||
        resume.skills.some(s => s.toLowerCase().includes(query))
      if (!matchesSearch) return false
    }

    // Фильтр по зарплате
    if (filters.salary_from && resume.salary < filters.salary_from) return false
    if (filters.salary_to && resume.salary > filters.salary_to) return false

    // Фильтр по городу
    if (filters.city && !resume.city.toLowerCase().includes(filters.city.toLowerCase())) {
      return false
    }

    // Фильтр по навыкам
    if (filters.skills.length > 0) {
      const hasAllSkills = filters.skills.every(skill =>
        resume.skills.some(s => s.toLowerCase().includes(skill.toLowerCase()))
      )
      if (!hasAllSkills) return false
    }

    return true
  })
})

// Сортировка
const sortedResumes = computed(() => {
  const sorted = [...filteredResumes.value]

  if (sortBy.value === 'date') {
    sorted.sort((a, b) => {
      return sortOrder.value === 'asc'
        ? new Date(a.date) - new Date(b.date)
        : new Date(b.date) - new Date(a.date)
    })
  } else if (sortBy.value === 'salary') {
    sorted.sort((a, b) => {
      return sortOrder.value === 'asc'
        ? a.salary - b.salary
        : b.salary - a.salary
    })
  } else if (sortBy.value === 'experience') {
    sorted.sort((a, b) => {
      const expA = parseInt(a.experience) || 0
      const expB = parseInt(b.experience) || 0
      return sortOrder.value === 'asc' ? expA - expB : expB - expA
    })
  }

  return sorted
})

// Пагинация
const totalPages = computed(() =>
  Math.ceil(sortedResumes.value.length / itemsPerPage)
)

const paginatedResumes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return sortedResumes.value.slice(start, end)
})

// Методы
const handleSearch = () => {
  currentPage.value = 1
}

const addSkillFilter = () => {
  if (newSkill.value && !filters.skills.includes(newSkill.value)) {
    filters.skills.push(newSkill.value)
    newSkill.value = ''
  }
}

const removeSkillFilter = (skill) => {
  filters.skills = filters.skills.filter(s => s !== skill)
}

const resetFilters = () => {
  filters.salary_from = ''
  filters.salary_to = ''
  filters.city = ''
  filters.experience = ''
  filters.skills = []
  searchQuery.value = ''
  currentPage.value = 1
}

const applyFilters = () => {
  showFilters.value = false
  currentPage.value = 1
}

const formatSalary = (salary) => {
  return salary.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

const getStatusText = (status) => {
  const statuses = {
    active: 'Активно ищет',
    passive: 'Пассивный поиск',
    employed: 'Работает'
  }
  return statuses[status] || status
}

const toggleFavorite = (id) => {
  const resume = allResumes.value.find(r => r.id === id)
  if (resume) {
    resume.isFavorite = !resume.isFavorite
  }
}

const inviteCandidate = (id) => {
  selectedCandidateId.value = id
  selectedVacancy.value = null
  showInviteModal.value = true
}

const sendInvite = () => {
  if (selectedVacancy.value) {
    console.log('Invite sent:', {
      candidateId: selectedCandidateId.value,
      vacancyId: selectedVacancy.value
    })
    showInviteModal.value = false
  }
}
</script>

<style scoped>
.resumes-search {
  max-width: 1200px;
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
  font-size: 32px;
}

.results-count {
  color: var(--text-secondary);
  font-size: 16px;
  background: var(--bg-tertiary);
  padding: 8px 16px;
  border-radius: 30px;
}

.search-section {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.search-box {
  flex: 1;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 16px 24px;
  padding-right: 60px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  font-size: 16px;
  transition: var(--transition-base);
}

.search-input:focus {
  border-color: var(--color-primary);
  outline: none;
  box-shadow: var(--shadow-primary);
}

.search-btn {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 20px;
  color: var(--text-secondary);
  transition: var(--transition-base);
}

.search-btn:hover {
  color: var(--color-primary);
  transform: translateY(-50%) scale(1.1);
}

.filter-toggle {
  padding: 0 30px;
  background: var(--bg-tertiary);
  border: 2px solid var(--border-color);
  border-radius: 40px;
  color: var(--text-primary);
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition-base);
}

.filter-toggle:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.filter-icon {
  font-size: 18px;
}

.filters-panel {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: var(--shadow-lg);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group.full-width {
  grid-column: 1 / -1;
}

.filter-group label {
  color: var(--text-secondary);
  font-size: 14px;
  font-weight: 500;
}

.filter-input,
.filter-select {
  padding: 12px 16px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  color: var(--text-primary);
  font-size: 14px;
  transition: var(--transition-base);
}

.filter-input:focus,
.filter-select:focus {
  border-color: var(--color-primary);
  outline: none;
}

.skills-filter {
  display: flex;
  gap: 10px;
}

.skills-filter .filter-input {
  flex: 1;
}

.filter-skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 10px;
}

.filter-skill-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--color-primary);
  border-radius: 30px;
  color: var(--text-primary);
  font-size: 13px;
}

.filter-skill-tag button {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  font-size: 14px;
  padding: 0 2px;
}

.filter-skill-tag button:hover {
  color: var(--color-danger);
}

.filters-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

.results-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding: 15px 0;
  border-bottom: 1px solid var(--border-color);
}

.sort-section {
  display: flex;
  align-items: center;
  gap: 15px;
  color: var(--text-secondary);
}

.sort-btn {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  padding: 6px 12px;
  border-radius: 20px;
  transition: var(--transition-base);
}

.sort-btn.active {
  color: var(--color-primary);
  background: rgba(0, 255, 136, 0.1);
}

.sort-arrow {
  margin-left: 4px;
}

.view-toggle {
  display: flex;
  gap: 8px;
}

.view-btn {
  padding: 6px 16px;
  background: none;
  border: 1px solid var(--border-color);
  border-radius: 20px;
  color: var(--text-secondary);
  cursor: pointer;
  transition: var(--transition-base);
}

.view-btn.active {
  background: var(--gradient-primary);
  color: var(--text-dark);
  border: none;
}

.resumes-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.resumes-list.grid-view {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.resume-card {
  background: var(--bg-card-gradient);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 20px;
  transition: var(--transition-base);
  position: relative;
  overflow: hidden;
}

.resume-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
}

.resume-card:hover {
  border-color: var(--color-primary);
  box-shadow: var(--shadow-primary);
}

.resume-card:hover::before {
  transform: translateX(0);
}

.resume-card.favorite {
  border-color: var(--color-primary);
}

.resume-main {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.candidate-avatar {
  width: 60px;
  height: 60px;
  background: var(--bg-secondary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  border: 2px solid var(--color-primary);
}

.candidate-info {
  flex: 1;
}

.candidate-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}

.candidate-header h3 a {
  color: var(--text-primary);
  text-decoration: none;
  font-size: 18px;
}

.candidate-header h3 a:hover {
  color: var(--color-primary);
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.status-badge.active {
  background: rgba(0, 255, 136, 0.1);
  color: var(--color-primary);
}

.position {
  color: var(--color-primary);
  font-weight: 500;
  margin-bottom: 10px;
}

.meta {
  display: flex;
  gap: 20px;
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}

.skills {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.skill {
  padding: 4px 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  font-size: 12px;
  color: var(--text-secondary);
}

.skill-more {
  padding: 4px 12px;
  background: var(--bg-tertiary);
  border-radius: 20px;
  font-size: 12px;
  color: var(--text-secondary);
}

.resume-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid var(--border-color);
}

.action-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  opacity: 0.7;
  transition: var(--transition-base);
  padding: 5px;
}

.action-btn:hover,
.action-btn.active {
  opacity: 1;
  transform: scale(1.2);
}

.btn-primary-small {
  padding: 8px 16px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  text-decoration: none;
  transition: var(--transition-base);
}

.btn-primary-small:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary);
}

.btn-outline-small {
  padding: 8px 16px;
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  font-size: 14px;
  cursor: pointer;
  text-decoration: none;
  transition: var(--transition-base);
}

.btn-outline-small:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-tertiary);
  border-radius: 16px;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-state h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 40px;
}

.page-btn {
  width: 44px;
  height: 44px;
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  color: var(--text-secondary);
  cursor: pointer;
  transition: var(--transition-base);
  font-size: 16px;
}

.page-btn:hover:not(:disabled) {
  border-color: var(--color-primary);
  color: var(--color-primary);
}

.page-btn.active {
  background: var(--gradient-primary);
  color: var(--text-dark);
  border: none;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Модальное окно */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-container {
  background: var(--bg-tertiary);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 30px;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.modal-container h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
  font-size: 24px;
}

.modal-container p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.vacancies-list {
  margin-bottom: 20px;
}

.vacancy-option {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: var(--bg-secondary);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  margin-bottom: 10px;
  cursor: pointer;
  transition: var(--transition-base);
}

.vacancy-option:hover {
  border-color: var(--color-primary);
}

.vacancy-option input[type="radio"] {
  accent-color: var(--color-primary);
  width: 20px;
  height: 20px;
}

.vacancy-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.vacancy-info strong {
  color: var(--text-primary);
  font-size: 16px;
}

.vacancy-info span {
  color: var(--color-primary);
  font-size: 14px;
}

.modal-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 20px;
}

/* Анимации */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .search-section {
    flex-direction: column;
  }

  .filters-grid {
    grid-template-columns: 1fr;
  }

  .results-toolbar {
    flex-direction: column;
    gap: 15px;
  }

  .sort-section {
    flex-wrap: wrap;
  }

  .resume-main {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .candidate-header {
    flex-direction: column;
    gap: 10px;
  }

  .meta {
    flex-wrap: wrap;
    justify-content: center;
  }

  .skills {
    justify-content: center;
  }

  .resume-actions {
    flex-wrap: wrap;
    justify-content: center;
  }

  .pagination {
    flex-wrap: wrap;
  }
}
</style>
