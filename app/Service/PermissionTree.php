<?php

namespace App\Service;

use App\Models\PermissionLevel;
use Illuminate\Support\Collection;

class PermissionTree
{
    protected $tree;

    /**
     * @param Illuminate\Support\Collection $permissionLevel
     *
     * @return $this
     */
    public function setPermissionNameToTree(Collection $permissionLevel = null)
    {
        $permissionLevel = $permissionLevel ?? PermissionLevel::get();

        $tree = $this->setPermissionName($permissionLevel)->toArray();

        return $this->setTree(collect($tree));
    }

    /**
     * @param Illuminate\Support\Collection $permissionLevel
     *
     * @return Kalnoy\Nestedset\Collection
     */
    public function setPermissionName(Collection $permissionLevel)
    {
        return $permissionLevel->makeHidden('parent_id')->map(function ($item) {
            $item->name = $item->permission->name;

            return $item->makeHidden('permission');
        })->toTree();
    }

    /**
     * @return Illuminate\Support\Collection $tree
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @param Illuminate\Support\Collection $tree
     *
     * @return $this
     */
    public function setTree($tree)
    {
        $this->tree = $tree;

        return $this;
    }

    /**
     * @param Illuminate\Support\Collection  $roleOrUser
     *
     * @return Collection
     */
    public function permissionToTree($roleOrUser)
    {
        return $this->dataHandle($this->getTree(), $roleOrUser);
    }

    /**
     * @param Illuminate\Support\Collection $tree
     * @param Collection  $roleOrUser
     *
     * @return Collection
     */
    protected function dataHandle($tree, $roleOrUser)
    {
        return $tree->map(function ($item) use ($roleOrUser) {
            // 確保每次 item 都是陣列
            if (!is_array($item))
                $item = $item->toArray();

            $item = collect($item);
            // 角色權限
            if (gettype($roleOrUser->search($item->get('permission_id'))) !== 'boolean') {
                $item->put('status', 1);
            } else {
                $item->put('status', 0);
            }
            $item->put('children', $this->dataHandle(collect($item->get('children')), $roleOrUser));

            return $item;
        });
    }
}
