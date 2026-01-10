<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $resume_id
 * @property string $title
 * @property string $experience_summary
 * @property string $start_date
 * @property string|null $end_date
 * @property bool $is_current
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereExperienceSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereResumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeWork whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResumeWork extends Model
{
    protected $fillable = [
        'resume_id',
        'title',
        'experience_summary',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
