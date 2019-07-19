<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class RenderChain extends Render{
    
    public function yearMultiple(){
    	
    	echo "<select class='selectpicker' data-selected-text-format='count' multiple='true' name='year[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
			echo "<option value='2019' selected='true'> 2019 </option>";
			echo "<option value='2018'> 2018 </option>";
			echo "<option value='2017'> 2017 </option>";
			echo "<option value='2016'> 2016 </option>";
		echo "</select>";
    	
    }

    public function year(){
    	
    	echo "<select class='form-control' name='year' data-width='100%'>";
			echo "<option value='2019' selected='true'> 2019 </option>";
			echo "<option value='2018'> 2018 </option>";
			echo "<option value='2017'> 2017 </option>";
			echo "<option value='2016'> 2016 </option>";
		echo "</select>";
    	
    }

    public function report(){

    	echo "<select class='form-control' name='table' data-width='100%'>";
    		//echo "<option value=''> Select </option>";
    		//echo "<option value='cmaps'> CMAPS </option>";
			echo "<option value='ytd'> YTD </option>";
			//echo "<option value='mini_header'> Mini-Header </option>";
			//echo "<option value='digital'> Digital </option>";
		echo "</select>";

    }

    public function table($name){

    	echo "<select class='form-control' name='$name' data-width='100%'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='cmaps'> CMAPS </option>";
			echo "<option value='ytd'> YTD </option>";
			echo "<option value='mini_header'> Mini-Header </option>";
			echo "<option value='digital'> Digital </option>";
		echo "</select>";

    }


}
