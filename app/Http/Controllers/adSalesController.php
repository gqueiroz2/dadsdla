<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;
use App\ytd;
use Excel;

class adSalesController extends Controller{
    public function home(){

/*
        $db = new dataBase();
        $con = $db->openConnection("dla");

        $ytd = new ytd();

        $region = "Brazil";

        $base = $ytd->get($con,"ytd_2019",$region);
*/
        return view("adSales.home"/*,compact("base","region")*/);
    }

    public function import(){
        
    }
}
