<?php

namespace App;


use App\sql;
use Illuminate\Database\Eloquent\Model;

class results extends Model{
    
    
    public function generateVector($con,$table,$region,$year,$month,$brand,$currency,$value,$join,$where,$souce = false){
        $sql = new sql();
    


        if($table == "cmaps"){
            if($value == "gross"){
                $sum = $value;
            }else{
                $sum = $value;
            }
        }elseif($table == "plan_by_brand"){
            $sum = "revenue";
        }else{
            if($value == "gross"){
                $sum = $value."_value";
            }else{
                $sum = $value."_value";
            }
        }
        $as = "sum";
        for ($m=0; $m < sizeof($month); $m++) { 

            $res[$m] = $sql->selectSum($con,$sum,$as,$table,$join,$where[$m]);
            $vector[$m] = $sql->fetchSum($res[$m],$as)["sum"];
        }
        return $vector;
    }
}
