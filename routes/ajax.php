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
	Route::group(['prefix'=>'ajax'],function(){
		
		Route::post("agencyNumberByAgencyGroup","rankingController@agencyNumberByAgencyGroup");

		Route::group(['prefix'=>'dashboards'],function(){
			Route::post("Overview-BaseFilter","ajaxController@baseFilter");
		});

		Route::group(['prefix'=>'relationship'],function(){
			Route::post('agencyGroupByNewAgency','ajaxRelationshipController@agencyGroupByNewAgency')
									->name('agencyGroupByNewAgency');
		});

		Route::group(['prefix'=>'checkElements'],function(){
			Route::post('clientGroupByClient','ajaxController@clientGroupByClient');									

			Route::post('agencyGroupByAgency','ajaxController@agencyGroupByAgency');
									

		});

	});
});


