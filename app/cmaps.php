<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:04/04/2019
*Razon:Cmaps modeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class cmaps extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:04/04/2019
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
	*Date:04/04/2019
	*Razon:Collum modeler
	*/

    public function colluns(
    	$sales_group_id,
    	$decode,
    	$year,
    	$month,
    	$map_number,
    	$sales_rep_id,
    	$package,
    	$client_id,
    	$product,
    	$segment,
    	$agency_id,
    	$brand_id,
    	$pi_number,
    	$gross,
    	$net,
    	$market,
    	$discount,
    	$client_cnpj,
    	$agency_cnpj,
    	$media_type,
    	$log,
    	$ad_sales_support,
    	$obs,
    	$setor,
    	$categoria    	
    )
    {
    	$colluns = "";

    	if ($sales_group_id) {
    		$colluns .= "sales_rep_group.name AS 'salesGroup', ";
    	}

    	if ($decode) {
    		$colluns .= "cmaps.decode AS 'decode', ";
    	}

    	if ($year) {
    		$colluns .= "cmaps.year AS 'year', ";
    	}

    	if ($month) {
    		$colluns .= "cmaps.month AS 'month', ";
    	}

    	if ($map_number) {
    		$colluns .= "cmaps.map_number AS 'mapNumber', ";
    	}

    	if ($sales_rep_id) {
    		$colluns .= "sales_rep.name AS 'salesRep', " ;
    	}

    	if ($package) {
    		$colluns .= "cmaps.package AS 'package', ";
    	}

    	if ($client_id) {
    		$colluns .= "client.name AS 'clientName', ";
    	}

    	if ($product) {
    		$colluns .= "cmaps.product AS 'product', ";
    	}

    	if ($segment) {
    		$colluns .= "cmaps.segment AS 'segment', ";
    	}

    	if ($agency_id) {
    		$colluns .= "agency.name AS 'agencyName', ";
    	}

    	if ($brand_id) {
    		$colluns .= "brand_unit.name AS 'brandName',";
    	}

    	if ($pi_number) {
    		$colluns .= "cmaps.pi_number AS 'piNumber',";
    	}

    	if ($gross) {
    		$coluns .= "cmaps.gross AS 'gross',";
    	}

    	if ($net) {
    		$colluns .= "cmaps.net AS 'net',";
    	}

    	if ($market) {
    		$colluns .= "cmaps.market AS 'market',";
    	}

    	if ($discount) {
    		$colluns .= "cmaps.discount AS 'discount',";
    	}

    	if ($client_cnpj) {
    		$colluns .= "cmaps.client_cnpj AS 'clientCnpj',";
    	}

    	if ($agency_cnpj) {
    		$colluns .= "cmaps.agency_cnpj AS 'agencyCnpj',";
    	}

    	if ($media_type) {
    		$colluns .= "cmaps.media_type AS 'mediaType',";
    	}

    	if ($log) {
    		$colluns .= "cmaps.log AS 'log',";
    	}

    	if ($ad_sales_support) {
    		$colluns .= "cmaps.ad_sales_support AS 'adSalesSupport',";
    	}

    	if ($obs) {
    		$colluns .= "cmaps.obs AS 'obs',";
    	}

    	if ($sector) {
    		$colluns .= "cmaps.sector AS 'sector', ";
    	}

    	if ($cathegory) {
    		$colluns .= "cmaps.cathegory AS 'cathegory', ";
    	}

    	$colluns .= "cmaps.ID AS 'id' ";

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:04/04/2019
	*Razon:Table modeler
	*/
    public function table(
    	$brand,
    	$client,
    	$agency,
    	$sales_rep,
    	$sales_rep_group
    )
    {
    	$table = "'cmaps' AS cmaps ";

    	if ($brand) {
    		//make the bound between Brand and Channel
    		$table .= "LEFT JOIN 'brand' AS brand ON ytd.channel_brand_id = brand.ID";

    		//make the bound between Brand and Brand Unit
    		$table .= "LEFT JOIN 'brand_unit' AS brand_unit ON brand.ID = brand_unit.brand_id";
    	}

    	if ($client) {
    		$table .= "LEFT JOIN 'client' AS client ON cmaps.client_id = client.ID";
    	}

    	if ($agency) {
    		$table .= "LEFT JOIN 'agency' AS agency ON cmaps.agency_id = agency.ID";
    	}    	

    	if ($sales_rep) {
    		//make the bound between Sales_rep and cmaps 
    		$table .= "LEFT JOIN 'sales_rep' AS sales_rep ON cmaps.sales_representant_id = sales_rep.ID";
    	}

    	if ($sales_rep_group) {
    		$table .= "LEFT JOIN 'sales_rep_group' AS sales_rep_group ON cmaps.sales_group_id = sales_rep_group.ID";
    	}

    	return $table;
    	
    }

    /*
	*Author: Bruno Gomes
	*Date:05/04/2019
	*Razon:Where modeler
	*/
    public function where(
    	$month,
    	$year,
    	$brand,
    	$sales_rep

    )
    {
    	$where = "";

    	//for this parameters and benith, pass it as value, not true or false
    	if ($month) {
    		$where .= "cmaps.month = '.$month.'";
    		if ($year OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($year) {
    		$where .= "cmaps.year = '.$year.'";
    		if ($month OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($sales_rep) {
    		$sales_rep_ids = implode(",", $sales_rep);
    		$where .= "sales_rep.ID IN ('$sales_rep_ids')";
    		if ($month OR $brand OR $year) {
    			$where .= " AND ";
    		}
    	}

    	//in this parameter, has a exception, pass it as true or false
    	if ($brand) {
            $brand_ids = implode(",", $brand);
    		$where .= "brand.ID IN ('$brand_ids') ";    		
    	}

    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:04/04/2019
	*Razon:Order by modeler
	*/
    public function order_by(
    	$month,
    	$year,
    	$brand,
    	$sales_rep,
        $order
    )
    {
    	$order_by = "ORDER_BY ";

    	if ($month) {
    		$order_by .= "cmaps.month";
    		if ($year OR $brand) {
    			$order_by .= " , ";
    		}
    	}

    	if ($year) {
    		$order_by .= "cmaps.year";
    		if ($brand OR $sales_rep) {
    			$order_by .= " , ";
    		}
    	}

    	if($sales_rep){
    		$order_by .= "sales_rep.name ";
            if($brand){
                $order_by .= " , ";
            }
    	}

        if ($brand) {
            $order_by .= "brand.name";
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
