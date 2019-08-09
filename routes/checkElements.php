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
	Route::group(['prefix'=>'checkElements'],function(){

		Route::post('PedingStuffByRegions','CheckElementsController@check');
									

		Route::post('insertClient','insertPendingController@insertClientUnit')
									->name('insertClient');

		Route::post('insertAgency','insertPendingController@insertAgencyUnit')
									->name('insertAgency');

		



	});
});


