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


		if ($source == "CMAPS"/*'cmaps'*/){
			$from = array(
						'year',
						'month',
						'brand',
						'agency',
		                'client',
		                'salesRep', 
		                'piNumber',		                
		                'mapNumber',
		                'product',
		                'segment',
		                'market',
		                'mediaType', 
		                'log',
		                'adSalesRupport',
		                'category',
		                'sector',                 
		                'package',
		                'discount',
		                'clientCnpj',
		                'agencyCnpj',
		                'grossRevenue',
		                'netRevenue'

			);


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
			                  c.gross AS 'grossRevenue',
			                  c.net AS 'netRevenue',
			                  c.year AS 'year',
			                  c.package AS 'package',
			                  c.discount AS 'discount',
			                  c.client_cnpj AS 'clientCnpj',
			                  c.agency_cnpj AS 'agencyCnpj'
						FROM cmaps c
						LEFT JOIN sales_rep sr ON sr.ID = c.sales_rep_id
						LEFT JOIN brand b ON b.ID = c.brand_id
						LEFT JOIN agency a ON c.agency_id = a.ID
						LEFT JOIN client cl ON c.client_id = cl.ID
						WHERE (c.brand_id IN ('$brandString')) 
								AND (c.year = '$year') 
								AND (c.month IN ('$monthString'))
						ORDER BY c.month";

		}elseif ($source == "IBMS/BTS"/*'ibms/bts'*/){
			$from = array(
						  'region',
        				  'year',
        				  'month',
        				  'brand', 
        				  'agency',
                          'client',
						  'salesRepName',
				          'orderReference',
         				  'campaignReference',
                          'brandFeed',
                          'clientProduct',
                          'spotDuration',
                          'numSpot', 
                          'impressionDuration', 
        				  'grossRevenue',
        				  'netRevenue',
);


			$select = "SELECT sr.name AS 'salesRepName', 
			                  y.order_reference AS 'orderReference',
			                  y.month AS 'month', 
			                  y.campaign_reference AS 'campaignReference',
			                  b.name AS 'brand', 
			                  a.name AS 'agency',
			                  cl.name AS 'client',
			                  y.brand_feed AS 'brandFeed',
			                  y.client_product AS 'clientProduct',
			                  y.spot_duration AS 'spotDuration',
			                  y.num_spot AS 'numSpot', 
			                  y.impression_duration AS 'impressionDuration', 
			                  r.name AS 'region',
			                  y.year AS 'year',
			                  y.gross_revenue AS 'grossRevenue',
			                  y.net_revenue AS 'netRevenue'

						FROM ytd y 
						LEFT JOIN sales_rep sr ON sr.ID = y.sales_rep_id
						LEFT JOIN brand b ON b.ID = y.brand_id
						LEFT JOIN agency a ON y.agency_id = a.ID
						LEFT JOIN client cl ON y.client_id = cl.ID
						LEFT JOIN region r ON r.ID = y.sales_representant_office_id
						LEFT JOIN currency c ON y.sales_representant_office_id = c.ID
						WHERE (y.brand_id IN ('$brandString'))
								AND (y.year = '$year')
								AND (y.month IN ('$monthString'))
						ORDER BY y.month";			
			

		}elseif ($source == "FW"/*'fw'*/){
			$from = array(
						  'region',
						  'year',
						  'month', 
						  'brand',
						  'client',
			              'agency',
			              'salesRep',
			              'insertionOrder',
			              'placement',
			              'campaign',			              
			              'ioStartDate',
			              'ioEndDate',
			              'buyType',
			              'adUnit',
			              'commission',
			              'insertionOrderId',
			              'repCommissionPercentage',
			              'agencyCommissionPercentage',
			              'grossRevenue',
			   	       	  'netRevenue'			   	       	  
			);

			$select = "SELECT sr.name AS 'salesRep',
							  cl.name AS 'client',
			                  a.name AS 'agency',
			                  f.insertion_order AS 'insertionOrder',
			                  f.month AS 'month', 
			                  b.name AS 'brand',
			                  f.placement AS 'placement',
			                  f.campaign AS 'campaign',
			                  r.name AS 'region',
			                  f.io_start_date AS 'ioStartDate',
			                  f.io_end_date AS 'ioEndDate',
			                  f.buy_type AS 'buyType',
			                  f.ad_unit AS 'adUnit',
			                  f.commission AS 'commission',
			                  f.insertion_order_id AS 'insertionOrderId',
			                  f.rep_commission_percentage AS 'repCommissionPercentage',
			                  f.agency_commission_percentage AS 'agencyCommissionPercentage',
			                  f.gross_revenue AS 'grossRevenue',
			                  f.net_revenue AS 'netRevenue',
			                  f.year AS 'year'       
						FROM fw_digital f
						LEFT JOIN sales_rep sr ON sr.ID = f.sales_rep_id
						LEFT JOIN brand b  ON b.ID = f.brand_id
						LEFT JOIN agency a ON a.ID = f.agency_id
						LEFT JOIN client cl ON cl.ID = f.client_id
						LEFT JOIN region r ON r.ID = f.region_id
						LEFT JOIN currency c ON c.ID = f.currency_id
						WHERE (f.brand_id IN ('$brandString'))
								AND (f.year = '$year')
								AND (f.month IN ('$monthString'))
						ORDER BY f.month";

		}elseif ($source == "SF"/*"sf"*/){
			$from = array(
							  'oppid',
							  'region',
							  'yearFrom',
			                  'yearTo', 
			                  'fromDate',
			                  'toDate',
			                  'brand',
							  'agency',
			                  'client',
			                  'opportunityName', 
			                  'stage',
			                  'fcstCategory',			                  
			                  'salesRepOwner',
			                  'salesRepSplitter',
			                  'success_probability',                 
			                  'agencyCommission',
			                  'fcstAmountGross', 
			                  'fcstAmountNet',
			                  'netRevenue',			                  
			                  'grossRevenue'
			);

			$select ="SELECT  sf.oppid AS 'oppid',
			                  sr.name AS 'salesRepOwner',
			                  s.name AS 'salesRepSplitter',
			                  a.name AS 'agency',
			                  c.name AS 'client',
			                  sf.opportunity_name AS 'opportunityName', 
			                  sf.stage AS 'stage',
			                  sf.fcst_category AS 'fcstCategory',
			                  sf.success_probability AS 'success_probability',
			                  sf.from_date AS 'fromDate',
			                  sf.to_date AS 'toDate',
			                  sf.year_from AS 'yearFrom',
			                  sf.year_to AS 'yearTo', 
			                  sf.brand AS 'brand',
			                  sf.agency_commission AS 'agencyCommission',
			                  sf.fcst_amount_gross AS 'fcstAmountGross', 			                  
			                  sf.gross_revenue AS 'grossRevenue',
			                  sf.net_revenue AS 'netRevenue',
			                  sf.fcst_amount_net AS 'fcstAmountNet',
			                  r.name AS 'region'
					FROM sf_pr sf
					LEFT JOIN sales_rep sr ON sr.ID = sf.sales_rep_owner_id
					LEFT JOIN sales_rep s ON s.ID = sf.sales_rep_splitter_id
					LEFT JOIN region r ON sf.region_id = r.ID
					LEFT JOIN agency a ON sf.agency_id = a.ID
					LEFT JOIN client c ON sf.client_id = c.ID
					WHERE (sf.year_from = '$year')
							";
						//AND (sf.year_to = '$year')";
		}
		
			echo "<pre>".($select)."</pre>";
			$result = $con->query($select);
			var_dump($result);
			$mtx = $sql->fetch($result,$from,$from);
		

		return $mtx;

	}

	public function assemble($mtx,$currency,$source){
		$base = new base();

		//var_dump($currency);

		//var_dump($mtx);

		for ($m=0; $m <sizeof($mtx); $m++) { 
			var_dump($mtx[$m]);

			switch ($source) {
				case 'CMAPS':
					
					if ($mtx[$m]['package'] == 1) {
						$mtx[$m]['package'] = "YES";
					}else{
						$mtx[$m]['package'] = "NO";
					}
					

					if ($mtx[$m]['month']){
						$mtx[$m]['month'] = $base->intToMonth(array($mtx[$m]['month']))[0];
					}

					if ($mtx[$m]['discount'] || $mtx[$m]['grossRevenue'] || $mtx[$m]['netRevenue']) {
						if ($mtx[$m]['discount']) {
							$mtx[$m]['discount'] = doubleval($mtx[$m]['discount']);

						}
						if ($mtx[$m]['grossRevenue']) {
							$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue']);
							
						}
						if ($mtx[$m]['netRevenue']) {
							$mtx[$m]['netRevenue'] = doubleval($mtx[$m]['netRevenue']);
							
						}
						
					}
					
					$mtx[$m]['log'] = $base->formatData("aaaa-mm-dd","dd/mm/aaaa",$mtx[$m]['log']);
					
					
					var_dump($mtx[$m]);
					break;

				case 'IBMS/BTS':
					if ($mtx[$m]['month']) {
						$mtx[$m]['month'] = $base->intToMonth(array($mtx[$m]['month']))[0];
					}

					if($mtx[$m]['grossRevenue'] || $mtx[$m]['netRevenue']){
						if ($mtx[$m]['grossRevenue']) {
							$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue']);
						}
						if ($mtx[$m]['netRevenue']) {
							$mtx[$m]['netRevenue'] = doubleval($mtx[$m]['netRevenue']);
						}
					}
					if ($mtx[$m]['impressionDuration']) {
						$mtx[$m]['impressionDuration'] = doubleval($mtx[$m]['impressionDuration']);
					}

					var_dump($mtx[$m]);

					break;

				case 'FW':
					
					
					break;

				case 'SF':
					if ($mtx[$m]['fromDate']) {
						$mtx[$m]['fromDate'] = $base->intToMonth(array($mtx[$m]['fromDate']))[0];
					}
					if ($mtx[$m]['toDate']) {
						$mtx[$m]['toDate'] = $base->intToMonth(array($mtx[$m]['toDate']))[0];
					}
					if ($mtx[$m]['fcstAmountGross']) {
						$mtx[$m]['fcstAmountGross'] = doubleval($mtx[$m]['fcstAmountGross']);
					}
					if ($mtx[$m]['fcstAmountNet']) {
						$mtx[$m]['fcstAmountNet'] = doubleval($mtx[$m]['fcstAmountNet']);
					}
					if ($mtx[$m]['netRevenue']) {
						$mtx[$m]['netRevenue'] = doubleval($mtx[$m]['netRevenue']);
					}
					if ($mtx[$m]['grossRevenue']) {
						$mtx[$m]['grossRevenue'] = doubleval($mtx[$m]['grossRevenue']);
					}
					if ($mtx[$m]['agencyCommission']) {
						 $mtx[$m]['agencyCommission'] = $mtx[$m]['agencyCommission']*100;
					}


					var_dump($mtx[$m]);
					break;
				
				
			}

	
		}
	}

}
