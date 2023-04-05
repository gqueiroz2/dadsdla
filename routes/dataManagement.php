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

		Route::post('insertBvBandAfterCheck','dataManagementController@insertBvBandAfterCheck')
							->name('insertBvBandAfterCheck');

		Route::post('agencyGroupCheck','dataManagementController@agencyGroupCheck')
							->name('agencyGroupCheck');

		Route::post('insertAgencyGroupBV','dataManagementController@insertAgencyGroupBV')
							->name('insertAgencyGroupBV');

		Route::get('insertBvBandG','dataManagementController@insertBvBandG')
							->name('insertBvBandG');	
		
		Route::post('insertPayTV','dataManagementController@insertPayTV')
							->name('insertPayTV');
		
		Route::post('insertCurrentTarget','dataManagementController@insertCurrentTarget')
							->name('insertCurrentTarget');
							
		Route::post('insertMonthTarget','dataManagementController@insertMonthTarget')
							->name('insertMonthTarget');

		Route::post('insertBvBandP','dataManagementController@insertBvBandP')
							->name('insertBvBandP');

		Route::get('dataCurrentThroughtG','dataManagementController@dataCurrentThroughtG')
							->name('dataCurrentThroughtG');	
		Route::post('dataCurrentThroughtP','dataManagementController@dataCurrentThroughtP')
							->name('dataCurrentThroughtP');		

		Route::post('fixCRM','dataManagementController@fixCRM')
							->name('fixCRM');

		Route::get('relationshipClient','relationshipController@relationshipClientGet')
							->name('relationshipClientGet');
		Route::post('relationshipClient','relationshipController@relationshipClientPost')
							->name('relationshipClientPost');
		Route::get('relationshipAgency','relationshipController@relationshipAgencyGet')
							->name('relationshipAgencyGet');
		Route::post('relationshipAgency','relationshipController@relationshipAgencyPost')
							->name('relationshipAgencyPost');

		Route::post('relationshipUpdateAgency','relationshipController@relationshipUpdateAgency')
							->name('relationshipUpdateAgency');

		Route::get('agencySomething','ClientAgencyController@agencyGet')
							->name('dataManagementAgencyGet');
		Route::get('clientSomething','ClientAgencyController@clientGet')
							->name('dataManagementClientGet');

		Route::post('insertClientGroup','ClientAgencyController@insertGroup')
							->name('insertClientGroup');
		Route::post('insertOneClient','ClientAgencyController@insertOne')
							->name('insertOneClient');

		Route::post('insertAgencyGroup','ClientAgencyController@insertGroup')
							->name('insertAgencyGroup');
		Route::post('insertOneAgency','ClientAgencyController@insertOne')
							->name('insertOneAgency');



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
		Route::get('email', 'dataManagementController@emailDivulgacaoGet')
							->name('dataManagementEmailDivulgacaoGet');
							
		Route::group(['prefix' => 'ClientAgency'],function(){

			Route::get('clientAgencyExcelGet','ClientAgencyController@rootExcel')
							->name('clientAgencyExcel');

			Route::post('clientAgencyExcelHandler','ClientAgencyController@excelHandler')
							->name('clientAgencyExcelHandler');


		});			

		Route::group(['prefix' => 'chain'],function(){
			Route::get('root','ChainController@chainGet')
							->name('chain');
			Route::post('truncateChain','ChainController@truncateChain')
							->name('truncateChain');
			Route::post('checkElements','CheckElementsController@base')
							->name('checkElementsPost');
			Route::post('first','ChainController@firstChain')
							->name('firstChain');
			Route::post('second','ChainController@secondChain')
							->name('secondChain');
			Route::post('third','ChainController@thirdChain')
							->name('thirdChain');
			Route::post('thirdToDLA','ChainController@thirdToDLA')
							->name('thirdToDLA');
							
		});

		Route::group(['prefix'=> 'chainCmaps'], function(){
			Route::get('chainCmaps','chainCmapsController@chainGet')
							->name('chainCmaps');
			Route::post('cmapstruncate','chainCmapsController@truncate')
							->name('truncate');
			Route::post('cmapsfirstC','chainCmapsController@firstChain')
							->name('cmapsFirstC');
			Route::post('cmapsSecondC','chainCmapsController@secondChain')
							->name('cmapsSecondC');
			Route::post('cmapstruncatethirdC','chainCmapsController@thirdChain')
							->name('thirdChainCmaps');
			Route::post('cmapsToDLA','chainCmapsController@thirdToDLA')
							->name('cmapsToDLA');
			Route::post('dailyResultsChain', 'chainCmapsController@dailyResultsChain')
							->name('dailyResults');
		});

		Route::group(['prefix'=> 'insightsChain'], function(){
			Route::get('insightsChain','chainInsightsController@INSIGHTSGet')
							->name('insightsChain');
			Route::post('trubcate','chainInsightsController@truncate')
							->name('truncate');
			Route::post('firstC','chainInsightsController@firstC')
							->name('firstC');
			Route::post('secondC','chainInsightsController@secondChain')
							->name('secondC');
			Route::post('thirdC','chainInsightsController@thirdChain')
							->name('thirdC');
			Route::post('ToDLA','chainInsightsController@toDLA')
							->name('toDLA');
		});

		Route::group(['prefix' => 'file'],function(){
			Route::get('ytd','ChainController@ytdGet')
							->name('fileUploadytdGet');
			Route::get('CMAPS','ChainController@CMAPSGet')
							->name('fileUploadCMAPSGet');
			Route::get('miniHeader','ChainController@miniHeaderGet')
							->name('fileUploadMiniHeaderGet');
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
