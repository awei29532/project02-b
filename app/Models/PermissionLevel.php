<?php

namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $permission_id
 * @property int $_lft
 * @property int $_rgt
 * @property int $parent_id
 * @property string $updated_at
 * @property string $created_at
 */
class PermissionLevel extends BaseModel
{
    use NodeTrait;

    protected $table = 'permission_level';

    protected $primaryKey = 'permission_id';

    protected $hidden = [
        'created_at',
        'updated_at',
        '_lft',
        '_rgt'
    ];

    protected $fillable = [
        'permission_id',
        'parent_id'
    ];

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id');
    }
}
