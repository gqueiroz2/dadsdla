<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class viewer extends Model{

	public function getTables($con,$value,$month,$source,$piNumber=NULL,$brand,$year){

		if ($source == 'cmaps'){ 
			$select = "SELECT sr.name, c.pi_number, c.month, b.name AS 'brand', SUM(c.'$value') AS '$value' 
						   FROM cmaps c
						   LEFT JOIN sales_Rep sr ON sr.ID = c.sales_rep_id
						   LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
						   LEFT JOIN brand b ON b.ID = c.brand_id
						   WHERE (c.brand_id IN ('$brand')) 
								AND (c.year = '$year') 
								AND (c.month IN ('$month'))
								AND (srs.status = '1')
						   GROUP BY c.pi_number
						   ORDER BY c.month";

			$result = $con->query($select);

		}elseif ($source == 'ibms/bts'){
			$select = "SELECT sr.name, y.order_reference,y.month,b.name, SUM(y.'$value'_revenue) AS '$value'
					   FROM ytd y 
					   LEFT JOIN sales_rep sr ON sr.ID = y.sales_rep_id
					   LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
					   LEFT JOIN brand b ON b.ID = y.brand_id
					   WHERE (y.brand_id IN ('brand'))
							AND (y.year = '$year')
							AND (y.month IN ('$month'))
							AND (srs.status = '1')
					   GROUP BY y.order_reference
					   ORDER BY y.month";

			$result = $con->query($select);

		}elseif ($source == 'fw'){
			$select = "SELECT sr.name, f.insertion_order,f.month,b.name,SUM(f.'$value'_revenue)
						FROM fw_digital f
						LEFT JOIN sales_rep sr ON sr.ID = f.sales_rep_id
						LEFT JOIN sales_rep_status srs ON srs.sales_rep_id = sr.ID
						LEFT JOIN brand b  ON b.ID = f.brand_id
						WHERE (f.brand_id IN ('$brand'))
								AND (f.year = '$year')
								AND (f.month IN ('$month'))
								AND (srs.status = '1')
						GROUP BY f.insertion_order_id
						ORDER BY f.month";
		}elseif ($source == "sf"){
			
		}
		
	}

}
