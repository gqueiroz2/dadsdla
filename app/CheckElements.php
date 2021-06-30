<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;
use App\region;
use App\salesRep;
class CheckElements extends Model{
    
	public function newValues($conDLA,$con,$region,$table){

		$sql = new sql();

		$currencies = false;

		if($table == "cmaps"){			
			$regions = false;			
		}else{
			$regions = false;//$this->checkNewRegions($conDLA,$con,$table,$sql);	
			//$currencies = $this->checkNewCurrencies($conDLA,$con,$table,$sql);		
		}

		$brands = $this->checkNewBrands($conDLA,$con,$table,$sql);
		$salesReps = $this->checkNewSalesReps($conDLA,$con,$table,$sql);

		if($table == "cmaps"){
			$clients = $this->checkNewClientsNoRegion($conDLA,$con,$table,$sql);
			$agencies = $this->checkNewAgenciesNoRegion($conDLA,$con,$table,$sql);
		}else{
			$clients = $this->checkNewClients($conDLA,$con,$table,$sql,$region);			
			$agencies = $this->checkNewAgencies($conDLA,$con,$table,$sql,$region);
		}
		

		$rtr = array(
				'regions' => $regions,
				'brands' => $brands,
				'salesReps' => $salesReps,
				'clients' => $clients,
				'agencies' => $agencies,
				'currencies' => $currencies
			);

		return($rtr);
	}

	public function newValuesNoRegion($conDLA,$con,$table){

		$sql = new sql();

		$salesReps = $this->checkNewSalesReps($conDLA,$con,$table,$sql);
		$clients = $this->checkNewClientsNoRegion($conDLA,$con,$table,$sql);
		$agencies = $this->checkNewAgenciesNoRegion($conDLA,$con,$table,$sql);
		$brands = $this->checkNewBrands($conDLA,$con,$table,$sql);

		$rtr = array(
				'brands' => $brands,
				'salesReps' => $salesReps,
				'clients' => $clients,
				'agencies' => $agencies,
			);

		return($rtr);
	}

