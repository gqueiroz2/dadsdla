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

class viewerController extends Controller{

	public function baseGet(){
	
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

                return view("adSales.viewer.baseGet",compact("render","years","region","currency","currencies","brand"));
	}


	public function basePost(){

                $render =  new baseRender();
                $base = new base();
                $months = $base->month;
        	
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
                
                $r = new region();
                $region = $r->getRegion($con, NULL);

                $b = new brand();
                $brands = $b->getBrand($con);

                $currency = new pRate();
                $currencies = $currency->getCurrency($con); 

                $viewer = new viewer();

                $salesRegion = Request::get("region");

                $source = Request::get("sourceDataBase");

                $month = Request::get("month");

                $piNumber = Request::get("PI");

                $tmp = Request::get("brand");
                $brand = $base->handleBrand($tmp);

                $value = Request::get("value");

                $year = Request::get("year");

                $salesCurrency = Request::get("currency");

                $salesRep = Request::get("salesRep");

                $table = $viewer->getTables($con,$salesRegion,$source,$month,$brand,$value,$year,$salesCurrency,$salesRep,$db,$sql);

                $assemble = $viewer->assemble($table,$salesCurrency,$source);

                var_dump($table);

                //var_dump(Request::all());

                //return view("adSales.viewer.basePost", compact("years","render", "salesRep", "region","currency","currencies","brands","viewer"));

	}

}
