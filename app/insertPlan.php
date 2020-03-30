<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\excel;

class insertPlan extends excel{
    
	public function baseSales(){
    	$db = new dataBase();
		$i = new import();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$table = Request::get('table');
		$year = Request::get('year');
		$region = Request::get('region');
		$spreadSheet = $i->base();
		$column = array("region_id","sales_rep_id","brand_id","currency_id","month","type_of_revenue","value","year");
		$spreadSheet = $this->fixSpreadSheet($spreadSheet,$column);

		$del = 0;

		for ($r=0; $r <  sizeof($region); $r++) { 
			
			$delete[$r] = "DELETE FROM $table WHERE(year = '".$year."') AND (region_id = '".$region[$r]."')";

			if($con->query($delete[$r]) === TRUE ){
				$del ++;
			}

		}		
		
		if( $del == sizeof($region) ){
			$into = $this->into($column);

			$check = 0;

			for ($s=0; $s < sizeof($spreadSheet); $s++) { 
				$bool = $this->insert($con,$spreadSheet[$s],$column,$table,$into);
				if(!$bool){
					$check++;
				}
			}
		}else{
			return back()->with('insertError',"There was and error on the rows delete :( ");
		}

		if(sizeof($spreadSheet) == $check){
			$rtr = true;
		}else{
			$rtr = false;
		}

		return $rtr;

    }

    public function baseBrand(){
    	$db = new dataBase();
		$i = new import();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$table = Request::get('table');
		$year = Request::get('year');
		$source = Request::get('source');
		$spreadSheet = $i->base();

		$column = array("sales_office_id","currency_id","brand_id","source","year","month","type_of_revenue","revenue");

		$spreadSheet = $this->fixSpreadSheet($spreadSheet,$column);

		$delete = "DELETE FROM plan_by_brand WHERE(year = '".$year."') AND (source = '".$source."')";

		if($con->query($delete) === TRUE ){
			$del = true;
		}else{
			$del = false;
		}
		
		$into = $this->into($column);

		$check = 0;

		if($del){
			for ($s=0; $s < sizeof($spreadSheet); $s++) { 
				$bool = $this->insert($con,$spreadSheet[$s],$column,$table,$into);
				if(!$bool){
					$check++;
				}
			}
		}else{
			return back()->with('insertError',"There was and error on the rows delete :( ");
		}

		if(sizeof($spreadSheet) == $check){
			$rtr = true;
		}else{
			$rtr = false;
		}

		return $rtr;	

    }

    public function insert($con,$spreadSheet,$column,$table,$into){
		
		$values = $this->values($spreadSheet,$column);

		$ins = " INSERT INTO $table ($into) VALUES ($values)"; 

		if($con->query($ins) === TRUE ){
            $error = false;
        }else{
            echo "<pre>".($ins)."</pre>";
            var_dump($con->error);
            $error = true;
        }     

        return $error;   

	}

	public function into($column){
		$into = "";
		for ($i=0; $i < sizeof($column); $i++) { 
			$into .= $column[$i];

			if($i != (sizeof($column) - 1) ){
				$into .= ", ";
			}
		}
		return $into;
	}

	public function values($spreadSheet,$column){

        $values = "";
		for ($c=0; $c < sizeof($column); $c++) { 
           
            if($column[$c] == "gross" || $column[$c] == "net" || $column[$c] == "discount"){
                $values .= "\"".round($spreadSheet[$column[$c]],5)."\"";
            }else{
                $values .= "\"".  str_replace("\\", "\\\\", $spreadSheet[$column[$c]] )."\"";
            }
            
            if($c != (sizeof($column) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
        
	}

	public function fixSpreadSheet($spreadSheet,$column){

		$head = $column;		

		unset($spreadSheet[0]);
		$spreadSheet = array_values($spreadSheet);

		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			for ($h=0; $h < sizeof($head); $h++) { 

				if($head[$h] == "value" || $head[$h] == "revenue"){					
					$spreadSheetV2[$s][$head[$h]] = $this->fixExcelNumber(trim( $spreadSheet[$s][$h] ));
				}else{
					$spreadSheetV2[$s][$head[$h]] = $spreadSheet[$s][$h];
				}
			}
		}

		$rtr = $spreadSheetV2;

		return $rtr;
	}
}
