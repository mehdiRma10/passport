<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class BasicAuthMiddleware
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
        if (!empty($request->header('api-token'))) {

            $apiToken     = $request->header('api-token');
            $tokenIsValid = collect(DB::Connection('shoooping')->select("SELECT id FROM shoooping_tokens WHERE md5(SUBSTRING(`keys`, 1, 32)) = :token LIMIT 1", ['token' => $apiToken]))->first();
        }
        if (empty($tokenIsValid) OR !$tokenIsValid) {
            return response('unauthorized', 401);
        }

        $request->attributes->add(['user_id' => $tokenIsValid]);

        return $next($request);
    }
}
