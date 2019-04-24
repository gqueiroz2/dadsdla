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

Auth::routes();

Route::get('test','RootController@getTest')
						->name('getTest');
Route::post('test','RootController@postTest')
						->name('postTest');

Route::get('/','adSalesController@home');



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

	Route::group(['prefix' => 'file'],function(){

		Route::post('agency','fileUploadController@agency')	
						->name('fileUploadAgency');

		Route::post('client','fileUploadController@client')	
						->name('fileUploadClient');

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

Route::group(['prefix' => 'adsales'],function(){
	Route::get('/','adSalesController@home')
						->name('adSalesHome');

	Route::group(['prefix' => 'results'],function(){

		Route::get('monthly','resultsController@monthlyGet')
						->name('monthlyResultsGet');
		Route::post('monthly','resultsController@monthlyPost')
						->name('monthlyResultsPost');
	});
});

Route::group(['prefix' => 'ajax'],function(){

	Route::group(['prefix' => 'adsales'],function(){
		Route::post('firstPosMonthly','ajaxController@firstPosMonthly');
		Route::post('secondPosMonthly','ajaxController@secondPosMonthly');
		Route::post('currencyByRegion','ajaxController@currencyByRegion');
	});

});

Route::group(['prefix' => 'User'], function(){

	Route::get('login', 'AuthController@loginGet')->name('loginGet');
	Route::post('login', 'AuthController@loginPost')->name('loginPost');
	Route::get('forgotPassword', 'AuthController@forgotPasswordGet')->name('forgotPasswordGet');
	Route::post('forgotPassword', 'AuthController@forgotPasswordPost')->name('forgotPasswordPost');
});

