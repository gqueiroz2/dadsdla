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

Route::group(['prefix' => 'test'],function(){
	
	Route::get('controle','testController@controleG');
	Route::post('controle','testController@controleP')->name('testeControle');
	

});



