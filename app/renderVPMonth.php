<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;

class renderVPMonth extends Render {
    
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function assemble($forRender,$client,$tfArray,$odd,$even,$regionName,$error = false){
        
        $cMonth = intval(date('m'));

        if ($cMonth == 3 || $cMonth == 7 || $cMonth == 11) {
            $cMonth--;
        }else{
            $cMonth++;
        }
        
        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        $client = $forRender['client'];
        $targetValues = $forRender['targetValues'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        $tfArray = $forRender["readable"]["tfArray"];
        $manualEstimation = $forRender["readable"]["manualEstimation"];
        $manualRolling = $forRender["manualRolling"];
        $color2 = $forRender["readable"]["color"];
        
        $rollingFCST = $forRender['rollingFCST'];
        $lastRollingFCST = $forRender['lastRollingFCST'];
        $clientRevenueCYear = $forRender['clientRevenueCYear'];
        $clientRevenuePYear = $forRender['clientRevenuePYear'];

        $executiveRF = $forRender["executiveRF"];
        $pastExecutiveRF = $forRender["pastExecutiveRF"];
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
        $percentage = $forRender["percentage"];
        $brandsPerClient = $forRender["brandsPerClient"];
        $corporateFcst = $forRender["corporateFcst"];

        echo "<input type='hidden' id='client' name='client' value='".base64_encode(json_encode($client))."'>";
        echo "<input type='hidden' id='currency' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' id='value' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' id='region' name='region' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' id='year' name='year' value='".base64_encode(json_encode($cYear))."'>";
        echo "<input type='hidden' id='percentage' name='percentage' value='".base64_encode(json_encode($percentage))."'>";
        echo "<input type='hidden' id='brandsPerClient' name='brandsPerClient' value='".base64_encode(json_encode($brandsPerClient))."'>";


        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>
                <tr><th class='lightBlue'>".$regionName." - ".$currencyName."/".$valueView."</th></tr>
            </table>
        </div>";

        /*if($error) {
            echo "<br>";
            echo "<div class=\"alert alert-danger\" style=\"width:50%;\">";
                echo $error;
            echo "</div>";
        }*/

        echo "<br>";

        echo "<div class='' style='zoom:80%; scroll-margin-botton: 10px;'>";

        echo "<div class='row'>";

        echo "<div class='col-2' style='padding-right:1px;'>";
        echo "<table class='' id='example' style='width:100%; text-align:center; min-height:244px;'>";
            echo "<tr>";
                echo "<td class='darkBlue ' style=' border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; font-size:20px; height:40px; '>".$regionName."</td>";
            echo "</tr>";
            echo "<tr id='linha-1-1'>";
                echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
            echo "</tr>";
            echo "<tr id='linha-1-2'>";
                echo "<td class='odd'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Corporate Fcst</td>";
            echo "</tr>";
            echo "<tr id='linha-1-3'>";
                echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Rolling Fcast ".$cYear."</span><br>";
            echo "</tr>";
            echo "<tr id='linha-1-4'>";
                echo "<td class='odd'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Manual Estimation</span><br>";
            echo "</tr>";
            echo "<tr id='linha-1-5'>";
                echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Previous Rolling Fcast ".$cYear."</span><br>";
            echo "</tr>";
            echo "<tr id='linha-1-6'>";
                echo "<td class='odd'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Bookings</td>";
            echo "</tr>";
            echo "<tr id='linha-1-7'>";
                echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Pending</td>";
            echo "</tr>";
            echo "<tr id='linha-1-8'>";
                echo "<td class='odd'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".$pYear."</td>";
            echo "</tr>";
            echo "<tr id='linha-1-9'>";
                echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Target</td>";
            echo "</tr>";
            echo "<tr id='linha-1-10'>";
                echo "<td class='odd'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>% Target Achievement</td>";
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
                for ($m=0; $m < sizeof($this->month) ; $m++) {
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
            echo "<tr id='linha-2-1'>";

            $totalTarget = 0.0;
            for ($m=0; $m < sizeof($this->month); $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    $totalTarget += $targetValues[$m];
                }else{
                    echo "<td class='$even[$m]'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".number_format($targetValues[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
                
                START OF TARGET BY SALES REP INFO

            */

            echo "<tr id='linha-2-2'>";

            for ($m=0; $m < sizeof($this->month); $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($corporateFcst[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }else{
                    echo "<td class='$odd[$m]'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($corporateFcst[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".number_format($corporateFcst[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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

                END OF TARGET BY SALES REP INFO

            */

            /*

                START OF ROLLING FCST BY SALES REP INFO

            */

            echo "<tr id='linha-2-3'>";
                    //echo "<div style='display:none;' id='totalTotalPP'><span >Total P.P. (%):   </span><input type='number' value='100' readonly='true' id='totalClients' style='display:;width:30%;text-align:right;'></div>";
            for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".number_format($executiveRF[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]'><input type='text' name='fcstSalesRep-$m' name='rf-$m' readonly='true' id='rf-$m' value='".number_format($executiveRF[$m],0,',','.')."' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' name='total-total' readonly='true' id='total-total' value='".number_format($executiveRF[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][4],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][7],0,',','.')."%</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][0],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][1],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][2],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][3],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][6],0,',','.')."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStageEx[1][5],0,',','.')."</td>";
            echo "</tr>";
            /*
                
                END OF ROLLING FCST BY SALES REP INFO

            */

            /*

                START OF MANUAL ROLLING FCST BY SALES REP INFO

            */

            echo "<tr id='linha-2-4'>";
                    //echo "<div style='display:none;' id='totalTotalPP'><span >Total P.P. (%):   </span><input type='number' value='100' readonly='true' id='totalClients' style='display:;width:30%;text-align:right;'></div>";
            for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='me-$m' name='manualEstimation-$m' value='".number_format($manualRolling[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        if ($m < $cMonth) {
                            echo "<td class='$odd[$m]' style='".$manualEstimation[$m]."'><input type='text' name='manualEstimation-$m' name='manualEstimation-$m' id='me-$m' readonly='true' value='".number_format($manualRolling[$m],0,',','.')."' style='width:100%; border:none; text-align:center; font-weight:bold; background-color:transparent; ".$color2[$m]."'></td>";
                        }else{
                            echo "<td class='$odd[$m]' style='".$manualEstimation[$m]."'><input type='text' name='manualEstimation-$m' name='manualEstimation-$m' id='me-$m' value='".number_format($manualRolling[$m],0,',','.')."' style='width:100%; border:none; text-align:center; font-weight:bold; background-color:transparent; ".$color2[$m]."'></td>";
                        }
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' name='total-manualEstimationTotal' id='total-manualEstimationTotal' value='".number_format($manualRolling[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
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
                
                END OF ROLLING FCST BY SALES REP INFO

            */
            /*
                START OF LAST ROLLING FCST BY SALES REP INFO
            */


            echo "<tr id='linha-2-5'>";
            for ($m=0; $m < sizeof($this->month); $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='prf-$m' name='prf-$m' value='".number_format($pastExecutiveRF[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }else{
                    echo "<td class='$even[$m]'><input type='text' name='pFcstSalesRep-$m' name='prf-$m' readonly='true' id='prf-$m' value='".number_format($pastExecutiveRF[$m],0,',','.')."' style='width:100%; border:none; text-align:center; font-weight:bold; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' name='total-pastTotal' readonly='true' id='total-pastTotal' value='".number_format($pastExecutiveRF[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
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
                END OF LAST ROLLING FCST BY SALES REP INFO
            */            


            /*
                
                START OF BOOKED BY SALES REP INFO

            */
            echo "<tr id='linha-2-6'>";
            for ($m=0; $m < sizeof($this->month); $m++) { 
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }else{
                    echo "<td class='$odd[$m]' ><input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m],0,',','.')."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                }
            }
            echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
            
            END OF BOOKED BY SALES REP INFO

        */ 

        /*
            
            START OF PENDING BY SALES REP INFO

        */
            echo "<tr id='linha-2-7'>";
                for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]' ><input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m],0,',','.')."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
                END OF PENDING BY SALES REP INFO
            */

             /*
                START OF PYEAR

            */ 
            echo "<tr id='linha-2-8'>";
                for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]'><input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m],0,',','.')."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m],0,',','.')."' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
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
                
                END OF PYEAR

            */ 


            /*
                
                START VAR RF VS TARGET BY SALES REP

            */ 
            echo "<tr id='linha-2-9'>";
                for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m],0,',','.')."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m],0,',','.')."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' ><input type='text' readonly='true' id='TotalRFvsTarget' name='TotalRFvsTarget' value='".number_format($RFvsTarget[$m],0,',','.')."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
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
                
                END VAR RF VS TARGET BY SALES REP

            */

            /*
                
                START % TARGET ACHIEVEMENT

            */ 
            echo "<tr id='linha-2-10'>";
                for ($m=0; $m < sizeof($this->month); $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m],0,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m],0,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;' ><input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m],0,',','.')."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
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

        for ($c=0; $c < sizeof($client); $c++) {
            
            $clr = "lightBlue";  
            $ow = false;                  
            
            if (round($lastRollingFCST[$c][16]) != round($rollingFCST[$c][16])) {
                $color = "red";
                $boolfcst = "0";
            }else{
                $color = "";
                $boolfcst = "1";
            }

            echo "<div class='' style='zoom:80%;'>";
            echo "<div class='row'>";
            echo "<div class='col-2' style='padding-right:1px;'>";
            echo "<table id='table-$c' style='width:100%; text-align:center; overflow:auto; min-height: 180px; display: none;' >";
                echo "<tr>";
                    echo "<td class='$clr' id='client-$c' rowspan='1' style=' text-align:center; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; background-color: $color '><span style='font-size:18px; '> ".$client[$c]['clientName']." $ow </span>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'> Roling Fcast ".$cYear." </td>";
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

            echo "<table id='table-$c' style='min-width:3000px; width:100%; text-align:center; overflow:auto; min-height: 180px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; display: none;' >";
                
                /*

                    START OF CLIENT NAME AND MONTHS

                */

                //echo "<input type='text' id='splitted-$c' name='splitted-$c' value='$ow' style='display:none;'>";

                echo "<tr>";

                    
                    echo "</td>";
                    for ($m=0; $m < sizeof($this->month); $m++) { 
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
                /* 

                    END OF CLIENT NAME AND MONTHS

                */

                    /* 

                    START OF CLIENT ROLLING FORECAST

                */                 
                echo "<tr>";
                    for ($m=0; $m < sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;' name='client-$c'>".number_format($lastRollingFCST[$c][$m])."</td>";
                        }else{
                            echo "<td class='$even[$m]'>".number_format($lastRollingFCST[$c][$m])."</td>";
                    
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' id='passTotal-$c' name='passTotal-$c' readonly='true' value='".number_format($lastRollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center; color:white;'></td>";

                    if ($fcstAmountByStage[$c]) {
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][4])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][7])."%</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][0])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][1])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][2])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][3])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][6])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][5])."</td>";
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
                 /* 

                    END OF CLIENT ROLLING FORECAST

                */ 
                /* 

                    START OF CLIENT MANUAL ESTIMATION

                */
                echo "<tr>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    echo "</td>";
                    for ($m=0; $m < sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='clientRF-$c-$m' name='clientRF-$c-$m' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                        }else{
                            echo "<td class='$odd[$m]' style='".$manualEstimation[$m]."'>";
                                if ($ow && $ow != '(P)') {
                                    echo "<input type='text' name='fcstClient-$c-$m' id='clientRF-$c-$m' readonly='true' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center;".$color2[$m]."'>";
                                }else{
                                    echo "<input type='text' name='fcstClient-$c-$m' id='clientRF-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center;".$color2[$m]."'>";
                                }
                            echo "</td>";
                            /*echo "<td class='odd' rowspan='5' style='width:4%; display:none; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;' id='newCol-$c-$m'>";
                                for ($ch=0; $ch <sizeof($this->channel) ; $ch++) { 
                                    echo"<center>";
                                        echo "<div class='row' id='inputC-$c-$ch-$m' style='width:100%;white-space:nowrap;'>";
                                            echo "<div class='col-sm-4'>".$this->channel[$ch]."</div>";
                                            echo "<div class='col-sm-8'><input id='inputCNumber-$c-$ch-$m' type='number' min='0' max='100' step='0.5' value='10' style='width:100%;'></div>";
                                        echo "</div>";
                                    echo"</center>";
                                }
                            echo "</td>";*/
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalClient-$c' name='totalClient-$c' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center'><input type='text' readonly='true' id='totalTClient-$c' name='totalTClient-$c' value='50000' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center;display:none;'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT MANUAL ESTIMATION

                */
                    /* 

                    START OF CLIENT BOOKING

                */   
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($clientRevenueCYear[$c][$m])."</td>";
                        }else{
                            echo "<td class='$even[$m]' >".number_format($clientRevenueCYear[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>".number_format($clientRevenueCYear[$c][$m])."</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT BOOKING

                */ 
                /* 

                    START OF CLIENT PAST YEAR

                */ 
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }else{
                            echo "<td class='$odd[$m]'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPY-$c' name='totalPY-$c' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; color:white; background-color:transparent; font-weight:bold; border:none; text-align:center'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT PAST YEAR

                */ 
                    /* 

                    START OF CLIENT RF VS PYEAR

                */
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCST[$c][$m] - $clientRevenuePYear[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>";                            
                                echo "<input type='text' readonly='true' id='RFvsPY-$c-$m' name='RFvsPY-$c-$m' value='".number_format($tmp)."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                        }else{
                            echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>";                            
                                echo "<input type='text' name='RFvsPY-$c-$m' readonly='true' id='RFvsPY-$c-$m' value='".number_format($tmp)."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'><input type='text' id='totalRFvsPY-$c' name='totalRFvsPY-$c' readonly='true' value='".number_format($rollingFCST[$c][$m] - $clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; color:white; text-align:center'></td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT RF VS PYEAR

                */
                
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
        }
    }
}
