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
	Route::group(['prefix'=>'dashboards'],function(){

		Route::group(['prefix'=>'overview'],function(){
			Route::get('/','dashboardsController@overviewGet')
							->name('overviewGet');
			Route::post('/','dashboardsController@overviewPost')
								->name('overviewPost');
		});

		// == Antique BV == //
		/*Route::group(['prefix'=>'bv'],function(){
			Route::get('/','dashboardsController@dashboardBVGet')
							->name('dashboardBVGet');
			Route::post('/','dashboardsController@dashboardBVPost')
								->name('dashboardBVPost');
		});*/

		Route::group(['prefix'=>'bv'],function(){
			Route::get('/','bvController@bvGet')
							->name('dashboardBVGet');
			Route::post('/','bvController@bvPost')
								->name('dashboardBVPost');
			Route::post('/save','bvController@bvSaveForecast')
										->name('bvSaveForecast');
		});

		Route::group(['prefix'=>'resumeBV'],function(){
			Route::get('/','bvController@resumeBVGet')
							->name('resumeBVGet');
			Route::post('/','bvController@resumeBVPost')
								->name('resumeBVPost');
			/*Route::post('/save','bvController@bvSaveForecast')
										->name('bvSaveForecast');*/
		});


	});
});


