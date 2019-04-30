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
	Route::get('YoY','resultsYoYController@YoYGet')
						->name('YoYResultsGet');
	Route::post('YoY','resultsYoYController@YoYPost')
					->name('YoYResultsPost');

	Route::get('share','shareController@shareGet')
					->name('resultsShareGet');
	Route::post('share','shareController@sharePost')
					->name('resultsSharePost');
						
	Route::get('resume','resultsResumeController@get')
					->name('resultsResumeGet');				
	Route::post('resume','resultsResumeController@post')
					->name('resultsResumePost');
});
