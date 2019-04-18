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
	Route::get('salesRepresentative','dataManagementController@salesRepresentativeGet')
						->name('dataManagementSalesRepresentativeGet');
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

	Route::group(['prefix' => 'add'],function(){
		
		Route::post('region','dataManagementController@addRegion')
						->name('dataManagementAddRegion');
		Route::post('user','dataManagementController@addUser')
						->name('dataManagementAddUser');
		Route::post('pRate','dataManagementController@addPRate')
						->name('dataManagementAddPRate');
		Route::post('currency','dataManagementController@addCurrency')
						->name('dataManagementAddCurrency');
		Route::post('salesRepresentativeGroup','dataManagementController@addSalesRepresentativeGroup')
						->name('dataManagementAddSalesRepresentativeGroup');
		Route::post('salesRepresentative','dataManagementController@addSalesRepresentative')
						->name('dataManagementAddSalesRepresentative');
		Route::post('agency', 'dataManagementController@addAgency')
						->name('dataManagementAddAgency');						
		Route::post('salesRepresentativeUnit','dataManagementController@addSalesRepresentativeUnit')
						->name('dataManagementAddSalesRepresentativeUnit');
		Route::post('brand','dataManagementController@addBrand')
						->name('dataManagementAddBrand');
		Route::post('brandUnit','dataManagementController@addBrandUnit')
						->name('dataManagementAddBrandUnit');
		Route::post('origin','dataManagementController@addOrigin')
						->name('dataManagementAddOrigin');
	});	

	Route::group(['prefix' => 'edit'],function(){
		Route::get('region','dataManagementController@editRegionGet')
						->name('dataManagementEditRegionGet');
		Route::post('region','dataManagementController@editRegionPost')
						->name('dataManagementEditRegionPost');
		Route::get('currency','dataManagementController@editCurrencyGet')
						->name('dataManagementEditCurrencyGet');
		Route::post('currency','dataManagementController@editCurrencyPost')
						->name('dataManagementEditCurrencyPost');
		Route::get('prate','dataManagementController@editPRateGet')
						->name('dataManagementEditPRateGet');
		Route::post('prate','dataManagementController@editPRatePost')
						->name('dataManagementEditPRatePost');
	});

	Route::group(['prefix' => 'ajax'],function(){
		Route::post('salesRepGroupByRegion','dataManagementAjaxController@salesRepGroupByRegion')
						->name('AjaxSalesRepGroupByRegion');
		Route::post('salesRepBySalesRepGroup','dataManagementAjaxController@salesRepBySalesRepGroup')
						->name('AjaxSalesRepBySalesRepGroup');
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

