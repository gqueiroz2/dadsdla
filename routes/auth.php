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
	Route::get('/logout','AuthController@logout')->name('logout');
});


Route::get('/','AuthController@loginGet')->name('loginGet');
Route::post('/', 'AuthController@loginPost')->name('loginPost');

Route::get('forgotPassword', 'AuthController@forgotPasswordGet')->name('forgotPasswordGet');
Route::post('forgotPassword', 'AuthController@forgotPasswordPost')->name('forgotPasswordPost');

Route::post('requestToChangePassword', 'AuthController@requestToChangePassword')->name('requestToChangePassword');
Route::post('resetPassword', 'AuthController@resetPassword')->name('resetPassword');