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
		//$salesReps = $this->checkNewSalesReps($conDLA,$con,$table,$sql);

		if($table == "cmaps"){
			$clients = $this->checkNewClientsNoRegion($conDLA,$con,$table,$sql);
			$agencies = $this->checkNewAgenciesNoRegion($conDLA,$con,$table,$sql);
		}else{
			$clients = $this->checkNewClients($conDLA,$con,$table,$sql,$region);
			$agencies = $this->checkNewAgencies($conDLA,$con,$table,$sql,$region);
		}
		var_dump($agencies);

		$rtr = array(
				'regions' => $regions,
				'brands' => $brands,
				'salesReps' => null,
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
		}elseif ($table == "forecast") {
			$selectDistinctFM = "SELECT DISTINCT client_id FROM $table ORDER BY client_id";
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
		}elseif ($table == "forecast") {
			$resultsFM = $sql->fetch($res,array("client_id"),array("client_id"));
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
			$new = $this->checkDifferencesAC('client', $distinctDLA, $distinctFM, $table);
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
		}elseif ($table == "forecast") {
			$selectDistinctFM = "SELECT DISTINCT agency_id FROM $table ORDER BY agency_id";
		}
		elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$selectDistinctFM = "SELECT DISTINCT agency,region FROM $table ORDER BY agency";
		}else{
			$selectDistinctFM = "SELECT DISTINCT agency,sales_representant_office FROM $table";
		}

		$res = $con->query($selectDistinctFM);
		$sql = new sql();

		if($table == "cmaps"){
			$resultsFM = $sql->fetch($res,array("agency"),array("agency"));
		}elseif($table == "forecast"){
			$resultsFM = $sql->fetch($res,array("agency_id"),array("agency_id"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("agency","region"),array("agency","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("agency","sales_representant_office"),array("agency","region"));
		}

		if($resultsFM){
			$distinctDLA = $this->getDistinctNR($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
			$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);

			$new = $this->checkDifferencesAC('agency', $distinctDLA, $distinctFM, $table);

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

		$new = $this->checkDifferences($distinctDLA, $distinctFM, $table);

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

		$new = $this->checkDifferences($distinctDLA, $distinctFM, $table);

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

			$new = $this->checkDifferences($distinctDLA, $distinctFM, $table);
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

			$new = $this->checkDifferences($distinctDLA, $distinctFM, $table);
			if($new){
				$new = array_values(array_unique($new));
			}
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
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

		//var_dump($table);

		if($table == "cmaps"){
			$selectDistinctFM = "SELECT DISTINCT client FROM $table ORDER BY client";
		}elseif($table == "forecast"){
			$selectDistinctFM = "SELECT DISTINCT client_id FROM $table ORDER BY client_id";
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
		}elseif ($table == "aleph") {
			$selectDistinctFM = "SELECT DISTINCT client,sales_office FROM $table
												WHERE (sales_office = '".$seekRegion['name']."')
												AND(client != '')
												ORDER BY sales_office,client ";
			//var_dump($selectDistinctFM);
		}elseif ($table == "wbd") {
			$selectDistinctFM = "SELECT DISTINCT client FROM $table ORDER BY client";
			var_dump($selectDistinctFM);
		}else{
			$selectDistinctFM = "SELECT DISTINCT client,sales_representant_office FROM $table
												WHERE (sales_representant_office = '".$seekRegion['name']."')
												AND(client != '')
												ORDER BY sales_representant_office,client ";
		}

		$res = $con->query($selectDistinctFM);

		if($table == "cmaps" || $table == "wbd"){
			$resultsFM = $sql->fetch($res,array("client"),array("client"));
			//var_dump($resultsFM);
		}elseif ($table == "forecast") {
			$resultsFM = $sql->fetch($res,array("client_id"),array("client_id"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("client","region"),array("client","region"));
		}elseif($table == "data_hub"){
			$resultsFM = $sql->fetch($res,array("client","holding_company"),array("client","region"));
		}elseif($table == "aleph") {
			$resultsFM = $sql->fetch($res,array("client","sales_office"),array("client","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("client","sales_representant_office"),array("client","region"));
		}
		
		if($resultsFM){

			if ($table == 'forecast') {
				$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"client_id");
				$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
				$new = $this->checkDifferencesAC('client_id', $distinctDLA, $distinctFM, $table);
			}else{
				$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"client");
				$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
				$new = $this->checkDifferencesAC('client', $distinctDLA, $distinctFM, $table);	
			}
			
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

		if($table == "cmaps"  || $table == 'wbd'
		){
			$selectDistinctFM = "SELECT DISTINCT agency FROM $table ORDER BY agency";
		}elseif ($table == "forecast") {
			$selectDistinctFM = "SELECT DISTINCT agency_id FROM $table ORDER BY agency_id";
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
		}elseif ($table == "aleph") {
			$selectDistinctFM = "SELECT DISTINCT agency,sales_office FROM $table
												WHERE (sales_office = '".$seekRegion['name']."')
												AND(agency != '')
												ORDER BY sales_office,agency ";
		}else{
			$selectDistinctFM = "SELECT DISTINCT agency,sales_representant_office FROM $table
														WHERE (sales_representant_office = '".$seekRegion['name']."')
														AND(agency != '')
														ORDER BY agency";
		}

		//var_dump($selectDistinctFM);

		$res = $con->query($selectDistinctFM);
		$sql = new sql();

		if($table == "cmaps" || $table == 'wbd'){
			$resultsFM = $sql->fetch($res,array("agency"),array("agency"));
		}elseif ($table == "forecast") {
			$resultsFM = $sql->fetch($res,array("agency_id"),array("agency_id"));
		}elseif($table == "fw_digital" || $table == "sf_pr" || $table == "sf_pr_brand"){
			$resultsFM = $sql->fetch($res,array("agency","region"),array("agency","region"));
		}elseif($table == "data_hub"){
			$resultsFM = $sql->fetch($res,array("agency","holding_company"),array("agency","region"));
		}elseif($table == "aleph") {
			$resultsFM = $sql->fetch($res,array("agency","sales_office"),array("agency","region"));
		}else{
			$resultsFM = $sql->fetch($res,array("agency","sales_representant_office"),array("agency","region"));
		}
		//var_dump($resultsFM);
		if($resultsFM){			
			if ($table == 'forecast') {
				$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"agency_id");
				$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
				//var_dump();
				$new = $this->checkDifferencesAC('agency_id', $distinctDLA, $distinctFM, $table);
			}else{
				$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"agency");
				$distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
				$new = $this->checkDifferencesAC('agency', $distinctDLA, $distinctFM, $table);	
			}
			

		}else{
			$new = false;
		}

		return $new;
	}


	public function getDistinct($con,$something,$table,$sql,$from,$region,$type){

		if($region){
			if($type == "agency" || $type == 'agency_id'){
				$join = "LEFT JOIN agency a ON t.agency_id = a.ID
				         LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
				         LEFT JOIN region r ON ag.region_id = r.ID";
			}elseif ($type == "client" || $type == 'client_id') {
				$join = "LEFT JOIN client c ON t.client_id = c.ID
				         LEFT JOIN client_group cg ON c.client_group_id = cg.ID
				         LEFT JOIN region r ON cg.region_id = r.ID";
			}

			$select = "SELECT DISTINCT t.$something FROM $table t $join WHERE(r.name = '".$region."') AND(t.$something != '') ORDER BY $something ";

		}else{
			$select = "SELECT DISTINCT $something FROM $table ORDER BY $something";
		}
		//var_dump($select);
		$res = $con->query($select);
		$tmp = $sql->fetch($res,$from,$from);

		for ($t=0; $t < sizeof($tmp); $t++) {
			for ($f=0; $f < sizeof($from); $f++) {
				$distinct[$t] = $tmp[$t][$from[$f]];
			}
		}
		//var_dump($distinct);
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


	/* public function checkDifferencesAC($type,$dla,$fm){

		$new = array();

		for ($f=0; $f < sizeof($fm); $f++) {
			$check = false;

			for ($d=0; $d < sizeof($dla); $d++) {

				//if( trim( $fm[$f][$type] ) !== trim( $dla[$d] ) || trim( strtolower($fm[$f][$type]) ) !== trim( strtolower($dla[$d]) ) ){
				if ( strcmp( trim($fm[$f][$type]),trim($dla[$d]) ) ){
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


	} */

	function remove_accents($string) {
		if ( !preg_match('/[\x80-\xff]/', $string) )
			return $string;
	
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
		);
	
		$string = strtr($string, $chars);
	
		return $string;
	}

	public function checkDifferencesAC(string $type, array $dla, array $fm, string $table){

		$new = array();
		$test = array();
		$formattedName = array();
		//var_dump($fm);
		//var_dump($dla);

		for ($f = 0; $f < sizeof($fm); $f++) {
			$fmName[] = $this->remove_accents($fm[$f][$type]);
		}

		for ($d = 0; $d < sizeof($dla); $d++) {
			$dlaName[] = $this->remove_accents($dla[$d]);
		}

		//var_dump($fmName);
		//var_dump($dlaName);

		$typeName = array_udiff($fmName, $dlaName, 'strcasecmp');
		//var_dump($typeName);

		$regionID = array_keys($typeName);
		//var_dump();

		for ($j = 0; $j < sizeof($typeName); $j++) {
			$formattedName[] = $fm[$regionID[$j]][$type];
		}
		
		//var_dump($formattedName);

		//var_dump($table);

		if ($table != 'cmaps' && $table != 'wbd' && $table != 'forecast') {
			for ($r = 0; $r < sizeof($typeName); $r++) {
				$region[] = $fm[$regionID[$r]]['region'];
			}

			for ($x = 0; $x < sizeof($formattedName); $x++){
				$test[$type] = $formattedName[$x];
				$test['region'] = $region[$x];
				$new[$x] = $test;
			} 

		} else {
			for ($x = 0; $x < sizeof($formattedName); $x++){
				$test[$type] = $formattedName[$x];
				$test['region'] = 'BRAZIL';
				$new[$x] = $test;
			} 
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
		}

		//var_dump($rtr);
		return $rtr;

	}

	public function checkDifferences($dla, $fm, $table){
		
		$new = array();
		$test = array();
		$formattedName = array();
		//var_dump($fm);
		//var_dump($dla);

		for ($f = 0; $f < sizeof($fm); $f++) {
			$fmName[] = $this->remove_accents($fm[$f]);
		}

		for ($d = 0; $d < sizeof($dla); $d++) {
			$dlaName[] = $this->remove_accents($dla[$d]);
		}

		//var_dump($fmName);
		//var_dump($dlaName);

		$typeName = array_udiff($fmName, $dlaName, 'strcasecmp');
		//var_dump($typeName);

		$regionID = array_keys($typeName);
		//var_dump($regionID);

		for ($j = 0; $j < sizeof($typeName); $j++) {
			$formattedName[] = $fm[$regionID[$j]];
		}
		
		//var_dump($formattedName);

		//var_dump($table);

		if ($table != 'cmaps' && $table != 'aleph' && $table != "wbd") {
			for ($r = 0; $r < sizeof($typeName); $r++) {
				$region[] = $fm[$regionID[$r]]['region'];
			}

			for ($x = 0; $x < sizeof($formattedName); $x++){
				$test[] = $formattedName[$x];
				$test['region'] = $region[$x];
				$new[$x] = $test;
			} 
		} else {

			for ($x = 0; $x < sizeof($formattedName); $x++){
				$test[] = $formattedName[$x];
				$test['region'] = 'BRAZIL';
				$new[$x] = $test;
			} 
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
		}

		//var_dump($rtr);
		return $rtr;



	}

	public function makeDistinct($array){

		$unique = array_map("unserialize", array_unique(array_map("serialize", $array)));
		//var_dump($unique);
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
