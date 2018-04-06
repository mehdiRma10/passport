<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function createClient(Request $request)
    {   
        $this->validate($request, ['customer.customer_id' => 'required|integer', 
        'customer.customer_group_id' => 'required|integer',  
        'customer.firstname' => 'required|max:32', 
        'customer.lastname' => 'required|max:32', 
        'customer.email' => 'required|email|max:96', 
        'customer.telephone' => 'string|max:32', 
        'customer.fax' => 'nullable', 
        'customer.password' => 'required|string|max:40', 
        'customer.salt' => 'nullable|string', 
        'customer.address_id' => 'required|string|max:40',  
        'customer.status' => 'required|boolean', 
        'customer.approved' => 'required|boolean',  
        'customer.date_added' => 'required|date' 
        ]);
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
