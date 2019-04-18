<?php

namespace App;

use Illuminate\Support\Facades\Request;

use App\Management;
use App\salesRep;
use App\brand;

class dataManagement extends Management{
    
	/*public function addRegion($con){
		
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
		
	}*/

	public function editRegion($con){
		$size = intval(Request::get("size"));
		$table = "region";
		$columns = array("name");
		$columnsWhere = array("name");
		for ($i=0; $i <$size ; $i++) { 
			$old[$i] = Request::get("Old-$i");
			$new[$i] = Request::get("New-$i");
			$values[$i] = array($old[$i]);
			$values2[$i] = array($new[$i]);
			$where[$i] = $this->where($columnsWhere,$values[$i]);
			$setUpdate[$i] = $this->setUpdate($columns,$values2[$i]);
		}


		for ($i=0; $i <$size ; $i++) { 
			$bool = $this->updateValues($con,$table,$setUpdate[$i],$where[$i]);
			if ($bool["bool"] == false) {
				break;
			}
		}

		return $bool;
	}

	public function editCurrency($con){
		$size = intval(Request::get("size"));
		$table = "currency";
		$columns = array('name','region_id');
		$columnsWhere = array("name","region_id");

		for ($i=0; $i <$size ; $i++) { 
			$OldRegion[$i] = Request::get("OldRegion-$i");
			$NewRegion[$i] = Request::get("NewRegion-$i");
			$OldCurrency[$i] = Request::get("OldName-$i");
			$NewCurrency[$i] = Request::get("NewName-$i");
			$OldId[$i] = $this->getID($con,'region',$OldRegion[$i]);
			$NewId[$i] = $this->getID($con,'region',$NewRegion[$i]);
			$values2[$i] = array($NewCurrency[$i],$NewId[$i]);
			$values[$i] = array($OldCurrency[$i],$OldId[$i]);
			$set[$i] = $this->setUpdate($columns,$values2[$i]);
			$where[$i] = $this->where($columns, $values[$i]);
		}

		for ($i=0; $i <$size ; $i++) { 
			$bool = $this->updateValues($con,$table,$set[$i],$where[$i]);
			if($bool["bool"] == false){
				break;
			}
		}

		return $bool;
	}

	public function editPRate($con){
		$size = intval(Request::get("size"));
		$table = "p_rate";
		$columns = array('year','value');
		$columnsWhere = array('currency_id','year','value');

		for ($i=0; $i <$size; $i++) { 
			$region[$i] = Request::get("region-$i");
			$currency[$i] = Request::get("currency-$i");
			$oldYear[$i] = Request::get("oldYear-$i");
			$oldValue[$i] = Request::get("oldValue-$i");
			$newYear[$i] = Request::get("newYear-$i");
			$newValue[$i] = Request::get("newValue-$i");
			$regionId[$i] = $this->getID($con,'region',$region[$i]);
			$currencyId[$i] = $this->getID($con,'currency',$currency[$i]);

			$values[$i] = array($newYear[$i],$newValue[$i]);
			$values2[$i] = array($currencyId[$i],$oldYear[$i],$oldValue[$i]);

			$where[$i] = $this->where($columnsWhere, $values2[$i]);
			$set[$i] = $this->setUpdate($columns,$values[$i]);
			
			if ($oldYear[$i] == $newYear[$i] && $newValue[$i] == $oldValue[$i]) {
				$verifier[$i] = false;
			}else{
				$verifier[$i] = true;
			}
		}


		for ($i=0; $i <$size ; $i++) { 
			if ($verifier[$i] == true) {
				$bool = $this->updateValues($con,$table,$set[$i],$where[$i]);
				if($bool["bool"] == false){
					break;
				}
			}
		}

		return $bool;
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

		$sr = new salesRep();

		$result = $sr->getSalesRepGroup($con,false);	

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

		$sr = new salesRep();

		$result = $sr->getSalesRep($con,false);

		if($result && $result->num_rows >0){
			$count = 0;
			while($row = $result->fetch_assoc()){
				$salesRep[$count]["id"] = $row["id"];
				$salesRep[$count]["salesRepGroup"] = $row["salesRepGroup"];
				$salesRep[$count]["salesRep"] = $row["salesRep"];
				$salesRep[$count]["region"] = $row["region"];

				$count ++;
			}
		}else{
			$salesRep = false;
		}
	
		return $salesRep;		
	}

