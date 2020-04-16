<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pRate;
use App\base;

class insights extends Model{

	public function assemble($con,$sql,$client,$month,$brand,$salesRep,$currencies,$salesRegion){
		$base = new base();
		$p = new pRate();
		$mtx = $this->seek($con,$sql,$client,$month,$brand,$salesRep);

		$year = date('Y');
		
			if ($currencies == 'USD'){
				$pRate = $p->getPRateByRegionAndYear($con,array($salesRegion),array($year));
			}else{
				$pRate = 1.0;
			}
		
			for($m=0; $m < sizeof($mtx); $m++){
				//var_dump($mtx[$m]);
				if ($mtx[$m]['month']){
					$mtx[$m]['month'] = $base->intToMonth(array($mtx[$m]['month']))[0];
				}

				if ($mtx[$m]['grossRevenue'] || $mtx[$m]['netRevenue'] || $mtx[$m]['numSpot']) {
					if ($mtx[$m]['grossRevenue']){
						$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue'])/$pRate;
					}
					if ($mtx[$m]['netRevenue']) {
						$mtx[$m]['netRevenue'] = doubleval($mtx[$m]['netRevenue'])/$pRate;
					}
					if ($mtx[$m]['numSpot']) {
						$mtx[$m]['numSpot'] = doubleval($mtx[$m]['numSpot']);
					}
				}

				$mtx[$m]['dateEvent'] = $base->formatData("aaaa-mm-dd","dd/mm/aaaa",$mtx[$m]['dateEvent']);

				if ($mtx[$m]['scheduleEvent']) {
					$mtx[$m]['scheduleEvent'] = explode("-",$mtx[$m]['scheduleEvent']);
					$mtx[$m]['scheduleEvent'] = trim($mtx[$m]['scheduleEvent'][0]);


					//var_dump($mtx[$m]['scheduleEvent']);
					
				}
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
					cl.name AS 'client',
					cl.ID AS 'clientID',
					i.month AS 'month',
					i.year AS 'year',
					c.name AS 'currency',
					i.charge_type AS 'chargeType',
					i.product AS 'product',
					i.campaign AS 'campaign',
					i.order_reference AS 'orderReference',
					i.schedule_event AS 'scheduleEvent',
					i.spot_status AS 'spotStatus',
					i.date_event AS 'dateEvent',
					i.unit_start_time AS 'unitStartTime',
					i.duration_impression AS 'durationImpression',
					i.copy_key AS 'copyKey',
					i.media_item AS 'mediaItem',
					i.spot_type AS 'spotType',
					i.num_spot AS 'numSpot',
					i.gross_revenue AS 'grossRevenue',
					i.net_revenue AS 'netRevenue'
					FROM insights i
					LEFT JOIN brand b ON i.brand_id = b.ID
					LEFT JOIN sales_rep sr ON i.sales_rep_id = sr.ID
					LEFT JOIN client cl ON i.client_id = cl.ID
					left join currency c on i.currency_id = c.ID 
					LEFT JOIN agency a ON i.agency_id = a.ID
					WHERE (b.ID IN ($brandString))
					AND(i.month IN ($monthString))
					AND(sr.ID IN ($salesRepString))
					AND (cl.ID IN ($clientString))

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
				'orderReference',
				'scheduleEvent',
				'spotStatus',
				'dateEvent',
				'unitStartTime',
				'durationImpression',
				'copyKey',
				'mediaItem',
				'spotType',
				'numSpot',
				'grossRevenue',
				'netRevenue',
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
					  'sumGrossRevenue',
					  'sumNetRevenue'
					);

		$selectTotal = "SELECT AVG(i.num_spot) AS 'averageNumSpot',
		 			    SUM(i.gross_revenue) AS 'sumGrossRevenue',
		 			    SUM(i.net_revenue) AS 'sumNetRevenue'
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
			if ($total[$t]['sumNetRevenue'] || $total[$t]['sumGrossRevenue'] || $total[$t]['averageNumSpot']){
				
				if ($total[$t]['sumGrossRevenue']){
					$total[$t]['sumGrossRevenue'] = doubleval($total[$t]['sumGrossRevenue'])/$pRate;
				}
				if ($total[$t]['sumNetRevenue']){
					$total[$t]['sumNetRevenue'] = doubleval($total[$t]['sumNetRevenue'])/$pRate;
				}
				if ($total[$t]['averageNumSpot']){
					$total[$t]['averageNumSpot'] = doubleval($total[$t]['averageNumSpot']);
				}
			}
		}

		return $total;

	}

}
