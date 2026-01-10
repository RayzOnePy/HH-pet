<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $vacancy_id
 * @property int $candidate_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VacancyResponse whereVacancyId($value)
 * @mixin \Eloquent
 */
class VacancyResponse extends Model
{
    //
}