	public function checkNewClientsNoRegion($conDLA,$con,$table,$sql){
		$sql = new sql();
		$tableDLA = 'client_unit';

		$somethingDLA = "name";
		$something = "client";

		$fromDLA = array("name");
		$from = array("client");

		if($table == "cmaps"){
			$selectDistinctFM = "SELECT DISTINCT client FROM $table ORDER BY client";		
		}elseif($table == "data_hub"){
			$selectDistinctFM = "SELECT DISTINCT client,holding_company FROM $table ORDER BY client";		
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$selectDistinctFM = "SELECT DISTINCT client,region FROM $table ORDER BY client";		
		}else{
			$selectDistinctFM = "SELECT DISTINCT client,sales_representant_office FROM $table";
		}

		$res = $con->query($selectDistinctFM);

		if($table == "cmaps"){
			$resultsFM = $sql->fetch($res,array("client"),array("client"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("client","region"),array("client","region"));
		}elseif($table == "data_hub"){
			$resultsFM = $sql->fetch($res,array("client","holding_company"),array("client","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("client","sales_representant_office"),array("client","region"));
		}
		
		if($resultsFM){
			$distinctDLA = $this->getDistinctNR($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
			$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
			$new = $this->checkDifferencesAC('client',$distinctDLA,$distinctFM);
			if($table != "cmaps"){
				if($new){
					$count = 0;
					for ($nn=0; $nn < sizeof($new); $nn++) { 
						$nova[$count] = $new[$nn]['region'];
						$count++;
					}
					$new = array_values(array_unique($nova));
				}
			}
		}else{
			$new = false;
		}
		return $new;
	}

	public function checkNewAgenciesNoRegion($conDLA,$con,$table,$sql){
		$tableDLA = 'agency_unit';

		$somethingDLA = "name";
		$something = "agency";

		$fromDLA = array("name");
		$from = array("agency");

		$r = new region();

		if($table == "cmaps"){
			$selectDistinctFM = "SELECT DISTINCT agency FROM $table ORDER BY agency";		
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$selectDistinctFM = "SELECT DISTINCT agency,region FROM $table ORDER BY agency";		
		}else{
			$selectDistinctFM = "SELECT DISTINCT agency,sales_representant_office FROM $table";		
		}

		$res = $con->query($selectDistinctFM);
		$sql = new sql();

		if($table == "cmaps"){
			$resultsFM = $sql->fetch($res,array("agency"),array("agency"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("agency","region"),array("agency","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("agency","sales_representant_office"),array("agency","region"));
		}

		if($resultsFM){
			$distinctDLA = $this->getDistinctNR($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
			$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);

			$new = $this->checkDifferencesAC('agency',$distinctDLA,$distinctFM);

			if($table != "cmaps"){
				if($new){
					$count = 0;
					for ($nn=0; $nn < sizeof($new); $nn++) { 
						$nova[$count] = $new[$nn]['region'];
						$count++;
					}
					$new = array_values(array_unique($nova));
				}
			}
		}else{
			$new = false;
		}

		return $new;
	}

	public function checkNewRegions($conDLA,$con,$table,$sql){

		$tableDLA = "region";
		
		$somethingDLA = "name";
		$something1 = "campaign_sales_office";
		$something2 = "sales_representant_office";

		$fromDLA = array("name");
		$from1 = array($something1);
		$from2 = array($something2);


		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM1 = $this->getDistinct($con,$something1,$table,$sql,$from1);
		$distinctFM2 = $this->getDistinct($con,$something2,$table,$sql,$from2);

		$tmp = array_merge($distinctFM1,$distinctFM2);
		$distinctFM = array_values(array_unique($tmp));

		$new = $this->checkDifferences($distinctDLA,$distinctFM);

		return $new;

	}

	public function checkNewBrands($conDLA,$con,$table,$sql){

		$tableDLA = "brand_unit";
			
		$somethingDLA = "name";

		if($table == "fw_digital"){
			$something = "ad_unit";
		}else if($table == "data_hub"){
			$something = "master_channel";
		}else{
			$something = "brand";	
		}	

		$fromDLA = array("name");
		$from = array($something);

		$distinctDLA = $this->getDistinctNR($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,false,false);
		$distinctFM = $this->getDistinctNR($con,$something,$table,$sql,$from,false,false);

		$new = $this->checkDifferences($distinctDLA,$distinctFM);

		if($new){
			$new = array_values(array_unique($new));
		}

		return $new;

	}

	public function checkNewSalesReps($conDLA,$con,$table,$sql){	
		$sr = new salesRep();

		if($table == "cmaps" || $table == "data_hub" ){
			$tableDLA = "sales_rep_unit";
			
			$somethingDLA = "name";
			$something = "sales_rep";

			$fromDLA = array("name");
			$from = array($something);

			$distinctDLA = $this->getDistinctNR($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
			$distinctFM = $this->getDistinctNR($con,$something,$table,$sql,$from);
			
			$new = $this->checkDifferences($distinctDLA,$distinctFM);
			if($new){
				$new = array_values(array_unique($new));
			}
		}else{

			$tableDLA = "sales_rep_unit";
			
			$somethingDLA = "name";
			$something = "sales_rep_owner";

			$fromDLA = array("name");
			$from = array($something);

			$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,false,false);
			$distinctFM = $this->getDistinct($con,$something,$table,$sql,$from,false,false);

			$new = $this->checkDifferences($distinctDLA,$distinctFM);
			if($new){
				$new = array_values(array_unique($new));
			}
		}

		$tp = $new;
		$missing = array();

		if( is_array($new) ){
			$prop = array();
			for ($n=0; $n < sizeof($new); $n++) { 
				$son =  explode("/",$new[$n]);

				$temp = explode(",",$new[$n]);
				unset($tp[$n]);
				if(sizeof($temp) > 1){
					$sales0 = trim($temp[0]);
					$sales1 = trim($temp[1]);
					array_push($prop, $sales0);
					array_push($prop, $sales1);
				}else{
					$temp2 =  explode("/",$new[$n]);
					if(sizeof($temp2) > 1){
						$sales0 = trim($temp2[0]);
						$sales1 = trim($temp2[1]);
						array_push($prop, $sales0);
						array_push($prop, $sales1);
					}else{
						$temp3 =  explode("|",$new[$n]);

						if(sizeof($temp3) > 1){
							$sales0 = trim($temp3[0]);
							$sales1 = trim($temp3[1]);
							array_push($prop, $sales0);
							array_push($prop, $sales1);
						}else{
							array_push($prop,$new[$n]);
						}
					}
				}
			}

			$prop = array_values(array_unique($prop));

			for ($p=0; $p < sizeof($prop); $p++) { 
				$check[$p] = $sr->getSalesRepUnitByName($conDLA,$prop[$p])[0]['salesRepUnit'];
				if(!$check[$p]){
					array_push($missing, $prop[$p]);
				}
			}
			
		}

		$rtr = false;

		$zz = 0;

		if(!empty($missing)){
			for ($m=0; $m < sizeof($missing); $m++) { 
				$rtr[$zz] = $missing[$m];
				$zz++;
			}
		}

		if(!empty($tp)){
			for ($t=0; $t < sizeof($tp); $t++) { 
				$rtr[$zz] = $tp[$t];
				$zz++;
			}
		}
		return $rtr;
	}

	public function checkNewClients($conDLA,$con,$table,$sql,$region){
		$sql = new sql();
		$tableDLA = 'client_unit';

		$somethingDLA = "name";
		$something = "client";

		$fromDLA = array("name");
		$from = array("client");

		$r = new region();

		$seekRegion = $r->getRegion($conDLA,array($region))[0];

		var_dump($table);

		if($table == "cmaps"){
			$selectDistinctFM = "SELECT DISTINCT client FROM $table ORDER BY client";		
		}else if($table == "data_hub"){
			if($seekRegion['name'] == "Europe"){
				$seekRegion['name'] = "%EUROPE%";
			}
			$selectDistinctFM = "SELECT DISTINCT client,holding_company FROM $table 
													WHERE (holding_company LIKE '".$seekRegion['name']."')
													AND(client != '')
													ORDER BY holding_company,client ";				
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$selectDistinctFM = "SELECT DISTINCT client,region FROM $table 
												WHERE (region = '".$seekRegion['name']."')
												AND(client != '')
												ORDER BY region,client ";
		}else{
			$selectDistinctFM = "SELECT DISTINCT client,sales_representant_office FROM $table 
												WHERE (sales_representant_office = '".$seekRegion['name']."')
												AND(client != '')
												ORDER BY sales_representant_office,client ";
		}

		$res = $con->query($selectDistinctFM);

		if($table == "cmaps"){
			$resultsFM = $sql->fetch($res,array("client"),array("client"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("client","region"),array("client","region"));
		}elseif($table == "data_hub"){
			$resultsFM = $sql->fetch($res,array("client","holding_company"),array("client","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("client","sales_representant_office"),array("client","region"));
		}

		if($resultsFM){

			$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"client");

			$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);

			$new = $this->checkDifferencesAC('client',$distinctDLA,$distinctFM);
		}else{
			$new = false;
		}
		return $new;
	}	

	public function checkNewAgencies($conDLA,$con,$table,$sql,$region){
		$tableDLA = 'agency_unit';

		$somethingDLA = "name";
		$something = "agency";

		$fromDLA = array("name");
		$from = array("agency");

		$r = new region();

		$seekRegion = $r->getRegion($conDLA,array($region))[0];

		if($table == "cmaps"){
			$selectDistinctFM = "SELECT DISTINCT agency FROM $table ORDER BY agency";		
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$selectDistinctFM = "SELECT DISTINCT agency,region FROM $table 
														WHERE (region = '".$seekRegion['name']."')
														AND(agency != '')
														ORDER BY agency";		
		}elseif($table == "data_hub"){
			$selectDistinctFM = "SELECT DISTINCT agency,holding_company FROM $table 
														WHERE (holding_company = '".$seekRegion['name']."')
														AND(agency != '')
														ORDER BY agency";		
		}else{
			$selectDistinctFM = "SELECT DISTINCT agency,sales_representant_office FROM $table 
														WHERE (sales_representant_office = '".$seekRegion['name']."')
														AND(agency != '')
														ORDER BY agency";		
		}		

		$res = $con->query($selectDistinctFM);
		$sql = new sql();

		if($table == "cmaps"){
			$resultsFM = $sql->fetch($res,array("agency"),array("agency"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("agency","region"),array("agency","region"));
		}elseif($table == "data_hub"){
			$resultsFM = $sql->fetch($res,array("agency","holding_company"),array("agency","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("agency","sales_representant_office"),array("agency","region"));
		}

		if($resultsFM){

			$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"agency");
			$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);

			$new = $this->checkDifferencesAC('agency',$distinctDLA,$distinctFM);

		}else{
			$new = false;
		}

		return $new;
	}

	
	public function getDistinct($con,$something,$table,$sql,$from,$region,$type){
		
		if($region){
			if($type == "agency"){
				$join = "LEFT JOIN agency a ON t.agency_id = a.ID 
				         LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID 
				         LEFT JOIN region r ON ag.region_id = r.ID";
			}elseif ($type == "client") {
				$join = "LEFT JOIN client c ON t.client_id = c.ID 
				         LEFT JOIN client_group cg ON c.client_group_id = cg.ID 
				         LEFT JOIN region r ON cg.region_id = r.ID";	
			}
			
			$select = "SELECT DISTINCT t.$something FROM $table t $join WHERE(r.name = '".$region."') AND(t.$something != '') ORDER BY $something ";

		}else{
			$select = "SELECT DISTINCT $something FROM $table ORDER BY $something";	
		}
		
		$res = $con->query($select);
		$tmp = $sql->fetch($res,$from,$from);

		for ($t=0; $t < sizeof($tmp); $t++) { 
			for ($f=0; $f < sizeof($from); $f++) { 
				$distinct[$t] = $tmp[$t][$from[$f]];
			}
		}
		return $distinct;	
	}

	public function getDistinctNR($con,$something,$table,$sql,$from){
		
		$select = "SELECT DISTINCT $something FROM $table ORDER BY $something";	

		$res = $con->query($select);
		$tmp = $sql->fetch($res,$from,$from);

		for ($t=0; $t < sizeof($tmp); $t++) { 
			for ($f=0; $f < sizeof($from); $f++) { 
				$distinct[$t] = $tmp[$t][$from[$f]];
			}
		}
		return $distinct;	
	}

	public function checkDifferencesAC($type,$dla,$fm){

		$new = array();		

		for ($f=0; $f < sizeof($fm); $f++) { 
			$check = false;

			for ($d=0; $d < sizeof($dla); $d++) { 
				//if( trim( $fm[$f][$type] ) == trim( $dla[$d] ) || trim( strtolower($fm[$f][$type]) ) == trim( strtolower($dla[$d]) ) ){
				if ( strcasecmp( trim($fm[$f][$type]) , trim($dla[$d]) ) ){	
					$check = true;
					break;
				}
			}

			if(!$check){
				$new[] = $fm[$f];
			}
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
		}

		return $rtr;


	}

	public function checkDifferences($dla,$fm){
		$new = array();		

		for ($f=0; $f < sizeof($fm); $f++) { 
			$check = false;
			for ($d=0; $d < sizeof($dla); $d++) { 
				//if($fm[$f] == $dla[$d]){
				if(strcasecmp($fm[$f],$dla[$d])){
					$check = true;
					break;
				}
			}

			if(!$check){
				$new[] = $fm[$f];
			}
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
		}

		return $rtr;


	}

	public function makeDistinct($array){

		$unique = array_map("unserialize", array_unique(array_map("serialize", $array)));

		return $unique;

	}

	public function checkNewCurrencies($conDLA,$con,$table,$sql){
		$tableDLA = "currency";
		
		$somethingDLA = "name";
		$something = "campaign_currency";

		$fromDLA = array("name");
		$from = array($something);

		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM = $this->getDistinct($con,$something,$table,$sql,$from);

		$new = $this->checkDifferences($distinctDLA,$distinctFM);
		return $new;

	}


	/*

	public function insertKeysOnArray($key,$new){

		$rtr = array();

		for ($n=0; $n < sizeof($new); $n++) { 
			$rtr[$key][$n] = $new[$n];
		}

		return $rtr;

	}*/

}
