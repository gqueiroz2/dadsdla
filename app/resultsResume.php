<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\results;

class resultsResume extends results{
    
	public function divideBrands($brands){
		
		$brandsTV = array();
		$brandsDigital = array();

		for ($b=0; $b < sizeof($brands); $b++) { 
			
			if ($brands[$b][1] == "ONL" || $brands[$b][1] == "VIX") {
				array_push($brandsDigital, $brands[$b]);
			}else{
				array_push($brandsTV, $brands[$b]);
			}

		}

		return array($brandsTV, $brandsDigital);
	}

	public function generateVectorsTV($con, $brands, $months, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion){
		$joinSales = false;
		$joinTarget = false;
		$joinActual = false;
		$joinCorporate = false;

		$tableSales = $this->salesTable($regionID,$cYear);

		$tableTarget = "plan_by_brand";
		$tableActual = $tableTarget;
		$tableCorporate = $tableActual;

		$tr = strtoupper($value);

		for ($m=0; $m < sizeof($months); $m++) { 
			for ($b=0; $b < sizeof($brands); $b++) { 
				// SE FOR CMAPS
	            if($salesRegion == 'Brazil'){
		            $whereSales[$m][$b] = "WHERE (cmaps.month IN (".$months[$m][1].") ) 
		                               AND (cmaps.year IN ($cYear) )
		                               AND (cmaps.brand_id IN (".$brands[$b][0].") )
		                              ";
		            $whereSalesPYear[$m][$b] = "WHERE (ytd.month IN (".$months[$m][1].") ) 
		                                    AND ( ytd.year IN ($pYear) )
		                                    AND (ytd.sales_representant_office_id IN (".$regionID.") )
		                                    AND (ytd.brand_id IN (".$brands[$b][0].") )
		                                   ";
		        }else{
		       	//SE FOR IBMS / BTS
		        	$whereSales[$m][$b] = "WHERE (ytd.month IN (".$months[$m][1].") ) 
		        	                   AND (ytd.year IN ($cYear) )
		        	                   AND (ytd.sales_representant_office_id IN (".$regionID.") )
		        	                   AND (ytd.brand_id IN (".$brands[$b][0].") )";

			        $whereSalesPYear[$m][$b] = "WHERE (ytd.month IN (".$months[$m][1].") ) 
			                                    AND ( ytd.year IN ($pYear) )
			                                    AND (ytd.sales_representant_office_id IN (".$regionID.") )
			                                    AND (ytd.brand_id IN (".$brands[$b][0].") )";	
		    	}
			}
		}

		for ($m=0; $m < sizeof($months); $m++) { 
        	for ($b=0; $b < sizeof($brands); $b++) { 

        		
					$whereTarget[$m][$b] = "WHERE (plan_by_brand.month IN (".$months[$m][1].")) 
            					   AND (source  = \"TARGET\")
            					   AND (year = $cYear)
                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")
                                   AND (sales_office_id = \"".$regionID."\")
                                   AND (currency_id = 4 )
                                   AND (brand_id = \"".$brands[$b][0]."\" )
                               ";

		            $whereActual[$m][$b] = "WHERE ( plan_by_brand.month IN (".$months[$m][1].") ) 
		            					   AND (source  = \"ACTUAL\" )
		            					   AND (year = $cYear)
		                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")
		                                   AND (sales_office_id = \"".$regionID."\")
		                                   AND (currency_id = 4 )
		                                   AND (brand_id = \"".$brands[$b][0]."\" )
		                               ";

		            $whereCorporate[$m][$b] = "WHERE ( plan_by_brand.month IN (".$months[$m][1].") ) 
		            					   AND (source  = \"CORPORATE\" )
		            					   AND (year = $cYear)
		                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")
		                                   AND (sales_office_id = \"".$regionID."\")  
		                                   AND (currency_id = 4 )
		                                   AND (brand_id = \"".$brands[$b][0]."\" )  
		                                   ";
				
        	}
        }

        $salesCYear = $this->generateVector($con,$tableSales,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinSales,$whereSales);
		$target = $this->generateVector($con,$tableTarget,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinTarget,$whereTarget);
		$actual = $this->generateVector($con,$tableActual,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinActual,$whereActual);
		$corporate = $this->generateVector($con,$tableCorporate,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinCorporate,$whereCorporate);

		if($tableSales == "cmaps"){
			$tableSales = 'ytd';
		}

		$previousYear = $this->generateVector($con,$tableSales,$regionID,$pYear,$months,$brands,$currencyID,$value,$joinSales,$whereSalesPYear);

		$mtx["salesCYear"] = $salesCYear;
		$mtx["target"] = $target;
		$mtx["actual"] = $actual;
		$mtx["corporate"] = $corporate;
		$mtx["previousYear"] = $previousYear;

		return $mtx;
	}
	
	public function generateVectorDigital($con, $brands, $months, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion){
		
		$joinSales = false;
		$joinTarget = false;
		$joinActual = false;
		$joinCorporate = false;

		$tableSales = "fw_digital";//"digital";
		$tableTarget = "plan_by_brand";
		$tableActual = $tableTarget;
		$tableCorporate = $tableActual;

		$tr = strtoupper($value);

		for ($m=0; $m < sizeof($months); $m++) { 
			for ($b=0; $b < sizeof($brands); $b++) { 
				
				if ($brands[$b][1] == 'ONL') {
					$whereSales[$m][$b] = "WHERE ( month = \"".$months[$m][1]."\" ) 
	            					   AND ( year =  \" $cYear \")
	                                   AND (region_id = \"".$regionID."\")
	                                   AND (brand_id != '10')";
	                $whereSalesPYear[$m][$b] = "WHERE ( month = \"".$months[$m][1]."\" ) 
	            					   AND ( year =  \" $pYear \")
	                                   AND (region_id = \"".$regionID."\")
	                                   AND (brand_id != '10')";
				}else{
					$whereSales[$m][$b] = "WHERE ( month = \"".$months[$m][1]."\" ) 
	            					   AND ( year =  \" $cYear \")
	                                   AND (region_id = \"".$regionID."\")
	                                   AND (brand_id = \"".$brands[$b][0]."\" )";
	                $whereSalesPYear[$m][$b] = "WHERE ( month = \"".$months[$m][1]."\" ) 
	            					   AND ( year =  \" $pYear \")
	                                   AND (region_id = \"".$regionID."\")
	                                   AND (brand_id = \"".$brands[$b][0]."\" )";

	            }
/*
				"WHERE (digital.month IN (".$months[$m][1]."))
										  AND (digital.year IN ($cYear))
										  AND (digital.brand_id IN (".$brands[$b][0]."))";

				$whereSalesPYear[$m][$b] = "WHERE ( plan_by_brand.month IN (".$months[$m][1].") ) 
									   AND ( year =  \" $pYear \")
	            					   AND ( source  = \"ACTUAL\" )
	                                   AND ( type_of_revenue = \"".$tr."\" )
	                                   AND (sales_office_id = \"".$regionID."\")
	                                   AND (currency_id = 4 )
	                                   AND (brand_id = \"".$brands[$b][0]."\" )
	                               ";
/*
				"WHERE (digital.month IN (".$months[$m][1]."))
												AND (digital.year IN ($cYear))
												AND (digital.brand_id IN (".$brands[$b][0]."))";

*/
			}
		}

		$tr = strtoupper($value);

		for ($m=0; $m < sizeof($months); $m++) { 
        	for ($b=0; $b < sizeof($brands); $b++) { 
        		$whereTarget[$m][$b] = "WHERE (plan_by_brand.month IN (".$months[$m][1].")) 
            					   AND (source  = \"TARGET\")
            					   AND (year = $cYear)
                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")
                                   AND (sales_office_id = \"".$regionID."\")
                                   AND (currency_id = 4 )
                                   AND (brand_id = \"".$brands[$b][0]."\" )
                               ";

	            $whereActual[$m][$b] = "WHERE ( plan_by_brand.month IN (".$months[$m][1].") ) 
	            					   AND ( source  = \"ACTUAL\" )
	            					   AND (year = $cYear)
	                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")
	                                   AND (sales_office_id = \"".$regionID."\")
	                                   AND (currency_id = 4 )
	                                   AND (brand_id = \"".$brands[$b][0]."\" )
	                               ";

	            $whereCorporate[$m][$b] = "WHERE ( plan_by_brand.month IN (".$months[$m][1].") ) 
	            					   AND ( source  = \"CORPORATE\" )
	            					   AND (year = $cYear)
	                                   AND (type_of_revenue = \"".$tr."\" OR type_of_revenue = \"".$tr." REVENUE\")z
	                                   AND (sales_office_id = \"".$regionID."\")  
	                                   AND (currency_id = 4 )
	                                   AND (brand_id = \"".$brands[$b][0]."\" )  
	                                   ";
        	}
        }

        $salesCYear = $this->generateVector($con,$tableSales,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinSales,$whereSales);
		$target = $this->generateVector($con,$tableTarget,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinTarget,$whereTarget);
		$actual = $this->generateVector($con,$tableActual,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinActual,$whereActual);
		$corporate = $this->generateVector($con,$tableCorporate,$regionID,$cYear,$months,$brands,$currencyID,$value,$joinCorporate,$whereCorporate);
		
		if($tableSales == "cmaps"){
			$tableSales = 'ytd';
		}

		$previousYear = $this->generateVector($con,$tableSales,$regionID,$pYear,$months,$brands,$currencyID,$value,$joinSales,$whereSalesPYear);

		$mtx["salesCYear"] = $salesCYear;
		$mtx["target"] = $target;
		$mtx["actual"] = $actual;
		$mtx["corporate"] = $corporate;
		$mtx["previousYear"] = $previousYear;

		return $mtx;
	}

	public function grouper($tv, $digital){

		$DN = array();
		
		if (is_null($tv)) {
			for ($i=0; $i <sizeof($digital["salesCYear"]) ; $i++) { 
				$DN["salesCYear"][$i] = $digital["salesCYear"][$i];
			}

			for ($i=0; $i <sizeof($digital["actual"]) ; $i++) { 
				$DN["actual"][$i] = $digital["actual"][$i];
			}

			for ($i=0; $i <sizeof($digital["target"]) ; $i++) { 
				$DN["target"][$i] = $digital["target"][$i];
			}

			for ($i=0; $i <sizeof($digital["corporate"]) ; $i++) { 
				$DN["corporate"][$i] = $digital["corporate"][$i];
			}

			for ($i=0; $i <sizeof($digital["previousYear"]) ; $i++) { 
				$DN["previousYear"][$i] = $digital["previousYear"][$i];
			}
		}elseif (is_null($digital)) {
			for ($i=0; $i <sizeof($tv["salesCYear"]) ; $i++) { 
				$DN["salesCYear"][$i] = $tv["salesCYear"][$i];
			}

			for ($i=0; $i <sizeof($tv["actual"]) ; $i++) { 
				$DN["actual"][$i] = $tv["actual"][$i];
			}

			for ($i=0; $i <sizeof($tv["target"]) ; $i++) { 
				$DN["target"][$i] = $tv["target"][$i];
			}

			for ($i=0; $i <sizeof($tv["corporate"]) ; $i++) { 
				$DN["corporate"][$i] = $tv["corporate"][$i];
			}

			for ($i=0; $i <sizeof($tv["previousYear"]) ; $i++) { 
				$DN["previousYear"][$i] = $tv["previousYear"][$i];
			}
		}else{
			for ($i=0; $i <sizeof($digital["salesCYear"]) ; $i++) { 
				$DN["salesCYear"][$i] = $digital["salesCYear"][$i] + $tv["salesCYear"][$i];
			}

			for ($i=0; $i <sizeof($digital["actual"]) ; $i++) { 
				$DN["actual"][$i] = $digital["actual"][$i] + $tv["actual"][$i];
			}

			for ($i=0; $i <sizeof($digital["target"]) ; $i++) { 
				$DN["target"][$i] = $digital["target"][$i] + $tv["target"][$i];
			}

			for ($i=0; $i <sizeof($digital["corporate"]) ; $i++) { 
				$DN["corporate"][$i] = $digital["corporate"][$i] + $tv["corporate"][$i];
			}

			for ($i=0; $i <sizeof($digital["previousYear"]) ; $i++) { 
				$DN["previousYear"][$i] = $digital["previousYear"][$i] + $tv["previousYear"][$i];
			}
		}

		return $DN;
	}

	public function assembler($month,$sales,$actual,$target,$corporate/*$pAndR,$finance*/,$pYear){
		$matrix = array();
		$salesSum = 0.0;
		$actualSum = 0.0;
		$targetSum = 0.0;
		$corporateSum = 0.0;
		/*
		$pAndRSum = 0.0;
		$financeSum = 0.0;
		*/
		$pYearSum = 0.0;
		for ($i=0; $i < sizeof($month); $i++) { 
			$matrix[$i]['month'] = $month[$i][0];
			$matrix[$i]['sales'] = $sales[$i];
			$matrix[$i]['actual'] = $actual[$i];
			$matrix[$i]['target'] = $target[$i];
			$matrix[$i]['corporate'] = $corporate[$i];
			/*
			$matrix[$i]['pAndR'] = $pAndR[$i];
			$matrix[$i]['finance'] = $finance[$i];
			*/
			$matrix[$i]['pYear'] = $pYear[$i];
			$salesSum += $sales[$i];
			$actualSum += $actual[$i];
			$targetSum += $target[$i];
			$corporateSum += $corporate[$i];
			/*
			$pAndRSum += $pAndR[$i];
			$financeSum += $finance[$i];
			*/
			$pYearSum += $pYear[$i];
			if($matrix[$i]['target'] > 0){
				$matrix[$i]['salesOverTarget'] = ($matrix[$i]['sales']/$matrix[$i]['target'])*100;
			}else{
				$matrix[$i]['salesOverTarget'] = 0.0;
			}
			if($matrix[$i]['corporate'] > 0){
				$matrix[$i]['salesOverCorporate'] = ($matrix[$i]['sales']/$matrix[$i]['corporate'])*100;
			}else{
				$matrix[$i]['salesOverCorporate'] = 0.0;
			}
			/*
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
			*/
			if($matrix[$i]['pYear'] > 0){
				$matrix[$i]['salesYoY'] = ($matrix[$i]['sales']/$matrix[$i]['pYear'])*100;
			}else{
				$matrix[$i]['salesYoY'] = 0.0;
			}
		}
		$last = $i;
		$matrix[$last]['month'] =  'Total';
		$matrix[$last]['sales'] =  $salesSum;
		$matrix[$last]['actual'] = $actualSum;
		$matrix[$last]['target'] = $targetSum;
		$matrix[$last]['corporate'] = $corporateSum;
		/*
		$matrix[$last]['pAndR'] =  $pAndRSum;
		$matrix[$last]['finance'] =  $financeSum;
		*/
		$matrix[$last]['pYear'] =  $pYearSum;
		if($matrix[$last]['target'] > 0){
			$matrix[$last]['salesOverTarget'] = ($matrix[$last]['sales']/$matrix[$last]['target'])*100;
		}else{
			$matrix[$last]['salesOverTarget'] = 0.0;
		}
		if($matrix[$last]['corporate'] > 0){
			$matrix[$last]['salesOverCorporate'] = ($matrix[$last]['sales']/$matrix[$last]['corporate'])*100;
		}else{
			$matrix[$last]['salesOverCorporate'] = 0.0;
		}
		/*
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
		*/
		if($matrix[$last]['pYear'] > 0){
			$matrix[$last]['salesYoY'] = ($matrix[$last]['sales']/$matrix[$last]['pYear'])*100;
		}else{
			$matrix[$last]['salesYoY'] = 0.0;
		}
		return ($matrix);
	}

	

}
