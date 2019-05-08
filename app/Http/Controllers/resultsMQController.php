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
                $region = $r->getRegion($con,false);
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
                $brand = $b->getBrand($con);
                $currency = $pr->getCurrency($con,false);
                $regionID = Request::get('region');
                $brandID = $base->handleBrand( $con, $b ,Request::get('brand'));

                $currencyID = Request::get('currency');
                $value = Request::get('value');        
                $year = Request::get('year');
                $month = $base->getMonth();
                $firstPos = Request::get('secondPos');
                $secondPos = Request::get('thirdPos');
                $tmp = $pr->getCurrency($con,array($currencyID));
                if($tmp){$currencyS = $tmp[0]['name'];}else{$currencyS = "ND";}
                $valueS = strtoupper($value);
                $cYear = $year;
                $pYear = $cYear - 1;
                $mq = new resultsMQ();
                $lines = $mq->lines($con,$brandID,$regionID,$year,$currencyID,$value,$firstPos,$secondPos);

                $mtx = $mq->assembler($con,$b,$brandID,$lines,$month,$year);
                //var_dump($mtx);
                $render = new renderMQ();

                return view('adSales.results.1monthlyPost',compact('render','region','brand','currency','valueS','currencyS','year','mtx'));
        }


        public function getQuarter(){
	$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currentYear = intval(date('Y'));
        $years = array($currentYear, $currentYear-1);

        $brand = new brand();
        $brands = $brand->getBrand($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $render = new Render();

        $qRender = new quarterRender();

        return view("adSales.results.2quarterGet", compact('salesRegion', 'years', 'brands', 'currencies', 'render', 'qRender'));
	}

	public function postQuarter(){

	  } 

    
}
