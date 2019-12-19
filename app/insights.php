<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;
use App\pRate;

class insights extends Model{
	
	public function assemble($client,$salesRep,$month,$value,$currency,$brand,$salesCurrency,$sql,$con){
		$base = new base();

		$brandString = $base->arrayToString($brand,false,0);
		$monthString = $base->arrayToString($month,false,false);
		$salesRepString = $base->arrayToString($salesRep,false,false);
		$clientString = $base->arrayToString($client,false,0);


		$from = array('salesRep',
					  'brand',
					  'agency',
					  'client',
					  'month',
					  'currency',
					  'charge_type',
					  'product',
					  'campaign',
					  'orderReference',
					  'scheduleEvent',
					  'spotStatus',
					  'dateEvent',
					  'unitStartTime',
					  'durationSpot',
					  'copyKey',
					  'mediaItem',
					  'spotType',
					  'durationImpression',
					  'grossRevenue',
					  'numSpot',
					  'netRevenue'
		);

		$select = "SELECT sr.name AS 'salesRep',
								  b.name AS 'brand',
								  i.brand_feed AS 'brandFeed',
								  a.name AS 'agency',
								  cl.name AS 'client',
								  i.month AS 'month',
								  c.name AS 'currency',
								  i.charge_type AS 'chargeType',
								  i.product AS 'product',
								  i.campaign AS 'campaign',
								  i.order_reference AS 'orderReference',
								  i.schedule_event AS 'scheduleEvent',
								  i.spot_status AS 'spotStatus',
								  i.date_event AS 'dateEvent',
								  i.unit_start_time AS 'unitStartTime',
								  i.duration_spot AS 'durationSpot',
								  i.copy_key AS 'copyKey',
								  i.media_item AS 'mediaItem',
								  i.spot_type AS 'spotYype',
								  i.duration_impression AS 'durationImpression',
								  i.gross_revenue AS 'grossRevenue',
								  i.num_spot AS 'numSpot',
								  i.net_revenue AS 'netRevenue'
						FROM insights i
						LEFT JOIN sales_rep sr ON sr.ID = i.sales_rep_id
						LEFT JOIN client cl ON cl.ID = i.client_id
						LEFT JOIN agency a ON a.ID = i.agency_id
						LEFT JOIN brand b ON b.ID = i.brand_id
						LEFT JOIN currency c ON c.ID = i.currency_id
						WHERE (i.brand_id IN ($brandString))
								AND (i.month IN ($monthString))
								AND (sr.ID IN ($salesRepString))
								AND (cl.ID IN ($clientString))
		";

		$result = $con->query($select);

		$mtx = $sql->fetch($result,$from,$from);

		var_dump($mtx);

		return $mtx;

	}
}
