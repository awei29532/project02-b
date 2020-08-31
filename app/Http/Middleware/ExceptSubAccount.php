<?php

namespace App\Http\Middleware;

use App\CustomTrait\UserCheckMiddlewareTrait;
use Closure;

class ExceptSubAccount
{
    use UserCheckMiddlewareTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->exceptSubAccountWithOutMiddleWare();

        return $next($request);
    }
}
