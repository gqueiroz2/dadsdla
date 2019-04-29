<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\planByBrand;
use App\ytdLatam;
use App\cmaps;
use App\pRate;

/*
Author: Bruno Gomes
*Date:26/04/2019
*Razon:Results Montly and Quarter controller
*/
class resultsQuarterController extends Controller
{
	public function get(){
		$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currentYear = intval(date('Y'));
        $years = array($currentYear, $currentYear-1);

        $brand = new brand();
        $brands = $brand->getBrand($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $plan = new planByBrand();

        return view("adSales.results.2quarterGet", compact('salesRegion', 'years', 'brands', 'currencies'));
	}

	public function post(){

	  } 

    
}
