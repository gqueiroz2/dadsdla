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
	Route::get('YoY','resultsController@YoYGet')
						->name('YoYResultsGet');
	Route::post('YoY','resultsController@YoYPost')
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


});
