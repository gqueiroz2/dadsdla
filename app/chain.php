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

class chain extends excel{
   
    public function handler($con,$table,$spreadSheet,$year){
        $base = new base();
        $bool = $this->firstChain($con,$table,$spreadSheet,$base,$year);			
        return $bool;
	}

    public function firstChain($con,$table,$spreadSheet,$base,$year){
        $columns = $this->defineColumns($table,'first');
        $parametter = $table;
        var_dump($parametter);
        
        $spreadSheet = $this->assembler($spreadSheet,$columns,$base,$parametter);
        if($table == 'cmaps'){
            array_push($columns, 'sales_rep_representatives');
            $spreadSheet = $this->addSalesRepRepresentatives($spreadSheet);
        }
        
        if($table == 'sf_pr_brand'){
            array_push($columns, 'net_revenue');
            $spreadSheet = $this->addNetRevenuePandR($spreadSheet);
        }

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
        if($table == 'sf_pr_brand'){
            array_push($columns, 'net_revenue');            
        }

        $columns = array_values($columns);
    	$columnsS = $this->defineColumns($table,'second');

        if($table == 'bts'){
            $tempBase = 'bts';
            $table = 'ytd';
        }else{
            $tempBase = false;
        }

        $current = $this->fixToInput($this->selectFromCurrentTable($sql,$fCon,$table,$columns),$columns);        

        if($tempBase){
            $table = 'bts';
        }

        if($table == "data_hub"){
            $current = $this->fixShareAccountsDH($con,$current);
        }

        if($table == "bts"){
            $current = $this->fixShareAccountsBTS($con,$current);            
        }       

        $into = $this->into($columnsS);		

        if($table == 'data_hub'){
            $columns = $this->ytdColumnsF;
        }

        if($table == "sf_pr_brand"){
            $current = $this->addSFInfo($fCon,$sql,$current,$columns);
            array_push($columns, "sales_rep_splitter");
        }

        $next = $this->handleForNextTable($con,$table,$current,$columns,$year);

        if($table == "sf_pr_brand"){
            $next = $this->addSFValues($fCon,$sql,$next,$columns);
        }   

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

        if($table == 'bts'){
            $tempBase = 'bts';
            $table = 'ytd';
        }else{
            $tempBase = false;
        }

    	$current = $this->fixToInput($this->selectFromCurrentTable($sql,$sCon,$table,$columnsS),$columnsS);

        if($tempBase){
            $table = 'bts';
        }

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

        if($table == "ytdFN"){
            if($year == "2019"){
                for ($y=0; $y < sizeof($year); $y++) { 
                    $delete[$y] = "DELETE FROM $table WHERE(year = '".$year[$y]."') AND (brand_id = '8') AND (month < '6')";     
                    if($con->query($delete[$y])){}
                }
            }else{
                for ($y=0; $y < sizeof($year); $y++) { 
                    $delete[$y] = "DELETE FROM $table WHERE(year = '".$year[$y]."') AND (brand_id = '8')";     
                    if($con->query($delete[$y])){}
                }
            }
            $table = "ytd";
        }else if($table == "bts"){
            if($year == "2019"){
                for ($y=0; $y < sizeof($year); $y++) { 
                    $delete[$y] = "DELETE FROM ytd 
                                        WHERE(year = '".$year[$y]."')
                                        AND (month > '9')
                                  ";     
                    if($con->query($delete[$y])){
                    }
                }
            }else{
                for ($y=0; $y < sizeof($year); $y++) { 
                    $delete[$y] = "DELETE FROM ytd 
                                        WHERE(year = '".$year[$y]."')
                                        AND (brand_id = '8')       
                                        AND (month > '9')                                 
                                  ";     
                    if($con->query($delete[$y])){
                    }
                }
            }
            $table = "ytd";
        }elseif($table == "data_hub"){            
            for ($y=0; $y < sizeof($year); $y++) { 
                $delete[$y] = "DELETE FROM ytd WHERE(year = '".$year[$y]."')";     
                if($con->query($delete[$y])){}
            }
        }else{
            if($table == "ytd"){
                if($truncate){
                    $truncateStatement = "TRUNCATE TABLE $table";
                    if($con->query($truncateStatement) === TRUE){
                        $truncated = true;
                    }else{
                        $truncated = false;
                    }
                }else{
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
                }
            }else{
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
            }
        }   
        

        if($table == 'sf_pr_brand'){
	        $columns = $this->defineColumns($table,'third');
            $columnsT = $this->defineColumns($table,'DLA');
        }else{
            $columns = $this->defineColumns($table,'third');
            $columnsT = $columns;

        }


    	$into = $this->into($columnsT);


        if($table == 'bts' || $table == 'data_hub'){
            if($table == 'bts'){    
                $tempBase = 'bts';
            }else{
                $tempBase = 'data_hub';
            }
            $table = 'ytd';
        }elseif ($table == 'sf_pr_brand') {
            $tempBase = "sf_pr_brand";
            $table = "sf_pr";
        }else{
            $tempBase = false;
        }

        if($tempBase && ($tempBase == "data_hub" || $tempBase == 'sf_pr_brand') ){
            $current = $this->fixToInput($this->selectFromCurrentTable($sql,$tCon,$tempBase,$columns),$columns);
        }else{
            $current = $this->fixToInput($this->selectFromCurrentTable($sql,$tCon,$table,$columns),$columns);
        }

    	$bool = $this->insertToDLA($con,$table,$columnsT,$current,$into);

        return $bool;

    }   

