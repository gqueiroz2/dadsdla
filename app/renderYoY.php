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

    public function renderDataHead($value){
        
        $style = "style='color: blue;height: auto;width: auto;'";

        for ($i=0; $i < 14; $i++) { 

            if ($i == 13) {
                echo "<th ".$style." >".number_format(doubleval($value[$i]))."<th/>";   
            }
            else{
                echo "<th>".number_format(doubleval($value[$i]))."<th/>";
            }
        }
    }

    public function renderDataBody($value){
        
        $style = "style='height: auto;width: auto;'";

        for ($i=0; $i < 14; $i++) { 

            if ($i == 13) {
                echo "<td ".$style." >".number_format(doubleval($value[$i]))."<td/>";   
            }
            else{
                echo "<td>".number_format(doubleval($value[$i]))."<td/>";
            }
        }
    }

}
