<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class executivePerformanceRender extends renderPerformance
{
    public function case1($mtx){
    	echo "<div class='row' >";
	    	echo "<div class='col' style='width:100%;'>";
	    		echo "<center>";
	    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold;' >".$mtx["region"]." - Executive ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - IBMS</th></tr></table>";
	    		echo "</center>";
	    	echo "</div>";
    	echo "</div>";

	    for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) {
	    	if ($s%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";
	    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;'>".$mtx["salesRep"][$s]["salesRep"]." - ".$mtx["salesRep"][$s]["salesRepGroup"]."</th></table>";
	    	for ($t=0; $t < sizeof($mtx["case1"]["value"][$s]); $t++) { 
		    	echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
		    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:5%;'>";
		    			if ($mtx["tier"][$t] == "TOTH") {
		    				echo "OTH";
		    			}else{
		    				echo $mtx["tier"][$t];
		    			}
		    			echo "</td>";
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
	    if (sizeof($mtx["salesRep"])>1) {
	    	if(sizeof($mtx["case1"]["value"])%2 == 0){
		    	echo "<div class='row'>";
	    	}
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
				   				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:5%;'>";
				    			if ($mtx["tier"][$t] == "TOTH") {
				    				echo "OTH";
				    			}else{
				    				echo $mtx["tier"][$t];
				    			}
				    			echo "</td>";
				    			echo "<td style='width:10%;' class='lightGrey'></td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td style='width:16%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
				    			}
				    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Plan</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["planValues"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalPlanValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Actual</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["values"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["varAbs"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalVarAbs"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["varPrc"][$t][$q],0)."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case1"]["totalVarPrc"][$t],0)."%</td>";
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
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["dnPlanValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case1"]["dnTotalPlanValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["dnValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["dnTotalValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["dnVarAbs"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case1"]["dnTotalVarAbs"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["dnVarPrc"][$q],0)."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case1"]["dnTotalVarPrc"],0)."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    }
    
    public function case2($mtx){
    	echo "<div class='row' >";
	    	echo "<div class='col' style='width:100%;'>";
	    		echo "<center>";
	    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold;' >".$mtx["region"]." - Executive ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - IBMS</th></tr></table>";
	    		echo "</center>";
	    	echo "</div>";
    	echo "</div>";


    	for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	if ($s%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;'>".$mtx["salesRep"][$s]["salesRep"]." - ".$mtx["salesRep"][$s]["salesRepGroup"]."</th></table>";

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
    	if (sizeof($mtx["salesRep"])>1) {
	    	if(sizeof($mtx["case2"]["value"])%2 == 0){
		    	echo "<div class='row'>";
		    }
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["brand"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
			    				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$t][1])."' style='width:5%;'>".$mtx["brand"][$t][1]."</td>";
				    			echo "<td style='width:10%;' class='lightGrey'></td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td style='width:16%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
				    			}
				    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Plan</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["planValues"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalPlanValueBrand"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Actual</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["values"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalValueBrand"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["varAbs"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalVarAbs"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["varPrc"][$t][$q],0)."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case2"]["totalVarPrc"][$t],0)."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:10%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td style='width:16%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%;' class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["dnPlanValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case2"]["dnTotalPlanValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["dnValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["dnTotalValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["dnVarAbs"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case2"]["dnTotalVarAbs"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["dnVarPrc"][$q],0)."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case2"]["dnTotalVarPrc"],0)."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    }
    
    public function case3($mtx){

    	echo "<div class='row' >";
	    	echo "<div class='col' style='width:100%;'>";
	    		echo "<center>";
	    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold;' >".$mtx["region"]." - Executive ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - IBMS</th></tr></table>";
	    		echo "</center>";
	    	echo "</div>";
    	echo "</div>";

    	for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;'>".$mtx["salesRep"][$s]["salesRep"]." - ".$mtx["salesRep"][$s]["salesRepGroup"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["tier"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$b])."' style='width:5%;'>";
			    			if ($mtx["tier"][$b] == "TOTH") {
			    				echo "OTH";
			    			}else{
			    				echo $mtx["tier"][$b];
			    			}
			    			echo "</td>";
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
    	if (sizeof($mtx["salesRep"])>1) {
	    	echo "<div class='row'>";
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
				   				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:5%;'>";
				    			if ($mtx["tier"][$t] == "TOTH") {
				    				echo "OTH";
				    			}else{
				    				echo $mtx["tier"][$t];
				    			}
				    			echo "</td>";
				    			echo "<td style='width:5%;' class='lightGrey'></td>";
					    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
					    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
					    			}
				    			echo "<td class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Plan</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["planValues"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalPlanValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Actual</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["values"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["varAbs"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalVarAbs"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["varPrc"][$t][$q],0)."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case3"]["totalVarPrc"][$t],0)."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>TT</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td style='width:7%;' class='quarterClick lightGrey' >".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["dnPlanValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case3"]["dnTotalPlanValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["dnValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["dnTotalValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["dnVarAbs"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case3"]["dnTotalVarAbs"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["dnVarPrc"][$q],0)."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case3"]["dnTotalVarPrc"],0)."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    }
    
    public function case4($mtx){

    	echo "<div class='row' >";
	    	echo "<div class='col' style='width:100%;'>";
	    		echo "<center>";
	    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold;' >".$mtx["region"]." - Executive ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - IBMS</th></tr></table>";
	    		echo "</center>";
	    	echo "</div>";
    	echo "</div>";

	    for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;'>".$mtx["salesRep"][$s]["salesRep"]." - ".$mtx["salesRep"][$s]["salesRepGroup"]."</th></table>";

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
			    			echo "<td class='darkBlue'>".number_format($mtx["case4"]["dnTotalVarPrc"][$s])."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
	    	
    	}
    	if (sizeof($mtx["salesRep"])>1) {
	    	echo "<div class='row'>";
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["brand"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
				   				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$t][1])."' style='width:5%;'>";
				    				echo $mtx["brand"][$t][1];
				    			echo "</td>";
				    			echo "<td style='width:5%;' class='lightGrey'></td>";
					    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
					    				echo "<td class='quarterClick lightGrey' style='width:7%;'>".$mtx["month"][$q]."</td>";
					    			}
				    			echo "<td class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Plan</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["planValues"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalPlanValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Actual</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["values"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalValueTier"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["varAbs"][$t][$q],0)."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalVarAbs"][$t],0)."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["varPrc"][$t][$q],0)."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case4"]["totalVarPrc"][$t],0)."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td style='width:7%;' class='quarterClick lightGrey' >".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Plan</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["dnPlanValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case4"]["dnTotalPlanValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Actual</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["dnValue"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["dnTotalValue"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["dnVarAbs"][$q],0)."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case4"]["dnTotalVarAbs"],0)."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["dnVarPrc"][$q],0)."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case4"]["dnTotalVarPrc"],0)."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    }
}
