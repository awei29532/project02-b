<?php

use App\Models\PermissionLevel;
use App\Models\Role;

class BasePermissionSeeder extends BaseSeeder
{
    /**
     * @param string $role
     * @param integer $index
     *
     * @return $this
     */
    public function giveRolePermission(string $role, $index)
    {
        Role::findByName($role, 'user')->givePermissionTo($index);

        return $this;
    }

    /**
     * @param array $data
     * @param array $params
     *
     * @return $this
     */
    public function permissionLevel($data, $params = [])
    {
        PermissionLevel::create($data, $params);

        return $this;
    }
}
