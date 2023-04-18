<?php

/*
|--------------------------------------------------------------------------
| Results Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'],function(){

	Route::group(['prefix' => 'results'],function(){
		
		Route::get('consolidateDLA','consolidateResultsController@getDLA')
							->name('consolidateResultsGetDLA');
		Route::post('consolidateDLA','consolidateResultsController@postDLA')
						->name('consolidateResultsPostDLA');

		Route::get('consolidateOffice','consolidateResultsController@getOffice')
							->name('consolidateResultsGetOffice');
		Route::post('consolidateOffice','consolidateResultsController@postOffice')
						->name('consolidateResultsPostOffice');

		Route::get('consolidate','consolidateResultsController@get')
							->name('consolidateResultsGet');
		Route::post('consolidate','consolidateResultsController@post')
						->name('consolidateResultsPost');

		Route::get('daily','resultsLATAMController@get')
							->name('resultsLATAMGet');
		Route::post('daily','resultsLATAMController@post')
						->name('resultsLATAMPost');


		Route::get('Pacing','resultsPacingController@get')
							->name('resultsPacingGet');
		Route::post('Pacing','resultsPacingController@post')
						->name('resultsPacingPost');

		Route::get('YoY','resultsYoYController@get')
							->name('resultsYoYGet');
		Route::post('YoY','resultsYoYController@post')
						->name('resultsYoYPost');

		Route::get('monthlyYoY','resultsMonthlyYoYController@get')
							->name('resultsMonthlyYoYGet');
		Route::post('monthlyYoY','resultsMonthlyYoYController@post')
						->name('resultsMonthlyYoYPost');

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

		Route::get('quarter','resultsMQController@getQuarter')
						->name('resultsQuarterGet');				
		Route::post('quarter','resultsMQController@postQuarter')
						->name('resultsQuarterPost');	

		
	});
});

Route::group(['prefix' => 'ajaxResults'], function(){
	Route::post('currencyByRegion','ajaxController@currencyByRegion');
	Route::post('newCurrencyByRegion','ajaxController@newCurrencyByRegion');
	Route::post('salesRepGroupByRegion','ajaxController@salesRepGroupByRegion');
	Route::post('salesRepBySalesRepGroup','ajaxController@salesRepBySalesRepGroup');
	Route::post('sourceByRegion','ajaxController@sourceByRegion');
	Route::post('valueBySource','ajaxController@valueBySource');
	Route::post('company', 'ajaxController@company');
});
