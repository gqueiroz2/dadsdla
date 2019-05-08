<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class digital extends Management {
    
    public function sum($con, $value, $columnsName, $columnsValue){
        
        $sql = new sql();

        $table = "digital";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);
        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $as);

        return $res;
    }
}