    public function addSFInfo($fCon,$sql,$next,$columns){
        for ($n=0; $n < sizeof($next); $n++) { 
            $oppid[$n] = $next[$n]['oppid'];
            $tmp = $this->getInfoFromSF($fCon,$sql,$oppid[$n]);
            $next[$n]['client'] = $tmp['client'];
            $next[$n]['agency'] = $tmp['agency'];
            $next[$n]['opportunity_name'] = $tmp['opportunity_name'];
            $next[$n]['sales_rep_owner'] = $tmp['sales_rep_owner'];
            $next[$n]['sales_rep_splitter'] = $tmp['sales_rep_owner'];
        }

        return $next;
    }

    public function addSFValues($fCon,$sql,$next,$columns){

        for ($n=0; $n < sizeof($next); $n++) { 
            $oppid[$n] = $next[$n]['oppid'];
            $amount[$n] = $next[$n]['gross_revenue'];
            $fullAmount = $this->fullValueOfOPP($fCon,$sql,$oppid[$n]);
            if($fullAmount > 0){                
                $share = $amount[$n]/$fullAmount;
            }else{
                $share = 0.0;
            }
            $forecastValues = $this->forecastValuesFromOPP($fCon,$sql,$oppid[$n]);

            $fcstAmountGross = $forecastValues['fcst_amount_gross'];
            $fcstAmountNet = $forecastValues['fcst_amount_net'];
            
            $next[$n]['fcst_amount_gross'] = $fcstAmountGross*$share;
            $next[$n]['fcst_amount_net'] = $fcstAmountNet*$share;
        }

        return $next;

    }

    public function forecastValuesFromOPP($con,$sql,$oppid){
        $select = "SELECT fcst_amount_gross,fcst_amount_net FROM sf_pr WHERE(oppid = '".$oppid."')";
        $res = $con->query($select);
        $from = array("fcst_amount_gross","fcst_amount_net");
        $tmp = $sql->fetch($res,$from,$from)[0];

        return $tmp;

    }

    public function fullValueOfOPP($con,$sql,$oppid){

        $selectSUM = "SELECT SUM(gross_revenue) AS amountSUM FROM sf_pr_brand WHERE(oppid = '".$oppid."')";
        $res = $con->query($selectSUM);
        $from = array("amountSUM");
        $tmp = $sql->fetch($res,$from,$from)[0]['amountSUM'];

        return $tmp;
    }

