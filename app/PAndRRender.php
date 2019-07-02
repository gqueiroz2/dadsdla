<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PAndRRender extends Render
{
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function AE1($total2018,$totaltotal2018,$totalClient2018,$client2018){
    	echo "<table style=' border: solid; width:100%; margin-top:1,5%; text-align:center;' >";
    		echo "<tr>";
                echo "<td style='width:15%'></td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>".$this->month[$m]."</td>";
                }
                echo "<td style='width:4.5%'>Total</td>";
    		echo "</tr>";
            echo "<tr>";
                echo "<td></td>";
                for ($m=0; $m <sizeof($this->month); $m++) { 
                    echo "<td style='width:4.5%'></td>";
                }
                echo "<td></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Nome do Executivo</td>";
                for ($m=0; $m <sizeof($this->month); $m++) { 
                    echo "<td style='width:4.5%'></td>";
                }
                echo "<td></td>";
            echo "</tr>";
    		echo "<tr>";
                echo "<td style='text-align:left'>Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
    		echo "</tr>";
    		echo "<tr>";
                echo "<td style='text-align:left'>Roling Fcast 2019</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td style='width:4.5%'><input type='text' readonly='true' id='rf-$m' value='0' style='width:100%; border:none;    text-align:center'></td>";
                    }else{
                        echo "<td style='width:4.5%'><input type='text' id='rf-$m' value='0' style='width:100%; border:none; text-align:center'></td>";
                    }
                }
                echo "<td style='width:4.5%'><input type='text' readonly='true' id='total-total' value='0' style='width:100%; border:none; text-align:center'></td>";
    		echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Booking</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Pending</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>2018</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'><input type='text' readonly='true' id='oldY-$m' value='$total2018[$m]' style='width:100%; border:none; text-align:center' ></td>";
                }
                echo "<td style='width:4.5%'><input type='text' readonly='true' id='totalOldYear' value='$totaltotal2018' style='width:100%; border:none; text-align:center' ></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Var RF vs Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>% Target Achievement</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Var RF vs Plan</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
                echo "<td style='width:4.5%'>0</td>";
            echo "</tr>";
    	echo "</table>";
        echo "<br>";
        echo "<table style=' border: solid; width:100%; margin-top:1,5%; text-align:center;' >";
            for ($c=0; $c <10/*numero de clientes*/ ; $c++) { 
                echo "<tr>";
                    echo "<td style='width:15%; text-align:left' >Nome do Cliente-$c</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'></td>";
                    }
                    echo "<td style='width:4.5%'></td>";

                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Roling Fcast 2019</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td style='width:4.5%'><input type='text' readonly='true' id='clientRF-$c-$m' value='0' style='width:100%; border:none; text-align:center'></td>";
                        }else{
                            echo "<td style='width:4.5%'><input type='text' id='clientRF-$c-$m' value='0' style='width:100%; border:none; text-align:center'></td>";
                        }
                    }
                    echo "<td style='width:4.5%'><input type='text' readonly='true' id='totalClient-$c' value='0' style='width:100%; border:none; text-align:center'></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Booking</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                    echo "<td style='width:4.5%'>0</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Var RF vs Plan</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                    echo "<td style='width:4.5%'>0</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'><input type='text' readonly='true' id='oldCY-$c-$m' value='".$client2018[$c][$m]."' style='width:100%; border:none; text-align:center' ></td>";
                    }
                    echo "<td style='width:4.5%'><input type='text' readonly='true' id='totalOldCY-$c' value='".$totalClient2018[$c]."' style='width:100%; border:none; text-align:center'></td>";
                echo "</tr>";
                echo "<tr style='border-bottom:1px dotted'>";
                    echo "<td style='text-align:left;'>Var RF vs 2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                    echo "<td style='width:4.5%'>0</td>";
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
