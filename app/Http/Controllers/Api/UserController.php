<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 登陆用户
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = Auth::user();
        return self::success($user->only(['avatar', 'nickname']));
    }

    /**
     * 退出登录
     * @return JsonResponse
     * @throws \Exception
     */
    public function logout()
    {
        Auth::user()->token()->delete();

        return self::success();
    }
}
