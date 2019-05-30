<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class corePerformanceRender extends renderPerformance
{
    public function case1($mtx){
	    for ($sg=0; $sg < sizeof($mtx["case1"]["value"]); $sg++) {
	    	if ($sg%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";
	    	echo "<table  border='1' class='salesGroupClick' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesGroup"][$sg]["name"]."</th></table>";
	    	for ($t=0; $t < sizeof($mtx["case1"]["value"][$sg]); $t++) { 
		    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
		    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>".$mtx["tier"][$t]."</td>";
		    			echo "<td style='width:10%;'></td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td style='width:16%;' class='quarterClick' >".$mtx["quarters"][$q]."</td>";
		    			}
		    			echo "<td style='width:18%;' >Total</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Meta</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["case1"]["planValue"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["case1"]["totalPlanValueTier"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Actual</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["case1"]["value"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["case1"]["totalValueTier"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Var Abs</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["case1"]["varAbs"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["case1"]["totalVarAbs"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Var %</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["case1"]["varPrc"][$sg][$t][$q],0)."%</td>";
		    			}
		    			echo "<td>".number_format($mtx["case1"]["totalVarPrc"][$sg][$t],0)."%</td>";
		    		echo "</tr>";
		    	echo "</table>";

	    	}
	    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
	    		echo "<tr>";
	    			echo "<td rowspan='5' style='width:5%;'>DN</td>";
	    			echo "<td style='width:10%;'></td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td style='width:16%;'>".$mtx["quarters"][$q]."</td>";
	    			}
	    			echo "<td style='width:18%;' >Total</td>";
	    		echo "</tr>";
	    		echo "<tr>";
	    			echo "<td style='width:10%;'>Meta</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td>".number_format($mtx["case1"]["totalPlanSG"][$sg][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;' >".number_format($mtx["case1"]["totalPlanTotalSG"][$sg],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td>Actual</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td>".number_format($mtx["case1"]["totalSG"][$sg][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;'>".number_format($mtx["case1"]["totalTotalSG"][$sg],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td>Var Abs</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td>".number_format($mtx["case1"]["totalSGVarAbs"][$sg][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;' >".number_format($mtx["case1"]["totalTotalSGVarAbs"][$sg],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td>Var %</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td>".number_format($mtx["case1"]["totalSGVarPrc"][$sg][$q],0)."%</td>";
	    			}
    				echo "<td style='width:18%;' >".number_format($mtx["case1"]["totalTotalSGVarPrc"][$sg],0)."%</td>";
	    		echo "</tr>";
		   	echo "</table>";

	    	echo "</div>";

	    	if ($sg%2 == 1) {
	    		echo "</div>";
	    	}

	    }
    }
    
    public function case2($mtx){
    	for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	if ($sg%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";

		    	echo "<table  border='1' class='salesGroupClick' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:10%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick' style='width:16%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td>".number_format($mtx["case2"]["planValue"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case2"]["totalPlanValueBrand"][$sg][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td>".number_format($mtx["case2"]["value"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case2"]["totalValueBrand"][$sg][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td>".number_format($mtx["case2"]["varAbs"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case2"]["totalVarAbs"][$sg][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td>".number_format($mtx["case2"]["varPrc"][$sg][$b][$q],0)."%</td>";
			    			}
			    			echo "<td>".number_format($mtx["case2"]["totalVarPrc"][$sg][$b],0)."</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table border='1' style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>DN</td>";
			    			echo "<td style='width:10%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick' style='width:16%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%' >Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	if ($sg%2 == 1) {
	    		echo "</div>";
	    	}
    	}
    }
    
    public function case3($mtx){
    	for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  border='1' class='salesGroupClick' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["tier"]); $b++) { 
			    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>".$mtx["tier"][$b]."</td>";
			    			echo "<td style='width:5%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td >Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case3"]["planValues"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case3"]["totalPlanValueTier"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case3"]["values"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case3"]["totalValueTier"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case3"]["varAbs"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td>".number_format($mtx["case3"]["totalVarAbs"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case3"]["varPrc"][$sg][$b][$q])."%</td>";
			    			}
			    			echo "<td>".number_format($mtx["case3"]["totalVarPrc"][$sg][$b])."</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table border='1' style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>DN</td>";
			    			echo "<td style='width:5%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td >Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
    	}
    
    }
    
    public function case4($mtx){
	    for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  border='1' class='salesGroupClick' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:5%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td >Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case4"]["planValues"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case4"]["values"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case4"]["varAbs"][$sg][$b][$q],0)."</td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td>".number_format($mtx["case4"]["varPrc"][$sg][$b][$q],0)."%</td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table border='1' style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick' style='width:5%;'>DN</td>";
			    			echo "<td style='width:5%;'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td >Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td>Meta</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td></td>";
			    			}
			    			echo "<td></td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
	    	
    	}
    }
}
