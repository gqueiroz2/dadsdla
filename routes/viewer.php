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
Route::group(['prefix'=>'viewer'],function(){
	Route::get('base','viewerController@baseGet')->name('baseGet');
	Route::post('base','viewerController@basePost')->name('basePost');

	Route::get('insights','viewerController@insightsGet')->name('insightsGet');
	Route::post('insights','viewerController@insightsPost')->name('insightsPost');

	Route::get('packets','viewerController@packetsGet')->name('packetsGet');
	Route::post('packets','viewerController@packetsPost')->name('packetsPost');
	Route::post('save','viewerController@savePackets')->name('savePackets');
	Route::post('edit','viewerController@editPackets')->name('editPackets');
	Route::post('delete','viewerController@deletePackets')->name('deletePackets');

	Route::get('pipeline','viewerController@pipelineGet')->name('pipelineGet');
	Route::post('pipeline','viewerController@pipelinePost')->name('pipelinePost');
	Route::post('savePipeline','viewerController@savePipeline')->name('savePipeline');

	Route::get('saveRead','viewerController@saveCMAPSReadGet')->name('saveCMAPSReadGet');
	Route::post('saveRead','viewerController@saveCMAPSReadPost')->name('saveCMAPSReadPost');
});
