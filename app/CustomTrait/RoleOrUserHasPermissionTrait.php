<?php

namespace App\CustomTrait;

use App\Exceptions\ValidateException;
use App\Models\PermissionLevel;
use App\Models\Role;
use App\Models\User;
use App\Service\PermissionTree;

trait RoleOrUserHasPermissionTrait
{
    /**
     * 取得權限樹狀圖
     *
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function getPermissionToTree($id = null, $model = User::class)
    {
        $permissionTree = app(PermissionTree::class);

        $roleOrUser = $this->getPermissionIds($id, $model);

        // 主帳號權限樹狀圖
        $permissionTree->setPermissionNameToTree(PermissionLevel::findOrFail($roleOrUser));

        // 子帳號權限
        $permissionIds = $model::findOrFail($id)->permissions->pluck('id');

        return $permissionTree->permissionToTree($permissionIds);
    }

    /**
     * 取得 Role or User 權限
     *
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function getRoleOrUserPermission($id = null, $model = User::class)
    {
        if ($id) {
            return $this->modelFindPermission($id, $model);
        }

        $user = auth()->user();
        $role = $user->roles->pluck('name')->pop();

        switch ($role) {
            case 'admin':
                return $user->getAllPermissions();
                break;
            case preg_match('/_sub/i', $role):
                return $user->getPermissionsViaRoles();
                break;
            default:
                return $user->getDirectPermissions();
                break;
        }
    }

    /**
     * 取得 Role or User 權限
     *
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function modelFindPermission($id, $model = User::class)
    {
        if (!isset($model::find($id)->permissions))
            throw new ValidateException('Role not exists');

        return $model::find($id)->permissions;
    }

    /**
     * 取得 Role or User 權限
     *
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function getPermissionIds($id = null, $model = User::class)
    {
        return $this->getRoleOrUserPermission($id, $model)->pluck('id');
    }

    /**
     * 取得 Role or User
     *
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return App\Models\Role|App\Models\User
     */
    public function getRoleOrUser($id = null, $model = User::class)
    {
        if ($id)
            return $model::find($id);

        $user = auth()->user();

        if ($user->Permissions->isEmpty()) {
            $role = $user->roles->pluck('name')->pop();
            return Role::findByName($role);
        }

        return $user;
    }

    /**
     * 取出建立時樹狀圖
     *
     * @param Illuminate\Support\Collection $roleOrUser
     *
     * @return Kalnoy\Nestedset\Collection
     */
    public function getCreatePermission($roleOrUser)
    {
        return $this->getPermissionTree($roleOrUser);
    }

    /**
     * 賦予權限 diff user 權限
     *
     * @param array|integer $permissionIds
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function takeOffWithGiveIds($permissionIds, $id = null, $model = User::class)
    {
        return collect($permissionIds)->diff($this->getPermissionIds($id, $model));
    }

    /**
     * user 權限 diff 賦予權限
     *
     * @param array|integer $permissionIds
     * @param integer $id
     * @param App\Models\Role|App\Models\User $model
     *
     * @return Illuminate\Support\Collection
     */
    public function takeOffWithOriginIds($permissionIds, $id = null, $model = User::class)
    {
        return $this->getPermissionIds($id, $model)->diff($permissionIds);
    }
}
