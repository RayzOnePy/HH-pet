<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUser query()
 * @mixin \Eloquent
 */
class CompanyUser extends Pivot
{
    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'company_role_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(CompanyRole::class, 'company_role_id');
    }
}
