<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	if(Auth::user())
		return redirect('/admin/product-management');
	else
        return view('auth.login');
});


// Route::post('login', 'Auth\LoginController@login');
Auth::routes();


   

	Route::group(['middleware' => ['auth:web','staffsuspend']], function () {

		Route::get('/home', 'HomeController@index')->name('home');
		Route::get('/admin/dashboard', 'Admin\DashboardController@dashboardAdmin');

		/*product-management*/
		Route::any('/admin/product-management', 'Admin\ProductController@productIndex');
		Route::post('/admin/save-product', 'Admin\ProductController@saveProduct');
		Route::post('/admin/edit-product', 'Admin\ProductController@editProduct');
		Route::get('/admin/delete-product/{id}', 'Admin\ProductController@deleteProduct');

		/*qrcode-management*/
		Route::any('/admin/qrcode-management', 'Admin\QRCodeController@qrIndex');
		Route::post('/admin/save-qrcodes', 'Admin\QRCodeController@saveQRcode');
		Route::get('/admin/delete-qrcode/{id}', 'Admin\QRCodeController@deleteQRcode');
		Route::post('/admin/product/delete-qr', 'Admin\QRCodeController@deleteSpecificQr');
		Route::get('/admin/qrcode-print/{id}','Admin\QRCodeController@printQRcode');
		Route::post('admin/filter-project','Admin\QRCodeController@getPrintQr');

		//qrcode-ajax

		Route::get('/admin/get-qr-code/{productid}','Admin\QRCodeController@getFirstQRCode');
		Route::get('/admin/get-more-pages/{page}/{product}','Admin\QRCodeController@getMoreQRCodes');



		Route::get('/admin/pagination', 'Admin\QRCodeController@index');
         Route::get('/admin/pagination/fetch_data', 'Admin\QRCodeController@fetch_data');



		/*slab-management*/
		Route::get('/admin/slab-management', 'Admin\SlabController@slabIndex');
		Route::get('/admin/slab-management/{id}', 'Admin\SlabController@slabDetails');
		Route::post('/admin/save-slab', 'Admin\SlabController@saveSlab');
		Route::post('/admin/edit-slab', 'Admin\SlabController@editSlab');
		Route::get('/admin/delete-slab/{id}', 'Admin\SlabController@deleteSlab');
		Route::post('/admin/plumber-sales-slab', 'Admin\SlabController@saveOtherSlabs');

		/*dealer-management*/
		Route::any('/admin/dealer-management', 'Admin\DealerController@dealerIndex');
		Route::post('/admin/save-dealer', 'Admin\DealerController@saveDealer');
		Route::post('/admin/edit-dealer', 'Admin\DealerController@editDealer');
		Route::get('/admin/email_validate/{email}', 'Admin\DealerController@validateEmail');
		Route::get('/admin/mobile_validate/{mobile}', 'Admin\DealerController@validateMobile');

		Route::get('/admin/suspend-dealer/{id}', 'Admin\DealerController@suspendDealer');
		Route::any('/admin/register-salesman', 'Admin\DealerController@registerSalesman');
		Route::any('/admin/edit-salesman', 'Admin\DealerController@editSalesman');



		/*transactions*/
		Route::get('/admin/transactions', 'Admin\TransactionsController@transactionsIndex');
		Route::get('/admin/transactions/data', 'Admin\TransactionsController@transactionsData');
		Route::get('/admin/transaction-details/{id}', 'Admin\TransactionsController@transactionDetails');
		Route::get('/admin/trans-print/{id}', 'Admin\TransactionsController@transPrint');



		/*plumber-management*/
		Route::any('/admin/plumber-management', 'Admin\PlumberController@plumberIndex');
		Route::get('/admin/accept-plumber/{id}', 'Admin\PlumberController@acceptPlumber');
		Route::get('/admin/reject-plumber/{id}', 'Admin\PlumberController@rejectPlumber');
		Route::get('/admin/suspend-plumber/{id}', 'Admin\PlumberController@suspendPlumber');

		/*salesman-management*/
		Route::any('/admin/salesman-management', 'Admin\SalesmanController@salesmanIndex');
		Route::get('/admin/suspend-salesman/{id}', 'Admin\SalesmanController@suspendSalesman');

		/*redeem*/
		Route::any('/admin/redeem-management', 'Admin\RedeemController@redeemIndex');
		Route::post('/admin/accept-redeem', 'Admin\RedeemController@acceptRedeem');
		Route::post('/admin/reject-redeem', 'Admin\RedeemController@rejectRedeem');
		Route::post('/admin/reject-allredeem', 'Admin\RedeemController@rejectAllRedeem');
		Route::post('/admin/accept-allredeem', 'Admin\RedeemController@acceptAllredem');


		/*staff management*/

		Route::get('/admin/staff-management', 'Admin\StaffController@staffIndex');
		Route::post('/admin/edit-staff', 'Admin\StaffController@staffEdit');
		Route::get('/admin/suspend-staff/{id}', 'Admin\StaffController@staffSuspend');
		Route::post('/admin/add-staff', 'Admin\StaffController@staffAdd');

		Route::get('save-curent-qr','Admin\DashboardController@saveCurrentQr');
		Route::get('admin/get-searched-products','Admin\QRCodeController@getSearchedProducts');
		Route::get('admin/get-list-products','Admin\QRCodeController@getlistProducts');



	Route::get('addDummy','Admin\DashboardController@addDummy');



});













