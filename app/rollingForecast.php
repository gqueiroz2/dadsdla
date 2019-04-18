<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:10/04/2019
*Razon:RollingForecast modeler
*/
class rollingForecast extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Query modeler
	*/
    public function select($con, $columns, $table, $join, $where, $order_by = 1){       
        
        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by";       
        $res = $con->query($sql);
        return $res;
    }      
}
