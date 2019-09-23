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
	Route::group(['prefix'=>'pAndR'],function(){


		Route::group(['prefix'=>'pacingReport'],function(){
			
			Route::get('/','pacingReportController@get')
										->name('pacingReportGet');
			Route::post('/','pacingReportController@post')
										->name('pacingReportPost');
		});

		Route::group(['prefix'=>'VPReport'],function(){
			
			Route::get('/','VPController@get')
										->name('VPGet');
			Route::post('/','VPController@post')
										->name('VPPost');
			Route::post('save','VPController@save')
										->name('VPSave');
		});

		Route::group(['prefix'=>'AccountExecutiveReport'],function(){
			
			Route::get('/','AEController@get')
										->name('AEGet');
			Route::post('/','AEController@post')
										->name('AEPost');
			Route::post('save','AEController@save')
										->name('AESave');

		});

		Route::group(['prefix'=>'VPMonthReport'],function(){
			
			Route::get('/','VPMonthController@get')
										->name('VPMonthGet');
			Route::post('/','VPMonthController@post')
										->name('VPMonthPost');

			Route::post('save','VPMonthController@save')
										->name('VPMonthSave');
		});

	});
	Route::group(['prefix'=>'ajax'],function(){
		Route::post('salesRepByRegion','ajaxController@getSalesRepByRegion')
									->name('salesRepByRegion');
	});
});


