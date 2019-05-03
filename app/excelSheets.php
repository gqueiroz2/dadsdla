<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\excel;

class excelSheets extends excel{

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

	public function handler($con,$table,$spreadSheet){
		switch ($table) {
			case 'cmaps':
				$bool = $this->cmaps($con,$table,$spreadSheet);		
				break;			
			case 'mini_header':
				$bool = $this->miniHeader($con,$table,$spreadSheet);
				break;
			case 'plan_by_brand':
				$bool = $this->planByBrand($con,$table,$spreadSheet);

				break;			
			default:
				# code...
				break;
		}
	}

	public function assembler($spreadSheet,$columns){
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			for ($c=0; $c < sizeof($columns); $c++) { 
				$bool = $this->searchEmptyStrings($spreadSheet[$s],$columns);

				if($bool){
					if($columns[$c] == 'gross_revenue' ||
					   $columns[$c] == 'revenue'
				      ){
						$spreadSheetV2[$s][$columns[$c]] = $this->fixExcelNumber( trim($spreadSheet[$s][$c]) );
					}else{
						$spreadSheetV2[$s][$columns[$c]] = trim($spreadSheet[$s][$c]);
					}
				}
			}
		}
		$spreadSheetV2 = array_values($spreadSheetV2);

		return $spreadSheetV2;
	}

	public function into($columns){
		$into = "";
		for ($i=0; $i < sizeof($columns); $i++) { 
			$into .= $columns[$i];

			if($i != (sizeof($columns) - 1) ){
				$into .= ", ";
			}
		}
		return $into;
	}

	public function values($spreadSheet,$columns){
		$values = "";
		for ($c=0; $c < sizeof($columns); $c++) { 
			$values .= " \" ".$spreadSheet[$columns[$c]]." \" ";

			if($c != (sizeof($columns) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
	}

	public function insert($con,$spreadSheet,$columns,$table,$into){
		$values = $this->values($spreadSheet,$columns);
		$ins = " INSERT INTO $table ($into) VALUES ($values)";

		if($con->query($ins) === TRUE ){
			$error = false;
		}else{
			if($table == 'cmaps'){
				$error = $spreadSheet['decode'];
			}elseif($table == 'mini_header'){
				$error = array($spreadSheet['campaign_reference'],$spreadSheet['order_reference']);
			}elseif($table = 'plan_by_brand'){
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = true;
			}else{
				$error = true;
			}
		}
		return $error;
	}

	public function cmaps($con,$table,$spreadSheet){
		$columns = $this->cmapsColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public function miniHeader($con,$table,$spreadSheet){
		$columns = $this->miniHeaderColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public function planByBrand($con,$table,$spreadSheet){
		$columns = $this->planByBrandColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public $planByBrandColumns = array('sales_office_id','currency_id','brand_id','source','year','type_of_revenue','month','revenue');

	public $miniHeaderColumns = array('campaign_sales_office_id','sales_rep_sales_office_id','brand_id','sales_rep_id','client_id','agency_id','campaign_currency_id','sales_group_id','year','month','brand_feed','sales_rep_role','order_reference','campaign_reference','campaign_status_id','campaign_option_desc','campaign_class_id','campaign_option_start_date','campaign_option_target_spot','campaign_option_spend','gross_revenue');

	public $cmapsColumns = array('sales_group_id','sales_rep_id','client_id','agency_id','brand_id','decode','year','month','map_number','package','product','segment','pi_number','gross','net','market','discount','client_cnpj','agency_cnpj','media_type','log','ad_sales_support','obs','sector','category');

}
