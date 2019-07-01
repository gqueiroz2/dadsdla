<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;

class AEController extends Controller
{
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
    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $total2018 = array(770,750,490,2010,500,680,1000,2180,350,840,740,1930,1000,1200,1250,3450);
        $totaltotal2018 = 9570;

        $client2018[0] = array(77,75,49,201,50,68,100,218,35,84,74,193,100,120,125,345);
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

        return view('pAndR.AEView.post',compact('render','region','currency','total2018',"totaltotal2018",'totalClient2018',"client2018"));
    }

}
