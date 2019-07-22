<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;

class subRankings extends rank {
    
    public function getSubValues($con, $tableName, $leftName, $type, $brands, $region, $value, $year, $months, $currency, $y, $filterValue){
        
        if ($type == "agencyGroup") {
            $filter = "agency_group_id";
        }else{
            $filter = "agency_id";
        }

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        $a = new agency();

        if ($type == "agencyGroup") {
            $oldAgency = $a->getAgencyGroupID($con, $sql, $filterValue, $region);
        }else{
            $oldAgency = $a->getAllAgenciesByName($con, $sql, $filterValue);    
        }

        if (is_array($oldAgency)) {
            for ($a=0; $a < sizeof($oldAgency); $a++) { 
                $agency[$a] = $oldAgency[$a]['id'];
            }    
        }else{
            $agency = $oldAgency;
        }
        
        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month", "year", $filter);
            $colsValue = array($region, $brands_id, $months, $year, $agency);
        }else{
            $columns = array("brand_id", "month", "year", $filter);
            $colsValue = array($brands_id, $months, $year, $agency);
        }

        $table = "$tableName $tableAbv";

        $tmp = $tableAbv.".".$leftName."_id AS '".$leftName."ID', ".$leftAbv.".name AS '".$leftName."', SUM($value) AS $as";

        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$leftName."_id";

        $name = $leftName."_id";
        $names = array($leftName."ID", $leftName, $as);

        $where = $sql->where($columns, $colsValue);
        $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

        $from = $names;

        $res = $sql->fetch($values[$y], $from, $from);
        
        //var_dump($res);
        return $res;
    }

    public function getSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $filter){
        
        if ($type == "agencyGroup") {
            $name = "agency";
        }else{
            $name = "client";
        }

        $p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $res[$y] = $this->getSubValues($con, "ytd", $name, $type, $brands, $region, $value, $years[$y], $months, $currency, $y, $filter);

            if (is_array($res[$y])) {
                for ($r=0; $r < sizeof($res[$y]); $r++) { 
                    $res[$y][$r]['total'] *= $pRate;
                }
            }
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
            $rtr = "-";
        }

        return $rtr;
    }

    public function checkColumn($mtx, $m, $name, $sub, $years, $type, $p, $typeF){

        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($name, $sub, $var, $years, $type);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($name, $sub, $var, $years, $type);
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
        }else{
            $res = $name;
        }

        return $res;
    }

    public function assembler($sub, $years, $type){
        
        if ($type == "agencyGroup") {
            $var = "Agencies";
            $type2 = "agency";
        }else{
            $var = "Clients";
            $type2 = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = $var;
        //var_dump($sub);
        for ($l=0; $l < sizeof($years); $l++) { 
            
            $mtx[(sizeof($years)+$l+1)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+1;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        
        for ($s=0; $s < sizeof($sub[0]); $s++) { 
            for ($m=0; $m < sizeof($mtx); $m++) {
                
                array_push($mtx[$m], $this->checkColumn($mtx, $m, $sub[0][$s][$type2], $sub, $years, $type2, sizeof($mtx[$m]), $type));
                
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
        
        return array($mtx, $total);
    }

    public function renderSubRankings($mtx, $total, $type, $size){
        
        echo "<div class='container-fluid' style='margin-top: 0.5%; margin-bottom: 0.5%;'>";
            echo "<div class='row'>";
                echo "<div class='col'>";
                    echo "<center><table style='width: 90%; zoom:100%; font-size: 16px;border: 2px solid black;'>";

                        $this->renderAssembler($mtx, $total, $type, $size);

                   echo "</table></center>";
               echo "</div>";
           echo "</div>";
       echo "</div>";
    }
}
