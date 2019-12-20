<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderChurnRanking extends Render {

    public function search($mtx, $type){

        if ($type != "agency") {
            $p = 1;
        }else{
            $p = 2;
        }

        echo "<select class='selectpicker' id='namesExcel' name='namesExcel[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control'>";

            for ($m=1; $m < sizeof($mtx[$p]); $m++) { 
                echo "<option value='".base64_encode(json_encode(array($mtx[$p-1][$m], $mtx[$p][$m])))."' >".$mtx[$p][$m]."</option>";
            }

        echo "</select>";

    }
    
    public function assembler($mtx, $total, $currency, $value, $type, $names, $region){

    	echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='".sizeof($mtx)."' class='lightBlue'><center>";
                            echo "<span style='font-size:18px;'>";
                                echo "<b> $region - Churn Ranking (BKGS) : (".$currency[0]['name']."/".strtoupper($value).")</b></br>";
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
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." %</td>";
            				}elseif ($mtx[$n][0] == "Ranking") {
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." º</td>";
            				}else{
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." </td>";
            				}
            			}else{
                            if ($mtx[$n][0] == ucfirst($type)) {
                                echo "<td id='".$type.$m."' class='$color center' data-value='".$mtx[$n-1][$m]."'> ".$mtx[$n][$m]." </td>";
                            }else{
                                echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
                            }
            			}
            		}
            	}

            	echo "</tr>";

                echo "<tr>";
                    echo "<td class='$color' id='sub".$type.$m."' style='display: none' colspan='".sizeof($mtx)."'></td>";
                echo "</tr>";
            }

            echo "<tr>";
            for ($t=0; $t < sizeof($total); $t++) {
            	if (is_numeric($total[$t])) {
            		if ($type == "agency") {
            			if ($t == 6) {
            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";
            			}else{
            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
            			}
            		}else{
        				if ($t == 5) {
        					echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";	
        				}else{
        					echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";	
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
