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

Route::group(['middleware' => 'auth'],function(){
	Route::group(['prefix'=>'pacingReport'],function(){
		
		Route::get('/','pacingReportController@get')
									->name('pacingReportGet');
		Route::post('/','pacingReportController@post')
									->name('pacingReportPost');
	});
});


