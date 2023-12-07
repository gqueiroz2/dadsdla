<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\property;
use App\sql;
use App\excel;
use Validator;

class propertyController extends Controller
{
    public function get(){
        $db = new dataBase();
        $b = new base();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $typeMsg = false;
        $msg = "";

        return view('pAndR.propertyView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
    }

    public function post(){
        $db = new dataBase();
        $b = new base();
        $r = new region();
        $pr = new pRate();
        $sr = new salesRep();
        $prop = new property();
        $render = new PAndRRender();   
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

        $regionID = Request::get('region');
        $salesRepID = Request::get('salesRep');
        $currencyID = '1';
        $value = 'gross';
        $regionName = Request::session()->get('userRegion');
        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));

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

        $table = $prop->makeRepTable($con, $cYear, $pYear, $salesRepID, $value);
        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        //var_dump($table);
       //return view('pAndR.propertyView.post',compact('render','region','currencyID','salesRepName','currency','value','salesRepID','cYear','pYear','table','intMonth'));
    }
}
