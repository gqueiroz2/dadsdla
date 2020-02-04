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

Route::group(['middleware' => 'auth'],function(){
	Route::group(['prefix'=>'performance1'],function(){
		
		Route::get('core','performanceController@coreGet')
									->name('coreGet');
		Route::post('core','performanceController@corePost')
									->name('corePost');

		Route::get('office','performanceController@officeGet')
									->name('officeGet');
		Route::post('office','performanceController@officePost')
									->name('officePost');

		Route::get('individual','performanceController@individualGet')
									->name('individualGet');
		Route::post('individual','performanceController@individualPost')
									->name('individualPost');
	});
});

Route::group(['prefix' => 'ajaxPerformance'], function(){

});
