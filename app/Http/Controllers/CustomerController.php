<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Validator;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        // verify if connected / has session
    	if ($request->session()->has('customer_id')) {
    		// if has session redirect with token to get data
    		$redirect = $this->getRedirectUri($request);
        	return redirect($redirect);

    	} else {
    		// if it doesnt have a session show login page
    		$error = $request->session()->has('error') ? ['error' => $request->session()->get('error')] : ['error' => ''];
    		return View('auth.login', $error);
    	}
    }

    public function signIn(Request $request)
    {
     	$validator = Validator::make($request->all(), ['email' => 'required|email|max:96','password' => 'required|max:32']);
     	
        $customerID = Customer::getIdByCredentials($request->get('email'), $request->get('password'));
    	
        if ($customerID AND !$validator->fails()) {
        	$request->session()->put('customer_id', $customerID);
        	$redirect = $this->getRedirectUri($request);
        	
        	return redirect($redirect);

        } else {

    		$request->session()->flash('error', "Oups… L’adresse courriel ou le mot de passe utilisé est erroné.");
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
        $this->sendMailRegistration($newCustomer->toArray(), $request->get('url_origin'));

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
        if (empty($request->header('Token-ps'))) {
			return response()->json([], 400);	        
        }
        
        $customerID = $this->getCustomerIdFromToken($request->header('Token-ps'));
        
        if ($customerID == false) {
			return response()->json([], 404);	        
        }

        $customer = Customer::load($customerID);
        $address  = Address::load($customer->address_id);
        
        return response()->json(['customer' => $customer->toArray(),'address' => $address->toArray()], 200);
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

    private function getRedirectUri($request)
    {
    	$token = str_random(32);
    	$redirect = $request->session()->get('redirect_uri') . '?token_ps='. $token;
    	
    	$boutique_id = $request->session()->get('user_id');
    	$isInserted = Customer::insertTokenRequest($request->session()->get('customer_id'), $boutique_id, $token);

    	$request->session()->forget('user_id');
    	$request->session()->forget('redirect_uri');
    	
    	return $redirect;
    }

    private function getCustomerIdFromToken($token_ps)
    {
		$customer = DB::table('authorized_keys')->where('token', $token_ps)->first();
    	$res = empty($customer->customer_id) ? false : $customer->customer_id;

    	return $res;
    }

    private function sendMailRegistration($receiverInfos, $url_origin = '')
    {
    	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    	
    	try {
    	    $mail->CharSet = 'UTF-8';
    	    //Recipients
    	    $mail->setFrom('info@passeport.shopping', 'Passeport Shopping');
    	    $mail->addAddress($receiverInfos['email'], $receiverInfos['firstname'] .' ' .$receiverInfos['lastname']);     // Add a recipient
    	    $mail->addReplyTo('info@passeport.shopping', 'Information');

    	    //Content
    	    $mail->isHTML(true); // Set email format to HTML
    	    $mail->Subject = "Votre Passeport Shopping a bien été créé";
    	    $mail->Body    = "Successful registration!!  his is the HTML message body <b>in bold!</b> <a href=\"$url_origin\">$url_origin</a>";

    	    $mail->send();
    	    
    	    return true;
    	} catch (Exception $e) {
    	    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    	}
    }
}
