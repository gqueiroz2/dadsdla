<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class results extends Model{
    
    public function assembler($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth){

        for ($b=0; $b < sizeof($brand); $b++) { 
            $mtx[$b] = $this->generateMatrix($con,$region,$year,$sumFirstPos,$operand,$brandFirstPos[$b],$firstPosMonth,$tableFirstPos);
        }

    }

    public function generateMatrix($con,$region,$year,$sum,$operand,$brand,$month,$table){

        for ($m=0; $m < sizeof($month); $m++) { 
            
            $where[$m] = $this->generateWhere($region,$year,$table,$brand,$month[$m],$sum);

            $sql = "SELECT $month[$m] FROM $table $where[$m]";            

            $res = $con->query($sql);

            if($res && $res->num_rows > 0){    
                while($row = $res->fetch_assoc()){
                    $mtx[$m] = doubleval($row[$month[$m]]);
                }
            }else{
                $mtx[$m] = FALSE;
            }
        }

        return $mtx;

    }

    public function generateWhere($region,$year,$table,$brand,$month,$sum){
        /*
        echo "                             SUB COMEÇA<br>";

        echo "<pre>".var_dump($brand)."</pre>";
        echo "<pre>".var_dump($month)."</pre>";
        */
        switch ($table) {
            case 'target':
                $where = "WHERE (sales_office = '$region') AND (currency = 'PR $year') AND (type_rev = '$sum') AND ( brand = '$brand' )";
                //echo " WHERE -----------------> ".$where."<br><br><br>";
                return $where;
                break;
            
            
        }

        //echo "                             SUB FIM<br><br><br><br>";
    }



}
