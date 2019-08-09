<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;

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

    public function getAllValues($con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by=null, $leftName2=null){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();

        $p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
        }

        $as = "total";

        if ($type == "agencyGroup") {
            $tableAbv = "a";
            $leftAbv = "b";
            $leftAbv2 = "c";
            $leftAbv3 = "d";

            if ($tableName == "ytd") {
                $value .= "_revenue_prate";
                $columns = array("$leftAbv3.ID", "sales_representant_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $region, $brands_id, $months);
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }

            $table = "$tableName $tableAbv";

            $tmp = $leftAbv.".ID AS '".$type."ID', ".
                   $leftAbv.".name AS '".$type."', SUM($value) AS $as";

           $join = "LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2."."."ID = ".     $tableAbv.".".$leftName2."_id
                    LEFT JOIN ".substr($leftName, 0, 6)."_group ".$leftAbv." ON ".$leftAbv.".ID = ".$leftAbv2.".".substr($leftName, 0, 6)."_group_id
                    LEFT JOIN region $leftAbv3 ON $leftAbv3.ID = $leftAbv.region_id";

            $name = substr($type, 0, 6)."_group_id";
            $names = array($type."ID", $type, $as);

            for ($y=0; $y < sizeof($years); $y++) {

                array_push($colsValue, $years[$y]);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, $order_by);
                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);
                
                if(is_array($res[$y])){
                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        $res[$y][$r]['total'] *= $pRate;
                    }
                }
            }

        }else{
            $tableAbv = "a";
            $leftAbv = "b";

            $as = "total";

            if ($tableName == "ytd") {
                $value .= "_revenue_prate";
                $columns = array("sales_representant_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
            }elseif ($tableName == "digital") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id","brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
            }elseif ($tableName == "plan_by_brand") {
                $columns = array("sales_office_id","type_of_revenue","brand_id", "month", "year");
                $colsValue = array($region, $value, $brands_id, $months);
                $value = "revenue";
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }

            $table = "$tableName $tableAbv";

            $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".
                   $leftAbv."."."name AS '".$type."', SUM($value) AS $as";

            $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";       

            $name = $type."_id";
            $names = array($type."ID", $type, $as);

            for ($y=0; $y < sizeof($years); $y++) {

                array_push($colsValue, $years[$y]);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);

                if(is_array($res[$y])){
                    for ($r=0; $r < sizeof($res[$y]); $r++) { 
                        $res[$y][$r]['total'] *= $pRate;
                    }
                }
            }
        }

        return $res;

    }

    public function getAllValuesUnion($tableName, $leftName, $type, $brands, $region, $value, $months, $currency){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $tableAbv = "a";
        $leftAbv = "b";

        $as = "total";

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month", "year");
            $colsValue = array($region, $brands_id, $months);
        }elseif ($tableName == "digital") {
            $value .= "_revenue";
            $columns = array("campaign_sales_office_id","brand_id", "month", "year");
            $colsValue = array($region, $brands_id, $months);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue","brand_id", "month", "year");
            $colsValue = array($region, $value, $brands_id, $months);
            $value = "revenue";
        }else{
            $columns = array("brand_id", "month", "year");
            $colsValue = array($brands_id, $months);
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
        //var_dump(is_array($rtr['colsValue']));
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
        //var_dump($type2);
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
            
            if ($type != "client") {
                echo "<tr class='$color'>";
                    echo "<td id='sub".$type.$m."' style='display: none' colspan='".sizeof($mtx)."'></td>"; 
                echo "</tr>";
            }
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
}
