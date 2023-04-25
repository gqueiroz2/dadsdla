<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\dataBase;

class rank extends Model{

    public function existInArray($array, $value, $type, $id=false){
        
        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }

        for ($a=0; $a < sizeof($array); $a++) { 
            
            if ($array[$a][$type.$id] == $value) {
                return false;
            }
        }

        return true;
    }

    public function existInSubArray($array, $value){

        for ($a=0; $a < sizeof($array); $a++) { 
            
            if ($array[$a]->id == $value->id) {
                return false;
            }
        }

        return true;
    }

    public function createPositions($first, $second, $third){
        
        if ($second == 0 && $third == 0) {
            $years = array($first);
        }elseif ($second == 0) {$total = $this->assemblerTotal($mtx, $years);
            $years = array($first, $third);$total = $this->assemblerTotal($mtx, $years);
        }elseif ($third == 0) {
            $years = array($first, $second);
        }else{
            $years = array($first, $second, $third);
        }

        return $years;
    }

    public function mountBrands($brands){

        $brandsTV = array();
        $brandsDigital = array();

        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b][1] == "DC" || $brands[$b][1] == "HH" || $brands[$b][1] == "DK" || $brands[$b][1] == "AP" 
                || $brands[$b][1] == "TLC"|| $brands[$b][1] == "ID" || $brands[$b][1] == "DT" || $brands[$b][1] == "FN" 
                || $brands[$b][1] == "OTH" || $brands[$b][1] == "HGTV" || $brands[$b][1] == "DN") {
                array_push($brandsTV, $brands[$b]);
            }else{
                array_push($brandsDigital, $brands[$b]);
            }
        }

        return array($brandsTV, $brandsDigital);
    }

    public function getAllNewValues($con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $type2, $order_by=null, $leftName2=null, $secondaryFilter=false ,$baseFilter){

        $ag = new agency();

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        if($type == "agencyGroup"){ 

            $temp = $ag->getAgencyByAgencyGroupID($con,$baseFilter);
            for ($t=0; $t < sizeof($temp); $t++) { 
                $base_filter_id[$t] = $temp[$t]['agencyID'];        
            }
            
        }else{
            $base_filter_id[0] = $baseFilter->id;    
        }

        $check = false;

        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b][1] == 'ONL') {
                $check = true;
            }
        }

        if ($check) {
            array_push($brands_id, '13');
            array_push($brands_id, '14');
            array_push($brands_id, '15');
            array_push($brands_id, '16');
        }

        $sql = new sql();

        $p = new pRate();

        for ($y=0; $y < sizeof($years); $y++) { 
            if($tableName == "cmaps" || $tableName == "wbd"){
                if ($currency[0]['name'] == "USD"){
                    $pRate[$y] = $p->getPRateByRegionAndYear($con, array($region), array($years[$y]));
                }else{
                    $pRate[$y] = 1.0;
                }
            }else{
                if ($currency[0]['name'] == "USD"){
                    $pRate[$y] = 1.0;
                }else{
                    $pRate[$y] = $p->getPRateByRegionAndYear($con, array($region), array($years[$y]));
                }
            }

            if ($currency[0]['name'] == "USD") {
                $pRateDigital[$y] = 1.0;
            }else{
                $pRateDigital[$y] = $p->getPRateByRegionAndYear($con, array($region), array($years[$y]));
            }

        }

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($type == "agency") {
            $aux = "client";
        }elseif ($type == "client") {
            $aux = "agency";
        }else{
            $aux = "agency";
        }

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month");
            $colsValue = array($region, $brands_id, $months);
        }elseif ($tableName == "wbd") {
            $value .= "_value";
            $columns = array("brand_id", "month");
            $colsValue = array($brands_id, $months);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue","brand_id", "month");
            $colsValue = array($region, $value, $brands_id, $months);
            $value = "revenue";
        }else{
            $columns = array("brand_id", "month");
            $colsValue = array($brands_id, $months);
        }

        if ($secondaryFilter) {
            array_push($columns, $aux."_id");
            array_push($colsValue, $secondaryFilter);
        }

        array_push($columns, "year");
        array_push($columns, "agency_id");

        if ($type == "agencyGroup") {
            $leftAbv2 = "c";
            $leftAbv3 = "d";

            if ($tableName == "ytd") {
                $columns = array("$leftAbv3.ID", "sales_representant_office_id", "brand_id", "month");
                $colsValue = array($region, $region, $brands_id, $months,);
            }else{
                $columns = array("brand_id", "month");
                $colsValue = array($brands_id, $months);
            }

            if ($secondaryFilter) {
                array_push($columns, $aux."_id");
                array_push($colsValue, $secondaryFilter);
            }

            array_push($columns, "year");
            array_push($columns, "agency_id");

            $table = "$tableName $tableAbv";
            $tmp = $leftAbv.".ID AS '".$type."ID', ".
                   $leftAbv.".name AS '".$type."', SUM($value) AS $as";
            $join = "LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2."."."ID = ".$tableAbv.".".$leftName2."_id
                    LEFT JOIN ".substr($leftName, 0, 6)."_group ".$leftAbv." ON ".$leftAbv.".ID = ".$leftAbv2.".".substr($leftName, 0, 6)."_group_id
                    LEFT JOIN region $leftAbv3 ON $leftAbv3.ID = $leftAbv.region_id";
            $name = substr($type, 0, 6)."_group_id";
            $names = array($type."ID", $type, $as);
            for ($y=0; $y < sizeof($years); $y++) {
                array_push($colsValue, $years[$y]);
                array_push($colsValue, $base_filter_id);

                $where = $sql->where($columns, $colsValue);

                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, $order_by);
                array_pop($colsValue);
                $from = $names;
                $res[$y] = $sql->fetch($values[$y], $from, $from);
                if(is_array($res[$y])){
                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        if ($tableName == "cmaps" || $tableName == "wbd") {
                            $res[$y][$r]['total'] /= $pRate[$y];
                        }else{
                            $res[$y][$r]['total'] *= $pRate[$y];
                        }
                    }
                }
            }
        }else{
            $table = "$tableName $tableAbv";
            if ($type == "sector" || $type == "category") {
                $tmp = $tableAbv.".".$type." AS '".$type."', SUM($value) AS $as";
                $tmpD = false;
                $join = null;
                $name = $type;
                $names = array($type, $as);
            }else{
                $name = $type."_id";
                if ($type == "agency") {
                    $leftName2 = "agency_group";
                    $leftAbv2 = "c";
                    $tmp = $leftAbv.".ID AS '".$type."ID', ".$leftAbv.".name AS '".$type."', ".$leftAbv2.".name AS 'agencyGroup', SUM($value) AS $as";
                    $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$type."_id
                            LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2.".ID = ".$leftAbv.".".$leftName2."_id";
                    $names = array($type."ID", $type, "agencyGroup", $as);
                }else{
                    $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".$leftAbv."."."name AS '".$type."', SUM($value) AS $as";
                    $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id"; 
                    $names = array($type."ID", $type, $as);
                }
            }

            for ($y=0; $y < sizeof($years); $y++) {
                array_push($colsValue, $years[$y]);
                array_push($colsValue, $base_filter_id);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
                array_pop($colsValue);
                $from = $names;
                $res[$y] = $sql->fetch($values[$y], $from, $from);

                if(is_array($res[$y])){

                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        if ($tableName == "cmaps") {
                            $res[$y][$r]['total'] /= $pRate[$y];
                        }else{
                            $temp = $res[$y][$r]['total'];
                            $res[$y][$r]['total'] *= $pRate[$y];
                        }
                    }
                }
            }
        }

        return $res;

    }

    public function getAllValues($con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $type2, $order_by=null, $leftName2=null, $secondaryFilter=false){

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $check = false;

        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b][1] == 'ONL') {
                $check = true;
            }
        }

        if ($check) {
            array_push($brands_id, '13');
            array_push($brands_id, '14');
            array_push($brands_id, '15');
            array_push($brands_id, '16');
        }

        $sql = new sql();

        $p = new pRate();

        for ($y=0; $y < sizeof($years); $y++) { 
            if($tableName == "cmaps" || $tableName == "wbd"){
                if ($currency[0]['name'] == "USD"){
                    $pRate[$y] = $p->getPRateByRegionAndYear($con, array($region), array($years[$y]));
                }else{
                    $pRate[$y] = 1.0;
                }
            }else{
                if ($currency[0]['name'] == "USD"){
                    $pRate[$y] = 1.0;
                }else{
                    $pRate[$y] = $p->getPRateByRegionAndYearIBMS($con, array($region), array($years[$y]));
                }
            }

            if ($currency[0]['name'] == "USD") {
                $pRateDigital[$y] = 1.0;
            }else{
                $pRateDigital[$y] = $p->getPRateByRegionAndYearIBMS($con, array($region), array($years[$y]));
            }

        }

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($type == "agency") {
            $aux = "client";
        }elseif ($type == "client") {
            $aux = "agency";
        }else{
            $aux = "agency";
        }

        $valueDigital = $value."_revenue";
        $columnsDigital = array("f.region_id","brand_id", "month");
        $colsValueDigital = array($region, $brands_id, $months);

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month");
            $colsValue = array($region, $brands_id, $months);
        }elseif ($tableName == "wbd") {
            $value .= "_value";
            $columns = array("brand_id", "month");
            $colsValue = array($brands_id, $months);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue","brand_id", "month");
            $colsValue = array($region, $value, $brands_id, $months);
            $value = "revenue";
        }else{
            $columns = array("brand_id", "month");
            $colsValue = array($brands_id, $months);
        }

        if ($secondaryFilter) {
            array_push($columns, $aux."_id");
            array_push($colsValue, $secondaryFilter);
            array_push($columnsDigital, $aux."_id");
            array_push($colsValueDigital, $secondaryFilter);
        }

        array_push($columns, "year");
        array_push($columnsDigital, "year");
        
        if ($type == "agencyGroup") {
            $leftAbv2 = "c";
            $leftAbv3 = "d";

            if ($tableName == "ytd") {
                $columns = array("$leftAbv3.ID", "sales_representant_office_id", "brand_id", "month");
                $colsValue = array($region, $region, $brands_id, $months);
            }else{
                $columns = array("brand_id", "month");
                $colsValue = array($brands_id, $months);
            }

            if ($secondaryFilter) {
                array_push($columns, $aux."_id");
                array_push($colsValue, $secondaryFilter);
            }

            array_push($columns, "year");

            $table = "$tableName $tableAbv";

            $tableDigital = "fw_digital f";

            $tmp = $leftAbv.".ID AS '".$type."ID', ".
                   $leftAbv.".name AS '".$type."', SUM($value) AS $as";

            $tmpD = $leftAbv.".ID AS '".$type."ID', ".
                   $leftAbv.".name AS '".$type."', SUM($valueDigital) AS $as";

            $join = "LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2."."."ID = ".$tableAbv.".".$leftName2."_id
                    LEFT JOIN ".substr($leftName, 0, 6)."_group ".$leftAbv." ON ".$leftAbv.".ID = ".$leftAbv2.".".substr($leftName, 0, 6)."_group_id
                    LEFT JOIN region $leftAbv3 ON $leftAbv3.ID = $leftAbv.region_id";

            $joinD = "LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2."."."ID = f.".$leftName2."_id
                    LEFT JOIN ".substr($leftName, 0, 6)."_group ".$leftAbv." ON ".$leftAbv.".ID = ".$leftAbv2.".".substr($leftName, 0, 6)."_group_id
                    LEFT JOIN region $leftAbv3 ON $leftAbv3.ID = $leftAbv.region_id";

            $name = substr($type, 0, 6)."_group_id";
            $names = array($type."ID", $type, $as);

            for ($y=0; $y < sizeof($years); $y++) {
                
                array_push($colsValue, $years[$y]);
                array_push($colsValueDigital, $years[$y]);

                $where = $sql->where($columns, $colsValue);
                $whereD = $sql->where($columnsDigital, $colsValueDigital);
                
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, $order_by);
                $valuesD[$y] = $sql->selectGroupBy($con, $tmpD, $tableDigital, $joinD, $whereD, "total", $name, $order_by);
                array_pop($colsValue);
                array_pop($colsValueDigital);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);
                $resD[$y] = $sql->fetch($valuesD[$y], $from, $from);

                if(is_array($res[$y])){
                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        if ($tableName == "cmaps" || $tableName == "wbd") {
                            $res[$y][$r]['total'] /= $pRate[$y];
                        }else{
                            $res[$y][$r]['total'] *= $pRate[$y];
                        }
                    }
                }
                
                if(is_array($res[$y]) && is_array($resD[$y])){
                    for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                        $resD[$y][$r]['total'] *= $pRateDigital[$y];
                    }

                    if ($res[$y]) {
                        $size1 = sizeof($resD[$y]);
                        $size2 = sizeof($res[$y]);

                        for ($r=0; $r < $size1; $r++) { 
                            for ($r2=0; $r2 < $size2; $r2++) {
                                if ($resD[$y][$r][$type."ID"] == $res[$y][$r2][$type."ID"]) {
                                    $res[$y][$r2]['total'] += $resD[$y][$r]['total'];

                                    unset($resD[$y][$r]);
                                    break;
                                }
                            }
                        }
                        
                        $resD[$y] = array_values($resD[$y]);
                        if (!is_null($type2)) {
                            for ($r=0; $r < sizeof($resD[$y]); $r++) {
                                
                                $obj = (object) [
                                    'id' => $resD[$y][$r][$type."ID"],
                                    'name' => $resD[$y][$r][$type],
                                    'agencyGroup' => $resD[$y][$r]['agencyGroup'],
                                ];
                                
                                if ($this->existInSubArray($type2, $obj)) {
                                    array_push($type2, $obj);   
                                }

                                array_push($res[$y], $resD[$y][$r]);
                            }
                        }else{
                            for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                                array_push($res[$y], $resD[$y][$r]);
                            }
                        }

                        usort($res[$y], array($this,'compare'));
                    }
                }elseif(is_array($resD[$y])){
                    for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                        $resD[$y][$r]['total'] *= $pRateDigital[$y];

                        if (!is_null($type2)) {
                            $obj = (object) [
                                'id' => $resD[$y][$r][$type."ID"],
                                'name' => $resD[$y][$r][$type],
                                'agencyGroup' => $resD[$y][$r]['agencyGroup'],
                            ];

                            if ($this->existInSubArray($type2, $obj)) {
                                array_push($type2, $obj);   
                            }   
                        }
                    }

                    $res[$y] = $resD[$y];
                }

            }
        }else{
            $table = "$tableName $tableAbv";
            $tableDigital = "fw_digital f";

            if ($type == "sector" || $type == "category") {
                $tmp = $tableAbv.".".$type." AS '".$type."', SUM($value) AS $as";
                $tmpD = false;
                $join = null;
                $joinD = null;
                $name = $type;
                $names = array($type, $as);
            }else{
                
                $name = $type."_id";

                if ($type == "agency") {
                    $leftName2 = "agency_group";
                    $leftAbv2 = "c";

                    $tmp = $leftAbv.".ID AS '".$type."ID', ".$leftAbv.".name AS '".$type."', ".$leftAbv2.".name AS 'agencyGroup', SUM($value) AS $as";
                    
                    $tmpD = $leftAbv.".ID AS '".$type."ID', ".$leftAbv.".name AS '".$type."', ".$leftAbv2.".name AS 'agencyGroup', SUM($valueDigital) AS $as";

                    $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$type."_id
                            LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2.".ID = ".$leftAbv.".".$leftName2."_id";

                    $joinD = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$type."_id
                            LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2.".ID = ".$leftAbv.".".$leftName2."_id";

                    $names = array($type."ID", $type, "agencyGroup", $as);
                }else{
                    $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".$leftAbv."."."name AS '".$type."', SUM($value) AS $as";

                    $tmpD = $type."_id AS '".$type."ID', ".$leftAbv."."."name AS '".$type."', SUM($valueDigital) AS $as";

                    $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id"; 

                    $joinD = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$type."_id"; 

                    $names = array($type."ID", $type, $as);
                }
                
            }

            for ($y=0; $y < sizeof($years); $y++) {

                array_push($colsValue, $years[$y]);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
                
                if($tmpD){
                    array_push($colsValueDigital, $years[$y]);
                    $whereDigital = $sql->where($columnsDigital,$colsValueDigital);
                    $valuesD[$y] = $sql->selectGroupBy($con, $tmpD, $tableDigital, $joinD, $whereDigital, "total", $name, "DESC");
                    array_pop($colsValueDigital);
                }

                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);

                if ($tmpD) {
                    $resD[$y] = $sql->fetch($valuesD[$y], $from, $from);
                }

                if(is_array($res[$y])){

                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        if ($tableName == "cmaps" || $tableName == "wbd") {
                            $res[$y][$r]['total'] /= $pRate[$y];
                        }else{
                            $temp = $res[$y][$r]['total'];
                            $res[$y][$r]['total'] *= $pRate[$y];
                        }
                    }
                }

                if ($tmpD) {
                    if(is_array($res[$y]) && is_array($resD[$y])){
                        for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                            $resD[$y][$r]['total'] *= $pRateDigital[$y];
                        }

                        if ($res[$y]) {
                            $size1 = sizeof($resD[$y]);
                            $size2 = sizeof($res[$y]);

                            for ($r=0; $r < $size1; $r++) { 
                                for ($r2=0; $r2 < $size2; $r2++) {
                                    if ($resD[$y][$r][$type."ID"] == $res[$y][$r2][$type."ID"]) {
                                        $res[$y][$r2]['total'] += $resD[$y][$r]['total'];

                                        unset($resD[$y][$r]);
                                        break;
                                    }
                                }
                            }

                            $resD[$y] = array_values($resD[$y]);

                            if (!is_null($type2)) {
                                for ($r=0; $r < sizeof($resD[$y]); $r++) { 

                                    if ($type == "agency") {
                                        $obj = (object) [
                                            'id' => $resD[$y][$r][$type."ID"],
                                            'name' => $resD[$y][$r][$type],
                                            'agencyGroup' => $resD[$y][$r]['agencyGroup'],
                                        ];
                                    }else{
                                        
                                        $obj = (object) [
                                            'id' => $resD[$y][$r][$type."ID"],
                                            'name' => $resD[$y][$r][$type]
                                        ];
                                    }
                                    
                                    if ($this->existInSubArray($type2, $obj)) {
                                        array_push($type2, $obj);   
                                    }

                                    array_push($res[$y], $resD[$y][$r]);
                                }
                            }else{
                                for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                                    array_push($res[$y], $resD[$y][$r]);
                                }
                            }

                            usort($res[$y], array($this,'compare'));
                        }
                    }elseif(is_array($resD[$y])){
                        for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                            $resD[$y][$r]['total'] *= $pRateDigital;

                            if (!is_null($type2)) {
                                if ($type == "agency") {
                                    $obj = (object) [
                                        'id' => $resD[$y][$r][$type."ID"],
                                        'name' => $resD[$y][$r][$type],
                                        'agencyGroup' => $resD[$y][$r]['agencyGroup'],
                                    ];   
                                }else{
                                    $obj = (object) [
                                        'id' => $resD[$y][$r][$type."ID"],
                                        'name' => $resD[$y][$r][$type]
                                    ];
                                }

                                if ($this->existInSubArray($type2, $obj)) {
                                    array_push($type2, $obj);   
                                }   
                            }
                        }
                        $res[$y] = $resD[$y];
                    }
                }
            }
        }


        return $res;

    }


    public function getAllValuesUnion($tableName, $leftName, $type, $brands, $region, $value, $months, $currency, $filter=false){
        
        if (empty($brands)) {
            $brands_id = "";
        }else{
            for ($b=0; $b < sizeof($brands); $b++) { 
                $brands_id[$b] = $brands[$b][0];
            }
        }

        $check = false;

        for ($b=0; $b < sizeof($brands) ; $b++) { 
            if ($brands[$b][1] == 'ONL') {
                $check = true;
            }
        }

        if ($check) {
            array_push($brands_id, '13');
            array_push($brands_id, '14');
            array_push($brands_id, '15');
            array_push($brands_id, '16');
        }

        $tableAbv = "a";
        $leftAbv = "b";

        $as = "total";

        if ($filter) {

            $c = new client();
            $sql = new sql();

            $db = new dataBase();   
            $default = $db->defaultConnection();
            $con = $db->openConnection($default);

            $client = $c->getClientIDByRegion($con, $sql, addslashes($filter), array($region));
            
            if ($tableName == "ytd") {
                $value .= "_revenue_prate";
                $columns = array("sales_representant_office_id", "brand_id", "month", "client_id", "year");
                $colsValue = array($region, $brands_id, $months, $client);
            }elseif ($tableName == "wbd") {
                $value .= "_value";
                $columns = array("brand_id", "month");
                $colsValue = array($brands_id, $months);
            }elseif ($tableName == "fw_digital") {
                $value .= "_revenue";
                $columns = array("region_id","brand_id", "month", "client_id", "year");
                $colsValue = array($region, $brands_id, $months, $client);
            }else{
                $columns = array("brand_id", "month", "client_id", "year");
                $colsValue = array($brands_id, $months, $client);
            }
        }else{
            if ($tableName == "ytd") {
                $value .= "_revenue_prate";
                $columns = array("sales_representant_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
            }elseif ($tableName == "wbd") {
                $value .= "_value";
                $columns = array("brand_id", "month");
                $colsValue = array($brands_id, $months);
            }elseif ($tableName == "fw_digital") {
                $value .= "_revenue";
                $columns = array("region_id","brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
            }elseif ($tableName == "plan_by_brand") {
                $columns = array("sales_office_id","type_of_revenue","brand_id", "month", "source", "currency_id", "year");
                $colsValue = array($region, $value, $brands_id, $months, "TARGET", 4);
                $value = "revenue";
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }
        }

        $table = "$tableName $tableAbv";

        $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".
               $leftAbv."."."name AS '".$type."', SUM($value) AS $as";

        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";       

        $name = $type."_id";
        $names = array($type."ID", $type, $as);

        $rtr['value'] = $value;
        $rtr['columns'] = $columns;
        $rtr['colsValue'] = $colsValue;
        $rtr['table'] = $table;
        $rtr['query'] = $tmp;
        $rtr['join'] = $join;
        $rtr['name'] = $name;
        $rtr['names'] = $names;
        
        return $rtr;

    }

    public function searchValue($name, $values, $type){

        if (is_array($values)) {
            for ($v=0; $v < sizeof($values); $v++) {
                $something = $type."ID";
                //var_dump($something);
                if ($name->id == $values[$v][$something]) {
                    return 1;
                }
            }   
        }

        return 0;

    }

    public function filterValues($values, $type2, $type){
        
        for ($t=0; $t < sizeof($type2); $t++) {
            $res[$type2[$t]->id] = $this->searchValue($type2[$t], $values[0], $type);
        }

        return $res;
    }

    public function filterValues2($values, $type2, $type){
    
        for ($y=0; $y <sizeof($values) ; $y++) { 
            for ($t=0; $t < sizeof($type2); $t++) {
                $tmp[$y][$type2[$t]->id] = $this->searchValue($type2[$t], $values[$y], $type);
            }
        }

        for ($t=0; $t < sizeof($type2); $t++) {
            for ($i=0; $i < sizeof($tmp); $i++) { 
               if ($tmp[$i][$type2[$t]->id] == 1) {
                   $res[$type2[$t]->id] = 1;
                   break;
               }else{
                   $res[$type2[$t]->id] = 0;
               }
            }
        }


        return $res;
    }

    public function assemblerTotal($mtx, $years, $size){

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

            for ($c=0; $c < $size; $c++) { 
                
                if ($c != 0 && substr($mtx[$l][0], 0, 3) == "Rev") {
                    if ($mtx[$l][$c] == "-") {
                        $vec[$l] += 0;    
                    }else{
                        $vec[$l] += $mtx[$l][$c];
                    }
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

    public function renderAssembler($mtx, $total, $type, $size){
        
        for ($m=0; $m < $size; $m++) {
            echo "<tr>";
            
            if ($m == 0) {
              $color = "lightBlue";
            }elseif ($m%2 != 0) {
              $color = "rcBlue";
            }else{
              $color = "medBlue";
            }

            for ($i=0; $i < sizeof($mtx); $i++) {
                
                if ($m == 0) {
                    echo "<td class='$color center'> ".$mtx[$i][$m]." </td>";
                }else {
                    if (!is_numeric($mtx[$i][$m])) {
                        if ($mtx[$i][$m] != "-") {
                            if ($type == "agency" && $mtx[$i][0] == "Agency") {
                                echo "<td id='".$type.$m."' class='$color center' data-value='".$mtx[$i-1][$m]."'> ".$mtx[$i][$m]." </td>";
                            }elseif ($type == "agencyGroup" && $mtx[$i][0] == "Agency Group") {
                                echo "<td id='".$type.$m."' class='$color center' data-value='".$mtx[$i-1][$m]."'> ".$mtx[$i][$m]." </td>";
                            }elseif ($type == "client" && $mtx[$i][0] == "Client") {
                                echo "<td id='".$type.$m."' class='$color center' data-value='".$mtx[$i-1][$m]."'> ".$mtx[$i][$m]." </td>";
                            }else{
                                if ($mtx[$i][$m] == "Others") {
                                    echo "<td class='$color center'> - </td>";      
                                }else{
                                    echo "<td class='$color center'> ".$mtx[$i][$m]." </td>";
                                }
                            }
                        }else{
                          echo "<td class='$color center'> ".$mtx[$i][$m]." </td>";
                        }
                    }else{
                        if (substr($mtx[$i][0], 0, 3) == "Pos") {
                            if ($mtx[$i][$m] != '-') {
                                echo "<td class='$color center'> ".$mtx[$i][$m]."º </td>";
                            }else{
                                echo "<td class='$color center'> ".$mtx[$i][$m]." </td>";
                            }
                        }elseif ($mtx[$i][0] == "VAR %") {
                            echo "<td class='$color center'> ".number_format($mtx[$i][$m],0,',','.')." %</td>";
                        }else{
                            echo "<td class='$color center'> ".number_format($mtx[$i][$m],0,',','.')." </td>";
                        }
                        
                    }
                }
                
            }

            echo "</tr>";
            
            echo "<tr class='$color'>";
                echo "<td id='sub".$type.$m."' style='display: none' colspan='".sizeof($mtx)."'></td>"; 
            echo "</tr>";
        }

        echo "<tr>";

        for ($t=0; $t < sizeof($total); $t++) { 
            
            if (is_numeric($total[$t])) {
                if ($mtx[$t][0] == "VAR %") {
                    echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";    
                }else{
                    echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
                }
            }else{
              if ($total[$t] != "-") {
                echo "<td class='darkBlue center'> ".$total[$t]." </td>"; 
              }else{
                echo "<td class='darkBlue center'> &nbsp; </td>"; 
              }
            }

        }

        echo "</tr>";

    }

    public function TruncateRegion($region){

        if ($region == "Brazil") {
            $name = "BR";            
        }elseif ($region == "Argentina") {
            $name = "AR";
        }elseif ($region == "Colombia") {
            $name = "COL";
        }elseif ($region == "Miami") {
            $name = "MIA";
        }elseif ($region == "Mexico") {
            $name = "MEX";
        }elseif ($region == "Chile") {
            $name = "CL";
        }elseif ($region == "Peru") {
            $name = "PE";
        }elseif ($region == "Venezuela") {
            $name = "VE";
        }elseif ($region == "Panama") {
            $name = "PA";
        }elseif ($region == "New York International") {
            $name = "NY";
        }elseif ($region == "Dominican Republic") {
            $name = "DR";
        }elseif ($region == "Ecuador") {
            $name = "EC";
        }elseif ($region == "Bolivia") {
            $name = "BO";
        }elseif ($region == "Puerto Rico") {
            $name = "PR";
        }else {
            $name = false;
        }

        return $name;
    }

    public function createNames($type, $months, $region, $brands){
        
        $res['name'] = ucfirst($type);

        if ($region == "Brazil") {
            $res['source'] = "CMAPS";
        }else{
            $res['source'] = "IBMS";
        }

        $res['brands'] = "";

        for ($b=0; $b < sizeof($brands); $b++) { 
            $res['brands'] .= $brands[$b][1];

            if ($b != (sizeof($brands)-1)) {
                $res['brands'] .= " - ";                
            }
        }

        $b = new base();

        if (sizeof($months) == 12) {
            $res['months'] = "All Year";            
        }else{
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
        }

        return $res;
    }

    public function compare($object1,$object2){
        return $object1['total'] < $object2['total'];
    }
}


