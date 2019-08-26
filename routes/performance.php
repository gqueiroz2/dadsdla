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
	Route::group(['prefix'=>'performance'],function(){
		
		Route::get('core','corePerformanceController@get')
									->name('corePerformanceGet');
		Route::post('core','corePerformanceController@post')
									->name('corePerformancePost');

		Route::get('office','quarterPerformanceController@get')
									->name('quarterPerformanceGet');
		Route::post('office','quarterPerformanceController@post')
									->name('quarterPerformancePost');

		Route::get('individual','executivePerformanceController@get')
									->name('executivePerformanceGet');
		Route::post('individual','executivePerformanceController@post')
									->name('executivePerformancePost');
	});
});

Route::group(['prefix' => 'ajaxPerformance'], function(){

});
