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
	Route::group(['prefix'=>'generate'],function(){
		Route::group(['prefix'=>'excel'],function(){

		Route::post('month','excelController@resultsMonth')
									->name('monthExcel');
		
		Route::post('summary','excelController@resultsSummary')
									->name('summaryExcel');
		});
	});
});


