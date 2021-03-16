<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class insightsRender extends Render{

	public function idNumber($mtx){

		for ($c=0; $c <sizeof($mtx); $c++) { 
					
			$client[$c] = $mtx[$c]['client']; 
		}

		$client = array_values(array_unique($client));

		for ($c=0; $c <sizeof($client); $c++) { 
			$idNumber[$c] =  array(); 
			for ($m=0; $m <sizeof($mtx); $m++) { 

				$temp[$m] = array($mtx[$m]['copyKey'], $mtx[$m]['mediaItem'], $mtx[$m]['client']);


				if ($client[$c] == $mtx[$m]['client']){
					array_push($idNumber[$c], $temp[$m]);
				}
			}
		}

		for ($i=0; $i <sizeof($idNumber); $i++) { 
			$idNumber[$i] = array_map('unserialize', array_values( array_unique(array_map('serialize', $idNumber[$i]))));


		}
		
		for ($i=0; $i <sizeof($idNumber); $i++) { 
			echo "<table style='width: 100%;'>";
				echo "<tr>";
					echo "<td class='darkBlue center'>Copy Title</td>";
					echo "<td class='darkBlue'>House Number</td>";
				echo "</tr>";
				echo "<tr class='darkBlue center'>";
					echo "<td colspan='2'>".$client[$i]."</td>";
				echo "</tr>";
			for ($j=0; $j <sizeof($idNumber[$i]); $j++) { 
				if ($j%2 == 0) {
					$color = 'even';
				}else{
					$color = 'medBlue';
				}
				echo "<tr class='$color'>";
					echo "<td class='center'>".$idNumber[$i][$j][0]."</td>";
					echo "<td class= 'center'>".$idNumber[$i][$j][1]."</td>";
				echo "</tr>";
			}
			
								
			echo "</table>";
		}
				
	}

	public function assemble($mtx,$currencies,$regions,$total){

		//$newValue = strtoupper($value);
		$newCurrency = strtoupper($currencies);


		echo "<table style='width: 100%;'>";
			echo "<tr>";
				echo "<th class='newBlue center' colspan='11' style='font-size:22px;'> $regions - Insights - ($newCurrency) </th>";
			echo "</tr>";
		
					echo "<tr class='newBlue center' style='font-size:15px;'>";
						echo "<td style='width:3%;'>Brand</td>";
						echo "<td style='width:5%;'>Sales Rep</td>";
						echo "<td style='width:4%;'>Month</td>";
						echo "<td style='width:4%;'>Year</td>";
						echo "<td style='width:10%;'>Client</td>";
						echo "<td style='width:10%;'>Agency</td>";
						echo "<td style='width:10%;'>Product</td>";
						echo "<td style='width:20%;'>Program</td>";
						echo "<td style='width:5%;'>Num Spot</td>";						
						echo "<td style='width:3%;'>Revenue</td>";
						echo "<td style='width:3%;'>Net Revenue</td>";
					echo "</tr>";

					echo "<tr style='font-size:14px;' class='darkBlue center'>";
						for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td>Total</td>";
							echo "<td colspan='7'></td>";
							echo "<td>".number_format($total[$t]['averageNumSpot'])."</td>";
							echo "<td>".number_format($total[$t]['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td>".number_format($total[$t]['sumNetRevenue'],0,",",".")."</td>";
						}	
					echo"</tr>";

					for ($m=0; $m < sizeof($mtx); $m++) { 
						
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}
						echo "<tr class='$color center' style='font-size:13px;'>";
							echo "<td>".$mtx[$m]['brand']."</td>";
							echo "<td>".$mtx[$m]['salesRep']."</td>";
							echo "<td>".$mtx[$m]['month']."</td>";
							echo "<td>".$mtx[$m]['year']."</td>";
							echo "<td>".$mtx[$m]['client']."</td>";
							echo "<td>".$mtx[$m]['agency']."</td>";
							echo "<td>".$mtx[$m]['product']."</td>";
							echo "<td>".$mtx[$m]['scheduleEvent']."</td>";
							echo "<td>".$mtx[$m]['numSpot']."</td>";
							echo "<td>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
							echo "<td>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
						echo"</tr>";
					}
		echo "</table>";
	}

}
