<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\AE;
use App\sql;
use App\excel;
use App\salesManagement;
use Validator;

class salesManagementController extends Controller{
    
	public function home(){
		return view('SalesManagement.home');
	}

	public function CustomReportV1(){

		$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$sM = new salesManagement();

		$temp = $sM->customReportV1($con);
		//var_dump($temp);
		return view('salesManagement.customReportV1',compact('temp'));
	}


}
