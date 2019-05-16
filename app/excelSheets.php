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
				var_dump("MINI_HEADER");
				$bool = $this->miniHeader($con,$table,$spreadSheet);
				break;
			case 'plan_by_brand':
				$bool = $this->planByBrand($con,$table,$spreadSheet);
				break;			
			case 'sales_rep':
				$bool = $this->salesRep($con,$table,$spreadSheet);
				break;
			case 'sales_rep_unit':
				$bool = $this->salesRepUnit($con,$table,$spreadSheet);
				break;
			case 'sales_rep_status':
				$bool = $this->salesRepStatus($con,$table,$spreadSheet);
				break;			
			case 'ytd':
				$bool = $this->ytd($con,$table,$spreadSheet);
				break;
			case 'brand_unit':
				$bool = $this->brandUnit($con,$table,$spreadSheet);
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
					   $columns[$c] == 'net_revenue' ||						
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
			$values .= "\"".$spreadSheet[$columns[$c]]."\"";

			if($c != (sizeof($columns) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
	}

	public function insert($con,$spreadSheet,$columns,$table,$into){
		$values = $this->values($spreadSheet,$columns);
		$ins = " INSERT INTO $table ($into) VALUES ($values)";
		var_dump($ins)."<br>";
		
		if($con->query($ins) === TRUE ){
			var_dump("FOI");

			$error = false;
		}else{
			if($table == 'cmaps'){
				$error = $spreadSheet['decode'];
			}elseif($table == 'mini_header'){
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = array($spreadSheet['campaign_reference'],$spreadSheet['order_reference']);
			}elseif($table = 'plan_by_brand'){
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = true;
			}elseif($table = 'ytd'){
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = true;
			}elseif($table = 'sales_rep' || $table = 'sales_rep_unity'){
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = true;
			}else{
				var_dump($ins);
				var_dump(mysqli_error($con));
				$error = true;
			}
		}
		
		return $error;
		
	}

	public function brandUnit($con,$table,$spreadSheet){
		$columns = $this->brandUnit;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);

		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;

	}

	public function ytd($con,$table,$spreadSheet){
		$columns = $this->ytdColumns;		
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public function cmaps($con,$table,$spreadSheet){
		$columns = $this->cmapsColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			//$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public function miniHeader($con,$table,$spreadSheet){
		$columns = $this->miniHeaderColumns;
		var_dump($columns);
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		var_dump($spreadSheet);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;
	}

	public function salesRep($con,$table,$spreadSheet){
		$columns = $this->salesRepColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}
	}

	public function salesRepUnit($con,$table,$spreadSheet){
		$columns = $this->salesRepUnitColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}
	}

	public function salesRepStatus($con,$table,$spreadSheet){
		$columns = $this->salesRepStatusColumns;
		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);		
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}
	}

	public function planByBrand($con,$table,$spreadSheet){
		$columns = $this->planByBrandColumns;

		$spreadSheet = $this->assembler($spreadSheet,$columns);
		$into = $this->into($columns);

		$del = "DELETE FROM plan_by_brand WHERE (source = 'TARGET')";
		if($con->query($del) == TRUE){
			var_dump("DELETOU");
		}

		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool[$s] = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
		}			
		return $bool;

	}

	public $planByBrandColumns = array('sales_office_id','currency_id','brand_id','source','year','month','type_of_revenue','revenue');

	public $miniHeaderColumns = array('campaign_sales_office_id','sales_rep_sales_office_id','brand_id','sales_rep_id','client_id','agency_id','campaign_currency_id','sales_group_id','year','month','brand_feed','sales_rep_role','order_reference','campaign_reference','campaign_status_id','campaign_option_desc','campaign_class_id','campaign_option_start_date','campaign_option_target_spot','campaign_option_spend','gross_revenue');

	public $cmapsColumns = array('sales_group_id','sales_rep_id','client_id','agency_id','brand_id','decode','year','month','map_number','package','product','segment','pi_number','gross','net','market','discount','client_cnpj','agency_cnpj','media_type','log','ad_sales_support','obs','sector','category');

	public $salesRepColumns = array('sales_group_id','name');
	public $salesRepUnitColumns = array('sales_rep_id','origin_id','name');
	public $salesRepStatusColumns = array('sales_rep_id','status','year');
	public $ytdColumns = array('campaign_sales_office_id', 'sales_representant_office_id','brand_id','sales_rep_id', 'client_id','agency_id','campaign_currency_id','year','month','brand_feed','client_product','order_reference','campaign_reference','spot_duration','impression_duration','num_spot','gross_revenue','net_revenue','net_net_revenue','gross_revenue_prate','net_revenue_prate','net_net_revenue_prate');
	public $brandUnit = array('brand_id','origin_id','name');

}
