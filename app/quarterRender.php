<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterRender extends Render
{
    public function assemble($mtx, $currency, $value, $year, $form){
    	
		//var_dump($mtx);
		
    	echo "<table style='width: 100%; zoom:80%;'>";
    		
    		echo "<tr>";
				echo "<th colspan='14' class='lightBlue'><center><span style='font-size:24px;'> Quarter :(".$form.") ".$year." (".$currency[0]['name']."/".strtoupper($value).") </span></center></th>";
			echo "</tr>";

			echo "<tr><td>&nbsp;</td></tr>";

			for ($b=0; $b < sizeof($mtx); $b++) {
				for ($l=0; $l < sizeof($mtx[$b]); $l++) { 
					echo "<tr>";
					for ($v=0; $v < sizeof($mtx[$b][$l]); $v++) { 
						if (is_numeric($mtx[$b][$l][$v])) {
							if ($v == 3 || $v == 6) {
								if ($l == 3) {
									echo "<td class='medBlue center'>".number_format($mtx[$b][$l][$v])." %</td>";	
								}elseif ($l == 4) {
									echo "<td class='quarter center'>".number_format($mtx[$b][$l][$v])."</td>";	
								}else{
									echo "<td class='medBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
								}
							}elseif($v == 7){
								if ($l == 3) {
									echo "<td class='smBlue center'>".number_format($mtx[$b][$l][$v])." %</td>";
								}elseif ($l == 4) {
									echo "<td class='darkBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
								}
								else{
									echo "<td class='smBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
								}
								
							}elseif ($l == 1 || $l == 2) {
								echo "<td class='center'>".number_format($mtx[$b][$l][$v])."</td>";
							}elseif ($l == 3) {
								echo "<td class='rcBlue center'>".number_format($mtx[$b][$l][$v])." %</td>";
							}else{
								echo "<td class='medBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
							}
						}else{
							if($l == 0 ){
								if ($v == 0) {
									echo "<td class='lightBlue center'>".$mtx[$b][$l][$v]."</td>";		
								}elseif (($v >= 1 && $v <= 2) || ($v >= 4 && $v <= 5)) {
									echo "<td class='lightGrey center'>".$mtx[$b][$l][$v]."</td>";		
								}elseif ($v == 3 || $v == 6) {
									echo "<td class='quarter center'>".$mtx[$b][$l][$v]."</td>";
								}else{
									echo "<td class='darkBlue center'>".$mtx[$b][$l][$v]."</td>";
								}
							}elseif ($l == 1) {
								echo "<td class='coralBlue center'>".$mtx[$b][$l][$v]."</td>";	
							}elseif ($l == 2 || $l == 3) {
								echo "<td class='rcBlue center'>".$mtx[$b][$l][$v]."</td>";	
							}else{
								echo "<td class='medBlue center'>".$mtx[$b][$l][$v]."</td>";	
							}
						}
						
					}
					echo "</tr>";
				}

				echo "<tr><td> &nbsp; </td></tr>";
			}

    	echo "</table>";
    }
}