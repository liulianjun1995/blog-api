<?php

namespace App\Http\Controllers;

use App\Http\Traits\ProxyTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthController extends Controller
{
    /**
     * oauth 授权跳转
     * @param $driver
     * @return RedirectResponse
     */
    public function authorized($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * github
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function githubLogin(Request $request)
    {
        try {
            $oauth = Socialite::driver('github')->user();

            $params = [
                'app_id'    => $oauth->id,
                'nickname'  => $oauth->nickname,
                'email'     => $oauth->email,
                'avatar'    => $oauth->avatar,
            ];

            $user = User::query()->where([
                'app_id' => $oauth->id,
                'provider' => 'github'
            ])->first();

            if ($user === null) {
                $params['password'] = Str::random(10);
                $params['provider'] = 'github';
                $user = User::query()->create($params);
            } else {
                $user->fill($params)->save();
            }

            $token = 'Bearer ' . $user->createToken('Blog', ['blog-web'])->accessToken;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            abort(500);
        }
//        event(new UserLoginEvent($result, $request));
        return redirect(env('APP_WEB_URL', env('APP_URL')) .'?access_token=' . $token);
    }

    /**
     * qq
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function qqLogin(Request $request)
    {
        try {
            $oauth = Socialite::driver('qq')->user();

            $params = [
                'app_id'    => $oauth->id,
                'nickname'  => $oauth->nickname,
                'email'     => $oauth->email,
                'avatar'    => $oauth->avatar
            ];

            $user = User::query()->where([
                'app_id' => $oauth->id,
                'provider' => 'qq'
            ])->first();

            if ($user === null) {
                $params['password'] = Str::random(10);
                $params['provider'] = 'qq';
                $user = User::query()->create($params);
            } else {
                $user->fill($params)->save();
            }

            $token = 'Bearer ' . $user->createToken('Blog', ['blog-web'])->accessToken;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            abort(500);
        }

        if (env('APP_ENV') === 'local') {
            return redirect(env('APP_WEB_URL', 'http://localhost:9528') .'?token=' . $token);
        }

        return redirect(env('APP_URL') .'?token=' . $token);
    }
}
