<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenderStuff extends Model
{
    public function base($table,$newValues,$dependencies){
    	echo "<table class='table table-bordered' style='width:100%;'>";
			echo "<tr> 
					<td> $table </td>
			      <tr>";
			for ($d=0; $d < sizeof($dependencies); $d++){ 		
				if($newValues[$dependencies[$d]]){
					for ($n=0; $n < sizeof($newValues[$dependencies[$d]]); $n++) { 
						echo "<tr>";
							echo "<td> Create the value <span style='color:red'>".$newValues[$dependencies[$d]][$n]."</span> for the table ".$dependencies[$d]."</td>";
						echo "</tr>";
					}
					
				}else{
					echo "<tr><td><span style='color:green'> There are values of ".$dependencies[$d]." to be Created </span></td></tr>";
				}			
			}
		echo "</table>";
    }
}
