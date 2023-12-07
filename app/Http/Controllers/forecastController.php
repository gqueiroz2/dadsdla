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

        $typeMsg = false;
        $msg = "";

        return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));

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
        $list = $fcst->listOFClients($con, $cYear);
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


        $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);
        //var_dump($salesRepID);

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep']); 
        //var_dump($newClientsTable);
        $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep']);
        
       
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID', 'cYear','pYear','list','newClientsTable'));

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
        
        $cYear = date('Y');
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');
        $currentMonth = date('n');
        $regionID = 1;
        $salesRepID = Request::get('salesRep');
        $currencyID = '1';
        $value = 'gross';

        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        
        $list = $fcst->listOFClients($con, $cYear);       

        $clients = $fcst->getClientByRep($con, $salesRepID, $regionID, $cYear, $pYear);
        //print_r($saveInfo);

        $company = array('3','1','2');
        $month = date('F');
        $intMonth = date('n');
        $check = $fcst->checkForecast($con, $salesRepID);//check if exists forecast for this rep in database

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep']);  

        if($saveInfo['newClient']){            
            $newAgency = $saveInfo['newAgency'];
            $newClient = $saveInfo['newClient'];
            $newProbability = $saveInfo['newProbability'];
            
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'digital',$newProbability,$intMonth);
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'pay tv',$newProbability,$intMonth);

            $fcst->checkClient($con,$sql,$newClient,$salesRepName[0]['salesRep']);           

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
                $payTvForecast[$a][$c] = str_replace('.', '', $saveInfo['payTvForecast-'.$a.'-'.$c.'-'.$month]);
                $digitalForecast[$a][$c] = str_replace('.', '', $saveInfo['digitalForecast-'.$a.'-'.$c.'-'.$month]);

                $fcst->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth, $salesRepID, 'pay tv',$payTvForecast[$a][$c],$currencyID,$probability,$check);
                
                //insere valores de digital
                $fcst->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth, $salesRepID, 'digital',$digitalForecast[$a][$c],$currencyID,$probability,$check);
                
            }
        }

        if ($newClientsTable != null) {
            for ($t=0; $t <sizeof($newClientsTable['clientInfo']); $t++) { 
                $clientN = $saveInfo['clientNew-'.$t];
                $agencyN = $saveInfo['agencyNew-'.$t];
                $probabilityNew = (int) $saveInfo['probabilityNew-'.$t];

                for ($c=0; $c <sizeof($company) ; $c++) { 
                    $payTvForecastNew[$t][$c] = str_replace('.', '', $saveInfo['payTvForecastNew-'.$t.'-'.$c.'-'.$month]);
                    $digitalForecastNew[$t][$c] = str_replace('.', '', $saveInfo['digitalForecastNew-'.$t.'-'.$c.'-'.$month]);
                    
                }

                $fcst->saveForecastNew($con, $clientN, $agencyN, $cYear, $value, $payTvForecastNew[$t][0],$payTvForecastNew[$t][1],$payTvForecastNew[$t][2], $intMonth, $salesRepID, 'pay tv',$currencyID,$probabilityNew,$checkNew);
                    
                //insere valores de digital
                $fcst->saveForecastNew($con, $clientN, $agencyN, $cYear, $value, $digitalForecastNew[$t][0],$digitalForecastNew[$t][1],$digitalForecastNew[$t][2], $intMonth, $salesRepID, 'digital',$currencyID,$probabilityNew,$checkNew);
            }
        }
        
       /* for ($z=0; $z <sizeof($newClient) ; $z++) { 
            
            


            

        }*/
        

        /*if ($newClient != null)  {
            $clients = array_merge($clients,$newClient);
            $clients = array_values($clients);
        }*/      
        
        $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);

        $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep']);   

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep']);  
        //var_dump($newClientsTable['companyValues'][0]);
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";
        
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel','cYear','pYear','list','newClientsTable'));
        
    }
}
