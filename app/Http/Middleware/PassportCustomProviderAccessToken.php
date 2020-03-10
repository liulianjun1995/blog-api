<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class PassportCustomProviderAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $psr = (new PsrHttpFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            $token_id = $psr->getAttribute('oauth_access_token_id');
            if ($token_id) {
                $access_token = DB::table('oauth_access_token_providers')->where('oauth_access_token_id',
                    $token_id)->first();

                if ($access_token) {
                    Config::set('auth.guards.api.provider', $access_token->provider);
                }
            }
        } catch (\Exception $e) {

        }

        return $next($request);
    }
}
