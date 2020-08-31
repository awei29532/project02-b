<?php

namespace App\Http\Middleware;

use App\CustomTrait\UserCheckMiddlewareTrait;
use Closure;

class ExceptCustomerService
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
        $this->exceptCustomerServiceWithOutMiddleWare();

        return $next($request);
    }
}
