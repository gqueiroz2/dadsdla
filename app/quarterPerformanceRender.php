<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterPerformanceRender extends Render {
    
    public function assemble($mtx, $region, $currency, $value, $year){
    	var_dump($mtx);
		echo "<table style='width: 100%; zoom: 80%;'>";
			echo "<tr>";
				echo "<th colspan='9' class='lightBlue'><center><span style='font-size: 24px;'>$region - Quarter $year (".$currency[0]['name']."/".strtoupper($value).")</span></center></th>";
			echo "</tr>";
			echo "<tr>";
				echo "<th colspan='9' class='lightBlue'><center><span style='font-size: 24px;'>Sales Group: ".$mtx["salesRepGroup"]."</span></center></th>";
			echo "</tr>";
			echo "<tr>";
				echo "<th colspan='9' class='lightBlue'><center><span style='font-size: 24px;'>Sales Representative: ".$mtx["salesRep"]."</span></center></th>";
			echo "</tr>";

			echo "<tr><td>&nbsp;</td></tr>";

		echo "</table>";
    }
}
