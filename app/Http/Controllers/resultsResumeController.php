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
        $b = new brand();
        $pr = new pRate();
		$render = new Render();

		$region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);

		return view('adSales.results.0resumeGet',compact('render','region','brand','currency'));

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
		$cYear = intval( date('Y') );
		$pYear = $cYear - 1;
		$region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
		$regionID = Request::get('region');

		$tmp = $r->getRegion($con,array($regionID));

		if( is_array($tmp) ){
			$salesRegion = $tmp[0]['name'];
		}else{
			$salesRegion = $tmp['name'];
		}

		if($salesRegion == 'Brazil'){
			$salesShow = 'CMAPS';
		}else{
			$salesShow = 'Header';
		}


		$brandID = $base->handleBrand( $con, $b ,Request::get('brand'));
		$currencyID = Request::get('currency');
		$value = Request::get('value');		
		$month = $base->getMonth();
		$tmp = $pr->getCurrency($con,array($currencyID));
		if($tmp){$currencyS = $tmp[0]['name'];}else{$currencyS = "ND";}
		$valueS = strtoupper($value);
		$resume = new resultsResume();
		$tableSales = $resume->salesTable($regionID,$cYear);
		$tableTarget = "plan_by_brand";
		$tableActual = $tableTarget;
		$tableCorporate = $tableActual;
		$joinSales = false;
		$joinTarget = false;
		$joinActual = false;
		$joinCorporate = false;

		for ($m=0; $m < sizeof($month); $m++) { 
            /* SE FOR CMAPS */
            if($salesRegion == 'Brazil'){
	            $whereSales[$m] = "WHERE (cmaps.month IN (".$month[$m][1].") ) 
	                               AND ( cmaps.year IN ($cYear))

	                              ";
	            $whereSalesPYear[$m] = "WHERE (cmaps.month IN (".$month[$m][1].") ) 
	                                    AND ( cmaps.year IN ($pYear))

	                                   ";
	        }else{
	        	$whereSales[$m] = "WHERE (mini_header.month IN (".$month[$m][1].")) 
	        	                   AND (mini_header.year IN ($cYear))
	        	                   AND (mini_header.campaign_sales_office_id IN (".$regionID.") )
	        	                  ";
	            $whereSalesPYear[$m] = "WHERE (mini_header.month IN (".$month[$m][1].")) 
	                                    AND ( mini_header.year IN ($pYear) )
	                                    AND (mini_header.campaign_sales_office_id IN (".$regionID.") )
	                                   ";
	        }
            /* FAZER SE FOR HEADER */            
        }

        if($value == "gross"){$tr = "GROSS";}else{$tr = "NET";}
        
        for ($m=0; $m < sizeof($month); $m++) { 
            $whereTarget[$m] = "WHERE (plan_by_brand.month IN (".$month[$m][1].")) 
            					   AND (source  = \"TARGET\")
                                   AND (type_of_revenue = \"".$tr."\")
                                   AND (sales_office_id = \"".$regionID."\")
                               ";

            $whereActual[$m] = "WHERE ( plan_by_brand.month IN (".$month[$m][1].") ) 
            					   AND ( source  = \"ACTUAL\" )
                                   AND ( type_of_revenue = \"".$tr."\" )
                                   AND (sales_office_id = \"".$regionID."\")
                               ";

            $whereCorporate[$m] = "WHERE ( plan_by_brand.month IN (".$month[$m][1].") ) 
            					   AND ( source  = \"CORPORATE\" )
                                   AND ( type_of_revenue = \"".$tr."\" )
                                   AND (sales_office_id = \"".$regionID."\")  
                                   ";
        }

		$salesCYear = $resume->generateVector($con,$tableSales,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinSales,$whereSales);
		$target = $resume->generateVector($con,$tableTarget,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinTarget,$whereTarget);
		$actual = $resume->generateVector($con,$tableActual,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinActual,$whereActual);	
		$corporate = $resume->generateVector($con,$tableCorporate,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinCorporate,$whereCorporate);;
		/*
		$pAndR = $cmaps;
		$finance = $cmaps;
		*/
		$previousYear = $resume->generateVector($con,$tableSales,$regionID,date('Y'),$month,$brandID,$currencyID,$value,$joinSales,$whereSalesPYear);

		//$id = implode(",", $brandID);
		$matrix = $resume->assembler($month,$salesCYear,$actual,$target,$corporate/*$pAndR,$finance*/,$previousYear);
		return view('adSales.results.0resumePost',compact('render','region','brand','currency','matrix','currencyS','valueS','cYear','pYear','salesShow'));

	}

}
