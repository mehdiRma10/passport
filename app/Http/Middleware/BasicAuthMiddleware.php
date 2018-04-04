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
    
    public function handle($request, Closure $next) {
        
        $shCenter = DB::table('oc_setting')->where('code', $request->__get('pass'))->first();
        dd($request);
        if(!isset($shCenter)) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('unauthorized', 401, $headers);
        }

        return $next($request);
    }
}
