<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PAndRRender extends Render
{
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4','Total');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function AE1(){
    	echo "<table style=' border: solid; width:100%; margin-top:1,5%; text-align:center;' >";
    		echo "<tr>";
                echo "<td style='width:15%'></td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>".$this->month[$m]."</td>";
                }
    		echo "</tr>";
            echo "<tr>";
                echo "<td></td>";
                for ($m=0; $m <sizeof($this->month); $m++) { 
                    echo "<td style='width:4.5%'></td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Nome do Executivo</td>";
                for ($m=0; $m <sizeof($this->month); $m++) { 
                    echo "<td style='width:4.5%'></td>";
                }
            echo "</tr>";
    		echo "<tr>";
                echo "<td style='text-align:left'>Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
    		echo "</tr>";
    		echo "<tr>";
                echo "<td style='text-align:left'>Roling Fcast 2019</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'><input type='text' id='rf-$m' value='0' style='width:100%; border:none; text-align:center'></td>";
                }
    		echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Booking</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Pending</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>2018</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Var RF vs Target</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>% Target Achievement</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left'>Var RF vs Plan</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    echo "<td style='width:4.5%'>0</td>";
                }
            echo "</tr>";
    	echo "</table>";
        echo "<br>";
        echo "<table style=' border: solid; width:100%; margin-top:1,5%; text-align:center;' >";
            for ($c=0; $c <10/*numero de clientes*/ ; $c++) { 
                echo "<tr>";
                    echo "<td style='width:15%; text-align:left' >$c</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'></td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Roling Fcast 2019</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'><input type='text' id='clientRF-$c-$m' value='0' style='width:100%; border:none; text-align:center'></td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Booking</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>Var RF vs Plan</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='text-align:left;'>2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
                echo "</tr>";
                echo "<tr style='border-bottom:1px dotted'>";
                    echo "<td style='text-align:left;'>Var RF vs 2018</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        echo "<td style='width:4.5%'>0</td>";
                    }
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
