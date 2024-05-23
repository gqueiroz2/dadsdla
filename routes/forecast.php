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

Route::group(['prefix'=>'forecast'],function(){	

	Route::group(['prefix'=>'monthlyForecast'],function(){
			
			Route::get('/','forecastController@byAEGet')
										->name('forecastByAEGet');
			Route::post('/','forecastController@byAEPost')
										->name('forecastByAEPost');		
			Route::post('/save','forecastController@byAESave')
										->name('forecastByAESave');			

		});

});


