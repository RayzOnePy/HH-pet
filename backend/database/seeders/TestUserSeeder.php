<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\EducationDegree;
use App\Models\Resume;
use App\Models\ResumeEducation;
use App\Models\ResumeSkill;
use App\Models\ResumeWork;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRole = CompanyRole::where('name', 'owner')->first();

        // ==================== ПОЛЬЗОВАТЕЛЬ 1: Работодатель без компании ====================
        $emptyEmployer = User::create([
            'first_name' => 'Алексей',
            'last_name' => 'Петров',
            'middle_name' => 'Сергеевич',
            'email' => 'ocus288.empty-employer@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $emptyEmployer->assignRole(UserRole::EMPLOYER);

        // ==================== ПОЛЬЗОВАТЕЛЬ 2: Работодатель с компанией ====================
        $employer = User::create([
            'first_name' => 'Екатерина',
            'last_name' => 'Смирнова',
            'middle_name' => 'Андреевна',
            'email' => 'ocus288.employer@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $employer->assignRole(UserRole::EMPLOYER);

        // Создаем компанию
        $company = Company::create([
            'name' => 'ТехноСила',
            'description' => 'Крупная IT-компания, занимающаяся разработкой программного обеспечения для бизнеса. На рынке более 10 лет. В нашей команде работают высококвалифицированные специалисты, мы ценим инициативность и профессиональный рост.',
            'logo_url' => null,
        ]);

        // Привязываем пользователя к компании
        $employer->companies()->attach($company->id, [
            'company_role_id' => $ownerRole->id,
        ]);

        // Создаем 200 активных и 100 архивных вакансий
        $jobTitles = [
            'PHP Developer', 'Laravel Developer', 'Full Stack Developer', 'Frontend Developer',
            'Backend Developer', 'Vue.js Developer', 'React Developer', 'Python Developer',
            'DevOps Engineer', 'System Administrator', 'Project Manager', 'Product Manager',
            'QA Engineer', 'Team Lead', 'Technical Architect', 'Data Scientist',
            'Mobile Developer', 'iOS Developer', 'Android Developer', 'UX/UI Designer'
        ];

        $cities = ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань', 'Нижний Новгород', 'Краснодар'];
        $experiences = ['no', '1-3', '3-6', '6+'];

        $activeVacancies = [];
        $inactiveVacancies = [];

        for ($i = 0; $i < 200; $i++) {
            $title = $jobTitles[array_rand($jobTitles)];
            $salaryFrom = rand(50000, 150000);
            $salaryTo = $salaryFrom + rand(30000, 100000);

            $activeVacancies[] = [
                'company_id' => $company->id,
                'creator_id' => $employer->id,
                'title' => $title,
                'description' => $this->generateVacancyDescription($title),
                'salary_from' => $salaryFrom,
                'salary_to' => $salaryTo,
                'experience' => $experiences[array_rand($experiences)],
                'status' => 'active',
                'city' => $cities[array_rand($cities)],
                'created_at' => now()->subDays(rand(0, 180)),
                'updated_at' => now(),
            ];
        }

        for ($i = 0; $i < 100; $i++) {
            $title = $jobTitles[array_rand($jobTitles)];
            $salaryFrom = rand(50000, 150000);
            $salaryTo = $salaryFrom + rand(30000, 100000);

            $inactiveVacancies[] = [
                'company_id' => $company->id,
                'creator_id' => $employer->id,
                'title' => $title,
                'description' => $this->generateVacancyDescription($title),
                'salary_from' => $salaryFrom,
                'salary_to' => $salaryTo,
                'experience' => $experiences[array_rand($experiences)],
                'status' => 'inactive',
                'city' => $cities[array_rand($cities)],
                'created_at' => now()->subDays(rand(0, 365)),
                'updated_at' => now(),
            ];
        }

        Vacancy::insert(array_merge($activeVacancies, $inactiveVacancies));

        // ==================== ПОЛЬЗОВАТЕЛЬ 3: Соискатель без резюме ====================
        $emptyApplicant = User::create([
            'first_name' => 'Дмитрий',
            'last_name' => 'Кузнецов',
            'middle_name' => 'Игоревич',
            'email' => 'ocus288.empty-applicant@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '1995-06-15',
            'gender' => 'male',
        ]);
        $emptyApplicant->assignRole(UserRole::APPLICANT);

        // ==================== ПОЛЬЗОВАТЕЛЬ 4: Соискатель с полным резюме ====================
        $applicant = User::create([
            'first_name' => 'Анна',
            'last_name' => 'Иванова',
            'middle_name' => 'Сергеевна',
            'email' => 'ocus288.applicant@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '1992-03-22',
            'gender' => 'female',
        ]);
        $applicant->assignRole(UserRole::APPLICANT);

        $resume = Resume::create([
            'user_id' => $applicant->id,
            'title' => 'Senior Laravel Developer',
            'salary' => 180000,
        ]);

        $works = [
            [
                'title' => 'Lead PHP Developer',
                'experience_summary' => 'Руководство командой из 5 разработчиков. Разработка высоконагруженных проектов на Laravel. Оптимизация запросов и производительности. Внедрение CI/CD процессов.',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'is_current' => true,
            ],
            [
                'title' => 'PHP Developer',
                'experience_summary' => 'Разработка и поддержка веб-приложений на Laravel и Symfony. Написание REST API. Работа с PostgreSQL и Redis.',
                'start_date' => '2019-03-01',
                'end_date' => '2021-12-31',
                'is_current' => false,
            ],
            [
                'title' => 'Junior Web Developer',
                'experience_summary' => 'Разработка сайтов на PHP и JavaScript. Верстка адаптивных интерфейсов. Работа с Git.',
                'start_date' => '2017-09-01',
                'end_date' => '2019-02-28',
                'is_current' => false,
            ],
        ];

        foreach ($works as $work) {
            ResumeWork::create([
                'resume_id' => $resume->id,
                'title' => $work['title'],
                'experience_summary' => $work['experience_summary'],
                'start_date' => $work['start_date'],
                'end_date' => $work['end_date'],
                'is_current' => $work['is_current'],
            ]);
        }

        $degrees = EducationDegree::all();

        $educations = [
            [
                'institution' => 'Московский государственный университет',
                'faculty' => 'Факультет вычислительной математики и кибернетики',
                'specialty' => 'Прикладная математика и информатика',
                'qualification' => 'Магистр',
                'degree_id' => $degrees->where('name', 'Магистр')->first()?->id ?? 4,
                'start_date' => '2015-09-01',
                'end_date' => '2017-06-30',
                'is_current' => false,
            ],
            [
                'institution' => 'Московский государственный университет',
                'faculty' => 'Факультет вычислительной математики и кибернетики',
                'specialty' => 'Прикладная математика и информатика',
                'qualification' => 'Бакалавр',
                'degree_id' => $degrees->where('name', 'Высшее')->first()?->id ?? 3,
                'start_date' => '2011-09-01',
                'end_date' => '2015-06-30',
                'is_current' => false,
            ],
        ];

        foreach ($educations as $education) {
            ResumeEducation::create([
                'resume_id' => $resume->id,
                'institution' => $education['institution'],
                'faculty' => $education['faculty'],
                'specialty' => $education['specialty'],
                'qualification' => $education['qualification'],
                'degree_id' => $education['degree_id'],
                'start_date' => $education['start_date'],
                'end_date' => $education['end_date'],
                'is_current' => $education['is_current'],
            ]);
        }

        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'PostgreSQL', 'Redis',
            'Docker', 'Git', 'REST API', 'MySQL', 'Linux', 'Nginx'
        ];

        foreach ($skills as $skill) {
            $level = in_array($skill, ['PHP', 'Laravel']) ? 'advanced' : 'intermediate';
            ResumeSkill::create([
                'resume_id' => $resume->id,
                'skill' => $skill,
                'level' => $level,
            ]);
        }
    }

    private function generateVacancyDescription(string $title): string
    {
        $requirements = [
            'Опыт работы от 2 лет',
            'Знание PHP 8+',
            'Опыт работы с Laravel',
            'Понимание ООП и паттернов проектирования',
            'Знание SQL и оптимизации запросов',
            'Опыт работы с Git',
            'Умение работать в команде',
        ];

        $responsibilities = [
            'Разработка новых функций',
            'Поддержка существующего кода',
            'Участие в код-ревью',
            'Написание тестов',
            'Документирование кода',
        ];

        $conditions = [
            'Оформление по ТК РФ',
            'Гибкий график работы',
            'Удаленная работа или офис в центре',
            'ДМС со стоматологией',
            'Оплата профессионального обучения',
            'Корпоративные мероприятия',
        ];

        shuffle($requirements);
        shuffle($responsibilities);
        shuffle($conditions);

        return "**Описание вакансии:**\n\n"
            . "Мы ищем {$title} в нашу команду.\n\n"
            . "**Требования:**\n- " . implode("\n- ", array_slice($requirements, 0, 5)) . "\n\n"
            . "**Обязанности:**\n- " . implode("\n- ", array_slice($responsibilities, 0, 4)) . "\n\n"
            . "**Условия:**\n- " . implode("\n- ", array_slice($conditions, 0, 4));
    }
}
