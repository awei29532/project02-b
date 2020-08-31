<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        $menu = config('menu');

        $permission = auth()->user()->getAllPermissions()->pluck('name')->toArray();

        return $this->returnData(
            collect($menu)->map(fn ($item) => $this->checkPermissionMenu($item, $permission))
                ->filter(fn ($item) => $this->takeOffEmpty($item))
                ->values()
        );
    }

    /**
     * @param array $item
     * @param array $permission
     *
     * @return array
     */
    private function checkPermissionMenu(array $item, array $permission)
    {
        if (isset($item['subList'])) {
            $item['subList'] = $this->arrayIntersectKey($item['subList'], $permission);

            return $item;
        } elseif (isset($item['tabList'])) {
            $item['tabList'] = $this->arrayIntersectKey($item['tabList'], $permission);

            return $item;
        } elseif (!isset($item['subList']) && !isset($item['tabList']) && preg_grep(sprintf("/%s/i", $item['mainList']), $permission)) {
            return $item;
        }
    }

    /**
     * @param array $listAry
     * @param array $permissionAry
     *
     * @return array
     */
    private function arrayIntersectKey(array $listAry, array $permissionAry)
    {
        return array_intersect_key($listAry, array_flip($permissionAry));
    }

    /**
     * @param array $item
     *
     * @return mixed
     */
    private function takeOffEmpty($item)
    {
        if (isset($item['subList']) && empty($item['subList']) || isset($item['tabList']) && empty($item['tabList']))
            return;

        return $item;
    }
}
