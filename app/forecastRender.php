<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class forecastRender extends Render{

	protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');
    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');
    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function loadForecast($forRender){

        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        
        $salesRep = $forRender['salesRep'];
        $client = $forRender['client'];
        $splitted = $forRender['splitted'];
        
        $targetValuesDiscovery = $forRender['targetValuesDiscovery'];
        $targetValuesSony = $forRender['targetValuesSony'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        //$tfArray = $forRender["readable"]["tfArray"];
        
        //$manualEstimation = $forRender["readable"]["manualEstimation"];
        //$color2 = $forRender["readable"]["color"];

        $rollingFCSTDisc = $forRender['rollingFCSTDisc'];
        $rollingFCSTSony = $forRender['rollingFCSTSony'];
        
        $lastRollingFCSTDisc = $forRender['lastRollingFCSTDisc'];
        $lastRollingFCSTSony = $forRender['lastRollingFCSTSony'];

        $clientRevenueCYearDisc = $forRender['clientRevenueCYearDisc'];
        $clientRevenueCYearSony = $forRender['clientRevenueCYearSony'];
        
        $clientRevenuePYearDisc = $forRender['clientRevenuePYearDisc'];
        $clientRevenuePYearSony = $forRender['clientRevenuePYearSony'];

        //$executiveRF = $forRender["executiveRF"];
        $executiveRevenueCYearDisc = $forRender["executiveRevenueCYearDisc"];
        $executiveRevenueCYearSony = $forRender["executiveRevenueCYearSony"];

        $executiveRevenuePYearDisc = $forRender["executiveRevenuePYearDisc"];
        $executiveRevenuePYearSony = $forRender["executiveRevenuePYearSony"];

        //$pending = $forRender["pending"];
        //$RFvsTarget = $forRender["RFvsTarget"];
        //$targetAchievement = $forRender["targetAchievement"];

        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $region = $forRender["region"];

        //$currencyName = $forRender["currencyName"];
        //$valueView = $forRender["valueView"];

        //$fcstAmountByStage = $forRender["fcstAmountByStage"];
        //$fcstAmountByStageEx = $forRender["fcstAmountByStageEx"];
        //$brandsPerClient = $forRender["brandsPerClient"];
        
        $emptyCheckDisc = $forRender["emptyCheckDisc"];
        $emptyCheckSony = $forRender["emptyCheckSony"];

        //$nSecondary = $forRender["nSecondary"];

        echo "<input type='hidden' id='salesRep' name='salesRep' value='".base64_encode(json_encode($salesRep))."'>";
        echo "<input type='hidden' id='client' name='client' value='".base64_encode(json_encode($client)) ."'>";
        echo "<input type='hidden' id='currency' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' id='splitted' name='splitted' value='".base64_encode(json_encode($splitted))."'>";
        echo "<input type='hidden' id='value' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' id='region' name='region' value='".base64_encode(json_encode($region))."'>";
        //echo "<input type='hidden' id='user' name='user' value='".base64_encode(json_encode($userName))."'>";
        echo "<input type='hidden' id='year' name='year' value='".base64_encode(json_encode($cYear))."'>";
        //echo "<input type='hidden' id='year' name='brandsPerClient' value='".base64_encode(json_encode($brandsPerClient))."'>";

        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>";
            //echo "<tr><th class='lightBlue'>".$salesRep['salesRep']." - ".$currencyName."/".$valueView."</th></tr>";
            echo "<tr><th class='lightBlue'>".$salesRep['salesRep']."</th></tr>";
        echo "</table>
        </div>";

        /*
        if($error) {
            echo "<br>";
            echo "<div class=\"alert alert-danger\" style=\"width:50%;\">";
                echo $error;
            echo "</div>";
        }
        */

        echo "<br>";
        echo "<div class='sticky-top' style='zoom:80%; scroll-margin-botton: 10px;'>";
        	echo "<div class='row'>";
       			echo "<div class='col-2' style='padding-right:1px;'>";
        			echo "<table class='' id='example' style='width:100%; text-align:center; min-height:225px;'>";
            			echo "<tr>";
                			echo "<td class='darkBlue' style=' border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; font-size:20px; height:40px; '>".$salesRep['abName']."</td>";
            			echo "</tr>";
			            echo "<tr>";
			                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Rolling Fcast ".$cYear."</span><br>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Bookings</td>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Pending</td>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".$pYear."</td>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Target</td>";
			            echo "</tr>";
			            echo "<tr>";
			                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>% Target Achievement</td>";
			            echo "</tr>";
        			echo "</table>";
        		echo "</div>";

        		echo "<div class='col linked table-responsive ' style='width:100%; padding-left:0px;'>";
        			echo "<table style='min-width:3000px; width:80%; text-align:center; min-height:225px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>";
			            /* START OF SALES REP AND SALES REP TOTAL MONTHS */
			            echo "<thead>";
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) {
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='quarter' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>".$this->month[$m]."</td>";
			                    }else{
			                        echo "<td class='smBlue' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; height:40px;'>".$this->month[$m]."</td>";
			                    }
			                }
			                echo "<td class='darkBlue' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>Total</td>";
			                echo "<td style='width:0.5%;'>&nbsp</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Closed</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Cons. (%)</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Exp</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Prop</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Adv</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Contr</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Total</td>";
			                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Lost</td>";
			            echo "</tr>";
			            echo "</thead>";			            
			            /* END OF SALES REP AND SALES REP TOTAL MONTHS */

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        				number_format(
			                        						($targetValuesDiscovery[$m] + $targetValuesSony[$m])
			                        						,2,',','.')
			                        				."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValuesDiscovery[$m] + $targetValuesSony[$m];
			                    }else{
			                        echo "<td class='$even[$m]'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        					number_format(
			                        						( $targetValuesDiscovery[$m] + $targetValuesSony[$m] )
			                        						,2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>
			                			<input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".
			                				number_format(
			                					( $targetValuesDiscovery[$m] + $targetValuesSony[$m] )
			                					,2,',','.')
			                				."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                }
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>
			                        			<input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".
			                        				number_format(
			                        					//$executiveRF[$m]
			                        					666
			                        				,2,',','.')
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='$odd[$m]'>
			                        			<input type='text' name='fcstSalesRep-$m' name='rf-$m' readonly='true' id='rf-$m' value='".
			                        				number_format(
			                        					//$executiveRF[$m]
			                        					777
			                        				,2,',','.')
			                        			."' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>
			                			<input type='text' name='total-total' readonly='true' id='total-total' value='".
			                				number_format(
			                					//$executiveRF[$m]
			                					888
			                					,2,',','.')
			                			."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'>
			                	 </td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                			//number_format($fcstAmountByStageEx[1][4],2,',','.')
			                			676
			                	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                			//number_format($fcstAmountByStageEx[1][7],2,',','.')
			                			676
			                	  ."% </td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][0],2,',','.')
			                			676
			                  	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][1],2,',','.')
			                			676
			                  	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][2],2,',','.')
			                			676
			                  	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][3],2,',','.')
			                			676
			                  	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][6],2,',','.')
			                			676
			                  	 ."</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".
			                  			//number_format($fcstAmountByStageEx[1][5],2,',','.')
			                			676
			                  	 ."</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            /*
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }else{
			                        echo "<td class='$even[$m]' ><input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m],2,',','.')."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
			            echo "</tr>";
			            */
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            /*
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }else{
			                        echo "<td class='$odd[$m]' ><input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m],2,',','.')."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
			            echo "</tr>";
			            */
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            /*
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }else{
			                        echo "<td class='$even[$m]'><input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m],2,',','.')."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m],2,',','.')."' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
			            echo "</tr>";
			            */
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            /*
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m],2,',','.')."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }else{
			                        echo "<td class='$odd[$m]'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m],2,',','.')."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' ><input type='text' readonly='true' id='TotalRFvsTarget' name='TotalRFvsTarget' value='".number_format($RFvsTarget[$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
			                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
			            echo "</tr>";
			            */
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */ 
			            /*
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m],2,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }else{
			                        echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m],2,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;' ><input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m],2,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
			                echo "<td>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
			                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
			            echo "</tr>";
			            */
			            /* END % TARGET ACHIEVEMENT */

           
            			echo "</tbody>";
        			echo "</table>";
        		echo "</div>";
        	echo "</div>";
        echo "</div>";
        echo "<br>";        

        for ($c=0; $c < sizeof($client); $c++) {
            if($splitted){
                if($splitted[$c]['splitted']){ $clr = "lightBlue"; }
                else{ $clr = "lightBlue"; }   
                if($splitted[$c]['splitted']){
                    if(is_null($splitted[$c]['owner'])){
                        $ow = "(?)";
                    }else{
                        if($splitted[$c]['owner']){
                            $ow = "(P)";
                        }else{
                            $ow = "(S)";
                        }
                    }
                }else{
                    $ow = "";
                }
            }else{
                $clr = "lightBlue";     
                $ow = false;               
            }
            /*
            if (round($nSecondary[$c]['lastRollingFCST'][16])-round($nSecondary[$c]['rollingFCST'][16]) < 5 && round($nSecondary[$c]['lastRollingFCST'][16])-round($nSecondary[$c]['rollingFCST'][16]) > -5) {
                $nSecondary[$c]['lastRollingFCST'][16] = $nSecondary[$c]['rollingFCST'][16];
                $color = "";
                $boolfcst = "1";
            }elseif (round($nSecondary[$c]['lastRollingFCST'][16]) != round($nSecondary[$c]['rollingFCST'][16])) {
                $color = "red";
                $boolfcst = "0";
            }else{
                $color = "";
                $boolfcst = "1";
            }
			*/

            var_dump($client);
            /*
            echo "<div class='' style='zoom:80%;'>";
            	echo "<div class='row'>";
            		echo "<div class='col-2' style='padding-right:1px;'>";
            			echo "<table id='table-$c' style='width:100%; text-align:center; overflow:auto; min-height: 180px;' >";
               				echo "<tr>";
                    			echo "<td class='$clr' id='client-$c' rowspan='1' style=' text-align:center; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; background-color: $color '>
                    					<span style='font-size:18px; '> 
                    						".$nSecondary[$c]['clientName']." - ".$nSecondary[$c]["agencyName"]." $ow 
                    						</span>";
                			echo "</tr>";
			                echo "<tr>";
			                    echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'> Rolling Fcast ".$cYear." </td>";
			                echo "</tr>";
			                echo "<tr>";
			                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Manual Estimation";
			                echo "</tr>";
			                echo "<tr>";
			                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Booking</td>";
			                echo "</tr>";
			                echo "<tr>";
			                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr>";
			                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>Var RF vs ".$pYear."</td>";
			                echo "</tr>";
            			echo "</table>";
            		echo "</div>";

            	echo "<div class='col linked table-responsive' style='padding-left:0px;'>";



            echo "<table id='table-$c' style='min-width:3000px; width:100%; text-align:center; overflow:auto; min-height: 180px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;' >";
                
                /* 
                    START OF CLIENT NAME AND MONTHS
                *//*

                

                echo "<input type='text' id='splitted-$c' name='splitted-$c' value='$ow' style='display:none;'>";

                echo "<tr>";

                    
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' id='quarter-$c-$m' rowspan='1' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; '>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='smBlue' colspan='1' id='month-$c-$m' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; '>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue' id='TotalTitle-$c' rowspan='1' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; '>Total</td>";
                    echo "<td rowspan='6' id='division-$c' style='width:0.5%;'>&nbsp</td>";
                    echo "<td id='sideTable-$c-0' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Closed</td>";
                    echo "<td id='sideTable-$c-1' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Cons.(%)</td>";
                    echo "<td id='sideTable-$c-2' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Exp</td>";
                    echo "<td id='sideTable-$c-3' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Prop</td>";
                    echo "<td id='sideTable-$c-4' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Adv</td>";
                    echo "<td id='sideTable-$c-5' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Contr</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";
                    echo "<td id='sideTable-$c-7' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Lost</td>";

                echo "</tr>";
                /* END OF CLIENT NAME AND MONTHS */
                
                /* START OF CLIENT ROLLING FORECAST *//*
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['lastRollingFCST'][$m],2,',','.')."</td>";
                        }else{
                            echo "<td class='$even[$m]'>".number_format($nSecondary[$c]['lastRollingFCST'][$m],2,',','.')."</td>";
                    
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' id='passTotal-$c' name='passTotal-$c' readonly='true' value='".number_format($nSecondary[$c]['lastRollingFCST'][$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center; color:white;'></td>";
 
                    if ($nSecondary[$c]['fcstAmountByStage']) {
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][4],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][7],2,',','.')."%</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][0],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][1],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][2],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][3],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][6],2,',','.')."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['fcstAmountByStage'][1][5],2,',','.')."</td>";
                    }else{
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00%</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";    
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST */ 

                /* START OF CLIENT MANUAL ESTIMATION */ /*               
                echo "<tr>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='clientRF-$c-$m' name='clientRF-$c-$m' value='".number_format($nSecondary[$c]['rollingFCST'][$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                        }else{
                            echo "<td class='$odd[$m]' style='".$manualEstimation[$m]."'>";
                                echo "<input type='text' name='fcstClient-$c-$m' id='clientRF-$c-$m' ".$tfArray[$m]." value='".number_format($nSecondary[$c]['rollingFCST'][$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center;".$color2[$m]."'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalClient-$c' name='totalClient-$c' value='".number_format($nSecondary[$c]['rollingFCST'][$m],2,',','.')."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";

                /* END OF CLIENT MANUAL ESTIMATION */

                /* START OF CLIENT BOOKING */  /*              
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($nSecondary[$c]['clientRevenueCYear'][$m],2,',','.')."</td>";
                        }else{
                            echo "<td class='$even[$m]' >".number_format($nSecondary[$c]['clientRevenueCYear'][$m],2,',','.')."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>".number_format($nSecondary[$c]['clientRevenueCYear'][$m],2,',','.')."</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING */ 
                
                /* START OF CLIENT PAST YEAR */   /*              
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($nSecondary[$c]['clientRevenuePYear'][$m],2,',','.')."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }else{
                            echo "<td class='$odd[$m]'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($nSecondary[$c]['clientRevenuePYear'][$m],2,',','.')."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPY-$c' name='totalPY-$c' value='".number_format($nSecondary[$c]['clientRevenuePYear'][$m],2,',','.')."' style='width:100%; color:white; background-color:transparent; font-weight:bold; border:none; text-align:center'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT PAST YEAR */               

                /* START OF CLIENT RF VS PYEAR */ /*           
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $nSecondary[$c]['rollingFCST'][$m] - $nSecondary[$c]['clientRevenuePYear'][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>";                            
                                echo "<input type='text' readonly='true' id='RFvsPY-$c-$m' name='RFvsPY-$c-$m' value='".number_format($tmp,2,',','.')."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                        }else{
                            echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>";                            
                                echo "<input type='text' name='RFvsPY-$c-$m' readonly='true' id='RFvsPY-$c-$m' value='".number_format($tmp,2,',','.')."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'><input type='text' id='totalRFvsPY-$c' name='totalRFvsPY-$c' readonly='true' value='".number_format($nSecondary[$c]['rollingFCST'][$m] - $nSecondary[$c]['clientRevenuePYear'][$m],2,',','.')."' style='width:100%; font-weight:bold; background-color:transparent; border:none; color:white; text-align:center'></td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR */
                /*
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>"; */
        }  

    }


}
