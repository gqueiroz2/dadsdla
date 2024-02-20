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
use App\dataBase;

class chainCmaps extends excel{
   
    public function handler($con,$table,$spreadSheet,$year){
        $base = new base();
        $bool = $this->firstChain($con,$table,$spreadSheet,$base,$year);			
        return $bool;
	}

    public function dailyChain($con,$table,$spreadSheet){
        $base = new base();
        $columns = $this->defineColumns('pipeline','DLA');
        $parametter = 'pipeline';
        //var_dump($table);
        $into = $this->into($columns);      
        $check = 0;               
        $mark = 0;
        
        //var_dump($spreadSheet[0]);
        if ($table == 'daily_results') {
            for ($i=0; $i <sizeof($spreadSheet); $i++) { 
                $spreadSheet[$i][0] = $base->formatData('mm/dd/aaaa','aaaa-mm-dd',$spreadSheet[$i][0]);
                $spreadSheet[$i][1] = $base->formatData('mm/dd/aaaa','aaaa-mm-dd',$spreadSheet[$i][1]);
                $spreadSheet[$i][2] = $base->formatData('mm/dd/aaaa','aaaa-mm-dd',$spreadSheet[$i][2]);
                $spreadSheet[$i][3] = $base->monthToIntWBD($spreadSheet[$i][3]);

                for ($d=0; $d <sizeof($spreadSheet[$i]) ; $d++) { 
                    if ($spreadSheet[$i][$d] == '  - ') {
                        $spreadSheet[$i][$d] = 0;
                    }
                    $temp[$i][$d] = $spreadSheet[$i][$d];

                    
                    if ($temp[$i][$d] != $spreadSheet[$i][0] && $temp[$i][$d] != $spreadSheet[$i][1] && $temp[$i][$d] != $spreadSheet[$i][2] && $temp[$i][$d] != $spreadSheet[$i][3] && $temp[$i][$d] != 0) {
                        
                        $spreadSheet[$i][$d] = str_replace(',','',trim($temp[$i][$d]));

                        //var_dump($temp[$i][$d]);
                    }
                    
                }
            }
            
            //var_dump($spreadSheet);
            for ($s=0; $s < sizeof($spreadSheet); $s++) {             
                $error = $this->insert($con,$spreadSheet[$s],$columns,'daily_results',$into);         
                if(!$error){
                    $check++;
                }            
            }
        }else{
            for ($s=0; $s < sizeof($spreadSheet); $s++) {             
                $error = $this->insert($con,$spreadSheet[$s],$columns,'pipeline',$into);         
                if(!$error){
                    $check++;
                }            
            }
        }
        

        if($check == (sizeof($spreadSheet) - $mark) ){ $complete = true;}
        else{ $complete = false; }

        return $complete;
    }

    public function firstChain($con,$table,$spreadSheet,$base,$year){
        $columns = $this->defineColumns($table,'first');
        $parametter = $table;
        
       
        
        //var_dump($spreadSheet);
        if ($table == 'cmaps') {
            array_push($columns, 'sales_rep_representatives');
            for ($s=0; $s <sizeof($spreadSheet); $s++) { 
                $spreadSheet = $this->assembler($spreadSheet,$columns,$base,$parametter);
                $spreadSheet[$s]['log'] = $base->formatData('mm/dd/aaaa','aaaa-mm-dd',$spreadSheet[$s]['log']);    
            }  

            $spreadSheet = $this->addSalesRepRepresentatives($spreadSheet); 

        }else{
            for ($i=0; $i <sizeof($spreadSheet); $i++) { 
               // $spreadSheet[$i][11] = $base->monthToIntWBD(trim($spreadSheet[$i][11]));
                //$spreadSheet[$i][12] = $base->monthToIntWBD(trim($spreadSheet[$i][12]));
            }
            
        }        
                     
        //var_dump($spreadSheet);
        $into = $this->into($columns);      
        $check = 0;               
        $mark = 0;

        for ($s=0; $s < sizeof($spreadSheet); $s++) {             
            $error = $this->insert($con,$spreadSheet[$s],$columns,$table,$into);         
            if(!$error){
                $check++;
            }            
        }

        if($check == (sizeof($spreadSheet) - $mark) ){ $complete = true;}
        else{ $complete = false; }

        return $complete;
    }    

