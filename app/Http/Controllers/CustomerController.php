<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        // verify if connected / has session
    	if ($request->session()->has('customer_id')) {
    		// if has session redirect with token to get data
    		$customer = Customer::load($request->get('customer_id'));
        	return response()->json([$customer->toArray()], 200);
    	} else {
    		// if it doesnt have a session show login page
    		$error = $request->session()->has('error') ? ['error' => $request->session()->get('error')] : ['error' => ''];
    		return View('auth.login', $error);
    	}
    }

    public function signIn(Request $request)
    {
    	$request->session()->flash('error', 'Task was successful!');
        $customerId = Customer::getIdByCredentials($request->get('email'), $request->get('password'));
        
        if ($customerId) {
        	dd('authorized customer get him his token and redirect !!!');
        } else {
        	return redirect('login');
        }
        
    }

    public function createCustomer(Request $request)
    {
        $newCustomer = new Customer($request->get('customer'));
        $newCustomer->validateAll();

        if (!$newCustomer->save()) {
            return response()->json(['message' => 'duplicate customer !!!'], 400);
        }

        $newAddress = new Address($request->get('address'));
        $newAddress->setCustomerId($newCustomer->customer_id);
        $newAddress->validateAll();

        if (!$newAddress->save()) {
            return response()->json(['message' => 'duplicate address !!!'], 400);
        }

        $newCustomer->updateAddressId($newAddress->address_id);
        $this->sendMailRegistration($newCustomer->toArray());

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

    public function getInfos(Request $request)
    {
        $customer = Customer::load($request->get('customer_id'));

        return response()->json([$customer->toArray()], 200);
    }

    public function hasSession(Request $request)
    {
        if ($request->session()->get('customer_id') !== null) {
        	
        	return response(1, 200);
        } else {
        	return response(0, 200);
        }
        
    }

    public function getCustomerFromSession(Request $request)
    {
        if ($request->session()->get('customer_id') !== null) {
        	
        	$customer = Customer::load($request->session()->get('customer_id'));

        	return response()->json([$customer->toArray()], 200);
        } else {
        	return response('', 401);
        }
        
    }

    private function sendMailRegistration($receiverInfos)
    {
    	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    	
    	try {
    	    //Recipients
    	    $mail->setFrom('equipe@passeport.shopping', 'passeport shopping');
    	    $mail->addAddress($receiverInfos['email'], $receiverInfos['firstname'] .' ' .$receiverInfos['lastname']);     // Add a recipient
    	    $mail->addReplyTo('equipe@passeport.shopping', 'Information');

    	    //Content
    	    $mail->isHTML(true);                                  // Set email format to HTML
    	    $mail->Subject = 'Here is the subject';
    	    $mail->Body    = 'Successful registration!!  his is the HTML message body <b>in bold!</b>';

    	    $mail->send();
    	    
    	    return true;
    	} catch (Exception $e) {
    	    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    	}
    }
}
