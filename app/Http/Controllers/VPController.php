<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\region;
use App\Render;
use App\salesRep;
use App\pRate;
use App\dataBase;

class VPController extends Controller
{
    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new Render();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.VPView.get',compact('render','region','currency'));
    }

    public function post(){

    }
}
