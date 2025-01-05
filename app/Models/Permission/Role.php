<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Role Model
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string id
 * @property string name
 * @property string guard_name
 */
class Role extends SpatieRole
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name'
    ];
}
