<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $vacancy_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyView whereVacancyId($value)
 * @mixin \Eloquent
 */
class VacancyView extends Model
{
    //
}
