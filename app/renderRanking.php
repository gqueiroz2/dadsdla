<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;

class renderRanking extends Render {
    
    public function assemble($mtx, $names, $pRate, $value, $total, $size, $type){
    
    	 echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center><span style='font-size:18px;'> ".$names['name']." ranking - (".$pRate[0]['name']."/".strtoupper($value).") </span></center></th>";
            echo "</tr>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center><span style='font-size:18px;'> ".$names['months']." </span></center></th>";
            echo "</tr>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center><span style='font-size:18px;'> VAR ABS. and VAR % are a comparison with ". $names['years']." </span></center></th>";
            echo "</tr>";
            echo "<tr><td> &nbsp; </td></tr>";

       $rank = new rank();

       $rank->renderAssembler($mtx, $total, $type, $size);

       echo "</table>";
    }
}