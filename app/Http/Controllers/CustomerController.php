<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function createCustomer(Request $request)
    {   
        $this->validateCustomer($request);
        $this->validateAddress($request);
        
        return response()->json(['message' => 'yeaay']);
    }

    public function customerExists(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        dd($request->user());
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

    private function validateCustomer(Request $request)
    {
    	$this->validate($request, ['customer.customer_id' => 'required|integer', 
        'customer.customer_group_id' => 'required|integer',  
        'customer.firstname' => 'required|max:32', 
        'customer.lastname' => 'required|max:32', 
        'customer.email' => 'required|email|max:96', 
        'customer.telephone' => 'required|string|max:32', 
        'customer.fax' => 'nullable|string|max:32', 
        'customer.password' => 'required|string|max:40', 
        'customer.salt' => 'nullable|string', 
        'customer.address_id' => 'required|integer',
        'customer.ip' => "nullable|ip",  
        'customer.status' => 'required|boolean', 
        'customer.approved' => 'required|boolean',  
        'customer.date_added' => 'required|date' 
        ]);
    }

    private function validateAddress(Request $request)
    {
    	$this->validate($request, ['address.address_id' => 'required|integer',  
        'address.customer_id' => 'required|integer',
        'address.firstname' => 'required|max:32', 
        'address.lastname' => 'required|max:32', 
        'address.company' => 'nullable|string', 
        'address.address_1' => 'required|string|max:128', 
        'address.address_2' => 'nullable|string|max:128', 
        'address.city' => 'required|string|max:128', 
        'address.postcode' => 'required|string|max:10', 
        'address.country_id' => "required|integer",  
        'address.zone_id' => 'required|integer', 
        'address.custom_field' => 'nullable|string|max:256'
        ]);
    }
}
