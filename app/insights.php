<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;
use App\pRate;

class insights extends Model{
	
	public function getTables($client,$salesRep,$month,$value,$currency,$brand,$salesCurrency){

		$from = array('salesRep',
					  'brand',
					  'agency',
					  'client',
					  'month',
					  'currency',
					  'charge_type',
					  'product',
					  'campaign',
					  'order_reference',
					  'schedule_event',
					  'spot_status',
					  'date_event',
					  'unit_start_time',
					  'duration_spot',
					  'copy_key',
					  'media_item',
					  'spot_type',
					  'duration_impression',
					  'gross_revenue',
					  'num_spot',
					  'net_revenue'
		);

		/*$select = "SELECT sr.name AS 'salesRep',
								  b.name AS 'brand',
								  i.brand_feed AS 'brand_feed',
								  a.name AS 'agency',
								  cl.name AS 'client',
								  i.month AS 'month',
								  c.name AS 'currency',
								  i.charge_type AS 'charge_type',
								  i.product AS 'product',
								  i.campaign AS 'campaign',
								  i.order_reference AS 'order_reference',
								  i.schedule_event AS 'schedule_event',
								  i.spot_status AS 'spot_status',
								  i.date_event AS 'date_event',
								  i.unit_start_time AS 'unit_start_time',
								  i.duration_spot AS 'duration_spot',
								  i.copy_key AS 'copy_key',
								  i.media_item AS 'media_item',
								  i.spot_type AS 'spot_type',
								  i.duration_impression AS 'duration_impression',
								  i.gross_revenue AS 'gross_revenue',
								  i.num_spot AS 'num_spot',
								  i.net_revenue AS 'net_revenue'
						FROM insights i
						LEFT JOIN sales_rep sr ON sr.ID = i.sales_rep_id
						LEFT JOIN client cl ON cl.ID = i.client_id
						LEFT JOIN agency a ON a.ID = i.agency_id
						LEFT JOIN brand b ON b.ID = i.brand_id
						LEFT JOIN currency c ON c.ID = i.currency_id

		";*/
	}
}
