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
Route::group(['middleware' => ['auth']],function(){

	Route::group(['prefix' => 'dataManagement'],function(){

		Route::get('/','dataManagementController@home')
							->name('dataManagementHomeGet');
		Route::get('region','dataManagementController@regionGet')
							->name('dataManagementRegionGet');
		Route::get('user','dataManagementController@userGet')
							->name('dataManagementUserGet');
		Route::get('pRate','dataManagementController@pRateGet')
							->name('dataManagementPRateGet');
		Route::get('salesRep','dataManagementController@salesRepGet')
							->name('dataManagementSalesRepGet');
		Route::get('agency','dataManagementController@agencyGet')
							->name('dataManagementAgencyGet');
		Route::get('client','dataManagementController@clientGet')
							->name('dataManagementClientGet');
		Route::get('origin','dataManagementController@originGet')
							->name('dataManagementOriginGet');
		Route::get('brand','dataManagementController@brandGet')
							->name('dataManagementBrandGet');
		Route::get('truncate','dataManagementController@truncateGet')
							->name('dataManagementTruncateGet');
		Route::get('trueTruncate','dataManagementController@trueTruncateGet')
							->name('dataManagementTrueTruncateGet');
		Route::get('importTable','dataManagementController@importTableGet')
							->name('dataManagementImportTableGet');
		Route::get('ytdLatam','dataManagementController@ytdLatamGet')
							->name('dataManagementYtdLatamGet');
		
		Route::get('agency','dataManagementController@agencyGetFromExcel')
							->name('dataManagementAgencyGetFromExcel');
		Route::get('client','dataManagementController@clientGetFromExcel')
							->name('dataManagementClientGetFromExcel');

		Route::group(['prefix' => 'chain'],function(){
			Route::post('miniHeaderSecond','ChainController@secondChain')
							->name('miniHeaderSecondChain');
			Route::post('miniHeaderThird','ChainController@thirdChain')
							->name('miniHeaderThirdChain');
			Route::post('miniHeaderThirdToDLA','ChainController@thirdToDLA')
							->name('miniHeaderThirdToDLA');

		});

		Route::group(['prefix' => 'file'],function(){

			Route::get('miniHeader','ChainController@miniHeaderGet')
							->name('fileUploadMiniHeaderGet');

			Route::post('miniHeader','ChainController@miniHeaderPost')
							->name('fileUploadMiniHeaderPost');

			Route::get('excel','fileUploadController@excelGet')
							->name('fileUploadExcelGet');

			Route::post('excel','fileUploadController@excelPost')
							->name('fileUploadExcelPost');

			Route::post('agency','fileUploadController@agency')	
							->name('fileUploadAgencyFromExcel');

			Route::post('client','fileUploadController@client')	
							->name('fileUploadClientFromExcel');

			Route::post('ytdLatam','fileUploadController@ytdLatam')	
							->name('fileUploadYtdLatam');

		});

		Route::group(['prefix' => 'add'],function(){
			
			Route::post('region','dataManagementController@regionAdd')
							->name('dataManagementRegionAdd');

			Route::post('user','dataManagementController@userAdd')
							->name('dataManagementUserAdd');

			Route::post('userType','dataManagementController@UserTypeAdd')
							->name('dataManagementUserTypeAdd');

			Route::post('pRate','dataManagementController@PRateAdd')
							->name('dataManagementPRateAdd');

			Route::post('currency','dataManagementController@CurrencyAdd')
							->name('dataManagementCurrencyAdd');

			Route::post('salesRepGroup','dataManagementController@SalesRepGroupAdd')
							->name('dataManagementSalesRepGroupAdd');

			Route::post('salesRep','dataManagementController@SalesRepAdd')
							->name('dataManagementSalesRepAdd');

			Route::post('newAgency','dataManagementController@newAgencyAdd')
							->name('fileUploadAgencyAdd');

			Route::post('newAgencyGroup','dataManagementController@newAgencyGroupAdd')
							->name('fileUploadAgencyGroupAdd');

			Route::post('newClient','dataManagementController@newClientAdd')
							->name('fileUploadClientAdd');

			Route::post('newClientGroup','dataManagementController@newClientGroupAdd')
							->name('fileUploadClientGroupAdd');

			Route::post('agency', 'dataManagementController@AgencyAdd')
							->name('dataManagementAgencyAdd');

			Route::post('salesRepUnit','dataManagementController@SalesRepUnitAdd')
							->name('dataManagementSalesRepUnitAdd');

			Route::post('brand','dataManagementController@BrandAdd')
							->name('dataManagementBrandAdd');

			Route::post('brandUnit','dataManagementController@BrandUnitAdd')
							->name('dataManagementBrandUnitAdd');
							
			Route::post('origin','dataManagementController@OriginAdd')
							->name('dataManagementOriginAdd');
		});	

		Route::group(['prefix' => 'edit'],function(){
			
			Route::get('region','dataManagementController@regionEditGet')
							->name('dataManagementRegionEditGet');

			Route::post('region','dataManagementController@regionEditPost')
							->name('dataManagementRegionEditPost');

			Route::get('currency','dataManagementController@currencyEditGet')
							->name('dataManagementCurrencyEditGet');

			Route::post('currency','dataManagementController@currencyEditPost')
							->name('dataManagementCurrencyEditPost');

			Route::get('prate','dataManagementController@pRateEditGet')
							->name('dataManagementPRateEditGet');

			Route::post('prate','dataManagementController@pRateEditPost')
							->name('dataManagementPRateEditPost');

			Route::post('salesRepGroup','dataManagementController@salesRepGroupEditFilter')
							->name('dataManagementSalesRepGroupEditFilter');

			Route::post('salesRep','dataManagementController@salesRepEditFilter')
							->name('dataManagementSalesRepEditFilter');

			Route::post('salesRepUnit','dataManagementController@salesRepUnitEditFilter')
							->name('salesRepUnitEditFilter');

			Route::get('userType','dataManagementController@userTypeEditGet')
							->name('dataManagementUserTypeEditGet');

			Route::post('userType','dataManagementController@userTypeEditPost')
							->name('dataManagementUserTypeEditPost');

			Route::post('user','dataManagementController@userEditFilter')
							->name('dataManagementUserEditFilter');


		});

		Route::group(['prefix' => 'ajax'],function(){
			Route::post('salesRepGroupByRegion','dataManagementAjaxController@salesRepGroupByRegion')
							->name('AjaxSalesRepGroupByRegion');
			Route::post('salesRepBySalesRepGroup','dataManagementAjaxController@salesRepBySalesRepGroup')
							->name('AjaxSalesRepBySalesRepGroup');
			Route::post('subLevelGroupByRegion','dataManagementAjaxController@subLevelGroupByRegion')
							->name('AjaxSubLevelGroupByRegion');
							
		});	

	});

});
