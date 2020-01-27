<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class insightsRender extends Render{

	public function assemble($mtx,$currencies,$value,$regions,$total){

		$newValue = strtoupper($value);
		$newCurrency = strtoupper($currencies);


		echo "<table style='width: 100%;'>";
			echo "<tr>";
				echo "<th class='newBlue center' colspan='21' style='font-size:22px;'> $regions - Insights - ($newCurrency/$newValue) </th>";
			echo "</tr>";
		
					echo "<tr class='newBlue center'>";
						echo "<td style='width:5%; '>Brand</td>";
						echo "<td style='width:5%;'>Brand Feed</td>";
						echo "<td style='width:5%;'>Sales Rep</td>";
						echo "<td style='width:5%;'>Agency</td>";
						echo "<td style='width:5%;'>Client</td>";
						echo "<td style='width:5%;'>Month</td>";
						echo "<td style='width:5%;'>Charge Type</td>";
						echo "<td style='width:5%;'>Product</td>";
						echo "<td style='width:5%;'>Campaign</td>";
						echo "<td style='width:5%;'>Order Reference</td>";
						echo "<td style='width:7%;'>Schedule Event</td>";
						echo "<td style='width:5%;'>Spot Status</td>";
						echo "<td style='width:5%;'>Date Event</td>";
						echo "<td style='width:5%;'>Unit Start Time</td>";
						echo "<td style='width:5%;'>Duration Spot</td>";
						echo "<td style='width:5%;'>Copy Key</td>";
						echo "<td style='width:3%;'>Media Item</td>";
						echo "<td style='width:7%;'>Spot Type</td>";
						echo "<td style='width:10%;'>Duration Impression</td>";
						echo "<td style='width:7%;'>Num Spot</td>";
						echo "<td style='width:5%;'>Revenue</td>";
					echo "</tr>";

					echo "<tr style='font-size:14px;' class='darkBlue center'>";
						for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td>Total</td>";
							echo "<td colspan='18'></td>";
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
							echo "<td>".$mtx[$m]['brandFeed']."</td>";
							echo "<td>".$mtx[$m]['salesRep']."</td>";
							echo "<td>".$mtx[$m]['agency']."</td>";
							echo "<td>".$mtx[$m]['client']."</td>";
							echo "<td>".$mtx[$m]['month']."</td>";
							echo "<td>".$mtx[$m]['chargeType']."</td>";
							echo "<td>".$mtx[$m]['product']."</td>";
							echo "<td>".$mtx[$m]['campaign']."</td>";
							echo "<td>".$mtx[$m]['orderReference']."</td>";
							echo "<td>".$mtx[$m]['scheduleEvent']."</td>";
							echo "<td>".$mtx[$m]['spotStatus']."</td>";
							echo "<td>".$mtx[$m]['dateEvent']."</td>";
							echo "<td>".$mtx[$m]['unitStartTime']."</td>";
							echo "<td>".$mtx[$m]['durationSpot']."</td>";
							echo "<td>".$mtx[$m]['copyKey']."</td>";
							echo "<td>".$mtx[$m]['mediaItem']."</td>";
							echo "<td>".$mtx[$m]['spotType']."</td>";
							echo "<td>".$mtx[$m]['durationImpression']."</td>";
							echo "<td>".$mtx[$m]['numSpot']."</td>";
							echo "<td>".number_format($mtx[$m][$value.'Revenue'],0,",",".")."</td>";
						echo"</tr>";
					}
		echo "</table>";
	}

}
