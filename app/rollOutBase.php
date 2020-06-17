<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\base;

class rollOutBase extends Model{
    protected $fullMonthEN = array("JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER");			

    public function endHour($row,$column,$mtx){

    	$check = true;
    	$count = 1;
    	while ($check){
    		if( $mtx[($row + $count)]['B'] != "Important Dates" || $mtx[($row + $count)]['B'] != "DAY" || $mtx[($row + $count)]['B'] != "AVG PT"){
    			$endHour = $mtx[($row + $count)]['B'];
    			$check = false;
    		}    		
    		$count++;
    	}

    	return $endHour;

    }

	public function loopThrough($kind,$seek,$where,$mtx){
		$values = array();
		switch ($kind) {
			case 'row':
				for ($i=0; $i < sizeof($mtx); $i++) { 
					if($mtx[$i][$where] == $seek)
						array_push($values, $i);
				}
				break;
			default:
				# code...
				break;
		}

		return $values;
	}

	public function findDateInSpreadsheet($columnHeader,$mtx,$row,$col,$dayRow,$monthRow){
		$base = new base();
		$M = $base->month;
		$dayC= -1;
		for ($d=0; $d < sizeof($dayRow); $d++) { 
			if($row > $dayRow[$d]){
				$dayC = $dayRow[$d];
			}
		}
		$day = intval($mtx[$dayC][$columnHeader[$col]]);
		$month = $mtx[$monthRow][$columnHeader[$col]];
		for ($i=0; $i < sizeof($M); $i++) { 
			if($month == $M[$i][4]){
				$monthAbv = $M[$i][1];
				break;
			}
		}
		
		$date = $base->fixDate("2020",$monthAbv,$day);
		return $date;		
	}

	public function getColumnPivot($sp,$column){
		$pen = 0;
		while ($sp != $column[$pen]) {
			$pen++;			
		}
		return $pen;
	} 

    public function putValuesOnMergedCells($mtx,$columnHeader,$cellsToFix){

    	$mtx = $this->getMasterValueAndPutOnChild($mtx,$columnHeader,$cellsToFix);

    	return $mtx;

    }

    public function getMasterValueAndPutOnChild($mtx,$columnHeader,$cellsToFix){

    	for ($c=0; $c < sizeof($cellsToFix); $c++) { 
    		$masterCell = $cellsToFix[$c]['master'];

    		$temp = $this->splitCellCoordinates($masterCell);

    		$masterCellRow = $temp['row'];
    		$masterCellColumn = $temp['column'];

    		$masterValue = $mtx[$masterCellRow][$masterCellColumn];

    		$childCells = $cellsToFix[$c]['child'];

    		for ($cc=0; $cc < sizeof($childCells); $cc++) { 
	    		$tempC = $this->splitCellCoordinates($childCells[$cc]);

	    		$childCellRow = $tempC['row'];
	    		$childCellColumn = $tempC['column'];	

	    		$mtx[$childCellRow][$childCellColumn] = $masterValue;
    		}
    	}

    	return $mtx;

    }

    public function splitCellCoordinates($coordinate){
    	$temp = preg_split('/(?=\d)/',$coordinate,2);

    	$column = $temp[0];

    	$row = $temp[1];

    	$rtr = array("row" => $row , "column" => $column);

    	return $rtr;

    }

	public function fixMergedLines($previousLine,$line){
		$size = sizeof($line);

		for ($l=2; $l < $size; $l++) { 
			$line[$l] = $previousLine[$l];
		}

		return $line;

	}

	public function removeNullColumns($column,$mtx){

		$fl = $this->checkFirstLine($column,$mtx[1]);

		for ($m=1; $m <= sizeof($mtx); $m++) { 
			for ($n = sizeof($mtx[$m]); $n >= $fl; $n--) { 
				unset($mtx[$m][$column[$n]]);
			}
		}

		return $mtx;

	}

    public function checkLineForNulls($column,$line){
    	
    	$size = sizeof($line);
    	$limit = $size - 2;
    	$cc = 0;
    	for ($i=0; $i < $size; $i++) { 
    		if( is_null($line[$column[$i]]) || $line[$column[$i]] == ''){
    			$cc++;
    		}	
    	}
    	if($cc >= $limit)
    		return false;
    	else
    		return $line;
    }

    public function checkFirstLine($column,$line){
    	$c = 0;
    	$check = false;
    	$pos = false;

    	while( !$check ){
    		if($c > 0){
    			if(is_null($line[$column[$c]])){
	    			if( is_null($line[$column[$c+1]]) ){
						if( is_null($line[$column[$c+2]]) ){
							$check = true;
						}else{
							$c++;
						}
	    			}else{
	    				$c++;
	    			}
	    		}else{
	    			$c++;
	    		}
    		}else{
    			if( is_null($line[$column[$c+1]]) ){
					if( is_null($line[$column[$c+2]]) ){
						$check = true;
					}else{
						$c++;
					}
    			}else{
    				$check = true;
    			}
    		}
    	}

    	return ($c);
    }
}
