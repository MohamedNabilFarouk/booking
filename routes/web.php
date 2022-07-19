<?php
use Stevebauman\Location\Facades\Location;
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
Route::post('OpayCallback','paymentController@Opaycallback');
Route::post('testCall','paymentController@testCall');
Route::get('FlightBooking',function(){
    return view('flight');
})->name('FlightBooking');
Route::get('Callback','paymentController@callback');
Route::get('fawrypayCallback/{id}','paymentController@payatfawryCallback')->name('payment.confirmation');
Route::group(['middleware' => 'auth'], function () {
Route::get('fawryPayment','paymentController@generate');


});
Route::get('payMobPayment','paymentController@payMob');
Route::put('updateGateway/{id}','paymentController@updateGateway');






Route::get('opay','paymentController@oPay');


Route::get('testreturn',function(){
 return 'return url';
});

Route::get('ip',function(){
    // $my_ip= Request::ip();
//  dd(Request::ip());
        $ip = '31.6.10.0';

        $data = \Location::get($ip);

        dd($data);

});

Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

Route::get('/update', function (){
    return redirect('/');
});

//Cache
Route::get('/clear', function() {
    Artisan::call('route:clear');
    return "Cleared!";
});

//Login
Auth::routes();
//Custom User Login and Register
Route::post('register','\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login','\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::post('logout','\Modules\User\Controllers\UserController@logout')->name('auth.logout');
// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view']);

Route::post('BankPayment','paymentController@bankgenerate');
Route::get('MobileBankPayment','mobilePaymentController@bankgenerate');
Route::get('bankPayment/callback','paymentController@bankcallback');



Route::get('payMobPaymentCallback','paymentController@payMobPaymentCallback');
Route::post('payMobPayment','paymentController@payMob');