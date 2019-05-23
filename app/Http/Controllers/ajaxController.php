<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\renderYoY;
use App\region;
use App\dataBase;
use App\salesRep;
use App\pRate;
use App\sql;
use App\brand;

class ajaxController extends Controller{

    public function tierByRegion(){

        echo "<option selected='true' value='1'>T1</option>";
        echo "<option selected='true' value='2'>T2</option>";
        echo "<option selected='true' value='3'>OTH</option>";
    }

    public function brandsByTier(){
        
        $tiers = Request::get('tiers');

        if (is_null($tiers)) {
            
        }else{
            $db = new dataBase();
            $con = $db->openConnection("DLA");

            $b = new brand();
            $brands = $b->getBrand($con);
            for ($t=0; $t < sizeof($tiers); $t++) {
                for ($b=0; $b < sizeof($brands); $b++) { 
                    $value[$b] = base64_encode(json_encode(array($brands[$b]['id'],$brands[$b]['name'])));
                    if ($tiers[$t] == '1') {
                        if ($brands[$b]['name'] == 'DC' || $brands[$b]['name'] == 'HH' || $brands[$b]['name'] == 'DK'){
                            echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";            
                        }
                    }elseif ($tiers[$t] == '2') {
                        if ($brands[$b]['name'] == 'AP' || $brands[$b]['name'] == 'TLC' || $brands[$b]['name'] == 'ID' || $brands[$b]['name'] == 'DT' || $brands[$b]['name'] == 'FN' || $brands[$b]['name'] == 'ONL' || $brands[$b]['name'] == 'VIX' || $brands[$b]['name'] == 'HGTV'){
                                echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";               
                        }
                    }else{
                        if ($brands[$b]['name'] == "OTH") {
                            echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";    
                        }
                        
                    }
                }  

            }    
        }
        
    }

    public function yearByRegion(){
        
        $regionID = Request::get('regionID');
        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;
        if($regionID == 1){
            $year = array($cYear,$pYear,$ppYear);           
        }else{
            $year = array($cYear);
        }
        for ($y=0; $y < sizeof($year); $y++) { 
            if($y == 0){
                echo "<option selected='true' value='".$year[$y]."'> ".$year[$y]." </option>";    
            }else{
                echo "<option value='".$year[$y]."'> ".$year[$y]." </option>";    
            }   
        }        

    }

    public function firstPosByRegion(){

        $form = Request::get("form");

        
        if($form == "ytd"){
            $showForm = "IBMS";
        }elseif($form == "cmaps"){
            $showForm = "CMAPS";
        }elseif($form == "mini_header"){
            $showForm = "HEADER";
        }else{
            $showForm = false;
        }
        

        echo "<select name='font' style='width:100%;'>";
            echo "<option value='$form'> $showForm </option>";
        echo "</select>";   
    }

    public function secondPosByRegion(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $sql = new sql();

        $source = $sql->select($con, "DISTINCT source", "plan_by_brand");
        $valueSource = $sql->fetch($source, array("source"), array("source"));
        
        echo "<select id='firstPos' value='firstPos' style='width:100%;'>";
        for ($i=0; $i < sizeof($valueSource); $i++) {
            $showSource = strtolower($valueSource[$i]['source']);
            $showSource = ucfirst($showSource);
            if ($valueSource[$i]['source'] != 'ACTUAL') {
                if($valueSource[$i]['source'] == "TARGET"){
                    echo "<option value='".$valueSource[$i]['source']."' selected='true'> ".$showSource." </option>";
                }else{
                    echo "<option value='".$valueSource[$i]['source']."'> ".$showSource." </option>";
                } 
            }
        }
        echo "</select>";  
    }

    public function thirdPosByRegion(){
        
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $regionID = Request::get("regionID");

        $regions = new region();
        $region = $regions->getRegion($con, array($regionID));

        $renderYoY = new renderYoY();

        $renderYoY->source($region[0]['name']);
    }


    public function salesRepGroupByRegion(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $regionID = array(Request::get('regionID'));
        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
        $userLevel = Request::session()->get('userLevel');

        if ($userLevel == "L3" || $userLevel == "L4") {
            $groupID = Request::session()->get('userSalesRepGroupID');
            $groupName = Request::session()->get('userSalesRepGroup');
            echo "<option value='".$groupID."' selected='true'>".$groupName."</option>";
        }else{
            if($salesRepGroup){
                for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
                    echo "<option value='".$salesRepGroup[$s]["id"]."' selected='true'>"
                        .$salesRepGroup[$s]["name"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. Groups for this region. </option>";
            }
        }

        
    }

    public function salesRepBySalesRepGroup(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $salesRepGroupID = Request::get('salesRepGroupID');         
        $year = Request::get('year');        
        $source = Request::get('source');
        $userLevel = Request::session()->get('userLevel');

        $salesRep = $sr->getSalesRep($con,$salesRepGroupID);

        $salesRep = $sr->getSalesRepStatus($con,$salesRep,$year);
        if ($userLevel == "L4") {
            $userName = Request::session()->get('userName');
            $check = false;            
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                if($salesRep[$s]["salesRep"] == $userName){
                    echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                    $check = true;
                }
            }
            if (!$check) {
                echo "<option value=''> Sales Rep Not Found </option>";
            }
        }else{

            if($salesRep){
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    echo "<option value='".$salesRep[$s]["id"]."' selected='true'>"
                        .$salesRep[$s]["salesRep"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
            }
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
            echo "<option value='4'>USD</option>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
        //echo $regionID;
    }

    public function sourceByRegion(){
        $region = Request::get('regionID');
        if ($region == 1) {
            echo "<option value='IBMS'> IBMS </option>";
            echo "<option value='CMAPS'> CMAPS </option>";
        }else{
            echo "<option value='IBMS'> IBMS </option>";
            echo "<option value='Header'> Header </option>";
        }
    }

    public function valueBySource(){
        $source = Request::get('source');
        //var_dump($source);

        if ($source == "mini_header" || $source == "Header") {
            echo "<option value='gross'> Gross </option>";
        }else{
            echo "<option value='gross'> Gross </option>";
            echo "<option value='net'> Net </option>";
        }    
    }
}
