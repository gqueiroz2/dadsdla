<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\pRate;
use App\rank;

class rankings extends rank{

    public function getAllResults($con, $brands, $type, $region, $value, $currency, $months, $years){

        if ($type == "agencyGroup") {
            $res = $this->getAllValues($con, "ytd", $type, $type, $brands, $region, $value, $years, $months, $currency, "agency");            
        }
        else{
            $res = $this->getAllValues($con, "ytd", $type, $type, $brands, $region, $value, $years, $months, $currency);    
        }
        
        return $res;
    }

    public function checkOtherYearsPosition($name, $values, $year, $type){
        
        if ($year == "2019") {
            $y = 0;
        }elseif ($year == "2018") {
            $y = 1;
        }else{
            $y = 2;
        }

        $ok = 0;

        if (is_array($values[$y])) {
            for ($v=0; $v < sizeof($values[$y]); $v++) { 
                if ($values[$y][$v][$type] == $name) {
                    $pos = $v+1;
                    $ok = 1;
                }
            }   
        }else{
            $pos = false;
        }

        if ($ok == 0) {
            $pos = 0;
        }

        return $pos;

    }

    public function getValueByYear($name, $values, $year, $type){
        
        if ($year == "2019") {
            $y = 0;
        }elseif ($year == "2018") {
            $y = 1;
        }else{
            $y = 2;
        }

        $ok = 0;

        if (is_array($values[$y])) {
            for ($v=0; $v < sizeof($values[$y]); $v++) { 
            
                if ($values[$y][$v][$type] == $name) {
                    $rtr = $values[$y][$v]['total'];
                    $ok = 1;
                }
            }
        }else{
            $rtr = false;
        }

        if ($ok == 0) {
            $rtr = 0;
        }

        return $rtr;
    }

    public function checkColumn($mtx, $m, $v, $values, $name, $val, $type){
        
        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($name, $values, $var, $type);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($name, $values, $var, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            $res = $mtx[1][$v] - $mtx[2][$v];
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[2][$v] == 0) {
                $res = 0.0;
            }else{
                $res = ($mtx[1][$v] / $mtx[2][$v])*100;
            }
        }else{
            $res = $name;
        }

        return $res;

    }

    public function assembler($values, $years, $type){
        //var_dump($values);

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agency";
        }else{
            $var = $type;
            $var .= "s";
            $aux = $type;
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = ucfirst($var);
        

        for ($l=0; $l < sizeof($years); $l++) { 
            
            $mtx[(sizeof($years)+$l+1)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+1;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        for ($y=0; $y < sizeof($years); $y++) {
            if (is_array($values[$y])) {
                for ($v=0; $v < sizeof($values[$y]); $v++) { 
                    for ($m=0; $m < sizeof($mtx); $m++) {
                        $mtx[$m][$v+1] = $this->checkColumn($mtx, $m, ($v+1), $values, $values[$y][$v][$aux], $values[$y][$v]['total'], $aux);
                    }
                }    
            }else{
                $mtx[$m][$v+1] = false;
            }
            
        }

        var_dump($mtx);

    }
}
