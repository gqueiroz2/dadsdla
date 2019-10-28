<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\shareRender;
use App\brand;
use App\pRate;

class shareController extends Controller{

    public function get(){
        $base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new shareRender();
        $b = new brand();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);

        $sb = sizeof($brand);

        for ($b=0; $b <$sb ; $b++) { 
            if ($brand[$b]['name'] == "OTH") {
                unset($brand[$b]);
            }
        }

        $brand = array_values($brand);        

        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con,null);

        return view("adSales.results.3shareGet",compact('region','salesRep','salesRepGroup','render','brand','currency'));
    }

    public function post(){
    	$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new shareRender();
        $b = new brand();
        $pr = new pRate();
        $s = new share();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'brand' => 'required',
            'source' => 'required',
            'salesRepGroup' => 'required',
            'salesRep' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'month' => 'required',
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $mtx = $s->generateShare($con);

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con,null);

        $sb = sizeof($brand);

        for ($b=0; $b <$sb ; $b++) { 
            if ($brand[$b]['name'] == "OTH") {
                unset($brand[$b]);
            }
        }

        $brand = array_values($brand);

        $rName = $s->TruncateRegion($mtx["region"]);

        $brandExcel = $base->handleBrand(Request::get('brand'));
        $regionExcel = Request::get('region');
        $yearExcel = Request::get('year');
        $monthExcel = Request::get('month');
        $sourceExcel = Request::get('source');
        $currencyExcel = Request::get('currency');
        $valueExcel = Request::get('value');
        $salesRepExcel = Request::get('salesRep');

        return view("adSales.results.3sharePost",compact('region','salesRep','salesRepGroup','render','brand','currency','mtx','rName'));
    }
}
