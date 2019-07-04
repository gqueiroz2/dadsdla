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

    public function excelToBase($sp){

        //var_dump($sp);

        unset($sp[0]);

        $sp = array_values($sp);
        var_dump($sp);
        for ($s=0; $s < sizeof($sp); $s++) { 
            
        }

    }
}
