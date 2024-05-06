<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\base;
use App\Render;
use App\baseRender;
use App\insightsRender;

use App\region;
use App\brand;
use App\agency;
use App\salesRep;
use App\cmaps;

use App\viewer;
use App\insights;
use App\packets;
use App\pipeline;


use App\sql;
use App\pRate;
use App\dataBase;


class viewerController extends Controller{

    public function saveCMAPSReadGet(){
        var_dump("GET");

        return view("adSales.viewer.saveReadGet");
    }

    public function saveCMAPSReadPost(){
        var_dump("POST");

        var_dump(Request::all());
    }

    
	public function baseGet(){
	
        $bs = new base();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $permission = Request::session()->get('userLevel');
        $regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');
        //var_dump($user);

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();
        $bRender = new baseRender();

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $currentMonth = date('m');
        $cYear = date('Y');
        $nYear = $cYear + 1;
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;
        $pppYear = $ppYear - 1;

         $b = new brand();

        $brand = $b->getBrand($con);
        
        if($currentMonth == 12){
            $year = array($cYear,$nYear,$pYear,$ppYear,$pppYear);           
        }else{
            $year = array($cYear,$pYear,$ppYear,$pppYear);           
        }

        $v = new viewer();

        return view("adSales.viewer.baseGet",compact("render","bRender","years","region","currency","currencies", "permission", "user","year",'bs','brand'));
	}


	public function basePost(){

        $render =  new Render();
        $bRender = new baseRender();
        $base = new base();
        $months = $base->month;
        $viewer = new viewer();
	
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'sourceDataBase' => 'required',
            'year' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'salesRep' => 'required',
            'currency' => 'required',
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

        $source = Request::get("sourceDataBase");
        $month = Request::get("month");
        $platform = Request::get("platform");
        $company = Request::get("company");
        $value = Request::get("value");
        $year = Request::get("year");
        $salesRep = Request::get("salesRep");       
        $agency = Request::get("agency");
        $client = Request::get("client");
        $check = false;
        $sizeOfClient = Request::get("sizeOfClient");
        $manager = Request::get("director");
        $permission = Request::session()->get('userLevel');
        $regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');


        $companyString = $base->arrayToString($company,false,0);        
        $monthString = $base->arrayToString($month,false,false);
        $salesRepString = $base->arrayToString($salesRep,false,false);
        //$stageString = $base->arrayToString($stage,false,false);
        $clientString = $base->arrayToString($client,false,0);
        $agencyString = $base->arrayToString($agency,false,0);
        $managerString = $base->arrayToString($manager,false,0);
        $platformString = $base->arrayToString($platform,false,0);

        if($sizeOfClient == sizeof($client)){
            $checkClient = true;
        }else{
            $checkClient = false;    
        }
        //var_dump($source);

        
        $table = $viewer->getTables($con,$source,$monthString,$companyString,$year,$salesCurrency,$salesRepString,$db,$sql,$agencyString,$clientString,$checkClient,$managerString,$platformString);
        
        
       // var_dump($table[0]);
        //$total = $viewer->total($con,$sql,$source,$company,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies,$salesRegion,$agency,$client);
        
        $total = $viewer->totalFromTable($con,$table,$source,$salesRegion,$currencies);
        //var_dump($table[0]);
        $mtx = $viewer->assemble($table,$salesCurrency,$source,$con,$salesRegion,$currencies);
        
        $regionExcel = $salesRegion;
        $sourceExcel = $source;
        $yearExcel = $year;
        $monthExcel = $monthString;   
        $salesRepExcel = $salesRepString;
        $managerExcel = $managerString;
        $agencyExcel = $agencyString;
        $clientExcel = $clientString;
        $currencyExcel = $salesCurrency;
        $valueExcel = $value;
        $userRegionExcel = $regionName;
        $platformExcel = $platformString;
        $companyExcel = $companyString;

        
        $title = $source." - Viewer Base";
        $titleExcel = $source." - Viewer Base.xlsx";
        $titlePdf = $source." - Viewer Base.pdf";

         return view("adSales.viewer.basePost", compact("years","render","bRender", "salesRep", "region","salesCurrency","currencies","brands","viewer","mtx","months","value","source","regions","year","total","regionExcel","sourceExcel","yearExcel","monthExcel","salesRepExcel","agencyExcel","clientExcel","currencyExcel","valueExcel", "title", "titleExcel", "titlePdf","base","userRegionExcel", "managerExcel", "user", "permission",'platformExcel','companyExcel','company','platform'));

	}


