<?php

namespace App\CustomTrait;

use App\Exceptions\ForbiddenException;
use App\Models\Role;
use Closure;
use Exception;

trait UserCheckMiddlewareTrait
{
    /**
     * get role name
     *
     * @param array|integer|string $ids
     * @param string $column
     * @param string $pluckColumn
     *
     * @return array
     */
    protected function getRoleName($ids, string $column = 'id', string $pluckColumn = 'name')
    {
        if (is_string($ids) || is_numeric($ids)) {
            $role = Role::where($column, $ids);
        } elseif (is_array($ids)) {
            $role = Role::whereIn($column, $ids);
        }

        return $role->get()->pluck($pluckColumn)->toArray();
    }

    /**
     * only role
     *
     * @param array|string $roles
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function onlyRole($roles, array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->onlyRoleWithOutMiddleWare($roles), $options);
    }

    /**
     * only role without middle
     *
     * @param array|string $roles
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function onlyRoleWithOutMiddleWare($roles)
    {
        $userRole = auth()->user()->getRoleNames()->pop();

        if (is_array($roles) && !in_array($userRole, $roles) || is_string($roles) && $userRole != $roles)
            throw new ForbiddenException();

        return $this;
    }

    /**
     * except role
     *
     * @param array|string $role
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptRole($role, array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->exceptRoleWithOutMiddleWare($role), $options);
    }

    /**
     * except role without middle
     *
     * @param array|string $role
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptRoleWithOutMiddleWare($role)
    {
        $userRole = auth()->user()->getRoleNames()->pop();

        if (is_array($role) && in_array($userRole, $role) || is_string($role) && $userRole == $role)
            throw new ForbiddenException();

        return $this;
    }

    /**
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptSubAccount(array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->exceptSubAccountWithOutMiddleWare(), $options);
    }

    /**
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptSubAccountWithOutMiddleWare()
    {
        return $this->subAccountExcept();
    }

    /**
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptCustomerService(array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->exceptCustomerServiceWithOutMiddleWare(), $options);
    }

    /**
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function exceptCustomerServiceWithOutMiddleWare()
    {
        return $this->exceptRoleWithOutMiddleWare(Role::find(7)->name);
    }

    /**
     * @param integer|string $subAccountId subAccount ID
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function mainSubRelate($subAccountId, array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->mainSubRelateWithOutMiddleWare($subAccountId), $options);
    }

    /**
     * @param integer|string $subAccountId subAccount ID
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function mainSubRelateWithOutMiddleWare($subAccountId)
    {
        return $this->relateMainSub(compact('subAccountId'));
    }

    /**
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function onlyAdmin(array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->onlyAdminWithOutMiddleWare(), $options);
    }

    /**
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function onlyAdminWithOutMiddleWare()
    {
        return $this->onlyRoleWithOutMiddleWare(Role::find(1)->name);
    }

    /**
     * @param array $params
     *
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     */
    private function relateMainSub(array $params)
    {
        if ($this->subAccountCheck($params['subAccountId']))
            throw new ForbiddenException();

        return $this;
    }

    /**
     * @param string|integer $subAccountId
     *
     * @return boolean
     */
    protected function subAccountCheck($subAccountId)
    {
        return auth()->user()->subAccount->where('user_id', $subAccountId)->isEmpty();
    }

    /**
     * @param array $params
     *
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     */
    private function subAccountExcept()
    {
        if (auth()->user()->mainAccount)
            throw new ForbiddenException();

        return $this;
    }

    /**
     * @param Closure $callBack
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    private function permissionMiddleWare(Closure $callBack, ?array $options = [])
    {
        return $this->middleware(
            fn ($request, $next) => $this->permissionMiddleWareCallBack($callBack, $request, $next),
            $options
        );
    }

    /**
     * @param Closure $callBack
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     *
     * @return Closure
     */
    protected function permissionMiddleWareCallBack(Closure $callBack, $request, Closure $next)
    {
        $callBack();

        return $next($request);
    }

    /**
     * @param array|integer|string $permissionIds permission ids
     * @param array $options see reference
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function userCheckPermission($permissionIds, array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->userPermission($permissionIds), $options);
    }

    /**
     * @param array|integer|string $ids permission ids
     *
     * @return $this
     *
     * @throws App\Exceptions\ForbiddenException|Exception
     */
    private function userPermission($permissionIds)
    {
        $user = auth()->user();

        if ($user->getDirectPermissions()->isEmpty() && $user->getPermissionsViaRoles()->whereIn('id', $permissionIds)->isEmpty())
            throw new ForbiddenException();

        if (!$user->getDirectPermissions()->whereIn('id', $permissionIds)->isEmpty())
            throw new ForbiddenException();

        return $this;
    }

    /**
     * user can do
     *
     * @param array|string $permission
     * @param array $options
     *
     * @return Illuminate\Routing\ControllerMiddlewareOptions
     *
     * @reference [office](https://laravel.com/docs/5.0/controllers#controller-middleware)
     */
    protected function can($permission, array $options = [])
    {
        return $this->permissionMiddleWare(fn () => $this->canDo($permission), $options);
    }

    /**
     * @param array|string $permission
     *
     * @return $this
     */
    protected function canDo($permission)
    {
        if (!auth()->user()->can($permission))
            throw new ForbiddenException();

        return $this;
    }
}
