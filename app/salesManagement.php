<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;


class salesManagement extends Model{

	public function customReportV1($con){
		$cYear = intval(date('Y'));
		$pYear = $cYear - 1;

		$sql = new sql();
		
		$salesRep = $this->base($con,$sql);

		$booking = $this->bookings($con,$sql,$cYear,$pYear);


		$temp = $this->assemblyPlanBySales($con,$sql,$cYear,$pYear,$salesRep);
		
		$temp2 = $this->joinBookings($con,$sql,$booking);

		//var_dump($booking);

		$mtx = $this->assembly($temp2);

		return $temp2;

	}

	public function customReportTarget($con){
		$cYear = intval(date('Y'));
		$pYear = $cYear - 1;

		$sql = new sql();
		
		$salesRep = $this->base($con,$sql);

		$booking = $this->bookings($con,$sql,$cYear,$pYear);


		$temp = $this->assemblyPlanBySales($con,$sql,$cYear,$pYear,$salesRep);
		
		$temp2 = $this->joinBookingsWithTarget($con,$sql,$temp);

		//var_dump($booking);

		$mtx = $this->assembly($temp2);

		return $temp2;

	}

	public function assemblyT($mtx){

		//$hour = "H:i:s";

		for ($m=0; $m < sizeof($mtx); $m++) { 			
			if($mtx[$m]){
				for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
					$temp[$m][$n]['region'] = $mtx[$m][$n]['region'];
					$temp[$m][$n]['salesRep'] = $mtx[$m][$n]['salesRep'];
					$temp[$m][$n]['salesRepSfID'] = $mtx[$m][$n]['salesRepSfID'];
					$temp[$m][$n]['currency'] = 'USD';				
					if($mtx[$m][$n]['month'] < 10){
						$month = "0".$mtx[$m][$n]['month'];
					}else{
						$month = $mtx[$m][$n]['month'];
					}
					$temp[$m][$n]['date'] = $month."/"."01/".date('Y');
					$temp[$m][$n]['month'] = $mtx[$m][$n]['month'];
					$temp[$m][$n]['brand'] = $mtx[$m][$n]['brand'];
					$temp[$m][$n]['targetValue'] = $mtx[$m][$n]['targetValue'];
					$temp[$m][$n]['bookingsNetCurrentYear'] = $mtx[$m][$n]['bookingsNetCurrentYear'];
					$temp[$m][$n]['bookingsNetPreviousYear'] = $mtx[$m][$n]['bookingsNetPreviousYear'];
				}
			}else{
				$temp[$m] = false;
			}
			//var_dump($temp);
			return $temp;
		}
		
	}


	public function assembly($mtx){

		//$hour = "H:i:s";

		for ($m=0; $m < sizeof($mtx); $m++) { 			
			if($mtx[$m]){
				for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
					$temp[$m][$n]['region'] = $mtx[$m][$n]['region'];
					$temp[$m][$n]['salesRep'] = $mtx[$m][$n]['salesRep'];
					$temp[$m][$n]['salesRepSfID'] = $mtx[$m][$n]['salesRepSfID'];
					$temp[$m][$n]['currency'] = 'USD';				
					if($mtx[$m][$n]['month'] < 10){
						$month = "0".$mtx[$m][$n]['month'];
					}else{
						$month = $mtx[$m][$n]['month'];
					}
					$temp[$m][$n]['date'] = $month."/"."01/".date('Y');
					$temp[$m][$n]['month'] = $mtx[$m][$n]['month'];
					$temp[$m][$n]['brand'] = $mtx[$m][$n]['brand'];
					$temp[$m][$n]['targetValue'] = $mtx[$m][$n]['targetValue'];
					$temp[$m][$n]['bookingsNetCurrentYear'] = $mtx[$m][$n]['bookingsNetCurrentYear'];
					$temp[$m][$n]['bookingsNetPreviousYear'] = $mtx[$m][$n]['bookingsNetPreviousYear'];
				}
			}else{
				$temp[$m] = false;
			}
			//var_dump($temp);
			return $temp;
		}
		
	}

	public function joinBookings($con,$sql,$mtx){

		$tmp = $mtx;
		//var_dump($mtx);

		for ($m=0; $m < sizeof($mtx); $m++) { 
			if($mtx[$m]){
				//for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
					//var_dump($mtx[$m]);
					$select[$m] = "SELECT SUM(y.net_revenue_prate) AS 'netRevenue',
									   c.name AS 'client',
									   c.ID AS 'clientId',
									   a.name AS 'agency',
									   a.ID as 'agencyId'
									   FROM	ytd y
									   LEFT JOIN agency a ON y.agency_id = a.ID
									   LEFT JOIN client c ON y.client_id = c.ID									   
									   WHERE (y.sales_representant_office_id = '4' )
									   AND (y.sales_rep_id = '".$mtx[$m]['salesRepID']."')									   
									   AND (y.year = '".$mtx[$m]['year']."')									   
									   AND (y.month = '".$mtx[$m]['month']."')									   
									   AND (y.brand_id = '".$mtx[$m]['brandID']."')									   
						              ";

					//echo "<pre>".$select[$m]."</pre>";
					$res[$m] = $con->query($select[$m]);
					$from = array('netRevenue', 'client', 'clientId', 'agency', 'agencyId');					
					$array[$m] = $sql->fetch($res[$m],$from,$from)[0];
					//var_dump($array[$m]['netRevenue']);
					$tmp[$m]['bookingsNetCurrentYear'] = $array[$m]['netRevenue'];
					$tmp[$m]['agency'] = $array[$m]['agency'];
					$tmp[$m]['agencyId'] = $array[$m]['agencyId'];
					$tmp[$m]['client'] = $array[$m]['client'];
					$tmp[$m]['clientId'] = $array[$m]['clientId'];

					$select2[$m] = "SELECT SUM(y.net_revenue_prate) AS 'netRevenue',
									    c.name AS 'client',
										c.ID AS 'clientId',
										a.name AS 'agency',
										a.ID as 'agencyId'
									    FROM	ytd y
									    LEFT JOIN agency a ON y.agency_id = a.ID
									    LEFT JOIN client c ON y.client_id = c.ID									   
									    WHERE (y.sales_representant_office_id = '4')
									    AND (y.sales_rep_id = '".$mtx[$m]['salesRepID']."')									   
									    AND (y.year = '".($mtx[$m]['year']-1)."')									   
									    AND (y.month = '".$mtx[$m]['month']."')									   
									    AND (y.brand_id = '".$mtx[$m]['brandID']."')									   
						              ";

					//echo "<pre>".$select[$m]."</pre>";
					$res2[$m] = $con->query($select2[$m]);
					$from = array('netRevenue', 'client', 'clientId', 'agency', 'agencyId');					
					$array2[$m] = $sql->fetch($res2[$m],$from,$from)[0];
					//var_dump($array[$m]['netRevenue']);
					$tmp[$m]['bookingsNetPreviousYear'] = $array2[$m]['netRevenue'];
					$tmp[$m]['agency'] = $array2[$m]['agency'];
					$tmp[$m]['agencyId'] = $array2[$m]['agencyId'];
					$tmp[$m]['client'] = $array2[$m]['client'];
					$tmp[$m]['clientId'] = $array2[$m]['clientId'];

				//}
			}			
		}

		return $tmp;


	}

	public function joinBookingsWithTarget($con,$sql,$mtx){

		$tmp = $mtx;
		//var_dump($mtx);

		for ($m=0; $m < sizeof($mtx); $m++) { 
			if($mtx[$m]){
				for ($n=0; $n < sizeof($mtx[$m]); $n++) { 
					//var_dump($mtx[$m]);
					$select[$m] = "SELECT SUM(y.net_revenue_prate) AS 'netRevenue',
									   FROM	ytd y
									   LEFT JOIN agency a ON y.agency_id = a.ID
									   LEFT JOIN client c ON y.client_id = c.ID									   
									   WHERE (y.sales_representant_office_id = '4')
									   AND (y.sales_rep_id = '".$mtx[$m]['salesRepID']."')									   
									   AND (y.year = '".$mtx[$m]['year']."')									   
									   AND (y.month = '".$mtx[$m]['month']."')									   
									   AND (y.brand_id = '".$mtx[$m]['brandID']."')									   
						              ";

					//echo "<pre>".$select[$m]."</pre>";
					$res[$m] = $con->query($select[$m]);
					$from = array('netRevenue');					
					$array[$m] = $sql->fetch($res[$m],$from,$from)[0];
					//var_dump($array[$m]['netRevenue']);
					$tmp[$m]['bookingsNetCurrentYear'] = $array[$m]['netRevenue'];


					$select2[$m] = "SELECT SUM(y.net_revenue_prate) AS 'netRevenue',
									    FROM	ytd y
									    LEFT JOIN agency a ON y.agency_id = a.ID
									    LEFT JOIN client c ON y.client_id = c.ID									   
									    WHERE (y.sales_representant_office_id = '4')
									    AND (y.sales_rep_id = '".$mtx[$m]['salesRepID']."')									   
									    AND (y.year = '".($mtx[$m]['year']-1)."')									   
									    AND (y.month = '".$mtx[$m]['month']."')									   
									    AND (y.brand_id = '".$mtx[$m]['brandID']."')									   
						              ";

					//echo "<pre>".$select[$m]."</pre>";
					$res2[$m] = $con->query($select2[$m]);
					$from = array('netRevenue');					
					$array2[$m] = $sql->fetch($res2[$m],$from,$from)[0];
					//var_dump($array[$m]['netRevenue']);
					$tmp[$m]['bookingsNetPreviousYear'] = $array2[$m]['netRevenue'];

				}
			}			
		}

		return $tmp;


	}

	public function assemblyPlanBySales($con,$sql,$cYear,$pYear,$salesRep){

		for ($s=0; $s < sizeof($salesRep); $s++) { 			

			$select[$s] = "SELECT DISTINCT
								pbs.month AS 'month', 
								pbs.year AS 'year', 
								pbs.value AS 'value', 
								r.ID AS 'regionID', 
								r.name AS 'region', 
								sr.ID AS 'salesRepID', 
								sr.name AS 'salesRep',
								sr.sf_id AS 'salesRepSfID', 								
								b.name AS 'brand',
								b.ID AS 'brandID'
						   FROM	plan_by_sales pbs						   
						   LEFT JOIN brand b ON b.ID = pbs.brand_id 
						   LEFT JOIN sales_rep sr ON sr.ID = pbs.sales_rep_id
						   LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id 					   
						   LEFT JOIN region r ON srg.region_id = r.ID
						   WHERE (pbs.region_id = '4')
						   AND (pbs.sales_rep_id = '".$salesRep[$s]['salesRepID']."')
						   AND(pbs.currency_id = '4')
						   AND (pbs.year = '".$cYear."')
						   AND (pbs.type_of_revenue = 'NET')						   
			              ";	

			//echo "<pre>$select[$s]</pre>";	
		    $res[$s] = $con->query($select[$s]);
			$from = array('regionID','region','salesRepID','salesRep','salesRepSfID','year','month','brandID','brand','value');
			$to = array('regionID','region','salesRepID','salesRep','salesRepSfID','year','month','brandID','brand','targetValue');
			$array[$s] = $sql->fetch($res[$s],$from,$to);			
		}

		return $array;


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
						ORDER BY 2,3
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
						sr.name AS 'salesRep',
						sr.sf_id AS 'salesRepSfID', 
						c.name AS 'client',
						c.ID AS 'clientId',
						a.name AS 'agency',
						a.ID as 'agencyId',
						b.ID AS 'brandID'
						FROM ytd y
						LEFT JOIN sales_rep sr ON y.sales_rep_id = sr.ID
						LEFT JOIN agency a ON y.agency_id = a.ID
						LEFT JOIN client c ON y.client_id = c.ID
						LEFT JOIN region r ON y.sales_representant_office_id = r.ID
						LEFT JOIN brand b ON y.brand_id = b.ID
						WHERE ( year = '$cYear' OR year = '$pYear' )
						ORDER BY 6,8,2,1
		          ";
		          //echo "<pre>$select</pre>";	
		$res = $con->query($select);
		$from = array('region','regionID','year','month','salesRep','salesRepID','bookingGross','bookingNet', 'client', 'clientId', 'agency', 'agencyId','salesRepSfID','brandID'/*,'bookingNetNet'*/);
		$array = $sql->fetch($res,$from,$from);

		return $array;
	}

}
