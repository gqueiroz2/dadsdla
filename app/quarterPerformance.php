<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\sql;
use App\salesRep;

class quarterPerformance extends performance {
    
	public function makeQuarter($con, $regionID, $year, $brands, $currencyID, $value, $months, $salesRepGroupID, $salesRepID, $tiers, $salesRepGroup, $salesRep){

		$sql = new sql();

		$sr = new salesRep();

		$salesRepGroup = $sr->getSalesRepGroupById($con, $salesRepGroupID);
        $salesRep = $sr->getSalesRepById($con, $salesRepID);

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=0; $m < sizeof($months); $m++) { 
				if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
					$table[$b][$m] = "ytd";
				}else{
					$table[$b][$m] = "digital";
				}

				$where[$b][$m] = $this->generateColumns($value);

				$values[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m][1], $where[$b][$m], $table[$b][$m]);
				$planValues[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m], "value", "plan_by_sales");

			}
		}

		//var_dump($salesRepGroup);
		$mtx = $this->assembler($values, $planValues, $salesRep, $months, $brands, $salesRepGroup, $tiers, $year, $salesRepGroup, $salesRep);

		return $mtx;

	}

	public function assembler($values, $planValues, $salesRep, $months, $brands, $salesRepGroup, $tiers, $year, $salesRepGroupN, $salesRepN){

		$mtx["salesRepGroup"] = $salesRepGroupN;
		$mtx["salesRep"] = $salesRep;

		$mtx["tiers"] = $tiers;
        $mtx["brands"] = $brands;

		$semester = 1;
		$quarter = 1;
		for ($t=0; $t < 8; $t++) { 
			
			if ($t == 0) {
				$mtx["title"][$t] = " ";	
			}elseif ($t == 3 || $t == 6) {
				$mtx["title"][$t] = "S".$semester;
				$semester++;
			}elseif ($t == 7) {
				$mtx["title"][$t] = "Total";
			}else{
				$mtx["title"][$t] = "Q".$quarter;
				$quarter++;
			}
		}

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=1; $m < 8; $m++) { 
				for ($s=0; $s < sizeof($salesRep); $s++) { 
					$tmpPlanValues[$s][$b][0] = "Target ".$year;
					$tmpPlanValues[$s][$b][$m] = 0;
					$tmpValues[$s][$b][0] = "Actual ".$year;
					$tmpValues[$s][$b][$m] = 0;
				}
			}
		}

		for ($b=0; $b < sizeof($brands); $b++) {
			for ($m=0; $m < sizeof($months); $m++) { 
				for ($s=0; $s < sizeof($salesRep); $s++) {
					if ($m == 0 || $m == 1 || $m == 2) {
						$q = 1;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];
					}elseif ($m == 3 || $m == 4 || $m == 5) {
						$q = 2;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];

						$q++;
						$tmpPlanValues[$s][$b][$q] = $tmpPlanValues[$s][$b][$q-1] + $tmpPlanValues[$s][$b][$q-2];
						$tmpValues[$s][$b][$q] = $tmpValues[$s][$b][$q-1] + $tmpValues[$s][$b][$q-2];
					}elseif ($m == 6 || $m == 7 || $m == 8) {
						$q = 4;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];
					}elseif ($m == 9 || $m == 10 || $m == 11) {
						$q = 5;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];

						$q++;
						$tmpPlanValues[$s][$b][$q] = $tmpPlanValues[$s][$b][$q-1] + $tmpPlanValues[$s][$b][$q-2];
						$tmpValues[$s][$b][$q] = $tmpValues[$s][$b][$q-1] + $tmpValues[$s][$b][$q-2];
					}
					
					$tmpPlanValues[$s][$b][7] += $tmpPlanValues[$s][$b][$q];
					$tmpValues[$s][$b][7] += $tmpValues[$s][$b][$q];

				}
			}
		}

		$mtx["planValues"] = $tmpPlanValues;
		$mtx["values"] = $tmpValues;
        
		for ($s=0; $s < sizeof($mtx["values"]); $s++) { 
			for ($b=0; $b < sizeof($mtx["values"][$s]); $b++) { 
				for ($v=1; $v < sizeof($mtx["values"][$s][$b]); $v++) { 
					$varAbs[$s][$b][0] = "Var. Abs";
					$varAbs[$s][$b][$v] = $tmpValues[$s][$b][$v] - $tmpPlanValues[$s][$b][$v];

					$var[$s][$b][0] = "Var(%)";

					if ($tmpPlanValues[$s][$b][$v] != 0) {
						$var[$s][$b][$v] = $tmpValues[$s][$b][$v] / $tmpPlanValues[$s][$b][$v];
					}else{
						$var[$s][$b][$v] = 0;
					}
				}
			}
		}


		$mtx["varAbs"] = $varAbs;
		$mtx["var"] = $var;
		
		/*Matrix Final*/
		
		$mtxFinal["salesRepGroup"] = "";

		if (sizeof($mtx["salesRepGroup"]) == sizeof($salesRepGroupN)) {
			$mtxFinal["salesRepGroup"] .= "Todos";
		}else{
			for ($srg=0; $srg < sizeof($mtx["salesRepGroup"]); $srg++) { 
				$mtxFinal["salesRepGroup"] .= $mtx["salesRepGroup"][$srg]['name'];

				if ($srg != sizeof($mtx["salesRepGroup"])-1) {
					$mtxFinal["salesRepGroup"] .= ",";
				}
			}	
		}
		
		$mtxFinal["salesRep"] = "";
		
		if (sizeof($mtx["salesRep"]) == sizeof($salesRepN)) {
			$mtxFinal["salesRep"] .= "Todos";
		}else{
			for ($sr=0; $sr < sizeof($mtx["salesRep"]); $sr++) { 
				$mtxFinal["salesRep"] .= $mtx["salesRep"][$sr]['salesRep'];

				if ($sr != sizeof($mtx["salesRep"])-1) {
					$mtxFinal["salesRep"] .= ",";
				}
			}	
		}

		$mtxFinal["tiers"] = $mtx["tiers"];
		$mtxFinal["brands"] = $mtx["brands"];
		$mtxFinal["title"] = $mtx["title"];

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($v=1; $v < 8; $v++) { 
				$totalPlanValues[$b][0] = "Target ".$year;
				$totalPlanValues[$b][$v] = 0;

				$totalValues[$b][0] = "Actual ".$year;
				$totalValues[$b][$v] = 0;
			}
		}

		for ($s=0; $s < sizeof($mtx["values"]); $s++) { 
			for ($b=0; $b < sizeof($mtx["values"][$s]); $b++) { 
				for ($v=1; $v < sizeof($mtx["values"][$s][$b]); $v++) { 
					$totalPlanValues[$b][$v] += $mtx["planValues"][$s][$b][$v];
					$totalValues[$b][$v] += $mtx["values"][$s][$b][$v];
				}
			}
		}

		$mtxFinal["planValues"] = $totalPlanValues;
		$mtxFinal["values"] = $totalValues;

        var_dump($mtxFinal);
        //var_dump($salesRep);
		//var_dump($mtxFinal);

		return $mtx;
	}
}
