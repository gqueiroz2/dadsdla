<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rollOutBase;

class rollOut extends rollOutBase{

	public function searchPatterns($parametter){
		$rowStart = 5;
		$columnStart = "C";

		$rtr = array("rowStart" => $rowStart, "columnStart" => $columnStart);

		return $rtr;
	}

	public function structureSpreadsheet($mtx,$column,$brand){
		$structure = array();

		$searchPatterns = $this->searchPatterns("Roll Out");
		$rs = $searchPatterns['rowStart'];
		$cs = $this->getColumnPivot($searchPatterns['columnStart'],$column);

		$kind1 = "row";
		$seek1 = "DAY";
		$where1 = "B";
		$dayRow = $this->loopThrough($kind1,$seek1,$where1,$mtx);

		$kind2 = "row";
		$seek2 = "2020";
		$monthRow = $this->loopThrough($kind2,$seek2,$where1,$mtx)[0];

		for ($r= $rs; $r < sizeof($mtx); $r++) { # ROWS
			for ($c=$cs; $c < sizeof($mtx[$r]); $c++) { # COLUMNS
				if( strlen($mtx[$r][$column[$c]]) != 0 ){
					$date = $this->findDateInSpreadsheet($column,$mtx,$r,$c,$dayRow,$monthRow);
					$dayOfTheWeek = $mtx[$r]['A'];
					$startHour = $mtx[$r]['B'];
					$endHour = $this->endHour($r,$c,$mtx);
					$info = array(
									"program" => $mtx[$r][$column[$c]],
									"brand" => $brand[1],
									"date" => $date,
									"dayOfTheWeek" => $dayOfTheWeek,
									"startHour" => $startHour,
									"endHour" => $endHour,
					             );
					array_push($structure, $info);
				}
			}
		}
		
		return $structure;
	}

	public function spreadSheetToMatrix($spreadSheet,$column){
		$i = 0;

		foreach ($spreadSheet->getRowIterator() as $row) {
			$j = 0;
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(FALSE);
		    foreach ($cellIterator as $cell) {

		    	$temp = $cell->getFormattedValue();
		    	$removeStartSlash = str_replace("[","",$temp);
		    	$removeEndSlash = str_replace("]","",$removeStartSlash);

		    	$value = $removeEndSlash;

		    	$newSpreadSheet[$i+1][$column[$j]] = $value;		        
		        $j++;
		    }
		    $i++;
		}

		return $newSpreadSheet;
	}

	public function getMergedCells($spreadSheet){
		
		$getMergedCells = $spreadSheet->getMergeCells();

		$count = 0;
		foreach ($getMergedCells as $getMergedCells) {
			$mergedCells[$count] = $getMergedCells;
			$count ++;
		}

		sort($mergedCells);

		return $mergedCells;
	}

    public function workOnMergedCells($merge,$columnHeader){

    	for ($m=0; $m < sizeof($merge); $m++) { 
    		$detail[$m] = $this->workOnEachOne($merge[$m],$columnHeader);
    	}

    	return $detail;

    }

    public function workOnEachOne($each,$columnHeader){
    	$pattern = '/(?=\d)/';
    	$temp = explode(":", $each);
    	$master = $this->getMaster($temp);
    	$child = $this->getChildren($master,$temp,$columnHeader);
		$rtr = array(
				"master" => $master,
				"child" => $child
		);

		return $rtr;
    }

    public function getChildren($master,$interval,$columnHeader){

    	for ($i=0; $i < sizeof($interval); $i++) { 
    		$worked[$i] = preg_split('/(?=\d)/',$interval[$i],2);
    	}

    	$child = $this->defineCellRange($worked,$columnHeader);
    	
    	return $child;
    }

    public function defineCellRange($worked,$columnHeader){

    	if( ($worked[0][0] == $worked[1][0]) && ($worked[0][1] != $worked[1][1]) ){ # Compara se as colunas são iguais e se as linhas são diferentes
    		$child = array();
    		$column = $worked[0][0];
			$start = intval($worked[0][1]);
			$end = intval($worked[1][1]);
			$diff = $end - $start;
			for ($i=$diff; $i > 0 ; $i--) { 
				array_push($child, $column.($start+$i));
			}
    	}else if( ($worked[0][0] != $worked[1][0]) && ($worked[0][1] == $worked[1][1]) ){ # Compara se as colunas forem diferentes e se as linhas forem iguais
    		$pivotStart = $worked[0][0];
    		$pivotEnd = $worked[1][0];
    		$row = $worked[0][1];
    		$child = $this->columnsBetweenPivots($columnHeader,$pivotStart,$pivotEnd,$row);
    	}else{ # Se as linhas e colunas forem diferentes
    		$columnStart = $worked[0][0];
    		$columnEnd = $worked[1][0];

    		$rowStart = $worked[0][1];
    		$rowEnd = $worked[1][1];

    		$child = $this->squareBetweenPivots($columnHeader,$rowStart,$rowEnd,$columnStart,$columnEnd);
    	}

    	return $child;
    }

    public function squareBetweenPivots($ch,$rs,$re,$cs,$ce){
    	$child = array();
    	for ($i=$rs; $i <= $re; $i++) { 
    		$pivot = $this->findPivot($ch,$cs);
	    	while ($ce != $ch[$pivot]) {
	    		array_push($child, $ch[$pivot].$i);
	    		$pivot++;
	    	}	
	    	array_push($child, $ch[$pivot].$i);
    	}
    	unset($child[0]); # Retira o primeiro elemento do array que é o master
    	$child = array_values($child);
    	return $child;
    }

    public function findPivot($ch,$ps){
    	for ($c=0; $c < sizeof($ch); $c++) { 
    		if($ps == $ch[$c]){
    			$pivot = $c;
    			break;
    		}
    	}

    	return $pivot;
    }

    public function columnsBetweenPivots($ch,$ps,$pe,$row){

    	$col = array();

    	$pivot = $this->findPivot($ch,$ps);
    	
    	$pivot++; # Incrementa 1 ao Pivot para eliminar o "master"

    	while ($pe != $ch[$pivot]) {
    		array_push($col, $ch[$pivot].$row);
    		$pivot++;
    	}

    	array_push($col, $ch[$pivot].$row);

    	return $col;

    }

    public function getMaster($exploded){
    	return $exploded[0];
    }


	public function handleExcel($pattern,$mtx,$columnHeader,$cellsToFix){
		$mtx = $this->putValuesOnMergedCells($mtx,$columnHeader,$cellsToFix);
		for ($m=1; $m <= sizeof($mtx); $m++) { 
			$validation[$m] = $this->checkLineForNulls($columnHeader,$mtx[$m]);
		}
		for ($m=1; $m < sizeof($mtx); $m++) { 
			if(!$validation[$m]){
				unset($mtx[$m]);
			}
		}
		$mtx = array_values($mtx);
		return $mtx;
	}



}
