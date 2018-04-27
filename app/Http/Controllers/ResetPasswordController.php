<?php

namespace App\Http\Controllers;

use App\Address;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Validator;

class ResetPasswordController  extends Controller
{
    // reset pw page
    public function Page(Request $request, $token)
    {
        $validator = Validator::make(['token' => $token], ['token' => 'required|size:45']);
        
        $client = DB::table('reset_pass')
            ->select(['email'])
            ->where('reset_token', $token)
            ->first();

        if (isset($client->email) AND !$validator->fails() ) {
            $request->session()->put('email_reset', $client->email);
            
            return View('auth.passwords.reset');
        
        } else {

        }
    }

    public function resetPassPage(Request $request)
    {
        $validator = Validator::make(['token' => $token], ['token' => 'required|size:45']);
        
        $client = DB::table('reset_pass')
            ->select(['email'])
            ->where('reset_token', $token)
            ->first();

        if (isset($client->email) AND !$validator->fails() ) {
            $request->session()->put('email_reset', $client->email);
            
            return View('auth.passwords.reset');
        
        } else {

        }
    }
}
