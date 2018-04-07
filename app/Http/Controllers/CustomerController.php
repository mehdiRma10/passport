<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function createCustomer(Request $request)
    {
        $newCustomer = new Customer($request->get('customer'));
        $newCustomer->validateAll();

        if (!$newCustomer->add()) {
            return response()->json(['message' => 'duplicate customer !!!'], 400);
        }

        $newAddress = new Address($request->get('address'));
        $newAddress->setCustomerId($newCustomer->customer_id);
        $newAddress->validateAll();

        if (!$newAddress->add()) {
            return response()->json(['message' => 'duplicate address !!!'], 400);
        }

        $newCustomer->updateAddressId($newAddress->address_id);

        return response()->json(['message' => 'good'], 201);
    }

    public function customerExists(Request $request)
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
