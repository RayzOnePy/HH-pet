<template>
  <div class="edit-resume">
    <div class="page-header">
      <h1>Редактирование резюме</h1>
      <router-link to="/applicant/resume" class="back-link">
        ← Назад к резюме
      </router-link>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка резюме...</p>
    </div>

    <ResumeForm
      v-else-if="hasResume"
      :initial-data="formData"
      :errors="errors"
      :saving="saving"
      :degrees="educationDegrees"
      :work-schedules="workSchedules"
      submit-button-text="Сохранить изменения"
      @submit="handleSubmit"
    />

    <div v-else class="empty-state">
      <div class="empty-icon">📄</div>
      <h3>Резюме не найдено</h3>
      <p>У вас ещё нет резюме. Создайте его, чтобы работодатели могли найти вас</p>
      <router-link to="/applicant/resume/create" class="btn-primary">
        + Создать резюме
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useResume } from '../../../composables/useResume'
import { useDictionaries } from '../../../composables/useDictionaries'
import ResumeForm from '../../../components/ResumeForm.vue'

const router = useRouter()
const { resume, loading, hasResume, fetchMyResume, updateResume } = useResume()
const { educationDegrees, workSchedules, fetchEducationDegrees, fetchWorkSchedules } = useDictionaries()
const saving = ref(false)
const errors = ref({})

const formData = reactive({
  title: '',
  salary: null,
  work_schedule_ids: [],
  contacts: [],
  skills: [],
  work_experiences: [],
  educations: []
})

const populateForm = () => {
  if (!resume.value) return

  formData.title = resume.value.title || ''
  formData.salary = resume.value.salary || null
  formData.work_schedule_ids = resume.value.work_schedules?.map(s => s.id) || []
  formData.contacts = resume.value.contacts?.map(c => ({ ...c })) || []
  formData.skills = resume.value.skills?.map(s => ({ skill: s.skill, level: s.level })) || []
  formData.work_experiences = resume.value.work_experiences?.map(w => ({ ...w })) || []
  formData.educations = resume.value.educations?.map(e => ({
    ...e,
    degree_id: e.degree?.id
  })) || []
}

const handleSubmit = async (data) => {
  saving.value = true
  errors.value = {}

  const submitData = {
    ...data,
    salary: data.salary ? Number(data.salary) : null,
    work_schedule_ids: data.work_schedule_ids || [],
    contacts: data.contacts.filter(c => c.value.trim()),
    skills: data.skills.filter(s => s.skill.trim()),
    work_experiences: data.work_experiences.filter(w => w.title.trim()),
    educations: data.educations.filter(e => e.institution.trim())
  }

  const result = await updateResume(submitData)

  if (result.success) {
    router.push('/applicant/resume')
  } else {
    if (result.errors) {
      errors.value = result.errors
      setTimeout(() => {
        const firstError = document.querySelector('.error-text')
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' })
        }
      }, 100)
    } else {
      alert(result.error || 'Ошибка обновления резюме')
    }
  }

  saving.value = false
}

onMounted(async () => {
  await Promise.all([
    fetchEducationDegrees(),
    fetchWorkSchedules(),
    fetchMyResume()
  ])

  if (hasResume.value) {
    populateForm()
  }
})
</script>

<style scoped>
.edit-resume {
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

h1 {
  color: var(--text-primary);
  font-size: 28px;
}

.back-link {
  color: var(--color-primary);
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
}

.loading-state {
  text-align: center;
  padding: 60px 20px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--border-color);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: var(--bg-card-gradient);
  border: 2px dashed var(--border-color);
  border-radius: 24px;
  margin: 40px auto;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.7;
}

.empty-state h3 {
  color: var(--text-primary);
  margin-bottom: 10px;
}

.empty-state p {
  color: var(--text-secondary);
  margin-bottom: 20px;
}

.btn-primary {
  padding: 12px 24px;
  background: var(--gradient-primary);
  border: none;
  border-radius: 30px;
  color: var(--text-dark);
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-lg);
}
</style>
