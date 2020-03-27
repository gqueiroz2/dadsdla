<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\base;
use App\Render;
use App\baseRender;
use App\insightsRender;

use App\region;
use App\brand;
use App\agency;
use App\salesRep;
use App\cmaps;

use App\viewer;
use App\insights;

use App\sql;
use App\pRate;
use App\dataBase;


class viewerController extends Controller{

    public function insightsGet(){
        $bs = new base();

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $b = new brand();
        $brand = $b->getBrand($con);

        $v = new viewer();

        return view("adSales.viewer.insightsGet",compact("render","years","region","currency","currencies","brand"));
    }

    public function insightsPost(){
       // var_dump(Request::all());

        $render =  new Render();
        $inRender =  new insightsRender();
        $base = new base();
        $months = $base->month;

        $in = new insights();

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $sql = new sql();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $years = array($cYear = intval(date('Y')), $cYear - 1);

        $salesRegion = Request::get("region");
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

        $b = new brand();
        $brand = $b->getBrand($con);

        $salesCurrency = Request::get("currency");
        $p = new pRate();
        $currencies = $p->getCurrency($con,array($salesCurrency))[0]['name'];

        $month = Request::get("month");

        $salesRep = Request::get("salesRep");

        $client = Request::get("client");

        $currency = Request::get("currency");

        $p = new pRate();
        $currencies = $p->getCurrency($con,array($currency))[0]['name']; 

        $value = Request::get("value");

        $check = false;

        $tmp = Request::get("brand");

        for ($t=0; $t < sizeof($tmp); $t++) { 
            $brands[$t] = json_decode(base64_decode($tmp[$t]))[0];
        }

        for ($b=0; $b < sizeof($brands); $b++) { 
            if ($brands[$b] == 9){
                $check = true;
            }
        }
        if ($check) {
            array_push($brands, "13");
            array_push($brands, "14");
            array_push($brands, "15");
            array_push($brands, "16");
        }

        $mtx = $in->assemble($con,$sql,$client,$month,$brands,$salesRep,$currency);

        $total = $in->total($con,$sql,$client,$month,$brands,$salesRep,$currencies,$salesRegion);

        $regionExcel = $salesRegion;
        $monthExcel = $month;
        $brandExcel = $brands;
        $salesRepExcel = $salesRep;
        $clientExcel = $client;
        $currencyExcel = $currencies;
        $valueExcel = $value;

        $title = "Viewer Insights";
        $titleExcel = "Viewer Insights.xlsx";
        $titlePdf = "Viewer Insights.pdf";

        return view("adSales.viewer.insightsPost",compact("render","years","region","currency","currencies","brand","regionExcel","monthExcel","brandExcel", "salesRepExcel","clientExcel", "currencyExcel","valueExcel"/*,"header"*/,"mtx","inRender","value","regions","total","titleExcel","titlePdf","title"));

    }

	public function baseGet(){
	
        $bs = new base();

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();
        $bRender = new baseRender();

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $b = new brand();
        $brand = $b->getBrand($con);

        $v = new viewer();

        return view("adSales.viewer.baseGet",compact("render","bRender","years","region","currency","currencies","brand"));
	}


	public function basePost(){

        $render =  new Render();
        $bRender = new baseRender();
        $base = new base();
        $months = $base->month;
        $viewer = new viewer();

	
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $sql = new sql();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'sourceDataBase' => 'required',
            'year' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'salesRep' => 'required',
            'currency' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $salesRegion = Request::get("region");
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];


        $b = new brand();
        $brands = $b->getBrand($con);

        $salesCurrency = Request::get("currency");
        $p = new pRate();
        $currencies = $p->getCurrency($con,array($salesCurrency))[0]['name']; 

        $source = Request::get("sourceDataBase");

        $month = Request::get("month");

        $especificNumber = Request::get("especificNumber");

        if (!is_null($especificNumber) ) {
            $checkEspecificNumber = true;
        }else{
            $checkEspecificNumber = false;
        }                

        $value = Request::get("value");

        $year = Request::get("year");

        $salesRep = Request::get("salesRep");
        
        $agency = Request::get("agency");

        $client = Request::get("client");

        $check = false;

        $tmp = Request::get("brand");

        $sizeOfClient = Request::get("sizeOfClient");

        if($sizeOfClient == sizeof($client)){
            $checkClient = true;
        }else{
            $checkClient = false;    
        }

        for ($t=0; $t < sizeof($tmp); $t++) { 
            $brand[$t] = json_decode(base64_decode($tmp[$t]))[0];
        }

        for ($b=0; $b < sizeof($brand); $b++) { 
            if ($brand[$b] == 9){
                $check = true;
            }
        }
        if ($check) {
            array_push($brand, "13");
            array_push($brand, "14");
            array_push($brand, "15");
            array_push($brand, "16");
        }

        $table = $viewer->getTables($con,$salesRegion,$source,$month,$brand,$year,$salesCurrency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client,$checkClient);

        //$total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies,$salesRegion,$agency,$client);
        
        $total = $viewer->totalFromTable($table);

        $mtx = $viewer->assemble($table,$salesCurrency,$source,$con,$salesRegion,$currencies);

        $regionExcel = $regions;
        $sourceExcel = $source;
        $yearExcel = $year;
        $monthExcel = $month;
        $brandExcel = $brand;
        $salesRepExcel = $salesRep;
        $agencyExcel = $agency;
        $clientExcel = $client;
        $currencyExcel = $currencies;
        $valueExcel = $value;
        $especificNumberExcel = $especificNumber;
        
        $title = $source." - Viewer Base";
        $titleExcel = $source." - Viewer Base.xlsx";
        $titlePdf = $source." - Viewer Base.pdf";

        return view("adSales.viewer.basePost", compact("years","render","bRender", "salesRep", "region","salesCurrency","currencies","brands","viewer","mtx","months","value","brand","source","regions","year","total","regionExcel","sourceExcel","yearExcel","monthExcel","brandExcel","salesRepExcel","agencyExcel","clientExcel","currencyExcel","currencyExcel","valueExcel", 'especificNumberExcel', "title", "titleExcel", "titlePdf"));

	}

}
