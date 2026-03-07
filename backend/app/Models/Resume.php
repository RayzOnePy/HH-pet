<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int|null $salary
 * @property bool $can_business_trip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResumeSkill> $skills
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResumeWork> $works
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResumeEducation> $educations
 * @method static \Database\Factories\ResumeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereCanBusinessTrip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereUserId($value)
 * @property-read int|null $educations_count
 * @property-read int|null $skills_count
 * @property-read int|null $works_count
 * @mixin \Eloquent
 */
class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'salary',
        'can_business_trip',
    ];

    protected $casts = [
        'salary' => 'integer',
        'can_business_trip' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(ResumeSkill::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(ResumeWork::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(ResumeEducation::class);
    }
}
