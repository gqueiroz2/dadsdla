<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderMonthlyYoY extends Model{
    
	public function renderHead($months, $size, $index, $firstColor, $secondColor, $thirdColor){
		
		$firstClass = "class='center ".$firstColor."' style='font-size: 18px'";
		$secondClass = "class='center ".$secondColor."' style='font-size: 18px'";
		$thirdClass = "class='center ".$thirdColor."' style='font-size: 18px'";

		echo "<td $firstClass>&nbsp;</td>";

		for ($i = $size, $j=0; $i < ($size+3); $i++, $j++) {
			
			if ($j == 0) {
				$class = $firstClass;
			}elseif($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			echo "<td colspan='3' $class>".$months[$i][0]."</td>";
		}

		echo "<td colspan='3' $thirdClass>Q".$index."</td>";

	}

    public function renderHead2($year, $size, $firstColor, $secondColor, $thirdColor){

    	$firstClass = "class='center ".$firstColor."' style='font-size: 18px'";
		$secondClass = "class='center ".$secondColor."' style='font-size: 18px'";
		$thirdClass = "class='center ".$thirdColor."' style='font-size: 18px'";

    	echo "<td $firstClass>&nbsp;</td>";

		for ($i = $size, $j=0; $i <= ($size+3); $i++, $j++) {

			if ($j == 0) {
				$class = $firstClass;
			}elseif($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			if ($i == ($size+3)) {
				$class = $thirdClass;
			}

			echo "<td $class>Real".($year-1)."</td>";
			echo "<td $class>Target".$year."</td>";
			echo "<td $class>Real".$year."</td>";
		}

    }

    public function renderData($brand, $matrix, $quarter, $brandPos, $size, $firstColor, $secondColor, $thirdColor, $fourthColor){

    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		echo "<td $firstClass>$brand</td>";
		
		for ($i=$size; $i < ($size+3); $i++) { 
			for ($j=0; $j < 3; $j++) {

				if ($j == 0) {
					$class = $secondClass;
				}elseif ($j == 1) {
					$class = $thirdClass;
				}else{
					$class = $fourthClass;
				}

				if($brandPos == 10){
					//var_dump($matrix[$brandPos]);
				}
				echo "<td $class>".number_format($matrix[$brandPos][$i][$j])."</td>";
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

			echo "<td $class>".number_format($quarter[$brandPos][$i])."</td>";
		}

    }

    public function renderModalHeader($firstcolor, $secondColor){
    	
    	$firstClass = "class='center ".$firstcolor."'";
    	$secondClass = "class='center ".$secondColor."'";
    	$style = "style='font-size: 18px;'";

    	echo "<td $firstClass>&nbsp;</td>";

    	for ($i=0; $i < 2; $i++) { 
    		echo "<td $firstClass colspan='3'>S".$i."</td>";
    	}

    	echo "<td $secondClass $style colspan='3'>TOTAL</td>";
    }

    public function renderModalHeader2($year, $firstcolor, $secondColor){
    	
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

    		echo "<td $class $style colspan='1'>Real ".($year-1)."</td>";
			echo "<td $class $style colspan='1'>Target ".$year."</td>";
			echo "<td $class $style colspan='1'>Real ".$year."</td>";
    	}
    }

    public function renderDataModal($brand, $quarter, $brandPos, $firstColor, $secondColor, $thirdColor, $fourthColor){
    	
    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		echo "<td $firstClass>$brand</td>";
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(($quarter[0][$j][$brandPos]+$quarter[1][$j][$brandPos]))."</td>";
		}
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(($quarter[2][$j][$brandPos]+$quarter[3][$j][$brandPos]))."</td>";
		}
		for ($i=0; $i < 3; $i++) { 

			if ($i == 0) {
				$class = $secondClass;
			}elseif ($i == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(
				(
					$quarter[0][$i][$brandPos]+$quarter[1][$i][$brandPos]+
					$quarter[2][$i][$brandPos]+$quarter[3][$i][$brandPos]
				)
				).
				"</td>";
		}

    }
}
