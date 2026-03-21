<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $company_id
 * @property int $creator_id
 * @property string $title
 * @property string $description
 * @property int|null $salary_from
 * @property int|null $salary_to
 * @property string $experience
 * @property string $status
 * @property string $city
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'creator_id',
        'title',
        'description',
        'salary_from',
        'salary_to',
        'experience',
        'city',
        'status',
    ];

    public function views()
    {
        return $this->hasMany(VacancyView::class);
    }

    public function getViewsCountAttribute()
    {
        return $this->views()->count();
    }

    public function isViewedByUser(?User $user)
    {
        if (!$user) return false;

        return $this->views()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function addView(?User $user)
    {
        if (!$user) {
            return;
        }

        if (!$this->isViewedByUser($user)) {
            $this->views()->create([
                'user_id' => $user->id
            ]);
        }
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function responses()
    {
        return $this->hasMany(VacancyResponse::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function workSchedules()
    {
        return $this->belongsToMany(WorkSchedule::class, 'vacancy_work_schedules');
    }

    public function employmentTypes()
    {
        return $this->belongsToMany(EmploymentType::class, 'vacancy_employment_types');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
