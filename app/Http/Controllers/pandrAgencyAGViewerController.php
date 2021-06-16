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
use App\AE;
use App\AgencyAGViewer;
use App\sql;
use App\excel;
use Validator;

class pandrAgencyAGViewerController extends Controller{

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

        	return view('pAndR.agencyAndAgencyGroupViewer.get2',compact('con','render','region','currency','permission','user','msg','typeMsg'));
                
                /*

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

                return view('pAndR.agencyAndAgencyGroupViewer.get2',compact('con','render','region','currency','permission','user','msg','typeMsg'));

                */
	}

	public function post(){
		
                $db = new dataBase();
                $render = new PAndRRender();
                $r = new region();
                $pr = new pRate();
                $ag = new AgencyAGViewer();        
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                
                $cYear = intval( Request::get('year') );
                $pYear = $cYear - 1;
                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);
                $permission = Request::session()->get('userLevel');
                $user = Request::session()->get('userName');

                $sr = new salesRep();
               
                $typeMsg = false;
                $msg = "";

                $temp = $ag->base($con,$r,$pr,$cYear,$pYear);

                var_dump($temp);

        	return view('pAndR.agencyAndAgencyGroupViewer.post',compact('con','render','region','currency','permission','user','msg','typeMsg'));

                /*
                $db = new dataBase();
                $render = new PAndRRender();
                $r = new region();
                $pr = new pRate();
                $ae = new AE();  
                $ag = new AgencyAGViewer();              
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                
                $cYear = intval( Request::get('year') );
                $pYear = $cYear - 1;
                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);
                $permission = Request::session()->get('userLevel');
                $user = Request::session()->get('userName');

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
                
                $tmp = $ag->baseLoad($con,$r,$pr,$cYear,$pYear);
              

                $forRender = $tmp;

                $sourceSave = $forRender['sourceSave'];
                $client = $tmp['client'];
                $agency = $tmp['agency'];
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
                $manual = $forRender['rollingFCST'];

                return view('pAndR.agencyAndAgencyGroupViewer.post2',compact('render','region','currency','forRender','client','agency',"tfArray","odd","even","error","sourceSave"));
                */

	}


    
}
