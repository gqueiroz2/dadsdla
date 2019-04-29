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

	Route::get('YoY','resultsYoYController@YoYGet')
						->name('ResultsYoYGet');
	Route::post('YoY','resultsYoYController@YoYPost')
					->name('ResultsYoYPost');				
});
