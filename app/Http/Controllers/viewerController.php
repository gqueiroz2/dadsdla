<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\base;
use App\Render;
use App\dataBase;
use App\region;
use App\brand;
use App\pRate;
use App\viewer;
use App\salesRep;
use App\cmaps;
use App\baseRender;
use App\sql;
use App\agency;

class viewerController extends Controller{

	public function baseGet(){
	
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
                    'value' => 'required',
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

                //var_dump($currencies);


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
               /*$a = new agency();
                $agencies = $a->getAgency($con,array($agency))[0]['name'];*/

                //var_dump($agencies);

                $client = Request::get("client");

                //var_dump($salesCurrency);

                $check = false;

                $brand = Request::get("brand");

                for ($b=0; $b <sizeof($brand); $b++) { 
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


                //var_dump($salesCurrency);

                $table = $viewer->getTables($con,$salesRegion,$source,$month,$brand,$value,$year,$salesCurrency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client);

                $total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies,$salesRegion);

                $mtx = $viewer->assemble($table,$total,$salesCurrency,$source,$con,$salesRegion,$currencies);

               //var_dump(Request::all());

                //var_dump($table);
                

                return view("adSales.viewer.basePost", compact("years","render","bRender", "salesRep", "region","salesCurrency","currencies","brands","viewer","mtx","months","value","brand","source","regions",'year','total'));

	}

}
