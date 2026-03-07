<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeEducation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeEducation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeEducation query()
 * @mixin \Eloquent
 */
class ResumeEducation extends Model
{
    protected $fillable = [
        'resume_id',
        'institution',
        'faculty',
        'specialty',
        'qualification',
        'degree_id',
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

    public function degree(): BelongsTo
    {
        return $this->belongsTo(EducationDegree::class);
    }
}
