<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\brand;
use App\pRate;
use App\Render;
use App\quarterRender;

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

        $render = new Render();

        $qRender = new quarterRender();

        return view("adSales.results.2quarterGet", compact('salesRegion', 'years', 'brands', 'currencies', 'render', 'qRender'));
	}

	public function post(){

	  } 

    
}
