<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as Permissions;

/**
 * @property int $id
 * @property string $guard_name
 * @property string $updated_at
 * @property string $created_at
 */
class Permission extends Permissions
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'guard_name',
    ];

    protected $fillable = [
        'guard_name',
        'type',
        'name'
    ];

    public function PermissionLevel()
    {
        return $this->belongsTo(PermissionLevel::class);
    }

    public static function create(array $attributes = [])
    {
        return static::query()->create($attributes);
    }
}
