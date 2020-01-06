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