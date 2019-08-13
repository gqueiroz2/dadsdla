<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\AE;

class AEController extends Controller{
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    public function get(){
    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.AEView.get',compact('render','region','currency'));
    }

    public function post(){

    	//var_dump(Request::all());

        $db = new dataBase(); 
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();        

        $con = $db->openConnection("DLA");        

        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $tmp = $ae->base($con,$r,$pr);

        $forRender = $tmp;
       
        $client = $tmp['client'];

        $mtx = false;

        $total2018 = array(770,750,490,2010,500,680,1000,2180,350,840,740,1930,1000,1200,1250,3450);
        $totaltotal2018 = 9570;

        for ($c=0; $c < sizeof($client); $c++) { 
            $client2018[$c] = array( 77*$c ,
                                    79*$c,
                                    81*$c,
                                    (77*$c + 79*$c + 81*$c),
                                    83*$c,
                                    85*$c,
                                    87*$c,
                                    (83*$c + 85*$c + 87*$c),
                                    89*$c,
                                    91*$c,
                                    93*$c,
                                    (89*$c + 91*$c + 93*$c),
                                    95*$c,
                                    97*$c,
                                    99*$c,
                                    (95*$c + 97*$c + 99*$c),                                    
                                ); 

            $totalClient2018[$c] = 666;   
        }

/*
        $client2018[0] = array(77,75,49,201,
            50,68,100,218
            ,35,84,74,193,
            100,120,125,345);
        $totalClient2018[0] = 957;

        $client2018[1] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[1] = 957;

        $client2018[2] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[2] = 957;

        $client2018[3] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[3] = 957;

        $client2018[4] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[4] = 957;

        $client2018[5] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[5] = 957;

        $client2018[6] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[6] = 957;

        $client2018[7] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
        $totalClient2018[7] = 957;

        $client2018[8] = array(154,150,98,402,100,136,200,436,70,168,148,386,200,240,250,690);
        $totalClient2018[8] = 1914;

        $client2018[9] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $totalClient2018[9] = 0;
*/
        $month = date('M');
        $tmp = false;
        $tfArray = array();
        $odd = array();
        $even = array();

        for ($m=0; $m <sizeof($this->month) ; $m++) { 
            if ($month == $this->month[$m]) {
                $tmp = true;
            }

            if ($tmp) {
                $tfArray[$m] = "";
                $odd[$m] = "odd";
                $even[$m] = "rcBlue";
            }else{
                $tfArray[$m] = "readonly='true'";
                $odd[$m] = "oddGrey";
                $even[$m] = "evenGrey";
            }

        }
        

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client','total2018',"totaltotal2018",'totalClient2018',"client2018","tfArray","odd","even"));
    }

}
