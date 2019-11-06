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

		//results excel
		Route::post('summary','excelController@resultsSummary')
								->name('summaryExcel');

		Route::post('month','excelController@resultsMQ')
									->name('monthExcel');

		Route::post('quarter','excelController@resultsMQ')
									->name('quarterExcel');

		Route::post('share','excelController@resultsShare')
									->name('shareExcel');

		Route::post('yoy','excelController@resultsYoY')
									->name('yoyExcel');

		Route::post('core','excelController@performanceCore')
									->name('coreExcel');

		//ranking excel
		Route::post('brand','rankingExcelController@brand')
									->name('brandExcel');

		});
	});
});


