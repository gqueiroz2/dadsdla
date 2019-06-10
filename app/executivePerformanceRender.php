<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class executivePerformanceRender extends renderPerformance
{
    public function case1($mtx){
	    for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) {
	    	if ($s%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";
	    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesRep"][$s]["salesRep"]."</th></table>";
	    	for ($t=0; $t < sizeof($mtx["case1"]["value"][$s]); $t++) { 
		    	echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
		    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:5%;'>".$mtx["tier"][$t]."</td>";
		    			echo "<td style='width:10%;' class='lightGrey'></td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td style='width:16%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
		    			}
		    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='rcBlue'>Plan</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["planValue"][$s][$t][$q],0)."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalPlanValueTier"][$s][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='medBlue'>Actual</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='medBlue'>".number_format($mtx["case1"]["value"][$s][$t][$q],0)."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalValueTier"][$s][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='rcBlue'>Var Abs</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["varAbs"][$s][$t][$q],0)."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalVarAbs"][$s][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='medBlue'>Var %</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='medBlue'>".number_format($mtx["case1"]["varPrc"][$s][$t][$q],0)."%</td>";
		    			}
		    			echo "<td class='darkBlue'>".number_format($mtx["case1"]["totalVarPrc"][$s][$t],0)."%</td>";
		    		echo "</tr>";
		    	echo "</table>";

	    	}
	    	echo "<table style='width: 100%;' class='mt-3'>";
	    		echo "<tr>";
	    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>TT</td>";
	    			echo "<td style='width:10%;' class='lightGrey'></td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td style='width:16%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
	    			}
	    			echo "<td style='width:18%;' class='darkBlue' >Total</td>";
	    		echo "</tr>";
	    		echo "<tr>";
	    			echo "<td class='rcBlue'>Plan</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["totalPlanSG"][$s][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue' >".number_format($mtx["case1"]["totalPlanTotalSG"][$s],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='medBlue'>Actual</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='medBlue'>".number_format($mtx["case1"]["totalSG"][$s][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue'>".number_format($mtx["case1"]["totalTotalSG"][$s],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='rcBlue'>Var Abs</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["totalSGVarAbs"][$s][$q],0)."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue' >".number_format($mtx["case1"]["totalTotalSGVarAbs"][$s],0)."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='medBlue'>Var %</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='medBlue'>".number_format($mtx["case1"]["totalSGVarPrc"][$s][$q],0)."%</td>";
	    			}
    				echo "<td style='width:18%;' class='darkBlue' >".number_format($mtx["case1"]["totalTotalSGVarPrc"][$s],0)."%</td>";
	    		echo "</tr>";
		   	echo "</table>";

	    	echo "</div>";

	    	if ($s%2 == 1) {
	    		echo "</div>";
	    	}

	    }
    }
    
    public function case2($mtx){
    	for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	if ($s%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesRep"][$s]["salesRep"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$b][1])."' style='width:5%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:10%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:16%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%' class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["planValue"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalPlanValueBrand"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["value"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalValueBrand"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["varAbs"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalVarAbs"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["varPrc"][$s][$b][$q],0)."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case2"]["totalVarPrc"][$s][$b],0)."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:10%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:16%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%' class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["dnPlanValue"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalPlanValue"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["dnValue"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalValue"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["dnVarAbs"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalVarAbs"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["dnVarPrc"][$s][$q],0)."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case2"]["dnTotalVarPrc"][$s],0)."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	if ($s%2 == 1) {
	    		echo "</div>";
	    	}
    	}
    }
    
    public function case3($mtx){
    	for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesRep"][$s]["salesRep"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["tier"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$b])."' style='width:5%;'>".$mtx["tier"][$b]."</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["planValues"][$s][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalPlanValueTier"][$s][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["values"][$s][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalValueTier"][$s][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["varAbs"][$s][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalVarAbs"][$s][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["varPrc"][$s][$b][$q])."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case3"]["totalVarPrc"][$s][$b])."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>TT</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["dnPlanValue"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalPlanValue"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["dnValue"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalValue"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["dnVarAbs"][$s][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalVarAbs"][$s],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["dnVarPrc"][$s][$q],0)."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case3"]["dnTotalVarPrc"][$s],0)."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
    	}
    
    }
    
    public function case4($mtx){
	    for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesRep"][$s]["salesRep"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$b][1])."' style='width:5%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["planValues"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalPlanValueTier"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["values"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalValueTier"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["varAbs"][$s][$b][$q],0)."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalVarAbs"][$s][$b],0)."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["varPrc"][$s][$b][$q],0)."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case4"]["totalVarPrc"][$s][$b],0)."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["dnPlanValue"][$s][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalPlanValue"][$s])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["dnValue"][$s][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalValue"][$s])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["dnVarAbs"][$s][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalVarAbs"][$s])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["dnVarPrc"][$s][$q])."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case4"]["dnTotalVarPrc"][$s][$q])."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
	    	
    	}
    }
}
