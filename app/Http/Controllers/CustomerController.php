<?php

namespace App\Http\Controllers;

use App\Address;
use App\Customer;
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
        $validator = Validator::make($request->all(), ['email' => 'required|email|max:96', 'password' => 'required|max:32']);

        $customerID = Customer::getIdByCredentials($request->get('email'), $request->get('password'));

        if ($customerID and !$validator->fails()) {
            $request->session()->put('customer_id', $customerID);
            $redirect = $this->getRedirectUri($request);

            return redirect($redirect);

        } else {

            $request->session()->flash('error', " *Oups… L’adresse courriel ou le mot de passe utilisé est erroné");
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
        $this->sendMailRegistration($newCustomer->toArray(), $request->get('shop_origin'), $request->get('center_origin'));

        return response()->json(['message' => 'good'], 201);
    }

/*
TODO
finish send email with link to reset pass
 */

    public function sendRestLink(Request $request)
    {
        $validator = Validator::make($request->all(), ['email_reset' => 'required|email|max:96']);

        $clientExists = DB::table('oc_customer')
            ->select(['customer_id'])
            ->where('email', $request->get('email_reset'))
            ->exists();

        if (!$clientExists or $validator->fails()) {
            $request->session()->flash('error', " *Oh… L’adresse courriel est erronée");
        } else {
            $this->sendResetLink($request->get('email_reset'));
            $request->session()->flash('error', "un email a été envoyé a votre adresse");
        }

        return redirect('login');

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

        return response()->json(['customer' => $customer->toArray(), 'address' => $address->toArray()], 200);
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
        $token    = str_random(32);
        $redirect = $request->session()->get('redirect_uri') . '?token_ps=' . $token;

        $boutique_id = $request->session()->get('user_id');
        $isInserted  = Customer::insertTokenRequest($request->session()->get('customer_id'), $boutique_id, $token);

        $request->session()->forget('user_id');
        $request->session()->forget('redirect_uri');

        return $redirect;
    }

    private function getCustomerIdFromToken($token_ps)
    {
        $customer = DB::table('authorized_keys')->where('token', $token_ps)->first();
        $res      = empty($customer->customer_id) ? false : $customer->customer_id;

        return $res;
    }

    private function sendResetLink($email)
    {
        $resetToken = $this->generateToken($email);
        $link = "https://www.passeport.shopping/reset_pass/$resetToken";
        $mail = new PHPMailer(true); // Passing `true` enables exceptions

        try {
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom('info@passeport.shopping', 'Passeport Shopping');
            $mail->addAddress($email); // Add a recipient
            $mail->addReplyTo('info@passeport.shopping', 'Information');
            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = "Réinitialisation de votre mot de passe – Passeport Shopping";
            $mail->Body    = "<p>&nbsp;</p>
                            <p><strong><img style=\"margin: 50px 100px 50px 100px;\" src=\"https://passeport.shopping/images/courriel.png\" alt=\"courriel passeport shopping\" width=\"410\" height=\"252\" /></strong></p>
                            <p>Bonjour,</p><br>
                            <p>Vous avez demandé de réinitialiser le mot de passe de votre Passeport Shopping.
                            Pour réinitialiser votre mot de passe, cliquez <span style=\"font-weight: 400;\"><a href=\"$link\">ici</a></span></a>&nbsp;<span style=\"font-weight: 400;\">. </span></p>
                            <p><span style=\"font-weight: 400;\">S’il vous n’avez pas demandé la réinitialisation de votre mot de passe, veuillez simplement ignorer ce courriel.</span></p>
                            <p>&nbsp;</p>
                            <p><span style=\"font-weight: 400;\">L&rsquo;&eacute;quipe Passeport Shopping</span></p>";
            $mail->send();

            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }

    private function generateToken($email)
    {
        $reset_token = sha1(time()) . str_random(5);

        try
        {
            DB::insert("INSERT INTO reset_pass (email, reset_token) VALUES (:email, :reset_token) ON DUPLICATE KEY UPDATE reset_token = :update_token", [
                'email'       => $email,
                'reset_token' => $reset_token,
                'update_token' => $reset_token
            ]);

            return $reset_token;

        } catch (QueryException $e) {
            return false;
        }
    }

    private function sendMailRegistration($receiverInfos, $shop_origin = '', $center_origin = '')
    {
        $mail = new PHPMailer(true); // Passing `true` enables exceptions

        try {
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom('info@passeport.shopping', 'Passeport Shopping');
            $mail->addAddress($receiverInfos['email'], $receiverInfos['firstname'] . ' ' . $receiverInfos['lastname']); // Add a recipient
            $mail->addReplyTo('info@passeport.shopping', 'Information');

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = "Votre Passeport Shopping a bien été créé";
            $mail->Body    = "<p>&nbsp;</p>
                            <p><strong><img style=\"margin: 50px 100px 50px 100px;\" src=\"https://passeport.shopping/images/courriel.png\" alt=\"courriel passeport shopping\" width=\"410\" height=\"252\" /></strong></p>
                            <p><strong>Nous venons de vous cr&eacute;er un compte Passeport Shopping. </strong></p>
                            <p><strong>Qu&rsquo;est-ce qu&rsquo;un Passeport Shopping?</strong></p>
                            <p><span style=\"font-weight: 400;\"><br /></span><span style=\"font-weight: 400;\">Le Passeport Shopping c&rsquo;est un acc&egrave;s unique pour tous vos achats &agrave; travers le r&eacute;seau Shooopping, dont fait partie </span><span style=\"font-weight: 400;\"><a href=\"$center_origin\">" . $center_origin . "</a></span></a>&nbsp;<span style=\"font-weight: 400;\">. </span></p>
                            <p><span style=\"font-weight: 400;\">Vous reconna&icirc;trez la possibilit&eacute; d&rsquo;une connexion </span><strong>passeport.shooopping</strong><span style=\"font-weight: 400;\"> gr&acirc;ce &agrave; la </span><span style=\"font-weight: 400;\"><br /></span><span style=\"font-weight: 400;\">petite cl&eacute; verte&nbsp;:</span></p><img src=\"https://passeport.shopping/images/ps_button.png\" max-height=\"42%\" max-width=\"42%\"/>
                            <p><span style=\"font-weight: 400;\">Vos acc&egrave;s ont &eacute;t&eacute; cr&eacute;&eacute;s gr&acirc;ce &agrave; votre dernier achat effectu&eacute; sur le r&eacute;seau.</span><span style=\"font-weight: 400;\"><br /></span><span style=\"font-weight: 400;\">Vous pourrez utiliser la m&ecirc;me adresse courriel ainsi que le mot de passe cr&eacute;er sur la boutique <a href=\"$shop_origin\">" . $shop_origin . "</a> pour tous vos prochains achats &agrave; travers le r&eacute;seau. </span></p>
                            <p><span style=\"font-weight: 400;\">Facilitez votre magasinage et utilisez votre compte </span><strong>Passeport Shopping</strong><span style=\"font-weight: 400;\"> pour effectuer tous vos achats plus rapidement. </span></p>
                            <p>&nbsp;</p>
                            <p><span style=\"font-weight: 400;\">L&rsquo;&eacute;quipe Passeport Shopping</span></p>";
            $mail->send();

            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
