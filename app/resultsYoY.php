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

class resultsYoY extends results {

    public function lines($con, $currency, $months, $form, $brands, $year, $region, $value, $source){
        
        $cYear = $year;
        $pYear = $year-1;

        for ($l=0; $l < 3; $l++) { 

            if ($l == 0) {
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $pYear, $region, $value, $cYear);
            }elseif($l == 1) {
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $cYear, $region, $value, $cYear, $source);
            }else{
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $cYear, $region, $value, $cYear);
            }
        }

        //var_dump($lines);
        return $lines;
    }

    public function assemblers($brands, $lines, $months, $year, $source){        

        $source = strtolower($source);
        $source = ucfirst($source);

        for ($i = 0; $i < sizeof($brands); $i++) {

            $matrix[$i] = $this->assembler($lines[2][$i], $lines[1][$i], $lines[0][$i], $months, $year, $brands[$i], $source);
        }

        if (sizeof($brands) > 1) {
            $matrix[sizeof($brands)] = $this->assemblerDN($matrix, sizeof($brands), $months, $year, $source);
        }
        
        return $matrix;

    }

    public function assembler($valueCurrentYear, $target, $valuePastYear, $months, $year, $brand, $source){

        $valueCurrentYearSum = 0;
        $targetSum = 0;
        $valuePastYearSum = 0;

        $matrix[0][0] = $brand[1];
        $matrix[1][0] = "BKGS ".($year-1);
        $matrix[2][0] = "$source $year";
        $matrix[3][0] = "Bookings $year";
        $matrix[4][0] = "Dif. 3° - 2°";
        $matrix[5][0] = "Dif. YoY";

        for ($i = 1; $i <= sizeof($months); $i++) {

            $matrix[0][$i] = $months[$i-1][2];

            $matrix[1][$i] = $valuePastYear[$i-1];
            $valuePastYearSum += $valuePastYear[$i-1];

            $matrix[2][$i] = $target[$i-1];
            $targetSum += $target[$i-1];

            $matrix[3][$i] = $valueCurrentYear[$i-1];
            $valueCurrentYearSum += $valueCurrentYear[$i-1];

            $matrix[4][$i] = $matrix[3][$i] - $matrix[2][$i];

            $matrix[5][$i] = $matrix[3][$i] - $matrix[1][$i];
        }

        $last = $i;
 
        $matrix[0][$last] = "Total";
        $matrix[1][$last] = $valuePastYearSum;
        $matrix[2][$last] = $targetSum;
        $matrix[3][$last] = $valueCurrentYearSum;
        $matrix[4][$last] = ($valueCurrentYearSum - $targetSum);
        $matrix[5][$last] = ($valueCurrentYearSum - $valuePastYearSum);

        return $matrix;
    }

    public function assemblerDN($matrix, $pos, $months, $year, $source){

        $currentMatrix[0][0] = "DN";
        $currentMatrix[1][0] = "Bookings ".($year-1);
        $currentMatrix[2][0] = "$source $year";
        $currentMatrix[3][0] = "Bookings $year";
        $currentMatrix[4][0] = "Dif. 3° - 2°";
        $currentMatrix[5][0] = "Dif. YoY";

        for ($i = 1; $i <= sizeof($months); $i++) {

            $currentMatrix[0][$i] = $months[$i-1][2];
            $currentMatrix[1][$i] = 0;
            $currentMatrix[2][$i] = 0;
            $currentMatrix[3][$i] = 0;
            $currentMatrix[4][$i] = 0;
            $currentMatrix[5][$i] = 0;
        }

        $valueCurrentYearSum = 0;
        $targetSum = 0;
        $valuePastYearSum = 0;

        for ($i = 0; $i < $pos; $i++) { 
            for ($j = 1; $j <= sizeof($months); $j++) { 
                $currentMatrix[1][$j] += $matrix[$i][1][$j];
                $valuePastYearSum += $matrix[$i][1][$j];

                $currentMatrix[2][$j] += $matrix[$i][2][$j];
                $targetSum += $matrix[$i][2][$j];

                $currentMatrix[3][$j] += $matrix[$i][3][$j];
                $valueCurrentYearSum += $matrix[$i][3][$j];

                $currentMatrix[4][$j] += $matrix[$i][4][$j];

                $currentMatrix[5][$j] += $matrix[$i][5][$j];
            }
        }

        $last = $j;
        $currentMatrix[0][$last] = "Total";
        $currentMatrix[1][$last] = $valuePastYearSum;
        $currentMatrix[2][$last] = $targetSum;
        $currentMatrix[3][$last] = $valueCurrentYearSum;
        $currentMatrix[4][$last] = ($valueCurrentYearSum - $targetSum);
        $currentMatrix[5][$last] = ($valueCurrentYearSum - $valuePastYearSum);

        return $currentMatrix;

    }

}
