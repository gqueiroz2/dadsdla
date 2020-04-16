<?php

/*
|--------------------------------------------------------------------------
| Web RoutesreResu
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::group(['middleware'=>'auth'],function(){
	Route::get('home','adSalesController@home')->name('home');
	Route::get('email','adSalesController@email')->name('email');
});
*/

Route::group(['prefix' => 'planning'],function(){
	Route::get('/','planningController@home')
						->name('planningHome');

	Route::group(['prefix' => 'base'],function(){
		Route::get('rollOutExcel','rollOutExcelController@excelG')
							->name('rollOutExcelG');
		Route::post('rollOutExcel','rollOutExcelController@excelP')
							->name('rollOutExcelP');		

	});	

});
