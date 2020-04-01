<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\base;

class insights extends Model{

	public function assemble($con,$sql,$client,$month,$brand,$salesRep,$currency){
		$base = new base();
		$mtx = $this->seek($con,$sql,$client,$month,$brand,$salesRep);
			for($m=0; $m < sizeof($mtx); $m++){
				if ($mtx[$m]['month']){
					$mtx[$m]['month'] = $base->intToMonth(array($mtx[$m]['month']))[0];
				}

				if ($mtx[$m]['grossRevenue'] || $mtx[$m]['grossRevenueP'] || $mtx[$m]['numSpot']) {
					if ($mtx[$m]['grossRevenue']){
						$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue']);
					}
					if ($mtx[$m]['grossRevenueP']) {
						$mtx[$m]['grossRevenueP'] = doubleval($mtx[$m]['grossRevenueP']);
					}
					if ($mtx[$m]['numSpot']) {
						$mtx[$m]['numSpot'] = doubleval($mtx[$m]['numSpot']);
					}
				}

				//$mtx[$m]['dateEvent'] = $base->formatData("aaaa-mm-dd","dd/mm/aaaa",$mtx[$m]['dateEvent']);

			}

		return $mtx;
	}


	public function seek($con,$sql,$client,$month,$brand,$salesRep){
		$base = new base();

		$brandString = $base->arrayToString($brand,false,0);
		$monthString = $base->arrayToString($month,false,false);
		$salesRepString = $base->arrayToString($salesRep,false,false);
		$clientString = $base->arrayToString($client,false,0);


		$sel = "SELECT 
					b.name AS 'brand',
					b.ID AS 'brandID',
					i.brand_feed AS 'brandFeed',
					sr.name AS 'salesRep',
					sr.ID AS 'salesRepID',
					a.name AS 'agency',
					c.name AS 'client',
					c.ID AS 'clientID',
					i.month AS 'month',
					i.year AS 'year',
					c.name AS 'currency',
					i.charge_type AS 'chargeType',
					i.product AS 'product',
					i.campaign AS 'campaign',
					ib.contract AS 'contract',
					i.order_reference AS 'orderReference',
					ib.program AS 'program',
					ib.spot_status AS 'spotStatus',
					ib.date_event AS 'dateEvent',
					ib.unit_start_time AS 'unitStartTime',
					ib.duration_impression AS 'durationImpression',
					i.clock_number AS 'clockNumber',
					ib.house_number AS 'houseNumber',
					ib.copy_title AS 'copyTitle',
					ib.spot_type AS 'spotType',
					i.num_spot AS 'numSpot',
					i.gross_revenue AS 'grossRevenue',
					i.gross_revenue_prate AS 'grossRevenueP',
					i.agency_commission_percentage AS 'agencyCommission'
					FROM insights i
					LEFT JOIN insights_bts ib ON i.campaign = ib.contract 
					LEFT JOIN brand b ON b.ID = i.brand_id
					LEFT JOIN sales_rep sr ON sr.ID = i.sales_rep_id
					LEFT JOIN agency a ON a.ID = i.agency_id
					LEFT JOIN client c ON c.ID = i.client_id
					WHERE (b.ID IN ($brandString))
					AND(i.month IN ($monthString))
					AND(sr.ID IN ($salesRepString))
					AND (c.ID IN ($clientString))

		";

		$res = $con->query($sel);

		$from = array(
				'brand',
				'brandFeed',
				'salesRep',
				'agency',
				'client',
				'month',
				'year',
				'currency',
				'chargeType',
				'product',
				'campaign',
				'contract',
				'orderReference',
				'program',
				'spotStatus',
				'dateEvent',
				'unitStartTime',
				'durationImpression',
				'clockNumber',
				'houseNumber',
				'copyTitle',
				'spotType',
				'numSpot',
				'grossRevenue',
				'grossRevenueP',
				'agencyCommission'
		);

		$mtx = $sql->fetch($res,$from,$from);

		return $mtx;

	}

	public function total($con,$sql,$client,$month,$brands,$salesRep,$currencies,$salesRegion){
		$p = new pRate();
		$base = new base();

		$brandString = $base->arrayToString($brands,false,0);

		$monthString = $base->arrayToString($month,false,false);
		
		$salesRepString = $base->arrayToString($salesRep,false,false);
		
		$clientString = $base->arrayToString($client,false,false);

		$year = date('Y');

		$from = array('averageNumSpot',
					  'sumGrossRevenue'
					);

		$selectTotal = "SELECT AVG(i.num_spot) AS 'averageNumSpot',
		 			    SUM(i.gross_revenue) AS 'sumGrossRevenue'
						FROM insights i
						LEFT JOIN brand b ON i.brand_id = b.ID
						LEFT JOIN sales_rep sr ON i.sales_rep_id = sr.ID
						LEFT JOIN client cl ON i.client_id = cl.ID
						WHERE (i.month IN ($monthString))
							AND (sr.ID IN ($salesRepString))
							AND (cl.ID IN ($clientString))
							AND (b.ID IN ($brandString))
						";
	

		$result = $con->query($selectTotal);

		$total = $sql->fetch($result,$from,$from);

		if ($currencies == 'USD'){
			$pRate = $p->getPRateByRegionAndYear($con,array($salesRegion),array($year));
		}else{
			$pRate = 1.0;
		}
		
		for ($t=0; $t <sizeof($total); $t++) {
			if ($total[$t]['sumGrossRevenue'] || $total[$t]['averageNumSpot']){
				
				if ($total[$t]['sumGrossRevenue']){
					$total[$t]['sumGrossRevenue'] = doubleval($total[$t]['sumGrossRevenue'])/$pRate;
				}
				if ($total[$t]['averageNumSpot']){
					$total[$t]['averageNumSpot'] = doubleval($total[$t]['averageNumSpot']);
				}
			}
		}

		return $total;

	}

}
