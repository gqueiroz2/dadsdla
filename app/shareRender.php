<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class shareRender extends Render{

    public function mtx($mtx){
   		echo "<table>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'>Share (".$mtx["source"].") - Sales Group: ".$mtx["salesRepGroup"]." - (".$mtx["currency"]."/".$mtx["value"].")</th>";
			echo "</tr>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'> Sales Representative: ".$mtx["salesRepView"]." </th>";
			echo "</tr>";
			echo "<tr class='lightBlue'>";
				echo "<th style='width:15%;'>Sales Rep</th>";
				for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
    	 			echo "<th style='width:5%;'>";
    	 				echo $mtx["brand"][$b];
	    			echo "</th>";
		    	}
	    		if ($mtx["dn"]) {
		    		echo "<th style='width:5%'>DN</th>";
		    	}
				echo "<th style='width:5%'>Share</th>";
	    	echo "</tr>";
	    	///Começa o Corpo do Codigo
	    	for ($s=0; $s <sizeof($mtx["salesRep"]); $s++) { 
		    	if ($s%2 == 1) {
		    		$string = "odd";
		    	}else{
		    		$string = "rcBlue";
		    	}
	    		echo "<tr class='".$string."'>";
    				echo "<td  >".$mtx["salesRep"][$s]."</td>";
	    			for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    				echo "<td>".(number_format($mtx["values"][$b][$s]))."</td>";
	    			}
	    			if ($mtx["dn"]) {
		    			echo "<td>".(number_format($mtx["dn"][$s]))."</td>";
	    			}
		    		echo "<td>".(number_format($mtx["share"][$s]))."%</td>";
	    		echo "</tr>";
	    	}
	    	//Começa o total
	    	echo "<tr class='darkBlue'>";
		    	echo "<td  >Total</td>";
	    		for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    			echo "<td>".(number_format($mtx["total"][$b]))."</td>";
	    		}

	    		if ($mtx["dn"]) {
		    		echo "<td>".number_format($mtx["totalT"])."</td>";
		    	}
		    	echo "<td>100%</td>";

	    	echo "</tr>";
		echo "</table>";

    }
}
