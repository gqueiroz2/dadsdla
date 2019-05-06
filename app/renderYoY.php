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

    public function brandTable($value, $color){
        
        $class = "class='".strtolower($color)." center'";

        echo "<td ".$class." rowspan='7'>";
            echo "<span style='font-size: 18px'>".$value."</span>";
        echo "</td>";

    }

    public function renderData($value, $line, $firstColor, $secondColor, $thirdColor=null){

        $class = null;

        $firstClass = "class='".$firstColor." center'";
        $secondClass = "class='".$secondColor." center'";
        $thirdClass = "class='".$thirdColor." center'";

        for ($col = 0; $col < 14; $col++) { 

            if ($line == 1 || $line == 4 || $line == 5 || $line == 6) {
                if ($col >= 0 && $col <= 12) {
                    $class = $firstClass;
                }else{
                    $class = $secondClass;
                }
            }
            else{
                if ($col == 0){
                    $class = $firstClass;
                }elseif ($col >= 1 && $col <= 12) {
                    $class = $secondClass;
                }else{
                    $class = $thirdClass;
                }
            }

            if (is_numeric($value[$col])) {
                echo "<td ".$class." >".number_format($value[$col])."<td/>";
            }else{
                echo "<td ".$class." >".$value[$col]."<td/>";
            }
        }

    }

}

/**/