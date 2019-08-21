<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\VP;

class VPController extends Controller
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

		return view('pAndR.VPView.get',compact('render','region','currency'));
    }

    public function post(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();
        $vp = new vp();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $forRender = $vp->base($con);

        return view('pAndR.VPView.post',compact('render','region','currency'));
    }
}
