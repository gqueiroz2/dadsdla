<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model{

    public function select($con, $columns, $table, $join, $where, $order_by = 1){    	

        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by";    	
    	$res = $con->query($sql);
    	return $res;
    }

}
