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

Route::group(['prefix' => 'dataManagement'],function(){

	Route::get('/','resultsResumeController@get')
										->name('resultsResume');

	Route::get('YoY','resultsController@YoYGet')
						->name('YoYResultsGet');
	Route::post('YoY','resultsController@YoYPost')
					->name('YoYResultsPost');

	Route::get('share','shareController@shareGet')
					->name('resultsShareGet');
	Route::post('share','shareController@sharePost')
					->name('resultsSharePost');
});
