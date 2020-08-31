<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnauthorizedException;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateApi
{
    /**
     * 驗證 backend 的登入狀況
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** 檢查 auth */
        try {
            /** JWT 驗證 */
            auth()->payload();

            $user = auth()->user();

            // 取不到 user ，重新登入
            if (is_null($user))
                throw new UnauthorizedException(__('common.unauthorized'));

            // 帳號狀態關閉
            if ($user->status == '0')
                throw new ForbiddenException(__('common.account_disable'));

            // 主帳號狀態關閉
            if ($user->mainAccount && $user->isSubAccount() && $user->mainAccount->mainAccount->status == '0')
                throw new ForbiddenException(__('common.main_account_disable'));

            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
            /* JWT_TTL token 過期，嘗試使用 refresh token 進行換證 */
            try {
                /** refresh token 還沒過期，自動換證 */
                $afterFreshToken = JWTAuth::parseToken()->refresh();
                JWTAuth::setToken($afterFreshToken)->toUser();

                throw new UnauthorizedException(
                    __('common.token_change_success'),
                    [
                        'data' => [
                            'access_token' => 'Bearer ' . $afterFreshToken,
                            'expires_at' => auth('user')->factory()->getTTL() * config('jwt.ttl'),
                        ]
                    ]
                );
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
                /** refresh token 已過期，強制重新登入 */
            } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $exception) {
                /** refresh token 已被註銷，強制重新登入 */
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $exception) {
            /** token 已被加入黑名單 */
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $exception) {
            /** token 無效 */
        } catch (JWTException $exception) { }

        throw new UnauthorizedException(__('common.unauthorized'));
    }
}
