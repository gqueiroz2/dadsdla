<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:08/04/2019
*Razon:Plan by sales modeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class planBySales extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Query modeler
	*/
    public function query($con, $colluns, $tabels, $where, $order_by = 1)
    {    		
        $sql = "SELECT $colluns FROM $tabels WHERE $where $order_by ;";

    	$res = $con->query($sql);

    	return $res;
    }

    /*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Colluns modeler
	*/

	public function colluns(
		$region_id,
		$sales_rep_id,
		$brand_id,
		$month,
		$currency_id,
		$type_value,
		$value

	)
	{
		$colluns = "";

    	if ($region_id) {
    		$colluns .= "region.name AS 'regionName',";
    	}

    	if ($sales_rep_id) {
    		$colluns .= "sales_rep.name AS 'salesRep',";
    	}

    	if ($brand_id) {
    		$colluns .= "brand.name AS 'brand',";
    	}

    	if ($month) {
    		$colluns .= "plan_by_sales.mounth AS 'mounth',";
    	}

    	if ($currency_id) {
    		$colluns .= "currency.name AS 'currency',";
    	}

    	if ($type_value) {
    		$colluns .= "plan_by_sales.type_of_value AS 'typeValue',";
    	}

    	if ($value) {
    		$colluns .= "plan_by_sales.value AS 'value', ";
    	}

    	$colluns .= "plan_by_sales.ID AS 'id'";

	}

	    /*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Table modeler
	*/
	public function table(
		$region,
		$sales_rep,
		$brand,
		$currency
	)
	{
		$table .= "'plan_by_sales' AS plan_by_sales ";

    	if ($region) {
    		$table .= "LEFT JOIN 'region' ON plan.sales_office_id = region.ID ";
    	}

    	if ($sales_rep) {
    		$table .= "LEFT JOIN 'sales_rep'  ON sales_rep.ID = plan_by_sales.sales_rep_id ";
    	}

    	if ($brand) {
    		$table .= "LEFT JOIN 'brand' ON brand.ID = plan_by_sales.brand_id ";
    	}

    	if ($currency) {
    		$table .= "LEFT JOIN 'currency' ON plan.currency_id = currency.ID ";
    	}

    	return $table;
	}


	/*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Where modeler
	*/
	public function where(
		$month,
		$sales_rep_id,
		$brand_id,
		$region_id

	)
	{
		$where = "";

    	//for those parameters benith, pass it as value, not true or false
    	if ($month) {
    		$where .= "plan_by_sales.month = '.$month.'";
    		if ($region_id OR $brand_id) {
    			$where .= " AND ";
    		}
    	}

    	if ($sales_rep_id) {
    		$sales_rep_ids = implode(",", $sales_rep_id);
    		$where .= "sales_rep.ID IN ('$sales_rep_ids')";
    		if ($month OR $brand OR $year) {
    			$where .= " AND ";
    		}
    	}

    	if ($brand_id) {
    		$where .= "brand.name = '.$brand_id.'";
    		if ($region_id OR $month) {
    			$where .= " AND ";
    		}
    	}

    	if ($currency_id) {
    		$where .= "plan_by_sales.currency = '.$currency_id.'";
    	}

    	return $where;
	}

	/*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Order_by modeler
	*/

	public function order_by(
	$month,
	$sales_office,
    $order

    )
    {
		$order_by = "ORDER BY ";

		if ($month) {
			$order_by .= "plan_by_sales.month";
			if ($sales_rep) {
				$order_by .= " , ";
			}
		} 

		if($sales_rep){
    		$order_by .= "sales_rep.name ";
    	} 
        //this parameters, pass it as true or false, for true the result will be ASC
        if ($order == TRUE) {
            $order_by .= " ASC";
        }
        else{
            $order_by .= " DESC";
        }

        return $order_by;
    }
}
