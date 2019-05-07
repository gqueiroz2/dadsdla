<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\renderYoY;
use App\region;
use App\dataBase;
use App\salesRep;
use App\pRate;

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

    public function firstPosByRegion(){

        $year = Request::get("year");
        $year -= 1;

        $form = Request::get("form");

        echo "<select name='font' style='width:100%;'>";
            echo "<option value='$form'> ($form) $year </option>";
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


    public function salesRepGroupByRegion(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $regionID = array(Request::get('regionID'));
        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
        if($salesRepGroup){
            echo "<option value=''> Select </option>";
            echo "<option value='all'> All </option>";
            for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
                echo "<option value='".$salesRepGroup[$s]["id"]."'>"
                    .$salesRepGroup[$s]["name"].
                "</option>";
            }
        }else{
            echo "<option value=''> There is no Sales Rep. Groups for this region. </option>";
        }
    }

    public function salesRepBySalesRepGroup(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $salesRepGroupID = array( Request::get('salesRepGroupID') );         

        if ($salesRepGroupID[0] == 'all') {
            $regionID = array($regionID);
            $salesRep = $sr->getSalesRepByRegion($con,$regionID);
        }else{
            $salesRep = $sr->getSalesRep($con,$salesRepGroupID);
        }



         if($salesRep){
            echo "<option value=''> Select </option>";
            echo "<option value='all'> All </option>";
            for ($s=0; $s < sizeof($salesRep); $s++) { 
                echo "<option value='".$salesRep[$s]["id"]."'>"
                    .$salesRep[$s]["salesRep"].
                "</option>";
            }
        }else{
            echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
        }
    }


    public function currencyByRegion(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        
        $pr = new pRate();
        $regionID = array(Request::get('regionID'));
        
        $currency = $pr->getCurrencyByRegion($con,$regionID);

        if ($currency) {
            for ($c=0; $c <sizeof($currency); $c++) {
                if ($currency[$c]["name"] != "USD" ) {
                    echo "<option value='".$currency[$c]["id"]."'>".$currency[$c]["name"]."</option>";
                }
            }
            echo "<option value='USD'>USD</option>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
        //echo $regionID;
    }

    public function sourceByRegion(){
        $region = Request::get('regionID');
        if ($region == 1) {
            echo "<option value=''> Select </option>";
            echo "<option value='IBMS'> IBMS </option>";
            echo "<option value='CMAPS'> CMAPS </option>";
        }else{
            echo "<option value=''> Select </option>";
            echo "<option value='IBMS'> IBMS </option>";
            echo "<option value='Header'> Header </option>";
        }
    }
}
