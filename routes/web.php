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

Route::get('test','RootController@getTest')
						->name('getTest');
Route::post('test','RootController@postTest')
						->name('postTest');

Route::get('/','AuthController@loginGet')->name('loginGet');
Route::post('/', 'AuthController@loginPost')->name('loginPost');

Route::get('forgotPassword', 'AuthController@forgotPasswordGet')->name('forgotPasswordGet');
Route::post('forgotPassword', 'AuthController@forgotPasswordPost')->name('forgotPasswordPost');

Route::post('requestToChangePassword', 'AuthController@requestToChangePassword')->name('requestToChangePassword');

Route::post('resetPassword', 'AuthController@resetPassword')->name('resetPassword');

Route::get('home','adSalesController@home');

Route::group(['prefix' => 'adsales'],function(){
	Route::get('/','adSalesController@home')
						->name('adSalesHome');

	Route::group(['prefix' => 'results'],function(){

		Route::get('monthly','resultsController@monthlyGet')
						->name('monthlyResultsGet');
		Route::post('monthly','resultsController@monthlyPost')
						->name('monthlyResultsPost');
	});
});

Route::group(['prefix' => 'ajax'],function(){

	Route::group(['prefix' => 'adsales'],function(){
		Route::post('firstPosMonthly','ajaxController@firstPosMonthly');
		Route::post('secondPosMonthly','ajaxController@secondPosMonthly');
		Route::post('currencyByRegion','ajaxController@currencyByRegion');
	});

});

