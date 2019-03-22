<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\results;

class monthly extends results{
    
    public function caller($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth){
        
    	//echo "<pre>".var_dump($firstPosMonth)."</pre>";

        $mtx = $this->assembler($con,$region,$year,$brand,$currency,$value,$brandFirstPos,$brandSecondPos,$tableFirstPos,$tableSecondPos,$operand,$sumFirstPos,$sumSecondPos,$firstPosMonth,$secondPosMonth);

        echo "<table style='width:100%;'>";
        echo "<tr>";
        	echo "<th> Brand </th>";
        	for ($m=0; $m < sizeof($firstPosMonth); $m++) { 
        		echo "<th>".$firstPosMonth[$m]."</th>";
        	}
        echo "</tr>";
        for ($b=0; $b < sizeof($brand) ; $b++) { 
        	echo "<tr>";
        		echo "<td>".$brand[$b]."</td>";
        	for ($m=0; $m < sizeof($mtx[$b]); $m++) { 
        		echo "<td>". number_format( $mtx[$b][$m] )."</td>";
        	}
        	echo "</tr>";
        }
        echo "</table>";



    }

}