    public function packetsGet(){
        $bs = new base();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $base = new base();

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $b = new brand();
        $brand = $b->getBrand($con);

        $p = new packets();

        return view("adSales.viewer.packetsGet",compact("render","years","region","brand","base"));
    }

    public function packetsPost(){
       // var_dump(Request::all());

        $render =  new Render();
        $inRender =  new insightsRender();
        $base = new base();
        $sr = new salesRep();
        $months = $base->month;

        $in = new insights();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $totalPerPacket['digital'] = 0;
        $totalPerPacket['tv'] = 0;
        $totalPerPacket['wbd'] = 0;
        $total['dsc_digital'] = 0;
        $total['dsc_tv'] = 0;
        $total['total_tv'] = 0;
        $total['total_digital'] = 0;
        $total['wm_digital'] = 0;
        $total['wm_tv'] = 0;
        $total['spt_tv'] = 0;
        $total['spt_digital'] = 0;
        $total['wbd_max'] = 0;
        $total['total'] = 0;

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        $rep2 = $sr->getSecondRepPackets($con, array($salesRegion),false, $year);
        $noRep['id'] = '10';
        $noRep['salesRep'] = 'Não';
        $noRep['salesRepGroup'] = 'Bruno Paula';
        $noRep['region'] = 'Brazil';

        array_unshift($rep2,$noRep);
        //var_dump($rep);
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

        $b = new brand();
        $brand = $b->getBrand($con);

        $p = new packets();

        $info = $p->getOptions($con);
        $table = $p->table($con,$sql);

        if ($table != false) {
             $totalPerPacket['tv'] = $p->makeTotal($table,'tv');
             $totalPerPacket['digital'] = $p->makeTotal($table,'digital');
             $totalPerPacket['wbd'] = $totalPerPacket['tv'] + $totalPerPacket['digital'];

            for ($t=0; $t <sizeof($totalPerPacket['tv']); $t++) { 
                $total['dsc_tv'] += $table[$t]['dsc_tv'];
                $total['dsc_digital'] += $table[$t]['dsc_digital'];
                $total['wm_tv'] += $table[$t]['wm_tv'];
                $total['wm_digital'] += $table[$t]['wm_digital'];
                $total['spt_tv'] += $table[$t]['spt_tv'];
                $total['spt_digital'] += $table[$t]['spt_digital'];
                $total['wbd_max'] += $table[$t]['wbd_max'];
                $total['total_tv'] += $totalPerPacket['tv'][$t];
                $total['total_digital'] += $totalPerPacket['digital'][$t];
                $total['total'] = $total['total_tv'] + $total['total_digital'];
            }


        }

         $title = "Closed packets";
         $titleExcel = "Closed packets.xlsx";

        return view("adSales.viewer.packetsPost",compact("render","years","region","brand","info",'rep','rep2','table','base','total','totalPerPacket', 'title','titleExcel'));

    }

