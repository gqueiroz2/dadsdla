<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\salesRep;
use App\forecastRender;
use App\forecast;
use App\pRate;
use App\sql;
use App\base;
use App\PAndRRender;
use App\excel;
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

        $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2);    
        $year = date('Y');
        //var_dump($months);

        $typeMsg = false;
        $msg = "";

        return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg','months','year'));

    }

    public function byAEPost(){
    	$db = new dataBase();
        $b = new base();
        $r = new region();
        $pr = new pRate();
        $fcst = new forecast();
        $sr = new salesRep();
        $render = new PAndRRender();   
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $intMonth = Request::get('month');
        $year = date('Y');
        $pYear = $year - 1;
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

        $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2);   
        $monthName = $b->intToMonth2(array($intMonth)); 
        //var_dump($salesRepName);
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
        /*if ($salesRepID == '59') {
            $salesRepID = '59,7,132,224';
        }elseif ($salesRepID == '222') {
            $salesRepID = '8,221,222';
        }elseif ($salesRepID == '9') {
            $salesRepID = '9,137';
        }*/

        $listOfClients = $fcst->listOFClients($con);

        $listOfAgencies = $fcst->listOFAgencies($con);

        $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$intMonth);
        //var_dump($listOfClients);

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth); 
        //var_dump($newClientsTable);
        $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);
        
       
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID', 'year','pYear','newClientsTable','months','monthName','intMonth','listOfClients','listOfAgencies'));

    }

    public function byAESave(){
        $db = new dataBase();
        $b = new base();
        $r = new region();
        $pr = new pRate();
        $fcst = new forecast();
        $sr = new salesRep();
        $render = new PAndRRender();   
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $saveInfo = Request::all();
        $sql =  new sql();
        
        $year = date('Y');
        $pYear = $year - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');
        $currentMonth = date('n');
        $regionID = 1;
        $salesRepID = Request::get('salesRep');
        $currencyID = '1';
        $value = 'gross';
        $months = array(intval(date('n')),intval(date('n')) + 1);    
        $intMonth = Request::get('month');
        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        
        $listOfClients = $fcst->listOFClients($con);

        $listOfAgencies = $fcst->listOFAgencies($con);      

        $clients = $fcst->getClientByRep($con, $salesRepID, $regionID, $year, $pYear,$intMonth);
        //print_r($saveInfo);

        $company = array('3','1','2');
        $month = date('F');
        
        //var_dump($intMonth);
        $monthName = $b->intToMonth2(array($intMonth)); 
       // var_dump($intMonth);
        $check = $fcst->checkForecast($con, $salesRepID,$saveInfo['month']);//check if exists forecast for this rep in database

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$saveInfo['month']);  

        if ($saveInfo['clientSubmit'] != null) {
            
            $submit = $fcst->newClientRequest($con,$salesRepID,$saveinfo['clientSubmit'],$saveInfo['agencySubmit'],$month);
        }

        if($saveInfo['newClient'][0] != 0){            
            $newAgency = $saveInfo['newAgency'][0];
            $newClient = $saveInfo['newClient'][0];
            $newProbability = $saveInfo['newProbability'];
            
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'digital',$newProbability,$intMonth);
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'pay tv',$newProbability,$intMonth);

            //$fcst->checkClient($con,$sql,$newClient,$salesRepName[0]['salesRep']);           

        }

        $newClient = $fcst->getSalesRepByClient($salesRepID,$con, $sql,$salesRepName[0]['salesRep']);
        //var_dump($newClientsTable);
        //ta funcionando
       // var_dump($newClient);
        $companyName = array('wm','dc','spt');
        
        $checkNew = $fcst->checkForecastNew($con, $salesRepID);//check if exists forecast for this rep in database
        
        for ($a=0; $a <sizeof($clients) ; $a++) { 
            $client = $saveInfo['client-'.$a];
            $agency = $saveInfo['agency-'.$a];
            $probability = (int) $saveInfo['probability-'.$a];

            for ($c=0; $c <sizeof($company) ; $c++) { 
                $payTvForecast[$a][$c] = str_replace('.', '', $saveInfo['payTvForecast-'.$a.'-'.$c.'-'.$intMonth]);
                $digitalForecast[$a][$c] = str_replace('.', '', $saveInfo['digitalForecast-'.$a.'-'.$c.'-'.$intMonth]);

                $fcst->saveForecast($con, $client, $agency, $year, $value, $company[$c], $intMonth, $salesRepID, 'pay tv',$payTvForecast[$a][$c],$currencyID,$probability,$check);
                
                //insere valores de digital
                $fcst->saveForecast($con, $client, $agency, $year, $value, $company[$c], $intMonth, $salesRepID, 'digital',$digitalForecast[$a][$c],$currencyID,$probability,$check);
                
            }
        }

        if ($newClientsTable != null) {
            for ($t=0; $t <sizeof($newClientsTable['clientInfo']); $t++) { 
                $clientN = $saveInfo['clientNew-'.$t];
                $agencyN = $saveInfo['agencyNew-'.$t];
                $probabilityNew = (int) $saveInfo['probabilityNew-'.$t];

                for ($c=0; $c <sizeof($company) ; $c++) { 
                    $payTvForecastNew[$t][$c] = str_replace('.', '', $saveInfo['payTvForecastNew-'.$t.'-'.$c.'-'.$intMonth]);
                    $digitalForecastNew[$t][$c] = str_replace('.', '', $saveInfo['digitalForecastNew-'.$t.'-'.$c.'-'.$intMonth]);                    
                }

                $fcst->saveForecastNew($con, $clientN, $agencyN, $year, $value, $payTvForecastNew[$t][0],$payTvForecastNew[$t][1],$payTvForecastNew[$t][2], $intMonth, $salesRepID, 'pay tv',$currencyID,$probabilityNew,$checkNew);
                    
                //insere valores de digital
                $fcst->saveForecastNew($con, $clientN, $agencyN, $year, $value, $digitalForecastNew[$t][0],$digitalForecastNew[$t][1],$digitalForecastNew[$t][2], $intMonth, $salesRepID, 'digital',$currencyID,$probabilityNew,$checkNew);
            }
        }
             
        
        $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$intMonth);

        $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);   

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);  
        var_dump($newClientsTable);
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";
        //var_dump($newClientsTable);
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel','year','pYear','newClientsTable','months','monthName','intMonth','listOfClients','listOfAgencies'));
        
    }
}
