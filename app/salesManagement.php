<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;


class salesManagement extends Model{

	public function customReportV1($con){
		$cYear = intval(date('Y'));
		$pYear = $cYear - 1;

		$sql = new sql();
		
		$bookings = $this->bookings($con,$sql,$cYear,$pYear);
		$targetGross = $this->targets($con,$sql,$cYear,$pYear,"GROSS");
		$targetNet = $this->targets($con,$sql,$cYear,$pYear,"Net");

		$rtr = array("bookings"=>$bookings,"targetGross"=>$targetGross,"targetNet"=>$targetNet);
		
		return $rtr;

	}

	public function base($con,$sql){
		$select = "SELECT
						sr.name AS 'salesRep',
						sr.ID AS 'salesRepID',
						r.ID AS 'regionID',
						r.name AS 'region'
						FROM sales_rep sr
						LEFT JOIN sales_rep_group srg ON sr.sales_group_id = srg.ID
						LEFT JOIN region r ON srg.region_id = r.ID
		          ";
		$res = $con->query($select);
		$from = array('salesRep','salesRepID','regionID','region');
		$array = $sql->fetch($res,$from,$from);
		return $array;
	}

	public function targets($con,$sql,$cYear,$pYear,$type){
		$select = "SELECT
						ps.month AS 'month', 
						ps.year AS 'year', 
						ps.value AS 'value', 
						r.ID AS 'regionID', 
						r.name AS 'region', 
						sr.ID AS 'salesRepID', 
						sr.name AS 'salesRep', 
						ps.type_of_revenue AS 'typeOfRevenue'
						FROM plan_by_sales ps
						LEFT JOIN sales_rep sr ON ps.sales_rep_id = sr.ID
						LEFT JOIN region r ON ps.region_id = r.ID
						WHERE ( year = '$cYear' OR year = '$pYear' )
						AND ( type_of_revenue = '".$type."')
						ORDER BY 4,6,2,1
		          ";
		$res = $con->query($select);
		$from = array('region','regionID','year','month','salesRep','salesRepID','value','typeOfRevenue');
		$array = $sql->fetch($res,$from,$from);

		return $array;
	}

	public function bookings($con,$sql,$cYear,$pYear){
		$select = "SELECT
						y.month AS 'month', 
						y.year AS 'year', 
						y.gross_revenue_prate AS 'bookingGross',
						y.net_revenue_prate AS 'bookingNet', 
						y.net_net_revenue_prate AS 'bookingNetNet', 
						r.ID AS 'regionID', 
						r.name AS 'region', 
						sr.ID AS 'salesRepID', 
						sr.name AS 'salesRep' 
						FROM ytd y
						LEFT JOIN sales_rep sr ON y.sales_rep_id = sr.ID
						LEFT JOIN region r ON y.sales_representant_office_id = r.ID
						WHERE ( year = '$cYear' OR year = '$pYear' )
						ORDER BY 6,8,2,1
		          ";
		$res = $con->query($select);
		$from = array('region','regionID','year','month','salesRep','salesRepID','bookingGross','bookingNet'/*,'bookingNetNet'*/);
		$array = $sql->fetch($res,$from,$from);

		return $array;
	}

}
