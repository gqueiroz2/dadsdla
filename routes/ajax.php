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

		Route::post('typeConsolidate','ajaxController@typeConsolidate');
		Route::post('typeSelectConsolidate','ajaxController@typeSelectConsolidate');
		Route::post('typeSelectConsolidateDLA','ajaxController@typeSelectConsolidateDLA');

		Route::group(['prefix'=>'adsales'],function(){
			Route::post('salesRepByRegion','ajaxController@getSalesRepByRegion');
			Route::post('newSalesRepByRegion','ajaxController@getNewSalesRepByRegion');
			Route::post('newSalesRepByRegionAndYear','ajaxController@getNewSalesRepByRegionAndYear');
			Route::post('repByRegionAndYear','ajaxController@getRepByRegionAndYear');
			Route::post('getDirector','ajaxController@getDirector');
			Route::post('getPackets','ajaxController@getPackets');
			Route::post('getManager','ajaxController@getManager');
			Route::post('getPacketsFilter','ajaxController@getPacketsFilter');
			Route::post('getAgencyPipeline','ajaxController@getAgencyPipeline');
			Route::post('getClientPipeline','ajaxController@getClientPipeline');

			Route::post('newSalesRepRepresentativesByRegionAndYear','ajaxController@getNewSalesRepRepresentativesByRegionAndYear');

			Route::post('newSalesRepUnitByRegionAndYear','ajaxController@getNewSalesRepUnitByRegionAndYear');
			Route::post('salesRepByRegionAndYear','ajaxController@getSalesRepByRegionAndYear');
			
			Route::post('agencyByRegion','ajaxController@getAgencyByRegion');
			Route::post('clientByRegion','ajaxController@getClientByRegion');

			Route::post('agencyByRegionSF','ajaxController@getAgencyByRegionSF');
			Route::post('clientByRegionSF','ajaxController@getClientByRegionSF');

			Route::post('agencyByRegionAndYear','ajaxController@getAgencyByRegionAndYear');
			Route::post('clientByRegionAndYear','ajaxController@getClientByRegionAndYear');


			Route::post('clientByRegionAndAgency','ajaxController@getClientByRegionAndAgency');
			Route::post('clientByRegionAndAgencySize','ajaxController@getClientByRegionAndAgencySize');
			Route::post('agencyByRegionAndAgency','ajaxController@getAgencyByRegionAndClient');
			Route::post('agencyByRegionAndAgencySize','ajaxController@getAgencyByRegionAndClientSize');
			Route::post('clientByRegionSize','ajaxController@getClientByRegionSize');
			Route::post('agencyByRegionSize','ajaxController@getAgencyByRegionSize');

			Route::post('clientByRegionInsights','ajaxController@getClientByRegionInsights');

			Route::post('sourceByRegion','ajaxController@newSourceByRegion');
			Route::post('brandBySource','ajaxController@brandBySource');
		});

		Route::post('yearOnFcst','ajaxController@yearOnFcst');

		Route::post("agencyNumberByAgencyGroup","rankingController@agencyNumberByAgencyGroup");

		Route::group(['prefix'=>'dashboards'],function(){
			Route::post("Overview-BaseFilter","ajaxController@baseFilter");
			Route::post("BV-BaseFilter","ajaxController@BVBaseFilter");

			Route::post("BV-agencyGroup","ajaxController@BVAgencyGroup");
			Route::post("resume-agencyGroup","ajaxController@BVAgencyGroupNoRep");

			Route::post('typeByRegionBV', 'ajaxController@typeByRegionBV');

			Route::post("Overview-SecondaryFilter","ajaxController@secondaryFilter");

			Route::post("Overview-BaseFilterTitle","ajaxController@baseFilterTitle");
			Route::post("Overview-SecondaryFilterTitle","ajaxController@secondaryFilterTitle");

			Route::post("Overview-Product", "ajaxController@Product");

			Route::post("salesRepByRegionFiltered","ajaxController@salesRepByRegionFiltered");

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


