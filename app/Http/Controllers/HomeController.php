<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\Account\LoginRequest;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if (!$token) {
            throw new UnauthorizedException(__('common.unauthorized_user_pass'));
        }

        /** @var User */
        $user = auth()->user();
        if (!$user->status || ($user->isSubAccount() && !$user->status)) {
            throw new ForbiddenException(__('common.upper_status_error'));
        }

        $log = new UserLoginLog();
        $log->user_id = auth()->user()->id;
        $log->ip = $request->ip();
        $log->saveOrFail();

        return $this->returnData([
            'user' => [
                'identity' => $user->identity,
                'username' => $user->username,
                'nickname' => $user->nickname,
                'image' => $user->image ? Storage::url($user->image) : null,
            ],
            'token' => 'Bearer '.$token,
        ], 200, [
            'content-type' => 'application/json',
            'token' => 'Bearer ' . $token,
        ]);
    }

    public function logout()
    {
        auth()->logout();
    }
}
