<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Render extends Model{
    
    protected $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

    public function region($region){

    	echo "<select id='region' name='region' style='width:100%;' class='form-control'>";
    		echo "<option value=''> Select </option>";
            for ($i = 0; $i < sizeof($region); $i++) { 
    			if($region[$i]['name'] != "LATAM" ){
                    echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";
                }
    		}

	   echo "</select>";
    }

    public function regionFiltered($region,$regionFiltered){

        echo "<select  id='region' name='region' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select </option>";

            for ($i = 0; $i < sizeof($region); $i++) { 
                if ( $region[$i]['id'] == $regionFiltered && ($region[$i]['name'] != "LATAM" )) {
                    echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";                    
                }
            }

       echo "</select>";
    }

    public function year(){
    	
    	echo "<select id='year' name='year' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";

    	echo "</select>";
    }

    public function brand($brand){

    	echo "<select id='brand' class='selectpicker' data-selected-text-format='count' multiple='true' name='brand[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
            for ($i = 0; $i < sizeof($brand); $i++) { 
                $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'])));
	    		echo "<option selected='true' value='".$value[$i]."'>".$brand[$i]["name"]."</option>";
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

    public function source(){
        
        echo "<select id='source' name='source' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Region </option>";
        echo "</select>";   
    }

    public function salesRepGroup($salesRepGroup){
    	echo "<select id='salesRepGroup'class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRepGroup[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
    		//echo "<option value=''> Select Region </option>";

    	echo "</select>";	

    }

    public function salesRep($salesRep){
    	echo "<select id='salesRep'class='selectpicker' data-selected-text-format='count' multiple='true' name='salesRep[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
    		//echo "<option value=''> Select Region </option>";

    	echo "</select>";	

    }

    public function months(){

    	echo "<select class='selectpicker' id='month' name='month[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-size='3' data-width='100%'>";
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
            echo "<option value=''> Select Source </option>";            
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
}
