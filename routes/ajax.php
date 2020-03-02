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

		Route::group(['prefix'=>'adsales'],function(){
			Route::post('salesRepByRegion','ajaxController@getSalesRepByRegion');
			Route::post('newSalesRepByRegion','ajaxController@getNewSalesRepByRegion');
			Route::post('newSalesRepByRegionAndYear','ajaxController@getNewSalesRepByRegionAndYear');
			Route::post('newSalesRepUnitByRegionAndYear','ajaxController@getNewSalesRepUnitByRegionAndYear');
			Route::post('salesRepByRegionAndYear','ajaxController@getSalesRepByRegionAndYear');
			
			Route::post('agencyByRegion','ajaxController@getAgencyByRegion');
			Route::post('clientByRegion','ajaxController@getClientByRegion');

			Route::post('agencyByRegionAndYear','ajaxController@getAgencyByRegionAndYear');
			Route::post('clientByRegionAndYear','ajaxController@getClientByRegionAndYear');


			Route::post('clientByRegionAndAgency','ajaxController@getClientByRegionAndAgency');
			Route::post('clientByRegionAndAgencySize','ajaxController@getClientByRegionAndAgencySize');
			Route::post('clientByRegionSize','ajaxController@getClientByRegionSize');

			Route::post('clientByRegionInsights','ajaxController@getClientByRegionInsights');

			Route::post('sourceByRegion','ajaxController@newSourceByRegion');
			Route::post('brandBySource','ajaxController@brandBySource');
		});

		Route::post('yearOnFcst','ajaxController@yearOnFcst');

		Route::post("agencyNumberByAgencyGroup","rankingController@agencyNumberByAgencyGroup");

		Route::group(['prefix'=>'dashboards'],function(){
			Route::post("Overview-BaseFilter","ajaxController@baseFilter");
			Route::post("Overview-SecondaryFilter","ajaxController@secondaryFilter");

			Route::post("Overview-BaseFilterTitle","ajaxController@baseFilterTitle");
			Route::post("Overview-SecondaryFilterTitle","ajaxController@secondaryFilterTitle");

			Route::post("Overview-Product", "ajaxController@Product");
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


