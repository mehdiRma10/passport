<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            
            $token = (!empty($request->get('api_token'))) ? $request->get('api_token') : $request->header('api-token');
            $redirect_uri = urldecode($request->get('redirect_uri'));

            $tokenIsValid = collect(DB::Connection('shoooping')->select("SELECT id FROM shoooping_tokens WHERE md5(SUBSTRING(`keys`, 1, 32)) = :token LIMIT 1",['token' => $token]))->first();

            if ((!empty($token) AND isset($tokenIsValid)) OR $request->session()->has('user_id')) {
                
                if ($request->session()->has('user_id') AND $request->session()->has('redirect_uri')) {

                    return new User($request->session()->get('user_id'));
                
                }else {
                    
                    $request->session()->put('user_id', $tokenIsValid->id);
                    $request->session()->put('redirect_uri', $redirect_uri);
                    $request->session()->save();
                    
                    return new User($tokenIsValid->id);
                }

            }

            return null;
        });
    }
}
