<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'booking'],function (){
    Route::get('/','BookingController@index')->name('report.admin.booking');
    Route::get('/orders','BookingController@orders')->name('report.admin.orders');
    Route::get('/{booking}/orderConfirm','BookingController@orderConfirm')->name('report.admin.orderConfirm');
    Route::get('/{booking}/orderCancelConfirm','BookingController@orderCancelConfirm')->name('report.admin.orderCancelConfirm');

    Route::get('/email_preview/{id}','BookingController@email_preview')->name('report.booking.email_preview');
});

