<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingChurn extends rank {
    
	public function getAllChurnValues($con, $tableName, $leftName, $type, $brands, $region, $value, $year, $months, $currency){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "brand_id", "month", "year");
            $colsValue = array($region, $brands_id, $months, $year);
        }elseif ($tableName == "digital") {
            $value .= "_revenue";
            $columns = array("campaign_sales_office_id","brand_id", "month", "year");
            $colsValue = array($region, $brands_id, $months, $year);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue","brand_id", "month", "year");
            $colsValue = array($region, $value, $brands_id, $months, $year);
            $value = "revenue";
        }else{
            $columns = array("brand_id", "month", "year");
            $colsValue = array($brands_id, $months, $year);
        }

        $table = "$tableName $tableAbv";

        if ($type == "sector" || $type == "category") {
            $tmp = $tableAbv.".".$type." AS '".$type."', SUM($value) AS $as";
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
                $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".
                $leftAbv."."."name AS '".$type."', SUM($value) AS $as";

                $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";       

                $names = array($type."ID", $type, $as);
            }
            
        }

        $where = $sql->where($columns, $colsValue);
        
        if ($year == intval(date('Y'))) {
        	$where .= " AND ($value = 0)";
        }

        $rtr['value'] = $value;
        $rtr['columns'] = $columns;
        $rtr['colsValue'] = $colsValue;
        $rtr['table'] = $table;
        $rtr['query'] = $tmp;
        $rtr['join'] = $join;
        $rtr['name'] = $name;
        $rtr['names'] = $names;
        $rtr['where'] = $where;

        return $rtr;

    }

    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years){
    	
    	for ($y=0; $y < sizeof($years); $y++) { 
    		if ($region == "Brazil") {
	    		$info[$y] = $this->getAllChurnValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years[$y], $months, $currency);
	    	}else{
				$info[$y] = $this->getAllChurnValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years[$y], $months, $currency);    	
			}
    	}

    	$order_by = "total";

    	$sql = new sql();

    	$whereArray[0] = $info[0]['where'];
    	$whereArray[1] = $info[1]['where'];

    	$values = $sql->selectWithUnion($con, $whereArray, $info, $info[0]['name'], $order_by, $order="DESC");

    	$from = $info[0]['names'];

    	$res = $sql->fetch($values, $from, $from);
    	
    	$p = new pRate();

        if ($region == "Brazil") {
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

    	for ($r=0; $r < sizeof($res); $r++) { 
    		if ($region == "Brazil") {
                $res[$r]['total'] /= $pRate;
            }else{
                $res[$r]['total'] *= $pRate;
            }
    	}

    	return $res;
		
    }

    public function existInYear($name, $values, $type, $y){
    
    	if (is_array($values[$y])) {
    		for ($s=0; $s < sizeof($values[$y]); $s++) { 
				if ($name == $values[$y][$s][$type]) {
					return true;
				}
    		}
    	}

    	return false;

    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $v, $values2=null){
    	
    	if ($mtx[$m][0] == "Ranking") {
    		$res = ($v+1);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $values[$v]['agencyGroup'];
    	}elseif ($mtx[$m][0] == $years[0]) {
    		$res = 0;
    	}elseif ($mtx[$m][0] == $years[1]) {
    		$res = $values[$v]['total'];
    	}elseif ($mtx[$m][0] == "Var (%)") {
    		if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs.") {
    		$res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
    	}elseif ($mtx[$m][0] == "Class") {
			$res = "Churn";
    	}elseif ($mtx[$m][0] == "YTD ".$years[0]) {
    		$res = 0;
    	}elseif ($mtx[$m][0] == "YTD ".$years[1]) {
    		$res = 0;
    	}elseif ($mtx[$m][0] == "Var YTD (%)") {
    		if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs. YTD") {
    		$res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
    	}else{
    		$res = $name;
    	}

    	return $res;
    }

    public function assemblerChurnTotal($mtx, $type, $years){
    	
    	$total[0] = "Total";

        $first = 0;
        $second = 0;

        $firstYtd = 0;
        $secondYtd = 0;

        if ($type == "agency") {
    		$pos = 3;
    		$pos2 = 9;
    	}else{
    		$pos = 2;
    		$pos2 = 8;
    	}

        for ($m=1; $m < sizeof($mtx[0]); $m++) { 
            $first += $mtx[$pos][$m];
            $second += $mtx[$pos+1][$m];
            
            $firstYtd += $mtx[$pos2][$m];
            $secondYtd += $mtx[$pos2+1][$m];
        }

        for ($m=1; $m < sizeof($mtx); $m++) { 

            if ($m == $pos || $m == ($pos+1)) {
                
                if ($m == $pos) {
                    $total[$m] = $first;
                }else{
                    $total[$m] = $second;
                }
            }elseif ($m == $pos2 || $m == ($pos2+1)) {
            	if ($m == $pos2) {
                    $total[$m] = $firstYtd;
                }else{
                    $total[$m] = $secondYtd;
                }
            }elseif ($mtx[$m][0] == "Var (%)") {
                if ($total[$m-1] != 0 && $total[$m-2] != 0) {
                    $total[$m] = ($total[$pos] / $total[$pos+1])*100;
                }else{
                    $total[$m] = 0;
                }
            }elseif ($mtx[$m][0] == "Var Abs.") {
                $total[$m] = $total[$m-3] - $total[$m-2];
            }elseif ($mtx[$m][0] == "Var YTD (%)") {
            	if ($total[$m-1] != 0 && $total[$m-2] != 0) {
                    $total[$m] = ($total[$pos2] / $total[$pos2+1])*100;
                }else{
                    $total[$m] = 0;
                }
            }elseif ($mtx[$m][0] == "Var Abs. YTD") {
            	$total[$m] = $total[$m-3] - $total[$m-2];
            }else{
                $total[$m] = "-";
            }
        }

        return $total;
    }

    public function assembler($values, $valuesYTD, $years, $type){
    	
    	$mtx[0][0] = "Ranking";
    	$pos = 1;
    	
    	if ($type == "agency") {
    		$mtx[$pos][0] = "Agency group";	
    		$pos++;
    	}
    	
    	$mtx[$pos][0] = ucfirst($type);$pos++;
    	$mtx[$pos][0] = $years[0];$pos++;
    	$mtx[$pos][0] = $years[1];$pos++;
    	$mtx[$pos][0] = "Var (%)";$pos++;
    	$mtx[$pos][0] = "Var Abs.";$pos++;
    	$mtx[$pos][0] = "Class";$pos++;
    	$mtx[$pos][0] = "YTD ".$years[0];$pos++;
		$mtx[$pos][0] = "YTD ".$years[1];$pos++;
		$mtx[$pos][0] = "Var YTD (%)";$pos++;
		$mtx[$pos][0] = "Var Abs. YTD";$pos++;

        for ($v=0; $v < sizeof($values); $v++) { 
        	for ($m=0; $m < sizeof($mtx); $m++) { 
				array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[$v][$type], $values, $years, sizeof($mtx[$m]), $type, $v, $valuesYTD));
        	}
        }
		
		var_dump(sizeof($values));
		var_dump(sizeof($valuesYTD));
		//var_dump($mtx);
        /*$total = $this->assemblerChurnTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);*/
    }
}
