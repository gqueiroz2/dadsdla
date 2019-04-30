<?php

namespace App;


use App\sql;
use Illuminate\Database\Eloquent\Model;

class results extends Model{
    
    public function assembler($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth){

        /*for ($b=0; $b < sizeof($brand); $b++) { 
            $mtx[$b] = $this->generateMatrix($con,$region,$year,$sumFirstPos,$operand,$brandFirstPos[$b],$firstPosMonth,$tableFirstPos);
        }

        return $mtx;*/
    }

    public function generateMatrix($con,$arrayWhere,$columns){



        /*for ($m=0; $m < sizeof($month); $m++) { 
            
            $where[$m] = $this->generateWhere($region,$year,$table,$brand,$month[$m],$sum);

            $sql = "SELECT $month[$m] FROM $table $where[$m]";            
            //echo "<pre>".var_dump($sql)."</pre>";
            $res = $con->query($sql);
            //echo "<pre>".var_dump($res)."</pre>";
            if($res && $res->num_rows > 0){    
                while($row = $res->fetch_assoc()){
                    $mtx[$m] = doubleval($row[$month[$m]]);
                }
            }else{
                $mtx[$m] = FALSE;
            }
        }
        //echo "<pre>".var_dump($mtx)."</pre>";
        return $mtx;*/

    }

}
