<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Traits\ProxyTrait;
use App\Models\Admin;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ProxyTrait;

    /**
     * 登录
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $admin = Admin::query()->where('username', $username)->first();

        if ($admin === null) {
            return $this->error('管理员不存在');
        }

        if (Hash::check($password, $admin->password) === false) {
            return $this->error('密码错误');
        }

        if ($admin->status !== UserConstants::USER_STATUS_ENABLED) {
            return $this->error('该账户已被禁用');
        }

        $accessToken = $this->authenticate('admins');

        return $this->success($accessToken);
    }

    /**
     * 登录用户详情
     * @param Request $request
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
        $user = $request->user('admin');

        $user->roles = ['admin'];

        return $this->success($user);
    }

    /**
     * 退出登录
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::guard('admin')->user()->token()->delete();

        return $this->success();
    }

    /**
     * 修改头像
     * @param Request $request
     * @return JsonResponse
     */
    public function avatar(Request $request): JsonResponse
    {
        $avatar = $request->post('avatar');

        if (Storage::disk('oss')->has(Str::after($avatar, env('OSS_ENDPOINT')))) {
            $user = Auth::guard('admin')->user();
            $user->avatar = $avatar;
            $user->save();
            return $this->success();
        }

        return $this->error('图片不存在');
    }
}
