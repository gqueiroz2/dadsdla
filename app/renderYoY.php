<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderYoY extends Model {
    
    public function source($region, $year){
    	echo "<select name='source' style='width:100%;'>";
    		echo "<option value=''> Select </option>";
            echo "<option value='IBMS'> Real (IBMS) $year </option>";
            
            if ($region == 'Brazil') {
                echo "<option value='CMAPS'> Real (CMAPS) $year </option>";
            }else{
                echo "<option value='Header'> Real (Header) $year </option>";//somente se for brasil a região selecionada
            }
    		
    	echo "</select>";	
    }
}
