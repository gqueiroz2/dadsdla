<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderResume extends Render {
    
    public function assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix, $type){
    	
    	echo "<table class='table table-bordered' style='width: 100%;'>";
    		echo "<tr>";
    			echo "<th class='darkBlue center' colspan='11'><span style='font-size:18px; font-weight: normal !important; '>$salesRegion - $type Summary : $salesShow - $cYear (currencyS/$valueS) </span> </th>";
    		echo "</tr>";

    		echo "<tr>";
    			echo "<th class='darkBlue center'> MONTH </th>";
    			echo "<th class='lightBlue center' style='font-weight: bold !important;'>". strtoupper($salesShow)."</th>";
    			echo "<th class='lightBlue center' style='font-weight: bold !important;'> ACTUAL </th>";
    			echo "<th class='darkBlue center'> TARGET </th>";
    			echo "<th class='darkBlue center'> CORPORATE </th>";
    			//<th class="darkBlue"> P&R FCST </th>
				//<th class="darkBlue"> Finance FCST </th>
				echo "<th class='darkBlue center'> $pYear </th>";	
				echo "<th class='grey center' style='font-weight: bold !important;'>".strtoupper($salesShow)."/TARGET </th>";
				echo "<th class='grey center' style='font-weight: bold !important;'>".strtoupper($salesShow)."/CORPORATE </th>";
				//<th class="grey"> Sales/P&R </th>
				//<th class="grey"> Sales/Finance </th>
				echo "<th class='grey center' style='font-weight: bold !important;'> $salesShow/$pYear </th>";
    		echo "</tr>";

    		for ($m=0; $m < sizeof($matrix); $m++) { 
    			if ($matrix[$m]['month'] == "Total") {
    				$bck = "darkBlue";
    				$matrix[$m]['month'] = strtoupper($matrix[$m]['month']);
    			}else{
    				if ($m%2 == 0){
    					$bck = 'odd';
    				}else{
    					$bck = 'even';
    				}
    			}

    			$bck .= " center";

    			echo "<tr>";
    				echo "<td class='$bck'> ".$matrix[$m]['month']." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['sales'], 2, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['actual'], 2, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['target'], 2, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['corporate'], 2, ",", ".")." </td>";
    				//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['pAndR']) }} </td>
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['finance']) }} </td>
					echo "<td class='$bck'> ".number_format( $matrix[$m]['pYear'], 2, ",", ".")." </td>";
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesOverTarget'], 2, ",", ".")."% </td>";
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesOverCorporate'], 2, ",", ".")."% </td>";
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverPAndR']) }} </td>
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverFinance']) }} </td>
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesYoY'], 2, ",", ".")."% </td>";
    			echo "</tr>";
    		}

    	echo "</table>";
    }
}
