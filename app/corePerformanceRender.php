<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class corePerformanceRender extends renderPerformance {
	
    public function case1($mtx,$cYear){
    	
    	$size = 64/sizeof($mtx["quarters"]);

    	echo "<div class='row'>";
	    	if (sizeof($mtx["salesGroup"]) == 1) {
	    		echo "<div class='col-sm-8' style='width:100%; margin-left:auto; margin-right: auto;'>";
    		}else{
	    		echo "<div class='col-sm' style='width:100%; margin-left:auto; margin-right: auto;'>";
    		}
    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold; font-size:22px;' >".$mtx["region"]." - Core ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - BKGS</th></tr></table>";
    		echo "</div>";
    	echo "</div>";
	    for ($sg=0; $sg < sizeof($mtx["case1"]["value"]); $sg++) {
	    	if ($sg%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	if (sizeof($mtx["salesGroup"]) == 1) {
		    	echo "<div class='col-sm-8' style='width:100%;  margin-left:auto; margin-right: auto;'>";
	    	}else{
		    	echo "<div class='col-sm-6' style='width:100%; margin-left:auto; margin-right: auto;'>";
	    	}
	    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >".$mtx["salesGroup"][$sg]["name"]."</th></table>";
	    	for ($t=0; $t < sizeof($mtx["case1"]["value"][$sg]); $t++) { 
		    	echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
		    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:5%;'>";
		    			if ($mtx["tier"][$t] == "TOTH") {
		    				echo "OTH";
		    			}else{
		    				echo $mtx["tier"][$t];
		    			}
		    			echo "</td>";
		    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
		    			}
		    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='rcBlue'> Target $cYear</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["planValue"][$sg][$t][$q],0,",",".")."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalPlanValueTier"][$sg][$t],0,",",".")."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='medBlue'> BKGS $cYear </td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='medBlue'>".number_format($mtx["case1"]["value"][$sg][$t][$q],0,",",".")."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalValueTier"][$sg][$t],0,",",".")."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='rcBlue'>Var Abs</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["varAbs"][$sg][$t][$q],0,",",".")."</td>";
		    			}
		    			echo "<td class='smBlue'>".number_format($mtx["case1"]["totalVarAbs"][$sg][$t],0,",",".")."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td class='medBlue'>Var %</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td class='medBlue'>".number_format($mtx["case1"]["varPrc"][$sg][$t][$q],0,",",".")."%</td>";
		    			}
		    			echo "<td class='darkBlue'>".number_format($mtx["case1"]["totalVarPrc"][$sg][$t],0,",",".")."%</td>";
		    		echo "</tr>";
		    	echo "</table>";
	    	}
	    	echo "<table style='width: 100%;' class='mt-3'>";
	    		echo "<tr>";
	    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>TT</td>";
	    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
	    			}
	    			echo "<td style='width:18%;' class='darkBlue' >Total</td>";
	    		echo "</tr>";
	    		echo "<tr>";
	    			echo "<td class='rcBlue'> Target $cYear</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["totalPlanSG"][$sg][$q],0,",",".")."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue' >".number_format($mtx["case1"]["totalPlanTotalSG"][$sg],0,",",".")."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='medBlue'> BKGS $cYear </td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='medBlue'>".number_format($mtx["case1"]["totalSG"][$sg][$q],0,",",".")."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue'>".number_format($mtx["case1"]["totalTotalSG"][$sg],0,",",".")."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='rcBlue'>Var Abs</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='rcBlue'>".number_format($mtx["case1"]["totalSGVarAbs"][$sg][$q],0,",",".")."</td>";
	    			}
    				echo "<td style='width:18%;' class='smBlue' >".number_format($mtx["case1"]["totalTotalSGVarAbs"][$sg],0,",",".")."</td>";
	    		echo "</tr>";
	    		echo "<tr>";
		    		echo "<td class='medBlue'>Var %</td>";
	    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
	    				echo "<td class='medBlue'>".number_format($mtx["case1"]["totalSGVarPrc"][$sg][$q],0,",",".")."%</td>";
	    			}
    				echo "<td style='width:18%;' class='darkBlue' >".number_format($mtx["case1"]["totalTotalSGVarPrc"][$sg],0,",",".")."%</td>";
	    		echo "</tr>";
		   	echo "</table>";

	    	
	    	echo "</div>";

	    	if ($sg%2 == 1 || sizeof($mtx["salesGroup"]) == 1) {
	    		echo "</div>";
	    	}
	    }
	    if (sizeof($mtx["salesGroup"])>1) {
	    	if(sizeof($mtx["case1"]["value"])%2 == 0){
		    	echo "<div class='row'>";
	    	}
	    		echo "<div class='col-sm-6'>";
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
				    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
				    			}
				    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'> Target $cYear</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["planValues"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalPlanValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'> BKGS $cYear </td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["values"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["varAbs"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["totalVarAbs"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["varPrc"][$t][$q],0,",",".")."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case1"]["totalVarPrc"][$t],0,",",".")."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>TT</td>";
			    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%;' class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["dnPlanValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case1"]["dnTotalPlanValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["dnValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case1"]["dnTotalValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case1"]["dnVarAbs"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case1"]["dnTotalVarAbs"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case1"]["dnVarPrc"][$q],0,",",".")."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case1"]["dnTotalVarPrc"],0,",",".")."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }


    }
    
    public function case2($mtx,$cYear){
    	$size = 64/sizeof($mtx["quarters"]);

    	echo "<div class='row'>";
	    	if (sizeof($mtx["salesGroup"]) == 1) {
	    		echo "<div class='col-sm-8' style='width:100%; margin-left:auto; margin-right: auto;'>";
    		}else{
	    		echo "<div class='col-sm' style='width:100%; margin-left:auto; margin-right: auto;'>";
    		}
    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold; font-size:22px;' >".$mtx["region"]." - Core ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - BKGS</th></tr></table>";
	    	echo "</div>";
    	echo "</div>";
    	for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	if ($sg%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	if (sizeof($mtx["salesGroup"]) == 1) {
		    	echo "<div class='col-sm-8' style='width:100%; margin-left:auto; margin-right: auto;'>";
	    	}else{
		    	echo "<div class='col-sm'>";
	    	}

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$b][1])."' style='width:5%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%' class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["planValue"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalPlanValueBrand"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["value"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalValueBrand"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["varAbs"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["totalVarAbs"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["varPrc"][$sg][$b][$q],0,",",".")."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case2"]["totalVarPrc"][$sg][$b],0,",",".")."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%'>".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%' class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["dnPlanValue"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalPlanValue"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["dnValue"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalValue"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case2"]["dnVarAbs"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case2"]["dnTotalVarAbs"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case2"]["dnVarPrc"][$sg][$q],0,",",".")."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case2"]["dnTotalVarPrc"][$sg],0,",",".")."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	if ($sg%2 == 1 ||sizeof($mtx["salesGroup"]) == 1) {
	    		echo "</div>";
	    	}
    	}
    	if (sizeof($mtx["salesGroup"])>1) {
 	    	if(sizeof($mtx["case2"]["value"])%2 == 0){
		    	echo "<div class='row'>";
	    	}
	    		echo "<div class='col-sm-6'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["brand"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
			    				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$t][1])."' style='width:5%;'>".$mtx["brand"][$t][1]."</td>";
				    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
				    			}
				    			echo "<td style='width:18%;' class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'> Target $cYear</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["planValues"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalPlanValueBrand"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'> BKGS $cYear </td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["values"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalValueBrand"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["varAbs"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["totalVarAbs"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["varPrc"][$t][$q],0,",",".")."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case2"]["totalVarPrc"][$t],0,",",".")."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:5%;'>DN</td>";
			    			echo "<td style='width:12.5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["quarters"][$q]."</td>";
			    			}
			    			echo "<td style='width:18%;' class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["dnPlanValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case2"]["dnTotalPlanValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["dnValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case2"]["dnTotalValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case2"]["dnVarAbs"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case2"]["dnTotalVarAbs"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case2"]["dnVarPrc"][$q],0,",",".")."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case2"]["dnTotalVarPrc"],0,",",".")."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }

    }
    
    public function case3($mtx,$cYear){
    	$size = 84/sizeof($mtx["month"]);

    	echo "<div class='row' >";
    		echo "<div class='col-sm' style='width:100%;  margin-left:auto; margin-right: auto;'>";
    			echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold; font-size:22px;' >".$mtx["region"]." - Core ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - BKGS</th></tr></table>";
	    		
	    	echo "</div>";
    	echo "</div>";
    	for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col-sm' style='margin-left: auto; margin-right: auto; width:100%;'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["tier"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$b])."' style='width:3%;'>";
			    			if ($mtx["tier"][$b] == "TOTH") {
			    				echo "OTH";
			    			}else{
			    				echo $mtx["tier"][$b];
			    			}
			    			echo "</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["planValues"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalPlanValueTier"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["values"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalValueTier"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["varAbs"][$sg][$b][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["totalVarAbs"][$sg][$b])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["varPrc"][$sg][$b][$q])."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case3"]["totalVarPrc"][$sg][$b])."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:3%;'>TT</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["dnPlanValue"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalPlanValue"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["dnValue"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalValue"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case3"]["dnVarAbs"][$sg][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case3"]["dnTotalVarAbs"][$sg],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case3"]["dnVarPrc"][$sg][$q],0,",",".")."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case3"]["dnTotalVarPrc"][$sg],0,",",".")."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
    	}
    	if (sizeof($mtx["salesGroup"])>1) {
	    	echo "<div class='row'>";
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
				   				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["tier"][$t])."' style='width:3%;'>";
				    			if ($mtx["tier"][$t] == "TOTH") {
				    				echo "OTH";
				    			}else{
				    				echo $mtx["tier"][$t];
				    			}
				    			echo "</td>";
				    			echo "<td style='width:5%;' class='lightGrey'></td>";
					    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
					    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
					    			}
				    			echo "<td class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'> Target $cYear</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["planValues"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalPlanValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'> BKGS $cYear </td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["values"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["varAbs"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["totalVarAbs"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["varPrc"][$t][$q],0,",",".")."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case3"]["totalVarPrc"][$t],0,",",".")."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:3%;'>TT</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td  class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["dnPlanValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case3"]["dnTotalPlanValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["dnValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case3"]["dnTotalValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case3"]["dnVarAbs"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case3"]["dnTotalVarAbs"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case3"]["dnVarPrc"][$q],0,",",".")."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case3"]["dnTotalVarPrc"],0,",",".")."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    
    }
    
    public function case4($mtx,$cYear){
    	$size = 84/sizeof($mtx["month"]);
    	echo "<div class='row' >";
	    	echo "<div class='col-sm' style='width:100%; margin-left: auto; margin-right: auto;'>";
	    		echo "<table style='width:100%;' class='t1'><tr><th style='font-weight: bold; font-size:22px;' >".$mtx["region"]." - Core ".$mtx["year"]." (".$mtx["currency"]."/".$mtx["valueView"].") - BKGS</th></tr></table>";
	    	echo "</div>";
    	echo "</div>";
	    for ($sg=0; $sg < sizeof($mtx["salesGroup"]); $sg++) {
	    	echo "<div class='row'>";
	    	
	    	echo "<div class='col'>";

		    	echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >".$mtx["salesGroup"][$sg]["name"]."</th></table>";

		    	for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
			    	echo "<table style='width: 100%;' class='mt-3'>";
			    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$b][1])."' style='width:3%;'>".$mtx["brand"][$b][1]."</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["planValues"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalPlanValueTier"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["values"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalValueTier"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["varAbs"][$sg][$b][$q],0,",",".")."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["totalVarAbs"][$sg][$b],0,",",".")."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["varPrc"][$sg][$b][$q],0,",",".")."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case4"]["totalVarPrc"][$sg][$b],0,",",".")."%</td>";
		    			echo "</tr>";
		    		echo "</table>";
		    	}
			    echo "<table style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:3%;'>DN</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td class='darkBlue'>Total</td>";
		    			echo "</tr>";
			    		echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["dnPlanValue"][$sg][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalPlanValue"][$sg])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["dnValue"][$sg][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalValue"][$sg])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["case4"]["dnVarAbs"][$sg][$q])."</td>";
			    			}
			    			echo "<td class='smBlue'>".number_format($mtx["case4"]["dnTotalVarAbs"][$sg])."</td>";
		    			echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["case4"]["dnVarPrc"][$sg][$q])."%</td>";
			    			}
			    			echo "<td class='darkBlue'>".number_format($mtx["case4"]["dnTotalVarPrc"][$sg])."%</td>";
		    			echo "</tr>";
		    		
		    	echo "</table>";
	    	
	    	echo "</div>";
	    	echo "</div>";
	    	
    	}
    	if (sizeof($mtx["salesGroup"])>1) {
	    	echo "<div class='row'>";
	    		echo "<div class='col'>";
	    			echo "<table  class='salesGroupClick darkBlue' style='width:100%; margin-top:1.5%;'><th style='font-weight: bold;' >Total</th></table>";
    				for ($t=0; $t < sizeof($mtx["brand"]); $t++) { 
			    		echo "<table style='width: 100%;' class='mt-3'>";
		    				echo "<tr>";
				   				echo "<td rowspan='5' class='tierClick ".strtolower($mtx["brand"][$t][1])."' style='width:3%;'>";
				    				echo $mtx["brand"][$t][1];
				    			echo "</td>";
				    			echo "<td style='width:5%;' class='lightGrey'></td>";
					    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
					    				echo "<td class='quarterClick lightGrey' style='width:".$size."%;'>".$mtx["month"][$q]."</td>";
					    			}
				    			echo "<td class='darkBlue'>Total</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'> Target $cYear</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["planValues"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalPlanValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'> BKGS $cYear </td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["values"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalValueTier"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='rcBlue'>Var Abs</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["varAbs"][$t][$q],0,",",".")."</td>";
				    			}
				    			echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["totalVarAbs"][$t],0,",",".")."</td>";
				    		echo "</tr>";
				    		echo "<tr>";
				    			echo "<td class='medBlue'>Var %</td>";
				    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
				    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["varPrc"][$t][$q],0,",",".")."%</td>";
				    			}
				    			echo "<td class='darkBlue'>".number_format($mtx["total"]["case4"]["totalVarPrc"][$t],0,",",".")."%</td>";
				    		echo "</tr>";
			   			echo "</table>";
	   				}
	    			echo "<table style='width: 100%;' class='mt-3'>";
			   			echo "<tr>";
			    			echo "<td rowspan='5' class='tierClick dn' style='width:3%;'>DN</td>";
			    			echo "<td style='width:5%;' class='lightGrey'></td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td style='width:".$size."%;' class='quarterClick lightGrey' >".$mtx["month"][$q]."</td>";
			    			}
			    			echo "<td  class='darkBlue' >Total</td>";
			    		echo "</tr>";
		    			echo "<tr>";
			    			echo "<td class='rcBlue'> Target $cYear</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["dnPlanValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case4"]["dnTotalPlanValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'> BKGS $cYear </td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["dnValue"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue'>".number_format($mtx["total"]["case4"]["dnTotalValue"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='rcBlue'>Var Abs</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='rcBlue'>".number_format($mtx["total"]["case4"]["dnVarAbs"][$q],0,",",".")."</td>";
			    			}
		    				echo "<td class='smBlue' >".number_format($mtx["total"]["case4"]["dnTotalVarAbs"],0,",",".")."</td>";
			    		echo "</tr>";
			    		echo "<tr>";
				    		echo "<td class='medBlue'>Var %</td>";
			    			for ($q=0; $q <sizeof($mtx["month"]); $q++) { 
			    				echo "<td class='medBlue'>".number_format($mtx["total"]["case4"]["dnVarPrc"][$q],0,",",".")."%</td>";
			    			}
		    				echo "<td class='darkBlue' >".number_format($mtx["total"]["case4"]["dnTotalVarPrc"],0,",",".")."%</td>";
			    		echo "</tr>";
		   			echo "</table>";
	    		echo "</div>";
	    	echo "</div>";
	    }
    }
}
