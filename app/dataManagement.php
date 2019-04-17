<?php

namespace App;

use App\Management;
use Illuminate\Support\Facades\Request;

class dataManagement extends Management{
    
	public function addRegion($con){
		
		$region = Request::get('region');
		$table = 'region';
		$columns = 'name';
		$values = "'$region'";
		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;
	}

	public function addCurrency($dm,$con){
        
        $region = Request::get('region');
        $currency = Request::get('currency');
        $regionID = $dm->getID($con,'region',$region);
        $table = 'currency';
        $columns = 'name,region_id';
        $values = " '$currency','$regionID' ";
        $bool = $this->insert($con,$table,$columns,$values);

        return $bool;

	}

	public function addPRate($dm,$con){

		$year = Request::get('year');
		$currency = Request::get('currency');
		$value = doubleval(Request::get('value'));

		$table = 'p_rate';
		$columns = 'currency_id,year,value';
		$values = " '$currency','$year','$value' ";

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;
		
	}

	public function addUsers(){

		return false;
	}

	public function addSalesRepresentativeGroup($dm,$con){

		$region = Request::get('region');
		$salesRepGroup = Request::get('salesRepGroup');

		$table = 'sales_rep_group';
		$columns = 'region_id,name';
		$values = " '$region','$salesRepGroup' ";

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;
	}

	public function addSalesRepresentative(){

		return false;
	}

	public function addSalesRepresentativeUnit(){

		return false;
	}

	

	public function addBrands(){

		return false;
	}

	public function addBrandUnits(){

		return false;
	}

	public function addOrigin(){
		
	}

	public function getRegions($con){

		$something = "id , name";
		$table = "region";
		$where = FALSE;
		$order = "name";

		$parameters = array("id","name");

		$regions = $this->get($con,$parameters,$something,$table,$where,$order);

		return $regions;
	}

	public function getUsers(){

		return false;
	}

	public function getSalesRepresentativeGroup($con){
		$sql = "SELECT
					srg.ID AS 'id',
					srg.name AS 'name',
					r.name AS 'region'
				FROM
					sales_rep_group srg
				LEFT JOIN region r ON srg.region_id = r.ID
				ORDER BY region , name
				";
		
		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				
				$salesRepGroup[$count]["id"] = $row["id"];
				$salesRepGroup[$count]["region"] = $row["region"];				
				$salesRepGroup[$count]["name"] = $row["name"];

				$count++;
			}
		}else{
			$salesRepGroup = false;
		}

		return $salesRepGroup;

	}

	public function getSalesRepresentative($con){

		return false;
	}

	public function getSalesRepresentativeUnit($con){

		return false;
	}

	public function getCurrency($con){

		$sql = "SELECT
					c.ID AS 'id',
					c.name AS 'name',
					r.name AS 'region'
				FROM
					currency c
				LEFT JOIN region r ON c.region_id = r.ID
				ORDER BY region
				";

		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				
				$currency[$count]["id"] = $row["id"];
				$currency[$count]["region"] = $row["region"];				
				$currency[$count]["name"] = $row["name"];

				$count++;
			}
		}else{
			$currency = false;
		}

		return $currency;
	}

	public function getPRate($con){

		$sql = "SELECT
					p.ID AS 'id',					
					p.year AS 'year',
					p.value AS 'value',				
					c.name AS 'currency',
					r.name AS 'region'
				FROM
					p_rate p 
				LEFT JOIN currency c ON p.currency_id = c.ID
				LEFT JOIN region r ON c.region_id = r.ID
				ORDER BY year,region,currency
				";

		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				
				$pRate[$count]["id"] = $row["id"];
				$pRate[$count]["region"] = $row["region"];
				$pRate[$count]["currency"] = $row["currency"];
				$pRate[$count]["year"] = $row["year"];
				$pRate[$count]["value"] = $row["value"];
				

				$count++;
			}
		}else{
			$pRate = false;
		}

		return $pRate;
	}

	public function getBrands(){

		return false;
	}

	public function getBrandUnits(){

		return false;
	}

	public function getOrigin(){
		
	}

	public function addAgency(){
		
	}

}
