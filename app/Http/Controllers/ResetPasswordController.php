<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ResetPasswordController extends Controller
{
    // reset pw page
    public function page(Request $request, $token)
    {
        $validator = Validator::make(['token' => $token], ['token' => 'required|size:45']);

        $client = DB::table('reset_pass')
            ->select(['email'])
            ->where('reset_token', $token)
            ->first();

        if (isset($client->email) and !$validator->fails()) {
            $request->session()->put('email_reset', $client->email);
            $request->session()->save();
            $error = ['error_1' => '', 'error_2' => ''];
            
            return View('auth.passwords.reset', $error);
        } else {

        }
    }

    public function resetPass(Request $request)
    {

        if ($request->session()->get('email_reset') === null) {
            dd('not found page');
        }

        $validator        = Validator::make($request->all(), ['password_1' => 'required|between:4,20', 'password_2' => 'required|between:4,20']);
        $validationFailed = $validator->fails();
        $pwDiffrent       = ($request->get('password_1') !== $request->get('password_2'));

        if ($validationFailed or $pwDiffrent) {

            $error_1 = $validationFailed ? ['error_1' => "Le mot de passe doit contenir entre 4 et 20 caractères!"] : ['error_1' => ''];
            $error_2 = $pwDiffrent ? ['error_2' => "La confirmation du mot de passe est différente du mot de passe!"] : ['error_2' => ''];
            $error = array_merge($error_1, $error_2);
            return View('auth.passwords.reset', $error);
        }

        $this->dbPwRest($request->session()->get('email_reset'), $request->get('password_1'));
        $message = ['message' => 'Votre mot de passe a été modifié avec succès'];
        return View('auth.passwords.success', $message);
    }

    /*
    TODO
        add this function to customer object
     */
    private function dbPwRest($email, $pw){
        
        try{
            DB::table('oc_customer')
                ->where('email', $email)
                ->update(['password' => md5($pw)]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }


}
