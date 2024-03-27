<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;
use App\pRate;

class viewer extends Model{

	public function getTables($con,$source,$month,$company,$year,$salesCurrency,$salesRep,$db,$sql,$agency,$client,$checkClient=null,$manager,$platform){
		$base = new base();
		
		//var_dump($manager);
			
		$from = array('company','year','month','oldRep', 'client','agency','brand','manager','salesRep','feedType','feedCode','internalCode', 'piNumber', 'property', 'grossRevenue','netRevenue');

		$select = "SELECT bg.abv AS 'company',
						  w.year AS 'year',
						  w.month AS 'month',
						  w.old_sales_rep AS 'oldRep',
						  c.name AS 'client',
						  a.name AS 'agency',
						  b.name AS 'brand',
						  w.manager AS 'manager',
						  sr.name AS 'salesRep',
						  w.feed_type as 'feedType',
						  w.feed_code as 'feedCode',
						  w.internal_code as 'internalCode',
						  w.pi_number as 'piNumber',
						  w.property as 'property',
						  w.gross_value AS 'grossRevenue',
						  w.net_value AS 'netRevenue'
				   FROM wbd w
				   LEFT JOIN client c ON w.client_id = c.ID
				   LEFT JOIN agency a ON w.agency_id = a.ID
				   LEFT JOIN brand b ON w.brand_id = b.ID
				   LEFT JOIN brand_group bg ON w.company_id = bg.ID
				   LEFT JOIN sales_rep sr ON w.current_sales_rep_id = sr.ID
				   WHERE (w.year = '$year')
						AND (w.company_id IN ($company))
						AND (sr.ID IN ($salesRep)) 
						AND (w.manager IN ($manager)) 
						AND (w.month IN ($month))
						AND (a.ID IN ($agency))
						AND ( c.ID IN ($client) )
						AND (w.feed_type IN ($platform))
					";
		//echo "<pre>".$select."</pre>";
		
		$result = $con->query($select);
		//echo "$result";

		$mtx = $sql->fetch($result,$from,$from);
		//var_dump(sizeof($mtx));
		return $mtx;
	}
	

	public function totalFromTable($con,$table,$source,$salesRegion,$currencies){
		$p = new pRate();
		$year = date('Y');
		$discount = 0.0;
		$net = 0.0;
		$gross = 0.0;

		$c = 1;
		if ($table) {
			//var_dump(sizeof($table));
			for ($t=0; $t < sizeof($table); $t++){ 
				
				if($source == "WBD"){
					$gross += $table[$t]['grossRevenue'];
					$net += $table[$t]['netRevenue'];
				}

				$c++;
			}
			//var_dump($table);

			$sumGrossRevenue = $gross;
			$sumNetRevenue = $net;			
			$averageDiscount = $discount/$c;
			
		}else{
			$sumGrossRevenue = $gross;
			$sumNetRevenue = $net;			
			$averageDiscount = $discount/$c;
		}
		

		$return = array('averageDiscount' => $averageDiscount, 'sumNetRevenue' => $sumNetRevenue, 'sumGrossRevenue' => $sumGrossRevenue );
		
		return $return;
	}



	public function assemble($mtx,$salesCurrency,$source,$con,$salesRegion,$currencies){

		$base = new base();
		$p = new pRate();
		
		$year = date('Y');

		$pRate = 1.0;

		//var_dump($mtx);

		if($mtx){

			for ($m=0; $m <sizeof($mtx); $m++) { 		

				if ($mtx[$m]['month']){
					$mtx[$m]['month'] = $base->intToMonth(array($mtx[$m]['month']))[0];
				}

				if ($mtx[$m]['netRevenue']) {
					$mtx[$m]['netRevenue'] = doubleval($mtx[$m]['netRevenue']);
				}

				if ($mtx[$m]['grossRevenue']) {
					$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue']);
				}	
			}

		}else{
			return false;
		}
		return $mtx;
	}

}
