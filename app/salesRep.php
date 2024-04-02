<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class salesRep extends Management
{
	/*
		Abreviations
		srg = sales_rep_group
		sr = sales_rep
		sru = sales_rep_unit
		o = origin
	*/
	

	public function getDirectorWBD($con,$year){
		$sql = new sql();
		$table = "wbd w";
		$columns = "w.manager AS 'director'
				    ";
		$where = "WHERE year = '$year'";
		$res = $sql->selectDistinct($con, $columns, $table, null, $where);
		$from = array('director');
		$director = $sql->fetch($res, $from, $from);
		//var_dump($director);
		return $director;

	}

	public function getSalesRepFilteredYear($con, $salesRepGroupID, $regionID, $year, $source)
	{
		$sql = new sql();
		$columns = "sr.name AS 'name',
					sr.ID as 'id',
					srg.name as 'salesRepGroup'";
		if ($source == "IBMS") {
			$table = "ytd";
			$gross = "gross_revenue";
		} elseif ($source == "CMAPS") {
			$table = "cmaps";
			$gross = "gross";
		} else {
			$table = "mini_header";
			$gross = "gross_revenue";
		}
		if ($salesRepGroupID[0] == 'all') {
			$regionsID = array($regionID);
			$reps = $this->getSalesRepByRegion($con, $regionsID);
		} else {
			$reps = $this->getSalesRep($con, $salesRepGroupID);
		}
		$add = "";
		if (date('m') == 01) {
			$year = $year - 1;
			$add = "AND month = '12'";
		}
		for ($r = 0; $r < sizeof($reps); $r++) {

			$firstSelect[$r] = "SELECT SUM($gross) AS sum FROM $table WHERE year = '$year' AND sales_rep_id = '" . $reps[$r]['id'] . "' $add";
			if ($source != "CMAPS") {
				$firstSelect[$r] .= "AND campaign_sales_office_id = '$regionID'";
			}
			$firstResult[$r] = $con->query($firstSelect[$r]);
			$results[$r] = $sql->fetchSum($firstResult[$r], "sum")["sum"];
		}
		for ($r = 0; $r < sizeof($results); $r++) {
			if ($results[$r] == 0) {
				unset($reps[$r]);
			}
		}
		$reps = array_values($reps);
		return $reps;
	}

	public function getSalesRepStatus($con, $salesRep, $year)
	{
		$sql = new sql();
		$from = array("status");
		for ($s = 0; $s < sizeof($salesRep); $s++) {
			$sqls[$s] = "SELECT status FROM sales_rep_status WHERE (sales_rep_id = '" . $salesRep[$s]["id"] . "') AND (year = '$year')";
			$result[$s] = $con->query($sqls[$s]);
			$salesStatus[$s] = $sql->fetch($result[$s], $from, $from)[0];
		}

		for ($s = 0; $s < sizeof($salesStatus); $s++) {
			if ($salesStatus[$s]["status"] == 0) {
				unset($salesRep[$s]);
			}
		}
		$salesRep = array_values($salesRep);
		return $salesRep;
	}

	public function getSalesRepGroupById($con, $id)
	{
		$sql = new sql();
		$table = "sales_rep_group srg";
		$columns = "srg.ID AS 'id',
				    srg.name AS 'name',
				    r.name AS 'region'";
		$where = "";
		if ($id) {
			$ids = "";
			for ($i = 0; $i < sizeof($id); $i++) {
				if ($i == 0) {
					$ids .= "'" . $id[$i] . "'";
				} else {
					$ids .= ",'" . $id[$i] . "'";
				}
			}
			$where .= "WHERE srg.ID IN ($ids)";
		}
		$join = "LEFT JOIN region r ON srg.region_id = r.ID";
		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'name', 'region');
		$salesRepGroup = $sql->fetch($res, $from, $from);
		return $salesRepGroup;
	}

	public function getSalesRepById($con, $id)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				srg.ID AS 'salesRepGroupID',
				r.name AS 'region',
				sr.ab_name AS abName";
		$where = "";
		if ($id) {
			$ids = "";
			for ($i = 0; $i < sizeof($id); $i++) {
				if ($i == 0) {
					$ids .= "'" . $id[$i] . "'";
				} else {
					$ids .= ",'" . $id[$i] . "'";
				}
			}
			$where .= "WHERE sr.ID IN ($ids)";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				 LEFT JOIN region r ON r.ID = srg.region_id";
		$order = "srg.ID,sr.name";
		$res = $sql->select($con, $columns, $table, $join, $where, $order);
		$from = array('id', 'salesRep', 'salesRepGroup', 'region', 'abName');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}

	public function getSalesRepGroup($con, $region)
	{
		$sql = new sql();
		$table = "sales_rep_group srg";
		$columns = "srg.ID AS 'id',
				    srg.name AS 'name',
				    r.name AS 'region'";
		$where = "";
		if ($region) {
			$regions = implode(",", $region);
			$where .= "WHERE r.ID IN ('$regions')";
		}
		$join = "LEFT JOIN region r ON srg.region_id = r.ID";
		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'name', 'region');
		$salesRepGroup = $sql->fetch($res, $from, $from);
		return $salesRepGroup;
	}

	public function addSalesRepGroup($con)
	{
		$sql = new sql();
		$region = Request::get('region');
		$salesRepGroup = Request::get('salesRepGroup');
		$table = 'sales_rep_group';
		$columns = 'region_id,name';
		$values = " '$region','$salesRepGroup' ";
		$bool = $sql->insert($con, $table, $columns, $values);
		return $bool;
	}
	public function editSalesRepGroup($con)
	{

		$sql = new sql();
		$size = Request::get("size");
		$table = "sales_rep_group";
		$columns = array("region_id", "name");
		for ($i = 0; $i < $size; $i++) {

			$oldRegion[$i] = Request::get("oldRegion-$i");
			$oldName[$i] = Request::get("oldName-$i");
			$newRegion[$i] = Request::get("newRegion-$i");
			$newName[$i] = Request::get("newName-$i");
			$arrayWhere[$i] = array($oldRegion[$i], $oldName[$i]);
			$arraySet[$i] = array($newRegion[$i], $newName[$i]);
			$where[$i] = $sql->where($columns, $arrayWhere[$i]);
			$set[$i] = $sql->setUpdate($columns, $arraySet[$i]);
		}
		for ($i = 0; $i < $size; $i++) {
			if ($oldRegion[$i] != $newRegion[$i] || $oldName[$i] != $newName[$i]) {
				$bool = $sql->updateValues($con, $table, $set[$i], $where[$i]);
				if ($bool == false) {
					break;
				}
			}
		}
		return $bool;
	}
	public function getSalesRepByName($con, $salesRepName = false)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id'";
		$where = "WHERE ( sr.name = \"" . $salesRepName . "\" )";
		$res = $sql->select($con, $columns, $table, false, $where);
		$from = array('id');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}
	public function getSalesRep($con, $salesRepGroupID = false)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				srg.ID AS 'salesRepGroupID',
				r.name AS 'region'";
		$where = "";
		if ($salesRepGroupID) {
			$ids = "";
			for ($i = 0; $i < sizeof($salesRepGroupID); $i++) {
				if ($i == 0) {
					$ids .= "'" . $salesRepGroupID[$i] . "'";
				} else {
					$ids .= ",'" . $salesRepGroupID[$i] . "'";
				}
			}
			$where .= "WHERE srg.ID IN ($ids)";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				 LEFT JOIN region r ON r.ID = srg.region_id";
		$order = "sr.name ASC";
		$res = $sql->larica($con, $columns, $table, $join, $where, $order);
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}

	public function getNewSalesRep($con, $salesRepName = false)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				srg.ID AS 'salesRepGroupID',
				r.name AS 'region'";
		$where = "WHERE ( sr.name = \"" . $salesRepName . "\" )";
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				 LEFT JOIN region r ON r.ID = srg.region_id";
		$order = "srg.ID,sr.name";
		$res = $sql->select($con, $columns, $table, $join, $where, $order);
		$from = array('salesRep', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}

	public function getSalesRepByRegionBV($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region',
				r.ID AS 'regionID',
				srs.status AS 'status'";
		$where = "";
		
		if ($region) {

			$where .= "WHERE r.ID IN (";
			for ($r = 0; $r < sizeof($region); $r++) {
				$where .= "\"" . $region[$r] . "\"";
				if ($r < sizeof($region) - 1) {
					$where .= ",";
				}
			}
			$where .= ")";
		}
		
		
		if ($notIN) {
			if (!$region) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			$where .= " ( srs.status != '0') AND (srs.year = '$year[0]')";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
				";
		$order = "sr.name ASC";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where, $order);
		//var_dump($sql->selectDistinct($con, $columns, $table, $join, $where, $order));
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		//var_dump($salesRep);
		return $salesRep;
	}

	public function getSalesRepPackets($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region',
				r.ID AS 'regionID',
				srs.status AS 'status'";
		$where = "";
		
		if ($region) {

			$where .= "WHERE r.ID IN (";
			for ($r = 0; $r < sizeof($region); $r++) {
				$where .= "\"" . $region[$r] . "\"";
				if ($r < sizeof($region) - 1) {
					$where .= ",";
				}
			}
			$where .= ")";
		}
		
		$where .= "AND ( srs.status != '0') AND (srs.year = '$year')";
		if ($notIN) {
			if (!$region) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			$where .= " ( srs.status != '0') AND (srs.year = '$year[0]')";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
				";
		$order = "sr.name ASC";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where, $order);
		//var_dump($sql->selectDistinct($con, $columns, $table, $join, $where, $order));
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		//var_dump($salesRep);
		return $salesRep;
	}

	public function getSecondRepPackets($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region',
				r.ID AS 'regionID',
				srs.status AS 'status'";
		$where = "";
		
		if ($region) {

			$where .= "WHERE r.ID IN (";
			for ($r = 0; $r < sizeof($region); $r++) {
				$where .= "\"" . $region[$r] . "\"";
				if ($r < sizeof($region) - 1) {
					$where .= ",";
				}
			}
			$where .= ")";
		}
		
		$where .= "AND ( srs.status != '0') AND (srs.year = '$year') AND (srg.ID = '5')";
		if ($notIN) {
			if (!$region) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			$where .= " ( srs.status != '0') AND (srs.year = '$year[0]') AND (srg.ID = '5')";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
				";
		$order = "sr.name ASC";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where, $order);
		//var_dump($sql->selectDistinct($con, $columns, $table, $join, $where, $order));
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		//var_dump($salesRep);
		return $salesRep;
	}
	public function getSalesRepByRegion($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region',
				r.ID AS 'regionID',
				srs.status AS 'status'";
		$where = "";
		
		if ($region) {

			$where .= "WHERE r.ID IN (";
			for ($r = 0; $r < sizeof($region); $r++) {
				$where .= "\"" . $region[$r] . "\"";
				if ($r < sizeof($region) - 1) {
					$where .= ",";
				}
			}
			$where .= ")";
		}
		
		
		if ($notIN) {
			if (!$region) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			$where .= " ( srs.status != '0') AND (srs.year = '$year')";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
				";
		$order = "sr.name ASC";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where, $order);
		//var_dump($sql->selectDistinct($con, $columns, $table, $join, $where, $order));
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		//var_dump($salesRep);
		return $salesRep;
	}

	public function getSalesRepRepresentativeByRegion($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep_representatives sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region',
				srs.status AS 'status'";
		$where = "";
		if ($region) {
			$ids = implode(",", $region);
			$where .= "WHERE r.ID IN ('$ids')";
		}
		if ($notIN) {
			if (!$region) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			$where .= " ( srs.status != '0') AND (srs.year = '$year')";
		}
		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep_status srs ON srs.sales_rep_representatives_id = sr.ID
				";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where);
		$from = array('id', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}

	public function getSalesRepUnitByRegion($con, $region = false, $notIN = false, $year)
	{
		$sql = new sql();
		$table = "sales_rep_unit sru";
		$columns = "sru.ID AS 'id',
				sru.name AS 'salesRepUnit',	
				r.name AS 'region'
				";
		$where = "";
		if ($region) {
			$ids = implode(",", $region);
			$where .= "WHERE r.ID IN ('$ids')";
		}

		//$where .= "AND (sr.ID = '9')";

		$where .= "AND (sru.name != 'N/A')";

		$join = "LEFT JOIN sales_rep sr ON sr.ID = sru.sales_rep_id
				 LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				 LEFT JOIN region r ON r.ID = srg.region_id
		        ";

		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'salesRepUnit', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}

	public function getSalesRepByRegionCMAPS($con, $region, $year)
	{

		$sql = new sql();
		$table = "cmaps c";
		$columns = "sr.ID AS 'salesRepID',
				    sr.name AS 'salesRep',	
				    srg.name AS 'salesRepGroup',
				    r.name AS 'region'
				    ";

		$where = "WHERE (r.ID = '$region') AND (year = '$year')";

		$join = "
				LEFT JOIN sales_rep sr ON sr.ID = c.sales_rep_id
		        LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
				";
		$res = $sql->selectDistinct($con, $columns, $table, $join, $where);
		$from = array('salesRepID', 'salesRep', 'salesRepGroup', 'region');
		$salesRep = $sql->fetch($res, $from, $from);
		return $salesRep;
	}


	public function addSalesRep($con)
	{
		$sql = new sql();
		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRep = Request::get('salesRep');
		$table = 'sales_rep';
		$columns = 'sales_group_id,name';
		$values = " '$salesRepGroupID','$salesRep' ";
		$bool = $sql->insert($con, $table, $columns, $values);
		return $bool;
	}
	public function editSalesRep($con)
	{
		$sql = new sql();
		$size = Request::get("size");
		$table = "sales_rep sr";
		$columns = array('sales_group_id', 'name');
		for ($i = 0; $i < $size; $i++) {
			$oldSalesGroup[$i] = Request::get("oldSalesGroup-$i");
			$newSalesGroup[$i] = Request::get("newSalesGroup-$i");

			$oldSalesRep[$i] = Request::get("oldSalesRep-$i");
			$newSalesRep[$i] = Request::get("newSalesRep-$i");
			$arrayWhere[$i] = array($oldSalesGroup[$i], $oldSalesRep[$i]);
			$arraySet[$i] = array($newSalesGroup[$i], $newSalesRep[$i]);
			$where[$i] = $sql->where($columns, $arrayWhere[$i]);
			$set[$i] = $sql->setUpdate($columns, $arraySet[$i]);
		}
		$bool = false;
		for ($i = 0; $i < $size; $i++) {
			if ($oldSalesGroup[$i] != $newSalesGroup[$i] || $oldSalesRep[$i] != $newSalesRep[$i]) {
				$bool = $sql->updateValues($con, $table, $set[$i], $where[$i]);
				if ($bool == false) {
					break;
				}
			}
		}
		return $bool;
	}

	public function getSalesRepUnit($con, $salesRepID = false)
	{
		$sql = new sql();
		$table = "sales_rep_unit sru";
		$columns = "sru.ID AS 'id',
				    sru.name AS 'salesRepUnit',
				    sr.name AS 'salesRep',
				    sr.ID AS 'salesRepID',
				    o.name AS 'origin',
				    srg.region_id AS 'regionID'";
		$where = "";
		if ($salesRepID) {
			$salesRepIDS = implode(",", $salesRepID);
			$where .= "WHERE sr.ID IN ('$salesRepIDS')";
		}
		$join = "LEFT JOIN sales_rep sr ON sr.ID = sru.sales_rep_id
				 LEFT JOIN origin o ON o.ID = sru.origin_id
				 LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id";
		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'salesRepUnit', 'salesRep', 'salesRepID', 'origin', 'regionID');
		$salesRepUnit = $sql->fetch($res, $from, $from);
		return $salesRepUnit;
	}

	public function getSalesRepUnitWithRepresentatives($con, $salesRepID = false)
	{

		$sql = new sql();
		$table = "sales_rep_unit sru";
		$columns = "sru.ID AS 'id',
				    sru.name AS 'salesRepUnit',
				    srr.name AS 'salesRepRepresentatives',
				    srr.ID AS 'salesRepRepresentativesID',				    
				    srg.region_id AS 'regionID'";

		$where = "";

		if ($salesRepID) {
			$salesRepIDS = implode(",", $salesRepID);
			$where .= "WHERE srr.ID IN ('$salesRepIDS')";
		}
		$join = "LEFT JOIN sales_rep_representatives srr ON srr.ID = sru.sales_rep_representatives_id
				 LEFT JOIN origin o ON o.ID = sru.origin_id
				 LEFT JOIN sales_rep_group srg ON srg.ID = srr.sales_group_id";
		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'salesRepUnit', 'salesRepRepresentatives', 'salesRepRepresentativesID', 'regionID');
		$salesRepUnitRepresentatives = $sql->fetch($res, $from, $from);
		return $salesRepUnitRepresentatives;
	}

	public function getSalesRepGroupingReps($con, $salesRepID = false)
	{
		$sql = new sql();
		$table = "sales_rep_grouping_reps srgr";
		$columns = "srgr.ID AS 'id',
				    srgr.name AS 'salesRepGroupingReps',
				    sr.name AS 'salesRep',
				    sr.ID AS 'salesRepID',				    
				    srg.region_id AS 'regionID'";
		$where = "";
		if ($salesRepID) {
			$salesRepIDS = implode(",", $salesRepID);
			$where .= "WHERE sr.ID IN ('$salesRepIDS')";
		}
		$join = "LEFT JOIN sales_rep sr ON sr.ID = srgr.sales_rep_id				 
				 LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id";
		$res = $sql->select($con, $columns, $table, $join, $where);
		$from = array('id', 'salesRepGroupingReps', 'salesRep', 'salesRepID', 'origin', 'regionID');
		$salesRepGroupingReps = $sql->fetch($res, $from, $from);
		return $salesRepGroupingReps;
	}

	public function getSalesRepByGroup($con, $salesRepGroupID, $year)
	{
		$sql = new sql();
		$query = "SELECT distinct sr.ID, sr.sales_group_id, sr.name
		FROM sales_rep sr 
		LEFT JOIN sales_rep_status srs ON sr.ID = srs.sales_rep_id 
		WHERE srs.status = 1 
		and (srs.year = $year)
		and (sr.sales_group_id = $salesRepGroupID) 
		ORDER BY sr.name ASC";
		//var_dump($query);
		$from = array('ID', 'sales_group_id', 'name');
		$res = $con->query($query);
		$salesRepByGroup =  $sql->fetch($res, $from, $from);
		return $salesRepByGroup;
	}

	public function getGroupIdByName($con,$group){
		$sql = new sql();

		for ($g=0; $g <sizeof($group); $g++) { 
			$select = "SELECT DISTINCT id,name
						FROM sales_rep_group sr
						WHERE sr.ab_name IN ('$group[$g]')
			";
		}
		$from = array('id','name');
		$result = $con->query($select);
		$groupId = $sql->fetch($result,$from,$from);
		
		return $groupId;

	}

	public function getSalesRepUnitByName($con, $salesRepUnit = false)
	{
		$sql = new sql();

		$table = "sales_rep_unit";

		$columns = "name AS 'salesRepUnit'";

		$where = "WHERE (name = \"" . $salesRepUnit . "\" )";

		$res = $sql->select($con, $columns, $table, false, $where);

		$from = array('salesRepUnit');

		$salesRepUnit = $sql->fetch($res, $from, $from);

		return $salesRepUnit;
	}


	public function addSalesRepUnit($con)
	{
		$sql = new sql();
		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRepID = Request::get('salesRep');
		$salesRepUnit = Request::get('salesRepUnit');
		$origin = Request::get('origin');
		$table = 'sales_rep_unit';
		$columns = 'sales_rep_id,origin_id,name';
		$values = " '$salesRepID','$origin','$salesRepUnit' ";
		$bool = $sql->insert($con, $table, $columns, $values);
		return $bool;
	}
	public function editSalesRepUnit($con)
	{
		$sql = new sql();
		$size = Request::get('size');
		$table = "sales_rep_unit";
		$columnsWhere = array("sales_rep_id", "name", "origin_id");
		$columnsSet = array("name", "origin_id");

		for ($i = 0; $i < $size; $i++) {

			$salesRep[$i] = Request::get("salesRep-$i");
			$oldSalesRepUnit[$i] = Request::get("oldSalesRepUnit-$i");
			$newSalesRepUnit[$i] = Request::get("newSalesRepUnit-$i");
			$oldOrigin[$i] = Request::get("oldOrigin-$i");
			$newOrigin[$i] = Request::get("newOrigin-$i");
			$arrayWhere[$i] = array($salesRep[$i], $oldSalesRepUnit[$i], $oldOrigin[$i]);
			$arraySet[$i] = array($newSalesRepUnit[$i], $newOrigin[$i]);
			$where[$i] = $sql->where($columnsWhere, $arrayWhere[$i]);
			$set[$i] = $sql->setUpdate($columnsSet, $arraySet[$i]);
		}
		$bool = false;
		for ($i = 0; $i < $size; $i++) {
			if ($oldSalesRepUnit[$i] != $newSalesRepUnit[$i] || $oldOrigin[$i] != $newOrigin[$i]) {
				$bool = $sql->updateValues($con, $table, $set[$i], $where[$i]);
				var_dump($bool);
				if ($bool == false) {
					break;
				}
			}
		}
		return $bool;
	}
	//comparando duas fontes para pegar os representantes
	/*$sql = new sql();
		$table = "sales_rep sr";
		$columns = "sr.name AS 'name',
					sr.ID as 'id',
					srg.name as 'salesRepGroup'";
		$firstTable = "ytd";
		$firstGross = "gross_revenue";
		if ($regionID == "1") {
			$secondTable = "cmaps";
			$secondGross = "gross";
		}else{
			$secondTable = "mini_header";
			$secondGross = "gross_revenue";
		}
	
		if ($salesRepGroupID[0] == 'all') {
            $regionsID = array($regionID);
            $reps = $this->getSalesRepByRegion($con,$regionsID);
        }else{
			$reps = $this->getSalesRep($con,$salesRepGroupID);
        }
        $add = "";
        if (date('m') == 01) {
        	$year = $year-1;
        	$add = "AND month = '12'";
        }
        for ($r=0; $r <sizeof($reps) ; $r++) {
        	$firstSelect[$r] = "SELECT SUM($firstGross) AS sum FROM $firstTable WHERE year = '$year' AND sales_rep_id = '".$reps[$r]['id']."' $add";
        	if ($regionID != "1") {
        		$firstSelect[$r] .= "AND campaign_sales_office_id = '$regionID'";
        	}
        	$firstResult[$r] = $con->query($firstSelect[$r]);
        	$results1[$r] = $sql->fetchSum($firstResult[$r],"sum")["sum"];
        	$secondSelect[$r] = "SELECT SUM($secondGross) AS sum FROM $secondTable WHERE year = '$year' AND sales_rep_id = '".$reps[$r]['id']."' $add";
        	$secondResult[$r] = $con->query($secondSelect[$r]);
        	$results2[$r] = $sql->fetchSum($secondResult[$r],"sum")["sum"];
        }
        for ($i=0; $i <sizeof($results1) ; $i++) { 
        	if ($results1[$i] == 0 || $results2[$i] == 0) {
        		unset($reps[$i]);
        	}
        }
        $reps = array_values($reps);
        return $reps;*/
}
