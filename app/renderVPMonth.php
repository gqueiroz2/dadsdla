<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;

class renderVPMonth extends Render {
    
    public function assemble($forRender,$client,$tfArray,$odd,$even,$regionName){
        
        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        $client = $forRender['client'];
        $targetValues = $forRender['targetValues'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        $tfArray = $forRender["readable"]["tfArray"];
        $manualEstimation = $forRender["readable"]["manualEstimation"];
        $color2 = $forRender["readable"]["color"];

        $rollingFCST = $forRender['rollingFCST'];
        $lastRollingFCST = $forRender['lastRollingFCST'];
        $clientRevenueCYear = $forRender['clientRevenueCYear'];
        $clientRevenuePYear = $forRender['clientRevenuePYear'];

        $executiveRF = $forRender["executiveRF"];
        $executiveRevenueCYear = $forRender["executiveRevenueCYear"];
        $executiveRevenuePYear = $forRender["executiveRevenuePYear"];

        $pending = $forRender["pending"];
        $RFvsTarget = $forRender["RFvsTarget"];
        $targetAchievement = $forRender["targetAchievement"];

        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $region = $forRender["region"];

        $currencyName = $forRender["currencyName"];
        $valueView = $forRender["valueView"];

        $fcstAmountByStage = $forRender["fcstAmountByStage"];
        $fcstAmountByStageEx = $forRender["fcstAmountByStageEx"];

        echo "<input type='hidden' id='client' name='client' value='".base64_encode(json_encode($client)) ."'>";
        echo "<input type='hidden' id='currency' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' id='value' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' id='region' name='region' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' id='user' name='user' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' id='year' name='year' value='".base64_encode(json_encode($cYear))."'>";

        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>
                <tr><th class='lightBlue'>".$regionName." - ".$currencyName."/".$valueView."</th></tr>
            </table>
        </div>";

        echo "<br>";

        echo "<div class='' style='zoom:80%; scroll-margin-botton: 10px;'>";

        echo "<div class='row'>";

        echo "<div class='col-2' style='padding-right:1px;'>";
        echo "<table class='' id='example' style='width:100%; text-align:center; min-height:225px;'>";
            echo "<tr>";
                echo "<td class='darkBlue' style=' border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; font-size:20px; height:40px; '>"."Brazil"."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Roling Fcast ".$cYear."</span><br>";
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
            /*
                START OF SALES REP AND SALES REP TOTAL MONTHS

            */
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
            
            /*
                
                START OF TARGET BY SALES REP INFO

            */

            echo "<tbody>";
            echo "<tr>";

            $totalTarget = 0.0;
            for ($m=0; $m < sizeof($this->month); $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    $totalTarget += $targetValues[$m];
                }else{
                    echo "<td class='$even[$m]'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
            /*

                END OF TARGET BY SALES REP INFO

            */

            /*

                START OF ROLLING FCST BY SALES REP INFO

            */

            echo "<tr>";
                    //echo "<div style='display:none;' id='totalTotalPP'><span >Total P.P. (%):   </span><input type='number' value='100' readonly='true' id='totalClients' style='display:;width:30%;text-align:right;'></div>";
            for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]'><input type='text' name='fcstSalesRep-$m' name='rf-$m' readonly='true' id='rf-$m' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' name='total-total' readonly='true' id='total-total' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
                                echo "<td>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][4])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][7])."%</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][0])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][1])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][2])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][3])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][6])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStageEx[1][5])."</td>";
            echo "</tr>";
            /*
                
                END OF ROLLING FCST BY SALES REP INFO

            */ 

            /*
                
                START OF BOOKED BY SALES REP INFO

            */
            echo "<tr>";
            for ($m=0; $m <sizeof($this->month) ; $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }else{
                    echo "<td class='$even[$m]' ><input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
        /*
            
            END OF BOOKED BY SALES REP INFO

        */ 

        /*
            
            START OF PENDING BY SALES REP INFO

        */
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]' ><input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m])."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
            /*
                
                END OF PENDING BY SALES REP INFO

            */ 

             /*
                
                START OF PYEAR

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]'><input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
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
            /*
                
                END OF PYEAR

            */ 


            /*
                
                START VAR RF VS TARGET BY SALES REP

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' ><input type='text' readonly='true' id='TotalRFvsTarget' name='TotalRFvsTarget' value='".number_format($RFvsTarget[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
            /*
                
                END VAR RF VS TARGET BY SALES REP

            */

            /*
                
                START % TARGET ACHIEVEMENT

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;' ><input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
            /*
                
                END % TARGET ACHIEVEMENT

            */
            /*
                
                START VAR RV vs PLAN

            */ 
           
            echo "</tbody>";
            /*
                
                END VAR RV vs PLAN

            */
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        echo "<br>";
    }
}
