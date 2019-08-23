<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\dataBase;

class rank extends Model{

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

    public function getAllValues($con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by=null, $leftName2=null){
        

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();

        $p = new pRate();

        var_dump($value);

        if ($tableName == "cmaps") {
            if ($currency[0]['name'] == "USD") {
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
            }else{
                $pRate = 1.0;
            }
        }else{
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
            }
        }

        if ($currency[0]['name'] == "USD") {
            $pRateDigital = 1.0;
        }else{
            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
        }

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        $valueDigital = $value."_revenue";
        $columnsDigital = array("f.region_id","brand_id", "month", "year");
        $colsValueDigital = array($region, $brands_id, $months);

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month", "year");
            $colsValue = array($region, $brands_id, $months);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue","brand_id", "month", "year");
            $colsValue = array($region, $value, $brands_id, $months);
            $value = "revenue";
        }else{
            $columns = array("brand_id", "month", "year");
            $colsValue = array($brands_id, $months);
        }


        if ($type == "agencyGroup") {
            $leftAbv2 = "c";
            $leftAbv3 = "d";

            if ($tableName == "ytd") {
                $columns = array("$leftAbv3.ID", "sales_representant_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $region, $brands_id, $months);
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }

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
                        if ($tableName == "cmaps") {
                            $res[$y][$r]['total'] /= $pRate;
                        }else{
                            $res[$y][$r]['total'] *= $pRate;
                        }
                    }
                }

                if(is_array($resD[$y])){
                    for ($r=0; $r <sizeof($resD[$y]) ; $r++) { 
                        $resD[$y][$r]['total'] *= $pRateDigital;
                    }
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
                    
                    $tmpD = $leftAbv."ID AS '".$type."ID', ".$leftAbv.".name AS '".$type."', ".$leftAbv2.".name AS 'agencyGroup', SUM($valueDigital) AS $as";

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
                        if ($tableName == "cmaps") {
                            $res[$y][$r]['total'] /= $pRate;
                        }else{
                            $res[$y][$r]['total'] *= $pRate;
                        }
                    }
                }


                if($tmpD && is_array($resD[$y])){
                    for ($r=0; $r < sizeof($resD[$y]); $r++) { 
                        $resD[$y][$r]['total'] *= $pRateDigital;
                    }
                    var_dump($resD[$y]);
                }



                /*if ($tmp2) {
                    for ($r=0; $r <sizeof($res[$y]) ; $r++) { 


                    }
                }*/




            }
        }

        return $res;

    }

    public function getAllValuesUnion($tableName, $leftName, $type, $brands, $region, $value, $months, $currency, $filter=false){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $tableAbv = "a";
        $leftAbv = "b";

        $as = "total";

        if ($filter) {

            $c = new client();
            $sql = new sql();

            $db = new dataBase();   
            $con = $db->openConnection("DLA");

            $client = $c->getClientIDByRegion($con, $sql, $filter, array($region));

            if ($tableName == "ytd") {
                $value .= "_revenue_prate";
                $columns = array("sales_representant_office_id", "brand_id", "month", "client_id", "year");
                $colsValue = array($region, $brands_id, $months, $client);
            }elseif ($tableName == "digital") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id","brand_id", "month", "client_id", "year");
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
        //var_dump($rtr['colsValue']);
        return $rtr;

    }

    public function searchValue($name, $values, $type){

        for ($v=0; $v < sizeof($values); $v++) {
            $something = $type."ID";
            if ($name->id == $values[$v][$something]) {
                return 1;
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
                                echo "<td id='".$type.$m."' class='$color center'> ".$mtx[$i][$m]." </td>";
                            }elseif ($type == "agencyGroup" && $mtx[$i][0] == "Agency Group") {
                                echo "<td id='".$type.$m."' class='$color center'> ".$mtx[$i][$m]." </td>";
                            }elseif ($type == "client" && $mtx[$i][0] == "Client") {
                                echo "<td id='".$type.$m."' class='$color center'> ".$mtx[$i][$m]." </td>";
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
                        }else{
                            echo "<td class='$color center'> ".number_format($mtx[$i][$m])." </td>";    
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
                echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>"; 
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
}
