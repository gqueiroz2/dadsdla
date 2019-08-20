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
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();        

        $con = $db->openConnection("DLA");        

        $cYear = intval(Request::get('year'));

        $pYear = $cYear - 1;


        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $tmp = $ae->base($con,$r,$pr,$cYear,$pYear);

        $forRender = $tmp;
       
        $client = $tmp['client'];

        $tfArray = array();
        $odd = array();
        $even = array();

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even"));
    }

}
