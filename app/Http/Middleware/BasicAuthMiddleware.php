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
        
        $email = $request->header('PHP_AUTH_USER');
        $pass = $request->header('PHP_AUTH_PW');
        $customerID = $this->validate($email, $pass);

        if(!isset($email, $pass) AND $customerID) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('unauthorized', 401, $headers);
        }

        $request->attributes->add(['customer_id' => $customerID]);
        return $next($request);
    }

    private function validate($email, $pass){
        // Query taken from opencart login customer
        $customer = collect(DB::select("SELECT customer_id FROM oc_customer WHERE LOWER(email) = :email AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(:pass))))) OR password = :md5pass) AND status = '1' AND approved = '1'", ['email' => $email, 'pass' => $pass, 'md5pass' => md5($pass)]))->first();
        
        $customerID = empty($customer->customer_id) ? 0 : $customer->customer_id;
        return $customerID;
    }
}
