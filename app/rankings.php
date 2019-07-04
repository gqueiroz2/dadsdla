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

    public function checkOtherYearsPosition($name, $values, $year, $years, $type){
        
        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;       
            }
        }

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
                if ($values[$p][$v][$type] == $name) {
                    $pos = $v+1;
                    $ok = 1;
                }
            }   
        }else{
            $pos = false;
        }

        if ($ok == 0) {
            $pos = -1;
        }

        return $pos;

    }

    public function getValueByYear($name, $values, $year, $years, $type){
        
        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;       
            }
        }

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
            
                if ($values[$p][$v][$type] == $name) {
                    $rtr = $values[$p][$v]['total'];
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

    public function checkColumn($mtx, $m, $type2, $t, $values, $years, $type, $p){
        
        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($type2[$t]->name, $values, $var, $years, $type);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($type2[$t]->name, $values, $var, $years, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            $res = $mtx[$m-sizeof($years)][$p] - $mtx[$m-sizeof($years)+1][$p];
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[$m-sizeof($years)][$p] == 0) {
                $res = 0.0;
            }else{
                $res = ($mtx[$m-sizeof($years)-1][$p] / $mtx[$m-sizeof($years)][$p])*100;
            }
        }else{
            $res = $type2[$t]->name;
        }

        return $res;

    }

    public function assembler($values, $type2, $years, $type, $filterValues, $size){
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

        $p = 1;

        for ($t=0; $t < $size; $t++) { 
            
            if ($filterValues[$type2[$t]->id] == 1) {
                
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    array_push($mtx[$m], $this->checkColumn($mtx, $m, $type2, $t, $values, $years, $aux, sizeof($mtx[$m])));

                }
            }
        }
        
        //var_dump($mtx);
        return $mtx;

    }
}
