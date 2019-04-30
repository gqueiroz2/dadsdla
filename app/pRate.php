<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\region;

class pRate extends Management{
    
	public function getPRate($con,$id){
		$where = "";

		if($id){
			$ids = implode($id);
			$where .= "WHERE p.ID IN ($ids)";
		}

		$sql = new sql();
		$table = "p_rate p";
		$columns = "p.ID AS 'id',					
					p.year AS 'year',
					p.value AS 'value',				
					c.name AS 'currency',
					r.name AS 'region'
				   ";
		$from = array('id','year','value','currency','region');	
		$join = "LEFT JOIN currency c ON p.currency_id = c.ID
				 LEFT JOIN region r ON c.region_id = r.ID";
		$order = "2,5,4";
		$result = $sql->select($con,$columns,$table,$join,$where,$order);
		$pRate = $sql->fetch($result,$from,$from);		
		return $pRate;
	}

	public function addPRate($con){
		$sql = new sql();
		$year = Request::get('year');
		$currency = Request::get('currency');
		$value = doubleval(Request::get('value'));
		$table = 'p_rate';
		$columns = 'currency_id,year,value';
		$values = " '$currency','$year','$value' ";
		$bool = $sql->insert($con,$table,$columns,$values);
		return $bool;
		
	}

	public function editpRate($con){
		$sql = new sql();
		$r = new region();
		$size = Request::get('size');
		$columnsWhere = array('currency_id','value','year');
		$columnsSet = array('value','year');
		$table = "p_rate";
		$cc = $this->getCurrency($con);


		for ($i=0; $i <$size ; $i++) { 
			$region[$i] = Request::get("region-$i");
			$currency[$i] = Request::get("currency-$i");
			$oY[$i] = Request::get("oldYear-$i");
			$oV[$i] = Request::get("oldValue-$i");
			$nY[$i] = Request::get("newYear-$i");
			$nV[$i] = Request::get("newValue-$i");
			for ($j=0; $j <sizeof($cc); $j++) { 
				if ($region[$i] == $cc[$j]["region"] && $currency[$i] == $cc[$j]["name"]) {
					$currencyID[$i] = $cc[$j]["id"];
				}
			}

			$arrayWhere[$i] = array($currencyID[$i],$oV[$i],$oY[$i]);
			$arraySet[$i] = array($nV[$i],$nY[$i]);


			$where[$i] = $sql->where($columnsWhere,$arrayWhere[$i]);

			$set[$i] = $sql->setUpdate($columnsSet,$arraySet[$i]);
		}

		$bool = false;

		for ($i=0; $i <$size ; $i++) { 
			if ($oY[$i] != $nY[$i] || $oV[$i] != $nV[$i]) {
				$bool = $sql->updateValues($con,$table,$set[$i],$where[$i]);
				if ($bool["bool"] == false) {
					break;
				}
			}
		}


		return $bool;
	}

	public function getCurrency($con,$id){
		$sql = new sql();
		$table = "currency c";
			
		$where = "";

		var_dump($id);

		if($id){
			$ids = implode($id);
			$where .= "WHERE c.ID IN ($ids)";
		}

		$columns = "c.ID AS 'id',
					c.name AS 'name',
					r.name AS 'region'
				   ";
		$from = array('id','name','region');	
		$join = "LEFT JOIN region r ON c.region_id = r.ID";
		$order = "3";
		$result = $sql->select($con,$columns,$table,$join,false,$order);
		$currency = $sql->fetch($result,$from,$from);
		return $currency;
	}

	public function addCurrency($con){
        $sql = new sql();
        $region = Request::get('region');
        $currency = Request::get('currency');
        $regionID = $this->getID($con,'region',$region);
        $table = 'currency';
        $columns = 'name,region_id';
        $values = " '$currency','$regionID' ";
        $bool = $sql->insert($con,$table,$columns,$values);
        return $bool;

	}

	public function editCurrency($con){
		$sql = new sql();
		$r = new region();
		$regions = $r->getRegion($con,"");
		$size = Request::get("size");
		$table = "currency";
		$col = array('name','region_id');

		for ($i=0; $i <$size ; $i++) { 
			$or[$i] = Request::get("OldRegion-$i");
			$on[$i] = Request::get("OldName-$i");
			$nr[$i] = Request::get("NewRegion-$i");
			$nn[$i] = Request::get("NewName-$i");
			for ($j=0; $j <sizeof($regions); $j++) { 
				if ($or[$i] == $regions[$j]["name"]) {
					$oldID[$i] = $regions[$j]["id"];
				}
				if ($nr[$i] == $regions[$j]["name"]) {
					$newID[$i] = $regions[$j]["id"];
				}
			}
			$arrayWhere[$i] = array($on[$i],$oldID[$i]);
			$arraySet[$i] = array($nn[$i],$newID[$i]);

			$where[$i] = $sql->where($col,$arrayWhere[$i]);
			$set[$i] = $sql->setUpdate($col,$arraySet[$i]);
		}

		$bool = false;

		for ($i=0; $i <$size ; $i++) { 
			if ($or[$i] != $nr[$i] || $on[$i] != $nn[$i]) {
				$bool = $sql->updateValues($con,$table,$set[$i],$where[$i]); 
				if ($bool["bool"] == false) {
					break;
				}
			}
		}

		return $bool;
	}

}
