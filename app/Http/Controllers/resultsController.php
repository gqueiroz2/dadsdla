<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\brand;
use App\planByBrand;

class resultsController extends Controller{

    public function monthlyGet(){
        
        $base = new base();

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );      
        $salesRegion = $base->getSalesRegion();
        $brand = $base->getBrand();

        return view("adSales.results.0monthlyGet",compact('years','salesRegion','brand'));
    }

    public function monthlyPost(){              

        $base = new base();
        $db = new dataBase();
        $con = $db->openConnection("dla");
        $monthly = new monthly();
        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );      
        $salesRegion = $base->getSalesRegion();
        $brand = $base->getBrand();

        $_salesRegion = Request::get("salesRegion");
        $_year = Request::get("year");
        $_brand = Request::get("brand");
        $_firstPos = Request::get("firstPos");
        $_secondPos = Request::get("secondPos");
        $_currency = Request::get("currency");
        $_value = Request::get("value");
/*
        echo "<pre>".var_dump($_salesRegion)."</pre>";
        echo "<pre>".var_dump($_year)."</pre>";
        echo "<pre>".var_dump($_brand)."</pre>";
        echo "<pre>".var_dump($_firstPos)."</pre>";
        echo "<pre>".var_dump($_secondPos)."</pre>";
        echo "<pre>".var_dump($_currency)."</pre>";
        echo "<pre>".var_dump($_value)."</pre>";
*/
        $tableFirstPos = $base->pattern("Source",$_salesRegion,$_firstPos,null,null,null);
        $tableSecondPos = $base->pattern("Source",$_salesRegion,$_secondPos,null,null,null);
        
        $brandFirstPos = $base->pattern("Brand",$_salesRegion,$_brand,"base",$_firstPos,null);
        $brandSecondPos = $base->pattern("Brand",$_salesRegion,$_brand,"base",$_secondPos,null);
        
        $operand = $base->defineCurrency($con,$_salesRegion,$_year,$_currency);
        
        $sumFirstPos = $base->defineValue($_firstPos,$_value);      
        $sumSecondPos = $base->defineValue($_secondPos,$_value);    

        $firstPosMonth = $base->pattern("Month",$_salesRegion,null,"base",$_firstPos,null);     
        $secondPosMonth = $base->pattern("Month",$_salesRegion,null,"base",$_secondPos,null);

        $mtx = $monthly->caller($con,$_salesRegion,$_year,$_brand,$_currency,$_value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth);

        return view("adSales.results.0monthlyPost");
    }

    public function YoYGet(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currentYear = intval(date('Y'));
        $years = array($currentYear, $currentYear-1);

        $brand = new brand();
        $brands = $brand->getBrand($con);

        $plan = new planByBrand();
        //var_dump($brands);

        return view("adSales.results.YoYGet", compact('salesRegion', 'years', 'brands'));

    }

    public function YoYPost(){
        
    }
}
