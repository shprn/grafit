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

Route::group(['middleware'=>'web'], function(){
    Route::get('/', 'PromoController@index')->name('promo');
    Route::post('/sendMessage', 'PromoController@sendMessage')->name('promo.sendMessage');

    Route::get('/constructor', 'PricesController@constructor')->name('prices');
    Route::get('/getprice', 'PricesController@getPrice');
});


// Closed routes
Auth::routes();
Route :: get ('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/productions', 'ProductionsController@index')->name('productions');
    Route::get('/productions/{id}', 'ProductionsController@show')->name('productions.show');

    Route::get('/invoices/export', 'InvoicesController@export')->name('invoices.export');
    Route::get('/invoices', 'InvoicesController@index')->name('invoices');
    Route::get('/invoices/{id}', 'InvoicesController@show')->name('invoices.show');

});

