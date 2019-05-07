<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\results;
use App\sql;
use App\brand;
use App\ytd;
use App\pRate;
use App\planByBrand;
use App\mini_header;
use App\digital;

class resultsMonthlyYoY extends results{
    
	public function cols($con, $brands, $region, $year, $currency, $value, $form, $source){

		$digital = false;

        if (sizeof($brands) == 0) {
            $cols = false;
        }else{
            for ($i=1; $i <= 12; $i++) {
        		for ($j=1; $j <= 3; $j++) { 

	    			$cols[$i-1][$j-1] = $this->col($con, $currency, $brands, $region, $year,
	                            $value, $form, $j, $source, $i, sizeof($brands));	
            	}
                
            }
        }

        return $cols;

    }

    

    public function col($con, $currency, $brands, $region, $year, $value, $form,
    					$colNumber, $source, $month, $numBrands){
    	
    	for ($i=0; $i < $numBrands; $i++) { 
    		
    		if ($brands[$i] == "9" || $brands[$i] == "10") {
                $finalForm = 'Digital';
            }else{
                $finalForm = $form;
            }

    		switch ($colNumber) {
	    		case 1:
	    			$res = $this->createObject($finalForm, $value);
	    			$formResp = $res[0];

	    			if (!is_null($formResp)) {
	    				
	    				$columns = $res[1];

	    				if ($columns) {
	    					
	    					if (sizeof($columns) == 4) {
	    						
								$colValues = array($region, ($year-1), $month, $brands[$i]);
	                        	$col[$i] = $this->colValues($con,$currency, $res[2], $formResp, $columns, $colValues, $region, $year, $brands);
	    						
	    					} elseif (sizeof($columns) == 3) {

								$colValues = array(($year-1), $month, $brands[$i]);
	                        	$col[$i] = $this->colValues($con,$currency, $res[2], $formResp, $columns, $colValues, $region, $year, $brands);
	                            
	                        }else{
	                            $col[$i] = false;
	                        }
	    				}else{
	    					$col[$i] = false;
	    				}

	    			}

	    			break;

	    		case 2:
	    			$columns = array("sales_office_id", "source", "type_of_revenue", "year", "month", "brand_id");
	                $colValues = array($region, $source, strtoupper($value), $year, $month, $brands[$i]);
	                
	                $p = new planByBrand();

	                $col[$i] = $this->colValues($con,$currency, "revenue", $p, $columns, $colValues, $region, $year, $brands);

	                break;

	            case 3:
	                	$res = $this->createObject($finalForm, $value);
		    			$formResp = $res[0];

		    			if (!is_null($formResp)) {
		    				
		    				$columns = $res[1];

		    				if ($columns) {
		    					
		    					if (sizeof($columns) == 4) {
		    						
									$colValues = array($region, $year, $month, $brands[$i]);
		                        	$col[$i] = $this->colValues($con,$currency, $res[2], $formResp, $columns, $colValues,
		                            						    $region, $year, $brands);
		    						
		    					} elseif (sizeof($columns) == 3) {

									$colValues = array($year, $month, $brands[$i]);
		                        	$col[$i] = $this->colValues($con,$currency, $res[2], $formResp, $columns, $colValues,
		                         						    $region, $year, $brands);
		                            
		                        }else{
		                            $col[$i] = false;
		                        }
		    				}else{
		    					$col[$i] = false;
		    				}

		    			}

	                	break;

	    		default:
	    			$col[$i] = false;
	    			break;
	    	}

    	}
    	

    	return $col;

    }

    public function colValues($con, $currency, $value, $form, $columns, $colValues, $region, $year, $brands){
    	
    	$r = new region();
        $p = new pRate();

        if($currency == "USD"){            
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
        }else{
            $pRate = 1.0;
        }

    	$formValue = $form->sum($con, $value, $columns, $colValues)['sum'];

    	if ($formValue == 0) {
            $formValue = 0;
        }else{
            $formValue = $formValue/$pRate;
        }
        
        return $formValue;
    }

