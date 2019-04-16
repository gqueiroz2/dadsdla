<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:16/04/2019
*Razon:Region modeler
*/
class region extends Model
{

    /*
    *Author: Bruno Gomes
    *Date:16/04/2019
    *Razon:GetRegion modeler
    */
    public function getRegion ($con, $ID){
    	$where = "";
    	if ($ID) {
    		$ids = implode(",", $ID);
    		$where .= "region.ID IN ('$ids')";
    	}

    	$sql = "
    		SELECT 
    			region.ID AS id,
    			region.name AS name
    		FROM region AS region 
    		$where
    		ORDER BY region.name ASC ;
    	";

    	$res = $con->query($sql);

        return $res;
    }
}
