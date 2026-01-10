<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereSalaryFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereSalaryTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vacancy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vacancy extends Model
{
    //
}
