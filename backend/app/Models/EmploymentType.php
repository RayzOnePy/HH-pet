<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmploymentType extends Model
{
    protected $fillable = ['name'];

    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'vacancy_employment_types');
    }
}
