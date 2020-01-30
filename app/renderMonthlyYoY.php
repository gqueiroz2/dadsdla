<?php

namespace App;

use Illuminate\Database\Eloquent\Model;				
use App\Render;

class renderMonthlyYoY extends Render{

	/*
	*$mtx = matriz dos meses
	*$quarters = quarters
	*/
	public function assemble($mtx,$quarters,$form,$pRate,$value,$year,$months,$brands, $source, $region){
		
		echo "<table style='width: 100%; zoom:80%; font-size: 16px;'>";

			echo "<th class='lightBlue center' colspan='13'>";
				echo "<span style='font-size:24px;''>";
					echo "$region - Monthly Year Over Year : ".$form." - ".$year." (".$pRate[0]['name']."/".strtoupper($value).")";
				echo "</span>";
			echo "</th>";
			echo "<tr height='10'><td>&nbsp;</td></tr>";
		for($i = 0, $j = 0; $i < sizeof($months); $i+=3, $j++){

			echo "<tr style='border-collapse:separate; border-spacing:0 15px;'>";
                $this->renderHead($months, $i, $j, "dc", "vix", "darkBlue");
            echo "</tr>";
           	echo "<tr>";
           		$this->renderHead2($year, "dc", "vix", "darkBlue", $source);
           	echo "</tr>";
            for($b = 0; $b < sizeof($brands); $b++){
	            if($b != (sizeof($brands) - 1)){
	            	echo "<tr>";
	            		$this->renderData($brands[$b], $mtx, $quarters[$j], $i, $b, "dc", "rcBlue", "month", "medBlue");
	        		echo "</tr>";
	        	}else{
	        		echo "<tr>";
	            		$this->renderData($brands[$b], $mtx, $quarters[$j], $i, $b, "darkBlue", "smBlue", "smBlue", "smBlue", true);
	        		echo "</tr>";
	        	}
            }

			if($i != (sizeof($months)-1)){
				echo "<tr height='10'><td>&nbsp;</td></tr>";
			}
		}

		echo "</table>";

	}

