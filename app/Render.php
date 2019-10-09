<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\region;
use App\base;
use App\salesRep;
use App\sql;

class Render extends Model{
    
    protected $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

    public function agencyForm(){
        
        echo "<select class='selectpicker' id='agency' name='agency[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function clientForm(){
        echo "<select id='client' name='client' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function sectorForm(){
        echo "<select id='sector' name='sector' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function categoryForm(){
        echo "<select id='category' name='category' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function savedFCST($con,$permission,$userName){

        $sql = new sql();

        $sr = new salesRep();

        if( $permission == "L3" || $permission == "L4"){
            $salesRepID = $sr->getSalesRepByName($con,$userName)[0]['id'];
        }else{
            $salesRepID = false;
        }

        $select = " SELECT * FROM forecast
                            WHERE(sales_rep_id = \"".$salesRepID."\")

        ";
        $res = $con->query($select);
        $from = array('oppid','region_id','currency_id','type_of_value','read_q','year','date_m','last_modify_by','last_modify_date','last_modify_time');

        $to = array('oppid','regionID','currencyID','typeOfValue','readQ','year','dateM','lastModifyBy','lastModifyDate','lastModifyTime');

        echo "<select id='savedFCST' class='selectpicker' name='savedFCST' data-width='100%'>";
            echo "<option value=''> Select </option>";
            //echo "<option value='".$month."-".$week."'>".$month."-".$week."</option>";
        echo "</select>";

    }

    public function tiers(){
        
        echo "<select id='tier' class='selectpicker' data-selected-text-format='count' multiple='true' name='tier[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
        echo "</select>";
    }

    public function regionWI(){ // Without Input
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();

        $region = $r->getRegion($con);

        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($i = 0; $i < sizeof($region); $i++) { 
                if($region[$i]['name'] != "LATAM" ){
                    echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";
                }
            }

       echo "</select>";

    }

    public function region($region){

        $temp = array();

        for ($r=0; $r <sizeof($region) ; $r++) { 
            if ($region[$r]['role'] != "None") {
                array_push($temp, $region[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t <sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r <sizeof($region) ; $r++) { 
                if ($temp[$t] == $region[$r]['role']) {
                    array_push($tempId[$t], $region[$r]["id"]);
                    array_push($tempName[$t], $region[$r]["name"]);
                }
            }
        }

    	echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
    		echo "<option value=''> Select </option>";
            for ($t=0; $t <sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r <sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

	   echo "</select>";
    }

    public function regionFilteredReps($region,$regionFiltered){
        $b = new base();

        $regions = $b->filteredRegionReps($regionFiltered,"Reps");

        $temp = array();

        for ($r=0; $r <sizeof($regions) ; $r++) { 
            if ($regions[$r]['role'] != "None") {
                array_push($temp, $regions[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t <sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r <sizeof($regions) ; $r++) { 
                if ($temp[$t] == $regions[$r]['role']) {
                    array_push($tempId[$t], $regions[$r]["id"]);
                    array_push($tempName[$t], $regions[$r]["name"]);
                }
            }
        }

        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($t=0; $t <sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r <sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

       echo "</select>";
    }

    public function regionFiltered($region,$regionFiltered,$special){
        $b = new base();
        $regions = $b->filteredRegion($regionFiltered,$special);
        
        $temp = array();

        for ($r=0; $r <sizeof($regions) ; $r++) { 
            if ($regions[$r]['role'] != "None") {
                array_push($temp, $regions[$r]['role']);
            }
        }

        $temp = array_values(array_unique($temp));

        $tempId = array(array());
        $tempName = array(array());

        for ($t=0; $t <sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r <sizeof($regions) ; $r++) { 
                if ($temp[$t] == $regions[$r]['role']) {
                    array_push($tempId[$t], $regions[$r]["id"]);
                    array_push($tempName[$t], $regions[$r]["name"]);
                }
            }
        }

        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($t=0; $t <sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r <sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

       echo "</select>";
    }

    public function sourceDataBase(){

        $arraySource = array("CMAPS","IBMS/BTS","FW","SF");

         echo "<select id='sourceDataBase' name='sourceDataBase' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($a=0; $a <sizeof($arraySource) ; $a++) {
                echo "<option value='".$arraySource[$a]."'>".$arraySource[$a]."</option>";
            }
        echo "</select>";

    }

    public function year(){    	
    	echo "<select id='year' name='year' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
    	echo "</select>";
    }

    public function piNumber(){
        echo "<input type='text' name='PI' class='form-control'>";
    }

    public function brand($brand){

    	echo "<select id='brand' class='selectpicker' data-selected-text-format='count' multiple='true' name='brand[]' multiple data-actions-box='true' data-size='4' data-width='100%'>";
            for ($i = 0; $i < sizeof($brand); $i++) { 
                if ($brand[$i]["name"] != "DN") {
                    $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'])));
                    echo "<option selected='true' value='".$value[$i]."'>".$brand[$i]["name"]."</option>";   
                }
    		}
    		
    	echo "</select>";
    }

    public function brandPerformance(){
        
        echo "<select id='brand' class='selectpicker' data-selected-text-format='count' multiple='true' name='brand[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
        echo "</select>";   
    }

    public function position($pos){
        echo "<select id='".$pos."Pos' name='".$pos."Pos' class='form-control' style='width: 100%;'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function positionYear($pos){
        echo "<select id='".$pos."Pos' name='".$pos."Pos' class='form-control' style='width: 100%;'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }    

    public function source(){
        
        echo "<select id='source' name='source' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";   
    }

    public function source2(){
        echo "<select id='source' name='source' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            echo "<option value='sf'> Sales Force </option>";
            echo "<option value='db'> Saved </option>";
        echo "</select>";   
    }

    public function salesRepGroup($salesRepGroup){
    	echo "<select id='salesRepGroup'class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRepGroup[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
    		//echo "<option value=''> Select Region </option>";

    	echo "</select>";	

    }

    public function salesRep2(){
        echo "<select id='salesRep' name='salesRep' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function salesRep(){
    	echo "<select id='salesRep'class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRep[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
    		//echo "<option value=''> Select Region </option>";

    	echo "</select>";	

    }

    public function months(){

    	echo "<select class='selectpicker' id='month' name='month[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-size='6' data-width='100%'>";
            //echo "<option selected='true' value='all'>All</option>";
    		//echo "<option value='ytd'>YTD</option>";
    		for ($m=0; $m < sizeof($this->month); $m++) { 
    			echo "<option selected='true' value='".($m+1)."'>".$this->month[$m]."</option>";
    		}

    	echo "</select>";
    }

    public function currency(){
    	echo "<select id='currency' name='currency' style='width:100%;' class='form-control'>";
    		echo "<option value=''> Select Region </option>";            
    	echo "</select>";
    }

    public function value(){
    	echo "<select id='value' name='value' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";            
    	echo "</select>";
    }

    public function value2(){
        echo "<select id='value' name='value' style='width:100%;' class='form-control'>";
            echo "<option value='gross'> Gross </option>";            
            echo "<option value='net'> Net </option>";            
        echo "</select>";
    }

    public function plan(){
        echo "<select id='plan' name='plan' style='width:100%;' class='form-control'>";
            echo "<option value='target'> Target </option>";
        echo "</select>";
    }

    public function type(){
        echo "<select id='type' name='type' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function type2(){
        echo "<select class='selectpicker' id='type2' name='type2[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-size='3' data-width='100%'>";
                echo "<option selected='true' value=''> Select the previous field </option>";    
        echo "</select>";
    }

    public function nPos(){
        echo "<select id='nPos' name='nPos' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select type </option>";
        echo "</select>";
    }

}
