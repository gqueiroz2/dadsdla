<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class ajaxController extends Controller{

    public function firstPosMonthly(){
        $year = Request::get("year");
        echo "<option> Selecione </option>";
        echo "<option value='target'> Target ".$year." </option>";
    }

    public function secondPosMonthly(){
        $year = Request::get("year");

        echo "<option> Selecione </option>";
        echo "<option value='ibms'> IBMS ".$year." </option>";
        echo "<option value='cmaps'> CMAPS/Header ".$year." </option>";
    }

    public function currencyByRegion(){
        $salesRegion = Request::get("salesRegion");     
        if($salesRegion == "Argentina"){
            echo "<option value='arg'> ARS </option>";
            echo "<option value='usd'> USD </option>";
        }if($salesRegion == "Brazil"){
            echo "<option value='brl'> BRL </option>";
            echo "<option value='usd'> USD </option>";
        }if($salesRegion == "Colômbia"){
            echo "<option value='cop'> COP </option>";
            echo "<option value='usd'> USD </option>";
        }if($salesRegion == "México"){
            echo "<option value='mxn'> MXN </option>";
            echo "<option value='usd'> USD </option>";
        }if($salesRegion == "Pan-Regional"){
            echo "<option value='usd'> USD </option>";
        }
    }
}
