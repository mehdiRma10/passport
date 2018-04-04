<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function createClient(Request $request)
    {
    	$this->validate($request, ['customer_id' => 'required|email', 
    	'customer_group_id' => 'required|email',  
    	'firstname' => 'required|email', 
    	'lastname' => 'required|email', 
    	'email' => 'required|email', 
    	'telephone' => 'required|email', 
    	'fax' => 'required|email', 
    	'password' => 'required|email', 
    	'salt' => 'required|email', 
    	'address_id' => 'required|email',  
    	'status' => 'required|email', 
    	'approved' => 'required|email',  
    	'date_added' => 'required|email' 
    	]);
        dd($request->user());
        return response()->json();
    }

    public function clientExists(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $idClient = DB::table('oc_customer')
            ->select(['customer_id'])
            ->where('email', $request->get('email'))
            ->first();

        if (!empty($idClient)) {
            $response = response(1, 200);
        } else {
            $response = response(0, 404);
        }

        return $response;
    }

}
