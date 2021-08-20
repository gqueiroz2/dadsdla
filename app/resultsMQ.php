<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;
use App\results;
use App\brand;
use App\region;
use App\ytd;
use App\mini_header;

class resultsMQ extends results{
    
    public function lines($con, $currency, $months, $form, $brands, $year, $region, $value, $source){

        for ($l=0; $l < 2; $l++) { 

            if ($l == 0) {
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $year, $region, $value, $year);
            }else{
                $lines[$l] = $this->matchBrandMonth($con, $currency, $form, $brands, $months, $year, $region, $value, $year, $source);
            }
        }
        
        return $lines;

    }

    public function assembler($con,$brandID, $lines, $month, $year,$source){

        $source = strtolower($source);
        $source = ucfirst($source);

    	for ($i = 0; $i < sizeof($brandID); $i++) {
    		$brandName[$i] = $brandID[$i];
            $matrix[$i] = $this->handler($brandName[$i],$lines[0][$i],$lines[1][$i],$month,$year,$source);
            
        }
        
        $mtx = $matrix;

        for ($m=0; $m < sizeof($mtx); $m++) { 
            $tp[$m+1] = $mtx[$m];
        }

        if (sizeof($brandID) > 1) {
            $tp[0] = $this->assemblerDN($matrix,sizeof($brandID),$month,$year,$source);
            //$matrix[sizeof($brandID)] = $this->assemblerDN($matrix,sizeof($brandID),$month,$year,$source);
        }              

        return $tp;
        //return $matrix;
    }

    public function handler($brand, $valueCurrentYear, $target, $month, $year, $source){

        $valueCurrentYearSum = 0;
        $targetSum = 0;

        $matrix[0][0] = $brand[1];
        $matrix[1][0] = "$source $year";
        $matrix[2][0] = "BKGS $year";
        $matrix[3][0] = "Var(%)";
        $matrix[4][0] = "Abs. Var.";

        for ($i = 1; $i <= sizeof($month); $i++) { 

            $matrix[0][$i] = $month[$i-1][2];
            $matrix[1][$i] = $target[$i-1];
            $matrix[2][$i] = $valueCurrentYear[$i-1];
            
            if($matrix[1][$i] > 0){
                $matrix[3][$i] = ($matrix[2][$i]*100)/$matrix[1][$i];
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

    public function assemblerQuarters($con,$brandID, $lines, $month, $year, $source){
        
        $matrix = $this->assembler($con,$brandID, $lines, $month, $year, $source);
        
        $source = strtolower($source);
        $source = ucfirst($source);

        for ($i=0; $i < sizeof($matrix); $i++) { 
            for ($j=0; $j < sizeof($matrix[$i]); $j++) { 
                for ($n=0; $n <= 6; $n++) { 
                    $quarter[$i][$j][$n] = 0;
                }
            }
        }
        //var_dump($quarter);

        for ($b=0; $b < sizeof($matrix); $b++) { 
            $quarter[$b][0][0] = $matrix[$b][0][0];
            $quarter[$b][0][1] = "Q1";
            $quarter[$b][0][2] = "Q2";
            $quarter[$b][0][3] = "S1";
            $quarter[$b][0][4] = "Q3";
            $quarter[$b][0][5] = "Q4";
            $quarter[$b][0][6] = "S2";
            $quarter[$b][0][7] = "Total";

            $quarter[$b][1][0] = "$source $year";
            $quarter[$b][2][0] = "BKGS $year";
            $quarter[$b][3][0] = "Var(%)";
            $quarter[$b][4][0] = "Abs. Var.";

            for ($l=1; $l < sizeof($matrix[$b]); $l++) { 
                for ($m=1, $j=1; $m < sizeof($matrix[$b][$l])-1; $m+=3,$j++) {
                    if ($j == 3) {
                        if ($l == 3) {
                            if ($quarter[$b][1][$j] > 0) {
                                $quarter[$b][$l][$j] = ($quarter[$b][2][$j] / $quarter[$b][1][$j])*100;
                            }else{
                                $quarter[$b][$l][$j] = 0.0;
                            }
                        }else{
                            $quarter[$b][$l][$j] = $quarter[$b][$l][$j-1] + $quarter[$b][$l][$j-2];    
                        }
                        $m -= 3;
                    }else{
                        for ($mq=$m; $mq < ($m+3); $mq++) {
                            if ($l == 3) {
                                if ($quarter[$b][1][$j] > 0) {
                                    $quarter[$b][$l][$j] = ($quarter[$b][2][$j] / $quarter[$b][1][$j])*100;
                                }else{
                                    $quarter[$b][$l][$j] = 0.0;
                                }
                                
                            }else{
                                $quarter[$b][$l][$j] += $matrix[$b][$l][$mq];
                            }
                        }
                    }
                 }

                 if ($l == 3) {
                     if ($quarter[$b][1][$j] > 0) {
                            $quarter[$b][$l][$j] = ($quarter[$b][2][$j] / $quarter[$b][1][$j])*100;
                        }else{
                            $quarter[$b][$l][$j] = 0.0;
                        }
                }else{
                    $quarter[$b][$l][$j] = $quarter[$b][$l][$j-1] + $quarter[$b][$l][$j-2];    
                }

                 if ($l == 3) {
                     if ($quarter[$b][1][$j+1] > 0) {
                         $quarter[$b][$l][$j+1] = ($quarter[$b][2][$j+1] / $quarter[$b][1][$j+1])*100; 
                     }else{
                        $quarter[$b][$l][$j+1] = 0.0;
                     }
                 }else{
                    $quarter[$b][$l][$j+1] = $quarter[$b][$l][$j] + $quarter[$b][$l][$j/2];   
                 }
                 
            }    
        }


        return $quarter;
        //var_dump($quarter);

    }

    public function assemblerDN($matrix, $pos, $month, $year, $source){
        
        $currentMatrix[0][0] = "DN";
        $currentMatrix[1][0] = "$source $year";
        $currentMatrix[2][0] = "Bookings $year";
        $currentMatrix[3][0] = "Var(%)";
        $currentMatrix[4][0] = "Absolut Var.";

        for ($i = 1; $i <= sizeof($month); $i++) {

            $currentMatrix[0][$i] = $month[$i-1][2];
            $currentMatrix[1][$i] = 0;
            $currentMatrix[2][$i] = 0;            
        }

        $valueCurrentYearSum = 0;
        $targetSum = 0;

        for ($i = 0; $i < $pos; $i++) { 
            for ($j = 1; $j <= sizeof($month); $j++) { 
                $currentMatrix[1][$j] += $matrix[$i][1][$j];
                $targetSum += $matrix[$i][1][$j];

                $currentMatrix[2][$j] += $matrix[$i][2][$j];
                $valueCurrentYearSum += $matrix[$i][2][$j];                
            }
        }

        for ($n=1; $n < 13; $n++) { 
                
            if($currentMatrix[1][$n] > 0){
                $currentMatrix[3][$n] = (($currentMatrix[2][$n]/$currentMatrix[1][$n])*100);
            }else{
                $currentMatrix[3][$n] = 0.0;
            }

            $currentMatrix[4][$n] = $currentMatrix[2][$n] - $currentMatrix[1][$n];

        }

        $last = $j;

        $currentMatrix[0][$last] = "Total";
        $currentMatrix[1][$last] = $targetSum;
        $currentMatrix[2][$last] = $valueCurrentYearSum;
        if($targetSum > 0){
            $currentMatrix[3][$last] = (($valueCurrentYearSum/$targetSum )*100);
        }else{
            $currentMatrix[3][$last] = 0.0;
        }
        $currentMatrix[4][$last] = $valueCurrentYearSum - $targetSum ;


        return $currentMatrix;

    }
}
