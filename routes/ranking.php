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


	Route::get('ranking','rankingController@get')
					->name('rankingGet');				
	Route::post('ranking','rankingController@post')
					->name('rankingPost');
});

Route::group(['prefix' => 'ajaxRanking'], function(){
	Route::post('typeByRegion', 'ajaxController@typeByRegion');
	Route::post('firstPosYear', 'ajaxController@firstPosYear');
	Route::post('secondPosYear', 'ajaxController@secondPosYear');
	Route::post('thirdPosYear', 'ajaxController@thirdPosYear');
	Route::post('typeNameByType', 'ajaxController@typeNameByType');
	Route::post('type2ByType', 'ajaxController@type2ByType');
	Route::post('topsByType2', 'ajaxController@topsByType2');
	Route::post('subRanking', 'ajaxController@subRanking');
});
