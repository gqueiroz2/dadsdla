<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:12/04/2019
*Razon:Forecastmodeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class forecast extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Query modeler
	*/
    public function select($con, $columns, $table, $join, $where, $order_by = 1){       
        
        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by";       
        $res = $con->query($sql);
        return $res;
    }    
}
