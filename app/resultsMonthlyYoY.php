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
    	
        $size = sizeof($brands);

    	for ($i=0; $i < sizeof($months); $i++) {
            
            $matrix[$i] = $this->assembler($brands, $cols, $i);

            if ($size > 1) {
                
                $sizeBrands = $size+1;

                $value = $this->assemblerDN($i, $matrix[$i], $brands);
                $matrix[$i][sizeof($brands)][0] = $value[0];
                $matrix[$i][sizeof($brands)][1] = $value[1];
                $matrix[$i][sizeof($brands)][2] = $value[2];
            }else{
                $sizeBrands = $size;
            }

    	}

        $quarters[0] = $this->assemblerQuarter($matrix, 0, 2, $sizeBrands);
        $quarters[1] = $this->assemblerQuarter($matrix, 3, 5, $sizeBrands);
        $quarters[2] = $this->assemblerQuarter($matrix, 6, 8, $sizeBrands);
        $quarters[3] = $this->assemblerQuarter($matrix, 9, 11, $sizeBrands);

        //var_dump($quarters);
    	return array($matrix, $quarters);
    }

    public function assembler($brands, $cols, $month){

        for ($i=0; $i <= sizeof($brands); $i++) {
            
            $matrix[$i][0] = 0;
            $matrix[$i][1] = 0;
            $matrix[$i][2] = 0;
            
        }

        $pos = 0;
        //var_dump($matrix);

        for ($i=0; $i < 3; $i++) { 

            for ($j = 0; $j < sizeof($brands); $j++) {
                $matrix[$pos][$i] += $cols[$month][$i][$j];
                $pos++;   

            }

            $pos = 0;

        }
    	

        //var_dump($matrix);
        return $matrix;

    }

    public function assemblerDN($month, $matrix, $brands){
        
        for ($i=0; $i < 3; $i++) { 
            $valuesDN[$i] = 0;
        }
        //var_dump($matrix);
        for ($c=0; $c < 3; $c++) { 
            for ($k=0; $k < sizeof($brands); $k++) { 
                $valuesDN[$c] += $matrix[$k][$c]; 
            }
        }

        //var_dump($valuesDN);
        return $valuesDN;

    }

    public function assemblerQuarter($matrix, $min, $max, $brands){

        //var_dump($matrix);

        for ($i=0; $i < $brands; $i++) { 
            for ($j=0; $j < 3; $j++) { 
                $quarter[$j][$i] = 0;
            }
        }

        for ($i=$min; $i < $max; $i++) { 
            for ($j=0; $j < 3; $j++) { 
                for ($k=0; $k < $brands; $k++) { 
                    $quarter[$j][$k] += $matrix[$i][$k][$j];
                }
            }
            
        }
    	
        //var_dump($quarter);
   		return $quarter;

    }

}
