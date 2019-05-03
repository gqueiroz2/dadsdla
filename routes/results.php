<?php

/*
|--------------------------------------------------------------------------
| Results Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'results'],function(){
	Route::get('YoY','resultsYoYController@get')
						->name('YoYResultsGet');
	Route::post('YoY','resultsYoYController@post')
					->name('YoYResultsPost');

	Route::get('share','shareController@get')
					->name('resultsShareGet');
	Route::post('share','shareController@post')
					->name('resultsSharePost');
						
	Route::get('resume','resultsResumeController@get')
					->name('resultsResumeGet');				
	Route::post('resume','resultsResumeController@post')
					->name('resultsResumePost');	

	Route::get('quarter','resultsQuarterController@get')
					->name('quarterResultsGet');				
	Route::post('quarter','resultsQuarterController@post')
					->name('quarterResultsPost');	

	Route::get('monthly','resultsController@monthlyGet')
					->name('monthlyGet');				
	Route::post('monthly','resultsController@monthlyPost')
					->name('monthlyPost');				


});


Route::group(['prefix' => 'ajaxResults'], function(){
	Route::post('currencyByRegion','ajaxController@currencyByRegion');
	Route::post('salesRepGroupByRegion','ajaxController@salesRepGroupByRegion');
	Route::post('salesRepBySalesRepGroup','ajaxController@salesRepBySalesRepGroup');
});
