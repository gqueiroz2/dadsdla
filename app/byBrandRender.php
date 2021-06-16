<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class byBrandRender extends Render{

	protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

     public function bybrand($forRender, $brands){

        /*$cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        $salesRep = $forRender['salesRep'];
        $client = $forRender['client'];
        $splitted = $forRender['splitted'];
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
        $brandsPerRep = $forRender["brandsPerRep"];
        $emptyCheck = $forRender["emptyCheck"];

        $nSecondary = $forRender["nSecondary"];
        //$bookingPYear = $forRender["bookingPYear"];
		$brandValueCYear = $forRender["brandValueCYear"];
		$brandValuePYear = $forRender["brandValuePYear"];*/

       /* echo "<input type='hidden' id='salesRep' name='salesRep' value='".base64_encode(json_encode($salesRep))."'>";
        echo "<input type='hidden' id='client' name='client' value='".base64_encode(json_encode($client)) ."'>";
        echo "<input type='hidden' id='currency' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' id='splitted' name='splitted' value='".base64_encode(json_encode($splitted))."'>";
        echo "<input type='hidden' id='value' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' id='region' name='region' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' id='user' name='user' value='".base64_encode(json_encode($userName))."'>";
        echo "<input type='hidden' id='year' name='year' value='".base64_encode(json_encode($cYear))."'>";
        echo "<input type='hidden' id='year' name='brandsPerClient' value='".base64_encode(json_encode($brandsPerRep))."'>";*/

       /* echo "<div class='table-responsive' style='zoom:80%;'>
                    <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>
                        <tr><th class='lightBlue'>".$salesRep['salesRep']." - ".$currencyName."/".$valueView."</th></tr>
                    </table>
              </div>";
        echo "<br>";

        echo "<div class='sticky-top' style='zoom:80%; scroll-margin-botton: 10px;'>";
            echo "<table style='width:100%; text-align:center;'>";
                echo "<tr>";
                    echo "<td class='darkBlue' rowspan='1' style=' text-align:center;' style='width:7%;'>".$salesRep['abName']."</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) {
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter'>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='smBlue'>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue'>Total</td>";                
                    echo "<td class='lightGrey'>Closed</td>";
                    echo "<td class='lightGrey'>Cons. (%)</td>";
                    echo "<td class='lightGrey'>Exp</td>";
                    echo "<td class='lightGrey'style='width:5%;'>Prop</td>";
                    echo "<td class='lightGrey'>Adv</td>";
                    echo "<td class='lightGrey'>Contr</td>";
                    echo "<td class='lightGrey'>Total</td>";
                    echo "<td class='lightGrey'>Lost</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left;'>Target</td>";
                    $totalTarget = 0.0;
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                            $totalTarget += $targetValues[$m];
                        }else{
                            echo "<td class='$even[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left;'><span>Rolling Fcast ".$cYear."</span><br>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                        }else{
                            echo "<td class='$odd[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][4],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][7],2,',','.')."%</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][0],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][1],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][2],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][3],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][6],2,',','.')."</td>";
                    echo "<td class='odd'>".number_format($fcstAmountByStageEx[1][5],2,',','.')."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left;'>Bookings</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                        }else{
                            echo "<td class='$even[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left;'>Pending</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                        }else{
                            echo "<td class='$odd[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left;'>".$pYear."</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                        }else{
                            echo "<td class='$even[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left;'>Var RF vs Target</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'></td>";
                        }else{
                            echo "<td class='$odd[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";                
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left;'>% Target Achievement</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'>%</td>";
                        }else{
                            echo "<td class='$even[$m]'>%</td>";
                        }
                    }
                    echo "<td class='smBlue'>%</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                    echo "<td class='rcBlue'>&nbsp</td>";
                echo "</tr>"; 
                echo "<tr><td> &nbsp; </td></tr>";          

            echo "</table>";
        echo "</div>";

		echo "<div style='zoom:80%; scroll-margin-botton: 10px;'>";
            echo "<table style='width:100%; text-align:center;'>";
        		for ($b=0; $b <sizeof($brands) ; $b++) { 
                    echo "<tr>";
                        echo "<td class='darkBlue' rowspan='1' style=' text-align:center;' style='width:7%;'>".$brands[$b]['name']."</td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='quarter' >".$this->month[$m]."</td>";
                            }else{
                                echo "<td class='smBlue' >".$this->month[$m]."</td>";
                            }
                        }
                        echo "<td class='darkBlue' >Total</td>";
	                    echo "<td class='lightGrey'>Closed</td>";
	                    echo "<td class='lightGrey'>Cons. (%)</td>";
	                    echo "<td class='lightGrey'>Exp</td>";
	                    echo "<td class='lightGrey'>Prop</td>";
	                    echo "<td class='lightGrey'>Adv</td>";
	                    echo "<td class='lightGrey'>Contr</td>";
	                    echo "<td class='lightGrey'>Total</td>";
	                    echo "<td class='lightGrey'>Lost</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left;'> Rolling Fcast ".$cYear." </td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 ) {
                            echo "<td class='medBlue' ></td>";
                        }else{
                        	echo "<td class='$even[$m]'></td>";
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00%</td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00</td>";
                    echo "<td class='rcBlue'>0.00</td>";    
                    
                echo "</tr>";
                    /* BOOKING C YEAR 
                    echo "<tr class='center'>";
                    	echo "<td class=' odd' style='text-align:left;'>Booking</td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                           if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' ></td>";
                            }else{
                                echo "<td class='$odd[$m]' ></td>";
                            }
                        }
                        echo "<td class='smBlue' ></td>";
                        echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
		                echo "<td class='odd'>&nbsp</td>";
                    echo "</tr>";

                     /* BOOKING P YEAR 
                    echo "<tr class='center'>";
                    	echo "<td class='rcBlue' style='text-align:left;'>".$pYear."</td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                           if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue'></td>";
                            }else{
                                echo "<td class='$even[$m]'></td>";
                            }
                        }
                        echo "<td class='smBlue'></td>";
                        echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
		                echo "<td class='rcBlue'>&nbsp</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td class='odd' style='text-align:left;'>Var RF vs ".$pYear."</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue'>";                            
                                //echo "".number_format($tmp,2,',','.')."";
                            echo "</td>";
                        }else{
                            echo "<td class='$odd[$m]'>";                            
                                //echo "".number_format($tmp,2,',','.')."";
                            echo "</td>";                           
                        }
                    }
                    echo "<td class='smBlue'></td>";
                    echo "<td class='odd'></td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                    echo "<td class='odd'>&nbsp</td>";
                echo "</tr>";           
                echo "<tr><td> &nbsp; </td></tr>";
                }
            echo "</table>";
        echo "</div>"; */

     }

}
