<template>
  <div class="create-resume">
    <div class="page-header">
      <h1>Создание резюме</h1>
      <router-link to="/applicant/resume" class="back-link">
        ← Назад
      </router-link>
    </div>

    <ResumeForm
      :initial-data="formData"
      :errors="errors"
      :saving="saving"
      :degrees="educationDegrees"
      :work-schedules="workSchedules"
      submit-button-text="Создать резюме"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useResume } from '../../../composables/useResume'
import { useDictionaries } from '../../../composables/useDictionaries'
import ResumeForm from '../../../components/ResumeForm.vue'

const router = useRouter()
const { createResume } = useResume()
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

  const result = await createResume(submitData)

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
      alert(result.error || 'Ошибка создания резюме')
    }
  }

  saving.value = false
}

onMounted(async () => {
  await Promise.all([
    fetchEducationDegrees(),
    fetchWorkSchedules()
  ])
})
</script>

<style scoped>
.create-resume {
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
</style>
