<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\base;

class insights extends Model{

	public function assemble($con,$sql,$client,$month,$brand,$salesRep,$currency,$value){

		$mtx = $this->seek($con,$sql,$client,$month,$brand,$salesRep);

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
					i.spot_type AS 'spotType',
					i.duration_impression AS 'durationImpression',
					i.gross_revenue AS 'grossRevenue',
					i.num_spot AS 'numSpot',
					i.net_revenue AS 'netRevenue'
					FROM insights i
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
				'currency',
				'chargeType',
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

		$mtx = $sql->fetch($res,$from,$from);

		return $mtx;

	}
}
