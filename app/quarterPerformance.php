<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\sql;
use App\salesRep;
use App\pRate;
use App\base;

class quarterPerformance extends performance {
    
	public function createLabels($con, $salesRepGroupID, $salesRepID, $regionID, $year){
		
		$sr = new salesRep();

		$salesRepGroup = $sr->getSalesRepGroupById($con, $salesRepGroupID);
        $salesRep = $sr->getSalesRepById($con, $salesRepID);

        $salesRepGroupN = $sr->getSalesRepGroup($con, array($regionID));
        $salesRepN = $sr->getSalesRepByRegion($con, array($regionID),true,$year);

        $sales["salesRepGroup"] = "";

		if (sizeof($salesRepGroup) == sizeof($salesRepGroupN)) {
			$sales["salesRepGroup"] .= "All";
		}else{
			for ($srg=0; $srg < sizeof($salesRepGroup); $srg++) { 
				$sales["salesRepGroup"] .= $salesRepGroup[$srg]['name'];

				if ($srg != sizeof($salesRepGroup)-1) {
					$sales["salesRepGroup"] .= ";";
				}
			}	
		}
		
		$sales["salesRep"] = "";
		
		if (sizeof($salesRep) == sizeof($salesRepN)) {
			$sales["salesRep"] .= "All";
		}else{
			for ($sr=0; $sr < sizeof($salesRep); $sr++) { 
				$sales["salesRep"] .= $salesRep[$sr]['salesRep'];

				if ($sr != sizeof($salesRep)-1) {
					$sales["salesRep"] .= ";";
				}
			}	
		}

        return $sales;

	}

