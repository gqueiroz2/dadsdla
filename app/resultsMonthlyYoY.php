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

    public function lines($con, $brands, $region, $year, $currency, $value, $form, $source){
        
        if (sizeof($brands) == 0) {
            $lines = false;
        }else{

            for ($i=0; $i < sizeof($brands); $i++) {

                if ($brands[$i] == "9" || $brands[$i] == "10") {
                    $newForm = 'Digital';
                }else{
                    $newForm = $form;
                }
                
                for ($j=0; $j < 3; $j++) { 
                    $lines[$i][$j] = $this->line($con, $currency, $brands[$i], $region, $year, $value, $newForm, ($j+1), $source);

                    if (!$lines[$i][$j]) {
                        $lines[$i][$j] = "This brand doesn't has values";
                    }
                }
            }
        }

        return $lines;
    }
    
    public function line($con, $currency, $brand, $region, $year, $value, $form, $lineNumber, $source){        

        switch ($lineNumber) {
            case 1:
                
                $res = $this->createObject($form, $value);
                $formResp = $res[0];
                
                if (!is_null($formResp)) {

                    $columns = $res[1];
                    if ($columns) {

                        if (sizeof($columns) == 4) {
                            $colValues = array($region, $brand, ($year-1));
                            $line = $this->lineValues($con, false, $currency, $res[2], $formResp, $columns, $colValues, $region, $year);

                        } elseif (sizeof($columns) == 3) {
                            $colValues = array($brand, ($year-1));
                            $line = $this->lineValues($con, false, $currency, $res[2], $formResp, $columns, $colValues, $region, $year);
                        }else{

                            $line = false;
                        }
                    }else{
                        $line = false;
                    }
                    
                }

                break;
            
            case 2:
                
                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month");
                $colValues = array($region, $source, strtoupper($value), $brand, $year);
                //var_dump($colValues);
                $p = new planByBrand();

                $line = $this->lineValues($con, true, $currency, "revenue", $p, $columns, $colValues, $region, $year);

                break;

            case 3:
                
                $res = $this->createObject($form, $value);
                $formResp = $res[0];

                if (!is_null($formResp)) {

                    $columns = $res[1];

                    if ($columns) {
                        if (sizeof($columns) == 4) {
                            $colValues = array($region, $brand, $year);
                            $line = $this->lineValues($con,false,$currency,$res[2], $formResp, $columns, $colValues, $region, $year);
                        } elseif (sizeof($columns) == 3) {
                            $colValues = array($brand, $year);
                            $line = $this->lineValues($con,false,$currency,$res[2], $formResp, $columns, $colValues, $region, $year);
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

    public function lineValues($con,$type,$currency, $value, $form, $columns, $colValues, $region, $year){
        
         /*
            $type == TYPE 

            True = TARGET
            False = REAL

        */

        $r = new region();
        $p = new pRate();

        if($type){
            $pRate = 1.0;
        }else{
            if($currency == "USD"){            
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
            }else{
                $pRate = 1.0;
            }
        }

        array_push($colValues, 0);
        $tam = sizeof($columns);

        $currentMonth = intval(date('m')) - 1;

        for ($i = 0; $i < 12; $i++) {
            $colValues[$tam-1] = ($i+1);

            if($type){
                    $formValue[$i] = $form->sum($con,$region,$value, $columns, $colValues)['sum'];
            }else{
                if($i < $currentMonth){
                    $form = new ytd();
                    $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                    $value = 'gross_revenue';
                }else{
                    $form = new mini_header();
                    $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                }
                
                $formValue[$i] = $form->sum($con, $value, $columns, $colValues)['sum'];
            }

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

    public function assemblers($totalBrands, $brands, $lines, $month, $year){        
        var_dump($totalBrands);
        for ($i = 0; $i < sizeof($brands); $i++) {

            if (is_string($lines[$i][2])) {
                $matrix[$i] = $lines[$i][2];       
            }else{
                $matrix[$i] = $this->assembler($lines[$i][2], $lines[$i][1], $lines[$i][0],
                                                $month, $year, $brands[$i]);
            }
            
        }

        if (sizeof($brands) > 1) {
            $matrix[sizeof($brands)] = $this->assemblerDN($matrix, sizeof($brands), $month, $year);
        }

        $quarters[0] = $this->assemblerQuarter($matrix, 0, 2, sizeof($brands));
        $quarters[1] = $this->assemblerQuarter($matrix, 3, 5, sizeof($brands));
        $quarters[2] = $this->assemblerQuarter($matrix, 6, 8, sizeof($brands));
        $quarters[3] = $this->assemblerQuarter($matrix, 9, 11, sizeof($brands));

        return array($matrix, $quarters);

    }

    public function assembler($valueCurrentYear, $target, $valuePastYear, $month, $year){

        for ($i = 0; $i < sizeof($month); $i++) { 

            $matrix[$i][0] = $valuePastYear[$i];

            $matrix[$i][1] = $target[$i];

            $matrix[$i][2] = $valueCurrentYear[$i];

        }

        return $matrix;
    }

    public function assemblerDN($matrix, $pos, $month, $year){

        //var_dump($matrix);

        for ($i = 0; $i < sizeof($month); $i++) {

            $currentMatrix[$i][0] = 0;
            $currentMatrix[$i][1] = 0;
            $currentMatrix[$i][2] = 0;

        }

        for ($i = 0; $i < $pos; $i++) { 
            for ($j = 0; $j < sizeof($month); $j++) { 
                $currentMatrix[$j][0] += $matrix[$i][$j][0];

                $currentMatrix[$j][1] += $matrix[$i][$j][1];

                $currentMatrix[$j][2] += $matrix[$i][$j][2];

            }
        }

        return $currentMatrix;

    }

    public function assemblerQuarter($matrix, $min, $max, $brands){

        //var_dump($matrix);

        for ($i=0; $i < $brands; $i++) { 
            for ($j=0; $j < 3; $j++) { 
                $quarter[$i][$j] = 0;
            }
        }

        for ($i=0; $i < $brands; $i++) { 
            for ($j=$min; $j <= $max; $j++) {
                for ($k=0; $k < 3; $k++) { 
                    $quarter[$i][$k] += $matrix[$i][$j][$k]; 
                } 
                
            }
        }
        
        
        return $quarter;

    }

}
