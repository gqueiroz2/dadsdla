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
use Validator;

class AEController extends Controller{
    
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

        return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
    }

    public function post(){

        $db = new dataBase();
        $b = new base();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();     
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
        $list = $ae->listOFClients($con, $cYear);
        $cMonth = date('M');
        $cDate = date('d/m/Y');
        
        $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $cYear"));

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
        //var_dump($cMonth);
       if ($cDate >= $lastMonday) {
            if ($cMonth == 'Aug' || $cMonth == 'May' || $cMonth == 'Feb' || $cMonth == 'Sep') {
                $num = 5;
            }else{
                $num = 6;
            }
            
        }else{

            if ($cMonth == 'Aug' || $cMonth == 'May' || $cMonth == 'Feb'  || $cMonth == 'Sep') {
                $num = 5;
            }else{
                $num = 4;
            }
            
        }
       
        //var_dump($aeTable['total']);
        $clientsTable = $ae->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$cDate,$lastMonday);  

        $aeTable = $ae->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$clientsTable,$cDate,$lastMonday);

        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";      
        //var_dump($clientsTable['clientInfo'][0]['probability']);
       return view('pAndR.AEView.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel', 'cYear','pYear','list','num'));
    }
    
    public function save(){
        $db = new dataBase();
        $sql = new sql();
        $pr = new pRate();
        $r = new region();
        $render = new PAndRRender();
        $ae = new AE();
        $sr = new salesRep();
        $base = new base();
        $excel = new excel();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $saveInfo = Request::all();
        //pegando o valor certo

        $cYear = date('Y');
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');
        $cMonth = date('M');
        $cDate = date('d/m/Y');
        
        $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $cYear"));
        if ($cDate >= $lastMonday) {
            if ($cMonth == 'Aug' || $cMonth == 'May' || $cMonth == 'Feb') {
                $num = 5;
                $u = 3;
            }else{
                $num = 6;
                $u = 4;
            }
            
        }else{
             
            if ($cMonth == 'Aug' || $cMonth == 'May' || $cMonth == 'Feb') {
                $num = 5;
                $u = 2;
            }else{
                $num = 4;
                $u = 3;
            }
            
        }

        $currentMonth = date('n')+$u;
        //var_dump($currentMonth);
        $regionID = 1;
        $salesRepID = Request::get('salesRep');
        $currencyID = '1';
        $value = 'gross';

        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        
        $list = $ae->listOFClients($con, $cYear);
               
        $newClient = $ae->getSalesRepByClient($salesRepID,$con, $sql);

        $repInfo = $ae->getClientByRep($con, $salesRepID,'1', $cYear, $pYear);

        $clientsMonthly = $ae->getMonthlyClients($salesRepID,$con, $sql,$cDate,$lastMonday);    
        //print_r($saveInfo);

        if($saveInfo['client'][0] != 0){
           // var_dump('aki');
            $test = explode(',', $saveInfo['client'][0]);
            
            $saveNewClient = $ae->newClientInclusion($con,$salesRepID,$test[0],$test[1]);

            if ($clientsMonthly != null) {
                $clients = array_merge($repInfo,$clientsMonthly);
                $clients = array_unique($clients,SORT_REGULAR);
                $clients = array_values($clients);
            }else{
                $clients = $repInfo;
            }

            $clients = array_unique($clients,SORT_REGULAR);
            $clients = array_values($clients);

        }else{
            if ($clientsMonthly != null && $newClient != null) {
                //var_dump('ali');
                $temp = array_merge($clientsMonthly,$newClient);
                $clients = array_merge($repInfo,$temp);
                $clients = array_unique($clients,SORT_REGULAR);
                //array_push($clients,$newClient);
                $clients = array_values($clients);
            
            }elseif ($clientsMonthly != null) {
           // var_dump('aki');
                $clients = array_merge($repInfo,$clientsMonthly);
                $clients = array_unique($clients,SORT_REGULAR);
                $clients = array_values($clients);
            
            }elseif ($newClient != null) {
            
                $clients = array_merge($repInfo,$newClient);
                $clients = array_unique($clients,SORT_REGULAR);
                $clients = array_values($clients);
            
            }else{
            
                $clients = $repInfo;
            
            }
        } //ta funcionando
        //var_dump($clients);
        $company = array('1','2','3');
        $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        
        
        //print_r($clients);
       // var_dump($currentMonth);
        for ($a=0; $a <sizeof($clients) ; $a++) { 
            $client = (int) $saveInfo['client-'.$a];
            $agency = (int) $saveInfo['agency-'.$a];
            $probability = (int) $saveInfo['probability-'.$a];
            //var_dump($client);
            for ($c=0; $c <sizeof($company) ; $c++) { 
                for ($m=$currentMonth; $m <sizeof($month) ; $m++) { 
                    $payTvForecast[$a][$c][$m] = str_replace('.', '', $saveInfo['payTvForecast-'.$a.'-'.$c.'-'.$month[$m]]);
                    $digitalForecast[$a][$c][$m] = str_replace('.', '', $saveInfo['digitalForecast-'.$a.'-'.$c.'-'.$month[$m]]);
                    //var_dump($payTvForecast);
                    //$check = $ae->checkForecast($con, $salesRepID,$client,$agency,$company[$c], 'pay tv');//check if exists forecast for this rep in database
                    //var_dump($check);
                    //insere valores de pay tv
                    $ae->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth[$m], $salesRepID, 'pay tv', $payTvForecast[$a][$c][$m],$currencyID,$probability);
                    
                   // $check = $ae->checkForecast($con, $salesRepID,$client,$agency,$company[$c],'digital');//check if exists forecast for this rep in database
                    //insere valores de digital
                    $ae->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth[$m], $salesRepID, 'digital', $digitalForecast[$a][$c][$m],$currencyID,$probability);
                }
            }
        }

       /* if ($newClient != null)  {
            $clients = array_merge($clients,$newClient);
            $clients = array_values($clients);
        } */       
        
        //var_dump($clients);

        $clientsTable = $ae->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$cDate,$lastMonday);   

        $aeTable = $ae->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value,$clientsTable,$cDate,$lastMonday);

        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";      
        //var_dump($clientsTable['clientInfo']);
        return view('pAndR.AEView.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel','cYear','pYear','list','num'));
        
    }

    

    
}