    public function savePackets(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $p = new packets();
        $render =  new Render();
        $inRender =  new insightsRender();
        $base = new base();
        $sr = new salesRep();
        $r = new region();
        $months = $base->month;
        $b = new brand();
        $brand = $b->getBrand($con);
        $p = new packets();        

        $sql = new sql();
        $totalPerPacket['digital'] = 0;
        $totalPerPacket['tv'] = 0;
        $totalPerPacket['wbd'] = 0;
        $total['dsc_digital'] = 0;
        $total['dsc_tv'] = 0;
        $total['total_tv'] = 0;
        $total['total_digital'] = 0;
        $total['wm_digital'] = 0;
        $total['wm_tv'] = 0;
        $total['spt_tv'] = 0;
        $total['spt_digital'] = 0;
        $total['wbd_max'] = 0;
        $total['total'] = 0;

        $saveInfo = Request::all();
        unset($saveInfo['_token']);
        //var_dump($saveInfo['newProject']);
        if ($saveInfo['newClient'] != null) {
            $p->insertNewLines($con,$sql,$saveInfo);    
        }

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        $table = $p->table($con,$sql);

        if ($table != false) {
            if (!$saveInfo['newClient']) {
                for ($t=0; $t <sizeof($table) ; $t++) { 
                    $saveInfo['dsc_tv-'.$t] = str_replace('.', '', $saveInfo['dsc_tv-'.$t]);
                    $saveInfo['dsc_digital-'.$t] = str_replace('.', '', $saveInfo['dsc_digital-'.$t]);
                    $saveInfo['wm_tv-'.$t] = str_replace('.', '', $saveInfo['wm_tv-'.$t]);
                    $saveInfo['wm_digital-'.$t] = str_replace('.', '', $saveInfo['wm_digital-'.$t]);
                    $saveInfo['spt_tv-'.$t] = str_replace('.', '', $saveInfo['spt_tv-'.$t]);
                    $saveInfo['spt_digital-'.$t] = str_replace('.', '', $saveInfo['spt_digital-'.$t]);
                    $saveInfo['wbd_max-'.$t] = str_replace('.', '', $saveInfo['wbd_max-'.$t]);


                    $p->updateLines($con,$sql,$saveInfo['ID-'.$t],$saveInfo['register-'.$t],$saveInfo['product-'.$t],$saveInfo['letter-'.$t],$saveInfo['cluster-'.$t],$saveInfo['project-'.$t],$saveInfo['client-'.$t],$saveInfo['agency-'.$t],$saveInfo['segment-'.$t],$saveInfo['ae1-'.$t],$saveInfo['ae2-'.$t],$saveInfo['dsc_tv-'.$t],$saveInfo['dsc_digital-'.$t],$saveInfo['wm_tv-'.$t],$saveInfo['wm_digital-'.$t],$saveInfo['spt_tv-'.$t],$saveInfo['spt_digital-'.$t],$saveInfo['wbd_max-'.$t],$saveInfo['startMonth-'.$t],$saveInfo['endMonth-'.$t],$saveInfo['payment-'.$t],$saveInfo['installments-'.$t],$saveInfo['quota-'.$t],$saveInfo['notes-'.$t]);
                }
            }
            //var_dump($table);
        
            $totalPerPacket['tv'] = $p->makeTotal($table,'tv');
            $totalPerPacket['digital'] = $p->makeTotal($table,'digital');
            $totalPerPacket['wbd'] = $totalPerPacket['tv'] + $totalPerPacket['digital'];

            for ($t=0; $t <sizeof($totalPerPacket['tv']); $t++) { 
                $total['dsc_tv'] += $table[$t]['dsc_tv'];
                $total['dsc_digital'] += $table[$t]['dsc_digital'];
                $total['wm_tv'] += $table[$t]['wm_tv'];
                $total['wm_digital'] += $table[$t]['wm_digital'];
                $total['spt_tv'] += $table[$t]['spt_tv'];
                $total['spt_digital'] += $table[$t]['spt_digital'];
                $total['wbd_max'] += $table[$t]['wbd_max'];
                $total['total_tv'] += $totalPerPacket['tv'][$t];
                $total['total_digital'] += $totalPerPacket['digital'][$t];
                $total['total'] = $total['total_tv'] + $total['total_digital'];
            }

            
        }
        
        $table = $p->table($con,$sql);

        $region = $r->getRegion($con,null);
        ///var_dump($salesRegion);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];     

        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        $rep2 = $sr->getSecondRepPackets($con, array($salesRegion),false, $year);
        $noRep['id'] = '10';
        $noRep['salesRep'] = 'Não';
        $noRep['salesRepGroup'] = 'Bruno Paula';
        $noRep['region'] = 'Brazil';

        array_unshift($rep2,$noRep);
        
        $info = $p->getOptions($con);

         $title = "Closed packets";
         $titleExcel = "Closed packets.xlsx";
        //var_dump($table);
        return view("adSales.viewer.packetsPost",compact("render","years","region","brand","info",'rep','rep2','table','base','total','totalPerPacket','title','titleExcel'));

    }

     public function pipelineGet(){
        $bs = new base();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $base = new base();

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $b = new brand();
        $brand = $b->getBrand($con);

        $p = new pipeline();

        return view("adSales.viewer.pipelineGet",compact("render","years","region","brand","base"));
    }

