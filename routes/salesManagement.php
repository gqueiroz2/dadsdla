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

Route::group(['prefix' => 'salesManagement'],function(){

	Route::get('/','salesManagementController@home')
						->name('salesManagementHome');

	Route::group(['prefix' => 'CRM'],function(){
		
		Route::post('customReportV1','salesManagementController@customReportV1')
							->name('salesManagementCustomReportV1Post');

		

	});	

});
