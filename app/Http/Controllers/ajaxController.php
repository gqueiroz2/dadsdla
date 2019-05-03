<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\renderYoY;
use App\region;
use App\dataBase;

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

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $salesRegion = Request::get("region");

        $regions = new region();
        $region = $regions->getRegion($con, array($salesRegion));

        if($region[0]['name'] == "Argentina"){
            echo "<select value='currency' style='width:100%;'>";
                echo "<option value='arg'> ARS </option>";
                echo "<option value='usd'> USD </option>";
            echo "</select>";
        }if($region[0]['name'] == "Brazil"){
            echo "<select value='currency' style='width:100%;'>";
                echo "<option value='brl'> BRL </option>";
                echo "<option value='usd'> USD </option>";
            echo "</select>";
        }if($region[0]['name'] == "Colombia"){
            echo "<select value='currency' style='width:100%;'>";
                echo "<option value='cop'> COP </option>";
                echo "<option value='usd'> USD </option>";
            echo "</select>";
        }if($region[0]['name'] == "Mexico"){
            echo "<select value='currency' style='width:100%;'>";
                echo "<option value='mxn'> MXN </option>";
                echo "<option value='usd'> USD </option>";
            echo "</select>";
        }if($region[0]['name'] == "Pan-Regional"){
            echo "<select value='currency' style='width:100%;'>";
                echo "<option value='usd'> USD </option>";
            echo "</select>";
        }
    }

    public function firstPosByRegion(){

        $year = Request::get("year");
        $year -= 1;

        $form = Request::get("form");

        echo "<select name='font' style='width:100%;'>";
            echo "<option value='$form'> Real ($form) $year </option>";
        echo "</select>";   
    }

    public function secondPosByRegion(){

        $year = Request::get("year");

        echo "<select id='firstPos' value='firstPos' style='width:100%;'>";
            echo "<option value='target'> Target ".$year." </option>";
        echo "</select>";  
    }

    public function thirdPosByRegion(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $regionID = Request::get("regionID");

        $regions = new region();
        $region = $regions->getRegion($con, array($regionID));

        $year = Request::get("year");

        $renderYoY = new renderYoY();

        $renderYoY->source($region[0]['name'], $year);
    }
}
