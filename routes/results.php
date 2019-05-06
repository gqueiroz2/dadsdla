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

	Route::get('monthly','resultsMQController@getMonthly')
					->name('resultsMonthlyGet');				
	Route::post('monthly','resultsMQController@postMonthly')
					->name('resultsMonthlyPost');				

	Route::get('quarter','resultsMQController@get')
					->name('resultsQuarterGet');				
	Route::post('quarter','resultsMQController@post')
					->name('resultsQuarterPost');	

	


});


Route::group(['prefix' => 'ajaxResults'], function(){
	Route::post('currencyByRegion','ajaxController@currencyByRegion');
	Route::post('salesRepGroupByRegion','ajaxController@salesRepGroupByRegion');
	Route::post('salesRepBySalesRepGroup','ajaxController@salesRepBySalesRepGroup');
	Route::post('sourceByRegion','ajaxController@sourceByRegion');
});