	public function getSalesRepresentativeUnit($con){

		$sr = new salesRep();

		$result = $sr->getSalesRepUnit($con,false);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {

				$salesRepUnit[$count]['id'] = $row['id'];
				$salesRepUnit[$count]['salesRepUnit'] = $row['salesRepUnit'];
				$salesRepUnit[$count]['salesRep'] = $row['salesRep'];
				$salesRepUnit[$count]['origin'] = $row['origin'];
				
				$count ++;
			}
		}else{
			$salesRepUnit = false;
		}

		return $salesRepUnit;
		
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
				ORDER BY region,currency,year DESC
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

	public function getBrand($con){
		$br = new brand();

		$table = "brand";
		$columns = "id,name";

		$result = $br->select($con,$columns,$table,false,false,1);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				$brand[$count]['id'] = $row['id'];
				$brand[$count]['name'] = $row['name'];

				$count ++;
			}
		}else{
			$brand = false;
		}

		return $brand;
	}

	public function getBrandUnit($con){
		$br = new brand();

		$table = "brand_unit brdu";

		$columns = "brdu.ID AS 'id',
					brdu.name AS 'brandUnit',
					brd.name AS 'brand',
					o.name AS 'origin'
					";

		$join = "LEFT JOIN brand brd ON brd.ID = brdu.brand_id
				 LEFT JOIN origin o ON o.ID = brdu.origin_id
				";

		$result = $br->select($con,$columns,$table,$join,false,1);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				$brandUnit[$count]['id'] = $row['id'];
				$brandUnit[$count]['brandUnit'] = $row['brandUnit'];
				$brandUnit[$count]['brand'] = $row['brand'];
				$brandUnit[$count]['origin'] = $row['origin'];

				$count++;
			}

		}else{
			$brandUnit = false;
		}

		return $brandUnit;
	}

	public function getOrigin($con){
		
		$sql = "SELECT id,name FROM origin ORDER BY name";

		$result = $con->query($sql);

		if($result && $result->num_rows > 0){
			$count = 0;
			while ($row = $result->fetch_assoc()) {
				
				$origin[$count]['id'] = $row['id'];
				$origin[$count]['name'] = $row['name'];

				$count ++;
			}
		}else{
			$origin = false;
		}

		return $origin;
	}

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

	public function addSalesRepresentative($dm,$con){

		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRep = Request::get('salesRep');

		$table = 'sales_rep';
		$columns = 'sales_group_id,name';
		$values = " '$salesRepGroupID','$salesRep' ";

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;

	}

	public function addSalesRepresentativeUnit($dm,$con){

		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRepID = Request::get('salesRep');
		$salesRepUnit = Request::get('salesRepUnit');
		$origin = Request::get('origin');

		$table = 'sales_rep_unit';
		$columns = 'sales_rep_id,origin_id,name';
		$values = " '$salesRepID','$origin','$salesRepUnit' ";

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;

	}

	

	public function addBrand($con){

		$brand = Request::get('brand');

		$table = 'brand';
		$columns = 'name';
		$values = "'$brand'";

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;
	}

	public function addBrandUnit($con){

		$brandID = Request::get('brand');
		$originID = Request::get('origin');
		$brandUnit = Request::get('brandUnit');

		var_dump($brandID);
		var_dump($originID);
		var_dump($brandUnit);

		$table = 'brand_unit';
		$columns = 'brand_id,origin_id,name';
		$values = "'$brandID','$originID','$brandUnit'";

		$bool = $this->insert($con,$table,$columns,$values);
		
		return $bool;
	}

	public function addOrigin($con){
    	$origin = Request::get('origin');

    	$table = "origin";
		$columns = 'name';
		$values = '"'.$origin.'"';

		$bool = $this->insert($con,$table,$columns,$values);

		return $bool;
	}

	public function addAgency(){
		
	}
	

}
