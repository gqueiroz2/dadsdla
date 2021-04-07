<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\region;
use App\PAndRRender;
use App\byBrandRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\pacingReport;
use App\byBrandReport;
use App\brand;
use App\base;
use App\AE;
use App\sql;
use App\excel;
use Validator;


class byBrandViewerController extends Controller {

	public function get(){
        	$db = new dataBase();
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

	       return view('pAndR.byBrandViewer.byBrandGet',compact('con','render','region','currency','permission','user','msg','typeMsg'));
	}
    
        public function post(){
                $db = new dataBase();
                $render = new byBrandRender();
                $r = new region();
                $pr = new pRate();
                $ae = new AE();  
                $b = new brand();
                $br = new byBrandReport();              
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                
                $cYear = intval( Request::get('year') );
                $pYear = $cYear - 1;
                //$region = intval( Request::get('region') );

                $region = $r->getRegion($con,false);
                //var_dump($region);
                $currency = $pr->getCurrency($con,false);
                $permission = Request::session()->get('userLevel');
                $user = Request::session()->get('userName');

                $validator = Validator::make(Request::all(),[
                    'region' => 'required',
                    'year' => 'required',
                    'currency' => 'required',
                    'value' => 'required'
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
                
                $tmp = $br->baseLoad($con,$r,$pr,$cYear,$pYear,$region);

                $forRender = $tmp;
                $brands = $b->getBrand($con);
                /*$sourceSave = $forRender['sourceSave'];
                $client = $tmp['client'];
                $tfArray = array();
                $odd = array();
                $even = array();

                $error = false;

                //lines of sales rep table
                $rollingSalesRep = $forRender['executiveRevenueCYear'];
                $pending = $forRender['pending'];
                $RFvsTarget = $forRender['RFvsTarget'];

                //lines of clients table
                $rollingClients = $forRender['lastRollingFCST'];
                $manual = $forRender['rollingFCST'];*/

                return view('pAndR.byBrandViewer.byBrandPost',compact('render','region','currency','forRender', /*'tfArray','odd','even','sourceSave','client','error',*/'brands'));
        }
}
