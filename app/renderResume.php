<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderResume extends Render {
    
    public function assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix, $type){
    	/*
        if ($type == "Digital") {
            $salesShow = "FREE WHEEL";
        }elseif($type == "DN"){
            $salesShow .= " + FW";
        }
        */

    	echo "<table class='table table-bordered' style='width: 100%;'>";
    		echo "<tr>";
    			echo "<th class='darkBlue center' colspan='11'><span style='font-size:18px; font-weight: normal !important; '>$salesRegion - $type Summary : $salesShow - $cYear ($currencyS/$valueS) </span> </th>";
    		echo "</tr>";

    		echo "<tr>";
    			echo "<th class='darkBlue center' style='width:5%'> MONTH </th>";
                echo "<th class='lightBlue center' style='font-weight: bold !important; width:10%;'> BKGS $cYear</th>";
    			echo "<th class='lightBlue center' style='font-weight: bold !important; width:10%;'> SAP $cYear </th>";
    			echo "<th class='darkBlue center' style='width:10%'> TARGET $cYear </th>";
    			echo "<th class='darkBlue center' style='width:12.5%'> CORP. FCST $cYear </th>";
    			//<th class="darkBlue"> P&R FCST </th>
				//<th class="darkBlue"> Finance FCST </th>
				echo "<th class='darkBlue center' style='width:10%'> BKGS $pYear </th>";	
				echo "<th class='grey center' style='font-weight: bold !important; width:12.5%;'> BKGS/TARGET </th>";
				echo "<th class='grey center' style='font-weight: bold !important; width:15%;'> BKGS/CORP. FCST </th>";
				//<th class="grey"> Sales/P&R </th>
				//<th class="grey"> Sales/Finance </th>
				echo "<th class='grey center' style='font-weight: bold !important; width:12.5%;'> BKGS ($cYear/$pYear) </th>";
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
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['sales'], 0, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['actual'], 0, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['target'], 0, ",", ".")." </td>";
    				echo "<td class='$bck'> ".number_format( $matrix[$m]['corporate'], 0, ",", ".")." </td>";
    				//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['pAndR']) }} </td>
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['finance']) }} </td>
					echo "<td class='$bck'> ".number_format( $matrix[$m]['pYear'], 0, ",", ".")." </td>";
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesOverTarget'], 0, ",", ".")." % </td>";
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesOverCorporate'], 0, ",", ".")." % </td>";
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverPAndR']) }} </td>
					//<td class="{{$bck}}">  {{ number_format( $matrix[$m]['salesOverFinance']) }} </td>
					echo "<td class='$bck'> ".number_format( $matrix[$m]['salesYoY'], 0, ",", ".")." % </td>";
    			echo "</tr>";
    		}

    	echo "</table>";
    }
}
