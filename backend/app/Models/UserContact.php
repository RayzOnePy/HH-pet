<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserContact whereValue($value)
 * @mixin \Eloquent
 */
class UserContact extends Model
{
    //
}
