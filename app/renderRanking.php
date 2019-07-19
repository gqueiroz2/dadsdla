<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

       for ($m=0; $m < $size; $m++) {
       		echo "<tr>";

   			for ($i=0; $i < sizeof($mtx); $i++) {
       			if ($m == 0) {
       				echo "<td class='lightBlue center'> ".$mtx[$i][$m]." </td>";
       			}elseif ($m%2 != 0) {
       				if (!is_numeric($mtx[$i][$m])) {
                if ($mtx[$i][$m] != "-") {
                  echo "<td id='".$type.$m."' class='rcBlue center'> ".$mtx[$i][$m]." </td>";  
                }else{
                  echo "<td class='rcBlue center'> ".$mtx[$i][$m]." </td>";
                }
	       			}else{
	       				if (substr($mtx[$i][0], 0, 3) == "Pos") {
       						if ($mtx[$i][$m] != '-') {
       							echo "<td class='rcBlue center'> ".$mtx[$i][$m]."º </td>";		
       						}else{
       							echo "<td class='rcBlue center'> ".$mtx[$i][$m]." </td>";
       						}
       					}else{
       						echo "<td class='rcBlue center'> ".number_format($mtx[$i][$m])." </td>";	
       					}
	       				
	       			}
       			}elseif ($m%2 == 0) {
       				if (!is_numeric($mtx[$i][$m])) {
	  					  if ($mtx[$i][$m] != "-") {
                  echo "<td id='".$type.$m."' class='medBlue center'> ".$mtx[$i][$m]." </td>";  
                }else{
                  echo "<td class='medBlue center'> ".$mtx[$i][$m]." </td>";  
                }
	       			}else{
	       				if (substr($mtx[$i][0], 0, 3) == "Pos") {
       						if ($mtx[$i][$m] != '-') {
       							echo "<td class='medBlue center'> ".$mtx[$i][$m]."º </td>";		
       						}else{
       							echo "<td class='medBlue center'> ".$mtx[$i][$m]." </td>";
       						}
       					}else{
       						echo "<td class='medBlue center'> ".number_format($mtx[$i][$m])." </td>";	
       					}
	       			}
       			}
       			
       		}

       		echo "</tr>";
          echo "<tr>";
            echo "<td id='sub".$type.$m."' style='display: none' colspan='".sizeof($mtx)."'></td>";

          echo "</tr>";
       }

      echo "<tr>";

      for ($t=0; $t < sizeof($total); $t++) { 
        
        if (is_numeric($total[$t])) {
          echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>"; 
        }else{
          if ($total[$t] != "-") {
            echo "<td class='darkBlue center'> ".$total[$t]." </td>"; 
          }else{
            echo "<td class='darkBlue center'> &nbsp; </td>"; 
          }
        }

      }

       echo "</table>";
    }
}
