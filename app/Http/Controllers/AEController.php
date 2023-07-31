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
        
        $aeTable = $ae->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);
        //var_dump($aeTable['total']);
        $clientsTable = $ae->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);  
        
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";      
        //var_dump($clientsTable['clientInfo'][0]['probability']);
       return view('pAndR.AEView.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel', 'cYear','pYear','list'));
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
        $currentMonth = date('n');
        $regionID = 1;
        $salesRepID = Request::get('salesRep');
        $currencyID = '1';
        $value = 'gross';

        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        
        $list = $ae->listOFClients($con, $cYear);
               
        $newClient = $ae->getSalesRepByClient($salesRepID,$con, $sql);

        $clients = $ae->getClientByRep($con, $salesRepID, $regionID, $cYear, $pYear);

        if($saveInfo['client'][0] != null){
           // var_dump('aki');
            $test = explode(',', $saveInfo['client'][0]);
            
            $saveNewClient = $ae->newClientInclusion($con,$salesRepID,$test[0],$test[1]);

        }else{
            if ($newClient != null)  {
                $clients = array_merge($clients,$newClient);
                $clients = array_values($clients);
             }
        } //ta funcionando
        
        $company = array('3','1','2');
        $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $check = $ae->checkForecast($con, $salesRepID);//check if exists forecast for this rep in database

        for ($a=0; $a <sizeof($clients) ; $a++) { 
            $client = (int) $saveInfo['client-'.$a];
            $agency = (int) $saveInfo['agency-'.$a];
            $probability = (int) $saveInfo['probability-'.$a];

            for ($c=0; $c <sizeof($company) ; $c++) { 
                for ($m=$currentMonth-1; $m <sizeof($month) ; $m++) { 
                    $payTvForecast[$a][$c][$m] = str_replace('.', '', $saveInfo['payTvForecast-'.$a.'-'.$c.'-'.$month[$m]]);
                    $digitalForecast[$a][$c][$m] = str_replace('.', '', $saveInfo['digitalForecast-'.$a.'-'.$c.'-'.$month[$m]]);
                    
                    //insere valores de pay tv
                    $ae->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth[$m], $salesRepID, 'pay tv', $payTvForecast[$a][$c][$m],$currencyID,$probability,$check);
                    
                    //insere valores de digital
                    $ae->saveForecast($con, $client, $agency, $cYear, $value, $company[$c], $intMonth[$m], $salesRepID, 'digital', $digitalForecast[$a][$c][$m],$currencyID,$probability,$check);
                }
            }
        }

        if ($newClient != null)  {
            $clients = array_merge($clients,$newClient);
            $clients = array_values($clients);
        }        
        
        $aeTable = $ae->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);

        $clientsTable = $ae->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);   

        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";      

        return view('pAndR.AEView.post',compact('render','region','currencyID','aeTable','salesRepName','currency','value','clientsTable','salesRepID','title','titleExcel','cYear','pYear','list'));
        
    }

    

    
}