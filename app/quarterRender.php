<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterRender extends Render
{
    public function quarters(){
        
        echo "<select id='quarter' class='selectpicker' data-selected-text-format='count' multiple='true' name='quarter[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
            for ($i = 0; $i < 4; $i++) { 
                $value[$i] = ($i+1);
                echo "<option selected='true' value='".$value[$i]."'>Q".$value[$i]."</option>";
            }
            
        echo "</select>";
    }

    public function assemble($mtx, $currency, $value, $year, $quarters){
    	
		var_dump($mtx[0]);
		$labels = 0;
    	echo "<table style='width: 100%; zoom:80%;'>";
    		
    		echo "<tr>";
				echo "<th colspan='14' class='lightBlue'><center><span style='font-size:18px;'> Monthly (".$currency[0]['name']."/".$value.") - ".$year." </span></center></th>";
			echo "</tr>";

			//var_dump($mtx);
			for ($b=0; $b < sizeof($mtx); $b++) {
				echo "<tr>"; 
				for ($q=0; $q < sizeof($mtx[$b]); $q++) { 
					for ($l=0; $l < sizeof($mtx[$b][$q]); $l++) { 
						if ($q == 0) {
							echo "<td>".$mtx[$b][$q][$l][0]."</td>";
						}else{
							echo "<td>".$mtx[$b][$q][$l][1]."</td>";
						}
					}
					echo "</tr>";
				}
			}

    	echo "</table>";
    }
}
