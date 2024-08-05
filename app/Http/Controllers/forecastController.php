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
        $cMonth = date('M');
        $year = date('Y');
        $cDate = date('d/m/Y');
        
         $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $year"));
        if ($cDate >= $lastMonday) {
            $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3); 
        }else{
            $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2); 
        }         
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

        //var_dump($salesRepID);
        $currencyID = '1'; 
        $value = 'gross';
        $regionName = Request::session()->get('userRegion');
        $cMonth = date('M');
        $cDate = date('d/m/Y');
        
         $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $year"));
        if ($cDate >= $lastMonday) {
            $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3); 
        }else{
            $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2); 
        } 
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

         if ($permission == 'L8') {
            $salesRep = Request::get('salesRep');
            $salesRepID = $sr->getSalesRepByName($con,$salesRep)[0]['id'];

            $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRep,$intMonth);

            $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRep,$intMonth); 
          
            if ($clientsTable != 'THERE IS NO INFORMATION TO THIS REP' || $newClientsTable != null) {
               //var_dump('aki');
                $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRep,$intMonth,$newClientsTable,$clientsTable);

            }else{
                $aeTable = 0;
            }

            //var_dump($salesRepName);
            $salesRepName[0]['salesRep'] = $salesRep;
            //var_dump($salesRepName); 
        }else{
            $salesRepID = Request::get('salesRep');
            $salesRepName = $sr->getSalesRepById($con,array($salesRepID));

            $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);

            $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth); 
             // var_dump($newClientsTable);
            if ($clientsTable != 'THERE IS NO INFORMATION TO THIS REP' || $newClientsTable != null) {
               //var_dump('aki');
                $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth,$newClientsTable,$clientsTable);
            }else{
                
                $aeTable = 0;
            }
        }
       // var_dump($aeTable);
       
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";   

        
       
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID', 'year','pYear','newClientsTable','months','monthName','intMonth','listOfClients','listOfAgencies','title','titleExcel','permission'));

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

       /*if ($permission == 'L8') {
            $salesRepName = Request::get('salesRep');
            $salesRepID = $sr->getSalesRepByName($con,$salesRepName)[0]['id'];
        }else{*/
            $salesRepID = Request::get('salesRep');
            $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        //}
        $currencyID = '1';
        $value = 'gross';
        $cMonth = date('M');
        $cDate = date('d/m/Y');
         $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $year"));
        if ($cDate >= $lastMonday) {
            $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3); 
        }else{
            $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2); 
        } 
        
        $intMonth = Request::get('month');
    
        $listOfClients = $fcst->listOFClients($con);

        $listOfAgencies = $fcst->listOFAgencies($con); 
        //var_dump($salesRepName);     

        $clients = $fcst->getClientByRep($con, $salesRepID, $regionID, $year, $pYear,$intMonth);

       
        //print_r($saveInfo);

        $company = array('1','2','3');
        $month = date('F');
        
        //var_dump($intMonth);
        $monthName = $b->intToMonth2(array($intMonth)); 
       // var_dump($intMonth);
       

       /* if ($permission == 'L8') {
            $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName,$saveInfo['month']);  
        }else{*/
                  
       // }
        

        if ($saveInfo['clientSubmit'] != null) {
            //var_dump($saveInfo['clientSubmit']);
            $submit = $fcst->newClientRequest($con,$salesRepID,$saveInfo['clientSubmit'],$saveInfo['agencySubmit'],$intMonth);
        }

        if($saveInfo['newClient'][0] != 0){            
            $newAgency = $saveInfo['newAgency'][0];
            $newClient = $saveInfo['newClient'][0];
            $newProbability = $saveInfo['newProbability'];
            
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'digital',$newProbability,$intMonth);
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'pay tv',$newProbability,$intMonth);

            //$fcst->checkClient($con,$sql,$newClient,$salesRepName[0]['salesRep']);           

        }

        if($saveInfo['clientPost'][0] != 0){            
            $newAgency = $saveInfo['agencyPost'][0];
            $newClient = $saveInfo['clientPost'][0];
            $newProbability = $saveInfo['newProbability'];
            
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'digital',$newProbability,$intMonth);
            $saveNewClient = $fcst->newClientInclusion($con,$salesRepID,$newClient,$newAgency,$saveInfo['wm'],$saveInfo['spt'],$saveInfo['dc'],'pay tv',$newProbability,$intMonth);

            //$fcst->checkClient($con,$sql,$newClient,$salesRepName[0]['salesRep']);           

        }

        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$saveInfo['month']);
        /*if ($permission == 'L8') {
            $newClient = $fcst->getSalesRepByClient($salesRepID,$con, $sql,$salesRepName,$intMonth);
        }else{*/
            $newClient = $fcst->getSalesRepByClient($salesRepID,$con, $sql,$salesRepName[0]['salesRep'],$intMonth);
        //}

      /*  for ($a=0; $a <sizeof($clients) ; $a++) { 
            for ($aa=0; $aa <sizeof($newClient) ; $aa++) { 
                 if ($clients[$a]['clientID'] == $newClient[$aa]['clientID']) {
                    unset($clients[$a]);
                    $clients = array_values($clients);
                        
                }
                           
            }
        }*/  
        $companyName = array('wm','dc','spt');
        $check = $fcst->checkForecast($con, $salesRepID,$saveInfo['month']);
        $checkNew = $fcst->checkForecastNew($con, $salesRepID);//check if exists forecast for this rep in database

        if ($clients != 'THERE IS NO INFORMATION TO THIS REP') {
            for ($a=0; $a <sizeof($clients) ; $a++) { 
                $client = $saveInfo['client-'.$a];
                $agency = $saveInfo['agency-'.$a];
                $probability = (int) $saveInfo['probability-'.$a];
               //check if exists forecast for this rep in database

                for ($c=0; $c <sizeof($company) ; $c++) { 
                    $payTvForecast[$a][$c] = str_replace('.', '', $saveInfo['payTvForecast-'.$a.'-'.$c.'-'.$intMonth]);
                    $digitalForecast[$a][$c] = str_replace('.', '', $saveInfo['digitalForecast-'.$a.'-'.$c.'-'.$intMonth]);

                    $fcst->saveForecast($con, $client, $agency, $year, $value, $company[$c], $intMonth, $salesRepID, 'pay tv',$payTvForecast[$a][$c],$currencyID,$probability,$check);
                    
                    //insere valores de digital
                    $fcst->saveForecast($con, $client, $agency, $year, $value, $company[$c], $intMonth, $salesRepID, 'digital',$digitalForecast[$a][$c],$currencyID,$probability,$check);
                    
                }
            }
        }
      // var_dump($saveInfo['newClient'][0]);
        if ($saveInfo['clientPost'][0] == '0') {
            if ($saveInfo['newClient'][0] == '0') {
                if($newClientsTable['clientInfo'][0]['clientID'] != null){
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
            }     
   
        }  
          
       
        /*if ($permission == 'L8') {
            $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName,$intMonth);   

            $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName,$intMonth); 

            $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName,$intMonth,$newClientsTable,$clientsTable);    
        }else{*/
            $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);   

            $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth); 

           
            $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth,$newClientsTable,$clientsTable);
        //}
        
        //var_dump($newClientsTable);
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";
        //var_dump($newClientsTable);
       return view('pAndR.forecastByAE.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel','year','pYear','newClientsTable','months','monthName','intMonth','listOfClients','listOfAgencies','permission'));
        
    }
}
