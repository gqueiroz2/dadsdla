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

Auth::routes();
Route::get('/','adSalesController@home');
Route::group(['prefix' => 'adsales'],function(){
	Route::get('/','adSalesController@home')->name('adSalesHome');

	Route::group(['prefix' => 'results'],function(){
		Route::get('monthly','resultsController@monthlyGet')->name('monthlyResultsGet');
		Route::post('monthly','resultsController@monthlyPost')->name('monthlyResultsPost');
	});
});

Route::group(['prefix' => 'ajax'],function(){

	Route::group(['prefix' => 'adsales'],function(){
		Route::post('firstPosMonthly','ajaxController@firstPosMonthly');
		Route::post('secondPosMonthly','ajaxController@secondPosMonthly');
		Route::post('currencyByRegion','ajaxController@currencyByRegion');
	});

});

