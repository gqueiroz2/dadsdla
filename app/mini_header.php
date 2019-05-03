<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class mini_header extends Management {
    
    public function sum($con, $value, $columnsName, $columnsValue){
    	
    	$sql = new sql();

        $table = "mini_header";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);

        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $as);

        return $res;
    }
}
