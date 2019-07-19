<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\excel;
use App\base;
use App\region;
use App\brand;
use App\salesRep;
use App\pRate;
use App\agency;
use App\client;
use App\sql;

class chain extends excel{
   
    public function handler($con,$table,$spreadSheet,$year){
		$base = new base();
        $bool = $this->firstChain($con,$table,$spreadSheet,$base,$year);			
        return $bool;
	}

    public function firstChain($con,$table,$spreadSheet,$base,$year){
        $columns = $this->defineColumns($table,'first');
        if($table == "cmaps"){
            $parametter = true;
        }else{
            $parametter = false;
        }

        $spreadSheet = $this->assembler($spreadSheet,$columns,$base,$parametter);
        $into = $this->into($columns);      
        $check = 0;
        
        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $error = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);         
            if(!$error){
                $check++;
            }
        } 
        if($check == sizeof($spreadSheet)){
            $complete = true;
        }else{
            $complete = false;
        }
        return $complete;
    }    

	public function secondChain($sql,$con,$fCon,$sCon,$table,$year = false){
        /*
            FAZER NOVA VERIFICAÇÃO COM CMAPS
        */

        $base = new base();
        $columns = $this->defineColumns($table,'first');
    	$columnsS = $this->defineColumns($table,'second');
        $current = $this->fixToInput($this->selectFromCurrentTable($sql,$fCon,$table,$columns),$columns);
        $into = $this->into($columnsS);		
        $next = $this->handleForNextTable($con,$table,$current,$columns,$year);
        $complete = $this->insertToNextTable($sCon,$table,$columnsS,$next,$into,$columnsS);
   		return $complete;
    }  

    public function thirdChain($sql,$con,$sCon,$tCon,$table){
        /*

            FAZER NOVA VERIFICAÇÃO COM CMAPS

        */
        $base = new base();    	
        $columnsS = $this->defineColumns($table,'second');
    	$columnsT = $this->defineColumns($table,'third');
    	$into = $this->into($columnsT);		
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$sCon,$table,$columnsS),$columnsS);
    	if($table == 'mini_header'){
    		$orderReference = $this->getOrderReferences($current);
    		$cleanedValues = $this->cleanValues($current,$orderReference);
    	}else{
    		$cleanedValues = $current;
    	}
    	
    	$next = $this->handleForLastTable($con,$table,$cleanedValues,$columnsS);

        if($table== 'cmaps'){
           $bool = $this->insertToLastTable($tCon,$table,$columnsT,$next,$into,$columnsS);
        }else{
            $bool = $this->insertToLastTable($tCon,$table,$columnsT,$next,$into);
        }
        return $bool;

    }

     public function thirdToDLA($sql,$con,$tCon,$table,$year,$truncate){
    	
        $base = new base(); 
        if($truncate){
            $truncateStatement = "TRUNCATE TABLE $table";
            if($con->query($truncateStatement) === TRUE){
                $truncated = true;
            }else{
                $truncated = false;
            }
        }else{
            for ($y=0; $y < sizeof($year); $y++) { 
                $delete[$y] = "DELETE FROM $table WHERE(year = '".$year[$y]."')";     
                if($con->query($delete[$y])){
                }
            }
        }        

    	$columns = $this->defineColumns($table,'third');
    	$into = $this->into($columns);
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$tCon,$table,$columns),$columns);
    	$bool = $this->insertToDLA($con,$table,$columns,$current,$into);

        return $bool;
    }   

    public function insert($con,$spreadSheet,$columns,$table,$into,$nextColumns = false){
        //if($nextColumns && ( $table == 'cmaps')){
        //    $values = $this->values($spreadSheet,$columns,$nextColumns);
        //}else{
            $values = $this->values($spreadSheet,$columns);
        //}
        
        $ins = " INSERT INTO $table ($into) VALUES ($values)"; 

        if($con->query($ins) === TRUE ){
            $error = false;
        }else{
            var_dump($spreadSheet['agency']);
            echo "<pre>".($ins)."</pre>";
            var_dump($con->error);
            $error = true;
        }     

        return $error;        
   
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

    public function selectFromCurrentTable($sql,$con,$table,$columns){
    	$res = $sql->select($con,"*",$table);
    	$current = $sql->fetch($res,$columns,$columns);
    	return $current;
    }

    public function handleForLastTable($con,$table,$current,$columns){
    	for ($c=0; $c < sizeof($current); $c++) { 
            if($table == "cmaps"){
                $current[$c]['agency_id'] = $this->seekAgencyID($con,"Brazil",$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,"Brazil",$current[$c]['client']);
            }else{                
                $current[$c]['agency_id'] = $this->seekAgencyID($con,$current[$c]['campaign_sales_office_id'],$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,$current[$c]['campaign_sales_office_id'],$current[$c]['client']);                
            }
        }
              
        /*
    	 	POR ENQUANTO A FUNÇÃO PEGA O ID DA REGIAO E COLOCA NA AGENCIA E NO CLIENTE
    	 	DEPOIS FAZER A FUNÇÃO PEGAR OS ID'S DOS CLIENTES E AGENCIAS 
    	

            var_dump($table);

        if($table == 'cmaps'){
        	for ($c=0; $c < sizeof($current); $c++) { 
                $current[$c]['agency_id'] = 1;
                $current[$c]['client_id'] = 1;
            }
        }else{
                      
        }*/
		return $current;
    }

    public function seekAgencyID($con,$region,$agency){
        $ag = new agency();
        $sql = new sql();

        $agencyID = $ag->getAgencyIDbyAgencyUnit($con,$sql,$agency);
        return($agencyID);
    }

    public function seekClientID($con,$region,$client){
        $cli = new client();
        $sql = new sql();
        $clientID = $cli->getClientIDbyClientUnit($con,$sql,$client);
        return($clientID);
    }


    public function handleForNextTable($con,$table,$current,$columns,$year){
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
    			$tmp = $this->handle($con,$table,$current[$c][$columns[$cc]],$columns[$cc],$regions,$brands,$salesReps,$currencies,$year,$current[$c]);
    			$current[$c][$tmp[1]] = $tmp[0];
    			if($columns[$cc] != $tmp[1]){
    				unset($current[$c][$columns[$cc]]);
    			}
    		}
            if($table == 'cmaps'){
                $current[$c]['year'] = $year;
            }
    	}
		return $current;
    }

    public function handle($con,$table,$current,$column,$regions,$brands,$salesReps,$currencies,$year,$currentC){
        if($column == 'campaign_sales_office'){
			$rtr =  array(false,'campaign_sales_office_id');
			for ($r=0; $r < sizeof($regions); $r++) { 
				if($current == $regions[$r]['name']){	
					$rtr =  array( $regions[$r]['id'],'campaign_sales_office_id');
				}
			}
		}elseif($column == 'package'){
            if($current == 'sim ' || $current == 'SIM' || $current == 'Sim'){
                $bool = 1;
            }else{
                $bool = 0;
            }

            $rtr =  array($bool,'package');
        }elseif($column == 'sales_representant_office'){
			$rtr =  array(false,'campaign_sales_office_id');
			for ($r=0; $r < sizeof($regions); $r++) { 
				if($current == $regions[$r]['name']){	
					$rtr =  array( $regions[$r]['id'],'sales_representant_office_id');
				}
			}
        }elseif($column == 'campaign_currency'){
            $rtr =  array(false,'campaign_currency_id');
        	for ($c=0; $c < sizeof($currencies); $c++) { 
				if($current == $currencies[$c]['name']){	
					$rtr =  array( $currencies[$c]['id'],'campaign_currency_id');
                    break;
				}elseif($current == 'VES'){
                    $rtr =  array( 9,'campaign_currency_id');
                    break;
                }
			}

        }elseif($column == 'brand'){
        	
        	$rtr =  array(false,'brand_id');
            if($table == "cmaps"){
                $temp = strtoupper($current);
            }else{
                $temp = $current;
            }

        	for ($b=0; $b < sizeof($brands); $b++) { 
				if( $temp  == $brands[$b]['brandUnit']){	
					$rtr =  array( $brands[$b]['brandID'],'brand_id');
				}
			}
			
        }elseif($column == 'sales_rep'){
        	$rtr =  array(false,'sales_rep_id');
        	$check = -1;
            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */

            for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
				if($current == $salesReps[$sr]['salesRepUnit']){	
					$rtr =  array( $salesReps[$sr]['salesRepID'],'sales_rep_id');

                    $check++;
				}

                if($check > 0){
                    for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                        if($current == $salesReps[$srr]['salesRepUnit'] &&
                            $currentC['campaign_sales_office_id'] == $salesReps[$srr]['regionID']){
                            $rtr =  array( $salesReps[$srr]['salesRepID'],'sales_rep_id');   
                        }                        
                    }
                }
			}
        }else{
        	$rtr = array($current,$column);
        }
        return $rtr;
    }

    public function insertToDLA($con,$table,$columns,$current,$into){
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		$bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
    		if(!$bool[$c]){
    			$count++;
    		}
    	}

    	if($count == sizeof($current)){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function insertToLastTable($con,$table,$columns,$current,$into,$nextColumns = false){
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		  //var_dump($current[$c]);
            if($table == 'cmaps'){
                $bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into,$nextColumns);
            }else{
                $bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
            }
    		if(!$bool[$c]){
    			$count++;
    		}
    	}

    	if($count == sizeof($current)){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function insertToNextTable($con,$table,$columns,$current,$into,$nextColumns){
    	$count = 0;
    	for ($c=0; $c < sizeof($current); $c++) { 
    		if($nextColumns && ($table == 'cmaps')){
                $error[$c] = $this->insert($con,$current[$c],$columns,$table,$into,$nextColumns);
            }else{
                $error[$c] = $this->insert($con,$current[$c],$columns,$table,$into);
            }
    		if(!$error[$c]){
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
            $column == 'gross' ||
		    $column == 'net_revenue' ||						
            $column == 'net' ||
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
                            if($table == "cmaps"){
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
		for ($i=0; $i < sizeof($columns); $i++) { 
			$into .= $columns[$i];

			if($i != (sizeof($columns) - 1) ){
				$into .= ", ";
			}
		}
		return $into;
	}

	public function values($spreadSheet,$columns,$nextColumns = false){
        $values = "";
		for ($c=0; $c < sizeof($columns); $c++) { 
            if($nextColumns){   
                if($nextColumns[$c] == "gross" || $nextColumns[$c] == "net" || $nextColumns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$nextColumns[$c]],5)."\"";
                }else{
                    $values .= "\"".  addslashes($spreadSheet[$nextColumns[$c]])."\"";
                }
            }else{
                if($columns[$c] == "gross" || $columns[$c] == "net" || $columns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$columns[$c]],5)."\"";
                }else{
                    $values .= "\"".  addslashes($spreadSheet[$columns[$c]])."\"";
                }
            }
            if($c != (sizeof($columns) - 1) ){
				$values .= ", ";
			}
		}
		return $values;
        
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

	public function defineColumns($table,$recurrency){

    	switch ($table) {
    		case 'ytd':
    			switch ($recurrency) {
    				case 'first':
    					return $this->ytdColumnsF;
    					break;
    				case 'second':
    					return $this->ytdColumnsS;
    					break;
    				case 'third':
    					return $this->ytdColumnsT;
    					break;
    				case 'DLA':
    					return $this->ytdColumns;
    					break;
    			}
    			break;

    		case 'mini_header':
    			switch ($recurrency) {
    				case 'first':
    					return $this->miniHeaderColumnsF;
    					break;
    				case 'second':
    					return $this->miniHeaderColumnsS;
    					break;
    				case 'third':
    					return $this->miniHeaderColumnsT;
    					break;
    				case 'DLA':
    					return $this->miniHeaderColumns;
    					break;
    			}
    			break;

    		case 'cmaps':
    			switch ($recurrency) {
    				case 'first':
    					return $this->cmapsColumnsF;
    					break;
    				case 'second':
    					return $this->cmapsColumnsS;
    					break;
    				case 'third':
    					return $this->cmapsColumnsT;
    					break;
    				case 'DLA':
    					return $this->cmapsColumns;
    					break;
    			}
    			break;
    		
    		
    	}

    }



    public $cmapsColumnsF = array('decode',
                                  'month',
                                  'map_number',
                                  'sales_rep',
                                  'package',
                                  'client',
                                  'product',
                                  'segment',
                                  'agency',
                                  'brand',
                                  'pi_number',
                                  'gross',                                  
                                  'net',
                                  'market',
                                  'discount',
                                  'client_cnpj',
                                  'agency_cnpj',
                                  'media_type',
                                  'log',
                                  'ad_sales_support',
                                  'obs',
                                  'sector',
                                  'category'
                              );

    public $cmapsColumnsS = array('decode',
                                  'year',
                                  'month',
                                  'map_number',                                  
                                  'sales_rep_id',
                                  'package',                                  
                                  'client',
                                  'product',
                                  'segment',
                                  'agency',
                                  'brand_id',
                                  'pi_number',
                                  'gross',                                  
                                  'net',
                                  'market',
                                  'discount',
                                  'client_cnpj',
                                  'agency_cnpj',
                                  'media_type',
                                  'log',
                                  'ad_sales_support',
                                  'obs',
                                  'sector',
                                  'category'
                              );

    public $cmapsColumnsT = array('decode',
                                  'year',
                                  'month',
                                  'map_number',
                                  'sales_rep_id',
                                  'package',                                  
                                  'client_id',
                                  'product',
                                  'segment',
                                  'agency_id',
                                  'brand_id',                                  
                                  'pi_number',
                                  'gross',                                  
                                  'net',
                                  'market',
                                  'discount',
                                  'client_cnpj',
                                  'agency_cnpj',
                                  'media_type',
                                  'log',
                                  'ad_sales_support',
                                  'obs',
                                  'sector',
                                  'category'
                              );


    public $cmapsColumns = array('sales_rep_id',
                                  'client_id',
                                  'agency_id',
                                  'brand_id',                                  
                                  'decode',
                                  'year',
                                  'month',
                                  'map_number',
                                  'package',                                  
                                  'product',
                                  'segment',
                                  'pi_number',
                                  'gross',                                  
                                  'net',
                                  'market',
                                  'discount',
                                  'client_cnpj',
                                  'agency_cnpj',
                                  'media_type',
                                  'log',
                                  'ad_sales_support',
                                  'obs',
                                  'sector',
                                  'category'
                              );

    public $ytdColumnsF = array(
		 					'campaign_sales_office', 
		 					'sales_representant_office',
		 					'year',
		 					'month',
		 					'brand',
		 					'brand_feed',
		 					'sales_rep', 
		 					'client',
		 					'client_product',
		 					'agency',
		 					'order_reference',
		 					'campaign_reference',
		 					'spot_duration',
		 					'campaign_currency',
		 					'impression_duration',
		 					'num_spot',		 								
		 					'gross_revenue',
		 					'net_revenue',
		 					'net_net_revenue',
		 					'gross_revenue_prate',
		 					'net_revenue_prate',
		 					'net_net_revenue_prate'
		 				   );

	public $ytdColumnsS = array(
		 					'campaign_sales_office_id', 
		 					'sales_representant_office_id',
		 					'brand_id',
		 					'sales_rep_id',
		 					'client',
		 					'agency',
		 					'campaign_currency_id', 
		 					'year',
		 					'month',		 					
		 					'brand_feed',
		 					'client_product',		 					
		 					'order_reference',
		 					'campaign_reference',
		 					'spot_duration',		 					
		 					'impression_duration',
		 					'num_spot',		 								
		 					'gross_revenue',
		 					'net_revenue',
		 					'net_net_revenue',
		 					'gross_revenue_prate',
		 					'net_revenue_prate',
		 					'net_net_revenue_prate'
		 				   );

	public $ytdColumnsT = array(
		 					'campaign_sales_office_id', 
		 					'sales_representant_office_id',
		 					'brand_id',
		 					'sales_rep_id',
		 					'client_id',
		 					'agency_id',
		 					'campaign_currency_id', 
		 					'year',
		 					'month',		 					
		 					'brand_feed',
		 					'client_product',		 					
		 					'order_reference',
		 					'campaign_reference',
		 					'spot_duration',		 					
		 					'impression_duration',
		 					'num_spot',		 								
		 					'gross_revenue',
		 					'net_revenue',
		 					'net_net_revenue',
		 					'gross_revenue_prate',
		 					'net_revenue_prate',
		 					'net_net_revenue_prate'
		 				   );

	public $ytdColumns = array(
		 					'campaign_sales_office_id', 
		 					'sales_representant_office_id',
		 					'brand_id',
		 					'sales_rep_id',
		 					'client_id',
		 					'agency_id',
		 					'campaign_currency_id', 
		 					'year',
		 					'month',		 					
		 					'brand_feed',
		 					'client_product',		 					
		 					'order_reference',
		 					'campaign_reference',
		 					'spot_duration',		 					
		 					'impression_duration',
		 					'num_spot',		 								
		 					'gross_revenue',
		 					'net_revenue',
		 					'net_net_revenue',
		 					'gross_revenue_prate',
		 					'net_revenue_prate',
		 					'net_net_revenue_prate'
		 				   );

	public $miniHeaderColumnsF = array('campaign_sales_office',
		                               'sales_representant_office',
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
		                               'sales_representant_office_id',
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
		                               'sales_representant_office_id',
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
    public $miniHeaderColumns = array('campaign_sales_office_id',
                                       'sales_representant_office_id',
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


	

	public $salesRepColumns = array('sales_group_id','name');
	public $salesRepUnitColumns = array('sales_rep_id','origin_id','name');
	
    public $planByBrandColumns = array('sales_office_id','currency_id','brand_id','source','year','month','type_of_revenue','revenue');

}
