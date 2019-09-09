<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\pAndR;
use App\sql;
use App\base;
use App\pRate;

class VPMonth extends pAndR {
    
	public function getLinesValue($con, $region, $currencyID, $year, $line) {
    	
    	$sql = new sql();
    	$base = new base();

    	$as = 'total';

    	$p = new pRate();

    	$currency = $p->getCurrency($con, array($currencyID));

    	if ($currency[0]['name'] == 'USD') {
    		$pRate = 1.0;
    	}else{
    		$pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
    	}

        $digital = false;

    	if ($line == "Target") {
			$value = 'GROSS';
			$sum = 'value';

			$columns = array("region_id", "currency_id", "year", "type_of_revenue", "month");
    		$colsValue = array($region, $currencyID, $year, $value, 0);

    		$table = 'plan_by_sales';
    		$join = null;

    	}elseif ($line == "Roling Fcast ".$year) {
    		$value = 'Gross';
    		$sum = 'value';

    		$columns = array("f2.region_id", "f2.currency_id", "f2.year", "f2.type_of_value", "f.month");
	    	$colsValue = array($region, $currencyID, $year, $value, 0);

	    	$table = 'forecast_sales_rep f';
	    	$join = "LEFT JOIN forecast f2 ON f2.ID = f.forecast_id";

    	}elseif ($line == "Bookings") {
    		$value = 'gross_revenue_prate';
            $valueD = "gross_revenue";

    		$sum = 'gross_revenue_prate';
            $sumD = "gross_revenue";

			$columns = array("sales_representant_office_id", "year", "month");
            $columnsD = array("region_id", "currency_id", "year", "month");

	    	$colsValue = array($region, $year, 0);
            $colsValueD = array($region, $currencyID, $year, 0);

	    	$table = 'ytd';
            $tableD = 'fw_digital';

    		$join = null;
            $joinD = null;

            $digital = true;

    	}elseif ($line == ($year-1)) {
			$value = 'gross_revenue_prate';
            $valueD = "gross_revenue";

    		$sum = 'gross_revenue_prate';
            $sumD = "gross_revenue";

			$columns = array("sales_representant_office_id", "year", "month");
            $columnsD = array("region_id", "currency_id", "year", "month");

	    	$colsValue = array($region, ($year-1), 0);
            $colsValueD = array($region, $currencyID, ($year-1), 0);

	    	$table = 'ytd';
            $tableD = 'fw_digital';

    		$join = null;
            $joinD = null;

            $digital = true;
    	}

    	for ($m=0; $m < sizeof($base->month); $m++) { 
    		
    		$colsValue[sizeof($colsValue)-1] = $base->month[$m][1];

    		$where = $sql->where($columns, $colsValue);

    		$values[$m] = $sql->selectSum($con, $sum, $as, $table, $join, $where);
    		
    		$res[$m] = $sql->fetchSum($values[$m], $as);
    		$res[$m]['total'] /= $pRate;

            if ($digital) {
                $colsValueD[sizeof($colsValue)-1] = $base->month[$m][1];

                $whereD = $sql->where($columnsD, $colsValueD);

                $valuesD[$m] = $sql->selectSum($con, $sumD, $as, $tableD, $joinD, $whereD);
                
                $resD[$m] = $sql->fetchSum($valuesD[$m], $as);
                $resD[$m]['total'] /= $pRate;

                $res[$m] += $resD[$m];
            }
    	}

    	return $res;
    }

