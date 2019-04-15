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

        var_dump($region);
        var_dump($currency);

	}

	public function addUsers(){

		return false;
	}

	public function addSalesRepresentativeGroup(){

		return false;
	}

	public function addSalesRepresentative(){

		return false;
	}

	public function addSalesRepresentativeUnit(){

		return false;
	}

	public function addPRate(){

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

	public function getSalesRepresentativeGroup(){

		return false;
	}

	public function getSalesRepresentative(){

		return false;
	}

	public function getSalesRepresentativeUnit(){

		return false;
	}

	public function getCurrency(){

		return false;
	}


	public function getPRate(){

		return false;
	}

	public function getBrands(){

		return false;
	}

	public function getBrandUnits(){

		return false;
	}

	public function getOrigin(){
		
	}

}
