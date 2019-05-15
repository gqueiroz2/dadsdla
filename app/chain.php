<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\excel;
use App\base;
use App\region;
use App\brand;
use App\salesRep;
use App\pRate;

class chain extends excel{
    public function thirdToDLA($sql,$con,$tCon,$table){
    	var_dump("FINALLY TO DLA");
    	$base = new base(); 
    	$columns = $this->miniHeaderColumnsT;
    	$into = $this->into($columns);
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$tCon,$table,$columns),$columns);
    	$bool = $this->insertToDLA($con,$table,$columns,$current,$into);

    	
    }

    public function thirdChain($sql,$con,$sCon,$tCon,$table){
    	$base = new base();    	
    	$columnsS = $this->miniHeaderColumnsS;
    	$columnsT = $this->miniHeaderColumnsT;
    	$into = $this->into($columnsT);		
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$sCon,$table,$columnsS),$columnsS);
    	$orderReference = $this->getOrderReferences($current);
    	$cleanedValues = $this->cleanValues($current,$orderReference);
    	$next = $this->handleForLastTable($con,$cleanedValues,$columnsS);
    	$bool = $this->insertToLastTable($tCon,$table,$columnsT,$next,$into);
		
    }

    public function cleanValues($current,$orderReference){
    	for ($o=0; $o < sizeof($orderReference); $o++) { 
    		for ($c=0; $c < sizeof($current); $c++) {     		
    			
    			if($orderReference[$o] == $current[$c]['order_reference']){
    				if($current[$c]['sales_rep_role'] == 'Sales Representitive'){
    					$current[$c]['campaign_option_spend'] = (doubleval($current[$c]['campaign_option_spend'])/2);
    				}
    				if($current[$c]['sales_rep_role'] == 'Primary Sales Rep'){
    					$current[$c]['campaign_option_spend'] = (doubleval($current[$c]['campaign_option_spend'])/2);
    				}


    			}

    		}	
    	}
    	return $current;
    }


    public function getOrderReferences($current){

    	$or = array();

    	for ($c=0; $c < sizeof($current); $c++) { 
	    	if($current[$c]['sales_rep_role'] == 'Sales Representitive'){
	    		$or[] = $current[$c]['order_reference'];
	    	}
	    }

	    $or = array_values(array_unique($or));

    	sort($or);

    	return($or);
    }

    public function secondChain($sql,$con,$fCon,$sCon,$table){
    	$base = new base();
    	$columns = $this->miniHeaderColumnsF;
    	$columnsS = $this->miniHeaderColumnsS;
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$fCon,$table,$columns),$columns);
    	$into = $this->into($columnsS);		
    	$next = $this->handleForNextTable($con,$current,$columns);
   		$bool = $this->insertToNextTable($sCon,$table,$columnsS,$next,$into);

   		return $bool;
    }



    public function selectFromCurrentTable($sql,$con,$table,$columns){
    	$res = $sql->select($con,"*",$table);
    	$current = $sql->fetch($res,$columns,$columns);
    	return $current;
    }

    public function handleForLastTable($con,$current,$columns){
    	
    	/*

    	 	POR ENQUANTO A FUNÇÃO PEGA O ID DA REGIAO E COLOCCA NA AGENCIA E NO CLIENTE

    	 	DEPOIS FAZER A FUNÇÃO PEGAR OS ID'S DOS CLIENTES E AGENCIAS 

    	*/

    	for ($c=0; $c < sizeof($current); $c++) { 
    		$current[$c]['agency_id'] = $current[$c]['campaign_sales_office_id'];
    		$current[$c]['client_id'] = $current[$c]['campaign_sales_office_id'];
    	}

		return $current;


    }


    public function handleForNextTable($con,$current,$columns){
    	$r = new region;
    	$sr = new salesRep();
    	$b = new brand();
    	$pr = new pRate();

    	$regions = $r->getRegion($con);
    	$brands = $b->getBrandUnit($con);
    	$salesReps = $sr->getSalesRepUnit($con);
    	$currencies = $pr->getCurrency($con);

    	for ($c=0; $c < sizeof($current); $c++) { 
    		for ($cc=0; $cc < sizeof($columns); $cc++) { 
    			//$current[$c][$cc] = $this->handle($current[$c][$columns[$cc]],$columns[$cc]);

    			$tmp = $this->handle($con,$current[$c][$columns[$cc]],$columns[$cc],$regions,$brands,$salesReps,$currencies);
    			$current[$c][$tmp[1]] = $tmp[0];
    			if($columns[$cc] != $tmp[1]){
    				unset($current[$c][$columns[$cc]]);
    			}
    		}
    	}

		return $current;


    }

    public function handle($con,$current,$column,$regions,$brands,$salesReps,$currencies){
    		if($column == 'campaign_sales_office'){
    			
    			$rtr =  array(false,'campaign_sales_office_id');

    			for ($r=0; $r < sizeof($regions); $r++) { 
    				if($current == $regions[$r]['name']){	
    					$rtr =  array( $regions[$r]['id'],'campaign_sales_office_id');
    				}
    			}
    			
    		}elseif($column == 'sales_representant_sales_office'){
    			
    			$rtr =  array(false,'campaign_sales_office_id');

    			for ($r=0; $r < sizeof($regions); $r++) { 
    				if($current == $regions[$r]['name']){	
    					$rtr =  array( $regions[$r]['id'],'sales_representant_sales_office_id');
    				}
    			}
    			
            }elseif($column == 'campaign_currency'){
            	$rtr =  array(false,'campaign_currency_id');

            	for ($c=0; $c < sizeof($currencies); $c++) { 
    				if($current == $currencies[$c]['name']){	
    					$rtr =  array( $currencies[$c]['id'],'campaign_currency_id');
    				}
    			}
            }elseif($column == 'brand'){
            	
            	$rtr =  array(false,'brand_id');
            	
            	for ($b=0; $b < sizeof($brands); $b++) { 
    				if($current == $brands[$b]['brandUnit']){	
    					$rtr =  array( $brands[$b]['brandID'],'brand_id');
    				}
    			}
    			
            }elseif($column == 'sales_rep'){
            	for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
    				if($current == $salesReps[$sr]['salesRepUnit']){	
    					$rtr =  array( $salesReps[$sr]['salesRepID'],'sales_rep_id');
    				}
    			}
            }/*elseif(){

            }*/else{
            	$rtr = array($current,$column);
            }

            

            return $rtr;

    }

    public function insertToDLA($con,$table,$columns,$current,$into){
    	var_dump($columns);
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		$bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
    		if($bool[$c]){
    			$count++;
    		}
    	}

    	if($count == sizeof($current)){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function insertToLastTable($con,$table,$columns,$current,$into){
    	var_dump($columns);
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		$bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
    		if($bool[$c]){
    			$count++;
    		}
    	}

    	if($count == sizeof($current)){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function insertToNextTable($con,$table,$columns,$current,$into){
    	var_dump($columns);
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		$bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
    		if($bool[$c]){
    			$count++;
    		}
    	}

    	if($count == sizeof($current)){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function fixToInput($array,$columns){
    	$sizeA = sizeof($array);
    	$sizeC = sizeof($columns);
    	for ($a=0; $a < $sizeA; $a++) { 
    		for ($c=0; $c < $sizeC; $c++) { 
    			$fix[$a][$columns[$c]] = $this->fix($columns[$c],$array[$a][$columns[$c]]);
    		}
    	}
    	return $fix;
    }

    public function fix($column,$toFix){
    	if( $column == 'gross_revenue' ||
		    $column == 'net_revenue' ||						
		    $column == 'net_net_revenue' ||						
		    $column == 'gross_revenue_prate' ||
		    $column == 'net_revenue_prate' ||						
		    $column == 'net_net_revenue_prate' ||						
		    $column == 'revenue' ||
		    $column == 'campaign_option_spend' ||
		    $column == 'spot_duration' ||
	        $column == 'impression_duration' ||
	        $column == 'month' ||
	        $column == 'year' ||
            $column == 'num_spot'
    	  ){
    			if($column == 'month' ||
	               $column == 'year'){
    				$toFix = intval($toFix);
    			}else{
    				$toFix = doubleval($toFix);
    			}
    	}else{
    		$toFix = $toFix;
    	}
    	return($toFix);
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

	public function handler($con,$table,$spreadSheet){
		$base = new base();
		switch ($table) {
			case 'cmaps':
				$bool = $this->cmaps($con,$table,$spreadSheet,$base);		
				break;			
			case 'mini_header':
				var_dump("MINI_HEADER");
				$bool = $this->miniHeader($con,$table,$spreadSheet,$base);
				break;
			case 'plan_by_brand':
				$bool = $this->planByBrand($con,$table,$spreadSheet,$base);
				break;			
			case 'sales_rep':
				$bool = $this->salesRep($con,$table,$spreadSheet,$base);
				break;
			case 'sales_rep_unit':
				$bool = $this->salesRepUnit($con,$table,$spreadSheet,$base);
				break;			
			case 'ytd':
				$bool = $this->ytd($con,$table,$spreadSheet,$base);
				break;
			default:
				# code...
				break;
		}
		return $bool;
	}

	public function assembler($spreadSheet,$columns,$base){
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
						if( is_null($spreadSheet[$s][$c])){
							$columnValue = $c + 1;
						}else{
							$columnValue = $c;
						}

						$spreadSheetV2[$s][$columns[$c]] = $this->fixExcelNumber( trim($spreadSheet[$s][$columnValue]) );

					}else{
						
						if($columns[$c] == 'campaign_option_start_date'){
							$spreadSheetV2[$s][$columns[$c]] = $base->formatData("dd/mm/aaaa","aaaa-mm-dd",trim($spreadSheet[$s][$c]));
						}elseif($columns[$c] == 'month'){
							$spreadSheetV2[$s][$columns[$c]] = $base->monthToInt(trim($spreadSheet[$s][$c]));
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
			$values .= "\"".  str_replace("\\", "\\\\", $spreadSheet[$columns[$c]] )."\"";
			if($c != (sizeof($columns) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
	}

	public function insert($con,$spreadSheet,$columns,$table,$into){
		$values = $this->values($spreadSheet,$columns);
		$ins = " INSERT INTO $table ($into) VALUES ($values)";
		echo($ins)."<br>";		
		
		if($con->query($ins) === TRUE ){
			$error = false;
		}else{
			if($table == 'cmaps'){
				$error = $spreadSheet['decode'];
			}elseif($table == 'mini_header'){
				//var_dump($ins);
				echo($ins)."<br>";		
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

	public function miniHeader($con,$table,$spreadSheet,$base){
		$columns = $this->miniHeaderColumnsF;
		$spreadSheet = $this->assembler($spreadSheet,$columns,$base);
		$into = $this->into($columns);		
		$check = 0;
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$bool = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);			
			if($bool){
				$check++;
			}
		}			
		$rtr = false;
		if($check == sizeof($spreadSheet)){
			$rtr = true;
		}

		return $rtr;
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

	public $miniHeaderColumnsF = array('campaign_sales_office',
		                               'sales_representant_sales_office',
		                               'year',
		                               'month',
		                               'brand',
		                               'brand_feed',
		                               'sales_rep_role',
		                               'sales_rep',
		                               'agency',
		                               'client',
		                               'order_reference',
		                               'campaign_reference',
		                               'campaign_currency',
		                               'campaign_status',
		                               'campaign_option_desc',
		                               'campaign_class',
		                               'campaign_option_start_date', //DATE
		                               'campaign_option_target_spot', // INT
		                               'campaign_option_spend', // DOUBLE
		                               'gross_revenue' // DOUBLE
		                              );

	public $miniHeaderColumnsS = array('campaign_sales_office_id',
		                               'sales_representant_sales_office_id',
		                               'brand_id',
		                               'sales_rep_id',
		                               'client',
		                               'agency',
		                               'campaign_currency_id',
		                               'year',
		                               'month',		                               
		                               'brand_feed',
		                               'sales_rep_role',
		                               'order_reference',
		                               'campaign_reference',
		                               'campaign_status',
		                               'campaign_option_desc',
		                               'campaign_class',
		                               'campaign_option_start_date', //DATE
		                               'campaign_option_target_spot', // INT
		                               'campaign_option_spend', // DOUBLE
		                               'gross_revenue' // DOUBLE
		                              );

	public $miniHeaderColumnsT = array('campaign_sales_office_id',
		                               'sales_representant_sales_office_id',
		                               'brand_id',
		                               'sales_rep_id',
		                               'client_id',
		                               'agency_id',
		                               'campaign_currency_id',
		                               'year',
		                               'month',		                               
		                               'brand_feed',
		                               'sales_rep_role',
		                               'order_reference',
		                               'campaign_reference',
		                               'campaign_status',
		                               'campaign_option_desc',
		                               'campaign_class',
		                               'campaign_option_start_date', //DATE
		                               'campaign_option_target_spot', // INT
		                               'campaign_option_spend', // DOUBLE
		                               'gross_revenue' // DOUBLE
		                              );

	public $miniHeaderColumns = array('campaign_sales_office_id','sales_rep_sales_office_id','brand_id','sales_rep_id','client_id','agency_id','campaign_currency_id','sales_group_id','year','month','brand_feed','sales_rep_role','order_reference','campaign_reference','campaign_status_id','campaign_option_desc','campaign_class_id','campaign_option_start_date','campaign_option_target_spot','campaign_option_spend','gross_revenue');

	public $cmapsColumns = array('sales_group_id','sales_rep_id','client_id','agency_id','brand_id','decode','year','month','map_number','package','product','segment','pi_number','gross','net','market','discount','client_cnpj','agency_cnpj','media_type','log','ad_sales_support','obs','sector','category');

	public $salesRepColumns = array('sales_group_id','name');
	public $salesRepUnitColumns = array('sales_rep_id','origin_id','name');
	public $ytdColumns = array('campaign_sales_office_id', 'sales_representant_office_id','brand_id','sales_rep_id', 'client_id','agency_id','campaign_currency_id','year','month','brand_feed','client_product','order_reference','campaign_reference','spot_duration','impression_duration','num_spot','gross_revenue','net_revenue','net_net_revenue','gross_revenue_prate','net_revenue_prate','net_net_revenue_prate');
}
