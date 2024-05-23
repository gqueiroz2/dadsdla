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

		Route::group(['prefix'=>'baseReport'],function(){
			
			Route::get('/','baseReportPandRController@get')
										->name('BaseReportPandRGet');
			Route::post('/','baseReportPandRController@post')
										->name('BaseReportPandRPost');			

		});
		
		Route::group(['prefix'=>'Pacing'],function(){
			
			Route::get('/','pacingReportController@get')
										->name('pacingReportGet');
			Route::post('/','pacingReportController@post')
										->name('pacingReportPost');
		});

		Route::group(['prefix'=>'managerView'],function(){
			
			Route::get('/','VPController@get')
										->name('VPGet');
			Route::post('/','VPController@post')
										->name('VPPost');
			Route::post('save','VPController@save')
										->name('VPSave');
		});
		
		Route::group(['prefix'=>'forecastCicle'],function(){
			
			Route::get('/','AEController@get')
										->name('AEGet');
			Route::post('/','AEController@post')
										->name('AEPost');
			Route::post('save','AEController@save')
										->name('AESave');

		});

		Route::group(['prefix'=>'propertyReport'],function(){
			
			Route::get('/','propertyController@get')
										->name('propertyGet');
			Route::post('/','propertyController@post')
										->name('propertyPost');
			//Route::post('save','propertyController@save')
										//->name('AESave');

		});


		/*
		Route::group(['prefix'=>'MonthAdjust'],function(){
			
			Route::get('/','VPMonthController@get')
										->name('VPMonthGet');
			Route::post('/','VPMonthController@post')
										->name('VPMonthPost');

			Route::post('save','VPMonthController@save')
										->name('VPMonthSave');
		});
		*/ 
		Route::group(['prefix'=>'agencyandagviewer'],function(){
			
			Route::get('/','pandrAgencyAGViewerController@get')
										->name('agencyAGroupViewerGet');

			Route::post('/','pandrAgencyAGViewerController@post')
										->name('agencyAGroupViewerPost');
		});

		Route::group(['prefix'=>'byBrandviewer'],function(){
			
			Route::get('/','byBrandViewerController@get')
										->name('byBrandGet');

			Route::post('/','byBrandViewerController@post')
										->name('byBrandPost');
		});

	});
	Route::group(['prefix'=>'ajax'],function(){
		Route::post('salesRepByRegionPandR','ajaxController@salesRepByRegionFiltered')
									->name('salesRepByRegionPandR');
		Route::post('salesRepByRegionPandRMult','ajaxController@salesRepByRegionFilteredMult')
									->name('salesRepByRegionPandR');
	});
});

Route::group(['prefix' => 'ajaxPAndR'], function(){
	Route::post('changeVal', 'ajaxController@changeVal');
	Route::post('reCalculateQuarterValues', 'ajaxController@reCalculateQuarterValues');
	Route::post('reCalculateTotalVal', 'ajaxController@reCalculateTotalVal');
	Route::post('verifyVal', 'ajaxController@verifyVal');
	Route::post('splittedClients', 'ajaxController@splittedClients');
	Route::post('transformVal', 'ajaxController@transformVal');
	Route::post('number', 'ajaxController@number');
});


