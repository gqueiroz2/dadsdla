<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\excel;

class excelBasics extends excel{

	public function values($spreadSheet,$columns,$nextColumns = false){

        $values = "";
		for ($c=1; $c < sizeof($columns); $c++) { 
            if($nextColumns){   
                if($nextColumns[$c] == "gross" || $nextColumns[$c] == "net" || $nextColumns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$nextColumns[$c]],5)."\"";
                }else{
                    $values .= "\"".  str_replace("\\", "\\\\", $spreadSheet[$nextColumns[$c]] )."\"";
                }
            }else{
                if($columns[$c] == "gross" || $columns[$c] == "net" || $columns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$columns[$c]],5)."\"";
                }else{
                    $values .= "\"".  str_replace("\\", "\\\\", $spreadSheet[$columns[$c]] )."\"";
                }
            }
            if($c != (sizeof($columns) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
	}

	public function assembler($spreadSheet,$columns,$base,$table = false){
        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			for ($c=0; $c < sizeof($columns); $c++) { 
                $bool = $this->searchEmptyStrings($spreadSheet[$s],$columns);
				if($bool){
					if($columns[$c] == 'gross_revenue' ||
                       $columns[$c] == 'gross' ||
					   $columns[$c] == 'net_revenue' ||						
                       $columns[$c] == 'net' ||                     
					   $columns[$c] == 'net_net_revenue' ||						
					   $columns[$c] == 'gross_revenue_prate' ||
					   $columns[$c] == 'net_revenue_prate' ||						
					   $columns[$c] == 'net_net_revenue_prate' ||						
					   $columns[$c] == 'revenue' ||
					   $columns[$c] == 'campaign_option_spend' ||
					   $columns[$c] == 'spot_duration' ||
				       $columns[$c] == 'impression_duration' ||
                       $columns[$c] == 'num_spot'
				      ){

						if( is_null($spreadSheet[$s][$c])){
							$columnValue = $c + 1;
							//$c++;
						}else{
							$columnValue = $c;
						}
						$spreadSheetV2[$s][$columns[$c]] = $this->fixExcelNumber( trim($spreadSheet[$s][$columnValue]) );
					}else{
						if($columns[$c] == 'campaign_option_start_date'){
                            $temp = $base->formatData("dd/mm/aaaa","aaaa-mm-dd",trim($spreadSheet[$s][$c]));
                            $spreadSheetV2[$s][$columns[$c]] = $temp;
						}elseif($columns[$c] == 'obs'){
                            $spreadSheetV2[$s][$columns[$c]] = "OBS";
                        }elseif($columns[$c] == 'month'){
							if($table){
                                $spreadSheetV2[$s][$columns[$c]] = $base->monthToIntCMAPS(trim($spreadSheet[$s][$c]));
                            }else{
                                $spreadSheetV2[$s][$columns[$c]] = $base->monthToInt(trim($spreadSheet[$s][$c]));
                            }
						}else{	
							$spreadSheetV2[$s][$columns[$c]] = trim($spreadSheet[$s][$c]);
						}
					}
				}
			}
		}
		$spreadSheetV2 = array_values($spreadSheetV2);
		return $spreadSheetV2;
	}

	public function into($columns){
		$into = "";
		for ($i=1; $i < sizeof($columns); $i++) { 
			$into .= $columns[$i];

			if($i != (sizeof($columns) - 1) ){
				$into .= ", ";
			}
		}
		return $into;
	}

	public function searchEmptyStrings($spreadSheet,$columns){
		$sizeC = sizeof($columns);
		$count = 0;
		$countString = 0;
		for ($c=0; $c < sizeof($spreadSheet); $c++) { 
			if(is_null($spreadSheet[$c]) || 
				 empty($spreadSheet[$c]) || 
				 	   $spreadSheet[$c] == '' 
			   ){
				$count++;
			}
		}
		if($count >= ( $sizeC / 2 ) ){
			return false;
		}else{
			return true;
		}
	}
    
}
