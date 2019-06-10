<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class shareRender extends Render{

    public function mtx($mtx){

   		echo "<table style='font-size: 16px;'>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'>Share (".$mtx["source"].") - Sales Group: ".$mtx["salesRepGroup"]." - (".$mtx["currency"]."/".$mtx["value"].")</th>";
			echo "</tr>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'> Sales Representative: ".$mtx["salesRepView"]." </th>";
			echo "</tr>";
			echo "<tr>";
				echo "<td style='width:15%;' class='lightBlue'>Sales Rep</td>";
				for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) {
					echo "<td style='width:5%;' class='lightBlue'>";
	 					echo $mtx["brand"][$b];
    				echo "</td>"; 	
		    	}
	    		if ($mtx["dn"]) {
		    		echo "<td style='width:5%' class='darkBlue'>DN</td>";
		    	}
				echo "<td style='width:5%' class='lightBlue'>Share</td>";
	    	echo "</tr>";
	    	///Começa o Corpo do Codigo
	    	for ($s=0; $s <sizeof($mtx["salesRep"]); $s++) { 
		    	if ($s%2 == 1) {
		    		$string = "odd";
		    	}else{
		    		$string = "rcBlue";
		    	}
	    		echo "<tr class='".$string."'>";
    				echo "<td>".$mtx["salesRep"][$s]."</td>";
	    			for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    				echo "<td>".(number_format($mtx["values"][$b][$s], ",", "."))."</td>";
	    			}
	    			if ($mtx["dn"]) {
		    			echo "<td class='smBlue'>".(number_format($mtx["dn"][$s], ",", "."))."</td>";
	    			}
		    		echo "<td>".(number_format($mtx["share"][$s], ",", "."))."%</td>";
	    		echo "</tr>";
	    	}
	    	//Começa o total
	    	echo "<tr class='darkBlue'>";
		    	echo "<td  >Total</td>";
	    		for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    			echo "<td>".(number_format($mtx["total"][$b], ",", "."))."</td>";
	    		}
				echo "<tr>";
					echo "<td style='width:15%;' class='lightBlue'>Sales Rep</td>";
					for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) {
						echo "<td style='width:5%;' class='lightBlue'>";
		 					echo $mtx["brand"][$b];
	    				echo "</td>"; 	
			    	}
		    		if ($mtx["dn"]) {
			    		echo "<td style='width:5%' class='darkBlue'>DN</td>";
			    	}
			    	echo "<td style='width:0.5%'> &nbsp; </td>";
					echo "<td style='width:5%' class='lightBlue'>Share</td>";
		    	echo "</tr>";
		    	///Começa o Corpo do Codigo
		    	for ($s=0; $s <sizeof($mtx["salesRep"]); $s++) { 
			    	if ($s%2 == 1) {
			    		$string = "odd";
			    	}else{
			    		$string = "rcBlue";
			    	}
		    		echo "<tr>";
	    				echo "<td class='".$string."'>".$mtx["salesRep"][$s]."</td>";
		    			for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
		    				echo "<td class='".$string."'>".(number_format($mtx["values"][$b][$s], ",", "."))."</td>";
		    			}
		    			if ($mtx["dn"]) {
			    			echo "<td class='smBlue'>".(number_format($mtx["dn"][$s], ",", "."))."</td>";
		    			}
		    			echo "<td> &nbsp; </td>";
			    		echo "<td class='".$string."'>".(number_format($mtx["share"][$s], ",", "."))."%</td>";
		    		echo "</tr>";
		    	}
		    	//Começa o total
		    	echo "<tr>";
			    	echo "<td class='darkBlue'>Total</td>";
		    		for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
		    			echo "<td class='darkBlue'>".(number_format($mtx["total"][$b], ",", "."))."</td>";
		    		}

		    		if ($mtx["dn"]) {
			    		echo "<td class='darkBlue'>".number_format($mtx["totalT"], ",", ".")."</td>";
			    	}
			    	echo "<td> &nbsp; </td>";
			    	echo "<td class='darkBlue'>100%</td>";

		    	echo "</tr>";
			echo "</table>";

			

		echo "</div>";

    }
}
