<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('base','analyticsController@baseGet')->name('analyticsBaseGet');
Route::group(['prefix'=>'analytics'],function(){	
	

	Route::post('base','analyticsController@base')->name('analyticsBasePost');

	Route::get('panel','analyticsController@panel')->name('analyticsPanel');


});


