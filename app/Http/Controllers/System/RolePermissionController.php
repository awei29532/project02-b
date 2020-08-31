<?php

namespace App\Http\Controllers\System;

use App\CustomTrait\RoleOrUserHasPermissionTrait;
use App\Exceptions\ValidateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\RolePermission\RolePermissionUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RolePermissionController extends Controller
{
    use RoleOrUserHasPermissionTrait;

    protected $logChannel = 'systemsettinglog';

    public function __construct()
    {
        $this->exceptSubAccount();
        $this->onlyRole($this->getRoleName([1, 3]));
        $this->can([
            'system_permissions',
            'system_permissions_add',
            'system_permissions_see'
        ]);
    }

    public function index()
    {
        $list = Role::all()->filter(fn ($item) => $this->roleListFilterCallBack($item))->values();

        return $this->returnData($list);
    }

    /**
     * @param App\Models\Role $role
     *
     * @return App\Models\Role
     */
    protected function roleListFilterCallBack(Role $role)
    {
        if (auth()->user()->getRoleNames()->pop() != 'admin' && preg_match('/admin/i', $role->name))
            return;

        return $role->makeHidden('guard_name');
    }

    public function show($id)
    {
        return $this->returnData($this->getPermissionToTree($id, Role::class));
    }

    public function store(Request $request)
    {
        $roleName = $request->name;

        $permissionIds = $this->permissionIds($request->permissionids);

        $permission = $this->permission(Permission::whereIn('id', $permissionIds)->get(), $permissionIds);

        try{
            Role::create(['name' => $roleName]);
        } catch (RoleAlreadyExists $e) {
            throw new ValidateException(__('validation.exists'));
        }

        collect($permission)->map(fn ($item) => $this->giveRolePermission($roleName, $item));
    }

    /**
     * @param string|integer|array $permissionIds
     *
     * @return array
     */
    protected function permissionIds($permissionIds)
    {
        if (is_string($permissionIds) && preg_match('/,/i', $permissionIds)) {
            $permissionIds = explode(',', $permissionIds);
        } elseif (is_array($permissionIds) && count($permissionIds) == 1 && preg_match('/,/i', $permissionIds[0])) {
            $permissionIds = explode(',', $permissionIds[0]);
        } elseif (is_numeric($permissionIds)) {
            $permissionIds = [$permissionIds];
        }

        return $permissionIds;
    }

    /**
     * @param Illuminate\Database\Eloquent\Collection $permission
     * @param array $permissionIds
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function permission(\Illuminate\Database\Eloquent\Collection $permission, array $permissionIds)
    {
        if ($permission->isEmpty())
            throw new ValidateException(__('validation.exists'));

        $ids = $permission->pluck('id')->toArray();

        if (array_diff($permissionIds, $ids))
            throw new ValidateException(__('validation.exists'));

        return $permission;
    }

    /**
     * @param string $roleName
     * @param string $permission
     *
     * @return $this
     */
    protected function giveRolePermission(string $roleName, string $permission)
    {
        Role::findByName($roleName, 'user')->givePermissionTo($permission);

        return $this;
    }

    /**
     * showdoc
     * @catalog 系統管理/權限設置
     * @title 修改角色權限
     * @description 修改角色權限
     * @method PUT/PATCH
     * @url /system/setting/permission/role/{id}
     * @param id true int 角色ID
     * @json_param {"permissionids": "1,2,3"}
     * @param permissionids true string permission_ids
     */
    public function update(RolePermissionUpdateRequest $request, $id)
    {
        $permissionIds = $request->input('permissionids');

        if (preg_match('/,/i', $permissionIds))
            $ids = array_map('intval', explode(',', $permissionIds));
        else
            $ids = (int) $permissionIds;

        $diffIds = $this->takeOffWithGiveIds($ids, $id, Role::class)->values();

        $role = $this->getRoleOrUser($id, Role::class);
        $role->revokePermissionTo($this->takeOffWithOriginIds($ids, $id, Role::class));
        $role->givePermissionTo($diffIds);
    }
}
