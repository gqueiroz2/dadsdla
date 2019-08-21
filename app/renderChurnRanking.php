<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderChurnRanking extends Render {
    
    public function assembler($mtx, $total, $currency, $value, $type, $names){
    	
    	echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='".sizeof($mtx)."' class='lightBlue'><center>";
                            echo "<span style='font-size:18px;'>";
                                echo "<b> Ranking ".ucfirst($type)." (".strtoupper($names['source']).") : (".$currency[0]['name']."/".strtoupper($value).")</b></br>";
	                            if ($type != "sector") {
	                            	echo "<span style='font-size:18px;'>";
	                            		echo "<b> Refer to the brands: ".$names['brands']."</b></br>";
	                        		echo "</span>";
	                            }
	                            echo "<span style='font-size:18px;'>";
                            		echo "<b> Refer to the period: ".$names['months']."</b>";
                        		echo "</span>";
                            echo "</span>";
                        echo "</center></th>";
            echo "</tr>";

            for ($m=0; $m < sizeof($mtx[0]); $m++) {

            	echo "<tr>";

            	if ($m == 0) {
        			$color = 'lightBlue';
        		}elseif ($m%2 == 0) {
        			$color = 'medBlue';
        		}else{
        			$color = 'rcBlue';
        		}

            	for ($n=0; $n < sizeof($mtx); $n++) { 
            		
            		if ($m == 0) {
            			echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
            		}else{
            			if (is_numeric($mtx[$n][$m])) {
            				if ($mtx[$n][0] == "Var (%)" || $mtx[$n][0] == "Var YTD (%)") {
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." %</td>";
            				}elseif ($mtx[$n][0] == "Ranking") {
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." º</td>";
            				}else{
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." </td>";
            				}
            			}else{
        					echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
            			}
            		}
            	}

            	echo "</tr>";
            }

            echo "<tr>";
            for ($t=0; $t < sizeof($total); $t++) {
            	if (is_numeric($total[$t])) {
            		if ($type == "agency") {
            			if ($t == 5 || $t == 11) {
            				echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";
            			}else{
            				echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";
            			}
            		}else{
        				if ($t == 4 || $t == 10) {
        					echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";	
        				}else{
        					echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";	
        				}
        			}
            	}else{
            		echo "<td class='darkBlue center'> ".$total[$t]." </td>";
            	}
            }
            echo "</tr>";

        echo "</table>";
    }
}
