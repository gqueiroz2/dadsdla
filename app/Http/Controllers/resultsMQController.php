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
use App\resultsMQ;
use App\renderMQ;
use Validator;

class resultsMQController extends Controller{
	
        public function getMonthly(){
                
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
                return view('adSales.results.1monthlyGet',compact('render','region','brand','currency'));
        }

        public function postMonthly(){
                $base = new base();
                $db = new dataBase();
                $con = $db->openConnection("DLA");
                $r = new region();
                $b = new brand();
                $pr = new pRate();
                $render = new Render();
                $brand = $b->getBrand($con);
                $currency = $pr->getCurrency($con,false);                
                $validator = Validator::make(Request::all(),[
                        'region' => 'required',
                        'year' => 'required',
                        'brand' => 'required',
                        'secondPos' => 'required',
                        'thirdPos' => 'required',
                        'currency' => 'required',
                        'value' => 'required',
                ]);

                if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                }

                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);
                $regionID = Request::get('region');

                $brandTmp = Request::get('brand');
                $brandID = $base->handleBrand($brandTmp);

                $currencyID = Request::get("currency");

                $value = Request::get('value');        
                $year = Request::get('year');
                $month = $base->getMonth();
                $firstPos = Request::get('secondPos');
                $secondPos = Request::get('thirdPos');
                $tmp = $pr->getCurrency($con,array($currencyID));
                if($tmp){$currencyS = $tmp[0]['name'];}else{$currencyS = "ND";}
                
                $mq = new resultsMQ();
                $lines = $mq->lines($con,$tmp,$month,$secondPos,$brandID,$year,$regionID,$value,$firstPos);

                $mtx = $mq->assembler($con,$brandID,$lines,$month,$year,$firstPos);
                $render = new renderMQ();

                $form = $mq->TruncateName($secondPos);

                $salesRegion = $r->getRegion($con, array($regionID));

                $salesRegion = $salesRegion[0]['name'];

                return view('adSales.results.1monthlyPost',compact('render','region','brand','currency','value','currencyS','year','mtx','form', 'salesRegion'));
        }


        public function getQuarter(){
        	$db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = new region();
                $salesRegion = $region->getRegion($con);

                $brand = new brand();
                $brands = $brand->getBrand($con);

                $render = new Render();

                $qRender = new quarterRender();

                return view("adSales.results.2quarterGet", compact('salesRegion', 'brands', 'render', 'qRender'));
	}

	public function postQuarter(){

                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $base = new base();

                $validator = Validator::make(Request::all(),[
                        'region' => 'required',
                        'year' => 'required',
                        'brand' => 'required',
                        'secondPos' => 'required',
                        'thirdPos' => 'required',
                        'currency' => 'required',
                        'value' => 'required',
                ]);

                if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                }

                $tmp = Request::get("brand");
                $base = new base();
                $brands = $base->handleBrand($tmp);

                $b = new brand();
                $brand = $b->getBrand($con);

                $regionID = Request::get("region");
                $r = new region();
                $salesRegion = $r->getRegion($con);

                $year = Request::get("year");
                
                $currency = Request::get("currency");
                $p = new pRate();
                $pRate = $p->getCurrency($con, array($currency));

                $value = Request::get("value");

                $form = Request::get("thirdPos");
                $source = strtoupper(Request::get("secondPos"));

                $mq = new resultsMQ();
                $lines = $mq->lines($con,$pRate,$base->getMonth(),$form,$brands,$year,$regionID,$value,$source);
                //var_dump($quarter);
                $matrix = $mq->assemblerQuarters($con,$brands,$lines,$base->getMonth(),$year,$source);
                
                $render = new Render();
                $qRender = new quarterRender();

                $form = $mq->TruncateName($form);

                $region = $r->getRegion($con, array($regionID));

                $region = $region[0]['name'];

                return view("adSales.results.2quarterPost", compact('salesRegion', 'brand', 'render', 'qRender', 'matrix', 'pRate', 'value', 'year', 'form', 'region'));

	} 

    
}
