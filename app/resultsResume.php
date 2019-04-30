<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\results;


class resultsResume extends results{
    
	public function assembler($month,$sales,$actual,$target,$pAndR,$finance,$pYear){

		$matrix = array();

		$salesSum = 0.0;
		$actualSum = 0.0;
		$targetSum = 0.0;
		$pAndRSum = 0.0;
		$financeSum = 0.0;
		$pYearSum = 0.0;

		for ($i=0; $i < sizeof($month); $i++) { 
			$matrix[$i]['month'] = $month[$i][0];
			$matrix[$i]['sales'] = $sales[$i];
			$matrix[$i]['actual'] = $actual[$i];
			$matrix[$i]['target'] = $target[$i];
			$matrix[$i]['pAndR'] = $pAndR[$i];
			$matrix[$i]['finance'] = $finance[$i];
			$matrix[$i]['pYear'] = $pYear[$i];

			$salesSum += $sales[$i];
			$actualSum += $actual[$i];
			$targetSum += $target[$i];
			$pAndRSum += $pAndR[$i];
			$financeSum += $finance[$i];
			$pYearSum += $pYear[$i];

			if($matrix[$i]['target'] > 0){
				$matrix[$i]['salesOverTarget'] = $matrix[$i]['sales']/$matrix[$i]['target'];
			}else{
				$matrix[$i]['salesOverTarget'] = 0.0;
			}

			if($matrix[$i]['pAndR'] > 0){
				$matrix[$i]['salesOverPAndR'] = $matrix[$i]['sales']/$matrix[$i]['pAndR'];
			}else{
				$matrix[$i]['salesOverPAndR'] = 0.0;
			}

			if($matrix[$i]['finance'] > 0){
				$matrix[$i]['salesOverFinance'] = $matrix[$i]['sales']/$matrix[$i]['finance'];
			}else{
				$matrix[$i]['salesOverFinance'] = 0.0;
			}

			if($matrix[$i]['pYear'] > 0){
				$matrix[$i]['salesYoY'] = $matrix[$i]['sales']/$matrix[$i]['pYear'];
			}else{
				$matrix[$i]['salesYoY'] = 0.0;
			}
		}

		$last = $i;

		$matrix[$last]['month'] =  'Total';
		$matrix[$last]['sales'] =  $salesSum;
		$matrix[$last]['actual'] = $actualSum;
		$matrix[$last]['target'] = $targetSum;
		$matrix[$last]['pAndR'] =  $pAndRSum;
		$matrix[$last]['finance'] =  $financeSum;
		$matrix[$last]['pYear'] =  $pYearSum;

		if($matrix[$last]['target'] > 0){
			$matrix[$last]['salesOverTarget'] = $matrix[$last]['sales']/$matrix[$last]['target'];
		}else{
			$matrix[$last]['salesOverTarget'] = 0.0;
		}

		if($matrix[$last]['pAndR'] > 0){
			$matrix[$last]['salesOverPAndR'] = $matrix[$last]['sales']/$matrix[$last]['pAndR'];
		}else{
			$matrix[$last]['salesOverPAndR'] = 0.0;
		}

		if($matrix[$last]['finance'] > 0){
			$matrix[$last]['salesOverFinance'] = $matrix[$last]['sales']/$matrix[$last]['finance'];
		}else{
			$matrix[$last]['salesOverFinance'] = 0.0;
		}

		if($matrix[$last]['pYear'] > 0){
			$matrix[$last]['salesYoY'] = $matrix[$last]['sales']/$matrix[$last]['pYear'];
		}else{
			$matrix[$last]['salesYoY'] = 0.0;
		}


		return ($matrix);


	}

}
