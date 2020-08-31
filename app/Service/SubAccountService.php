<?php

namespace App\Service;

class SubAccountService
{
    /** @var \App\CustomTrait\RoleOrUserHasPermissionTrait */
    use \App\CustomTrait\RoleOrUserHasPermissionTrait;

    /**
     * 取得建立時的權限樹狀圖
     *
     * @return Illuminate\Support\Collection
     */
    public function getCreatePermissionTree()
    {
        $roleOrUser = $this->getReadOnlyPermissionIds();

        return $this->getCreatePermission($roleOrUser);
    }

    /**
     * 取得更新時的權限樹狀圖
     *
     * @param int id
     *
     * @return Illuminate\Support\Collection
     */
    public function getUpdatePermissionTree($id, $model = \App\Models\User::class)
    {
        $roleOrUser = $this->getReadOnlyPermissionIds();

        return $this->getUpdatePermission($id, $roleOrUser, $model);
    }

    /**
     * 排除寫入的 IDS
     *
     * @param array|integer $permissionIds
     *
     * @return Illuminate\Support\Collection
     */
    public function takeOffWriteWithGiveIds($permissionIds)
    {
        return collect($permissionIds)->diff($this->getWriteOnlyPermissionIds());
    }

    /**
     * 排除讀取的 IDS
     *
     * @param array|integer $permissionIds
     *
     * @return Illuminate\Support\Collection
     */
    public function takeOffReadWithGiveIds($permissionIds)
    {
        return collect($permissionIds)->diff($this->getReadOnlyPermissionIds());
    }


    /**
     * 排除讀取以外的權限
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getReadOnly()
    {
        return $this->getRoleOrUserPermission()->filter(function ($item) {
            if ($item->type == 1)
                return $item;
        })->values();
    }

    /**
     * 排除寫入以外的權限
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getWriteOnly()
    {
        return $this->getRoleOrUserPermission()->filter(function ($item) {
            if ($item->type == 2)
                return $item;
        })->values();
    }


    /**
     * 取得讀取權限 IDS
     *
     * @return Illuminate\Support\Collection
     */
    public function getReadOnlyPermissionIds()
    {
        return $this->getReadOnly()->pluck('id');
    }

    /**
     * 取得寫入權限 IDS
     *
     * @return Illuminate\Support\Collection
     */
    public function getWriteOnlyPermissionIds()
    {
        return $this->getWriteOnly()->pluck('id');
    }
}
