<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderMQ extends Render{
    public function assemble($mtx,$currency,$value,$year,$form,$region){

    	echo "<table style='width: 100%; zoom: 85%;font-size: 16px;'>";
    		
			echo "<tr>";
				echo "<th colspan='14' class='lightBlue'><center><span style='font-size:24px;'> $region - Month : ".$form." - ".$year." (".$currency."/".strtoupper($value).")</span></center></th>";
			echo "</tr>";

			echo "<tr><td> &nbsp; </td></tr>";
			
			for ($m=0; $m < sizeof($mtx); $m++) { 
				for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
					echo "<tr>";
					for ($o=0; $o < sizeof($mtx[$m][$n]); $o++) { 
						if(is_numeric($mtx[$m][$n][$o])){
							if($n == 3){
								if($o == 13){
									echo "<td class='smBlue center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")." %</td>";
								}else{
									echo "<td class='rcBlue center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")." %</td>";
								}
							}elseif($n == 4){
								if($o == 13){
									echo "<td class='darkBlue center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")."</td>";
								}else{
									echo "<td class='medBlue center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")."</td>";
								}
							}else{
								if($o == 13){
									echo "<td class='smBlue center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")."</td>";
								}else{
									echo "<td class='center'>".number_format($mtx[$m][$n][$o], 2, ",", ".")."</td>";
								}
							}
						}else{
							if($n == 0 && $o == 0){
								if ($mtx[$m][$n][$o] == "DN") {
									echo "<td class='darkBlue center' style='width:10%;'>".$mtx[$m][$n][$o]."</td>";
								}else{
									echo "<td class='lightBlue center' style='width:10%;'>".$mtx[$m][$n][$o]."</td>";
								}								
							}elseif($n == 0 && $o != 0){
								if($o == 13){
									echo "<td class='darkBlue center' style='width:10%;'>".$mtx[$m][$n][$o]."</td>";
								}else{
									echo "<td class='lightGrey center' style='width:6.66%;'>".$mtx[$m][$n][$o]."</td>";
								}
							}elseif($n == 1 && $o == 0){
								if ($m == 0) {
									echo "<td class='smBlue center'>".$mtx[$m][$n][$o]."</td>";
								}else{
									echo "<td class='coralBlue center'>".$mtx[$m][$n][$o]."</td>";
								}
							}elseif( ($n == 2 || $n == 3)  && $o == 0){
								if ($m == 0) {
									echo "<td class='smBlue center'>".$mtx[$m][$n][$o]."</td>";
								}else{
									echo "<td class='rcBlue center'>".$mtx[$m][$n][$o]."</td>";
								}
							}elseif($n == 4 && $o == 0){
								if ($m == 0) {
									echo "<td class='darkBlue center'>".$mtx[$m][$n][$o]."</td>";
								}else{
									echo "<td class='medBlue center'>".$mtx[$m][$n][$o]."</td>";
								}
							}else{
								echo "<td class='center'>".$mtx[$m][$n][$o]."</td>";
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