    public function getInfoFromSF($con,$sql,$oppid){
        $select = "SELECT sales_rep_owner,sales_rep_splitter,client,agency,opportunity_name FROM sf_pr WHERE(oppid = '".$oppid."')";
        $res = $con->query($select);
        $from = array("sales_rep_owner","sales_rep_splitter","client","agency","opportunity_name");
        $tmp = $sql->fetch($res,$from,$from)[0];
        return $tmp;
    }

    public function addNetRevenuePandR($spreadSheet){

        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $spreadSheet[$s]['net_revenue'] = $spreadSheet[$s]['gross_revenue']*(1-$spreadSheet[$s]['agency_commission']);
        }

        return $spreadSheet;

    }

    public function addSalesRepRepresentatives($spreadSheet){

        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $spreadSheet[$s]['sales_rep_representatives'] = $spreadSheet[$s]['sales_rep'];
        }

        return $spreadSheet;
    }

    public function insert($con,$spreadSheet,$columns,$table,$into,$nextColumns = false){

        if($table == 'bts'){ $table = 'ytd'; }
        $values = $this->values($spreadSheet,$columns);
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

    public function fixShareAccountsDH($con,$current){
        $r = new region();
        $pRate = new pRate();

        for ($c=0; $c < sizeof($current); $c++) { 
            $current[$c]['campaign_sales_office'] = $this->fixBTSRegion($current[$c]['holding_company']);

            $current[$c]['sales_representant_office'] = $this->fixBTSRegion($current[$c]['invoice_holding_company']);
            
            $currentMonth = floatval(date('m'));

            if( $current[$c]['month'] < $currentMonth){
                $current[$c]['gross_revenue_prate'] = $current[$c]['gross_revenue_curr_prate'];
            }
            $current[$c]['brand'] = $current[$c]['master_channel'];
            $current[$c]['brand_feed'] = $current[$c]['channel'];

            $current[$c]['order_reference'] = $current[$c]['campaign_desc'];
            $current[$c]['campaign_reference'] = $current[$c]['campaign'];
            $current[$c]['campaign_currency'] = $current[$c]['currency'];
            $current[$c]['client_product'] = $current[$c]['product'];

            $current[$c]['spot_duration'] = 0;
            $current[$c]['num_spot'] = $current[$c]['booked_spots'];
            $current[$c]['impression_duration'] = 0;
            $current[$c]['net_net_revenue'] = 0.0;

            $valPRate = $pRate->getPRateByRegionAndYear($con,
                                                        array(
                $r->getIDRegion($con,
                                array($current[$c]['campaign_sales_office'])
                               )[0]['id']) ,
                                                        array($current[$c]['year']));
            
            $current[$c]['net_revenue'] = $current[$c]['gross_revenue']*(1-$current[$c]['agency_commission_percentage']);
            $current[$c]['net_revenue_prate'] = $current[$c]['gross_revenue_prate']*(1-$current[$c]['agency_commission_percentage']);
            $current[$c]['net_net_revenue_prate'] = 0.0;
        }

        return $current;

    }

    public function fixBTSRegion($region){

        if($region == "US HISPANIC" || $region == 'US HISPANIC INTL'){
            $region = "US Hispanic";
        }elseif($region == "DOMINICAN REPUBLIC" || $region == "Dominican Republic"){
            $region = "Dominican Republic";
        }else if($region == 'EUROPE INTL.'){
            $region = "Europe";
        }else if($region == 'New York International'){
            $region = "New York International";
        }else if($region == 'MIAMI INTL.'){
            $region = "Miami";
        }else{
            $region = ucfirst(strtolower($region));
        }

        if($region == "Spain" || $region == "SPAIN"){
            $region = "Europe";
        }
        

        return $region;
    }

    public function fixShareAccountsBTS($con,$current){
        $sr = new salesRep(); 
        $count = 0;
        for ($c=0; $c < sizeof($current); $c++) { 
            $temp = explode(",", $current[$c]['sales_rep']);
            if(sizeof($temp) > 1){                
                $newC = $current[$c];
                $sales1 = trim($temp[0]);
                $sales2 = trim($temp[1]);
                $newC['sales_rep'] = $temp[1];
                if(sizeof($temp) < 3){
                    $current[$c]['sales_rep'] = $sales1;
                    $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/2;
                    $current[$c]['net_revenue'] = $current[$c]['net_revenue']/2;
                    $current[$c]['net_net_revenue'] = $current[$c]['net_net_revenue']/2;

                    $current[$c]['gross_revenue_prate'] = $current[$c]['gross_revenue_prate']/2;
                    $current[$c]['net_revenue_prate'] = $current[$c]['net_revenue_prate']/2;
                    $current[$c]['net_net_revenue_prate'] = $current[$c]['net_net_revenue_prate']/2;
                    
                    $newC['gross_revenue'] = $newC['gross_revenue']/2;
                    $newC['net_revenue'] = $newC['net_revenue']/2;
                    $newC['net_net_revenue'] = $newC['net_net_revenue']/2;

                    $newC['gross_revenue_prate'] = $newC['gross_revenue_prate']/2;
                    $newC['net_revenue_prate'] = $newC['net_revenue_prate']/2;
                    $newC['net_net_revenue_prate'] = $newC['net_net_revenue_prate']/2;
                }else{
                    $salesArray = array();
                    for ($t=0; $t < sizeof($temp); $t++){ 
                      $somp = $sr->getNewSalesRep($con, trim($temp[$t]))[0];
                      //if($somp['region'] == $current[$c]['sales_representant_office']){
                        array_push($salesArray, trim($temp[$t]) ) ;
                      //}

                    }

                    if(sizeof($salesArray) > 1){

                        if(sizeof($salesArray) == 2){
                            $divi = 2;
                        }else if(sizeof($salesArray) == 3){
                            $divi = 3;
                        }else{
                            $divi = 4;
                        }

                        $current[$c]['sales_rep'] = $salesArray[0];
                        $current[$c]['gross_revenue'] = $current[$c]['gross_revenue']/$divi;
                        $current[$c]['net_revenue'] = $current[$c]['net_revenue']/$divi;
                        $current[$c]['net_net_revenue'] = $current[$c]['net_net_revenue']/$divi;

                        $current[$c]['gross_revenue_prate'] = $current[$c]['gross_revenue_prate']/$divi;
                        $current[$c]['net_revenue_prate'] = $current[$c]['net_revenue_prate']/$divi;
                        $current[$c]['net_net_revenue_prate'] = $current[$c]['net_net_revenue_prate']/$divi;
                        
                        $newC['sales_rep'] = $salesArray[1];

                        $newC['gross_revenue'] = $newC['gross_revenue']/$divi;
                        $newC['net_revenue'] = $newC['net_revenue']/$divi;
                        $newC['net_net_revenue'] = $newC['net_net_revenue']/$divi;

                        $newC['gross_revenue_prate'] = $newC['gross_revenue_prate']/$divi;
                        $newC['net_revenue_prate'] = $newC['net_revenue_prate']/$divi;
                        $newC['net_net_revenue_prate'] = $newC['net_net_revenue_prate']/$divi;
                    }else{
                        $current[$c]['sales_rep'] = $salesArray[0];
                    }
                }


                array_push($current, $newC);

                $count ++;
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

    public function selectFromCurrentTable($sql,$con,$table,$columns){
    	$res = $sql->select($con,"*",$table);
        $current = $sql->fetch($res,$columns,$columns);
    	return $current;
    }

    public function handleForLastTable($con,$table,$current,$columns){
    	for ($c=0; $c < sizeof($current); $c++) {             
                $rr = new region();
                
            if($table == "cmaps"){

                $regionName = $rr->getRegion($con,array(1))[0]['name'];

                $current[$c]['agency_id'] = $this->seekAgencyID($con,"Brazil",$regionName,$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,"Brazil",$regionName,$current[$c]['client']);

            }elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){

                $regionName = $rr->getRegion($con,array($current[$c]['region_id']))[0]['name'];

                $current[$c]['agency_id'] = $this->seekAgencyID($con,$current[$c]['region_id'],$regionName,$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,$current[$c]['region_id'],$regionName,$current[$c]['client']);      

            }elseif($table == "insights"){

                $regionName = "Brazil";

                $current[$c]['agency_id'] = $this->seekAgencyID($con,1,$regionName,$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,1,$regionName,$current[$c]['client']);
            }else{                

                $regionName = $rr->getRegion($con,array($current[$c]['sales_representant_office_id']))[0]['name'];

                $current[$c]['agency_id'] = $this->seekAgencyID($con,$current[$c]['sales_representant_office_id'],$regionName,$current[$c]['agency']);
                $current[$c]['client_id'] = $this->seekClientID($con,$current[$c]['sales_representant_office_id'],$regionName,$current[$c]['client']);                
            }
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

        for ($c=0; $c < sizeof($current); $c++) { 
    		for ($cc=0; $cc < sizeof($columns); $cc++) { 
                $tmp = $this->handle($con,$table,$current[$c][$columns[$cc]],$columns[$cc],$regions,$brands,$salesReps,$salesRepRepresentatives,$currencies,$year,$current[$c]);
    			
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

            if( $table == 'cmaps' || $table == 'fw_digital' || $table == 'sf_pr' ){
                $current[$c]['year'] = $year;
            }
    	}
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

        }elseif($column == 'sales_rep'){
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
            $column == 'num_spot' ||
            $column == 'agency_commission_percentage' ||
            $column == 'fcst_amount_gross' ||
            $column == 'fcst_amount_net' ||
            $column == 'success_probability' ||

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
                        if($columns[$c] == 'gross_revenue' ||
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
                           $columns[$c] == 'gross_revenue_curr_prate'
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
    						if($columns[$c] == 'campaign_option_start_date'  || $columns[$c] == 'date_event'                                                                               
                              ){
                                $temp = $base->formatData("dd/mm/aaaa","aaaa-mm-dd",trim($spreadSheet[$s][$c]));
                                $spreadSheetV2[$s][$columns[$c]] = $temp;

    			
                            }elseif($columns[$c] == 'impression_duration' ){

                                    $temp = explode(" ", $spreadSheet[$s][$c]);
                                    var_dump($temp);
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
                            }elseif($columns[$c] == 'month'){
                                if( $table && ($table == "cmaps") ){
                                   $spreadSheetV2[$s][$columns[$c]] = $base->monthToIntCMAPS(trim($spreadSheet[$s][$c]));
                                }else if( $table && ($table == "insights") ){
                                    $temp = $base->monthToIntInsights(trim($spreadSheet[$s][$c]));
                                    $spreadSheetV2[$s][$columns[$c]] = $temp[1];
                                    $spreadSheetV2[$s]['year'] = $temp[0];
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

        $excel = new excel();
        for ($c=0; $c < sizeof($columns); $c++) { 
            if($nextColumns){   
                if($nextColumns[$c] == "gross" || $nextColumns[$c] == "net" || $nextColumns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$nextColumns[$c]],5)."\"";
                }else if($nextColumns[$c] == "from_value" || $nextColumns[$c] == "to_value"){
                    $values .= "\"".round($excel->fixExcelNumber($spreadSheet[$nextColumns[$c]]),5)."\"";
                }else{
                    $values .= "\"".  addslashes($spreadSheet[$nextColumns[$c]])."\"";
                }
            }else{
                if($columns[$c] == "gross" || $columns[$c] == "net" || $columns[$c] == "discount"){
                    $values .= "\"".round($spreadSheet[$columns[$c]],5)."\"";
                }else if($columns[$c] == "from_value" || $columns[$c] == "to_value"){
                    $values .= "\"".round( $excel->fixExcelNumber( $spreadSheet[$columns[$c]] ) ,5)."\"";
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
            case 'data_hub':                
                switch ($recurrency) {
                    case 'first':
                        return $this->dataHubColumnsF;
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

            case 'bts':
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

            case 'ytdFN':
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

            case 'fw_digital':
                switch ($recurrency) {
                    case 'first':
                        return $this->fwDigitalColumnsF;
                        break;
                    case 'second':
                        return $this->fwDigitalColumnsS;
                        break;
                    case 'third':
                        return $this->fwDigitalColumnsT;
                        break;
                    case 'DLA':
                        return $this->fwDigitalColumns;
                        break;
                }
                break;

            case 'sf_pr':
                switch ($recurrency) {
                    case 'first':
                        return $this->sfPandRColumnsF;
                        break;
                    case 'second':
                        return $this->sfPandRColumnsS;
                        break;
                    case 'third':
                        return $this->sfPandRColumnsT;
                        break;
                    case 'DLA':
                        return $this->sfPandRColumns;
                        break;
                }
                break;

            case 'sf_pr_brand':
                switch ($recurrency) {
                    case 'first':
                        return $this->sfPandRBrandColumnsF;
                        break;                    
                    case 'second':
                        return $this->sfPandRBrandColumnsS;
                        break;
                    case 'third':
                        return $this->sfPandRBrandColumnsT;
                        break;
                    case 'DLA':
                        return $this->sfPandRBrandColumns;
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
            case 'insights':
                switch ($recurrency) {
                    case 'first':
                        return $this->insightsColumnsF;
                        break;
                    case 'second':
                        return $this->insightsColumnsS;
                        break;
                    case 'third':
                        return $this->insightsColumnsT;
                        break;
                    case 'DLA':
                        return $this->insightsColumns;
                        break;
                }
            case 'insights_bts':
                switch ($recurrency) {
                    case 'first':
                        return $this->insightsColumnsF;
                        break;
                    case 'second':
                        return $this->insightsColumnsS;
                        break;
                    case 'third':
                        return $this->insightsColumnsT;
                        break;
                    case 'DLA':
                        return $this->insightsColumns;
                        break;
                }
                    
                break;
    		
    		
    	}

    }

    public $sfPandRBrandColumnsF = array(
                                  'oppid',
                                  'region',          
                                  'sales_rep_owner',
                                  'client',
                                  'agency',
                                  'opportunity_name',
                                  'agency_commission',
                                  'stage',
                                  'forecast_category',
                                  'brand',
                                  'currency',
                                  'amount_currency',
                                  'gross_revenue_loc',
                                  'amount_converted_currency',
                                  'gross_revenue',
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'opportunity_record_type',
                                  'is_split',
                              );

    public $sfPandRBrandColumnsS = array(
                                  'oppid',
                                  'region_id',          
                                  'sales_rep_owner_id',
                                  'sales_rep_splitter_id',
                                  'client',
                                  'agency',
                                  'opportunity_name',
                                  'agency_commission',
                                  'stage',
                                  'forecast_category',
                                  'brand_id',
                                  'currency_id',                                  
                                  'gross_revenue_loc',                                  
                                  'gross_revenue',
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'opportunity_record_type',
                                  'is_split',
                                  'year_from',
                                  'year_to',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',
                                  'net_revenue'
                              );

    public $sfPandRBrandColumnsT = array(
                                  'oppid',
                                  'region_id',          
                                  'sales_rep_owner_id',
                                  'sales_rep_splitter_id',
                                  'client_id',
                                  'agency_id',
                                  'opportunity_name',
                                  'agency_commission',
                                  'stage',
                                  'forecast_category',
                                  'brand_id',
                                  'currency_id',                                  
                                  'gross_revenue_loc',                                  
                                  'gross_revenue',
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'opportunity_record_type',
                                  'is_split',
                                  'year_from',
                                  'year_to',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',
                                  'net_revenue'
                              );

    public $sfPandRBrandColumns = array(
                                  'oppid',
                                  'region_id',
                                  'sales_rep_owner_id',
                                  'sales_rep_splitter_id',
                                  'client_id',
                                  'brand_id',
                                  'currency_id', 
                                  'opportunity_name',
                                  'agency_id',
                                  'agency_commission',
                                  'stage',
                                  'forecast_category',
                                  'gross_revenue',
                                  'net_revenue',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',                                
                                  'success_probability',
                                  'from_date',
                                  'to_date',                                  
                                  'is_split',
                                  'year_from',
                                  'year_to',
                              );  

    public $sfPandRColumnsF = array(
                                  'oppid',
                                  'region',                                  
                                  'sales_rep_owner',
                                  'sales_rep_splitter',
                                  'client',
                                  'brand',
                                  'opportunity_name',                                  
                                  'agency',
                                  'agency_commission',
                                  'stage',
                                  'fcst_category',
                                  'gross_revenue_currency',
                                  'gross_revenue',
                                  'net_revenue_currency',
                                  'net_revenue',
                                  'fcst_amount_gross_currency',
                                  'fcst_amount_gross',
                                  'fcst_amount_net_currency',
                                  'fcst_amount_net',                                  
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'is_split'
                              );

    public $sfPandRColumnsS = array(
                                  'oppid',
                                  'region_id',
                                  'sales_rep_owner_id',
                                  'client',
                                  'brand',
                                  'opportunity_name',
                                  'agency',
                                  'agency_commission',
                                  'stage',
                                  'fcst_category',
                                  'gross_revenue',
                                  'net_revenue',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',                                  
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'sales_rep_splitter_id',
                                  'is_split',
                                  'year_from',
                                  'year_to',

                              );

    public $sfPandRColumnsT = array(
                                  'oppid',
                                  'region_id',
                                  'sales_rep_owner_id',
                                  'client_id',
                                  'brand',
                                  'opportunity_name',
                                  'agency_id',
                                  'agency_commission',
                                  'stage',
                                  'fcst_category',
                                  'gross_revenue',
                                  'net_revenue',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',                                  
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'sales_rep_splitter_id',
                                  'is_split',
                                  'year_from',
                                  'year_to',
                              );

    public $sfPandRColumns = array(
                                  'oppid',
                                  'region_id',
                                  'sales_rep_owner_id',
                                  'client_id',
                                  'brand',
                                  'opportunity_name',
                                  'agency_id',
                                  'agency_commission',
                                  'stage',
                                  'fcst_category',
                                  'gross_revenue',
                                  'net_revenue',
                                  'fcst_amount_gross',
                                  'fcst_amount_net',                                  
                                  'success_probability',
                                  'from_date',
                                  'to_date',
                                  'sales_rep_splitter_id',
                                  'is_split',
                                  'year_from',
                                  'year_to',
                              );      


    public $fwDigitalColumnsF = array(
                                  'client',
                                  'agency',
                                  'campaign',
                                  'insertion_order',
                                  'insertion_order_id',
                                  'region',
                                  'sales_rep',
                                  'io_start_date',
                                  'io_end_date',
                                  'agency_commission_percentage',
                                  'rep_commission_percentage',
                                  'placement',
                                  'buy_type',
                                  'content_targeting_set_name',
                                  'currency',
                                  'ad_unit',
                                  'gross_revenue',
                                  'month'
                                  
                              );

    public $fwDigitalColumnsS = array(
                                  'client',
                                  'agency',
                                  'campaign',
                                  'insertion_order',
                                  'insertion_order_id',
                                  'region_id',
                                  'sales_rep_id',
                                  'io_start_date',
                                  'io_end_date',
                                  'agency_commission_percentage',
                                  'rep_commission_percentage',
                                  'currency_id',
                                  'placement',
                                  'buy_type',
                                  'content_targeting_set_name',
                                  'ad_unit',
                                  'month',
                                  'gross_revenue',
                                  'commission',
                                  'net_revenue',
                                  'brand_id',
                                  'year'

                              );

    public $fwDigitalColumnsT = array(
                                  'client_id',
                                  'agency_id',
                                  'campaign',
                                  'insertion_order',
                                  'insertion_order_id',
                                  'region_id',
                                  'sales_rep_id',
                                  'io_start_date',
                                  'io_end_date',
                                  'agency_commission_percentage',
                                  'rep_commission_percentage',
                                  'currency_id',
                                  'placement',
                                  'buy_type',
                                  'content_targeting_set_name',
                                  'ad_unit',
                                  'month',
                                  'gross_revenue',
                                  'commission',
                                  'net_revenue',
                                  'brand_id',
                                  'year'

                              );

    public $fwDigitalColumns = array(
                                  'client_id',
                                  'agency_id',
                                  'campaign',
                                  'insertion_order',
                                  'insertion_order_id',
                                  'region_id',
                                  'sales_rep_id',
                                  'io_start_date',
                                  'io_end_date',
                                  'agency_commission_percentage',
                                  'rep_commission_percentage',
                                  'currency_id',
                                  'placement',
                                  'buy_type',
                                  'content_targeting_set_name',
                                  'ad_unit',
                                  'month',
                                  'gross_revenue',
                                  'commission',
                                  'net_revenue',
                                  'brand_id',
                                  'year'

                              );

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

    public $dataHubColumnsF = array(
                                'holding_company',
                                'invoice_holding_company',
                                'year',
                                'month',
                                'master_channel',
                                'channel',
                                'sales_rep',
                                'client',
                                'product',
                                'agency',
                                'campaign_desc',
                                'campaign',
                                'start_and_end_date',
                                'currency',
                                'agency_commission_percentage',
                                'booked_spots',
                                'gross_revenue',
                                'gross_revenue_prate',
                                'gross_revenue_curr_prate'
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
    
    public $insightsColumnsF = array('brand',
                                     'brand_feed',
                                     'sales_rep',
                                     'agency',
                                     'client',
                                     'month',                                     
                                     'currency',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',                                     
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'gross_revenue_prate', //DOUBLE
                                     'year' //DOUBLE
    );
    

    public $insightsColumnsS = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency',
                                     'client',
                                     'month',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',                                     
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'gross_revenue_prate', //DOUBLE
                                     'year' //DOUBLE
                                     
    );


    public $insightsColumnsT = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',                                     
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'gross_revenue_prate', //DOUBLE
                                     'year' //DOUBLE
    );

    public $insightsColumns = array('brand_id',
                                     'brand_feed',
                                     'sales_rep_id',
                                     'agency_id',
                                     'client_id',
                                     'month',
                                     'currency_id',
                                     'charge_type',
                                     'product',
                                     'campaign',
                                     'order_reference',
                                     'schedule_event',
                                     'spot_status',
                                     'date_event',
                                     'unit_start_time',
                                     'duration_spot',
                                     'copy_key',
                                     'media_item',
                                     'spot_type',
                                     'duration_impression',
                                     'gross_revenue', //DOUBLE
                                     'num_spot', //INT
                                     'net_revenue', //DOUBLE
                                     'year'//INT
    );


    public $insightsBTSColumnsF = array('order_reference',
                                         'sales_rep',
                                         'brand',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'
    );

    public $insightsBTSColumnsS = array('order_reference',
                                         'sales_rep_id',
                                         'brand_id',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'
    );

    public $insightsBTSColumnsT = array('order_reference',
                                         'sales_rep_id',
                                         'brand_id',
                                         'spot_type',
                                         'duration_spot',
                                         'schedule_event',
                                         'media_item',
                                         'copy_key',
                                         'duration_impression',
                                         'date_event',
                                         'spot_status',
                                         'gross_revenue'

    );

     
	public $salesRepColumns = array('sales_group_id','name');
	public $salesRepUnitColumns = array('sales_rep_id','origin_id','name');
	
    public $planByBrandColumns = array('sales_office_id','currency_id','brand_id','source','year','month','type_of_revenue','revenue');

}
