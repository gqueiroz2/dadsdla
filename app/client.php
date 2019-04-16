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
	*Date:09/04/2019
	*Razon:getClient modeler
	*/
    public function getClient(

    )
    {
    	$sql = "
    		SELECT
    			client.ID AS 'ID',
    			client.name AS 'client_name'
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
    			client_unit.ID AS 'ID',
    			client_unit.name AS 'client_unit_name'

    		FROM 'DLA'.'client_unit' AS client_unit
    			LEFT JOIN 'DLA'.'origin' AS origin ON origin.ID = client_unit.origin_id
    		$where
    	";

    	$res = $con->query($sql);

    	return $res;
    }
}
