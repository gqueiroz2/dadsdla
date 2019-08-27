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
    
    public function getSubResults($con, $type, $regionID, $value, $months, $brands, $currency, $filter, $filterType){

    	$sql = new sql();

    	$r = new region();                      
                                
        $tmp = $r->getRegion($con,array($regionID));

        $p = new pRate();

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }

        $cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1, $cYear-2);

		$brand = array();

        for ($i=0; $i < sizeof($brands); $i++) { 
            array_push($brand, $brands[$i][0]);
        }

		if ($type == "agency") {
			
			$a = new agency();

			$oldAgency = $a->getAllAgenciesByName($con, $sql, $filter);

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
		
    		if ($region == "Brazil") {
    			$table = "cmaps";
    		}else{
    			$table = "ytd";
    		}
    		
            if ($table != "cmaps") {
                if ($currency[0]['name'] == "USD") {
                    $pRate = 1.0;
                }else{
                    $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                }
            }else{
                if ($currency[0]['name'] == "USD") {
                    $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                }else{
                    $pRate = 1.0;
                }
            }

    		$values[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency[0]['id'], $brand, $val, $filterType);
            
            if (is_array($values[$y])) {
                for ($v=0; $v < sizeof($values[$y]); $v++) { 
                    if ($table != "cmaps") {
                        $values[$y][$v]['total'] *= $pRate;
                    }else{
                        $values[$y][$v]['total'] /= $pRate;
                    }
                }
            }
    	}
    	
		return $values;
    }

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $brands, $filter, $filterType){
    	
    	$sql = new sql();

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

		if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $brands, $filter);
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $brands, $filter);

            if ($type == "agency") {
            	array_push($columns, "agency_id");
            }elseif ($type == "client") {
            	array_push($columns, "client_id");
            }elseif($type == "sector"){
            	array_push($columns, "sector");
            }else{
            	array_push($columns, "category");
            }
        }

        $table = "$tableName $tableAbv";

        $tmp = "$leftAbv.ID AS '".$filterType."ID', $leftAbv.name AS '$filterType', SUM($value) AS '$as'";
        $join = "LEFT JOIN $filterType $leftAbv ON $leftAbv.ID = $tableAbv.".$filterType."_ID";

        if ($tableName == "cmaps") {
            
        }

        $name = $filterType."_id";
		$names = array($filterType."ID", $filterType, $as);

        $where = $sql->where($columns, $colsValue);
    
    	$values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

    	$from = $names;

    	$res = $sql->fetch($values, $from, $from);

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
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." %</td>";
		            				}elseif ($mtx[$n][0] == "Ranking") {
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." º</td>";
		            				}else{
		            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." </td>";
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
			            				echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";
			            			}else{
			            				echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";
			            			}
			            		}else{
			        				if ($t == 4 || $t == 10) {
			        					echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";	
			        				}else{
			        					echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";	
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
