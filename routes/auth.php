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
Route::group(['middleware' => ['auth']],function(){
	Route::post('logout','AuthController@logout')->name('logout');
	Route::post('logout2','AuthController@logout2')->name('logout2');

	Route::get('/','AuthController@loginGet')->name('loginGet');
	Route::get('/login2','AuthController@loginGet2')->name('loginGet2');

});

Route::get('/logout','AuthController@logoutGet')->name('logoutGet');
Route::get('/logout2','AuthController@logoutGet2')->name('logoutGet2');

Route::get('/autenticate','AuthController@autenticate')->name('autenticate');
Route::get('/autenticate2','AuthController@autenticate2')->name('autenticate2');

Route::get('/permission','AuthController@permission')->name('permission');

Route::post('/', 'AuthController@loginPost')->name('loginPost');

Route::get('forgotPassword', 'AuthController@forgotPasswordGet')->name('forgotPasswordGet');
Route::post('forgotPassword', 'AuthController@forgotPasswordPost')->name('forgotPasswordPost');

Route::post('requestToChangePassword', 'AuthController@requestToChangePassword')->name('requestToChangePassword');
Route::post('resetPassword', 'AuthController@resetPassword')->name('resetPassword');
