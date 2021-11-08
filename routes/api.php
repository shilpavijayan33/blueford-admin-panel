<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


	// Route::middleware('auth:api')->get('/user', function (Request $request) {
	//     return $request->user();
	// });

	/*login*/
	Route::post('login','Api\LoginController@login');
	Route::post('check-phone-exists','Api\LoginController@checkPhoneNumber');
	Route::post('register-plumber','Api\RegisterController@registerPlumber');

	Route::group(['middleware' => ['auth:api','suspenduser']], function () {
	    Route::post('user/register-salesman','Api\UserController@registerSalesman');
	    Route::post('user/save-reward','Api\UserController@saveRewards');
	    Route::post('user/return-product','Api\UserController@returnProduct');
	    Route::get('user/transactions','Api\UserController@allTransactions');
	    Route::post('user/redeem-request','Api\UserController@redeemRequest');
	    Route::get('user/redeem-history','Api\UserController@redeemHistory');   
	    Route::get('user/user-details','Api\UserController@userDetails'); 
	    Route::get('user/redeem-total','Api\UserController@redeemTotals'); 
	    Route::get('user/redeem-list','Api\UserController@redeemList');  


	});

	Route::group(['middleware' => ['auth:api']], function () {
	    Route::get('user/sign-out','Api\UserController@userSignOut');   
	});



