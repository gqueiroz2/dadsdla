<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\shareRender;
use App\brand;
use App\pRate;
use App\sql;
use App\resultsResume;

class resultsResumeController extends Controller{
    
	public function get(){
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new Render();

		$region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con);

		return view('adSales.results.0resumeGet',compact('render','region','brand','salesRepGroup','salesRep','currency'));

	}

	public function post(){
		$sql = new sql();
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new Render();

		$region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con,false);

		$regionID = Request::get('region');
		$brandID = $base->handleBrand( $con, $b ,Request::get('brand'));
		$currencyID = Request::get('currency');
		$value = Request::get('value');		
		$month = $base->getMonth();

		

		$currencyS = $pr->getCurrency($con,array($currencyID));
		$valueS = strtoupper($value);


		var_dump($currencyS);
		var_dump($valueS);

		$resume = new resultsResume();

		$tableCMAPS = "cmaps";
		$tableTarget = "plan_by_brand";

		$joinCMAPS = false;
		$joinTarget = false;

		for ($m=0; $m < sizeof($month); $m++) { 
            $whereCMAPS[$m] = "WHERE (cmaps.month IN (".$month[$m][1].") ) ";//" AND ( cmaps.brand_id IN ($id) )";
        }

        if($value == "gross"){
        	$tr = "GROSS";
        }else{
        	$tr = "NET";
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            $whereTarget[$m] = "WHERE ( plan_by_brand.month IN (".$month[$m][1].") ) 
                                   AND ( type_of_revenue = \"".$tr."\" )";
        }

		$cmaps = $resume->generateVector($con,$tableCMAPS,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinCMAPS,$whereCMAPS);
		

		$target = $resume->generateVector($con,$tableTarget,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinTarget,$whereTarget);	

		$actual = $cmaps;
		$pAndR = $cmaps;
		$finance = $cmaps;
		$pYear = $target;

		//$id = implode(",", $brandID);
		$matrix = $resume->assembler($month,$cmaps,$actual,$target,$pAndR,$finance,$pYear);

		return view('adSales.results.0resumePost',compact('render','region','brand','salesRepGroup','salesRep','currency','matrix'));

	}

}