	public function secondChain($sql,$con,$fCon,$sCon,$table,$year = false){
        /* FAZER NOVA VERIFICAÇÃO COM CMAPS */

        $base = new base();
        $columns = $this->defineColumns($table,'first');

        if($table == "cmaps"){
            array_push($columns, 'sales_rep_representatives');
        }
        
        $columns = array_values($columns);
    	$columnsS = $this->defineColumns($table,'second');

        $tempBase = false;
        
        $current = $this->fixToInput($this->selectFromCurrentTable($sql,$fCon,$table,$columns),$columns);            
        //var_dump($current);
        $into = $this->into($columnsS);		
        
        $next = $this->handleForNextTable($con,$table,$current,$columns,$year);
        //var_dump($columnsS);
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

        $tempBase = false;
        
    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$sCon,$table,$columnsS),$columnsS);

    	$cleanedValues = $current;
    	    	
    	$next = $this->handleForLastTable($con,$table,$cleanedValues,$columnsS);
        
        $bool = $this->insertToLastTable($tCon,$table,$columnsT,$next,$into,$columnsS);
        
        return $bool;

    }

     public function thirdToDLA($sql,$con,$tCon,$table,$year,$truncate){
        $base = new base();                
                
        if($year == "2018"){
            for ($y=0; $y < sizeof($year); $y++) { 
                $delete[$y] = "DELETE FROM $table 
                                    WHERE(year = '".$year[$y]."')
                                    AND (brand_id != '8')                                            
                                    ";     
                if($con->query($delete[$y])){
                }                            
            }
        }else{
            for ($y=0; $y < sizeof($year); $y++) { 
                $delete[$y] = "DELETE FROM $table WHERE(year = '".$year[$y]."')";     
                if($con->query($delete[$y])){
                }
            }
        }
              
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
        $columnsT = $columns;

    	$into = $this->into($columnsT);

        $tempBase = false;
            
       
       $current = $this->fixToInput($this->selectFromCurrentTable($sql,$tCon,$table,$columns),$columns);
        var_dump($current);
/*    	$bool = $this->insertToDLA($con,$table,$columnsT,$current,$into);

        return $bool;*/

    }   

    public function addSalesRepRepresentatives($spreadSheet){

        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $spreadSheet[$s]['sales_rep_representatives'] = $spreadSheet[$s]['sales_rep'];
        }

        return $spreadSheet;
    }

    public function insert($con,$spreadSheet,$columns,$table,$into,$nextColumns = false){

        $values = $this->values($spreadSheet,$columns,$nextColumns);
        $ins = " INSERT INTO $table ($into) VALUES ($values)"; 
        if($con->query($ins) === TRUE ){
            $error = false;
            //var_dump($ins);
        }else{
            var_dump($spreadSheet);            
            echo "<pre>".($ins)."</pre>";
            var_dump($con->error);
            $error = true;
        }     

        return $error;     
      
    }

    public function localCurrencyToDolar($con,$current,$year){
        for ($c=0; $c < sizeof($current); $c++) { 
            $current[$c]['local_gross_revenue'] = $current[$c]['gross_revenue'];
            $current[$c]['local_commission'] = $current[$c]['commission'];
            $current[$c]['local_net_revenue'] = $current[$c]['net_revenue'];

            $current[$c]['gross_revenue'] = $this->multiplyByPRate($con,$current[$c]['region'],$year,$current[$c]['local_gross_revenue']);
            $current[$c]['commission'] = $this->multiplyByPRate($con,$current[$c]['region'],$year,$current[$c]['local_commission']);
            $current[$c]['net_revenue'] = $this->multiplyByPRate($con,$current[$c]['region'],$year,$current[$c]['local_net_revenue']);
            
        }

        return $current;
    }

    public function multiplyByPRate($con,$region,$year,$value){
        $r = new region();
        $pr = new pRate();
        $cYear = date('Y');
        //$cYear = $year;
        if($value > 0){
            $regionID = $r->getIDRegion($con,array($region))[0]['id'];
            $pRate = $pr->getPRateByRegionAndYear($con,array($regionID),array($cYear));
            $newValue = doubleval( $value/$pRate );
        }else{
            $newValue = $value;
        }

        return $newValue;
    }
    public function splitRevenues($current){
        for ($c=0; $c < sizeof($current); $c++) { 
            var_dump($current[$c]);
        }
    }
    public function createNetRevenueAndCommission($current){
        for ($c=0; $c < sizeof($current); $c++) { 
            $current[$c]['commission'] = $current[$c]['gross_revenue']*$current[$c]['agency_commission_percentage'];
            $current[$c]['net_revenue'] = $current[$c]['gross_revenue']-$current[$c]['commission'];
        }

        return $current;
    }

    public function fixShareAccounts($current){

        $count = 0;
        for ($c=0; $c < sizeof($current); $c++) { 
            //if($current[$c]['region'] == "Brazil" || $current[$c]['region'] == "Brasil"){                
                $temp = explode("/", $current[$c]['sales_rep']);

                if(sizeof($temp) > 1){
                    $newC = $current[$c];
                    $sales1 = trim($temp[0]);
                    $sales2 = trim($temp[1]);
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $newC['sales_rep'] = $temp[1];
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    array_push($current, $newC);

                    $count ++;
                }

                $temp2 = explode("&", $current[$c]['sales_rep']);

                if(sizeof($temp2) > 1){
                    $newC = $current[$c];
                    $sales1 = trim($temp2[0]);
                    $sales2 = trim($temp2[1]);
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $newC['sales_rep'] = $temp2[1];
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    array_push($current, $newC);

                    $count ++;
                }

                $temp3 = explode(" e ", $current[$c]['sales_rep']);

                if(sizeof($temp3) > 1){
                    $newC = $current[$c];
                    $sales1 = trim($temp3[0]);
                    $sales2 = trim($temp3[1]);
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $newC['sales_rep'] = $temp3[1];
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    array_push($current, $newC);

                    $count ++;
                }

                $temp4 = explode(",", $current[$c]['sales_rep']);

                if(sizeof($temp4) > 1){
                    $newC = $current[$c];
                    $sales1 = trim($temp4[0]);
                    $sales2 = trim($temp4[1]);
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $newC['sales_rep'] = $temp4[1];
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    array_push($current, $newC);

                    $count ++;
                }

                $temp5 = explode("|", $current[$c]['sales_rep']);

                if(sizeof($temp5) > 1){
                    $newC = $current[$c];
                    $sales1 = trim($temp5[0]);
                    $sales2 = trim($temp5[1]);
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $newC['sales_rep'] = $temp5[1];
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    array_push($current, $newC);

                    $count ++;
                } 

            //}
                
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

    public function selectFromCurrentTable($sql,$con,$table,$columns){
    	$res = $sql->select($con,"*",$table);
        $current = $sql->fetch($res,$columns,$columns);
    	return $current;
    }

    public function handleForLastTable($con,$table,$current,$columns){
    	for ($c=0; $c < sizeof($current); $c++) {             
            $rr = new region();

            $regionName = $rr->getRegion($con,array(1))[0]['name'];

            $current[$c]['agency_id'] = $this->seekAgencyID($con,"Brazil",$regionName,$current[$c]['agency']);
            $current[$c]['client_id'] = $this->seekClientID($con,"Brazil",$regionName,$current[$c]['client']);            
        }

		return $current;
    }

    public function seekAgencyID($con,$region,$regionName,$agency){
        
        $ag = new agency();
        $sql = new sql();

        $agencyID = $ag->getAgencyIDbyAgencyUnit($con,$sql,$agency,$region,$regionName);

        return($agencyID);

    }

    public function seekClientID($con,$region,$regionName,$client){

        $cli = new client();        
        $sql = new sql();
        
        $clientID = $cli->getClientIDbyClientUnit($con,$sql,$client,$region,$regionName);

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
        $salesRepRepresentatives = $sr->getSalesRepUnitWithRepresentatives($con);
    	$currencies = $pr->getCurrency($con);
        //var_dump($columns);
        for ($c=0; $c < sizeof($current); $c++) { 
    		for ($cc=0; $cc < sizeof($columns); $cc++) { 
                $tmp = $this->handle($con,$table,$current[$c][$columns[$cc]],$columns[$cc],$regions,$brands,$salesReps,$salesRepRepresentatives,$currencies,$year,$current[$c]);
    			//var_dump($tmp);
                if($columns[$cc] == "ad_unit" || $columns[$cc] == "from_date" || $columns[$cc] == "to_date"){
                    $current[$c][$tmp[1][1]] = $tmp[1][0];
                    $current[$c][$tmp[0][1]] = $tmp[0][0];                    
                }else{
                    $current[$c][$tmp[1]] = $tmp[0];
                }
    			if($columns[$cc] != $tmp[1] && $columns[$cc] != "ad_unit" && $columns[$cc] != "from_date" && $columns[$cc] != "to_date" ){
    				unset($current[$c][$columns[$cc]]);
    			}
    		}

            if( $table == 'cmaps'){
                $current[$c]['year'] = $year;
            }
    	}
        //var_dump($current);
		return $current;
    }

    public function handle($con,$table,$current,$column,$regions,$brands,$salesReps,$salesRepRepresentatives,$currencies,$year,$currentC){
        $base = new base();

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
        }elseif($column == 'stage'){
            $stg = $this->defineStage($current);
            $rtr =  array($stg,'stage');
        }elseif($column == 'sales_representant_office'){
			$rtr =  array(false,'sales_representant_office');
			for ($r=0; $r < sizeof($regions); $r++) { 
				if($current == $regions[$r]['name']){	
					$rtr =  array( $regions[$r]['id'],'sales_representant_office_id');
				}
			}
        }elseif($column == 'from_date' || $column == 'to_date'){
            $tmp = $base->dateToMonth($current);

            $rtr1 =  array( $tmp['month'] , $column );
            
            if($column == 'from_date'){
                $rtr2 = array( $tmp['year'] , 'year_from' );
            }else{
                $rtr2 = array( $tmp['year'] , 'year_to' );
            }                

            $rtr = array($rtr1, $rtr2);

            
        }elseif($column == 'region'){
            $rtr =  array(false,'region');

            if($current == 'CAM, Andina & Caribe Region'){
                $current = "LATAM";
            }

            if($current == 'Us Hispanic'){
                $current = "US Hispanic";
            }



            if( $current == "" ){
                $rtr =  array( 8,'region_id');
            }else{
                for ($r=0; $r < sizeof($regions); $r++) { 
                    if($current == $regions[$r]['name']){   
                        $rtr =  array( $regions[$r]['id'],'region_id');
                    }
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
        }elseif($column == 'currency'){
            $rtr =  array(false,'currency_id');
            for ($c=0; $c < sizeof($currencies); $c++) { 
                if($current == $currencies[$c]['name']){    
                    $rtr =  array( $currencies[$c]['id'],'currency_id');
                    break;
                }elseif($current == 'VES'){
                    $rtr =  array( 9,'currency_id');
                    break;
                }
            }
        }elseif($column == 'brand' && $table != "sf_pr"){
        	
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
			
        }elseif($column == 'ad_unit'){
            
            $rtr = array( array(false,'ad_unit') , array(false,'brand_id') );
            
            $temp = $current;            
            for ($b=0; $b < sizeof($brands); $b++) { 
                if( $temp  == $brands[$b]['brandUnit']){    
                    $rtr = array( array( $current,'ad_unit')   , array( $brands[$b]['brandID'],'brand_id') );
                }
            }
            

            $current = trim($current);

            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
				if($current == $salesReps[$sr]['salesRepUnit']){	
					$rtr =  array( $salesReps[$sr]['salesRepID'],'sales_rep_id');

                    $check++;
				}

                if($check > 0){

                    if($table == "fw_digital"){
                        $frt = "region_id";
                    }else{
                        $frt = "campaign_sales_office_id";                        
                    }

                    for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                        if($current == $salesReps[$srr]['salesRepUnit'] &&
                            $currentC[$frt] == $salesReps[$srr]['regionID']){
                            
                            $rtr =  array( $salesReps[$srr]['salesRepID'],'sales_rep_id');   
                        }                        
                    }
                }
			}
            
            if(!$rtr[0]){
                //var_dump($current);
            }

        }elseif($column == 'sales_rep' ){
            $rtr =  array(false,'sales_rep_id');
            $check = -1;

            $current = trim($current);

            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
                if($current == $salesReps[$sr]['salesRepUnit']){    
                    $rtr =  array( $salesReps[$sr]['salesRepID'],'sales_rep_id');

                    $check++;
                }

                if($check > 0){

                    if($table == "fw_digital"){
                        $frt = "region_id";
                    }else{
                        $frt = "campaign_sales_office_id";                        
                    }

                    for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                        if($current == $salesReps[$srr]['salesRepUnit'] &&
                            $currentC[$frt] == $salesReps[$srr]['regionID']){
                            
                            $rtr =  array( $salesReps[$srr]['salesRepID'],'sales_rep_id');   
                        }                        
                    }
                }
            }
            
            if(!$rtr[0]){
                //var_dump($current);
            }

        }elseif($column == 'primary_ae'){
            $rtr =  array(false,'primary_ae_id');
            $check = -1;

            $current = trim($current);

            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
                if($current == $salesReps[$sr]['salesRepUnit']){    
                    $rtr =  array( $salesReps[$sr]['salesRepID'],'primary_ae_id');

                    $check++;
                }

                if($check > 0){

                    if($table == "fw_digital"){
                        $frt = "region_id";
                    }else{
                        $frt = "campaign_sales_office_id";                        
                    }

                    for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                        if($current == $salesReps[$srr]['salesRepUnit']){
                            
                            $rtr =  array( $salesReps[$srr]['salesRepID'],'primary_ae_id');   
                        }                        
                    }
                }
            }
            
            if(!$rtr[0]){
                //var_dump($current);
            }
        }elseif($column == 'second_ae'){
            $rtr =  array(false,'second_ae_id');
            $check = -1;

            $current = trim($current);

            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
                if($current == $salesReps[$sr]['salesRepUnit']){    
                    $rtr =  array( $salesReps[$sr]['salesRepID'],'second_ae_id');

                    $check++;
                }

                if($check > 0){

                    if($table == "fw_digital"){
                        $frt = "region_id";
                    }else{
                        $frt = "campaign_sales_office_id";                        
                    }

                    for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                        if($current == $salesReps[$srr]['salesRepUnit']){
                            
                            $rtr =  array( $salesReps[$srr]['salesRepID'],'second_ae_id');   
                        }                        
                    }
                }
            }
            
            if(!$rtr[0]){
                //var_dump($current);
            }
        }elseif($column == 'sales_rep_representatives'){
            $rtr =  array(false,'sales_rep_representatives_id');
            $check = -1;

            $current = trim($current);

            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            for ($sr=0; $sr < sizeof($salesRepRepresentatives); $sr++) { 

                if($current == $salesRepRepresentatives[$sr]['salesRepUnit']){    
                    $rtr =  array( $salesRepRepresentatives[$sr]['salesRepRepresentativesID'],'sales_rep_representatives_id');

                    $check++;
                }
                
            }
            

        }elseif($column == 'sales_rep_owner' || $column == 'sales_rep_splitter'){

            if($column == 'sales_rep_owner'){
                $smtg = 'sales_rep_owner_id';
            }else{
                $smtg = 'sales_rep_splitter_id';
            }

            $rtr =  array(false,$smtg);
            $check = -1;
            $current = trim($current);
            
            /*
                O Check vai comparar o executivo, e ao encontrar um 'match' , colocará o ID no executivo encontrado na posição atual "current" e incrementará ++ ao seu valor , se o valor final do check for 0 significa que apenas 1 ocorrência do executivo foi encontrada, se for maior que isso irá ser feito o 'match' da região para inserção correta.
            */
            if($current == "Milena Timm"){
                
                if($column == 'sales_rep_owner'){
                    $pivot = 'sales_rep_splitter_id';
                }else{
                    $pivot = 'sales_rep_owner_id';
                }
                $rtr = array($currentC[$pivot],$smtg);
            }else{
                for ($sr=0; $sr < sizeof($salesReps); $sr++) { 
                    if($current == $salesReps[$sr]['salesRepUnit']){    
                        $rtr =  array( $salesReps[$sr]['salesRepID'],$smtg);
                        $check++;
                    }

                    if($check > 0){
                        for ($srr=0; $srr < sizeof($salesReps); $srr++) {
                            if($current == $salesReps[$srr]['salesRepUnit'] &&
                                $currentC['campaign_sales_office_id'] == $salesReps[$srr]['regionID']){
                                $rtr =  array( $salesReps[$srr]['salesRepID'],$smtg);   
                            }                        
                        }
                    }
                }
            }

        }else{
        	$rtr = array($current,$column);
        }
        return $rtr;
    } 

    public function defineStage($stg){
        $tmp = explode(" -", $stg);
        $newStg = $tmp[0];
        return $newStg;
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
            if($table == 'cmaps'){
                $bool[$c] = $this->insert($con,$current[$c],$columns,$table,$into,$nextColumns);
            }elseif($table == 'pipeline'){
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

        if($table == 'bts'){
            $table = 'ytd';
        }
        //var_dump($nextColumns);
    	for ($c=0; $c < sizeof($current); $c++) { 
    		if($nextColumns && ($table == 'cmaps')){
                $error[$c] = $this->insert($con,$current[$c],$columns,$table,$into,$nextColumns);
            }elseif($nextColumns && ($table == 'pipeline')){
                //var_dump('expression');
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
            $column == 'num_spot' ||
            $column == 'agency_commission_percentage' ||
            $column == 'fcst_amount_gross' ||
            $column == 'fcst_amount_net' ||
            $column == 'success_probability' ||
            $column == 'tv_value' ||
            $column == 'digital_value' ||
            $column == 'rep_commission_percentage' 

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
        
        $feed = array();
        $bd = new brand();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $allBrands = $bd->getBrandUnit($con);

        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            for ($c=0; $c < sizeof($columns); $c++) { 
                if($columns[$c] != ''){
                    $bool = $this->searchEmptyStrings($spreadSheet[$s],$columns);
                	if($bool){
                        if($columns[$c] == 'tv_value' ||
                            $columns[$c] == 'digital_value' ||
                            $columns[$c] == 'gross_revenue' ||
                           $columns[$c] == 'gross' ||
    					   $columns[$c] == 'net_revenue' ||						
                           $columns[$c] == 'net' ||                     
                           $columns[$c] == 'booked_spots' ||                     
    					   $columns[$c] == 'net_net_revenue' ||						
    					   $columns[$c] == 'gross_revenue_prate' ||
    					   $columns[$c] == 'net_revenue_prate' ||						
    					   $columns[$c] == 'net_net_revenue_prate' ||						
    					   $columns[$c] == 'revenue' ||
    					   $columns[$c] == 'campaign_option_spend' ||
    					   $columns[$c] == 'spot_duration' ||
    				       $columns[$c] == 'impression_duration'||
                           $columns[$c] == 'duration_impression' ||
                           $columns[$c] == 'fcst_amount_gross' ||
                           $columns[$c] == 'fcst_amount_net' ||
                           $columns[$c] == 'success_probability' ||
                           $columns[$c] == 'amount' ||
                           $columns[$c] == 'amount_converted' ||
                           $columns[$c] == 'num_spot' ||
                           $columns[$c] == 'gross_revenue_curr_prate' ||
                           $columns[$c] == 'real_dsc_tv'||
                           $columns[$c] == 'real_dsc_onl'||
                           $columns[$c] == 'real_spt_tv'||
                           $columns[$c] == 'real_spt_onl'||
                           $columns[$c] == 'read_dsc_tv'||
                           $columns[$c] == 'read_dsc_onl'||
                           $columns[$c] == 'read_spt_tv'||
                           $columns[$c] == 'read_spt_onl'||
                           $columns[$c] == 'real_wm_tv'||
                           $columns[$c] == 'real_wm_onl'||
                           $columns[$c] == 'read_wm_tv'||
                           $columns[$c] == 'read_wm_onl'/*||
                           
                           $columns[$c] == 'real_date'  ||
                           $columns[$c] == 'extract_date' || 
                           $columns[$c] == 'log'*/
    				      ){
                            if ($columns[$c] == 'gross_revenue_prate' || $columns[$c] == 'gross_revenue_curr_prate'){
                                explode("$", $columns[$c]);
                            }

    						if( is_null($spreadSheet[$s][$c])){
    							$columnValue = 0.0;
    						}else{
    							$columnValue = $c;
    						}
    						$spreadSheetV2[$s][$columns[$c]] = $this->fixExcelNumber( trim($spreadSheet[$s][$columnValue]) );
    					}else{
                            //var_dump($spreadSheet[$s][$columns[$c]]);

    						if($columns[$c] == 'campaign_option_start_date'  || $columns[$c] == 'date_event'){
                                $temp = $base->formatData("dd/mm/aaaa","aaaa-mm-dd",trim($spreadSheet[$s][$c]));    
                                
                                $spreadSheetV2[$s][$columns[$c]] = $temp;

    			             }elseif($columns[$c] == 'impression_duration' ){

                                    $temp = explode(" ", $spreadSheet[$s][$c]);
                                    $time = trim($temp[0]);
                                    $period = trim($temp[1]);

                                    if($period == "PM"){
                                        $hour = date("H:i:s",strtotime($time) + (3600*12) );
                                    }else{
                                        $hour = $time;
                                    }

                                    $spreadSheetV2[$s][$columns[$c]] = $hour; 

                            }elseif($columns[$c] == 'agency_commission'){
                                
                                if(trim($spreadSheet[$s][$c]) == ""){
                                    $temp = 0.0;
                                }else{
                                    $temp = trim($spreadSheet[$s][$c])/100;
                                }

                                $spreadSheetV2[$s][$columns[$c]] = $temp;


                            }elseif($columns[$c] == 'io_start_date' ||
                                    $columns[$c] == 'io_end_date' ||
                                    $columns[$c] == 'from_date' ||
                                $columns[$c] == 'to_date'
                              ){
                                
                                //$temp = $base->formatData("mm/dd/aaaa","aaaa-mm-dd",trim($spreadSheet[$s][$c]));
                                //$spreadSheetV2[$s][$columns[$c]] = $temp;
                                if(trim($spreadSheet[$s][$c]) != ""){
                                    $spreadSheetV2[$s][$columns[$c]] = trim($spreadSheet[$s][$c]);
                                }else{
                                    $someYear = date("Y");
                                    $spreadSheetV2[$s][$columns[$c]] = $someYear."-12-"."31";
                                }
                            }elseif($columns[$c] == 'rep_commission_percentage' ||
                                    $columns[$c] == 'agency_commission_percentage'
                                    ){
                                if($spreadSheet[$s][$c] == ''){
                                    $spreadSheetV2[$s][$columns[$c]] = 0.0;
                                }else{
                                    $spreadSheetV2[$s][$columns[$c]] = $base->removePercentageSymbol(trim($spreadSheet[$s][$c]), $table);
                                }                            
                            }elseif($columns[$c] == "brand" && $table == "sf_pr"){
                                
                                if(!is_null($spreadSheet[$s][$c])){
                                    $temporario = explode(";", $spreadSheet[$s][$c]);
                                    $cc = 0;
                                    for ($t=0; $t < sizeof($temporario); $t++) {                                     
                                        for ($u=0; $u < sizeof($allBrands); $u++) { 
                                            if( trim($temporario[$t]) === $allBrands[$u]['brandUnit'] ){
                                                $temporario2[$cc] = $allBrands[$u]['brand'];
                                                $cc++;
                                                break;
                                            }
                                        }
                                    }
                                    $ff = sizeof($temporario2);
                                    for ($i=$ff; $i >= $cc; $i--) { 
                                        unset($temporario2[$i]);
                                    }
                                    $temporario2 = array_values( array_unique( $temporario2 ) );
                                    $string = "";
                                    for ($tt=0; $tt < sizeof($temporario2); $tt++) { 
                                        $string .= $temporario2[$tt];
                                        if($tt < (sizeof($temporario2) - 1) ){
                                            $string .= ";";
                                        }
                                    }
                                }else{
                                    $string = "NOCHANNELS";
                                }
                                $spreadSheetV2[$s][$columns[$c]] = $string;
                            }elseif($columns[$c] == 'obs'){
                                $spreadSheetV2[$s][$columns[$c]] = "OBS";
                            }elseif($columns[$c] == 'year'){
                                if($table == "insights"){
                                    $x = date('Y');
                                    $spreadSheetV2[$s][$columns[$c]] = $x;
                                }else{
                                    $spreadSheetV2[$s][$columns[$c]] = intval($spreadSheet[$s][$c]);
                                }
                            }elseif($columns[$c] == 'start_month' || $columns[$c] == 'end_month'){
                               $spreadSheetV2[$s][$columns[$c]] = $base->monthToIntAleph(trim($spreadSheet[$s][$c]));
                            }
                            elseif($columns[$c] == 'month'){     
                            //var_dump('aki');                           
                                   $spreadSheetV2[$s][$columns[$c]] = $base->monthToIntCMAPS(trim($spreadSheet[$s][$c]));                                
    						}else{
    							$spreadSheetV2[$s][$columns[$c]] = trim($spreadSheet[$s][$c]);
    						}
    					}
    				}
                }else{
                    var_dump($columns);
                }

			}
		} 
        
		//$spreadSheetV2 = array_values($spreadSheetV2);
		//return $spreadSheetV2;
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

        $excel = new excel();
        for ($c=0; $c < sizeof($columns); $c++) { 
            if($nextColumns){   
                if($nextColumns[$c] == "gross" || $nextColumns[$c] == "net" || $nextColumns[$c] == "discount" || $nextColumns[$c] == "tv_value" || $nextColumns[$c] == "digital_value"){
                    $values .= "\"".round($spreadSheet[$nextColumns[$c]],5)."\"";
                }else if($nextColumns[$c] == "from_value" || $nextColumns[$c] == "to_value"){
                    $values .= "\"".round($excel->fixExcelNumber($spreadSheet[$nextColumns[$c]]),5)."\"";
                }else{
                    $values .= "\"".  addslashes($spreadSheet[$nextColumns[$c]])."\"";
                }
            }else{
                if($columns[$c] == "gross" || $columns[$c] == "net" || $columns[$c] == "discount" || $nextColumns[$c] == "tv_value" || $nextColumns[$c] == "digital_value"){
                    $values .= "\"".round($spreadSheet[$columns[$c]],5)."\"";
                }else if($columns[$c] == "from_value" || $columns[$c] == "to_value"){
                    $values .= "\"".round( $excel->fixExcelNumber( $spreadSheet[$columns[$c]] ) ,5)."\"";
                }else{
                    //var_dump($columns);
                    $values .= "\"".  addslashes($spreadSheet[$c])."\"";
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
            case 'daily_results':
                  case 'DLA':
                      return $this->dailyColumns;
                      break;
                  break;  
            case 'pipeline':
                  switch ($recurrency) {
                    case 'first':
                        return $this->pipelineColumnsF;
                        break;
                    case 'second':
                        return $this->pipelineColumnsS;
                        break;
                    case 'third':
                        return $this->pipelineColumnsT;
                        break;
                    case 'DLA':
                        return $this->pipelineColumns;
                        break;
                }
                  break;  
            break;   		
    	}

    }

    public $pipelineColumnsF = array('register',
                                    'cluster',
                                    'property',
                                    'client',
                                    'agency',
                                    'product',
                                    'primary_ae',
                                    'second_ae',
                                    'manager',
                                    'tv_value',
                                    'digital_value',
                                    'start_month',
                                    'end_month',
                                    'quota',
                                    'status',
                                    'notes');

    public $pipelineColumnsS = array('register',
                                    'cluster',
                                    'property',
                                    'client',
                                    'agency',
                                    'product',
                                    'primary_ae_id',
                                    'second_ae_id',
                                    'manager',
                                    'tv_value',
                                    'digital_value',
                                    'start_month',
                                    'end_month',
                                    'quota',
                                    'status',
                                    'notes');

    public $pipelineColumnsT = array('register',
                                    'cluster',
                                    'property',
                                    'client',
                                    'agency',
                                    'product',
                                    'primary_ae_id',
                                    'second_ae_id',
                                    'manager',
                                    'tv_value',
                                    'digital_value',
                                    'start_month',
                                    'end_month',
                                    'quota',
                                    'status',
                                    'notes');

    public $pipelineColumns = array('register',
                                    'cluster',
                                    'property',
                                    'client',
                                    'agency',
                                    'product',
                                    'primary_ae_id',
                                    'second_ae_id',
                                    'manager',
                                    'tv_value',
                                    'digital_value',
                                    'start_month',
                                    'end_month',
                                    'quota',
                                    'status',
                                    'notes');

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
                                  'sales_rep_representatives_id',
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
                                  'sales_rep_representatives_id',
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
                                  'category',
                                  'sales_rep_representatives_id'
                              );

    public $dailyColumns = array('real_date',
                                 'extract_date',
                                 'log',
                                 'month',
                                 'real_dsc_tv',
                                 'real_dsc_onl',
                                 'real_spt_tv',
                                 'real_spt_onl',                                 
                                 'real_wm_tv',
                                 'real_wm_onl',
                                 'read_dsc_tv',
                                 'read_dsc_onl',
                                 'read_spt_tv',
                                 'read_spt_onl',
                                 'read_wm_tv',
                                 'read_wm_onl'
                              );

    

     
	public $salesRepColumns = array('sales_group_id','name');
	public $salesRepUnitColumns = array('sales_rep_id','origin_id','name');
	
    public $planByBrandColumns = array('sales_office_id','currency_id','brand_id','source','year','month','type_of_revenue','revenue');

}
