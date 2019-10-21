<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\base;
class viewer extends Model{


	public function getTables($con,$salesRegion,$source,$month,$brand,$value,$year,$salesCurrency,$salesRep,$db,$sql){
		$base = new base();
/*
		if ($source == "sf") {
			$columns = array('brand','year_from','year_to','from_date','to_date','sales_rep_owner_id','sales_rep_splitter_id');
			$variables = array(array($brand),$year,$year,array($month),array($month),array($salesRep),array($salesRep));
		}else{
			$columns = array('brand_id','year','month','sales_rep_id');
			$variables = array( array($brand),$year,array($month),array($salesRep));
			$where = "WHERE  $columns IN $variables";
		}		
*/

		$brandString = $base->arrayToString($brand,true,0);

		$monthString = $base->arrayToString($month,false,false);

		if ($source == "CMAPS"){
			$select = "SELECT sr.name AS 'salesRep', 
			                  c.pi_number AS 'piNumber', 
			                  c.month AS 'month',
			                  c.map_number AS 'mapNumber',
			                  c.product AS 'product',
			                  c.segment AS 'segment',
			                  c.market AS 'market',
			                  c.media_type AS 'mediaType', 
			                  b.name AS 'brand',
			                  a.name AS 'agency',
			                  cl.name AS 'client',
			                  c.log AS 'log',
			                  c.ad_sales_support AS 'adSalesRupport',
			                  c.category AS 'category',
			                  c.sector AS 'sector', 
			                  c.".$value." AS revenue
						FROM cmaps c
						LEFT JOIN sales_rep sr ON sr.ID = c.sales_rep_id
						LEFT JOIN brand b ON b.ID = c.brand_id
						LEFT JOIN agency a ON c.agency_id = a.ID
						LEFT JOIN client cl ON c.client_id = cl.ID
						WHERE (c.brand_id IN ('$brandString')) 
								AND (c.year = '$year') 
								AND (c.month IN ('$monthString'))
						GROUP BY c.pi_number
						ORDER BY c.month";

		}elseif ($source == "IBMS/BTS"){
			$select = "SELECT sr.name AS 'salesRepName', 
			                  y.order_reference AS 'orderReference',
			                  y.month AS 'month', 
			                  y.campaign_reference AS 'campaignReference',
			                  b.name AS 'brand', 
			                  a.name AS 'agency',
			                  cl.name AS 'client',
			                  y.brand_feed AS 'feed',
			                  y.client_product AS 'clientProduct',
			                  y.spot_duration AS 'spotDuration',
			                  y.num_spot AS 'numSpot', 
			                  y.impression_duration AS 'impression_duration', 
			                  y.".$value."_revenue AS 'revenue'
						FROM ytd y 
						LEFT JOIN sales_rep sr ON sr.ID = y.sales_rep_id
						LEFT JOIN brand b ON b.ID = y.brand_id
						LEFT JOIN agency a ON y.agency_id = a.ID
						LEFT JOIN client cl ON y.client_id = cl.ID
						LEFT JOIN currency c ON y.sales_representant_office_id = c.ID
						WHERE (y.brand_id IN ('$brandString'))
								AND (y.year = '$year')
								AND (y.month IN ('$monthString'))
						GROUP BY y.order_reference
						ORDER BY y.month";
			
			
		}elseif ($source == "FW"){
			$select = "SELECT sr.name,cl.name AS 'client',
							  a.name AS 'agency',
							  f.insertion_order,
							  f.month, 
							  b.name AS 'brand',
							  f.placement,
							  f.campaign,
							  r.name AS 'region',
							  f.io_start_date,
							  f.io_end_date,
							  f.buy_type,
							  f.ad_unit,
							  f.".$value."_revenue,
							  f.commission,
							  f.insertion_order_id,
							  f.rep_commission_percentage, 
							  f.agency_commission_percentage
						FROM fw_digital f
						LEFT JOIN sales_rep sr ON sr.ID = f.sales_rep_id
						LEFT JOIN brand b  ON b.ID = f.brand_id
						LEFT JOIN agency a ON a.ID = f.agency_id
						LEFT JOIN client cl ON cl.ID = f.client_id
						LEFT JOIN region r ON r.ID = f.region_id
						LEFT JOIN currency c ON c.ID = f.currency_id
						WHERE (f.brand_id IN ('$brand'))
								AND (f.year = '$year')
								AND (f.month IN ('$month'))
						GROUP BY f.insertion_order_id
						ORDER BY f.month";

		}elseif ($source == "SF"){
			$select ="SELECT  sf.oppid,
							  sf.sales_rep_owner_id,
							  sf.sales_rep_splitter_id,
							  sf.is_split,a.name AS 'agency',
							  c.name AS 'client',
							  sf.opportunity_name,
							  sf.stage,
							  sf.fcst_category,
							  sf.success_probability,
							  sf.from_date,
							  sf.to_date,
							  sf.year_from,
							  sf.year_to,
							  sf.brand,
							  sf.".$value."_revenue+sf.fcst_amount_".$value.",
							  sf.agency_commission
					FROM sf_pr sf
					LEFT JOIN sales_rep sr ON sr.ID = sf.sales_rep_owner_id
						AND sr.ID = sf.sales_rep_splitter_id
					LEFT JOIN region r ON sf.region_id = r.ID
					LEFT JOIN agency a ON sf.agency_id = a.ID
					LEFT JOIN client c ON sf.client_id = c.ID
					WHERE (sf.year_from = '$year')
						AND (sf.year_to = '$year')
					GROUP BY sf.oppid";
		}
		if(isset($select)){
			echo "<pre>".($select)."</pre>";

			$result = $con->query($select);
		}

	}

}
