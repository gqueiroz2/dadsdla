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

    public function lines( $con, $currency, $months, $form, $brands, $year, $region, $value, $source){
        
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
  

    /*monta a matriz para renderização*/
    public function assemblers($brands, $lines, $months, $year, $source){        
        
        $size = sizeof($brands);

        $source = strtolower($source);
        $source = ucfirst($source);

        /*
        *Monta a matriz
        *primeiro indice são os meses
        *segundo indice são as colunas (actual passado, target, actual atual)
        *terceiro indice são os canais
        */
        for ($i = 0; $i < $size; $i++) {

            $matrix[$i] = $this->assembler($lines[2][$i], $lines[1][$i], $lines[0][$i],
                                                $months, $year, $source);
        }
        
        /*verifica se existe DN*/
        if ($size > 1) {
            $matrix[$size] = $this->assemblerDN($matrix, sizeof($brands), $months, $year, $source);
            $size += 1;
        }

        /*
        *monta os quarters
        *o primeiro indice é o numero do quarter ,  e valor
        *segundo indice são as colunas(real passado, target, real atual)
        *terceiro indice são os canais
        */
        for ($q=1, $i = 0; $q <= 12; $q+=3, $i++) {
            $quarters[$i] = $this->assemblerQuarter($matrix, $q, ($q+2), $size, $year, $source);
        }
        
        //var_dump($quarters);

        return array($matrix, $quarters);

    }

    /*
    *faz o calculo do quarter
    *$matrix = matriz com os valores
    *$min = o mes inicial usado para o quarter
    *$max = o mes final usado para o quarter
    */
    public function assemblerQuarter($matrix, $min, $max, $brands, $year, $source){

        $quarter[0][0] = "Bookings ".($year-1);
        $quarter[1][0] = "$source $year";
        $quarter[2][0] = "Bookings $year";

        //zera os valores do quarter para calculo
        for ($i=1; $i <= $brands; $i++) { 
            $quarter[0][$i] = 0;
            $quarter[1][$i] = 0;
            $quarter[2][$i] = 0;
        }
        
        //faz o calculo dos quarters
        for ($i=0; $i < $brands; $i++) { 
            for ($m=$min; $m <= $max; $m++) {
                for ($c=0; $c < 3; $c++) { 
                    $quarter[$c][$i+1] += $matrix[$i][$c][$m];
                }
                
            }    
        }
        
        return $quarter;

    }

    /*
    *$valueCurrentYear = valor do real passado
    *$target = valor do plano
    *$valuePastYear = valor do real atual
    */
    public function assembler($valueCurrentYear, $target, $valuePastYear, $months, $year, $source){

        $matrix[0][0] = "BKGS ".($year-1);
        $matrix[1][0] = "$source $year";
        $matrix[2][0] = "BKGS $year";

        for ($i = 1; $i <= sizeof($months); $i++) { 

            $matrix[0][$i] = $valuePastYear[$i-1];

            $matrix[1][$i] = $target[$i-1];

            $matrix[2][$i] = $valueCurrentYear[$i-1];

        }
        
        return $matrix;
    }

    public function assemblerDN($matrix, $pos, $months, $year, $source){
        
        $currentMatrix[0][0] = "BKGS ".($year-1);
        $currentMatrix[1][0] = "$source $year";
        $currentMatrix[2][0] = "BKGS $year";

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

    

}