    public function pipelinePost(){
       // var_dump(Request::all());

        $render =  new Render();
        $inRender =  new insightsRender();
        $base = new base();
        $sr = new salesRep();
        $months = $base->month;

        $in = new insights();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $userLevel = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

        $sql = new sql();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
         //var_dump(Request::all());
        $totalPerPacket = 0;
        $total['digital'] = 0;
        $total['tv'] = 0;
        $total['total'] = 0;

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        if ($userLevel == 'L8') {
            $salesRep = Request::get('salesRep');
            $salesRepString = ($sr->getSalesRepByName($con,$salesRep[0]))[0]['id'];
        }else{
            $salesRep = Request::get('salesRep');  
            $salesRepString = $base->arrayToString($salesRep,false,false);  
        }
        
        $agency = Request::get('agency');
        $client = Request::get('client');
        $property = Request::get('property');
        $manager = Request::get('director');
        //var_dump($salesRep);
        /*for ($m=0; $m <sizeof($manager) ; $m++) { 
            if ($manager[$m] == 'Bruno Paula') {
                $manager[$m] = 'BP';
            }elseif ($manager[$m] == 'Fabio Morgado') {
                $manager[$m] = 'FM';
            }else{
                $manager[$m] = 'RA';
            }
        }*/
        //var_dump($manager);
        $status = Request::get('status');

        $clientString = $base->arrayToString($client,false,0);
        $agencyString = $base->arrayToString($agency,false,0);        
        $propString = $base->arrayToString($property,false,0);
        $managerString = $base->arrayToString($manager,false,0);
        $statusString = $base->arrayToString($status,false,0);
        
        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        $rep2 = $sr->getSecondRepPackets($con, array($salesRegion),false, $year);

        //echo "<pre>$clientString</pre>";
        $noRep['id'] = '289';
        $noRep['salesRep'] = 'Não';
        $noRep['salesRepGroup'] = 'Bruno Paula';
        $noRep['region'] = 'Brazil';

        array_unshift($rep2,$noRep);

        //var_dump($managerString);
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

        $b = new brand();
        $brand = $b->getBrand($con);

        $p = new pipeline();

        $info = $p->getOptions($con);
        $table = $p->table($con,$sql,$agencyString,$clientString,$salesRepString,$propString,$managerString,$statusString);

        /*for ($t=0; $t <sizeOf($table) ; $t++) { 
            var_dump($table[$t]['cluster']);
        }*/
        //var_dump($info[3]);
        //var_dump($salesRep);
        if ($table != false) {
            $totalPerPacket = $p->makeTotal($table);

            for ($t=0; $t <sizeof($totalPerPacket); $t++) { 
                $total['digital'] += $table[$t]['digital_value'];
                $total['tv'] += $table[$t]['tv_value'];
                $total['total'] += $totalPerPacket[$t];    
            }
        }

         $title = "Pipeline";
         $titleExcel = "pipeline.xlsx";

       return view("adSales.viewer.pipelinePost",compact("render","years","region","brand","info",'rep','rep2','table','base','total','totalPerPacket','title','titleExcel','clientString','agencyString','salesRep','propString','managerString','statusString','salesRepString'));

    }

