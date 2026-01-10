<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $resume_id
 * @property string $skill
 * @property string $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereResumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResumeSkill whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResumeSkill extends Model
{
    protected $fillable = [
        'resume_id',
        'skill',
        'level',
    ];

    protected $casts = [
        'level' => 'string',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
