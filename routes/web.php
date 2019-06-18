<?php

/*
|--------------------------------------------------------------------------
| Web RoutesreResu
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test','HomeController@test')
						->name('test');
Route::post('test','RootController@postTest')
						->name('postTest');

Route::get('home','adSalesController@home')
						->name('home');
Route::get('/','adSalesController@home');

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
		Route::post('firstPosByRegion','ajaxController@firstPosByRegion');
		Route::post('secondPosByRegion','ajaxController@secondPosByRegion');
		Route::post('thirdPosByRegion','ajaxController@thirdPosByRegion');
		Route::post('yearByRegion','ajaxController@yearByRegion');
		Route::post('tierByRegion','ajaxController@tierByRegion');
		Route::post('brandsByTier','ajaxController@brandsByTier');
	});

});

