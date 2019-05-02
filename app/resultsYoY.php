<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\results;
use App\sql;
use App\brand;
use App\ytd;
use App\pRate;
use App\planByBrand;

class resultsYoY extends results {
    
    public function getBrandsName($con, $brand){

    	$b = new brand();
    	$allBrands = $b->getBrand($con);
    	$brands = array();

    	//Selecionar canais
    	if ($brand[0] == "dn") {
    		for ($i = 0; $i < sizeof($allBrands); $i++) { 
				$brands[$i] = $allBrands[$i]['name'];
    		}
    	}else{
    		for ($i = 0; $i < sizeof($brand); $i++) { 
    			for ($j = 0; $j < sizeof($allBrands); $j++) { 
    				if ($brand[$i] == $allBrands[$j]['id']) {
    					$brands[$i] = $allBrands[$j]['name'];
    				}
    			}
    		}
    	}

    	return $brands;
    }

    public function line1Get($con, $form1, $year, $value, $region){

    	$form = null;
    	$order_by = 1;

    	$columnsName = array();

    	if ($form1 == "IBMS") {
			$form = new ytd();
			
			$columnsName = array("campaign_sales_office_id", "sales_rep_sales_office_id", "year");
			
			$order_by = 10;

			$value .= "_revenue";
    	} elseif($form1 == "CMAPS"){
    		$form = new cmaps();

    		$columnsName = array("sales_group_id", "sales_rep_id", "year");

    		$order_by = 9;
    	}else{
    		$form = new header();

    		$columnsName = array("campaign_sales_office_id", "sales_rep_sales_office_id", "year");

    		$order_by = 10;

    		$value .= "_revenue";
    	}

    	$columnsValue = array($region, $region, $year);

        $formValue = $form->get($con, $columnsName, $columnsValue, $order_by);

        $p = new pRate();
        $pRate = 3;

        $finalValue = array();

        /*for ($i = 0; $i < sizeof($formValue); $i++) {
        	$finalValue[$formValue[$i]['month']] = $formValue[$i]['$value'] * $pRate;
        }

        $sumLine = $ytd->sum($con, $value, $columnsName, $columnsValue, $region, $year);
        return $sumLine;*/
    }

    public function line2Get($con, $form2, $regionID, $year){

    	$columnsName = array("sales_office_id", "year");
    	$columnsValue = array($regionID, $year);

    	$p = new planByBrand();

    	$plans = $p->get($con, $colNames, $values, $order_by = 8);

    	$pR = new pRate();
    	$pRate = 3;

    	$finalValue = array();

    	for ($i = 0; $i < sizeof($formValue); $i++) {
        	$finalValue[$plans[$i]['month']] = $plans[$i]['revenue'] * $pRate;
        }

        $sumLine = $plans->sum($con, $value, $columnsName, $columnsValue, $regionID, $year);
        return $sumLine;	
    }
}