    public function savePipeline(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $p = new pipeline();
        $render =  new Render();
        $inRender =  new insightsRender();
        $base = new base();
        $sr = new salesRep();
        $r = new region();
        $months = $base->month;
        $b = new brand();
        $brand = $b->getBrand($con);

        $sql = new sql();

        $total['digital'] = 0;
        $total['tv'] = 0;
        $total['total'] = 0;
        $totalPerPacket = 0;

        $saveInfo = Request::all();
        unset($saveInfo['_token']);
        $clientString = Request::get('clientString');
        $agencyString = Request::get('agencyString');

        
        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        $salesRep = Request::get('salesRep');
        $propString = Request::get('propString');
        $managerString = Request::get('managerString');
        $statusString = Request::get('statusString');
        $salesRepString = Request::get('salesRepString');
        
        $table = $p->table($con,$sql,$agencyString,$clientString,$salesRepString,$propString,$managerString,$statusString);
        //print_r($saveInfo);
        if ($table != null) {
            for ($t=0; $t <sizeof($table) ; $t++) { 
                
                $pipes[$t] = $saveInfo['pipeline-'.$t];
                $pipes[$t] = explode(',',$pipes[$t]);
                if ($saveInfo['editID'] == $pipes[$t][0]) {
                    if ($saveInfo['editClient'][0] != $pipes[$t][3]) {
                        $clientString .= " ,'";
                        $clientString .= $saveInfo['editClient'][0];
                        $clientString .= "'"; 
                    }

                    if ($saveInfo['editAgency'][0] != $pipes[$t][4]) {
                        $agencyString .= " ,'";
                        $agencyString .= $saveInfo['editAgency'][0];
                        $agencyString .= "'"; 
                    }
                }
               // $p->updateLines($con,$sql,$saveInfo['ID-'.$t],$saveInfo['cluster-'.$t],$saveInfo['project-'.$t],$saveInfo['client-'.$t],$saveInfo['agency-'.$t],$saveInfo['ae1-'.$t],$saveInfo['ae2-'.$t],$saveInfo['manager-'.$t],$saveInfo['tv-'.$t],$saveInfo['digital-'.$t],$saveInfo['startMonth-'.$t],$saveInfo['endMonth-'.$t],$saveInfo['quota-'.$t],$saveInfo['status-'.$t],$saveInfo['notes-'.$t]);
            }    
        }
        

         if ($saveInfo['newClient'][0] != 0) {
            if ($saveInfo['newAe2'][0] == '10') {
                $saveInfo['newAe2'] = $saveInfo['newAe1'];
            }

            $p->insertNewLines($con,$sql,$saveInfo);   

            $clientString .= " ,'";
            $clientString .= $saveInfo['newClient'][0];
            $clientString .= "'"; 
            $agencyString .= " ,'";
            $agencyString .= $saveInfo['newAgency'][0];
            $agencyString .= "'";
        }
        //var_dump($pipes);
        //var_dump($table);
       if (!$saveInfo['newClient'][0]) {
            if ($saveInfo['editClient'][0] != 0) {
                $saveInfo['editTv'][0] = str_replace('.', '', $saveInfo['editTv'][0]);
                $saveInfo['editDigital'][0] = str_replace('.', '', $saveInfo['editDigital'][0]);               
                //var_dump($editNotes);
               $p->updateLines($con,$sql,$saveInfo['editID'],$saveInfo['editCluster'][0],$saveInfo['editProject'][0],$saveInfo['editClient'][0],$saveInfo['editAgency'][0],$saveInfo['editAe1'][0],$saveInfo['editAe2'][0],$saveInfo['editManager'][0],$saveInfo['editTv'],$saveInfo['editDigital'],$saveInfo['editFirstMonth'][0],$saveInfo['editEndMonth'][0],$saveInfo['editQuota'][0],$saveInfo['editStatus'][0],$saveInfo['editNotes']);
            }
           
        }
            //print_r($saveInfo);       
        $table = $p->table($con,$sql,$agencyString,$clientString,$salesRepString,$propString,$managerString,$statusString);
    
        $totalPerPacket = $p->makeTotal($table);

        for ($t=0; $t <sizeof($totalPerPacket); $t++) { 
            $total['digital'] += $table[$t]['digital_value'];
            $total['tv'] += $table[$t]['tv_value'];
            $total['total'] += $totalPerPacket[$t];    
        }
        

        $region = $r->getRegion($con,null);
        ///var_dump($salesRegion);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];     

        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        $rep2 = $sr->getSecondRepPackets($con, array($salesRegion),false, $year);

        $noRep['id'] = '289';
        $noRep['salesRep'] = 'Não';
        $noRep['salesRepGroup'] = 'Bruno Paula';
        $noRep['region'] = 'Brazil';

        array_unshift($rep2,$noRep);
        
        $info = $p->getOptions($con);

        $title = "Pipeline";
        $titleExcel = "pipeline.xlsx";
       // var_dump($table);
        return view("adSales.viewer.pipelinePost",compact("render","years","region","brand","info",'rep','table','base','total','totalPerPacket','title','titleExcel','rep2','clientString','agencyString','salesRep','propString','managerString','statusString','salesRepString'));

    }

    

}
