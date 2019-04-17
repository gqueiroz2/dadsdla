<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:08/04/2019
*Razon:Plan by brand modeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class planByBrand extends Model
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
		$sales_office_id,
		$currency_id,
		$month,
		$year,
		$revenue,
		$net_revenue, 
		$net_net_revenue,
		$source_id
	)
	{
		$colluns = "";

    	if ($sales_office_id) {
    		$colluns .= "region.name AS 'salesOffice',";
    	}

    	if ($currency_id) {
    		$colluns .= "currency.name AS 'currency',";
    	}

    	if ($month) {
    		$colluns .= "plan.mounth AS 'mounth',";
    	}

    	if ($year) {
    		$colluns .= "plan.year AS 'year',";
    	}

    	if ($revenue) {
    		$colluns .= "plan.revenue AS 'revenue',";
    	}

    	if ($net_revenue) {
    		$colluns .= "plan.net_revenue AS 'netRevenue',";
    	}

    	if ($net_net_revenue) {
    		$colluns .= "plan.net_net_revenue AS 'netNetRevenue',";
    	}

    	if ($source_id) {
    		$colluns .= "source.name AS 'sourceName',";
    	}

    	$colluns .= "plan.ID = id";

    	return $colluns;
	}

    /*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:Table modeler
	*/
	public function table(
		$region,
		$currency,
		$source
	)
	{
		$table .= "'plan_by_brand' AS plan ";

    	if ($region) {
    		$table .= "LEFT JOIN 'region'  ON plan.sales_office_id = region.ID ";
    	}

    	if ($currency) {
    		$table .= "LEFT JOIN 'currency'  ON plan.currency_id = currency.ID ";
    	}

    	if ($source) {
    		$table .= "LEFT JOIN 'plan_source'  ON source.ID = plan.source_id";
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
    	$year,
    	$sales_office,
    	$source
	)
	{
		$where = "";

    	//for those parameters benith, pass it as value, not true or false
    	if ($month) {
    		$where .= "plan_by_brand.month = '.$month.'";
    		if ($year OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($year) {
    		$where .= "plan_by_brand.year = '.$year.'";
    		if ($month OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($sales_office) {
    		$sales_office_ids = implode(",", $sales_office);
    		$where .= "region.ID IN ('$sales_office_ids')";
    		if ($month OR $brand OR $year) {
    			$where .= " AND ";
    		}
    	}

    	if($source){
    		$source_ids = implode(",", $source)
    		$where .= "source.ID IN ('$source_ids')";
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
	$year,
	$sales_office,
    $order

    )
    {
		$order_by = "ORDER BY ";

		if ($month) {
			$order_by .= "plan_by_brand.month";
			if ($year OR $brand) {
				$order_by .= " , ";
			}
		}

		if ($year) {
			$order_by .= "plan_by_brand.year";
			if ($year OR $brand) {
				$order_by .= " , ";
			}
		}

		if($sales_office){
			$order_by .= "region.name ";
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
