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

    public function getAllResults($con, $brands, $type, $region, $value, $currency, $months, $years, &$type2){

        if ($type == "agencyGroup") {
            $res = $this->getAllValues($con, "ytd", $type, $type, $brands, $region, $value, $years, $months, $currency, $type2, "DESC", "agency");            
        }else{
            $res = $this->getAllValues($con, "ytd", $type, $type, $brands, $region, $value, $years, $months, $currency, $type2, "DESC");    
        }
        
        /*for ($r=0; $r < sizeof($res[0]); $r++) { 
            var_dump($res[0][$r]);
        }*/
        
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
                if ($values[$p][$v][$type."ID"] == $name) {
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
                if ($values[$p][$v][$type."ID"] == $name) {
                    $rtr = $values[$p][$v]['total'];
                    $ok = 1;
                }
            }
        }else{
            $rtr = false;
        }

        if ($ok == 0) {
            $rtr = "-";
        }

        return $rtr;
    }

    public function checkColumn($mtx, $m, $type2, $t, $values, $years, $type, $p){
        //var_dump($type2[$t]);
        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($type2[$t]->id, $values, $var, $years, $type);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($type2[$t]->id, $values, $var, $years, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            if ($mtx[$m-sizeof($years)][$p] == "-" && $mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = "-";
            }elseif ($mtx[$m-sizeof($years)][$p] == "-") {
                $res = ($mtx[$m-sizeof($years)+1][$p]*-1);
            }elseif ($mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = $mtx[$m-sizeof($years)][$p];
            }else{
                $res = $mtx[$m-sizeof($years)][$p] - $mtx[$m-sizeof($years)+1][$p];
            }
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[$m-sizeof($years)][$p] == 0 || $mtx[$m-sizeof($years)][$p] == "-") {
                $res = 0.0;
            }else{
                $res = ($mtx[$m-sizeof($years)-1][$p] / $mtx[$m-sizeof($years)][$p])*100;
            }
        }elseif ($type == "agency" || $type == "agencyGroup") {
            if ($mtx[$m][0] == "Agency Group") {
                $res = $type2[$t]->agencyGroup;
            }else{
                $res = $type2[$t]->name;
            }
        }else{
            $res = $type2[$t]->name;
        }    
        

        return $res;

    }

    public function assembler($values, $type2, $years, $type, $filterValues){

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agencyGroup";
        }else{
            if ($type == "client") {
                $var = "Client";
                $aux = $type;    
            }else{
                $var = "Agency";
                $aux = $type;
            }
            
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = "Agency Group";

        if ($type == "agency") {
            $option = 2;
            $mtx[$last+1][0] = ucfirst($var);
        }else{
            $option = 1;
        }
        
        if ($type == "client") {
            $mtx[$last][0] = ucfirst($var);
        }

        for ($l=0; $l < sizeof($years); $l++) { 
            $mtx[(sizeof($years)+$l+$option)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+$option;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        for ($t=0; $t < sizeof($type2); $t++) { 
            if ($filterValues[$type2[$t]->id] == 1) {
                for ($m=0; $m < sizeof($mtx); $m++) {
                    array_push($mtx[$m], $this->checkColumn($mtx, $m, $type2, $t, $values, $years, $aux, sizeof($mtx[$m])));
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

        //var_dump($mtx);

        return $mtx;
    }

    public function createNames2($type, $months, $years){
        
        if ($type == "agencyGroup") {
            $res['name'] = "Agency group";
        }else{
            $res['name'] = ucfirst($type);
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
