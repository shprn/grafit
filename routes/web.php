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

Route::get('/', 'PromoController@index')->name('promo');
Route::post('/sendMessage', 'PromoController@sendMessage')->name('promo.sendMessage');

Route::get('/productions', 'ProductionsController@index')->name('productions');
Route::get('/productions/{id}', 'ProductionsController@show')->name('productions.show');

Route::get('/invoices', 'InvoicesController@index')->name('invoices');
Route::get('/invoices/{id}', 'InvoicesController@show')->name('invoices.show');

Route::get('/constructor', 'PricesController@constructor')->name('prices');
Route::get('/getprice_blank', 'PricesController@getPriceBlank');
Route::get('/getprice_journal', 'PricesController@getPriceJournal');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();
Route :: get ('/logout', 'Auth\LoginController@logout')->name('logout');