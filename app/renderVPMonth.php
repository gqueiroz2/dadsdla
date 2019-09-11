<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;

class renderVPMonth extends Render {
    
    public function defineStyle($cDate, $date){

        $base = new base();

        for ($m=0; $m < sizeof($base->month); $m++) { 

            if ($cDate == $base->month[$m][2]) {
                $cDateVal = $base->month[$m][1];
            }

            if ($date == $base->month[$m][2]) {
                $dateVal = $base->month[$m][1];   
            }
        }
        
        if (!isset($cDateVal)) {
            $cDateVal = 0;
        }

        if (!isset($dateVal)) {
            $dateVal = 0;
        }

        return $dateVal >= $cDateVal ? 1 : 0;

    }

    public function assemble($mtx, $value, $currency, $region){
    	
        $cMonth = date('F');

    	echo "<table style='width: 100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='".sizeof($mtx[0])."' class='lightBlue'><center><span style='font-size:24px;'> $region - (".$currency[0]['name']."/".strtoupper($value).")</span></center></th>";
        	echo "</tr>";

            echo "<tr><td> &nbsp; </td></tr>";

            for ($m=0; $m < sizeof($mtx); $m++) { 
            	echo "<tr>";
            	for ($v=0; $v < sizeof($mtx[$m]); $v++) { 
            		if (is_numeric($mtx[$m][$v])) {
                        if ($v == 0) {
                            echo "<td class='center rcBlue'>".number_format($mtx[$m][$v])."</td>";    
                        }elseif ($v == sizeof($mtx[0])-1) {
                            if ($m == sizeof($mtx)-1) {
                                echo "<td class='center smBlue'>".number_format($mtx[$m][$v])."%</td>";
                            }else{
                                echo "<td class='center smBlue'>".number_format($mtx[$m][$v])."</td>";
                            }
                        }elseif ($v == 4 || $v == 8 || $v == 12 || $v == 16) {
                            if ($m == sizeof($mtx)-1) {
                                echo "<td class='center medBlue' style='border-left: solid 1px black; border-right: solid 1px black; border-bottom: solid 1px black;'>".number_format($mtx[$m][$v])."%</td>";   
                            }else{
                                echo "<td class='center medBlue' style='border-left: solid 1px black; border-right: solid 1px black;'>".number_format($mtx[$m][$v])."</td>";
                            }
                        }else{
                            $style = $this->defineStyle($cMonth, $mtx[0][$v]);

                            if ($style) {
                                if ($m%2 != 0) {
                                    if ($m == sizeof($mtx)-1) {
                                        echo "<td class='center rcBlue'>".number_format($mtx[$m][$v])."%</td>";
                                    }else{
                                        if ($mtx[$m][0] == "Manual Estimation") {
                                            echo "<td class='center rcBlue' style='background-color:#99b3ff;'>";
                                                echo "<input type='text' value='".number_format($mtx[$m][$v])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center;'>";
                                            echo "</td>";
                                        }else{
                                            echo "<td class='center rcBlue'>".number_format($mtx[$m][$v])."</td>";
                                        }
                                    }
                                }else{
                                    echo "<td class='center odd'>".number_format($mtx[$m][$v])."</td>";
                                }
                            }else{
                                if ($m%2 != 0) {
                                    if ($m == sizeof($mtx)-1) {
                                        echo "<td class='center evenGrey'>".number_format($mtx[$m][$v])."%</td>";
                                    }else{
                                        echo "<td class='center evenGrey'>".number_format($mtx[$m][$v])."</td>";
                                    }
                                }else{
                                    echo "<td class='center oddGrey'>".number_format($mtx[$m][$v])."</td>";
                                }
                                
                            }
                        }
            		}else{
                        if ($m == 0) {
                            if ($v == 0 || $v == sizeof($mtx[0])-1) {
                                echo "<td class='center darkBlue'>".$mtx[$m][$v]."</td>";
                            }elseif ($v == 4 || $v == 8 || $v == 12 || $v == 16) {
                                echo "<td class='center quarter' style='border-left: solid 1px black; border-right: solid 1px black; border-top: solid 1px black;'>".$mtx[$m][$v]."</td>";
                            }else{
                                echo "<td class='center smBlue'>".$mtx[$m][$v]."</td>";
                            }
                        }else{
                            if ($m%2 != 0) {
                                echo "<td class='center rcBlue'>".$mtx[$m][$v]."</td>";
                            }else{
                                echo "<td class='center odd'>".$mtx[$m][$v]."</td>";
                            }
                        }
            		}
            	}
            	echo "</tr>";
            }

            echo "</table>";
    }
}