	public function makeQuarter($con, $regionID, $year, $brands, $currencyID, $value, $months, $tiers, $salesRepID){

		$sql = new sql();

		$base = new base();

		$pr = new pRate();

		$sr = new salesRep();

		if ($regionID == '6' || $regionID == '7') {
            array_push($salesRepID, '15');
        }elseif ($regionID == '9') {
            array_push($salesRepID, '102');
        }elseif ($regionID == '10') {
            array_push($salesRepID, '103');
        }elseif ($regionID == '12') {
            array_push($salesRepID, '104');
        }elseif ($regionID == '13') {
            array_push($salesRepID, '105');
        }elseif ($regionID == '11') {
            array_push($salesRepID, '45');
        }

        $salesRep = $sr->getSalesRepById($con, $salesRepID);

        $value = strtoupper($value);

        $tmp = array($year);
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        //$div = 1/$div;tiers

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=0; $m < sizeof($months); $m++) { 
				if ( ($brands[$b][1] == 'ONL' && $brands[$b][1] == 'VIX') && $year < 2020 ) {
					$table[$b][$m] = "fw_digital";
				}else{
					$table[$b][$m] = "ytd";
				}

				$where[$b][$m] = $this->generateColumns($value,$table[$b][$m]);

				$values[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m][1], $where[$b][$m], $table[$b][$m]);
				$planValues[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m][1], "value", "plan_by_sales", $value);

			}

		}

		//var_dump($salesRepGroup);
		//var_dump($planValues);
		$mtx = $this->assembler($values, $planValues, $salesRep, $months, $brands, $tiers, $year, $div, $value);

		if (sizeof($brands) > 1) {
			array_push($mtx[0], $this->assemblerDN($mtx[0], $year));
		}

		return $mtx;

	}

	public function remakeArray($brandsTiers){
		
		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			if (($b == (sizeof($brandsTiers)-1)) && empty($brandsTiers[$b])) {
				array_pop($brandsTiers);
			}elseif (empty($brandsTiers[$b])) {
				for ($b2=$b; $b2 < (sizeof($brandsTiers)-1); $b2++) { 
					//var_dump($b2);
					$brandsTiers[$b2] = $brandsTiers[$b2+1];	
				}
				array_pop($brandsTiers);
			}else{

			}
		}

		return $brandsTiers;
	}

	public function assembler($values, $planValues, $salesRep, $months, $brands, $tiers, $year, $div, $value){

		setlocale(LC_ALL, "en_US.utf8");
        $uN = iconv("utf-8", "ascii//TRANSLIT", Request::session()->get('userName'));
        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $sql = new sql();
        $base = new base();


        $valueFix = strtolower($value);
        $from = array('revenue');

        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            for ($b=0; $b <sizeof($brands); $b++) {
                for ($m=0; $m <sizeof($months); $m++) { 
                    if( ($salesRep[$s]['id'] == 131) && ($m > 5) && ($brands[$b][1] == 'ONL' || $brands[$b][1] == 'VIX') ){
                        if( $brands[$b][1] == "ONL"){
                            $select[$b][$m] = "SELECT SUM(".$valueFix."_revenue) AS revenue FROM fw_digital WHERE (region_id = 1) AND(year = '$year') AND (brand_id != '10') AND (month = '".($m+1)."')";
                        }elseif($brands[$b][1] == "VIX"){
                            $select[$b][$m] = "SELECT SUM(".$valueFix."_revenue) AS revenue FROM fw_digital WHERE (region_id = 1) AND(year = '$year') AND (brand_id = '10') AND (month = '".($m+1)."')";
                        }
                        $result[$b][$m] = $con->query($select[$b][$m]);
                        $kaplau = doubleval($sql->fetch($result[$b][$m],$from,$from)[0]['revenue']);
                        $values[$b][$m][$s] = $kaplau;
                    }                    
                }                
            }
        }


		//separando as marcas por tiers
		$brandsTiers = array(0, 1, 2);
		$newPlanValues = array(0, 1, 2);
		$newValues = array(0, 1, 2);

		for ($b=0; $b <sizeof($values) ; $b++) { 
			for ($m=0; $m <sizeof($values[$b]) ; $m++) { 
				for ($s=0; $s <sizeof($values[$b][$m]) ; $s++) {
					if ($salesRep[$s]['id'] != 131 || $uN == 'Joao Romano') {
						$values[$b][$m][$s] = $values[$b][$m][$s]*$div;
						$planValues[$b][$m][$s] = $planValues[$b][$m][$s]*$div;
					}else{
						$values[$b][$m][$s] = 0.0;
						$planValues[$b][$m][$s] = 0.0;
					} 
				}
			}
		}

		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			$brandsTiers[$b] = array();
			$newPlanValues[$b] = array();
			$newValues[$b] = array();
		}

		for ($b=0; $b < sizeof($brands); $b++) { 
			if ($brands[$b][1] == "DC" || $brands[$b][1] == "HH" || $brands[$b][1] == "DK") {
				array_push($brandsTiers[0], $brands[$b][1]);
				array_push($newPlanValues[0], $planValues[$b]);
				array_push($newValues[0], $values[$b]);
			}elseif ($brands[$b][1] == "AP" 
					|| $brands[$b][1] == "TLC"
					|| $brands[$b][1] == "ID"
					|| $brands[$b][1] == "DT"
					|| $brands[$b][1] == "FN"
					|| $brands[$b][1] == "ONL"
					|| $brands[$b][1] == "VIX"
					|| $brands[$b][1] == "HGTV") {
				array_push($brandsTiers[1], $brands[$b][1]);
				array_push($newPlanValues[1], $planValues[$b]);
				array_push($newValues[1], $values[$b]);
			}else{
				array_push($brandsTiers[2], $brands[$b][1]);
				array_push($newPlanValues[2], $planValues[$b]);
				array_push($newValues[2], $values[$b]);
			}
		}
		$auxTiers = $brandsTiers;
		//var_dump($planValues);
		//arrumando vetor, caso haja tiers em branco
		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			$brandsTiers = $this->remakeArray($brandsTiers);
			$newPlanValues = $this->remakeArray($newPlanValues);
			$newValues = $this->remakeArray($newValues);
		}
		
		/*for ($i=0; $i < sizeof($newValues); $i++) { 
			var_dump($brandsTiers[$i]);
		}*/
		
		//criando valores texto da matriz
		for ($t=0; $t < sizeof($brandsTiers); $t++) { 
			for ($b=0; $b < sizeof($brandsTiers[$t]); $b++) { 
				$mtx[$t][$b][0][0] = $brandsTiers[$t][$b];
				$mtx[$t][$b][1][0] = " ";
				$mtx[$t][$b][1][1] = "Q1";
				$mtx[$t][$b][1][2] = "Q2";
				$mtx[$t][$b][1][3] = "S1";
				$mtx[$t][$b][1][4] = "Q3";
				$mtx[$t][$b][1][5] = "Q4";
				$mtx[$t][$b][1][6] = "S2";
				$mtx[$t][$b][1][7] = "Total";
				$mtx[$t][$b][2][0] = "Target ".$year;
				$mtx[$t][$b][3][0] = "BKGS ".$year;
				$mtx[$t][$b][4][0] = "Abs. Var.";
				$mtx[$t][$b][5][0] = "Var(%)";
			}

		}
		//var_dump($mtx[0]);

		for ($t=0; $t < sizeof($mtx); $t++) { 
			for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
				for ($v=2; $v < sizeof($mtx[$t][$b]); $v++) { 
					for ($v2=1; $v2 < 8; $v2++) {
						$mtx[$t][$b][$v][$v2] = 0;	
					}
				}	
			}
			//var_dump($mtx[$t]);
		}
		
		//var_dump($values);
		//var_dump($tiers);
		//pegando valores das linhas 1 e 2, da matriz, menos do total
		for ($t=0; $t < sizeof($newValues); $t++) { 
			for ($b=0; $b < sizeof($newValues[$t]); $b++) { 
				for ($m=0; $m < sizeof($newValues[$t][$b]); $m++) { 
					for ($s=0; $s < sizeof($newValues[$t][$b][$m]); $s++) {
						if ($m == 0 || $m == 1 || $m == 2) {
							$v = 1;
							
							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][2][$v] += 0;
							}else{
								$mtx[$t][$b][2][$v] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][3][$v] += 0;
							}else{
								$mtx[$t][$b][3][$v] += $newValues[$t][$b][$m][$s];	
							}
						}elseif ($m == 3 || $m == 4 || $m == 5) {
							$v = 2;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][2][$v] += 0;
							}else{
								$mtx[$t][$b][2][$v] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][3][$v] += 0;
							}else{
								$mtx[$t][$b][3][$v] += $newValues[$t][$b][$m][$s];	
							}

							$v = 3;

							$mtx[$t][$b][2][$v] = $mtx[$t][$b][2][$v-1] + $mtx[$t][$b][2][$v-2];
							$mtx[$t][$b][3][$v] = $mtx[$t][$b][3][$v-1] + $mtx[$t][$b][3][$v-2];

						}elseif ($m == 6 || $m == 7 || $m == 8) {
							$v = 4;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][2][$v] += 0;
							}else{
								$mtx[$t][$b][2][$v] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][3][$v] += 0;
							}else{
								$mtx[$t][$b][3][$v] += $newValues[$t][$b][$m][$s];	
							}
						}else{
							$v = 5;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][2][$v] += 0;
							}else{
								$mtx[$t][$b][2][$v] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][3][$v] += 0;
							}else{
								$mtx[$t][$b][3][$v] += $newValues[$t][$b][$m][$s];	
							}

							$v = 6;

							$mtx[$t][$b][2][$v] = $mtx[$t][$b][2][$v-1] + $mtx[$t][$b][2][$v-2];
							$mtx[$t][$b][3][$v] = $mtx[$t][$b][3][$v-1] + $mtx[$t][$b][3][$v-2];
						}
					}
				}
			}

			//var_dump($mtx[$t]);
		}

		//var_dump($mtx[1]);
		for ($t=0; $t < sizeof($mtx); $t++) { 
			for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
				for ($c=2; $c < sizeof($mtx[$t][$b]); $c++) { 
					for ($v=1; $v < sizeof($mtx[$t][$b][$c]); $v++) {
						if ($c == 4) {
							$mtx[$t][$b][$c][$v] = $mtx[$t][$b][$c-1][$v] - $mtx[$t][$b][$c-2][$v];
						}elseif ($c == 5) {
							if ($mtx[$t][$b][$c-3][$v] != 0) {
								$mtx[$t][$b][$c][$v] = ($mtx[$t][$b][$c-2][$v] / $mtx[$t][$b][$c-3][$v])*100;
							}else{
								$mtx[$t][$b][$c][$v] = 0;
							}
						}elseif ($v == 7 && ($c == 2 || $c == 3)) {
							$mtx[$t][$b][$c][$v] = $mtx[$t][$b][$c][$v-1] + $mtx[$t][$b][$c][$v-4];
						}else {
							
						}
					}
				}	
			}

			//var_dump($mtx[$t]);
		}

		return array($mtx, $auxTiers);
	}

	public function assemblerDN($mtx, $year){
		
		//var_dump($mtx[0]);

		$mtxFinal[0][0][0] = "DN";
		$mtxFinal[0][1][0] = " ";
		$mtxFinal[0][1][1] = "Q1";
		$mtxFinal[0][1][2] = "Q2";
		$mtxFinal[0][1][3] = "S1";
		$mtxFinal[0][1][4] = "Q3";
		$mtxFinal[0][1][5] = "Q4";
		$mtxFinal[0][1][6] = "S2";
		$mtxFinal[0][1][7] = "Total";
		$mtxFinal[0][2][0] = "Target ".$year;
		$mtxFinal[0][3][0] = "BKGS ".$year;
		$mtxFinal[0][4][0] = "Abs. Var.";
		$mtxFinal[0][5][0] = "Var(%)";

		for ($c=2; $c < sizeof($mtxFinal[0]); $c++) { 
			for ($v=1; $v < 8; $v++) { 
				$mtxFinal[0][$c][$v] = 0;
			}
		}
		//var_dump($mtx[1][0]);
		for ($t=0; $t < sizeof($mtx); $t++) { 
			for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
				for ($c=2; $c < (sizeof($mtx[$t][$b])-2); $c++) { 
					for ($v=1; $v < sizeof($mtx[$t][$b][$c]); $v++) { 
						$mtxFinal[0][$c][$v] += $mtx[$t][$b][$c][$v];
					}
				}
			}
		}

		for ($c=4; $c < sizeof($mtxFinal[0]); $c++) { 
			for ($v=1; $v < sizeof($mtxFinal[0][$c]); $v++) { 
				if ($c == 5) {
					if ($mtxFinal[0][$c-3][$v] != 0) {
						$mtxFinal[0][$c][$v] = ($mtxFinal[0][$c-2][$v] / $mtxFinal[0][$c-3][$v])*100;
					}else{
						$mtxFinal[0][$c][$v] = 0;
					}
				}else{
					$mtxFinal[0][$c][$v] = $mtxFinal[0][$c-1][$v] - $mtxFinal[0][$c-2][$v];
				}
			}
		}

		return $mtxFinal;

	}

}
