<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Render extends Model{
    
    protected $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

    public function region($region){
    	echo "<select name='region' style='width:100%;' class='form-control'>";
    		echo "<option value=''> Select </option>";
            for ($i = 0; $i < sizeof($region); $i++) { 
    			echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";
    		}

	   echo "</select>";
    }

    public function year(){
    	//Fazer uma funçao na controler pra pegar os anos disponiveis, por enquanto estou setando quais nos vamos utilizar

        $currentYear = intval(date('Y'));
        $year = array($currentYear, $currentYear-1);

    	echo "<select id='year' name='year' style='width:100%;'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='$year[0]'> $year[0] </option>";
    		echo "<option value='$year[1]'> $year[1] </option>";
    	echo "</select>";
    }

    public function brand($brand){
    	echo "<select name='brand[]' multiple='true' style='width:100%;'>";
    		echo "<option value='dn' selected='true'> DN </option>";
    		
            for ($i = 0; $i < sizeof($brand); $i++) { 
	    		echo "<option value='".$brand[$i]["id"]."'>".$brand[$i]["name"]."</option>";
    		}
    		
    	echo "</select>";
    }

    public function font(){
    	echo "<select name='font' style='width:100%;'>";
    		echo "<option value=''> Select </option>";
            echo "<option value='IBMS'> Real (IBMS) </option>";
            
            
                echo "<option value='CMAPS'> Real (CMAPS) </option>";
            
                echo "<option value='Header'> Real (HEADER) </option>";//somente se for brasil a região 
    		
    	echo "</select>";	
    }

    public function salesRepGroup($salesRepGroup){
    	echo "<select name='salesRepGroup' style='width:100%;'>";
    		echo "<option value='all'> All </option>";
    		
            for ($i = 0; $i < sizeof($salesRepGroup); $i++) { 
	    		echo "<option value='".$salesRepGroup[$i]["id"]."'>".$salesRepGroup[$i]["name"]."</option>";
    		}

    	echo "</select>";	

    }

    public function salesRep($salesRep){
    	echo "<select name='salesRep' style='width:100%;'>";
    		echo "<option value='all'>All</option>";
    		
            for ($i = 0; $i < sizeof($salesRep); $i++) { 
    			echo "<option value='".$salesRep[$i]["id"]."'>".$salesRep[$i]["salesRep"]."</option>";
    		}

    	echo "</select>";	

    }

    public function months(){

    	echo "<select name='month[]' multiple='true' style='width:100%;'>";
            echo "<option selected='true' value='all'>All</option>";
    		echo "<option value='ytd'>YTD</option>";
            
    		for ($m=0; $m < sizeof($this->month); $m++) { 
    			echo "<option value='".($m+1)."'>".$this->month[$m]."</option>";
    		}

    	echo "</select>";
    }

    public function currency($currency){
    	echo "<select name='currency' style='width:100%;'>";
    		echo "<option value=''> Select </option>";
    		
            for ($i = 0; $i < sizeof($currency); $i++) { 
    			echo "<option value='".$currency[$i]["id"]."'>".$currency[$i]["name"]."</option>";
    		}

    	echo "</select>";
    }

    public function value(){
    	echo "<select name='value' style='width:100%;'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='gross'> Gross </option>";
    		echo "<option value='net'> Net </option>";
    	echo "</select>";
    }
}
