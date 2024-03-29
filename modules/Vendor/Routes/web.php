<?php
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
use Illuminate\Support\Facades\Route;
Route::group(['prefix'=>'vendor'],function(){
    Route::post('/register','VendorController@register')->name('vendor.register');

});

Route::group(['prefix'=>'vendor','middleware' => ['auth']],function(){
    Route::match(['get'],'/payouts','PayoutController@index')->name("vendor.payout.index");
    Route::post('/storePayoutAccounts','PayoutController@storePayoutAccounts')->name("vendor.payout.storePayoutAccounts");
    Route::post('/createPayoutRequest','PayoutController@createPayoutRequest')->name("vendor.payout.createPayoutRequest");

    Route::get('/booking-report','VendorController@bookingReport')->name("vendor.bookingReport");
    Route::get('/vendor_balance','VendorController@getBalance');
    Route::get('/zeroBalance/{vendor_id}','VendorController@addTransaction')->name('returnToZero');
});
