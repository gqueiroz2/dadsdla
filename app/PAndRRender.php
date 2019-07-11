<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PAndRRender extends Render
{
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function AE1($total2018,$totaltotal2018,$totalClient2018,$client2018){
    	echo "<table style=' border: solid; border-width:1px; width:100%; margin-top:1,5%; text-align:center;' >";
    		echo "<tr>";
                echo "<td class='lightBlue' style='width:15%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Nome do Executivo</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) {
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='quarter' style='width:4.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>".$this->month[$m]."</td>";
                    }else{
                        echo "<td class='lightGrey' style='width:4.5% border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>".$this->month[$m]."</td>";
                    }
                }
                echo "<td class='darkBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";
    		echo "</tr>";
            
    		echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='rcBlue' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
    		echo "</tr>";
    		echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Roling Fcast 2019</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='rf-$m' value='0' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='odd' style='width:4.5%'><input type='text' id='rf-$m' value='0' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'><input type='text' readonly='true' id='total-total' value='0' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
    		echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Booking</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='rcBlue' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Pending</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>2018</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' value='$total2018[$m]' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }else{
                        echo "<td class='rcBlue' style='width:4.5%'><input type='text' readonly='true' id='oldY-$m' value='$total2018[$m]' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'><input type='text' readonly='true' id='totalOldYear' value='$totaltotal2018' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>% Target Achievement</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='rcBlue' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Plan</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                    }else{
                        echo "<td class='odd' style='width:4.5%'>0</td>";
                    }
                }
                echo "<td class='smBlue' style='width:4.5%'>0</td>";
            echo "</tr>";
    	echo "</table>";
        

        echo "<br>";


        echo "<table style=' width:100%; margin-top:1,5%; text-align:center;' >";
            for ($c=0; $c <10/*numero de clientes*/ ; $c++) { 
                echo "<tr>";
                    echo "<td class='lightBlue' style='width:15%; text-align:center; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' >Nome do Cliente-$c</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' style='width:4.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='lightGrey' style='width:4.5%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;'>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>Total</td>";

                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Roling Fcast 2019</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:4.5%;  border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='clientRF-$c-$m' value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                        }else{
                            echo "<td class='odd' style='width:4.5%'>
                                <input type='text' id='clientRF-$c-$m' value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'>";

                                for ($ch=0; $ch <sizeof($this->channel); $ch++) {
                                    echo "<div style='display:none;'>".$this->channel[$ch]."</div>" ;
                                    echo "<input type='hidden'>";
                                }

                            echo "</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalClient-$c' value='0' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center'></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Booking</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='rcBlue' style='width:4.5%'>0</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Plan</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='odd' style='width:4.5%'>0</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldCY-$c-$m' value='".$client2018[$c][$m]."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }else{
                            echo "<td class='rcBlue' style='width:4.5%'><input type='text' readonly='true' id='oldCY-$c-$m' value='".$client2018[$c][$m]."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldCY-$c' value='".$totalClient2018[$c]."' style='width:100%; color:white; background-color:transparent; font-weight:bold; border:none; text-align:center'></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>Var RF vs 2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>0</td>";
                        }else{
                            echo "<td class='odd' style='width:4.5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:4.5%;  border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>&nbsp</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td>&nbsp</td>";
                    }

                    echo "<td>&nbsp</td>";
                echo "</tr>";
            }
        echo "</table>";

    }
    public function AE2(){
        echo "<table style=' border: solid; width:100%; margin-top:1,5%;' >";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>".$this->head[$h]."</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
             echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td>0</td>";
                echo "<td>0%</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
            echo "<tr>";
                for ($h=0; $h <sizeof($this->head) ; $h++) { 
                    echo "<td>&nbsp</td>";
                }
            echo "</tr>";
        echo "</table>";
        echo "<br>";
        echo "<table style=' border: solid; width:100%; margin-top:1,5%;' >";
            for ($c=0; $c <10/*numero de clientes*/ ; $c++) { 
                echo "<tr>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>&nbsp</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>-</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>-</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>-</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>-</td>";
                    }
                echo "</tr>";
                echo "<tr style='border-bottom:1px dotted'>";
                    for ($h=0; $h <sizeof($this->head) ; $h++) { 
                        echo "<td style='width:4.5%'>-</td>";
                    }
                echo "</tr>";
            }
        echo "</table>";
    }
}
