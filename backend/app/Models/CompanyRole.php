<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyRole extends Model
{
    protected $fillable = ['name'];

    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class);
    }
}