	/*
	*renderiza o primeiro cabeçalho (meses e quarter)
	*$index = indice do quarter
	*$size = utilizada para indicar quais os meses para o cabeçalho nesta linha
	*as cores são sempre essas 3 e são determinadas pelo numero da coluna
	*/
	public function renderHead($months, $size, $index, $firstColor, $secondColor, $thirdColor){
		$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."'";

		echo "<td $firstClass style='width: 3%;'>&nbsp;</td>";

		for ($i = $size, $j=0; $i < ($size+3); $i++, $j++) {
			
			if($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			echo "<td colspan='3' $class>".$months[$i][0]."</td>";
		}

		echo "<td colspan='3' $thirdClass>Q".($index+1)."</td>";
	}

	/*
	*renderiza o segundo cabeçalho (actual passado, target, actual atual)
	*as cores são sempre essas 3 e são determinadas pelo numero da coluna
	*/
    public function renderHead2($year, $firstColor, $secondColor, $thirdColor, $source){

    	$source = strtolower($source);
        $source = ucfirst($source);

    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."'";

    	echo "<td $firstClass>&nbsp;</td>";

		for ($i = 0, $j=0; $i <= 3; $i++, $j++) {

			if($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			if ($i == 3) {
				$class = $thirdClass;
			}

			echo "<td $class> BKGS ".($year-1)."</td>";
			echo "<td $class> $source ".$year."</td>";
			echo "<td $class> BKGS ".$year."</td>";
		}

    }

	/*
	*renderiza o corpo cabeçalho (valores de cada coluna)
	*as cores são sempre essas 4 e são determinadas pelo numero da coluna
	*/
    public function renderData($brand, $matrix, $quarter, $month, $brandPos, $firstColor, $secondColor, $thirdColor, $fourthColor, $ok=false){
    	
    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		echo "<td $firstClass>".$brand[1]."</td>";

		for ($i=$month; $i < ($month+3); $i++) { 
			
			for ($j=0; $j < 3; $j++) {

				if ($j == 0) {
					$class = $secondClass;
				}elseif ($j == 1) {
					$class = $thirdClass;
				}else{
					$class = $fourthClass;
				}

				//é feita (pos + 1) no ultimo indice, pois as marcas começam no indice 1, sendo que o indice 0 é o nome da coluna
				echo "<td $class>".number_format($matrix[$brandPos][$j][$i+1], 0, ".", ",")."</td>";
			}
		}
		//var_dump($quarter);
		for ($i=0; $i < 3; $i++) { 

			if ($i == 0) {
				$class = $secondClass;
			}elseif ($i == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			//é feita (pos + 1) no ultimo indice, pois as marcas começam no indice 1, sendo que o indice 0 é o nome da coluna
			if (!$ok) { //verifca se é DN
				echo "<td $class>".number_format($quarter[$i][$brandPos+1], 0, ".", ",")."</td>";	
			}else{
				echo "<td $firstClass>".number_format($quarter[$i][$brandPos+1], 0, ".", ",")."</td>";	
			}	
		}
    }

    //aqui começa a renderização do modal, os parametros passados ja foram explicados nas funções da tabela principal
    public function assembleModal($brands, $quarters, $year, $source){

    	$source = strtolower($source);
        $source = ucfirst($source);

    	echo "<table style='width: 100%; zoom:90%; font-size: 16px;'>";
	    	echo "<tr>";
	    		$this->renderModalHeader("dc", "darkBlue");
			echo "</tr>";
	        
	        echo "<tr>";
	        	$this->renderModalHeader2($year, "dc", "darkBlue", $source);
	    	echo "</tr>";

			for($i = 0; $i < sizeof($brands); $i++){
				if ($i != (sizeof($brands) - 1)) {
					echo "<tr>";
	                	$this->renderDataModal($brands[$i], $quarters, $i, "dc", "rcBlue", "white", "medBlue", true);
	            	echo "</tr>";
				}else{
					echo "<tr>";
	                	$this->renderDataModal($brands[$i], $quarters, $i, "dc", "rcBlue", "white", "medBlue");
	            	echo "</tr>";
				}
	        }
        echo "</table>";

    }

    public function renderModalHeader($firstcolor, $secondColor){
    	
    	$firstClass = "class='center ".$firstcolor."'";
    	$secondClass = "class='center ".$secondColor."'";
    	$style = "style='font-size: 18px;'";

    	echo "<td $firstClass>&nbsp;</td>";

    	for ($i=1; $i <= 2; $i++) { 
    		echo "<td $firstClass colspan='3'>S".$i."</td>";
    	}

    	echo "<td $secondClass $style colspan='3'>TOTAL</td>";
    }

    public function renderModalHeader2($year, $firstcolor, $secondColor, $source){
    	
    	$firstClass = "class='center ".$firstcolor."'";
    	$secondClass = "class='center ".$secondColor."'";
    	$style = "style='font-size: 14px;'";
    	echo "<td $firstClass>&nbsp;</td>";

    	for ($i=0; $i < 3; $i++) {

    		if ($i == 2) {
    			$class = $secondClass;
    		}else{
    			$class = $firstClass;
    		}

    		echo "<td $class $style colspan='1'>BKGS ".($year-1)."</td>";
			echo "<td $class $style colspan='1'>$source ".$year."</td>";
			echo "<td $class $style colspan='1'>BKGS ".$year."</td>";
    	}
    }

    public function renderDataModal($brand, $quarter, $brandPos, $firstColor, $secondColor, $thirdColor, $fourthColor, $ok=false){
    	
    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		if ($brand[1] == "DN") {
			echo "<td class='center darkBlue'>".$brand[1]."</td>";	
		}else{
			echo "<td $firstClass>".$brand[1]."</td>";	
		}
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			//feito calculo dos quarters 1 e 2 para formar o primeiro semestre
			if ($ok) {
				echo "<td $class colspan='1'>".number_format(($quarter[0][$j][$brandPos+1]+$quarter[1][$j][$brandPos+1]), 0, ".", ",")."</td>";
			}else{
				echo "<td class='center darkBlue' colspan='1'>".number_format(($quarter[0][$j][$brandPos+1]+$quarter[1][$j][$brandPos+1]), 0, ".", ",")."</td>";
			}
			
		}
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}
			//feito calculo dos quarters 3 e 4 para formar o segundo semestre
			if ($ok) {
				echo "<td $class colspan='1'>".number_format(($quarter[2][$j][$brandPos+1]+$quarter[3][$j][$brandPos+1]), 0, ".", ",")."</td>";
			}else{
				echo "<td class='center darkBlue' colspan='1'>".number_format(($quarter[2][$j][$brandPos+1]+$quarter[3][$j][$brandPos+1]), 0, ".", ",")."</td>";
			}
		}

		for ($i=0; $i < 3; $i++) { 

			if ($i == 0) {
				$class = $secondClass;
			}elseif ($i == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}
			//feito calculo de todos os quarters
			if ($ok) {
				echo "<td $class colspan='1'>".number_format(
				(
					$quarter[0][$i][$brandPos+1]+$quarter[1][$i][$brandPos+1]+
					$quarter[2][$i][$brandPos+1]+$quarter[3][$i][$brandPos+1]
				), 0, ".", ","
				).
				"</td>";
			}else{
				echo "<td class='center darkBlue' colspan='1'>".number_format(
				(
					$quarter[0][$i][$brandPos+1]+$quarter[1][$i][$brandPos+1]+
					$quarter[2][$i][$brandPos+1]+$quarter[3][$i][$brandPos+1]
				), 0, ".", ","
				).
				"</td>";
			}
		}

    }
}
