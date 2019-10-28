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

		Route::post('quarter','excelController@resultsQuarter')
									->name('quarterExcel');

		Route::post('month','excelController@resultsMonth')
									->name('monthExcel');

		Route::post('yoy','excelController@resultsYoY')
									->name('yoyExcel');
		
		Route::post('summary','excelController@resultsSummary')
									->name('summaryExcel');

		Route::post('share','excelController@resultsShare')
									->name('shareExcel');

		});
	});
});


