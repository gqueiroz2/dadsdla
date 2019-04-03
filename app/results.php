<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class results extends Model{
    
    public function assembler($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth){

        for ($b=0; $b < sizeof($brand); $b++) { 
            $mtx[$b] = $this->generateMatrix($con,$region,$year,$sumFirstPos,$operand,$brandFirstPos[$b],$firstPosMonth,$tableFirstPos);
        }

        return $mtx;
    }

    public function generateMatrix($con,$region,$year,$sum,$operand,$brand,$month,$table){

        for ($m=0; $m < sizeof($month); $m++) { 
            
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
        return $mtx;

    }

    public function generateWhere($region,$year,$table,$brand,$month,$sum){
        /*
        echo "                             SUB COMEÇA  <br>";

        echo "<pre>".var_dump($brand)."</pre>";
        echo "<pre>".var_dump($month)."</pre>";
        echo "<pre>".var_dump($sum)."</pre>";
        echo "<pre>".var_dump($table)."</pre>";
        echo "<pre>".var_dump($year)."</pre>";
        echo "<pre>".var_dump($region)."</pre>";

        echo "                             SUB FIM  <br>";
        */
        switch ($table) {
            case 'target':
                //
                if(!is_array( $brand ) ){
                    
                    $where = "WHERE (sales_office = '".strtoupper($region) ."') AND (currency = 'PR $year') AND (type_rev = '$sum') AND ( brand = '$brand' )";
                }else{
                    $where = "WHERE (sales_office = '$region') AND (currency = 'PR $year') AND (type_rev = '$sum') AND ( brand = '$brand[0]' OR brand = '$brand[1]')";
                }
                //echo " WHERE -----------------> ".$where."<br><br><br>";
                return $where;
                break;
            
            
        }

        //echo "                             SUB FIM<br><br><br><br>";
    }



}
