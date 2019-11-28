<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderNewRanking extends Render {
    
    public function checkNew($cont, $values, $name, $type, $years){
        
        $bool = -1;
        $bool2 = -1;

        if (is_array($values[0])) {
            for ($v=0; $v < sizeof($values[0]); $v++) { 
                if ($values[0][$v][$type] == $name[$type]) {
                    $bool = 0;
                    if ($values[0][$v]['total'] > 0) {
                        $bool = 1;
                    }else{
                        $bool = 2;
                    }
                }
            }   
        }

        if (is_array($values[1])) {
            for ($v=0; $v < sizeof($values[1]); $v++) { 
                if ($values[1][$v][$type] == $name[$type]) {
                    $bool2 = 1;
                }
            }
        }

        if ($bool == 1 && $bool2 == -1) {
            return $cont;
        }else{
            return -1;
        }
    }

    public function search($brands, $type, $regionID, $region, $value, $currency, $months, $years, $sector=false){
        
        $null = null;

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $r = new rankingMarket();

        if ($region == "Brazil") {
            $res = $r->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null, "DESC");
        }else{
            $res = $r->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null, "DESC");   
        }

        $names = array();

        for ($r=0; $r < sizeof($res); $r++) { 
            for ($r2=0; $r2 < sizeof($res[$r]); $r2++) { 
                array_push($names, $res[$r][$r2][$type]);
            }
        }

        $names = array_values(array_unique($names));
        var_dump($names);
        /*echo "<select class='selectpicker' id='namesExcel' name='namesExcel[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control'>";

            for ($n=0; $n < sizeof($names); $n++) { 
                if ($this->checkNew($c = 1, $res, $names[$n], $type, $years) != -1) {
                    echo "<option value='".$names[$n]."' >".$names[$n]."</option>";   
                }
            }

        echo "</select>";*/

    }

    public function assembler($mtx, $total, $currency, $value, $type, $names, $region){
    	
    	echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='".sizeof($mtx)."' class='lightBlue'><center>";
                            echo "<span style='font-size:18px;'>";
                                echo "<b> $region - New Ranking (BKGS) : (".$currency[0]['name']."/".strtoupper($value).")</b></br>";
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
                                echo "<td id='".$type.$m."' class='$color center'> ".$mtx[$n][$m]." </td>";
                                $name = $mtx[$n][$m];
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
            			if ($t == 5) {
            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";
            			}else{
            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
            			}
            		}else{
        				if ($t == 4) {
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
