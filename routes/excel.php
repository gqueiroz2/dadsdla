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

				Route::post('yoyMonth','resultsExcelController@resultsYoYMonth')
											->name('yoyMonthExcel');
			});

			Route::group(['prefix'=>'ranking'], function(){
				Route::post('brand','rankingExcelController@brand')
									->name('brandExcel');

				Route::post('market','rankingExcelController@market')
									->name('marketExcel');

				Route::post('churn','rankingExcelController@churn')
									->name('churnExcel');

				Route::post('new','rankingExcelController@new')
									->name('newExcel');
			});

			Route::group(['prefix'=>'performance'],function(){
				Route::post('core','performanceExcelController@performanceCore')
											->name('coreExcel');

				Route::post('executive','performanceExcelController@performanceExecutive')
												->name('executiveExcel');
			});
				
			Route::group(['prefix'=>'viewer'], function(){
				Route::post('vBase', 'viewerExcelController@viewerBase')
											->name('baseExcel');
			});

		});
	});
});


