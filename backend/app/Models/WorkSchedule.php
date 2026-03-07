<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WorkSchedule extends Model
{
    //
}
