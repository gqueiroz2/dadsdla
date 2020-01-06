<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;

class renderRanking extends Render {
    
    public function search($mtx, $type){

        if($type == "client"){
            $p = 2;
        }elseif($type == "agency") {
            $p = 3;
        }else{
            $p = 2;
        }

        echo "<select class='selectpicker' id='namesExcel' name='namesExcel[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control'>";

            for ($m=1; $m < sizeof($mtx[$p]); $m++) { 
                echo "<option value='".base64_encode(json_encode(array($mtx[$p-1][$m], $mtx[$p][$m])))."' >".$mtx[$p][$m]."</option>";
            }

        echo "</select>";

    }

    public function assemble($mtx, $names, $pRate, $value, $total, $size, $type){
    	 echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center>
                            <span style='font-size:18px;'> 
                                <b> ".$names['name']." Ranking (BKGS) : (".$pRate[0]['name']."/".strtoupper($value).") </b>
                            </span>
                        </center></th>";
            echo "</tr>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center>
                            <span style='font-size:14px;'> 
                                <i>".$names['months']." </i>
                            </span>
                      </center></th>";
            echo "</tr>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center>
                            <span style='font-size:12px;'> 
                                <i> VAR ABS. and VAR % are a comparison with ". $names['years']." </i> 
                            </span>
                      </center></th>";
            echo "</tr>";
            echo "<tr><td> &nbsp; </td></tr>";

           $rank = new rank();

           $rank->renderAssembler($mtx, $total, $type, $size);

       echo "</table>";
    }
}