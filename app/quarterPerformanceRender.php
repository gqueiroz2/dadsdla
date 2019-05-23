<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterPerformanceRender extends Render {
    
    public function tiers(){
    	
    	echo "<select id='tier' class='selectpicker' data-selected-text-format='count' multiple='true' name='tier[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
    	echo "</select>";
    }
}
