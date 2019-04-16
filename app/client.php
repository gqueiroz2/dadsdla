<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:09/04/2019
*Razon:Client modeler
*/
class client extends Model
{
    /*
    *Author: Bruno Gomes
    *Date:15/04/2019
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
    *Date:15/04/2019
    *Razon:Colluns modeler
    */
    public function colluns (
        $client,
        $client_unit,
        $client_group
    )
    {
        $colluns = "";

        if ($client) {
            $colluns .= "client.ID AS 'id', client.name AS 'name',";
        }

        if ($client_unit) {
            $colluns .= "client_unit.ID AS 'id', client_unit.name AS 'clientName',";
        }

        if ($client_group) {
            $colluns .= "client_group.ID AS 'id', client_group.name AS 'clientGroupName'";
        }

        return $colluns;
    }

    /*
    *Author: Bruno Gomes
    *Date:15/04/2019
    *Razon:Table modeler
    */
    public function table (
        $client,
        $client_unit,
        $client_group
    )
    {
        if ($client) {
            $table = "'client' client";
            $table .= "LEFT JOIN client_group client_group ON client_group.ID = client.client_group_id";
        }

        if ($client_unit) {
            $table = "'client_unit' client_unit";
            $table .= "LEFT JOIN client client ON client.ID = client_unit.client_id ";
        }

        if ($client_group) {
            $table = "'client_group' client_group";
            $table .= "LEFT JOIN region region ON region.ID = client_group.region_id";
        }

        return $table;
    }

    /*
    *Author: Bruno Gomes
    *Date:15/04/2019
    *Razon:Where modeler
    */
    public function where (
        $client,
        $client_unit,
        $client_group,
        $origin,
        $region
    )
    {
        $where = "";

        if ($client) {
            if ($client_group) {
                $client_group_ids = implode(",", $client_group);
                $where .= "client.client_group_id IN ('$client_group_ids')";
            }
            $client_ids = implode(",", $client);
            $where .= "client.ID IN ('..')";
        }

        if ($client_unit) {
            if ($client) {
                $client_ids = implode(",", $client);
                $where .= "client_unit.client_id IN ('$client_ids')";
            }

            if ($origin) {
                $origin_ids = implode(",", $origin);
                $where .= "client_unit.origin_id IN ('$origin_ids')";
            }
            $client_unit_ids = implode(",", $client_unit);
            $where .= "client_unit.ID IN ('$client_unit_ids')";
        }

        if ($client_group) {
            if ($region) {
                $region_ids = implode(",", $region);
                $where .= "client_group.region_id IN ('$region_ids')";
            }

            $where .= "client_group.ID IN ('$client_group')";

        }
    }

    /*
    *Author: Bruno Gomes
    *Date:15/04/2019
    *Razon:Order_by modeler
    */
    public function order_by (
        $client,
        $client_unit,
        $client_group,
        $order

    )
    {
        $order_by = "ORDER BY ";

        if ($client) {
            $order_by .= "client.name";
            if ($client_unit OR $client_group) {
                $order_by .= " , ";
            }
        }

        if ($client_unit) {
            $order_by .= "client_unit.name";
            if ($client_group OR $client) {
                $order_by .= " , ";
            }
        }

        if ($client_group) {
            $order_by .= "client_group.name";
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


	/*
	*Author: Bruno Gomes
	*Date:09/04/2019
	*Razon:getClient modeler
	*/
    public function getClient(

    )
    {
    	$sql = "
    		SELECT
    			client.ID AS 'id',
    			client.name AS 'name'
    		FROM 'DLA'.'client' AS client
            ORDER BY client.name ASC
    	";

    	$res = $con->query($sql);

    	return $res;
    }

	/*
	*Author: Bruno Gomes
	*Date:09/04/2019
	*Razon:getClientUnit modeler
	*/
    public function getClientUnit(
    	$client_id

    )
    {	
    	$where = "";

		if($client_id){
			$where .= "WHERE client_unit.ID in ('.$client_id.')";
		}

    	$sql = "
    		SELECT
    			client_unit.ID AS 'id',
    			client_unit.name AS 'clientUnitName'

    		FROM 'client_unit' AS client_unit
    			LEFT JOIN 'origin' AS origin ON origin.ID = client_unit.origin_id
    		$where
    	";

    	$res = $con->query($sql);

    	return $res;
    }
}
