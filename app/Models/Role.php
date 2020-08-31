<?php

namespace App\Models;

use Spatie\Permission\Models\Role as Roles;

/**
 * @property int $id
 * @property string $guard_name
 * @property string $updated_at
 * @property string $created_at
 */
class Role extends Roles
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'guard_name',
        'name'
    ];
}