    public function assembler($target, $forecast, $bookings, $pBookings, $year){
    	
    	$totalTarget = 0;
    	$totalForecast = 0;
    	$totalBookings = 0;
    	$totalPBookings = 0;

    	$matrix[0][0] = "";
    	$matrix[1][0] = "Target";
        $matrix[2][0] = "Rolling Fcast ".$year;
        $matrix[3][0] = "Bookings";
        $matrix[4][0] = "Pending";
        $matrix[5][0] = $year-1;
        $matrix[6][0] = "Var RF vs Target";
        $matrix[7][0] = "% Target Achievement";

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
    			$matrix[4][($p+1)] = $matrix[2][($p+1)] - $matrix[3][($p+1)];
    			$matrix[5][($p+1)] = $matrix[5][($p+1)-1] + $matrix[5][($p+1)-2] + $matrix[5][($p+1)-3];
    			$matrix[6][($p+1)] = $matrix[2][($p+1)] - $matrix[1][($p+1)];

    			if ($base->month[$m][2] < $cMonth) {
	        		if ($matrix[1][($p+1)] == 0 || $matrix[3][($p+1)] == 0) {
	        			$matrix[7][($p+1)] = 0;
	        		}else{
	        			$matrix[7][($p+1)] = ($matrix[3][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}else{
	        		if ($matrix[1][($p+1)] == 0 || $matrix[2][($p+1)] == 0) {
	        			$matrix[7][($p+1)] = 0;
	        		}else{
	        			$matrix[7][($p+1)] = ($matrix[2][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}

	        	$quarter[($p+1)] = 1;
	        	$p++;
	        	$m--;
        	}else{
        		$matrix[0][($p+1)] = $base->month[$m][2];

	        	$matrix[1][($p+1)] = $target[$m]['total'];
	        	$totalTarget += $target[$m]['total'];

	        	$matrix[2][($p+1)] = $forecast[$m]['total'];
	        	$totalForecast += $forecast[$m]['total'];

	        	$matrix[3][($p+1)] = $bookings[$m]['total'];
	        	$totalBookings += $bookings[$m]['total'];

	        	$matrix[4][($p+1)] = $forecast[$m]['total'] - $bookings[$m]['total'];

	        	$matrix[5][($p+1)] = $pBookings[$m]['total'];
	        	$totalPBookings += $pBookings[$m]['total'];

	        	$matrix[6][($p+1)] = $forecast[$m]['total'] - $target[$m]['total'];

	        	if ($base->month[$m][2] < $cMonth) {
	        		if ($matrix[1][($p+1)] == 0 || $matrix[3][($p+1)] == 0) {
	        			$matrix[7][($p+1)] = 0;
	        		}else{
	        			$matrix[7][($p+1)] = ($matrix[3][($p+1)]/$matrix[1][($p+1)])*100;
	        		}
	        	}else{
	        		if ($matrix[1][($p+1)] == 0 || $matrix[2][($p+1)] == 0) {
	        			$matrix[7][($p+1)] = 0;
	        		}else{
	        			$matrix[7][($p+1)] = ($matrix[2][($p+1)]/$matrix[1][($p+1)])*100;
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
		$matrix[4][$last] = $matrix[2][$last] - $matrix[3][$last];
		$matrix[5][$last] = $matrix[5][$last-1] + $matrix[5][$last-2] + $matrix[5][$last-3];
		$matrix[6][$last] = $matrix[2][$last] - $matrix[1][$last];

		if ($base->month[$m-1][2] < $cMonth) {
    		if ($matrix[1][$last] == 0 || $matrix[3][$last] == 0) {
    			$matrix[7][$last] = 0;
    		}else{
    			$matrix[7][$last] = ($matrix[3][$last]/$matrix[1][$last])*100;
    		}
    	}else{
    		if ($matrix[1][$last] == 0 || $matrix[2][$last] == 0) {
    			$matrix[7][$last] = 0;
    		}else{
    			$matrix[7][$last] = ($matrix[2][$last]/$matrix[1][$last])*100;
    		}
    	}

    	$quarter[$last] = 1;
    	$last++;

		$matrix[0][$last] = "Total";
        $matrix[1][$last] = $totalTarget;
        $matrix[2][$last] = $totalForecast;
		$matrix[3][$last] = $totalBookings;
		$matrix[4][$last] = $totalForecast - $totalBookings;
		$matrix[5][$last] = $totalPBookings;
		$matrix[6][$last] = $totalForecast - $totalTarget;

		if ($base->month[$m-1][2] < $cMonth) {
    		if ($matrix[1][$last] == 0 || $matrix[3][$last] == 0) {
    			$matrix[7][$last] = 0;
    		}else{
    			$matrix[7][$last] = ($matrix[3][$last]/$matrix[1][$last])*100;
    		}
    	}else{
    		if ($matrix[1][$last] == 0 || $matrix[2][$last] == 0) {
    			$matrix[7][$last] = 0;
    		}else{
    			$matrix[7][$last] = ($matrix[2][$last]/$matrix[1][$last])*100;
    		}
    	}    	

        return $matrix;
    }
}
