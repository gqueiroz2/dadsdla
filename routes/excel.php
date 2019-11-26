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
			Route::group(['prefix'=>'results'], function(){
				Route::post('summary','resultsExcelController@resultsSummary')
								->name('summaryExcel');

				Route::post('month','resultsExcelController@resultsMonth')
											->name('monthExcel');

				Route::post('quarter','resultsExcelController@resultsQuarter')
											->name('quarterExcel');

				Route::post('share','resultsExcelController@resultsShare')
											->name('shareExcel');

				Route::post('yoyBrand','resultsExcelController@resultsYoYBrand')
											->name('yoyBrandExcel');
			});

			Route::group(['prefix'=>'ranking'], function(){
				Route::post('brand','rankingExcelController@brand')
									->name('brandExcel');
			});

		Route::post('core','excelController@performanceCore')
									->name('coreExcel');

		Route::group(['prefix'=>'viewer'], function(){
			Route::post('base', 'viewerExcelController@viewerBase')
										->name('baseExcel');
		});

		});
	});
});


