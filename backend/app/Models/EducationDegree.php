<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationDegree whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EducationDegree extends Model
{
    protected $fillable = ['name', 'sort'];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function educations(): HasMany
    {
        return $this->hasMany(ResumeEducation::class);
    }
}
