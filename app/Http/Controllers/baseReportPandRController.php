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
use App\sql;
use App\excel;
use App\baseReportPandR;
use Validator;
use App\PAndRBaseReportRender;

class baseReportPandRController extends Controller{
    
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

		return view('pAndR.baseReport.get',compact('con','render','region','currency','permission','user'));

	}

	public function post(){

                $db = new dataBase();
                $render = new PAndRBaseReportRender();                
                $r = new region();
                $pr = new pRate();
                $br = new baseReportPandR();        
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $b = new brand();
                $brands = $b->getBrand($con);
                

                $cYear = intval( Request::get('year') );
                $pYear = $cYear - 1;
                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);
                $permission = Request::session()->get('userLevel');
                $user = Request::session()->get('userName');

                $regionID = Request::get('region');
                $salesRepID = Request::get('salesRep');
                $currencyID = Request::get('currency');
                $value = Request::get('value');
                $baseReport = Request::get('baseReport');   
                $regionName = Request::session()->get('userRegion');
             

/*
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
                $tmp = $ae->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);
*/
                //var_dump(Request::all());

                $forRender = $br->baseLoadReport($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value,$baseReport);
                
                $regionExcel = $regionID;
                $salesRepExcel = $salesRepID;
                $currencyExcel = $currencyID;
                $valueExcel = $value;
                $baseReportExcel = $baseReport;
                $yearExcel = $cYear;
                $userRegionExcel = $regionName;

                $titleExcel = "PandR - Base Report.xlsx";

                return view('pAndR.baseReport.post',compact('con','render','region','currency','permission','user','forRender','baseReport','regionExcel','salesRepExcel','currencyExcel','valueExcel','baseReportExcel','yearExcel', 'titleExcel', 'userRegionExcel'));
                

/*
                $sourceSave = $forRender['sourceSave'];
                $client = $tmp['client'];
                $tfArray = array();
                $odd = array();
                $even = array();

                $error = false;

                //lines of sales rep table
                $rollingSalesRep = $forRender['executiveRevenueCYear'];
                $pending = $forRender['pending'];
                $RFvsTarget = $forRender['RFvsTarget'];

                $yearExcel = $cYear;
                $clientExcel = $client;
                $currencyExcel = $currencyID;
                $regionExcel = $regionID;
                $valueExcel = $value;
                $salesRepExcel = Request::get("salesRep");

                $titleExcel = "PandR - AE.xlsx";

                return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even","error","sourceSave", "titleExcel", "yearExcel",'tmp', "clientExcel","currencyExcel","regionExcel","valueExcel","salesRepExcel"));
*/
	}

}
