<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PAndRRender extends Render
{
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function AE1($forRender,$client,$total2018,$totaltotal2018,$totalClient2018,$client2018,$tfArray){

        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        $salesRep = $forRender['salesRep'];
        $client = $forRender['client'];
        $targetValues = $forRender['targetValues'];



        echo "<div class='table-responsive'>";
    	echo "<table style='min-width:2600px; width:100%; margin-top:1,5%; text-align:center;'>";
    		/*
                
                START OF SALES REP AND SALES REP TOTAL MONTHS

            */
            echo "<tr>";
                echo "<td class='lightBlue' style='width:8%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; font-size:20px'>".$salesRep['salesRep']."</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) {
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='quarter' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>".$this->month[$m]."</td>";
                    }else{
                        echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>".$this->month[$m]."</td>";
                    }
                }
                echo "<td class='darkBlue' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";
                echo "<td rowspan='9' style='width:0.5%;'>&nbsp</td>";
                echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Closed</td>";
                echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>%Cons.</td>";
                echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Prop</td>";
                echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Fcast</td>";
                echo "<td class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";
    		echo "</tr>";
            
            /*
                
                START OF TARGET BY SALES REP INFO

            */
    		echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
                $totalTarget = 0.0;
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($targetValues[$m])."</td>";
                        $totalTarget += $targetValues[$m];
                    }else{
                        echo "<td class='rcBlue'>".number_format($targetValues[$m])."</td>";
                    }
                }
                echo "<td class='smBlue'>".number_format($targetValues[$m])."</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
    		echo "</tr>";
            /*
                
                END OF TARGET BY SALES REP INFO

            */

            /*
                
                START OF ROLLING FCST BY SALES REP INFO

            */ 
    		echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Roling Fcast 2019</span><br>";
                    //echo "<div style='display:none;' id='totalTotalPP'><span >Total P.P. (%):   </span><input type='number' value='100' readonly='true' id='totalClients' style='display:;width:30%;text-align:right;'></div>";
                echo"</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='rf-$m' value='-1' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='odd'><input type='text' readonly='true' id='rf-$m' value='0' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' ><input type='text' readonly='true' id='total-total' value='0' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0%</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
    		echo "</tr>";
            /*
                
                END OF ROLLING FCST BY SALES REP INFO

            */ 

            /*
                
                START OF BOOKED BY SALES REP INFO

            */ 
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Bookings</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='rcBlue' >0</td>";
                    }
                }
                echo "<td class='smBlue' >0</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END OF BOOKED BY SALES REP INFO

            */ 

            /*
                
                START OF PENDING BY SALES REP INFO

            */ 
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Pending</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' >0</td>";
                    }
                }
                echo "<td class='smBlue' >0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END OF PENDING BY SALES REP INFO

            */ 


            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>2018</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' value='$total2018[$m]' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }else{
                        echo "<td class='rcBlue'><input type='text' readonly='true' id='oldY-$m' value='$total2018[$m]' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }
                }
                echo "<td class='smBlue' ><input type='text' readonly='true' id='totalOldYear' value='$totaltotal2018' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' >0</td>";
                    }
                }
                echo "<td class='smBlue' >0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>% Target Achievement</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='rcBlue' >0</td>";
                    }
                }
                echo "<td class='smBlue' >0</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>Var RF vs Plan</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' style='border-style:solid; border-color:black; border-width:0px 0px 1px 0px;' >0</td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width:0px 0px 1px 0px;'>0</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
            echo "</tr>";
    	echo "</table>";
        echo "</div>";

        echo "<br>";


        for ($c=0; $c <10/*numero de clientes*/; $c++) {
            echo "<div class='table-responsive'>";
            echo "<table id='table-$c' style='min-width:2600px; width:100%; margin-top:1,5%; text-align:center; overflow:auto;' >";
                
                echo "<tr>";
                    echo "<td class='lightBlue' id='client-$c' rowspan='1' style='width:8%; text-align:center; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'><span style='font-size:18px;'>Nome do Cliente-$c</span>";

                    
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' id='quarter-$c-$m' rowspan='1' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='lightGrey' colspan='1' id='month-$c-$m' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue' id='TotalTitle-$c' rowspan='1' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>Total</td>";
                    echo "<td rowspan='7' id='division-$c' style='width:0.5%;'>&nbsp</td>";
                    echo "<td id='sideTable-$c-0' rowspan='1' class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Closed</td>";
                    echo "<td id='sideTable-$c-1' rowspan='1' class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>%Cons.</td>";
                    echo "<td id='sideTable-$c-2' rowspan='1' class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Prop</td>";
                    echo "<td id='sideTable-$c-3' rowspan='1' class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Fcast</td>";
                    echo "<td id='sideTable-$c-4' rowspan='1' class='lightGrey' style='width:3.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";

                echo "</tr>";
                echo "<tr style='display:none;' id='newLine-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {

                        }else{
                            echo "<td colspan='1' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;' class='lightGrey'>";
                                echo "<div class='row'>";
                                    echo "<div class='col' id='input-$c-$m' style='display:none;width:100%;'><span>P.P. (%):</span><input id='inputNumber-$c-$m' type='number' min='0' max='100' step='0.5' value='0' style='width:25%; background-color:transparent; text-align:right; border-style:solid; border-color: grey; border-width:1px;'></div>";
                                    echo "<input type='number' style='display:none;' id='inputNumber2-$c-$m' value='0'>";

                                echo "</div>";

                            echo "</td>";
                        }
                    }
                echo "</tr>";

                echo "<tr>";
                    echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'> Roling Fcast 2019 </td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='rcBlue'>0</td>";
                    
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' id='passTotal-$c' readonly='true' value='10000' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center; color:white;'></td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0%</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                echo "</tr>";

                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Manual Estimation";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='clientRF-$c-$m' value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                        }else{
                            echo "<td class='odd'>
                                <input type='text' id='clientRF-$c-$m' ".$tfArray[$m]." value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'>";
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
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalClient-$c' value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center'><input type='text' readonly='true' id='totalTClient-$c' value='50000' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center;display:none;'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";

                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Booking</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='rcBlue' >0</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Plan</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='odd'>0</td>";
                            echo "<td id='RFxPlan-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldCY-$c-$m' value='".$client2018[$c][$m]."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }else{
                            echo "<td class='rcBlue'><input type='text' readonly='true' id='oldCY-$c-$m' value='".$client2018[$c][$m]."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldCY-$c' value='".$totalClient2018[$c]."' style='width:100%; color:white; background-color:transparent; font-weight:bold; border:none; text-align:center'></td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>Var RF vs 2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>0</td>";
                        }else{
                            echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "</tr>";
                
            echo "</table>";
            echo "</div>";
            echo "<br>";
        }   

    }

    public function VP1(){
        echo "<div class='table-responsive'>";
            echo "<table  style='min-width:2600px; width:100%; margin-top:1,5%; text-align:center;'>";
                echo "<tr>";
                    echo "<td style='width:8%;'>&nbsp</td>";
                    echo "<td style='width:1%;'>&nbsp</td>";
                    echo "<td class='darkBlue' style='width:10%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;' colspan='2'>BookingYTD</td>";
                    echo "<td class='darkBlue' style='width:5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>MesP.</td>";
                    echo "<td style='width:1%;'>&nbsp</td>";
                    echo "<td class='darkBlue' style='width:20%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;' colspan='4'>Current Month</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;' >MesA.</td>";
                    echo "<td style='width:1%;'>&nbsp</td>";
                    echo "<td class='darkBlue' style='width:45%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' colspan='9'>Full Year</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' >&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>2019</td>";
                    echo "<td class='lightBlue'>2018</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. 2018</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='3' style='width:15%; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>2019</td>";
                    echo "<td class='lightBlue'>2018</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. 2018</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='6' style='width:30%; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>2019</td>";
                    echo "<td class='lightBlue' >2018</td>";
                    echo "<td class='lightBlue' colspan='2' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var 2019/2018</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' style='width:8%; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Bookings</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcast</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Closed</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Booked</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>% Booked</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Proposals</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcast</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>\$</td>";
                    echo "<td class='lightBlue' style='width:5%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>%</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' >Total</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>0</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>0</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>0</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>0%</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>%</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0%</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0%</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td>&nbsp</td>";
                echo "</tr>";

                for ($c=0; $c </*sizeof(clientes)*/10; $c++) {
                    if($c%2 == 0){
                        $class = "rcBlue";
                    }else{
                        $class = "odd";
                    }

                    echo "<tr>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'>Nome do Cliente-$c</td>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 0px 0px 0px;'>&nbsp</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;'>0</td>";
                        echo "<td>&nbsp</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;'>0</td>";
                        echo "<td>&nbsp</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0%</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>0</td>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;'>0%</td>";
                    echo "</tr>";
                }
            echo "</table>";
        echo "</div>";
    }    

    public function PandR1(){
        echo "<div class='table-responsive'>";
            for ($b=0; $b <sizeof($this->channel) ; $b++) {
                echo "<table style='width:100%; margin-top:1,5%; text-align:center;' >";
                    echo "<tr>";
                        echo "<td colspan='2' class='".strtolower($this->channel[$b])."' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:15%;'>&nbsp</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='quarter' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:4.5%;'>".$this->month[$m]."</td>";
                            }else{
                                echo "<td class='lightGrey' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:4.5%;'>".$this->month[$m]."</td>";
                            }
                        }
                        echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:6%;'>Total</td>";
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td rowspan='9' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;' class='".strtolower($this->channel[$b])."' >".$this->channel[$b]."</td>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;' class='rcBlue'>2018 Ad Sales</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>0</td>";
                            
                            }else{
                                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;' class='rcBlue'>0</td>";    
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' 
                         class='odd'>2018 SAP</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }    
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>Target</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";               

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>Fcast Ad Sales - Current</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='fa-$b-$m' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                            }else{
                                echo "<td class='odd'><input type='text' id='fa-$b-$m' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                            }   
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='total-$b' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>Forecast Corporate</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>2019 Ad Sales</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }   
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>2019 SAP</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }    
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>Fcast 2019 - Fcast 2018 (%)</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }  
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>2019-Target (%)</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>";    
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0</td>";    
                    echo "</tr>";

                echo "</table>";
                echo "<br>";
            }
        echo "</div>";
    }
    
}
