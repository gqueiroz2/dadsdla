<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\region;
use App\base;
use App\salesRep;
use App\Http\Controllers\ajaxController;
use App\sql;

class Render extends Model{
    
    protected $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

    protected $stageFCST = array(' 1 - Qualification','2 - Proposal','3 - Negotiation','4 - Verbal');
    protected $manager = array('BP','FM','RA','VV','REGIONAIS');

    public function agencyForm(){
        
        echo "<select class='selectpicker agencyChange' id='agency' name='agency[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' data-live-search='true'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function agencyGroupForm(){
        
        echo "<select class='selectpicker agencyChange' id='agencyGroup' name='agencyGroup' data-selected-text-format='count' data-width='100%' data-live-search='true'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function clientForm(){
        echo "<select class='selectpicker' id='client' name='client[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>";
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
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $r = new region();

        $region = $r->getRegion($con);

        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            //echo "<option value=''> Select </option>";
            for ($i = 0; $i < sizeof($region); $i++) { 
                if($region[$i]['name'] != "LATAM" ){
                    echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";
                }
            }

       echo "</select>";

    }

    public function region($region){

        $temp = array();

        for ($r=0; $r < sizeof($region) ; $r++) { 
            if ($region[$r]['role'] != "None") {
                array_push($temp, $region[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t < sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r < sizeof($region) ; $r++) { 
                if ($temp[$t] == $region[$r]['role']) {
                    array_push($tempId[$t], $region[$r]["id"]);
                    array_push($tempName[$t], $region[$r]["name"]);
                }
            }
        }

    	echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
    		echo "<option value=''> Select </option>";
            for ($t=0; $t < sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r < sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

	   echo "</select>";
    }

    public function regionOffice($region){

        $temp = array();

        for ($r=0; $r < sizeof($region) ; $r++) { 
            if ($region[$r]['role'] != "None") {
                array_push($temp, $region[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t < sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r < sizeof($region) ; $r++) { 
                if ($temp[$t] == $region[$r]['role']) {
                    array_push($tempId[$t], $region[$r]["id"]);
                    array_push($tempName[$t], $region[$r]["name"]);
                }
            }
        }

        echo "<select class='selectpicker' id='region' name='region[]' data-selected-text-format='count' multiple='true' multiple data-actions-box='true' data-width='100%' style='z-index: 999;'>";
            for ($t=0; $t < sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r < sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."' selected='true'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }
       echo "</select>";
    }

    public function regionArray($region){

        $temp = array();

        for ($r=0; $r < sizeof($region) ; $r++) { 
            if ($region[$r]['role'] != "None") {
                array_push($temp, $region[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t < sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r < sizeof($region) ; $r++) { 
                if ($temp[$t] == $region[$r]['role']) {
                    array_push($tempId[$t], $region[$r]["id"]);
                    array_push($tempName[$t], $region[$r]["name"]);
                }
            }
        }

        echo "<select id='region' name='region[]' style='width:100%;' class='selectpicker' data-selected-text-format='count' multiple='true' multiple data-actions-box='true' data-width='100%'>";
            for ($t=0; $t < sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r < sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."' selected='true'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

       echo "</select>";
    }

    public function regionFiltered($region,$regionFiltered,$special){
        $b = new base();
        $regions = $b->filteredRegion($regionFiltered,$special);
        
        $temp = array();
        for ($r=0; $r < sizeof($regions) ; $r++) { 
            if ($regions[$r]['role'] != "None") {
                array_push($temp, $regions[$r]['role']);
            }
        }
        $temp = array_values(array_unique($temp));
        $tempId = array(array());
        $tempName = array(array());
        for ($t=0; $t < sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r < sizeof($regions) ; $r++) { 
                if ($temp[$t] == $regions[$r]['role']) {
                    array_push($tempId[$t], $regions[$r]["id"]);
                    array_push($tempName[$t], $regions[$r]["name"]);
                }
            }
        }
        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($t=0; $t < sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r < sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[0][0]."' selected='true'>".$tempName[0][0]."</option>";
                    }
                echo "</optgroup>";
            }
       echo "</select>";
    }    

    public function regionFilteredReps($region,$regionFiltered){
        $b = new base();

        $regions = $b->filteredRegionReps($regionFiltered,"Reps");

        $temp = array();

        for ($r=0; $r < sizeof($regions) ; $r++) { 
            if ($regions[$r]['role'] != "None") {
                array_push($temp, $regions[$r]['role']);
            }
        }

        $temp = array_unique($temp);

        $temp = array_values($temp);

        $tempId = array(array());
        $tempName = array(array());


        for ($t=0; $t < sizeof($temp); $t++) {
            $tempId[$t] = array();
            $tempName[$t] = array();
            for ($r=0; $r < sizeof($regions) ; $r++) { 
                if ($temp[$t] == $regions[$r]['role']) {
                    array_push($tempId[$t], $regions[$r]["id"]);
                    array_push($tempName[$t], $regions[$r]["name"]);
                }
            }
        }

        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";
            for ($t=0; $t < sizeof($temp) ; $t++) { 
                echo "<optgroup label='".$temp[$t]."'>";
                    for ($r=0; $r < sizeof($tempId[$t]) ; $r++) {
                        echo "<option value='".$tempId[$t][$r]."'>".$tempName[$t][$r]."</option>";
                    }
                echo "</optgroup>";
            }

       echo "</select>";
    }

    public function newRegionFiltered($regionName,$regionID){
        echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value='".$regionID."'>".$regionName."</option>";
        echo "</select>";
    }

    public function sourceDataBase(){

        $arraySource = array("WBD"/*,"IBMS/BTS","FW","SF"*/);

         echo "<select id='sourceDataBase' name='sourceDataBase' style='width:100%;' class='form-control'>";
            for ($a=0; $a < sizeof($arraySource); $a++) { 
                echo "<option value='".$arraySource[$a]."' selected='true'>".$arraySource[$a]."</option>";
            }
            //echo "<option value=''> Select Region </option>";           
        echo "</select>";

    }

    public function sourceDataBasev2(){
        echo "<select id='sourceDataBase' name='sourceDataBase' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function platform(){
        echo "<select id='platform' name='platform[]' class='selectpicker' data-selected-text-format='count' multiple='true' multiple data-actions-box='true' data-size='4' data-width='100%'>";
            echo "<option value='Pay TV' selected='true'> Pay TV </option>";
             echo "<option value='Digital' selected='true'> Digital </option>";
        echo "</select>";
    }
    public function yearViewer($year){   

        echo "<select id='year' class='selectpicker' data-selected-text-format='count' multiple='true' name='year[]' multiple data-actions-box='true' data-size='4' data-width='100%'>";
        for ($y=0; $y <sizeof($year); $y++) { 
            var_dump($year);
             echo "<option selected='true' value='".$year[$y]."'>".$year[$y]."</option>";
        }
           
        echo "</select>";
    }    

    public function year(){    	
    	echo "<select id='year' name='year' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
    	echo "</select>";
    }

    public function yearLATAM(){     

        $cYear = date('Y');

        if($cYear > 2020){
            $pYear = $cYear - 1;
            $years = array($cYear,$pYear);
        }else{
            $years = array($cYear);
        }

        echo "<select id='year' name='year' style='width:100%;' class='form-control'>";
            for ($y=0; $y < sizeof($years); $y++) { 
                echo "<option value='".$years[$y]."'>".$years[$y]."</option>";
            }
        echo "</select>";

    }

    public function especificNumber(){
        echo "<input type='text' id='especificNumber' name='especificNumber' value='' class='form-control' style='display: block;'>";
    }

    public function stageFCST(){
        echo "<select class='selectpicker' id='stageFCST' name='stageFCST[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-size='6' data-width='100%'>";
            for ($m=0; $m < sizeof($this->stageFCST); $m++) { 
                echo "<option selected='true' value='".($m+1)."'>".$this->stageFCST[$m]."</option>";
            }
        echo "</select>";
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

    public function company(){
        echo "<select id='company' class='selectpicker' data-selected-text-format='count' multiple='true' name='company[]' multiple data-actions-box='true' data-size='2' data-width='100%'>";
            echo "<option value='1' selected='true'> DSC </option>";   
            echo "<option value='2' selected='true'> SPT </option>";  
            echo "<option value='3' selected='true'> WM </option>"; 
        echo "</select>";
    }

    public function baseReportFilter(){
        echo "<select class='selectpicker' name='baseReport' data-width='100%'>";
            echo "<option value='brand'> Brand </option>";   
            echo "<option value='ae'> AE </option>";   
            echo "<option value='client'> Advertiser </option>";   
            echo "<option value='agency'> Agency </option>";   
            echo "<option value='agencyGroup'> Agency Group </option>";   
        echo "</select>";
    }

    public function brandSS($brand){
        
        echo "<select id='brandSS' class='selectpicker' name='brand' data-size='7' data-width='100%'>";
            echo "<option value=''> Select </option>";
            for ($i = 0; $i < sizeof($brand); $i++) { 
                if ($brand[$i]["name"] != "DN") {
                    $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'])));
                    echo "<option value='".$value[$i]."'>".$brand[$i]["name"]."</option>";   
                }
            }
            
        echo "</select>";
    }

    public function brandViewer(){

        echo "<select id='brand' class='selectpicker' data-selected-text-format='count' multiple='true' name='brand[]' multiple data-actions-box='true' data-size='4' data-width='100%'>";
            echo "<option selected='true' value=''> Select a Region </option>";               
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
    	echo "<select id='salesRep' class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRep[]' multiple data-actions-box='true' data-size='8' data-width='100%'>";
    		echo "<option value=''> Select Region </option>";

    	echo "</select>";	

    }

    public function properties(){
        echo "<select id='property' class='selectpicker' data-selected-text-format='count' multiple='true' name='property[]' multiple data-actions-box='true' data-size='8' data-width='100%'>";
            echo "<option value=''> Select Region </option>";

        echo "</select>";   

    }

    public function salesRepHide(){
        echo "<select id='salesRep' class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRep[]' multiple data-actions-box='true' data-size='8' data-width='100%' style='display:none'>";
            echo "<option value=''> Select Region </option>";

        echo "</select>";   

    }

    public function salesRepUnit(){
        echo "<select id='salesRepUnit'class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRepUnit[]' multiple data-actions-box='true' data-size='3 ' data-width='100%' data-live-search='true'>";
            echo "<option value=''> Select Region </option>";

        echo "</select>";   

    }

    public function director(){
        echo "<select id='director' class='selectpicker' data-selected-text-format='count' multiple='true' name='director[]' multiple data-actions-box='true' data-size='2' data-width='100%'>";
            echo "<option value=''> Select Region </option>";

        echo "</select>";   

    }


    public function manager($user){
 //var_dump($manager);
        echo "<select id='director' class='selectpicker' data-selected-text-format='count' multiple='true' name='director[]' multiple data-actions-box='true' data-size='2' data-width='100%'>";
            if ($user == 'Fabio Morgado') {
                $manager = array('FM');
                for ($m=0; $m < sizeof($manager); $m++) { 
                    echo "<option selected='true' value='".$manager[$m]."'>".$manager[$m]."</option>";
                }
            }elseif ($user == 'Bruno Paula') {
                $manager = array('BP');
                for ($m=0; $m < sizeof($manager); $m++) { 
                    echo "<option selected='true' value='".$manager[$m]."'>".$manager[$m]."</option>";
                }
            }elseif ($user == 'Ricardo Alves') {
                $manager = array('RA','REGIONAIS');
                for ($m=0; $m < sizeof($manager); $m++) { 
                    echo "<option selected='true' value='".$manager[$m]."'>".$manager[$m]."</option>";
                }
            }elseif($user == 'Victor Vasconcelos') {
                $manager = array('VV','REGIONAIS');
                for ($m=0; $m < sizeof($manager); $m++) { 
                    echo "<option selected='true' value='".$manager[$m]."'>".$manager[$m]."</option>";
                }
            }else{
                for ($m=0; $m < sizeof($this->manager); $m++) { 
                    echo "<option selected='true' value='".$this->manager[$m]."'>".$this->manager[$m]."</option>";
                }
            }        
           

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

    public function currencyOffice(){
        echo "<select id='currency' name='currency' style='width:100%;' class='form-control'>";
            echo "<option value='1'> BRL </option>";
            echo "<option value='4'> USD </option>";              
        echo "</select>";
    }

    public function currencyUSD(){
        echo "<select id='currency' name='currency' style='width:100%;' class='form-control'>";
            echo "<option value='4'> USD </option>";            
        echo "</select>";
    }
    
    public function month($months){
        $base = new base();
        $monthName = $base->intToMonth2($months);
        
        echo "<select id='month' name='month' style='width:100%;' class='form-control'>";
            for ($m=0; $m < sizeof($months); $m++) { 
                echo "<option value='".$months[$m]."'>".$monthName[$m]."</option>";
            }
        echo "</select>";
        
    }

    public function newCurrency($regionName,$regionCurrencies){
    	echo "<select id='currency' name='currency' style='width:100%;' class='form-control'>";
    		for ($c=0; $c < sizeof($regionCurrencies[$regionName]); $c++) { 
                if($c == 0){
                    echo "<option selected='true' value='".$regionCurrencies[$regionName][$c]."'>".$regionCurrencies[$regionName][$c]."</option>";
                }else{
                    echo "<option value='".$regionCurrencies[$regionName][$c]."'>".$regionCurrencies[$regionName][$c]."</option>";
                }
            }
    	echo "</select>";
        
    }

    public function currencyLATAM(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $pr = new pRate();
        $currency = $pr->getCurrencyByRegion($con);
        if ($currency) {
            echo "<select id='currency' name='currency' style='width:100%;' class='form-control'>";
                echo "<option value='4'>USD</option>";
                for ($c=0; $c <sizeof($currency); $c++) {
                    if($currency[$c]["name"] != "USD" && $currency[$c]['id'] <= 6){
                        echo "<option value='".$currency[$c]["id"]."'>".$currency[$c]["name"]."</option>";
                    }
                }
            echo "</select>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
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

    public function value3(){
        echo "<select id='value' name='value' style='width:100%;' class='form-control'>";
            echo "<option value='gross'> Gross </option>"; 
            echo "<option value='net'> Net </option>";                   
        echo "</select>";
    }

    public function value4(){
        echo "<select id='value' name='value' style='width:100%;' class='form-control'>";
            echo "<option value='gross'> Gross </option>"; 
            echo "<option value='net'> Net </option>";  
            echo "<option value='net net'> Net Net </option>";                   
        echo "</select>";
    }

    public function valueNet(){
        echo "<select id='value' name='value' style='width:100%;' class='form-control'>";
            echo "<option value='net'> Net </option>";            
            //echo "<option value='gross'> Gross </option>";           
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

    public function typeOffice(){
        $ajax = new ajaxController();

        

        echo "<select id='type' name='type' style='width:100%;' class='form-control'>";
            //echo "<option value=''> Select Region </option>";
            $ajax->typeConsolidate();
        echo "</select>";
    }

    public function typeSelect(){
        echo "<select id='typeSelect' name='typeSelect[]' style='width:100%;' class='selectpicker' data-selected-text-format='count' multiple='true' multiple data-actions-box='true' data-size='4' data-width='100%'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";
    }

    public function typeSelectOffice(){
        echo "<select id='typeSelect' name='typeSelect[]' style='width:100%;' class='selectpicker' data-selected-text-format='count' multiple='true' multiple data-actions-box='true' data-size='4' data-width='100%'>";
            echo "<option value='' selected='true'> Select Type </option>";
        echo "</select>";
    }

    public function typeNojQuery(){
        echo "<select id='type' name='type' style='width:100%;' class='form-control'>";
            echo "<option value='agency'> Agency </option>";
            echo "<option value='agencyGroup'> Agency Group </option>";
        echo "</select>";
    }

    public function type2(){
        echo "<select class='selectpicker' id='type2' name='type2[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-size='3' data-width='100%' data-live-search='true'>";
                echo "<option selected='true' value=''> Select the previous field </option>";    
        echo "</select>";
    }

    public function nPos(){
        echo "<select id='nPos' name='nPos' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select type </option>";
        echo "</select>";
    }

}
