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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix'=>'api', 'middleware' => 'auth'], function($app) {

  	$app->post('customer/create',  ['uses' => 'CustomerController@createCustomer']);
  	
  	$app->post('customer/status',  ['uses' => 'CustomerController@customerExists']);

});

$app->group(['prefix'=>'api', 'middleware' => 'BasicAuth'], function($app) {

  	$app->get('customer/infos',  ['uses' => 'CustomerController@getInfos']);

});

$app->get('customer/has_session',  ['uses' => 'CustomerController@hasSession']);
$app->get('customer/get_session_data',  ['uses' => 'CustomerController@getCustomerFromSession']);