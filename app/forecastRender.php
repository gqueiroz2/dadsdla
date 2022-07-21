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
        $targetValues = $forRender['targetValues'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        $tfArray = $forRender["readable"]["tfArray"];        
        $manualEstimation = $forRender["readable"]["manualEstimation"];
        $color = $forRender["readable"]["color"];
        $color2 = $forRender["readable"]["color2"];

        $rollingFCSTDisc = $forRender['rollingFCSTDisc'];
        $rollingFCSTSony = $forRender['rollingFCSTSony'];
        
        $lastRollingFCSTDisc = $forRender['lastRollingFCSTDisc'];
        $lastRollingFCSTSony = $forRender['lastRollingFCSTSony'];

        $clientRevenueCYearDisc = $forRender['clientRevenueCYearDisc'];
        $clientRevenueCYearSony = $forRender['clientRevenueCYearSony'];
        
        $clientRevenuePYearDisc = $forRender['clientRevenuePYearDisc'];
        $clientRevenuePYearSony = $forRender['clientRevenuePYearSony'];

        $executiveRF = $forRender["executiveRF"];
        $executiveRFDisc = $forRender["executiveRFDisc"];
        $executiveRFSony = $forRender["executiveRFSony"];
        $executiveRevenueCYearDisc = $forRender["executiveRevenueCYearDisc"];
        $executiveRevenueCYearSony = $forRender["executiveRevenueCYearSony"];
        $executiveRevenueCYear = $forRender["executiveRevenueCYear"];

        $executiveRevenuePYearDisc = $forRender["executiveRevenuePYearDisc"];
        $executiveRevenuePYearSony = $forRender["executiveRevenuePYearSony"];
        $executiveRevenuePYear = $forRender["executiveRevenuePYear"];

        $pending = $forRender["pending"];
        $pendingDisc = $forRender["pendingDisc"];
        $pendingSony = $forRender["pendingSony"];
        $RFvsTarget = $forRender["RFvsTarget"];
        $RFvsTargetDisc = $forRender["RFvsTargetDisc"];
        $RFvsTargetSony = $forRender["RFvsTargetSony"];
        $targetAchievement = $forRender["targetAchievement"];
        $targetAchievementDisc = $forRender["targetAchievementDisc"];
        $targetAchievementSony = $forRender["targetAchievementSony"];

        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $region = $forRender["region"];

        $currencyName = $forRender["currencyName"];
        $valueView = $forRender["valueView"];

        $fcstAmountByStageDisc = $forRender["fcstAmountByStageDisc"];
        $fcstAmountByStageSony = $forRender["fcstAmountByStageSony"];
        $fcstAmountByStage = $forRender["fcstAmountByStage"];
        
        $fcstAmountByStageExDisc = $forRender["fcstAmountByStageExDisc"];
        $fcstAmountByStageExSony = $forRender["fcstAmountByStageExSony"];
        $fcstAmountByStageEx = $forRender["fcstAmountByStageEx"];
        $brandsPerClient = $forRender["brandsPerClient"];
        
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
        echo "<input type='hidden' id='year' name='brandsPerClient' value='".base64_encode(json_encode($brandsPerClient))."'>";

        echo "<input type='hidden' id='clickBoolHeader' value='1'>";

        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style='width:100%; text-align:center; font-size:25px;'>";
            echo "<tr><th class='lightBlue'>".$salesRep['salesRep']." - ".$currencyName."/".$valueView."</th></tr>";
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
        echo "<div class='sticky-top' style='zoom:75%; scroll-margin-botton: 10px;'>";
        	echo "<div class='row'>";
       			echo "<div class='col-2' style='padding-right:1px;'>";
        			echo "<table id='example' style='width:100%; text-align:center; min-height:225px;'>";
            			echo "<tr>";
            				echo "<td style='height:30px; background-color: #FFFFFF;'>&nbsp;</td>";
                			echo "<td class='darkBlue' style='text-align:center;  width:25%;'>
                    					<span style='font-size:18px;'>".$salesRep['abName']." </span></td>";
            			echo "</tr>";
            			echo "<tr class='clickBoolHeader'>";
                			echo "<td class='darkBlue' id='' rowspan='7' style='text-align:center; border-bottom: 1pt solid black;  width:5.5%;'>
                					<span style='font-size:12px;'>";
                						echo " TT "; 
                						echo "</span>";
                			echo "</td>";
			            	echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; border-bottom: 1pt solid black; height:25px;'>% Target Achievement</td>";
							echo "</tr>";
						echo "</tr>";
						

			            /* INICIO DISC */
			            echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
                			echo "<td class='dc' id='' rowspan='8' style=' text-align:CENTER; border-bottom: 1pt solid black; width:5.5%; height:25px;'>
                					<span style='font-size:12px;'>";
                						echo " DISC "; 
                						echo "</span>";
                			echo "</td>";
                		echo "</tr>";

		                echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader' >";
			                echo "<td class='rcBlue' style='text-align:left; border-bottom: 1pt solid black; height:25px;'>% Target Achievement</td>";
			            echo "</tr>";
		                /* FIM  DISC */


			                /* INICIO SONY */
		                echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
                			echo "<td class='sony' id='' rowspan='9' style='text-align:center; border-bottom: 1pt solid black; width:5.5%; height:25px;'>
                					<span style='font-size:12px;'>";
                						echo " SONY "; 
                					echo "</span>";
                			echo "</td>";
                		echo "</tr>";
		                
		               echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px; border-bottom: 1pt solid black;'>% Target Achievement</td>";
		                /* FIM SONY */

        			echo "</table>";
        		echo "</div>";
        		echo "<div class='col linked table-responsive ' style='width:100%; padding-left:0px;'>";
        			echo "<table style='min-width:3000px; width:80%; text-align:right; min-height:225px;'>";
			            /* START OF SALES REP AND SALES REP TOTAL MONTHS */
			            echo "<thead>";
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) {
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                       echo "<td class='quarter' id='quarter-$m'  style='text-align:right; width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp </td>";
		                        }else{
		                            echo "<td style='text-align:right; ".$color2[$m]."' class='smBlue' id='month-$m' style='text-align:right; width:3%;  height:30px;'>".$this->month[$m]."&nbsp &nbsp</td>";
			                    }
			                }
			                echo "<td class='darkBlue' style=' text-align:right; width:3%; height:30px;'>Total &nbsp &nbsp</td>";
			                echo "<td style=' text-align:right; width:0.5%; background-color: #ffffff;'>&nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Closed &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Cons. (%) &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Exp &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Prop &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Adv &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Contr &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Total &nbsp &nbsp</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            echo "</thead>";			            
			            /* END OF SALES REP AND SALES REP TOTAL MONTHS */

			            /*TT SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='text-align:right; width:3%;'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        				number_format(
			                        						($targetValues[$m])
			                        						)
			                        				."' style=' width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValues[$m];
			                    }else{
			                        echo "<td class='even' style='text-align:right; width:3%;'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        					number_format(
			                        						( $targetValues[$m] )
			                        						)."' style=' width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue' style='text-align:right; width:3%; height:25px;'>
			                			<input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".
			                				number_format(
			                					( $targetValues[$m] )
			                					)
			                				."' style='text-align:right; width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			               	echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>
			                        			<input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".
			                        				number_format(
			                        					$executiveRF[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd' style='text-align:right; width:3%;'>
			                        			<input type='text' name='rf-$m' readonly='true' id='rf-$m' value='".
			                        				number_format(
			                        					$executiveRF[$m]
			                        				)
			                        			."' style=' width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='text-align:right; width:3%; height:25px;'>
			                			<input type='text' name='total-total' readonly='true' id='total-total' value='".
			                				number_format(
			                					$executiveRF[$m]
			                					)
			                			."' style=' width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right;'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                			number_format($fcstAmountByStageEx[1][4])
			                	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                			number_format($fcstAmountByStageEx[1][7])
			                	  ."% </td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][0])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][1])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][2])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][3])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even' style='width:3%;'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m])."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTarget' name='totalRFvsTarget' value='".number_format($RFvsTarget[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";
			            /* END % TARGET ACHIEVEMENT */

			             /*DISC SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr class='clickLoopHeader'>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue''>
			                        			<input type='text' readonly='true' id='targetD-$m' name='targetD-$m' value='".
			                        				number_format(
			                        						($targetValuesDiscovery[$m])
			                        						)
			                        				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValuesDiscovery[$m];
			                    }else{
			                        echo "<td class='even'>
			                        			<input type='text' readonly='true' id='targetD-$m' name='targetD-$m' value='".
			                        					number_format(
			                        						( $targetValuesDiscovery[$m] )
			                        						)."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue style='height:25px;'>
			                			<input type='text' readonly='true' id='totalTargetD' name='totalTargetD' value='".
			                				number_format(
			                					( $targetValuesDiscovery[$m] )
			                					)
			                				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>
			                        			<input type='text' readonly='true' id='rfD-$m' name='rfD-$m' value='".
			                        				number_format(
			                        					$executiveRFDisc[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd'>
			                        			<input type='text' name='rfD-$m' readonly='true' id='rfD-$m' value='".
			                        				number_format(
			                        					$executiveRFDisc[$m]
			                        				)
			                        			."' style='width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' name='total-totalDisc' readonly='true' id='total-totalDisc' value='".
			                				number_format(
			                					$executiveRFDisc[$m]
			                					)
			                			."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExDisc[1][4])
			                	 ."</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExDisc[1][7])
			                	  ."% </td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][0])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][1])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][2])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][3])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingED-$m' name='bookingED-$m' value='".number_format($executiveRevenueCYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingED-$m' value='".number_format($executiveRevenueCYearDisc[$m])."' name='bookingED-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingED' name='totalBookingED' value='".number_format($executiveRevenueCYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' name='pendingD-$m' id='pendingD-$m' value='".number_format($pendingDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='pendingD-$m' value='".number_format($pendingDisc[$m])."' name='pendingD-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPendingDisc' name='totalPendingDisc' value='".number_format($pendingDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYearDisc[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYearDisc[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetD-$m' value='".number_format($RFvsTargetDisc[$m])."' name='RFvsTargetD-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetD-$m' value='".number_format($RFvsTargetDisc[$m])."' name='RFvsTargetD-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTargetD' name='totalRFvsTargetD' value='".number_format($RFvsTargetDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' name='achievementD-$m' id='achievementD-$m' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>";
			                        	echo "<input type='text' readonly='true' name='achievementD-$m' id='achievementD-$m' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievementD' name='totalAchievementD' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";

			             /*Sony SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr class='clickLoopHeader'>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue''>
			                        			<input type='text' readonly='true' id='targetS-$m' name='targetS-$m' value='".
			                        				number_format(
			                        						($targetValuesSony[$m])
			                        						)
			                        				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValuesSony[$m];
			                    }else{
			                        echo "<td class='even'>
			                        			<input type='text' readonly='true' id='targetS-$m' name='targetS-$m' value='".
			                        					number_format(
			                        						( $targetValuesSony[$m] )
			                        						)."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' readonly='true' id='totalTargetS' name='totalTargetS' value='".
			                				number_format(
			                					( $targetValuesSony[$m] )
			                					)
			                				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>
			                        			<input type='text' readonly='true' id='rfS-$m' name='rfS-$m' value='".
			                        				number_format(
			                        					$executiveRFSony[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd'>
			                        			<input type='text' name='rfS-$m' readonly='true' id='rfS-$m' value='".
			                        				number_format(
			                        					$executiveRFSony[$m]
			                        				)
			                        			."' style='width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' name='total-totalSony' readonly='true' id='total-totalSony' value='".
			                				number_format(
			                					$executiveRFSony[$m]
			                					)
			                			."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExSony[1][4])
			                	 ."</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExSony[1][7])
			                	  ."% </td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][0])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][1])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][2])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][3])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingES-$m' name='bookingES-$m' value='".number_format($executiveRevenueCYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingES-$m' value='".number_format($executiveRevenueCYearSony[$m])."' name='bookingES-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingES' name='totalBookingES' value='".number_format($executiveRevenueCYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' name='pendingS-$m' id='pendingS-$m' value='".number_format($pendingSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='pendingS-$m' value='".number_format($pendingSony[$m])."' name='pendingS-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPendingSony' name='totalPendingSony' value='".number_format($pendingSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYearSony[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYearSony[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetS-$m' value='".number_format($RFvsTargetSony[$m])."' name='RFvsTargetS-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetS-$m' value='".number_format($RFvsTargetSony[$m])."' name='RFvsTargetS-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTargetS' name='totalRFvsTargetS' value='".number_format($RFvsTargetSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' name='achievementS-$m' id='achievementS-$m' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>";
			                        	echo "<input type='text' readonly='true' name='achievementS-$m' id='achievementS-$m' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievementS' name='totalAchievementS' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";	                
			            echo "</tr>";
           
            			echo "</tbody>";
        			echo "</table>";
        		echo "</div>";
        	echo "</div>";
        echo "</div>";
        echo "<br>";        


        

        for ($c=0; $c < sizeof($client); $c++) {

            if($splitted){
                if($splitted[$c]['splitted']){ 
                	$clr = "lightBlue";
                	$ow = "";
                }else{ 
                	$clr = "lightBlue"; 
                	$ow = false;
                }   
                
            }else{
                $clr = "lightBlue";     
                $ow = false;               
            }

            if($splitted){
                if($splitted[$c]['splitted']){
                    if(is_null($splitted[$c]['owner'])){
                        $ow = "(?)";
                    }else{
                        if($splitted[$c]['sales_rep_owner_id'] == $salesRep['id']){
                            $ow = "(P)";
                        }else{
                            $ow = "(S)";
                        }
                    }
                }else{
                    $ow = "";
                }
            }else{
                $ow = false;
            }

            $color = "";
            $boolfcst = "1";

            echo "<input type='hidden' id='clickBool-$c' value='1'>";

            echo "<div class='' style='zoom:75%;'>";
            	echo "<div class='row mt-3'>";
            		echo "<div class='col-2' style='padding-right:1px;'>";
            			echo "<table id='table-$c' style='width:100%; text-align:right; overflow:auto; min-height: 180px;' >";

                    		echo "<tr>";
                    			
                    			echo "<td style='height:30px;'> &nbsp; </td>";
                    			echo "<td  class='darkBlue' id='client-$c' style='text-align:center; height:30px; width:25%;'>
                    					<span style='font-size:14px;'>";
                    						echo "".$client[$c]['clientName']." - ".$client[$c]["agencyName"] ." $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                			echo "</tr>";

                			 /* INICIO TT */
			                echo "<tr class='clickBool-$c'>";
                    			echo "<td class='darkBlue' id='client-$c' rowspan='5' style='text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " TT $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                                echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
                                echo "<tr>";
			                        echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                    echo "</tr>";
			                    echo "<tr>";
			                        echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'>Booking</td>";
			                    echo "</tr>";
			                    echo "<tr>";
			                        echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>".$pYear."</td>";
			                    echo "</tr>";
			                    echo "<tr style='border-bottom: 1pt solid black;'>";
			                        echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                    echo "</tr>";
                    		echo "</tr>";
			                /* FIM TT */

                			/*INICIO DISC */
                			echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    			echo "<td class='dc' id='client-$c' rowspan='6' style=' text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " DISC $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                    		echo "</tr>";

                			
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Booking</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                echo "</tr>";
			                /* FIM  DISC */


			                /* INICIO SONY */
			                echo "<tr class='clickLoop-$c'>";
                    			echo "<td class='sony' id='client-$c' rowspan='6' style='text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " SONY $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                    		echo "</tr>";

			                
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'>Booking</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                echo "</tr>";
			                /* FIM SONY */

			               

            			echo "</table>";
            		echo "</div>";

            	echo "<div class='col linked table-responsive' style='padding-left:0px;'>";



            echo "<table id='table-$c' style='min-width:3000px; width:100%; text-align:right; overflow:auto; min-height: 180px;'>";
                /* START OF CLIENT NAME AND MONTHS */

                echo "<input type='text' id='splitted-$c' name='splitted-$c' value='$ow' style='display:none;'>";
                echo "<tr>";
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' id='quarter-$c-$m' style='width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp</td>";
                        }else{
                            echo "<td style='".$color2[$m]."' class='smBlue' id='month-$c-$m' style='width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp</td>";
                        }
                    }
                    echo "<td class='darkBlue' id='TotalTitle-$c' rowspan='1' style='width:3%;'>Total &nbsp &nbsp</td>";
                    echo "<td rowspan='16' id='division-$c' style='width:0.5%; border-bottom: 1pt solid white;'>&nbsp</td>";
                    echo "<td id='sideTable-$c-0' rowspan='1' class='lightGrey' style='width:3%;'>Closed &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-1' rowspan='1' class='lightGrey' style='width:3%;'>Cons.(%) &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-2' rowspan='1' class='lightGrey' style='width:3%;'>Exp &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-3' rowspan='1' class='lightGrey' style='width:3%;'>Prop &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-4' rowspan='1' class='lightGrey' style='width:3%;'>Adv &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-5' rowspan='1' class='lightGrey' style='width:3%;'>Contr &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' class='lightGrey' style='width:3%;'>Total &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' style='width:0.42%; border-bottom:1pt solid white;' colspan=6;> &nbsp</td>";

                echo "</tr>";
                /* END OF CLIENT NAME AND MONTHS */

                /* START OF CLIENT ROLLING FORECAST TT*/
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."</td>";             
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' id='passTT-Total-$c' name='passTT-Total-$c' readonly='true' value='".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageSony[$c]) {
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][4] + $fcstAmountByStageSony[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][7] + $fcstAmountByStageSony[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][0] + $fcstAmountByStageSony[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][1] + $fcstAmountByStageSony[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][2] + $fcstAmountByStageSony[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][3] + $fcstAmountByStageSony[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][6] + $fcstAmountByStageSony[$c][1][6])."</td>";
                       
                    }else{
	                    for ($i=0; $i < 7; $i++) { 
	                    	echo "<td class='rcBlue'>&nbsp</td>";
	                    }   
	                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST TT*/ 

                /* START OF CLIENT MANUAL ESTIMATION TT*/            
                echo "<tr>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-TT-$c-$m' name='clientRF-TT-$c-$m' value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>"; 
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>";
                                echo "<input type='text' readonly='true' name='fcstClient-TT-$c-$m' id='clientRF-TT-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                   		echo "<input type='text' readonly='true' id='totalClient-TT-$c' name='totalClient-TT-$c' value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>"; 
                   	echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    } 
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION TT*/

                /* START OF CLIENT BOOKING TT*/          
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                            echo "<td id='booking-TT-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING TT*/ 
                
                /* START OF CLIENT PAST YEAR TT*/            
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";


                /* END OF CLIENT PAST YEAR TT*/               

                /* START OF CLIENT RF VS PYEAR TT*/         
                echo "<tr style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = ($rollingFCSTDisc[$c][$m] + $rollingFCSTSony[$c][$m]) - ($clientRevenuePYearDisc[$c][$m] + $clientRevenuePYearSony[$c][$m]) ;
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format(($rollingFCSTDisc[$c][$m] + $rollingFCSTSony[$c][$m]) - ($clientRevenuePYearDisc[$c][$m] + $clientRevenuePYearSony[$c][$m]) )."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR TT*/

                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/


                
                /* START OF CLIENT ROLLING FORECAST DISC */
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($lastRollingFCSTDisc[$c][$m])."</td>";                    
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' id='passTotal-DISC-$c' name='passTotal-DISC-$c' readonly='true' value='".number_format($lastRollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageDisc[$c]) {                    	
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][6])."</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                   
                    }else{
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00%</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST DISC */ 

                /* START OF CLIENT MANUAL ESTIMATION DISC */            
                echo "<tr class='clickLoop-$c'>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-DISC-$c-$m' name='clientRF-DISC-$c-$m' value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>";
                                echo "<input type='text' name='fcstClient-DISC-$c-$m' id='clientRF-DISC-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' readonly='true' id='totalClient-DISC-$c' name='totalClient-DISC-$c' value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>";
                    echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION DISC*/

                /* START OF CLIENT BOOKING DISC*/          
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING DISC*/ 
                
                /* START OF CLIENT PAST YEAR DISC*/            
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT PAST YEAR DISC*/               

                /* START OF CLIENT RF VS PYEAR DISC*/         
                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCSTDisc[$c][$m] - $clientRevenuePYearDisc[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($rollingFCSTDisc[$c][$m] - $clientRevenuePYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR DISC*/


                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/


                /* START OF CLIENT ROLLING FORECAST SONY*/
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($lastRollingFCSTSony[$c][$m])."</td>";                    
                        }
                    }
                    echo "<td class='smBlue'>";
                    	echo "<input type='text' id='passTotal-SONY-$c' name='passTotal-SONY-$c' readonly='true' value='".number_format($lastRollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageSony[$c]) {
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][6])."</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }else{
	                    for ($i=0; $i < 7; $i++) { 
	                    	echo "<td class='rcBlue'>&nbsp</td>";
	                    } 
	                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>"; 
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST SONY*/ 

                /* START OF CLIENT MANUAL ESTIMATION SONY*/            
                echo "<tr class='clickLoop-$c'>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-SONY-$c-$m' name='clientRF-SONY-$c-$m' value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>"; 
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>";
                                echo "<input type='text' name='fcstClient-SONY-$c-$m' id='clientRF-SONY-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue'>";
                   		echo "<input type='text' readonly='true' id='totalClient-SONY-$c' name='totalClient-SONY-$c' value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>"; 
                   	echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    } 
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION SONY*/

                /* START OF CLIENT BOOKING SONY*/          
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING SONY*/ 
                
                /* START OF CLIENT PAST YEAR SONY*/            
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";


                /* END OF CLIENT PAST YEAR SONY*/               

                /* START OF CLIENT RF VS PYEAR SONY*/         
                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCSTSony[$c][$m] - $clientRevenuePYearSony[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none; border-bottom: 1pt solid black;'></td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($rollingFCSTSony[$c][$m] - $clientRevenuePYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR SONY*/

                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/

            echo "</table>";         



            echo "</div>";
            echo "</div>";
            echo "</div>";
 

        }  

    }

    public function loadFcstBrazil($forRender){

        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        
        $salesRep = $forRender['salesRep'];
        $client = $forRender['client'];
        $splitted = $forRender['splitted'];
        
        $targetValuesDiscovery = $forRender['targetValuesDiscovery'];
        $targetValuesSony = $forRender['targetValuesSony'];
        $targetValues = $forRender['targetValues'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        $tfArray = $forRender["readable"]["tfArray"];        
        $manualEstimation = $forRender["readable"]["manualEstimation"];
        $color = $forRender["readable"]["color"];
        $color2 = $forRender["readable"]["color2"];

        $cmapsDisc = $forRender['cmapsDisc'];
        $cmapsSony = $forRender['cmapsSony'];

        $cmapsTotal = $forRender['cmapsTotal'];

        $cmapsClientDisc = $forRender['cmapsClientDisc'];
        $cmapsClientSony = $forRender['cmapsClientSony'];

        $rollingFCSTDisc = $forRender['rollingFCSTDisc'];
        $rollingFCSTSony = $forRender['rollingFCSTSony'];
        
        $lastRollingFCSTDisc = $forRender['lastRollingFCSTDisc'];
        $lastRollingFCSTSony = $forRender['lastRollingFCSTSony'];

        $clientRevenueCYearDisc = $forRender['clientRevenueCYearDisc'];
        $clientRevenueCYearSony = $forRender['clientRevenueCYearSony'];
        
        $clientRevenuePYearDisc = $forRender['clientRevenuePYearDisc'];
        $clientRevenuePYearSony = $forRender['clientRevenuePYearSony'];

        $executiveRF = $forRender["executiveRF"];
        $executiveRFDisc = $forRender["executiveRFDisc"];
        $executiveRFSony = $forRender["executiveRFSony"];
        $executiveRevenueCYearDisc = $forRender["executiveRevenueCYearDisc"];
        $executiveRevenueCYearSony = $forRender["executiveRevenueCYearSony"];
        $executiveRevenueCYear = $forRender["executiveRevenueCYear"];

        $executiveRevenuePYearDisc = $forRender["executiveRevenuePYearDisc"];
        $executiveRevenuePYearSony = $forRender["executiveRevenuePYearSony"];
        $executiveRevenuePYear = $forRender["executiveRevenuePYear"];

        $pending = $forRender["pending"];
        $pendingDisc = $forRender["pendingDisc"];
        $pendingSony = $forRender["pendingSony"];
        $RFvsTarget = $forRender["RFvsTarget"];
        $RFvsTargetDisc = $forRender["RFvsTargetDisc"];
        $RFvsTargetSony = $forRender["RFvsTargetSony"];
        $targetAchievement = $forRender["targetAchievement"];
        $targetAchievementDisc = $forRender["targetAchievementDisc"];
        $targetAchievementSony = $forRender["targetAchievementSony"];

        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $region = $forRender["region"];

        $currencyName = $forRender["currencyName"];
        $valueView = $forRender["valueView"];

        $fcstAmountByStageDisc = $forRender["fcstAmountByStageDisc"];
        $fcstAmountByStageSony = $forRender["fcstAmountByStageSony"];
        $fcstAmountByStage = $forRender["fcstAmountByStage"];
        
        $fcstAmountByStageExDisc = $forRender["fcstAmountByStageExDisc"];
        $fcstAmountByStageExSony = $forRender["fcstAmountByStageExSony"];
        $fcstAmountByStageEx = $forRender["fcstAmountByStageEx"];
        $brandsPerClient = $forRender["brandsPerClient"];
        
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
        echo "<input type='hidden' id='year' name='brandsPerClient' value='".base64_encode(json_encode($brandsPerClient))."'>";

        echo "<input type='hidden' id='clickBoolHeader' value='1'>";

        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style='width:100%; text-align:center; font-size:25px;'>";
            echo "<tr><th class='lightBlue'>".$salesRep['salesRep']." - ".$currencyName."/".$valueView."</th></tr>";
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
        echo "<div class='sticky-top' style='zoom:75%; scroll-margin-botton: 10px;'>";
        	echo "<div class='row'>";
       			echo "<div class='col-2' style='padding-right:1px;'>";
        			echo "<table id='example' style='width:100%; text-align:center; min-height:225px;'>";
            			echo "<tr>";
            				echo "<td style='height:30px; background-color: #FFFFFF;'>&nbsp;</td>";
                			echo "<td class='darkBlue' style='text-align:center;  width:25%;'>
                    					<span style='font-size:18px;'>".$salesRep['abName']." </span></td>";
            			echo "</tr>";
            			echo "<tr class='clickBoolHeader'>";
                			echo "<td class='darkBlue' id='' rowspan='8' style='text-align:center; border-bottom: 1pt solid black;  width:5.5%;'>
                					<span style='font-size:12px;'>";
                						echo " TT "; 
                						echo "</span>";
                			echo "</td>";
			            	echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='rcBlue' style='text-align:left; height:25px;'>% Target Achievement</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='odd' style='text-align:left; border-bottom: 1pt solid black; height:25px;'>Cmaps</td>";
							echo "</tr>";
						echo "</tr>";
						

			            /* INICIO DISC */
			            echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
                			echo "<td class='dc' id='' rowspan='9' style=' text-align:CENTER; border-bottom: 1pt solid black; width:5.5%; height:25px;'>
                					<span style='font-size:12px;'>";
                						echo " DISC "; 
                						echo "</span>";
                			echo "</td>";
                		echo "</tr>";

		                echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader' >";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>% Target Achievement</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
							echo "<td class='odd' style='text-align:left; border-bottom: 1pt solid black; height:25px;'>Cmaps</td>";
						echo "</tr>";
		                /* FIM  DISC */


			                /* INICIO SONY */
		                echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
                			echo "<td class='sony' id='' rowspan='10' style='text-align:center; border-bottom: 1pt solid black; width:5.5%; height:25px;'>
                					<span style='font-size:12px;'>";
                						echo " SONY "; 
                					echo "</span>";
                			echo "</td>";
                		echo "</tr>";
		                
		               echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'><span>Rolling Fcast ".$cYear."</span><br>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>Bookings</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Pending</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>".$pYear."</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='odd' style='text-align:left; height:25px;'>Var RF vs Target</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
			                echo "<td class='rcBlue' style='text-align:left; height:25px;'>% Target Achievement</td>";
			            echo "</tr>";
			            echo "<tr class='clickLoopHeader'>";
							echo "<td class='odd' style='text-align:left; border-bottom: 1pt solid black; height:25px;'>Cmaps</td>";
						echo "</tr>";
		                /* FIM SONY */

        			echo "</table>";
        		echo "</div>";
        		echo "<div class='col linked table-responsive ' style='width:100%; padding-left:0px;'>";
        			echo "<table style='min-width:3000px; width:80%; text-align:right; min-height:225px;'>";
			            /* START OF SALES REP AND SALES REP TOTAL MONTHS */
			            echo "<thead>";
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) {
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                       echo "<td class='quarter' id='quarter-$m'  style='text-align:right; width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp </td>";
		                        }else{
		                            echo "<td style='text-align:right; ".$color2[$m]."' class='smBlue' id='month-$m' style='text-align:right; width:3%;  height:30px;'>".$this->month[$m]."&nbsp &nbsp</td>";
			                    }
			                }
			                echo "<td class='darkBlue' style=' text-align:right; width:3%; height:30px;'>Total &nbsp &nbsp</td>";
			                echo "<td style=' text-align:right; width:0.5%; background-color: #ffffff;'>&nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Closed &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Cons. (%) &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Exp &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Prop &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Adv &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Contr &nbsp &nbsp</td>";
			                echo "<td class='lightGrey' style=' text-align:right; width:3%;'>Total &nbsp &nbsp</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            echo "</thead>";			            
			            /* END OF SALES REP AND SALES REP TOTAL MONTHS */

			            /*TT SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='text-align:right; width:3%;'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        				number_format(
			                        						($targetValues[$m])
			                        						)
			                        				."' style=' width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValues[$m];
			                    }else{
			                        echo "<td class='even' style='text-align:right; width:3%;'>
			                        			<input type='text' readonly='true' id='target-$m' name='target-$m' value='".
			                        					number_format(
			                        						( $targetValues[$m] )
			                        						)."' style=' width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue' style='text-align:right; width:3%; height:25px;'>
			                			<input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".
			                				number_format(
			                					( $targetValues[$m] )
			                					)
			                				."' style='text-align:right; width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			               	echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>
			                        			<input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".
			                        				number_format(
			                        					$executiveRF[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd' style='text-align:right; width:3%;'>
			                        			<input type='text' name='rf-$m' readonly='true' id='rf-$m' value='".
			                        				number_format(
			                        					$executiveRF[$m]
			                        				)
			                        			."' style=' width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='text-align:right; width:3%; height:25px;'>
			                			<input type='text' name='total-total' readonly='true' id='total-total' value='".
			                				number_format(
			                					$executiveRF[$m]
			                					)
			                			."' style=' width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right;'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                			number_format($fcstAmountByStageEx[1][4])
			                	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                			number_format($fcstAmountByStageEx[1][7])
			                	  ."% </td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][0])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][1])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][2])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][3])
			                  	 ."</td>";
			                echo "<td class='odd' style='text-align:right;'>".
			                  			number_format($fcstAmountByStageEx[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even' style='width:3%;'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m])."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even' style='width:3%;'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTarget' name='totalRFvsTarget' value='".number_format($RFvsTarget[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";
			            /* END % TARGET ACHIEVEMENT */

			            /* START CMAPS BY SALES REP */
		                echo "<tr style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsTotal-$m' id='cmapsTotal-$m' value='".number_format($cmapsTotal[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsTotal-$m' id='cmapsTotal-$m' value='".number_format($cmapsTotal[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='cmapsTotalTT' name='cmapsTotalTT' value='".number_format($cmapsTotal[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.40%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";

			            /* END CMAPS BY SALES REP*/

			             /*DISC SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr class='clickLoopHeader'>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue''>
			                        			<input type='text' readonly='true' id='targetD-$m' name='targetD-$m' value='".
			                        				number_format(
			                        						($targetValuesDiscovery[$m])
			                        						)
			                        				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValuesDiscovery[$m];
			                    }else{
			                        echo "<td class='even'>
			                        			<input type='text' readonly='true' id='targetD-$m' name='targetD-$m' value='".
			                        					number_format(
			                        						( $targetValuesDiscovery[$m] )
			                        						)."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue style='height:25px;'>
			                			<input type='text' readonly='true' id='totalTargetD' name='totalTargetD' value='".
			                				number_format(
			                					( $targetValuesDiscovery[$m] )
			                					)
			                				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>
			                        			<input type='text' readonly='true' id='rfD-$m' name='rfD-$m' value='".
			                        				number_format(
			                        					$executiveRFDisc[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd'>
			                        			<input type='text' name='rfD-$m' readonly='true' id='rfD-$m' value='".
			                        				number_format(
			                        					$executiveRFDisc[$m]
			                        				)
			                        			."' style='width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' name='total-totalDisc' readonly='true' id='total-totalDisc' value='".
			                				number_format(
			                					$executiveRFDisc[$m]
			                					)
			                			."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExDisc[1][4])
			                	 ."</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExDisc[1][7])
			                	  ."% </td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][0])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][1])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][2])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][3])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExDisc[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingED-$m' name='bookingED-$m' value='".number_format($executiveRevenueCYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingED-$m' value='".number_format($executiveRevenueCYearDisc[$m])."' name='bookingED-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingED' name='totalBookingED' value='".number_format($executiveRevenueCYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' name='pendingD-$m' id='pendingD-$m' value='".number_format($pendingDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='pendingD-$m' value='".number_format($pendingDisc[$m])."' name='pendingD-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPendingDisc' name='totalPendingDisc' value='".number_format($pendingDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYearDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYearDisc[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYearDisc[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetD-$m' value='".number_format($RFvsTargetDisc[$m])."' name='RFvsTargetD-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetD-$m' value='".number_format($RFvsTargetDisc[$m])."' name='RFvsTargetD-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTargetD' name='totalRFvsTargetD' value='".number_format($RFvsTargetDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' name='achievementD-$m' id='achievementD-$m' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>";
			                        	echo "<input type='text' readonly='true' name='achievementD-$m' id='achievementD-$m' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievementD' name='totalAchievementD' value='".number_format($targetAchievementDisc[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";

			            /* START CMAPS BY SALES REP DISCOVERY */
		                echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsDisc-$m' id='cmapsDisc-$m' value='".number_format($cmapsDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsDisc-$m' id='cmapsDisc-$m' value='".number_format($cmapsDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='cmapsDiscTT' name='cmapsDiscTT' value='".number_format($cmapsDisc[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.40%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";

			            /* END CMAPS BY SALES REP*/

			             /*Sony SALES REP*/

			            /* START OF TARGET BY SALES REP INFO */
			            echo "<tbody>";
			            echo "<tr class='clickLoopHeader'>";
			                $totalTarget = 0.0;
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue''>
			                        			<input type='text' readonly='true' id='targetS-$m' name='targetS-$m' value='".
			                        				number_format(
			                        						($targetValuesSony[$m])
			                        						)
			                        				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                        $totalTarget += $targetValuesSony[$m];
			                    }else{
			                        echo "<td class='even'>
			                        			<input type='text' readonly='true' id='targetS-$m' name='targetS-$m' value='".
			                        					number_format(
			                        						( $targetValuesSony[$m] )
			                        						)."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' readonly='true' id='totalTargetS' name='totalTargetS' value='".
			                				number_format(
			                					( $targetValuesSony[$m] )
			                					)
			                				."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>
			                	  </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
			                	echo "<td class='rcBlue'>&nbsp</td>";
			                }
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";			            
			            /* END OF TARGET BY SALES REP INFO */

			            /* START OF ROLLING FCST BY SALES REP INFO */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>
			                        			<input type='text' readonly='true' id='rfS-$m' name='rfS-$m' value='".
			                        				number_format(
			                        					$executiveRFSony[$m]
			                        				)
			                        			."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>
			                        	  </td>";
			                    }else{
			                        echo "<td class='odd'>
			                        			<input type='text' name='rfS-$m' readonly='true' id='rfS-$m' value='".
			                        				number_format(
			                        					$executiveRFSony[$m]
			                        				)
			                        			."' style='width:100%; border:none; text-align:right; font-weight:bold;  background-color:transparent;'></td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>
			                			<input type='text' name='total-totalSony' readonly='true' id='total-totalSony' value='".
			                				number_format(
			                					$executiveRFSony[$m]
			                					)
			                			."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:right'>
			                	 </td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExSony[1][4])
			                	 ."</td>";
			                echo "<td class='odd'>".
			                			number_format($fcstAmountByStageExSony[1][7])
			                	  ."% </td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][0])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][1])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][2])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][3])
			                  	 ."</td>";
			                echo "<td class='odd'>".
			                  			number_format($fcstAmountByStageExSony[1][6])
			                  	 ."</td>";
			                echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END OF ROLLING FCST BY SALES REP INFO */ 

			            /* START OF BOOKED BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' id='bookingES-$m' name='bookingES-$m' value='".number_format($executiveRevenueCYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo"<input type='text' readonly='true' id='bookingES-$m' value='".number_format($executiveRevenueCYearSony[$m])."' name='bookingES-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>"; 
			                	echo "<input type='text' readonly='true' id='totalBookingES' name='totalBookingES' value='".number_format($executiveRevenueCYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF BOOKED BY SALES REP INFO */ 

			            /* START OF PENDING BY SALES REP INFO */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>"; 
			                        	echo "<input type='text' readonly='true' name='pendingS-$m' id='pendingS-$m' value='".number_format($pendingSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='pendingS-$m' value='".number_format($pendingSony[$m])."' name='pendingS-$m' style='width:100%; border:none;  font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalPendingSony' name='totalPendingSony' value='".number_format($pendingSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PENDING BY SALES REP INFO */ 

			            /* START OF PYEAR */ 
			            
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYearSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>"; 
			                        	echo "<input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYearSony[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                       	echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYearSony[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:right; background-color:transparent;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            
			            /* END OF PYEAR */ 


			            /* START VAR RF VS TARGET BY SALES REP */ 
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetS-$m' value='".number_format($RFvsTargetSony[$m])."' name='RFvsTargetS-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'>";
			                        	echo "<input type='text' readonly='true' id='RFvsTargetS-$m' value='".number_format($RFvsTargetSony[$m])."' name='RFvsTargetS-$m' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                		echo "<input type='text' readonly='true' id='totalRFvsTargetS' name='totalRFvsTargetS' value='".number_format($RFvsTargetSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>";
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
			            echo "</tr>";
			            /* END VAR RF VS TARGET BY SALES REP */

			            /* START % TARGET ACHIEVEMENT */
			            echo "<tr class='clickLoopHeader'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue'>";
			                        	echo "<input type='text' readonly='true' name='achievementS-$m' id='achievementS-$m' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='even'>";
			                        	echo "<input type='text' readonly='true' name='achievementS-$m' id='achievementS-$m' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='height:25px;'>";
			                	echo "<input type='text' readonly='true' id='totalAchievementS' name='totalAchievementS' value='".number_format($targetAchievementSony[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='rcBlue'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";	                
			            echo "</tr>";

			            /* START CMAPS BY SALES REP SONY */
		                echo "<tr class='clickLoopHeader' style='border-bottom: 1pt solid black;'>";
			                for ($m=0; $m <sizeof($this->month) ; $m++) { 
			                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
			                        echo "<td class='medBlue' style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsSony-$m' id='cmapsSony-$m' value='".number_format($cmapsSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>";
			                        echo "</td>";
			                    }else{
			                        echo "<td class='odd'style='width:3%;'>";
			                        	echo "<input type='text' readonly='true' name='cmapsSony-$m' id='cmapsSony-$m' value='".number_format($cmapsSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent;'>"; 
			                        echo "</td>";
			                    }
			                }
			                echo "<td class='smBlue' style='width:3%; height:25px;'>";
			                	echo "<input type='text' readonly='true' id='cmapsSonyTT' name='cmapsSonyTT' value='".number_format($cmapsSony[$m])."' style='width:100%; border:none; font-weight:bold; text-align:right; background-color:transparent; color:white;'>"; 
			                echo "</td>";
			                echo "<td style='background-color: #FFFFFF; border-bottom: 1pt solid white;'>&nbsp</td>";
			                for ($i=0; $i < 7; $i++) { 
		                    	echo "<td class='odd'>&nbsp</td>";
		                    }
		                    echo "<td style=' text-align:right; width:0.40%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";		                
			            echo "</tr>";
           
            			echo "</tbody>";
        			echo "</table>";
        		echo "</div>";
        	echo "</div>";
        echo "</div>";
        echo "<br>";        


       


        for ($c=0; $c < sizeof($client); $c++) {

            if($splitted){
                if($splitted[$c]['splitted']){ 
                	$clr = "lightBlue";
                	$ow = "";
                }else{ 
                	$clr = "lightBlue"; 
                	$ow = false;
                }   
                
            }else{
                $clr = "lightBlue";     
                $ow = false;               
            }

            if($splitted){
                if($splitted[$c]['splitted']){
                    if(is_null($splitted[$c]['owner'])){
                        $ow = "(?)";
                    }else{
                        if($splitted[$c]['sales_rep_owner_id'] == $salesRep['id']){
                            $ow = "(P)";
                        }else{
                            $ow = "(S)";
                        }
                    }
                }else{
                    $ow = "";
                }
            }else{
                $ow = false;
            }

            $color = "";
            $boolfcst = "1";

            echo "<input type='hidden' id='clickBool-$c' value='1'>";

            echo "<div class='' style='zoom:75%;'>";
            	echo "<div class='row mt-3'>";
            		echo "<div class='col-2' style='padding-right:1px;'>";
            			echo "<table id='table-$c' style='width:100%; text-align:right; overflow:auto; min-height: 180px;' >";

                    		echo "<tr>";
                    			
                    			echo "<td style='height:30px;'> &nbsp; </td>";
                    			echo "<td  class='darkBlue' id='client-$c' style='text-align:center; height:30px; width:25%;'>
                    					<span style='font-size:14px;'>";
                    						echo "".$client[$c]['clientName']." - ".$client[$c]["agencyName"] ." $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                			echo "</tr>";

                			 /* INICIO TT */
			                echo "<tr class='clickBool-$c'>";
                    			echo "<td class='darkBlue' id='client-$c' rowspan='6' style='text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " TT $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                                echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
                                echo "<tr>";
			                        echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                    echo "</tr>";
			                    echo "<tr>";
			                        echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'>Booking</td>";
			                    echo "</tr>";
			                    echo "<tr>";
			                        echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>".$pYear."</td>";
			                    echo "</tr>";
			                    echo "<tr>";
			                        echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                    echo "</tr>";
			                    echo "<tr style='border-bottom: 1pt solid black;'>";
			                        echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>Cmaps</td>";
			                    echo "</tr>";
                    		echo "</tr>";
			                /* FIM TT */

                			/*INICIO DISC */
                			echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    			echo "<td class='dc' id='client-$c' rowspan='7' style=' text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " DISC $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                    		echo "</tr>";

                			
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Booking</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
			                    echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>Cmaps</td>";
			                echo "</tr>";
			                /* FIM  DISC */


			                /* INICIO SONY */
			                echo "<tr class='clickLoop-$c'>";
                    			echo "<td class='sony' id='client-$c' rowspan='7' style='text-align:center; background-color: $color; width:5.5%;'>
                    					<span style='font-size:12px;'>";
                    						echo " SONY $ow"; 
                    						echo "</span>";
                    			echo "</td>";
                    		echo "</tr>";

			                
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'> Rolling Fcast ".$cYear." </td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8; text-align:left; height:30px;'>Manual Estimation";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0;text-align:left; height:30px;'>Booking</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c'>";
			                    echo "<td style='background-color:#dbe5f0; text-align:left; height:30px;'>Var RF vs ".$pYear."</td>";
			                echo "</tr>";
			                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
			                    echo "<td style='background-color:#c9d8e8;text-align:left; height:30px;'>Cmaps</td>";
			                echo "</tr>";
			                /* FIM SONY */

			               

            			echo "</table>";
            		echo "</div>";

            	echo "<div class='col linked table-responsive' style='padding-left:0px;'>";



            echo "<table id='table-$c' style='min-width:3000px; width:100%; text-align:right; overflow:auto; min-height: 180px;'>";
                /* START OF CLIENT NAME AND MONTHS */

                echo "<input type='text' id='splitted-$c' name='splitted-$c' value='$ow' style='display:none;'>";
                echo "<tr>";
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' id='quarter-$c-$m' style='width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp</td>";
                        }else{
                            echo "<td style='".$color2[$m]."' class='smBlue' id='month-$c-$m' style='width:3%; height:30px;'>".$this->month[$m]." &nbsp &nbsp</td>";
                        }
                    }
                    echo "<td class='darkBlue' id='TotalTitle-$c' rowspan='1' style='width:3%;'>Total &nbsp &nbsp</td>";
                    echo "<td rowspan='16' id='division-$c' style='width:0.5%; border-bottom: 1pt solid white;'>&nbsp</td>";
                    echo "<td id='sideTable-$c-0' rowspan='1' class='lightGrey' style='width:3%;'>Closed &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-1' rowspan='1' class='lightGrey' style='width:3%;'>Cons.(%) &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-2' rowspan='1' class='lightGrey' style='width:3%;'>Exp &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-3' rowspan='1' class='lightGrey' style='width:3%;'>Prop &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-4' rowspan='1' class='lightGrey' style='width:3%;'>Adv &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-5' rowspan='1' class='lightGrey' style='width:3%;'>Contr &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' class='lightGrey' style='width:3%;'>Total &nbsp &nbsp</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' style='width:0.42%; border-bottom:1pt solid white;' colspan=6;> &nbsp</td>";

                echo "</tr>";
                /* END OF CLIENT NAME AND MONTHS */

                /* START OF CLIENT ROLLING FORECAST TT*/
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."</td>";             
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' id='passTT-Total-$c' name='passTT-Total-$c' readonly='true' value='".number_format($lastRollingFCSTDisc[$c][$m]+$lastRollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageSony[$c]) {
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][4] + $fcstAmountByStageSony[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][7] + $fcstAmountByStageSony[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][0] + $fcstAmountByStageSony[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][1] + $fcstAmountByStageSony[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][2] + $fcstAmountByStageSony[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][3] + $fcstAmountByStageSony[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][6] + $fcstAmountByStageSony[$c][1][6])."</td>";
                       
                    }else{
	                    for ($i=0; $i < 7; $i++) { 
	                    	echo "<td class='rcBlue'>&nbsp</td>";
	                    }   
	                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST TT*/ 

                /* START OF CLIENT MANUAL ESTIMATION TT*/            
                echo "<tr>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-TT-$c-$m' name='clientRF-TT-$c-$m' value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>"; 
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>";
                                echo "<input type='text' readonly='true' name='fcstClient-TT-$c-$m' id='clientRF-TT-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                   		echo "<input type='text' readonly='true' id='totalClient-TT-$c' name='totalClient-TT-$c' value='".number_format($rollingFCSTDisc[$c][$m]+$rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>"; 
                   	echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    } 
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION TT*/

                /* START OF CLIENT BOOKING TT*/          
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                            echo "<td id='booking-TT-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenueCYearDisc[$c][$m]+$clientRevenueCYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING TT*/ 
                
                /* START OF CLIENT PAST YEAR TT*/            
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m]+$clientRevenuePYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";


                /* END OF CLIENT PAST YEAR TT*/               

                /* START OF CLIENT RF VS PYEAR TT*/         
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = ($rollingFCSTDisc[$c][$m] + $rollingFCSTSony[$c][$m]) - ($clientRevenuePYearDisc[$c][$m] + $clientRevenuePYearSony[$c][$m]) ;
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format(($rollingFCSTDisc[$c][$m] + $rollingFCSTSony[$c][$m]) - ($clientRevenuePYearDisc[$c][$m] + $clientRevenuePYearSony[$c][$m]) )."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR TT*/

                /* START OF CMAPS BY CLIENT*/
                echo "<tr style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($cmapsClientDisc[$c][$m]+$cmapsClientSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>".number_format($cmapsClientDisc[$c][$m]+$cmapsClientSony[$c][$m])."</td>";
                            echo "<td id='cmapsByclient-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($cmapsClientDisc[$c][$m]+$cmapsClientSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";

                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/


                
                /* START OF CLIENT ROLLING FORECAST DISC */
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($lastRollingFCSTDisc[$c][$m])."</td>";                    
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' id='passTotal-DISC-$c' name='passTotal-DISC-$c' readonly='true' value='".number_format($lastRollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageDisc[$c]) {                    	
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageDisc[$c][1][6])."</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                   
                    }else{
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00%</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td class='rcBlue'>0.00</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST DISC */ 

                /* START OF CLIENT MANUAL ESTIMATION DISC */            
                echo "<tr class='clickLoop-$c'>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-DISC-$c-$m' name='clientRF-DISC-$c-$m' value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>";
                                echo "<input type='text' name='fcstClient-DISC-$c-$m' id='clientRF-DISC-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>";
                    	echo "<input type='text' readonly='true' id='totalClient-DISC-$c' name='totalClient-DISC-$c' value='".number_format($rollingFCSTDisc[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>";
                    echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION DISC*/

                /* START OF CLIENT BOOKING DISC*/          
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenueCYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING DISC*/ 
                
                /* START OF CLIENT PAST YEAR DISC*/            
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($clientRevenuePYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT PAST YEAR DISC*/               

                /* START OF CLIENT RF VS PYEAR DISC*/         
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCSTDisc[$c][$m] - $clientRevenuePYearDisc[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($rollingFCSTDisc[$c][$m] - $clientRevenuePYearDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR DISC*/

                /* START OF CMAPS BY CLIENT DISC*/
                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($cmapsClientDisc[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>".number_format($cmapsClientDisc[$c][$m])."</td>";
                            echo "<td id='cmapsByclientDisc-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($cmapsClientDisc[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";


                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/


                /* START OF CLIENT ROLLING FORECAST SONY*/
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($lastRollingFCSTSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($lastRollingFCSTSony[$c][$m])."</td>";                    
                        }
                    }
                    echo "<td class='smBlue'>";
                    	echo "<input type='text' id='passTotal-SONY-$c' name='passTotal-SONY-$c' readonly='true' value='".number_format($lastRollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right; color:white;'>";
                    echo "</td>";
 							
                    if ($fcstAmountByStageSony[$c]) {
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][4])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][7])."%</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][0])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][1])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][2])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][3])."</td>";
                        echo "<td class='rcBlue'>".number_format($fcstAmountByStageSony[$c][1][6])."</td>";
                        echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                    }else{
	                    for ($i=0; $i < 7; $i++) { 
	                    	echo "<td class='rcBlue'>&nbsp</td>";
	                    } 
	                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>"; 
                    }
                echo "</tr>";
                /* END OF CLIENT ROLLING FORECAST SONY*/ 

                /* START OF CLIENT MANUAL ESTIMATION SONY*/            
                echo "<tr class='clickLoop-$c'>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style='width:3%; height:30px;'>";
                            	echo "<input type='text' readonly='true' id='clientRF-SONY-$c-$m' name='clientRF-SONY-$c-$m' value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>"; 
                            echo "</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>";
                                echo "<input type='text' name='fcstClient-SONY-$c-$m' id='clientRF-SONY-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:right;'>";
                            echo "</td>";                            
                        }
                    }
                    echo "<td class='smBlue'>";
                   		echo "<input type='text' readonly='true' id='totalClient-SONY-$c' name='totalClient-SONY-$c' value='".number_format($rollingFCSTSony[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:right;'>"; 
                   	echo "</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    } 
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT MANUAL ESTIMATION SONY*/

                /* START OF CLIENT BOOKING SONY*/          
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenueCYearSony[$c][$m])."</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT BOOKING SONY*/ 
                
                /* START OF CLIENT PAST YEAR SONY*/            
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue'>".number_format($clientRevenuePYearSony[$c][$m])."</td>";
                    echo "<td>&nbsp</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";


                /* END OF CLIENT PAST YEAR SONY*/               

                /* START OF CLIENT RF VS PYEAR SONY*/         
                echo "<tr class='clickLoop-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCSTSony[$c][$m] - $clientRevenuePYearSony[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($tmp)."</td>";
                        }else{
                            echo "<td class='even' style='height:30px; width:3%;'>".number_format($tmp)."</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($rollingFCSTSony[$c][$m] - $clientRevenuePYearSony[$c][$m])."</td>";
                    echo "<td>&nbsp</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='rcBlue'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /* END OF CLIENT RF VS PYEAR SONY*/

                /* START OF CMAPS BY CLIENT SONY*/
                echo "<tr class='clickLoop-$c' style='border-bottom: 1pt solid black;'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:3%; height:30px;'>".number_format($cmapsClientSony[$c][$m])."</td>";
                        }else{
                            echo "<td class='odd' style='height:30px; width:3%;'>".number_format($cmapsClientSony[$c][$m])."</td>";
                            echo "<td id='cmapsByclientSony-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:3%;'>".number_format($cmapsClientSony[$c][$m])."</td>";
                    echo "<td>&nbsp</td>";
                    for ($i=0; $i < 7; $i++) { 
                    	echo "<td class='odd'>&nbsp</td>";
                    }
                    echo "<td style=' text-align:right; width:0.42%; border-bottom:1pt solid white;' colspan=6;>&nbsp</td>";
                echo "</tr>";
                /**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/
				/**********************************************************************************************************************************************************/

            echo "</table>";         



            echo "</div>";
            echo "</div>";
            echo "</div>";
 

        }  

    }
}

    