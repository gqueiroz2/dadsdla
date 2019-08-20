<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\region;
use App\PAndRRender;
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

        $content = file_get_contents('/home/dads/saida.json');  
        $contents = json_decode($content, true);  
        var_dump($contents);

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        return view('pAndR.VPView.post',compact('render','region','currency'));
    }
}
