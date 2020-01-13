<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class insightsRender extends Render{

	public function assemble($mtx,$currencies,$value,$regions){

		$newValue = strtoupper($value);
		$newCurrency = strtoupper($currencies);


		echo "<table style='width: 100%;'>";
			echo "<tr>";
				echo "<th class='newBlue center' colspan='21' style='font-size:22px;'> $regions - Viewer Insights - ($newCurrency/$newValue) </th>";
			echo "</tr>";
		
					echo "<tr class='center'>";
						echo "<td class='newBlue' style='width:5%; '>Brand</td>";
						echo "<td class='newBlue' style='width:5%;'>Brand Feed</td>";
						echo "<td class='newBlue' style='width:5%;'>Sales Rep</td>";
						echo "<td class='newBlue' style='width:5%;'>Agency</td>";
						echo "<td class='newBlue' style='width:5%;'>Client</td>";
						echo "<td class='newBlue' style='width:5%;'>Month</td>";
						echo "<td class='newBlue' style='width:5%;'>Charge Type</td>";
						echo "<td class='newBlue' style='width:5%;'>Product</td>";
						echo "<td class='newBlue' style='width:5%;'>Campaign</td>";
						echo "<td class='newBlue' style='width:5%;'>Order Reference</td>";
						echo "<td class='newBlue' style='width:7%;'>Schedule Event</td>";
						echo "<td class='newBlue' style='width:5%;'>Spot Status</td>";
						echo "<td class='newBlue' style='width:5%;'>Date Event</td>";
						echo "<td class='newBlue' style='width:5%;'>Unit Start Time</td>";
						echo "<td class='newBlue' style='width:5%;'>Duration Spot</td>";
						echo "<td class='newBlue' style='width:5%;'>Copy Key</td>";
						echo "<td class='newBlue' style='width:3%;'>Media Item</td>";
						echo "<td class='newBlue' style='width:7%;'>Spot Type</td>";
						echo "<td class='newBlue' style='width:10%;'>Duration Impression</td>";
						echo "<td class='newBlue' style='width:7%;'>Num Spot</td>";
						echo "<td class='newBlue' style='width:5%;'>  Revenue</td>";
					echo "</tr>";

					echo "<tr style='font-size:14px;' class='darkBlue center'>";
						echo "<td>Total</td>";
						echo "<td colspan='17'></td>";
						echo "<td></td>";
					echo"</tr>";

					for ($m=0; $m < sizeof($mtx); $m++) { 
						
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}

						echo "<tr class='center' style='font-size:11px;'>";
							echo "<td class='$color'>".$mtx[$m]['brand']."</td>";
							echo "<td class='$color'>".$mtx[$m]['brandFeed']."</td>";
							echo "<td class='$color'>".$mtx[$m]['salesRep']."</td>";
							echo "<td class='$color'>".$mtx[$m]['agency']."</td>";
							echo "<td class='$color'>".$mtx[$m]['client']."</td>";
							echo "<td class='$color'>".$mtx[$m]['month']."</td>";
							echo "<td class='$color'>".$mtx[$m]['chargeType']."</td>";
							echo "<td class='$color'>".$mtx[$m]['product']."</td>";
							echo "<td class='$color'>".$mtx[$m]['campaign']."</td>";
							echo "<td class='$color'>".$mtx[$m]['orderReference']."</td>";
							echo "<td class='$color'>".$mtx[$m]['scheduleEvent']."</td>";
							echo "<td class='$color'>".$mtx[$m]['spotStatus']."</td>";
							echo "<td class='$color'>".$mtx[$m]['dateEvent']."</td>";
							echo "<td class='$color'>".$mtx[$m]['unitStartTime']."</td>";
							echo "<td class='$color'>".$mtx[$m]['durationSpot']."</td>";
							echo "<td class='$color'>".$mtx[$m]['copyKey']."</td>";
							echo "<td class='$color'>".$mtx[$m]['mediaItem']."</td>";
							echo "<td class='$color'>".$mtx[$m]['spotType']."</td>";
							echo "<td class='$color'>".$mtx[$m]['durationImpression']."</td>";
							echo "<td class='$color'>".$mtx[$m]['numSpot']."</td>";
							echo "<td class='$color'>".number_format($mtx[$m][$value.'Revenue'],0,",",".")."</td>";
						echo"</tr>";
					}
		echo "</table>";
	}

}
