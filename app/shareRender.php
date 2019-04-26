<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shareRender extends Model
{
    public function region($region){
    	echo "<select name='region'>";
    		echo "<option value=''> Select </option>";
    		for ($i=0; $i <sizeof($region) ; $i++) { 
    			echo "<option value='".$region[$i]['id']."'>".$region[$i]['name']."</option>";
    		}
    	echo "</select>";
    }

    public function year(){
    	//Fazer uma funçao na controler pra pegar os anos disponiveis, por enquanto estou setando quais nos vamos utilizar
    	echo "<select name='year'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='2019'> 2019 </option>";
    		echo "<option value='2018'> 2018 </option>";
    	echo "</select>";
    }

    public function brand($brand){
    	echo "<select name='brand' multiple='true'>";
    		echo "<option value=''> Select </option>";
    		for ($i=0; $i <sizeof($brand) ; $i++) { 
	    		echo "<option value='".$brand[$i]["id"]."'>".$brand[$i]["name"]."</option>";
    		}
    		echo "<option value='all'> All </option>";
    	echo "</select>";
    }

    public function font(){
    	echo "<select name='font'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='IBMS'> IBMS </option>";
    		echo "<option value='CMAPS'> CMAPS </option>";
    		echo "<option value='Header'> Header </option>";//somente se for brasil a região selecionada
    	echo "</select>";	
    }

    public function salesRepGroup($salesRepGroup){
    	echo "<select name='salesRepGroup'>";
    		echo "<option value='all'> All </option>";
    		for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
	    		echo "<option value='".$salesRepGroup[$i]["id"]."'>".$salesRepGroup[$i]["name"]."</option>";
    		}
    	echo "</select>";	

    }

    public function salesRep($salesRep){
    	echo "<select name='salesRep'>";
    		echo "<option value='all'>All</option>";
    		for ($i=0; $i <sizeof($salesRep); $i++) { 
    			echo "<option value='".$salesRep[$i]["id"]."'>".$salesRep[$i]["salesRep"]."</option>";
    		}
    	echo "</select>";	

    }

    public function months(){
    	echo "<select value='months' multiple='true'>";
    		echo "<option value='all'>All</option>";
    		echo "<option value='1'>Jan</option>";
    		echo "<option value='2'>Feb</option>";
    		echo "<option value='3'>Mar</option>";
    		echo "<option value='4'>Apr</option>";
    		echo "<option value='5'>May</option>";
    		echo "<option value='6'>Jun</option>";
    		echo "<option value='7'>Jul</option>";
    		echo "<option value='8'>Aug</option>";
    		echo "<option value='9'>Sep</option>";
    		echo "<option value='10'>Oct</option>";
    		echo "<option value='11'>Nov</option>";
    		echo "<option value='12'>Dec</option>";
    	echo "</select>";
    }

    public function currency($currency){
    	echo "<select value='currency'>";
    		echo "<option value=''> Select </option>";
    		for ($i=0; $i <sizeof($currency) ; $i++) { 
    			echo "<option value='".$currency[$i]["id"]."'>".$currency[$i]["name"]."</option>";
    		}
    	echo "</select>";
    }

    public function value(){
    	echo "<select value='value'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='gross'> Gross </option>";
    		echo "<option value='net'> Net </option>";
    	echo "</select>";
    }
}
