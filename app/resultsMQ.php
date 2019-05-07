<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\results;
use App\brand;
use App\region;

class resultsMQ extends results{
    
    public function lines($con, $brands, $region, $year,$currency, $value, $form, $source){

        $p = new pRate();
        
        $currency = $p->getCurrency($con,array($currency))[0]['name'];

        var_dump($currency);

        if (sizeof($brands) == 0) {
            $lines = false;
        }else{

            for ($i=0; $i < sizeof($brands); $i++) { 

                if ($brands[$i] == 9 || $brands[$i] == 10) {
                    $finalValue = 'Digital';
                }else{
                    $finalValue = $value;
                }

                for ($j=0; $j < 2; $j++) { 
                    $lines[$i][$j] = $this->line($con, $currency, $brands[$i], $region, $year, $finalValue, $form, ($j+1), $source);

                    if (!$lines[$i][$j]) {
                    	var_dump("AKI");
                        $lines[$i][$j] = "This brand doesn't has values";
                    }
                }
            }
        }

        return $lines;

    }

    public function line($con,$currency, $brand, $region, $year, $value, $form, $lineNumber, $source){        

    	$newSource = strtoupper($form);
        switch ($lineNumber) {
            case 1:
                
                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month");
                $colValues = array($region, $newSource, strtoupper($value), $brand, $year);
                $p = new planByBrand();
                $line = $this->lineValues($con,$currency, "revenue", $p, $columns, $colValues, $region, $year);
                break;

            case 2:
                $res = $this->createObject($source, $value);

                $formResp = $res[0];
                if (!is_null($formResp)) {

                    $columns = $res[1];

                    if ($columns) {
                        if (sizeof($columns) == 4) {
                            $colValues = array($region, $brand, $year);
                            $line = $this->lineValues($con,$currency, $res[2], $formResp, $columns, $colValues, $region, $year);
                        } elseif (sizeof($columns) == 3) {
                            $colValues = array($brand, $year);
                            $line = $this->lineValues($con,$currency, $res[2], $formResp, $columns, $colValues, $region, $year);
                        }else{
                            $line = false;
                        }
                    }else{
                        $line = false;
                    }
                    
                }

                break;

            default:
                $line = false;
                break;
        }

        return $line;
        
    }

    public function lineValues($con,$currency, $value, $form, $columns, $colValues, $region, $year){
        
        $r = new region();
        $p = new pRate();
        if($currency == "USD"){            
            $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
        }else{
            $pRate = 1.0;
        }
        
        array_push($colValues, 0);
        $tam = sizeof($columns);
        for ($i = 0; $i < 12; $i++) {
            $colValues[$tam-1] = ($i+1);

            $formValue[$i] = $form->sum($con, $value, $columns, $colValues)['sum'];

            if ($formValue[$i] == 0) {
                $formValue[$i] = 0;
            }else{

                $formValue[$i] = $formValue[$i]/$pRate;

            }
        }
        return $formValue;
    }

    public function createObject($form, $value){
        
        $obj = null;
        $columns = array();

        switch ($form) {
            case 'IBMS':
                $obj = new ytd();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;

            case 'ytd':
                $obj = new ytd();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;
            
            case 'CMAPS':
                $obj = new cmaps();

                $columns = array("brand_id", "year", "month");

                break;

            case 'cmaps':
                $obj = new cmaps();

                $columns = array("brand_id", "year", "month");

                break;

            case 'Header':
                $obj = new mini_header();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;

            case 'Digital':
                $obj = new digital();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;

            default:
                $obj = false;

                break;
        }

        return array($obj, $columns, $value);
    }

    public function assembler($con,$brand,$brandID, $lines, $month, $year){

    	for ($i = 0; $i < sizeof($brandID); $i++) {

    		$brandName[$i] = $brand->getBrand($con, array($brandID[$i]) )[0];

            if (is_string($lines[$i][1])) {
                $matrix[$i] = $lines[$i][1];       
            }else{
                $matrix[$i] = $this->handler($brandName[$i],$lines[$i][1],$lines[$i][0],$month,$year);
            }
        }
        return $matrix;
    }

    public function handler($brand, $valueCurrentYear, $target, $month, $year){

    	$valueCurrentYearSum = 0;
        $targetSum = 0;
     
        $matrix[0][0] = $brand['name'];
        $matrix[1][0] = "Target $year";
        $matrix[2][0] = "Real $year";
        $matrix[3][0] = "Var(%)";
        $matrix[4][0] = "Absolut Var.";

        for ($i = 1; $i <= sizeof($month); $i++) { 

            $matrix[0][$i] = $month[$i-1][2];
            $matrix[1][$i] = $target[$i-1];
            $matrix[2][$i] = $valueCurrentYear[$i-1];
            
            if($matrix[1][$i] > 0){
            	$matrix[3][$i] = ( $matrix[2][$i] / $matrix[1][$i] )*100;
            }else{
            	$matrix[3][$i] = 0.0;
            }
            
            $matrix[4][$i] = $matrix[2][$i] - $matrix[1][$i];

            $targetSum += $target[$i-1];            
            $valueCurrentYearSum += $valueCurrentYear[$i-1];

        }

        $last = $i;

        $matrix[0][$last] = "Total";
        $matrix[1][$last] = $targetSum;
        $matrix[2][$last] = $valueCurrentYearSum;
        if($targetSum > 0){
        	$matrix[3][$last] = ( $valueCurrentYearSum / $targetSum )*100;
        }else{
        	$matrix[3][$last] = 0.0;
        }
        
        $matrix[4][$last] = $valueCurrentYearSum - $targetSum;

        return $matrix;

    }
}
