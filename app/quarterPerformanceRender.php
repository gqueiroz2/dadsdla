<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterPerformanceRender extends Render {
    
    public function tiers(){
    	
    	echo "<select id='tier' name='tier' style='width: 100%' class='form-control'>";
    		echo "<option value=''> Select Region</option>";
    	echo "</select>";
    }
}
