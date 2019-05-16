<?php

namespace App;

use App\region;
use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class performance extends Model{
    
    public function makeCore($con){
    	$b = new brand();
        $r = new region();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();
        $pr = new pRate();


 		$region = Request::get('region');
 		$year = Request::get('year');
 		$brand = $base->handleBrand(Request::get('brand'));
 		$source = Request::get('source');
 		$salesRepGroup = Request::get('salesRepGroup');
 		$currency = Request::get('currency');
 		$month = Request::get('month');
 		$value = Request::get('value');


 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$region,$year,$currency);

        //nome da moeda pra view
        $tmp = array($currency);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        //valor para view
        if ($value == "gross") {
            $valueView = "Gross";
        }else{
            $valueView = "Net";
        }
        //year view
        $yearView = $year[0];
    	
    	//nome da região na view
    	$tmp = array($region);
        $regionView = $r->getRegion($con,$tmp)[0]["name"];

        //definindo nome dos brands
        $brandName = array();
        for ($b=0; $b <sizeof($brand) ; $b++) { 
            array_push($brandName, $brand[$b][1]);
        }

        
        //define de onde vai se tirar as informações do banco, sendo as opções ytd(IBMS), cmaps, header ou digital.
        $actualMonth = date("m");
        for ($m=0; $m <sizeof($month) ; $m++) {
            for ($b=0; $b <sizeof($brand); $b++) {
                if ($m > $actualMonth-1) {
                    if($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($region == "1") {
                        $sourceBrand[$m][$b] = "CMAPS";
                    }else{
                        $sourceBrand[$m][$b] = "Header";
                    }
                }else{
                    if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($brand[$b][1] == "OTH") {
                        $sourceBrand[$m][$b] = "IBMS";
                    }elseif($brand[$b][1] == "FN" && $region == "1"){
                        $sourceBrand[$m][$b] = "CMAPS";
                    }else{
                        $sourceBrand[$m][$b] = $source;
                    }
                }
            }
        }

		//olha quais nucleos serão selecionados        
        $salesRepName = array();
        if ($salesRepGroup == 'all') {
                
            $tmp = array($region);
        
            $salesRepGroup = $sr->getSalesRepGroup($con,$tmp);
        
            $tmp = array();
            
            for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
                array_push($tmp, $salesRepGroup[$i]["id"]);
            }

            $salesRepGroup = $tmp;
        
            $salesRepGroupView = "All";   
        }else{

            $salesRepGroup = array($salesRepGroup);

            $salesRepGroupView = $sr->getSalesRepGroupById($con,$salesRepGroup)["name"];

        }

        //pega informações dos representantes do nucleo(s)
        $tempYear = $year[0];
        $tmp = $sr->getSalesRepFilteredYear($con,$salesRepGroup,$region,$tempYear,$source);
        $salesRep = array();
        $salesRepView = "All";
        for ($i=0; $i <sizeof($tmp) ; $i++) { 
            array_push($salesRep, $tmp[$i]["id"]);
            array_push($salesRepName, $tmp[$i]["salesRep"]);
        }


        

    }
}
