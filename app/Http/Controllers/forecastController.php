<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\salesRep;
use App\forecastRender;
use App\forecast;
use App\pRate;
use Validator;

class forecastController extends Controller{
    
    public function byAEGet(){

    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new forecastRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $typeMsg = false;
        $msg = "";

        return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));

    }

    public function byAEPost(){
    	var_dump("POST");

    	$db = new dataBase();
        $render = new forecastRender();
        $r = new region();
        $pr = new pRate();
        $fcst = new forecast();        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $typeMsg = false;
        $msg = "";

        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

        var_dump($permission);
        var_dump($user);

        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'salesRep' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tmp = $fcst->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);
        $forRender = $tmp;
       
        return view('pAndR.forecastByAE.post',compact('render','region','currency','forRender','msg','typeMsg'));

    }
}
