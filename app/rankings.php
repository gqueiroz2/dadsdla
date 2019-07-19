<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\pRate;
use App\rank;
use App\subRankings;
use App\base;

class rankings extends rank{

    public function getAllResults($con, $brands, $type, $region, $value, $currency, $months, $years){

        if ($type == "agencyGroup") {
            $res = $this->getAllValues($con, "ytd", $type, $type, $brands, $region, $value, $years, $months, $currency, "agency");            
        }else{
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
            $pos = "-";
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

    public function checkColumn($mtx, $m, $type2, $t, $values, $years, $type, $p, $typeF){

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
            if ($typeF == "agency") {
                $res = $type2[$t]->name." - ".$type2[$t]->agencyGroup;    
            }else{
                $res = $type2[$t]->name;    
            }
            
        }

        return $res;

    }

    public function assembler($values, $type2, $years, $type, $filterValues, $size){
        //var_dump($values);

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agencyGroup";
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
        
        for ($t=0; $t < $size; $t++) { 
            
            if ($filterValues[$type2[$t]->id] == 1) {
                
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    array_push($mtx[$m], $this->checkColumn($mtx, $m, $type2, $t, $values, $years, $aux, sizeof($mtx[$m]), $type));
                }
            }
        }
        
        $fun = "array_multisort(";

        for ($m=0; $m < sizeof($mtx); $m++) { 
            $fun .= "\$mtx[".$m."], SORT_ASC";

            if ($m != sizeof($mtx)-1) {
                $fun .= ", ";
            }
        }

        $fun .= ");";
        eval($fun);

        $total = $this->assemblerTotal($mtx, $years);

        //var_dump($mtx);
        return array($mtx, $total);
    }

    public function assemblerTotal($mtx, $years){

        for ($l=0; $l < sizeof($mtx); $l++) { 

            if (substr($mtx[$l][0], 0, 3) == "Rev") {
                $vec[$l] = 0;
            }elseif (substr($mtx[$l][0], 0, 3) == "VAR") {
                $vec[$l] = 0;
                
                if ($mtx[$l][0] == "VAR ABS.") {
                    $varAbs = $l;
                }else{
                    $varP = $l;
                }
            }else{
                $vec[$l] = "-";
            }       

            for ($c=0; $c < sizeof($mtx[$l]); $c++) { 
                
                if ($c != 0 && substr($mtx[$l][0], 0, 3) == "Rev") {
                    $vec[$l] += $mtx[$l][$c];
                }
            }
        }

        $vec[0] = "Total";

        if (isset($varAbs)) {
            
            $vec[$varAbs] = $vec[$varAbs-sizeof($years)] - $vec[$varAbs-sizeof($years)+1];

            if ($vec[$varP-sizeof($years)] == 0) {
                $vec[$varP] = 0.0;
            }else{
                $vec[$varP] = ($vec[$varP-sizeof($years)-1] / $vec[$varP-sizeof($years)])*100;
            }
        }

        return $vec;
    }

    public function createNames($type, $months, $years){
        
        if ($type == "agencyGroup") {
            $res['name'] = "Agency groups";
        }else{
            $res['name'] = ucfirst($type)."s";
        }

        $b = new base();

        $month = $b->intToMonth2($months);

        $res['months'] = "";

        for ($m=0; $m < sizeof($month); $m++) { 
            
            $res['months'] .= $month[$m];

            if ($m == sizeof($month)-2) {
                $res['months'] .= " and ";
            }elseif (($m != sizeof($month)-2) && ($m != sizeof($month)-1)) {
                $res['months'] .= ", ";
            }
            
        }

        if (sizeof($years) >= 2) {
            $res['years'] = $years[0]." and ".$years[1];
        }else{
            $res['years'] = $years[0];
        }
        

        return $res;
    }
}
