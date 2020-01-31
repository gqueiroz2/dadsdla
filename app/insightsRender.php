<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class insightsRender extends Render{

	public function idNumber($mtx){
		echo "<table style='width: 100%;'>";
			echo "<tr class='darkBlue center'>";
				echo "<td>Copy Key</td>";
				echo "<td>Media Item</td>";
			echo "</tr>";

			for ($m=0; $m <sizeof($mtx); $m++) { 

				if ($m%2 == 0) {
					$color = 'even';
				}else{
					$color = 'medBlue';
				}

			echo "<tr class='$color center'>";
					echo "<td>".$mtx[$m]['copyKey']."</td>";
					echo "<td>".$mtx[$m]['mediaItem']."</td>";	
				}
				
			echo "</tr>";
		echo "</table>";
	}

	public function assemble($mtx,$currencies,$value,$regions,$total){

		$newValue = strtoupper($value);
		$newCurrency = strtoupper($currencies);


		echo "<table style='width: 100%;'>";
			echo "<tr>";
				echo "<th class='newBlue center' colspan='9' style='font-size:22px;'> $regions - Insights - ($newCurrency/$newValue) </th>";
			echo "</tr>";
		
					echo "<tr class='newBlue center'>";
						echo "<td style='width:3%;'>Brand</td>";
						echo "<td style='width:5%;'>Sales Rep</td>";
						echo "<td style='width:3%;'>Month</td>";
						echo "<td style='width:10%;'>Client</td>";
						echo "<td style='width:10%;'>Agency</td>";
						echo "<td style='width:10%;'>Product</td>";
						echo "<td style='width:20%;'>Schedule Event</td>";
						echo "<td style='width:3%;'>Num Spot</td>";
						echo "<td style='width:3%;'>Revenue</td>";
					echo "</tr>";

					echo "<tr style='font-size:14px;' class='darkBlue center'>";
						for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td>Total</td>";
							echo "<td colspan='6'></td>";
							echo "<td>".number_format($total[$t]['averageNumSpot'])."</td>";
							echo "<td>".number_format($total[$t]['sum'.$value.'Revenue'],0,",",".")."</td>";
						}	
					echo"</tr>";

					for ($m=0; $m < sizeof($mtx); $m++) { 
						
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}

						echo "<tr class='$color center' style='font-size:11px;'>";
							echo "<td>".$mtx[$m]['brand']."</td>";
							echo "<td>".$mtx[$m]['salesRep']."</td>";
							echo "<td>".$mtx[$m]['month']."</td>";
							echo "<td>".$mtx[$m]['client']."</td>";
							echo "<td>".$mtx[$m]['agency']."</td>";
							echo "<td>".$mtx[$m]['product']."</td>";
							echo "<td>".$mtx[$m]['scheduleEvent']."</td>";
							echo "<td>".$mtx[$m]['numSpot']."</td>";
							echo "<td>".number_format($mtx[$m][$value.'Revenue'],0,",",".")."</td>";
						echo"</tr>";
					}
		echo "</table>";
	}

}
