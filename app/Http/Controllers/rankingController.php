<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderRanking;

class rankingController extends Controller {
    
    public function get(){
    	
    	$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brands = $b->getBrand($con);

        $render = new renderRanking();

        return view('adSales.ranking.0rankingGet', compact('salesRegion', 'currencies', 'brands', 'render'));
    }

    public function post(){
    	# code...
    }
}

