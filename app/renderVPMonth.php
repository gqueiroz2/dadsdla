<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderVPMonth extends Render {
    
    public function assemble($mtx, $value, $currency, $region){
    	
    	echo "<table style='width: 100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='".sizeof($mtx[0])."' class='lightBlue'><center><span style='font-size:24px;'> $region - (".$currency[0]['name']."/".strtoupper($value).")</span></center></th>";
        	echo "</tr>";

            echo "<tr><td> &nbsp; </td></tr>";

            for ($m=0; $m < sizeof($mtx); $m++) { 
            	echo "<tr>";
            	for ($v=0; $v < sizeof($mtx[$m]); $v++) { 
            		if (is_numeric($mtx[$m][$v])) {
            			echo "<td>".number_format($mtx[$m][$v])."</td>";
            		}else{
            			echo "<td>".$mtx[$m][$v]."</td>";
            		}
            	}
            	echo "</tr>";
            }

            echo "</table>";
    }
}
