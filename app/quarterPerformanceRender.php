<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterPerformanceRender extends Render {
    
    public function assemble($mtx, $region, $currency, $value, $year, $sales, $tiers){

		echo "<table style='width: 100%; zoom: 80%; font-size: 16px;'>";
			echo "<tr>";
				echo "<th colspan='11' class='lightBlue'><center><span style='font-size: 20px;'>$region - Office $year (".$currency[0]['name']."/".strtoupper($value).")</span></center></th>";
			echo "</tr>";
			echo "<tr>";
				echo "<th colspan='11' class='lightBlue'><center><span style='font-size: 20px;'>Sales Group: ".$sales["salesRepGroup"]." / Sales Representative: ".$sales["salesRep"]."</span></center></th>";
			echo "</tr>";

			echo "<tr><td>&nbsp;</td></tr>";

			for ($t=0; $t < sizeof($mtx); $t++) { 

				echo "<tr>";
					echo "<td rowspan='".(sizeof($mtx[$t])*7)."' class='".strtolower($tiers[$t])." center'  style='width: 2%;'>";
						echo $tiers[$t];
					echo "</td>";
					echo "<td rowspan='".(sizeof($mtx[$t])*7)."'>&nbsp;</td>";

				for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
					echo "<tr>";
						echo "<td rowspan='6' class='".strtolower($mtx[$t][$b][0][0])." center' style='width: 2.5%;'>";
							echo $mtx[$t][$b][0][0];
						echo "</td>";
					for ($c=1; $c < sizeof($mtx[$t][$b]); $c++) { 
						echo "<tr>";
						for ($v=0; $v < sizeof($mtx[$t][$b][$c]); $v++) { 
							if($v == 3 || $v == 6){

							}elseif ($v == 0) {
								if (($t == (sizeof($tiers)-1))) {
									if ($c == 5 || $c == 1) {
										echo "<td class='darkBlue center'>".$mtx[$t][$b][$c][$v]."</td>";
									}else{
										echo "<td class='smBlue center'>".$mtx[$t][$b][$c][$v]."</td>";
									}
								}elseif ($c == 2) {
									echo "<td class='coralBlue center'>".$mtx[$t][$b][$c][$v]."</td>";	
								}elseif ($c == 3 || $c == 4) {
									echo "<td class='rcBlue center'>".$mtx[$t][$b][$c][$v]."</td>";	
								}elseif ($c == 1) {
									echo "<td class='lightGrey center'>".$mtx[$t][$b][$c][$v]."</td>";	
								}else{
									echo "<td class='medBlue center'>".$mtx[$t][$b][$c][$v]."</td>";	
								}
							}elseif ($v >= 1 && $v <= 6) {
								$style = "style='width: 15%'";
								if ($c == 2 || $c == 3) {
									echo "<td class='center'>".number_format($mtx[$t][$b][$c][$v],0,",",".")."</td>";	
								}elseif ($c == 4) {
									echo "<td class='rcBlue center'>".number_format($mtx[$t][$b][$c][$v],0,",",".")."</td>";	
								}elseif ($c == 5) {
									echo "<td class='medBlue center'>".number_format($mtx[$t][$b][$c][$v],0,",",".")." %</td>";
								}else{
									echo "<td class='lightGrey center' $style>".$mtx[$t][$b][$c][$v]."</td>";
								}
							}else{
								if ($c == 1) {
									echo "<td class='darkBlue center'>".$mtx[$t][$b][$c][$v]."</td>";
								}elseif ($c >= 2 && $c <= 4) {
									echo "<td class='smBlue center'>".number_format($mtx[$t][$b][$c][$v],0,",",".")."</td>";
								}else{
									echo "<td class='darkBlue center'>".number_format($mtx[$t][$b][$c][$v],0,",",".")." %</td>";
								}
							}
							
						}

						echo "</tr>";
					}

					echo "</tr>";
					echo "<tr><td>&nbsp;</td></tr>";
				}

				echo "</tr>";
			}

		echo "</table>";
    }
}