    public function createObject($form, $value){
        
        $obj = null;
        $columns = array();

        switch ($form) {
            case 'IBMS':
                $obj = new ytd();

                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;

            case 'ytd':
                $obj = new ytd();

                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;
            
            case 'CMAPS':
                $obj = new cmaps();

                $columns = array("year", "month", "brand_id");

                break;

            case 'cmaps':
                $obj = new cmaps();

                $columns = array("year", "month", "brand_id");

                break;

            case 'Header':
                $obj = new mini_header();

                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;

            case 'mini_header':
                $obj = new mini_header();

                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;

            case 'Digital':
                $obj = new digital();
                
                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;

            case 'digital':
                $obj = new digital();

                $columns = array("campaign_sales_office_id", "year", "month", "brand_id");
                $value .= "_revenue";

                break;

            default:
                $obj = false;

                break;
        }

        return array($obj, $columns, $value);

    }

    public function assemblers($months, $year, $brands, $cols){
    	
    	$pos = 1;

    	for ($i=0; $i < sizeof($months); $i++) {
            for ($j=0; $j < sizeof($brands); $j++) { 
                $matrix[$i] = $this->assembler($brands[$j], $j, $cols, $i, $pos);
                $pos++; 

                if ($i == 2 || $i == 5 || $i == 8 || $i == 11) {
                   $quarter[$q] = $this->assemblerQuarter($matrix, $brands);
                    $q++;
                }
            }

    		$pos = 1;

            if ($size > 1) {
                
                for ($i=0; $i < 3; $i++) { 
                    $valuesDN[$i] = 0;
                }

                for ($k=0; $k < 3; $k++) { 
                    for ($c=1; $c <= sizeof($brands); $c++) { 
                        $valuesDN[$k] += $matrix[$i][$c][$k]; 
                    }
                }

                $matrix[$i][sizeof($matrix[$i][$c])][0] += $valuesDN[0];
                $matrix[$i][sizeof($matrix[$i][$c])][1] += $valuesDN[1];
                $matrix[$i][sizeof($matrix[$i][$c])][2] += $valuesDN[2];
            }

    	}

    	/*if (sizeof($brands) > 1) {
			$matrixDn = $this->assemblerDN($matrix, $months, $brands);
			$quarterDn = $this->assemblerDNQuarter($quarter, $brands);

    	}*/

        //var_dump($matrix);
    	return $matrix;
    }

    public function assembler($brand, $brandPos, $cols, $month, $pos){

        $matrix[$month][0][$brandPos] = $brand;

    	for ($j = 0; $j < 3; $j++) {
    		$matrix[$month][$pos][$j] = $cols[$month][$j][$pos-1];

    	}

        return $matrix;

    }

    public function assemblerQuarter($matrix, $brands){

    	for ($i=0; $i < sizeof($brands); $i++) { 
    		$quarter[$i][0] = 0;
    		$quarter[$i][1] = 0;
    		$quarter[$i][2] = 0;
    	}

        for ($i = 0; $i < 3; $i++) { 
        	for ($k = 0; $k < sizeof($brands); $k++) {
        		for ($j = 0; $j < 3; $j++) { 
        		 	$quarter[$k][$j] += $matrix[$i][$k][$j];
        		 } 
        		
        	}
        }

   		return $quarter;

    }

    

    public function assemblerDNQuarter($quarter, $brands){
    	
    	for ($i=0; $i < 4; $i++) { 
    		for ($j=0; $j < 3; $j++) { 
    			$currentQuarter[$i][$j] = 0;
    		}
    	}

    	//var_dump($quarter);

    	for ($i = 0; $i < 4; $i++) { 
    		for ($j = 0; $j < 3; $j++) { 
    			for ($k = 0; $k < sizeof($brands); $k++) { 
    				$currentQuarter[$i][$j] += $quarter[$i][$k][$j];
    			}
    		}
    	}
    	//var_dump($currentQuarter);
    	return $currentQuarter;
    }
}
