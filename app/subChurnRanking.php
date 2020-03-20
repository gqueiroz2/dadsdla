<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\region;
use App\brand;
use App\agency;
use App\client;
use App\sql;
use App\base;

class subChurnRanking extends rankingChurn {
    
    public function getSubResults($con, $type, $regionID, $value, $months, $brands, $currency, $filter, $filterType, $auxName,$year){
        
    	$sql = new sql();

    	$r = new region();

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }

        if ($region == "Brazil") {
            $table = "cmaps";
        }else{
            $table = "ytd";
        }

        

        $cYear = $year;//intval(date('Y'));
		$years = array($cYear, $cYear-1, $cYear-2);

		$brand = array();

        for ($i=0; $i < sizeof($brands); $i++) { 
            array_push($brand, $brands[$i][0]);
        }

		if ($type == "agency") {
			
			$a = new agency();

			$oldAgency = $a->getAllAgenciesByName($con, $sql, $filter, $auxName);

			if (is_array($oldAgency)) {
	            for ($a=0; $a < sizeof($oldAgency); $a++) {
	                $val[$a] = $oldAgency[$a]['id'];
	            }    
	        }else{
	            $val = $oldAgency;
	        }
		}elseif ($type == "client") {
			$c = new client();

			$val = $c->getClientIDByRegion($con, $sql, $filter, array($regionID));
		}else{
			$val = $filter;
		}
		
		for ($y=0; $y < sizeof($years); $y++) { 
		  $values[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brand, $val, $filterType);
    	}
    	
		return $values;
    }

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $brands, $filter, $filterType){

    	$sql = new sql();
        
        $p = new pRate();

        if ($tableName != "cmaps") {
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
            }
        }else{
            if ($currency[0]['name'] == "USD") {
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
            }else{
                $pRate = 1.0;
            }
        }

        if ($currency[0]['name'] == "USD") {
            $pRateDigital = 1.0;
        }else{
            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }

        $brands_id = array();
        $brands_idD = array();
        
        for ($b=0; $b < sizeof($brands); $b++) {
            if ($brands[$b] == '9') {
                array_push($brands_idD, '9');
                array_push($brands_idD, '13');
                array_push($brands_idD, '14');
                array_push($brands_idD, '15');
                array_push($brands_idD, '16');
            }elseif ($brands[$b] == '10') {
                array_push($brands_idD, '10');
            }else{
                array_push($brands_id,$brands[$b]);
            }
        }

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($type == "agency" || $type == "client") {
            $valueD = $value."_revenue";
            $columnsD = array("f.region_id","brand_id","month","year");
            $colsValueD = array($region, $brands_idD, $months, $year, $filter);
        }
        
		if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $brands, $filter);
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $brands, $filter);
        }

        if ($type == "agency") {
            array_push($columns, "agency_id");
            array_push($columnsD, "agency_id");
        }elseif ($type == "client") {
            array_push($columns, "client_id");
            array_push($columnsD, "client_id");
        }elseif($type == "sector"){
            array_push($columns, "sector");
        }else{
            array_push($columns, "category");
        }

        $table = "$tableName $tableAbv";        

        $tmp = "$leftAbv.ID AS '".$filterType."ID', $leftAbv.name AS '$filterType', SUM($value) AS '$as'";

        if ($type == "client") {
            $tmp .= ", c.name AS 'agencyGroup'";
        }

        $join = "LEFT JOIN $filterType $leftAbv ON $leftAbv.ID = $tableAbv.".$filterType."_ID";

        if ($type == "client") {
            $join .= " LEFT JOIN agency_group c ON c.ID = b.agency_group_id";
        }

        $name = $filterType."_id";
		$names = array($filterType."ID", $filterType, $as);

        if ($type == "client") {
            array_push($names, "agencyGroup");
        }

        $where = $sql->where($columns, $colsValue);

    	$values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

    	$from = $names;
    	$res = $sql->fetch($values, $from, $from);

        if ($type == "agency" || $type == "client") {
            $tmpD = $filterType."_id AS '".$filterType."ID', ".$leftAbv.".name AS '".$filterType."', SUM($valueD) AS $as";

            if ($type == "client") {
                $tmpD .= ", c.name AS 'agencyGroup'";
            }

            $joinD = "LEFT JOIN ".$filterType." ".$leftAbv." ON ".$leftAbv.".ID = ".$filterType."_id";

            if ($type == "client") {
                $joinD .= " LEFT JOIN agency_group c ON c.ID = b.agency_group_id";
            }

            $whereD = $sql->where($columnsD, $colsValueD);
            $valuesD = $sql->selectGroupBy($con, $tmpD, "fw_digital f", $joinD, $whereD, "total", $name, "DESC");
            $resD = $sql->fetch($valuesD, $from, $from);
        }

        if (is_array($res)) {
            for ($v=0; $v < sizeof($res); $v++) { 
                if ($tableName != "cmaps") {
                    $res[$v]['total'] *= $pRate;
                }else{
                    $res[$v]['total'] /= $pRate;
                }
            }
        }
        if ($type == "agency" || $type == "client") {
            if ($resD && $res) {
            
                for ($r=0; $r < sizeof($resD); $r++) { 
                    $resD[$r]['total'] *= $pRateDigital;
                }

                $size1 = sizeof($resD);
                for ($r=0; $r < $size1; $r++) { 
                    for ($r2=0; $r2 < sizeof($res); $r2++) {
                        if ($resD[$r][$filterType.'ID'] == $res[$r2][$filterType.'ID']) {
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
        }

    	return $res;
    }

    public function renderSubAssembler($mtx, $total, $type, $years){

        echo "<div class='container-fluid'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 2px solid black;'>";
                    
                    for ($m=0; $m < sizeof($mtx[0]); $m++) { 
                        echo "<tr>";    
                        
                        if ($m == 0) {
                            $color = 'lightBlue';
                        }elseif ($m%2 == 0) {
                            $color = 'medBlue';
                        }else{
                            $color = 'rcBlue';  
                        }

                        for ($n=0; $n < sizeof($mtx); $n++) { 
                            
                            if ($m == 0) {
		            			echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
		            		}else{
		            			if (is_numeric($mtx[$n][$m])) {
		            				if ($mtx[$n][0] == "Var (%)" || $mtx[$n][0] == "Var YTD (%)") {
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." %</td>";
		            				}elseif ($mtx[$n][0] == "Ranking") {
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." º</td>";
		            				}else{
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." </td>";
		            				}
		            			}else{
	                                echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
		            			}
		            		}
                        }

                        echo "</tr>";
                    }

                    	echo "<tr>";
			            for ($t=0; $t < sizeof($total); $t++) {
			            	if (is_numeric($total[$t])) {
			            		if ($type == "agency") {
			            			if ($t == 5 || $t == 11) {
			            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";
			            			}else{
			            				echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
			            			}
			            		}elseif ($type == "client") {
                                    if ($t == 6) {
                                        echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";   
                                    }else{
                                        echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";    
                                    }
                                }else{
                                    if ($t == 5) {
                                        echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";   
                                    }else{
                                        echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";    
                                    }
			        			}
			            	}else{
			            		echo "<td class='darkBlue center'> ".$total[$t]." </td>";
			            	}
			            }
		            	echo "</tr>";

            	    echo "</table>";
        	   echo "</div>";
    	   echo "</div>";
	   echo "</div>";
	}
    
}
