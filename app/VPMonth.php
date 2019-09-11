<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\pAndR;
use App\sql;
use App\base;
use App\pRate;

class VPMonth extends pAndR {
    
    public function weekOfMonth($date) {
        $date = strtotime($date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.

        $res = intval(date("W", $date)) - intval(date("W", $firstOfMonth));

        return $res > 0 ? $res : ($res+1);
    }

    public function getLinesValue($con, $region, $currencyID, $year, $aux_value, $currency, $line){
        
        $sql = new sql();
        $base = new base();
        $p = new pRate();

        $as = 'total';

        if ($currency[0]['name'] == 'USD') {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
        }

        $cMonth = intval(date('m'));

        $today = date("Y-m-d");
        $read = $this->weekOfMonth($today);

        for ($m=0; $m < sizeof($base->month); $m++) { 
            $value = $aux_value;
            $digital = false;

            if ($line == "Target") {
                
                $value = strtoupper($value);
                $tmp = "SUM(value) AS 'total'";

                $columns = array("region_id", "currency_id", "year", "type_of_revenue", "month");
                $colsValue = array($region, $currencyID, $year, $value, $base->month[$m][1]);

                $table = 'plan_by_sales';
                $join = null;

                $names = array("total");

            }elseif ($line == "Rolling Fcast ".$year) {

                if ($base->month[$m][1] < $cMonth) {
                    
                    $value .= '_revenue_prate';
                    $valueD = $aux_value."_revenue";

                    $tmp = "SUM($value) AS 'total'";
                    $tmpD = "SUM($valueD) AS 'total'";

                    $columns = array("sales_representant_office_id", "year", "month");
                    $columnsD = array("region_id", "currency_id", "year", "month");

                    $colsValue = array($region, $year, $base->month[$m][1]);
                    $colsValueD = array($region, $currencyID, $year, $base->month[$m][1]);

                    $table = 'ytd';
                    $tableD = 'fw_digital';

                    $join = null;
                    $joinD = null;

                    $digital = true;

                    $names = array("total");
                    $namesD = array("total");

                }else{
                    
                    $value = ucfirst($value);
                    $tmp = "read_q, SUM(value) AS 'total'";

                    if (($read - 1) > 0) {
                        $aux = $read-1;
                    }else{
                        $aux = $read;
                    }

                    $columns = array("f2.region_id", "f2.currency_id", "f2.year", "f2.type_of_value", "f2.month", "f.month", "f2.read_q");
                    $colsValue = array($region, $currencyID, $year, $value, intval(date('m')), $base->month[$m][1], $aux);

                    $table = 'forecast_sales_rep f';
                    $join = "LEFT JOIN forecast f2 ON f2.ID = f.forecast_id";

                    $names = array("read_q", "total");
                }

            }elseif ($line == "Past Rolling Fcast") {
                
                $value = ucfirst($value);
                $tmp = "read_q, SUM(value) AS 'total'";

                if (($read - 2) > 0) {
                    $aux = $read-2;
                }else{
                    $aux = 1;
                }

                $columns = array("f2.region_id", "f2.currency_id", "f2.year", "f2.type_of_value", "f2.month", "f.month", "f2.read_q");
                $colsValue = array($region, $currencyID, $year, $value, intval(date('m')), $base->month[$m][1], $aux);

                $table = 'forecast_sales_rep f';
                $join = "LEFT JOIN forecast f2 ON f2.ID = f.forecast_id";

                $names = array("read_q", "total");

            }elseif ($line == "Manual Estimation") {
                
                $value = ucfirst($value);
                $tmp = "read_q, SUM(value) AS 'total'";

                if (($read - 1) > 0) {
                    $aux = $read-1;
                }else{
                    $aux = $read;
                }

                $columns = array("f2.region_id", "f2.currency_id", "f2.year", "f2.type_of_value", "f2.month", "f.month", "f2.read_q");
                $colsValue = array($region, $currencyID, $year, $value, intval(date('m')), $base->month[$m][1], $aux);

                $table = 'forecast_sales_rep f';
                $join = "LEFT JOIN forecast f2 ON f2.ID = f.forecast_id";

                $names = array("read_q", "total");

            }elseif ($line == "Bookings") {

                $value .= '_revenue_prate';
                $valueD = $aux_value."_revenue";

                $tmp = "SUM($value) AS 'total'";
                $tmpD = "SUM($valueD) AS 'total'";

                $columns = array("sales_representant_office_id", "year", "month");
                $columnsD = array("region_id", "currency_id", "year", "month");

                $colsValue = array($region, $year, $base->month[$m][1]);
                $colsValueD = array($region, $currencyID, $year, $base->month[$m][1]);

                $table = 'ytd';
                $tableD = 'fw_digital';

                $join = null;
                $joinD = null;

                $digital = true;

                $names = array("total");
                $namesD = array("total");

            }elseif ($line == ($year-1)) {

                $value .= '_revenue_prate';
                $valueD = $aux_value."_revenue";

                $tmp = "SUM($value) AS 'total'";
                $tmpD = "SUM($valueD) AS 'total'";

                $columns = array("sales_representant_office_id", "year", "month");
                $columnsD = array("region_id", "currency_id", "year", "month");

                $colsValue = array($region, ($year-1), $base->month[$m][1]);
                $colsValueD = array($region, $currencyID, ($year-1), $base->month[$m][1]);

                $table = 'ytd';
                $tableD = 'fw_digital';

                $join = null;
                $joinD = null;

                $digital = true;

                $names = array("total");
                $namesD = array("total");
            }

            $where = $sql->where($columns, $colsValue);

            $values[$m] = $sql->larica($con, $tmp, $table, $join, $where);
            
            $res[$m] = $sql->fetch($values[$m], $names, $names);
            
            if (is_array($res[$m])) {
                $res[$m][0]['total'] /= $pRate;
            }

            if ($digital) {

                $whereD = $sql->where($columnsD, $colsValueD);

                $valuesD[$m] = $sql->select($con, $tmpD, $tableD, $joinD, $whereD);
                
                $resD[$m] = $sql->fetch($valuesD[$m], $namesD, $namesD);

                if (is_array($resD[$m])) {
                    $resD[$m][0]['total'] /= $pRate;
                }

                $res[$m][0] += $resD[$m][0];
            }
        }

        if (is_array($res)) {
            for ($r=0; $r < sizeof($res); $r++) { 
                $resF[$r] = $res[$r][0];       
            }
        }else{
            $resF = $res;
        }
        
        return $resF;
    }

    public function assembler($target, $forecast, $pForecast, $manualEstimation, $bookings, $pBookings, $year, $region){
    	
    	$totalTarget = 0;
    	$totalForecast = 0;
        $totalManualEstimation = 0;
        $totalPForecast = 0;
    	$totalBookings = 0;
    	$totalPBookings = 0;

    	$matrix[0][0] = $region;
    	$matrix[1][0] = "Target";
        $matrix[2][0] = "Rolling Fcast ".$year;
        $matrix[3][0] = "Manual Estimation";
        $matrix[4][0] = "Past Rolling Fcast";
        $matrix[5][0] = "Bookings";
        $matrix[6][0] = "Pending";
        $matrix[7][0] = $year-1;
        $matrix[8][0] = "Var RF vs Target";
        $matrix[9][0] = "% Target Achievement";

        $base = new base();

        $cMonth = intval(date('m'));

        $p = 0;

        $quarter = array(1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0);

        for ($m=0; $m < sizeof($base->month); $m++) {
        	if ($quarter[$p] == 0) {
    			if ($base->month[$m][1] == 4) {
    				$val = "Q1";
    			}elseif ($base->month[$m][1] == 7) {
    				$val = "Q2";
    			}else{
    				$val = "Q3";
    			}

    			$matrix[0][($p+1)] = $val;

    			$matrix[1][($p+1)] = $matrix[1][($p+1)-1] + $matrix[1][($p+1)-2] + $matrix[1][($p+1)-3];
    			$matrix[2][($p+1)] = $matrix[2][($p+1)-1] + $matrix[2][($p+1)-2] + $matrix[2][($p+1)-3];
                $matrix[3][($p+1)] = $matrix[3][($p+1)-1] + $matrix[3][($p+1)-2] + $matrix[3][($p+1)-3];
    			$matrix[4][($p+1)] = $matrix[4][($p+1)-1] + $matrix[4][($p+1)-2] + $matrix[4][($p+1)-3];
                $matrix[5][($p+1)] = $matrix[5][($p+1)-1] + $matrix[5][($p+1)-2] + $matrix[5][($p+1)-3];
    			$matrix[6][($p+1)] = $matrix[2][($p+1)] - $matrix[5][($p+1)];
    			$matrix[7][($p+1)] = $matrix[7][($p+1)-1] + $matrix[7][($p+1)-2] + $matrix[7][($p+1)-3];
    			$matrix[8][($p+1)] = $matrix[2][($p+1)] - $matrix[1][($p+1)];

    			if ($base->month[$m][2] < $cMonth) {
	        		if ($matrix[1][($p+1)] == 0 || $matrix[5][($p+1)] == 0) {
	        			$matrix[9][($p+1)] = 0;
	        		}else{
	        			$matrix[9][($p+1)] = ($matrix[5][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}else{
	        		if ($matrix[1][($p+1)] == 0 || $matrix[2][($p+1)] == 0) {
	        			$matrix[9][($p+1)] = 0;
	        		}else{
	        			$matrix[9][($p+1)] = ($matrix[2][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}

	        	$quarter[($p+1)] = 1;
	        	$p++;
	        	$m--;
        	}else{
        		$matrix[0][($p+1)] = $base->month[$m][2];

                if (!$target[$m]['total'] || is_null($target[$m]['total'])) {
                    $matrix[1][($p+1)] = 0;                    
                }else{
                    $matrix[1][($p+1)] = $target[$m]['total'];
                }

	        	$totalTarget += $target[$m]['total'];

                if (!$forecast[$m]['total'] || is_null($forecast[$m]['total'])) {
                    $matrix[2][($p+1)] = 0;                    
                }else{
                    $matrix[2][($p+1)] = $forecast[$m]['total'];
                }

	        	$totalForecast += $forecast[$m]['total'];

                if (!$manualEstimation[$m]['total'] || is_null($manualEstimation[$m]['total'])) {
                    $matrix[3][($p+1)] = 0;
                }else{
                    $matrix[3][($p+1)] = $manualEstimation[$m]['total'];
                }

                $totalManualEstimation += $manualEstimation[$m]['total'];

                if (!$pForecast[$m]['total'] || is_null($pForecast[$m]['total'])) {
                    $matrix[4][($p+1)] = 0;
                }else{
                    $matrix[4][($p+1)] = $pForecast[$m]['total'];
                }

                $totalPForecast += $pForecast[$m]['total'];

                if (!$bookings[$m]['total'] || is_null($bookings[$m]['total'])) {
                    $matrix[5][($p+1)] = 0;
                }else{
                    $matrix[5][($p+1)] = $bookings[$m]['total'];
                }

	        	$totalBookings += $bookings[$m]['total'];

	        	$matrix[6][($p+1)] = $forecast[$m]['total'] - $bookings[$m]['total'];

                if (!$pBookings[$m]['total'] || is_null($pBookings[$m]['total'])) {
                    $matrix[7][($p+1)] = 0;
                }else{
                    $matrix[7][($p+1)] = $pBookings[$m]['total'];
                }

	        	$totalPBookings += $pBookings[$m]['total'];

	        	$matrix[8][($p+1)] = $forecast[$m]['total'] - $target[$m]['total'];

	        	if ($base->month[$m][2] < $cMonth) {
	        		if ($matrix[1][($p+1)] == 0 || $matrix[5][($p+1)] == 0) {
	        			$matrix[9][($p+1)] = 0;
	        		}else{
	        			$matrix[9][($p+1)] = ($matrix[5][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}else{
	        		if ($matrix[1][($p+1)] == 0 || $matrix[2][($p+1)] == 0) {
	        			$matrix[9][($p+1)] = 0;
	        		}else{
	        			$matrix[9][($p+1)] = ($matrix[2][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}

	        	$p++;
        	}
        }

        $last = ($p+1);

        $matrix[0][$last] = "Q4";

        $matrix[1][$last] = $matrix[1][$last-1] + $matrix[1][$last-2] + $matrix[1][$last-3];
        $matrix[2][$last] = $matrix[2][$last-1] + $matrix[2][$last-2] + $matrix[2][$last-3];
        $matrix[3][$last] = $matrix[3][$last-1] + $matrix[3][$last-2] + $matrix[3][$last-3];
        $matrix[4][$last] = $matrix[4][$last-1] + $matrix[4][$last-2] + $matrix[4][$last-3];
        $matrix[5][$last] = $matrix[5][$last-1] + $matrix[5][$last-2] + $matrix[5][$last-3];
        $matrix[6][$last] = $matrix[2][$last] - $matrix[5][$last];
        $matrix[7][$last] = $matrix[7][$last-1] + $matrix[7][$last-2] + $matrix[7][$last-3];
        $matrix[8][$last] = $matrix[2][$last] - $matrix[1][$last];

        if ($base->month[$m-1][2] < $cMonth) {
            if ($matrix[1][$last] == 0 || $matrix[5][$last] == 0) {
                $matrix[9][$last] = 0;
            }else{
                $matrix[9][$last] = ($matrix[5][$last]/$matrix[1][$last])*100;
            }
        }else{
            if ($matrix[1][$last] == 0 || $matrix[2][$last] == 0) {
                $matrix[9][$last] = 0;
            }else{
                $matrix[9][$last] = ($matrix[2][$last]/$matrix[1][$last])*100;
            }
        }

    	$quarter[$last] = 1;
    	$last++;

		$matrix[0][$last] = "Total";
        $matrix[1][$last] = $totalTarget;
        $matrix[2][$last] = $totalForecast;
        $matrix[3][$last] = $totalManualEstimation;
        $matrix[4][$last] = $totalPForecast;
		$matrix[5][$last] = $totalBookings;
		$matrix[6][$last] = $totalForecast - $totalBookings;
		$matrix[7][$last] = $totalPBookings;
		$matrix[8][$last] = $totalForecast - $totalTarget;

		if ($base->month[$m-1][2] < $cMonth) {
    		if ($matrix[1][$last] == 0 || $matrix[5][$last] == 0) {
    			$matrix[9][$last] = 0;
    		}else{
    			$matrix[9][$last] = ($matrix[5][$last]/$matrix[1][$last])*100;
    		}
    	}else{
    		if ($matrix[1][$last] == 0 || $matrix[2][$last] == 0) {
    			$matrix[9][$last] = 0;
    		}else{
    			$matrix[9][$last] = ($matrix[2][$last]/$matrix[1][$last])*100;
    		}
    	}

        return $matrix;
    }
}
