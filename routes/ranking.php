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
	Route::group(['prefix'=>'rankings'], function(){
		Route::group(['prefix'=>'ranking'], function(){
			Route::get('/','rankingController@get')
						->name('rankingGet');				
			Route::post('/','rankingController@post')
						->name('rankingPost');
		});

		Route::group(['prefix'=>'brand'],function(){
			Route::get('/','rankingBrandController@brandGet')
					->name('brandGet');
			Route::post('/','rankingBrandController@brandPost')
						->name('brandPost');
		});

		Route::group(['prefix'=>'market'],function(){
			Route::get('/','rankingMarketController@get')
					->name('marketGet');
			Route::post('/','rankingMarketController@post')
						->name('marketPost');
		});
	});
	
	Route::group(['prefix'=>'churn'],function(){
		Route::get('/', 'rankingChurnController@get')
				->name('churnGet');
		Route::post('/', 'rankingChurnController@post')
				->name('churnPost');
	});

	Route::group(['prefix'=>'new'], function(){
		Route::get('/', 'rankingNewController@get')
				->name('newGet');
		Route::post('/','rankingNewController@post')
				->name('newPost');
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
	Route::post('brandSubRanking', 'ajaxController@brandSubRanking');
	Route::post('marketSubRanking', 'ajaxController@marketSubRanking');
	Route::post('churnSubRanking', 'ajaxController@churnSubRanking');
	Route::post('newSubRanking', 'ajaxController@newSubRanking');
});
});
