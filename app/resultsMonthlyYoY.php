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

    public function lines($con, $currency, $months, $form, $brands, $year, $region, $value, $source){
        
        $cYear = $year;
        $pYear = $year-1;

        for ($l=0; $l < 3; $l++) { 

            if ($l == 0) {
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $pYear, $region, $value);
            }elseif($l == 1) {
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $cYear, $region, $value, $source);
            }else{
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $cYear, $region, $value);
            }
        }

        //var_dump($lines);
        return $lines;
    }

    public function assemblers($brands, $lines, $months, $year){        
        
        $size = sizeof($brands);

        for ($i = 0; $i < $size; $i++) {

            $matrix[$i] = $this->assembler($lines[2][$i], $lines[1][$i], $lines[0][$i],
                                                $months, $year);
        }
        
        if ($size > 1) {
            $matrix[$size] = $this->assemblerDN($matrix, sizeof($brands), $months, $year);
            $size += 1;
        }

        $quarters[0] = $this->assemblerQuarter($matrix, 1, 3, $size, $year);
        $quarters[1] = $this->assemblerQuarter($matrix, 4, 6, $size, $year);
        $quarters[2] = $this->assemblerQuarter($matrix, 7, 9, $size, $year);
        $quarters[3] = $this->assemblerQuarter($matrix, 10, 12, $size, $year);
        
        //var_dump($quarters);

        return array($matrix, $quarters);

    }

    public function assembler($valueCurrentYear, $target, $valuePastYear, $months, $year){

        $matrix[0][0] = "Real ".($year-1);
        $matrix[1][0] = "Target $year";
        $matrix[2][0] = "Real $year";

        for ($i = 1; $i <= sizeof($months); $i++) { 

            $matrix[0][$i] = $valuePastYear[$i-1];

            $matrix[1][$i] = $target[$i-1];

            $matrix[2][$i] = $valueCurrentYear[$i-1];

        }
        
        return $matrix;
    }

    public function assemblerDN($matrix, $pos, $months, $year){
        
        $currentMatrix[0][0] = "Real ".($year-1);
        $currentMatrix[1][0] = "Target $year";
        $currentMatrix[2][0] = "Real $year";

        for ($i = 1; $i <= sizeof($months); $i++) {

            $currentMatrix[0][$i] = 0;
            $currentMatrix[1][$i] = 0;
            $currentMatrix[2][$i] = 0;
        }
        
        for ($i = 1; $i <= sizeof($months); $i++) { 
            for ($j = 0; $j < $pos; $j++) { 
                $currentMatrix[0][$i] += $matrix[$j][0][$i];

                $currentMatrix[1][$i] += $matrix[$j][1][$i];

                $currentMatrix[2][$i] += $matrix[$j][2][$i];
            }
        }

        return $currentMatrix;

    }

    public function assemblerQuarter($matrix, $min, $max, $brands, $year){

        var_dump($matrix);

        $quarter[0][0] = "Real ".($year-1);
        $quarter[1][0] = "Target $year";
        $quarter[2][0] = "Real $year";

        for ($i=1; $i <= $brands; $i++) { 
            $quarter[0][$i] = 0;
            $quarter[1][$i] = 0;
            $quarter[2][$i] = 0;
        }
        //var_dump($quarter);
        for ($i=0; $i < $brands; $i++) { 
            for ($m=$min; $m <= $max; $m++) {
                for ($c=0; $c < 3; $c++) { 
                    $quarter[$c][$i+1] += $matrix[$i][$c][$m];
                }
                
            }    
        }
        
        return $quarter;

    }

}
