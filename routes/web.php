<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$app->get('/', function () use ($app) {
//    return View('test');
//});

$app->group(['middleware' => 'auth'], function($app) {
  	$app->get('login',  ['uses' => 'CustomerController@login']);
	$app->post('sign_in',  ['as' => 'sign_in','uses' => 'CustomerController@signIn']);
});


$app->group(['prefix'=>'api', 'middleware' => 'auth'], function($app) {

  	$app->post('customer/create',  ['uses' => 'CustomerController@createCustomer']);
  	
  	$app->post('customer/status',  ['uses' => 'CustomerController@customerExists']);

	$app->get('customer/has_session',  ['uses' => 'CustomerController@hasSession']);
	$app->get('customer/get_session_data',  ['uses' => 'CustomerController@getCustomerFromSession']);

});

$app->group(['prefix'=>'api', 'middleware' => 'BasicAuth'], function($app) {

  	$app->get('customer/infos',  ['uses' => 'CustomerController@getInfos']);

});
