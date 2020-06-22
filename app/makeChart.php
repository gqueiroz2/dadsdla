<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dashboards;
use App\subRankings;
class makeChart extends Model{
    
	public function bvBrand($values,$year){
		$string = "[['Client','Revenue $year'],";
		
		for ($v=0; $v < sizeof($values); $v++) { 
			$string .= " ['".$values[$v]['brand']."',";			
			$string .= " ".$values[$v]['value'].",";
			$string .= " ], ";
		}

		$string .= "]";
		
		return $string;
	}

    public function bvChild($values,$year){
    	
		$string = "[['Client','Investimento $year',{ role: 'style' }],";
		
		for ($v=0; $v < sizeof($values); $v++) { 
			$string .= " ['".$values[$v]['client']."',";			
			$string .= " ".$values[$v]['total'].",";
			$string .= "'blue'";
			$string .= " ], ";
		}

		$string .= "]";

		return $string;
		
	}

	public function bvMonth($values,$year){
		$dash = new dashboards();
        $months = $dash->getMonths();
        $monthsFN = $dash->getMonthsMidName();

		$string = "['Month','Revenue $year'],";

		for ($m=0; $m < 12; $m++) { 
			$string .= " ['".$monthsFN[$m]."',";			
			$string .= " ".$values[$m]['value']."";
			$string .= " ], ";
		}

		return $string;

	}

	public function overviewMonth($con,$type,$l3M,$years){
		$dash = new dashboards();
        $months = $dash->getMonths();
        $monthsFN = $dash->getMonthsFullName();

		$string = "['Month','".$years[0]."','".$years[1]."','".$years[2]."'],";

		for ($m=0; $m < 12; $m++) { 
			$string .= " ['".$monthsFN[$m]."', ";
			for ($y=0; $y < sizeof($years); $y++) { 

				$string .= " ".$l3M[$y][$m].", ";

			}
			$string .= " ], ";
		}

		return $string;

	}

	public function overviewChild($con,$type,$l3C,$years){
		$sr = new subRankings();

		if($type == "agency"){
			$smt = "client";
		}else{
			$smt = "agency";
		}

		$temp = $sr->assembler($l3C,$years,$type);
		$tmp = $temp[0];
		//unset para retirar  posições, e nome do grupo da agência
		unset($tmp[0]);
		unset($tmp[1]);
		unset($tmp[2]);
		unset($tmp[8]);
		unset($tmp[9]);
		$tmp = array_values($tmp);

		$pivot = $tmp[0];		
		$revCY = $tmp[1];
		$revPY = $tmp[2];
		$revPPY = $tmp[3];

		for ($l=0; $l < sizeof($pivot); $l++) { 
			$label[$l] = $pivot[$l];
			if($l == 0){
				$revC[$l] = $years[0];
				$revP[$l] = $years[1];
				$revPP[$l] = $years[2];
			}else{
				if(is_numeric($revCY[$l])){
					$revC[$l] = $revCY[$l];
				}else{
					$revC[$l] = 0.0;
				}

				if(is_numeric($revPY[$l])){
					$revP[$l] = $revPY[$l];
				}else{
					$revP[$l] = 0.0;
				}

				if(is_numeric($revPPY[$l])){
					$revPP[$l] = $revPPY[$l];
				}else{
					$revPP[$l] = 0.0;
				}
			}
		}

		$rtr = array("label" => $label, "cYear" => $revC, "pYear" => $revP, "ppYear" => $revPP);

		return $rtr;
		
	}

	public function overviewBrandColumn($con,$l3B,$years){

    	$dash = new dashboards();
    	$brands = $dash->getBrands($con);
    	$string = "['Channel','".$years[0]."','".$years[1]."','".$years[2]."'],";

    	$max = 0;

    	for ($b=0; $b < sizeof($brands); $b++) { 
	    	if( ($l3B[0][$b] > 0) || ($l3B[1][$b] > 0) || ($l3B[2][$b] > 0) ){
	    		$string .= "['".$brands[$b][1]."',";
	    		for ($y=0; $y < sizeof($years); $y++) { 
					
	    			if ($l3B[$y][$b] == 0) {
						$l3B[$y][$b] = -1;
					}else{
						$string .= " ".$l3B[$y][$b].", ";

						if($l3B[$y][$b] > $max){
							$max = $l3B[$y][$b];
						}
					}

				}

				$string .= " ], ";
			}
    	}

    	$rtr = array("string" => $string, "max" => $max);
    	
    	return $rtr;
    }

    public function overviewBrand($con,$l3B){

    	$dash = new dashboards();
    	$brands = $dash->getBrands($con);

    	for ($b=0; $b < sizeof($brands); $b++) { 
    		$chart[$b]['label'] = $brands[$b][1];
    		$chart[$b]['value'] = $l3B[$b];
    	}

    	return $chart;
    }
}
