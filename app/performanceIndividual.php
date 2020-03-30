<?php

namespace App;

use App\performance;
use App\region;
use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use App\dataBase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class performanceIndividual extends performance{


	public function matrix($con,$region,$year,$tier,$salesRep,$salesRepGroup,$currency,$month,$value){
		$p = new pRate();
		$sql = new sql();
		$base = new base();
		$sr = new salesRep();

		if ($region == '6' || $region == '7') {
			array_push($salesRep, '15');
		}elseif ($region == '9') {
			array_push($salesRep, '102');
		}elseif ($region = '10') {
			array_push($salesRep, '103');
		}elseif ($region == '11') {
			array_push($salesRep,'45');	
		}elseif ($region == '12') {
			array_push($salesRep, '104');
		}elseif ($region == '13') {
			array_push($salesRep, '105');
		}

		for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                if (  ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") && $year < 2020 ) {
                    $table[$b][$m] = "fw_digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }

        $saleGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);

        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
        	if ($salesRep[$s]['salesRep'] == 'Martin Hernandez' && $region == '6'){
        		$salesRep[$s]['salesRepGroup'] = 'Chile';
        	}elseif ($salesRep[$s]['salesRep'] == 'Martin Hernandez' && $region == '7'){
        		$salesRep[$s]['salesRepGroup'] = 'Peru';
        	}elseif ($salesRep[$s]['salesRep'] == 'Jesse Leon' && $region == '11') {
        		$salesRep[$s]['salesRepGroup'] = 'NY International';
        	}
        }

        for ($t=0; $t <sizeof($table); $t++) { 
        	for ($v=0; $v <sizeof($table[$t]); $v++) { 
        		$values[$t][$v] = $this->generateValue($con,$sql,$region,$year,$brand[$t],$salesRep,$month[$v],$sum[$t][$v],$table[$t][$v]);
        		$planValues[$t][$v] = $this->generateValue($con,$sql,$region,$year,$brand[$t],$salesRep,$month[$v],'value','plan_by_sales',$value);
        	}
        }

        $mtx = $this->assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier,$currency,$div,$divDig);

        return $mtx;
	}

	public function assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier,$currency,$div,$divDig){
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$sql = new sql();
		$base = new base();

		$tmp1['values'] = array();
		$tmp1['planValues'] = array();
		$tmp2['values'] = array();
		$tmp2['planValues'] = array();

		for ($b=0; $b <sizeof($brand); $b++) { 
			for ($m=0; $m <sizeof($month); $m++) { 
				for ($s=0; $s <sizeof($salesRep); $s++) { 
					$temp[$s][$b][$m] = 0;
					$temp_2[$s][$b][$m] = 0;
				}
			}
		}

		for ($b=0; $b < sizeof($brand); $b++) { 
			if ($brand[$b][1] == 'ONL' || $brand[$b][1] == 'VIX') {
				for ($m=0; $m <sizeof($month); $m++) { 
					for ($s=0; $s <sizeof($salesRep); $s++) { 
						$tmp[$s][$b][$m] = $values[$b][$m][$s]*$divDig;
						$temp_2[$s][$b][$m] = $planValues[$b][$m][$s]*$divDig;
					}
				}
			}else{
				for ($m=0; $m <sizeof($month); $m++) { 
					for ($s=0; $s <sizeof($salesRep); $s++) { 
						$tmp[$s][$b][$m] = $values[$b][$m][$s]*$div;
						$tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]*$div;
					}
				}
			}
		}

		

	}
}