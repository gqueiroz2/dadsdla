<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;

class subRankings extends rank{
    
    public function getNewSubValues($con, $tableName, $leftName, $type, $brands, $region, $value, $year, $months, $currency, $y, $filterValue, $auxName, $secondaryFilter=false, $baseFilter){
        if ($type == "agencyGroup") {
            $filter = "agency_group_id";
        }elseif($type == "client"){
            $filter = "agency_id";
        }else{
            $filter = "agency_id";
        }

        $brands_id = array();
        $brands_idD = array();

        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b][0] == '9') {
                array_push($brands_idD, '9');
                array_push($brands_idD, '13');
                array_push($brands_idD, '14');
                array_push($brands_idD, '15');
                array_push($brands_idD, '16');
            }elseif ($brands[$b][0] == '10') {
                array_push($brands_idD, '10');
            }else{
                array_push($brands_id,$brands[$b][0]);
            }
        }

        $sql = new sql();

        $p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }else{
            $pRate = 1.0;
        }

        if ($currency[0]['name'] == "USD") {
            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }else{
            $pRateDigital = 1.0;
        }

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        $a = new agency();

        if ($type == "agencyGroup") {
            $oldAgency = $baseFilter;//$a->getAgencyGroupID($con, $sql, $filterValue, $region);
            $aux = "client";
        }elseif($type == "client"){
            $oldAgency = $a->getAllAgenciesByClient($con, $sql, $filterValue ,$region);
            $aux = "agency";
        }else{
            $oldAgency = $a->getAllAgenciesByName($con, $sql, $filterValue, $auxName);
            $aux = "client";
        }

        if (is_array($oldAgency)) {
            for ($a=0; $a < sizeof($oldAgency); $a++) { 
                $agency[$a] = $oldAgency[$a]['id'];
            }    
        }else{
            $agency = $oldAgency;
        }

        $valueD = $value."_revenue";
        $columnsD = array("region_id","brand_id","month","year",$filter);
        $colsValueD = array($region,$brands_idD,$months,$year,$agency);

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", $filter, "brand_id", "month", "year");
            $colsValue = array($region, $agency, $brands_id, $months, $year);
        }else{
            $columns = array($filter,"brand_id", "month", "year");
            $colsValue = array($agency,$brands_id, $months, $year);
        }

        if ($secondaryFilter) {
            array_push($columns, $aux."_id");
            array_push($colsValue, $secondaryFilter);
            array_push($columnsD, $aux."_id");
            array_push($colsValueD, $secondaryFilter);
        }

        $table = "$tableName $tableAbv";
        if($type == "client"){
            $leftName = "agency";
            array_push($columns, $type."_id");
            array_push($columnsD, $type."_id");

            $c = new client();
            $val = $c->getClientIDByRegion($con,$sql,$filterValue,array($region));
            array_push($colsValue, $val);
            array_push($colsValueD, $val);
        }

        $tmp = $tableAbv.".".$leftName."_id AS '".$leftName."ID', ".$leftAbv.".name AS '".$leftName."', SUM($value) AS $as";

        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$leftName."_id";

        if($type == "agencyGroup")
            $join .= " LEFT JOIN agency i ON i.ID = ".$tableAbv.".agency_id LEFT JOIN agency_group j ON j.ID = i.agency_group_id";

        $name = $leftName."_id";
        $names = array($leftName."ID", $leftName, $as);

        $where = $sql->where($columns, $colsValue);
        
        $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
        $from = $names;

        $res = $sql->fetch($values[$y], $from, $from);


        
        if (is_array($res)) {
            for ($r=0; $r < sizeof($res); $r++) { 
                $res[$r]['total'] /= $pRate;
            }
        }
                
        return $res;
    }

    public function getSubValues($con, $tableName, $leftName, $type, $brands, $region, $value, $year, $months, $currency, $y, $filterValue, $auxName, $secondaryFilter=false){
        /*
            $lefName = LEFT JOIN
            $type no caso de overviw eh a ROOT
            $filterValue = nome da agencia
        */

        if ($type == "agencyGroup") {
            $filter = "agency_group_id";
        }elseif($type == "client"){
            $filter = "agency_id";
        }else{
            $filter = "agency_id";
        }

        $brands_id = array();
        $brands_idD = array();

        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b][0] == '9') {
                array_push($brands_idD, '9');
                array_push($brands_idD, '13');
                array_push($brands_idD, '14');
                array_push($brands_idD, '15');
                array_push($brands_idD, '16');
            }elseif ($brands[$b][0] == '10') {
                array_push($brands_idD, '10');
            }else{
                array_push($brands_id,$brands[$b][0]);
            }
        }

        $sql = new sql();

        $p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }

        if ($currency[0]['name'] == "USD") {
            $pRateDigital = 1.0;
        }else{
            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        $a = new agency();

        if ($type == "agencyGroup") {
            $oldAgency = $a->getAgencyGroupID($con, $sql, $filterValue, $region);
            $aux = "agency";
        }elseif($type == "client"){
            $oldAgency = $a->getAllAgenciesByClient($con, $sql, $filterValue ,$region);
            $aux = "agency";
        }else{
            $oldAgency = $a->getAllAgenciesByName($con, $sql, $filterValue, $auxName);
            $aux = "client";
        }

        if (is_array($oldAgency)) {
            for ($a=0; $a < sizeof($oldAgency); $a++) { 
                $agency[$a] = $oldAgency[$a]['id'];
            }    
        }else{
            $agency = $oldAgency;
        }

        $valueD = $value."_revenue";
        $columnsD = array("region_id","brand_id","month","year",$filter);
        $colsValueD = array($region,$brands_idD,$months,$year,$agency);

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month", "year", $filter);
            $colsValue = array($region, $brands_id, $months, $year, $agency);
        }else{
            $columns = array("brand_id", "month", "year", $filter);
            $colsValue = array($brands_id, $months, $year, $agency);
        }

        if ($secondaryFilter) {
            array_push($columns, $aux."_id");
            array_push($colsValue, $secondaryFilter);
            array_push($columnsD, $aux."_id");
            array_push($colsValueD, $secondaryFilter);
        }

        $table = "$tableName $tableAbv";
        if($type == "client"){
            $leftName = "agency";
            array_push($columns, $type."_id");
            array_push($columnsD, $type."_id");

            $c = new client();
            $val = $c->getClientIDByRegion($con,$sql,$filterValue,array($region));
            array_push($colsValue, $val);
            array_push($colsValueD, $val);
        }

        $tmp = $tableAbv.".".$leftName."_id AS '".$leftName."ID', ".$leftAbv.".name AS '".$leftName."', SUM($value) AS $as";
        
        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$leftName."_id";

        $tmpD = $leftName."_id AS '".$leftName."ID', ".$leftAbv.".name AS '".$leftName."', SUM($valueD) AS $as";

        $joinD = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$leftName."_id";

        $name = $leftName."_id";
        $names = array($leftName."ID", $leftName, $as);

        $where = $sql->where($columns, $colsValue);
        $whereD = $sql->where($columnsD, $colsValueD);
        
        $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
        $valuesD[$y] = $sql->selectGroupBy($con, $tmpD, "fw_digital", $joinD, $whereD, "total", $name, "DESC");

        $from = $names;

        $res = $sql->fetch($values[$y], $from, $from);
        $resD = $sql->fetch($valuesD[$y], $from, $from);
       
        if (is_array($res)) {
            for ($r=0; $r < sizeof($res); $r++) { 
                $res[$r]['total'] *= $pRate;
            }
        }

        if ($resD && $res) {
            
            for ($r=0; $r < sizeof($resD); $r++) { 
                $resD[$r]['total'] *= $pRateDigital;
            }

            $size1 = sizeof($resD);
            for ($r=0; $r < $size1; $r++) { 
                for ($r2=0; $r2 < sizeof($res); $r2++) {
                    if ($resD[$r][$leftName.'ID'] == $res[$r2][$leftName.'ID']) {
                        $res[$r2]['total'] += $resD[$r]['total'];

                        unset($resD[$r]);

                        break;
                    }
                }
            }

            $resD = array_values($resD);

            for ($r=0; $r < sizeof($resD) ; $r++) { 
                array_push($res, $resD[$r]);
            }

            usort($res, array($this,'compare'));

        }elseif ($resD) {
            for ($r=0; $r < sizeof($resD); $r++) { 
                $resD[$r]['total'] *= $pRateDigital;
            }
            $res = $resD;

        }

        return $res;
    }

    public function compare($object1,$object2){
        return $object1['total'] < $object2['total'];
    }

    public function getNewSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $filter, $auxName, $secondaryFilter=false,$baseFilter){
        if ($type == "agencyGroup") {
            $name = "client";
        }elseif ($type == "client") {
            $name = "agency";
        }else{
            $name = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) {
            if ($secondaryFilter) {
                $res[$y] = $this->getNewSubValues($con, "cmaps", $name, $type, $brands, $region, $value, $years[$y], $months, $currency, $y, $filter, $auxName, $secondaryFilter);    
            }else{
                $res[$y] = $this->getNewSubValues($con, "cmaps", $name, $type, $brands, $region, $value, $years[$y], $months, $currency, $y, $filter, $auxName , false, $baseFilter);
            }
        }

        return $res;
    }

    public function getSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $filter, $auxName, $secondaryFilter=false){
        if ($type == "agencyGroup") {
            $name = "agency";
        }elseif ($type == "client") {
            $name = "agency";
        }else{
            $name = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) {
            if ($secondaryFilter) {
                $res[$y] = $this->getSubValues($con, "ytd", $name, $type, $brands, $region, $value, $years[$y], $months, $currency, $y, $filter, $auxName, $secondaryFilter);    
            }else{
                $res[$y] = $this->getSubValues($con, "ytd", $name, $type, $brands, $region, $value, $years[$y], $months, $currency, $y, $filter, $auxName);
            }
        }

        return $res;
    }

    public function checkYearValue($years, $year){
        
        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;       
            }
        }

        return $p;
    }

    public function checkOtherYearsPosition($name, $values, $year, $years, $type){
        
        $p = $this->checkYearValue($years, $year);

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
                if ($values[$p][$v][$type."ID"] == $name[$type."ID"]) {
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
        
        $p = $this->checkYearValue($years, $year);

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
            
                if ($values[$p][$v][$type."ID"] == $name[$type."ID"]) {
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
            }elseif ($mtx[$m-sizeof($years)-1][$p] == "-") {
                $res = 0.0;
            }else{
                $res = ($mtx[$m-sizeof($years)-1][$p] / $mtx[$m-sizeof($years)][$p])*100;
            }
        }elseif ($mtx[$m][0] == "Agency group") {
            $res = $mtx[$m][$p]['agencyGroup'];
        }else{
            $res = addslashes($name[$type]);
        }

        return $res;
    }

    public function assembler($sub, $years, $type){

        if ($type == "agencyGroup" || $type == "client") {
            $var = "Agency";
            $type2 = "agency";
        }else{
            $var = "Client";
            $type2 = "client";
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        $v = 0;

        if ($type == "client") {
            $mtx[$last][0] = "Agency Group";
            $last++;
            $v = 1;
        }

        $mtx[$last][0] = $var;

        for ($l=0; $l < sizeof($years); $l++) { 
            
            $mtx[(sizeof($years)+$l+1+$v)][0] = "Rev. ".$years[$l];
        }
        
        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+1+$v;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }
        
        $values = array();

        for ($y=0; $y < sizeof($sub); $y++) { 
            if (is_array($sub[$y])) {
                for ($n=0; $n < sizeof($sub[$y]); $n++) { 
                    if ($this->existInArray($values, $sub[$y][$n][$type2."ID"], $type2, true)) {
                        array_push($values, $sub[$y][$n]);
                    }
                }
            }
        }
        
        for ($v=0; $v < sizeof($values); $v++) { 
            for ($m=0; $m < sizeof($mtx); $m++) {
                array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[$v], $sub, $years, $type2, sizeof($mtx[$m]), $type));
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

        $total = $this->assemblerTotal($mtx, $years,sizeof($mtx[0]));

        return array($mtx, $total);
    }

    public function renderSubRankings($mtx, $total, $type, $size){
        
        echo "<div class='container-fluid'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 2px solid black;'>";
                    
                        $this->renderAssembler($mtx, $total, $type, $size);

                   echo "</table>";
               echo "</div>";
           echo "</div>";
       echo "</div>";
    }
}

