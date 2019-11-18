<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class baseRender extends Render{

	public function assemble($mtx,$value,$months,$year,$regions,$brand,$source,$currencies,$total){
		//var_dump($currencies);

		$value = strtoupper($value);

		if ($source == 'SF') {
			$source = "SalesForce";
		}elseif ($source == 'FW') {
			$source = "FreeWheel";
		}elseif ($source == 'CMAPS') {
			$source = "Cmaps";
		}elseif ($source == 'IBMS/BTS') {
			$source = "BTS";
		}
		
		
		
			echo "<table style='width: 100%;'>";
				echo "<tr>";	
					echo "<th class='newBlue center' colspan='14' style='font-size:20px; width:100%;'> $regions - Viewer $source $year - ($currencies/$value) </th>";
				echo "</tr>";
							
					if ($source == 'Cmaps') {
						echo "<tr class='center'>";
							echo "<td class='oddGrey' style='width:4%; '>Map Number</td>";
							echo "<td class='oddGrey' style='width:5%;'>Pi Number</td>";
							echo "<td class='oddGrey' style='width:3%;'>Month</td>";
							echo "<td class='oddGrey' style='width:3%;'>Brand</td>";
							echo "<td class='oddGrey' style='width:3%;'>Sales Rep</td>";
							echo "<td class='oddGrey' style='width:5%;'>Agency</td>";
							echo "<td class='oddGrey' style='width:5%;'>Client</td>";
							echo "<td class='oddGrey' style='width:5%;'>Product</td>";
							echo "<td class='oddGrey' style='width:8%;'>Segment</td>";
							echo "<td class='oddGrey' style='width:5%;'>Media Type</td>";
							/*echo "<td class='oddGrey' style='width:5%;'>Log</td>";*/
							echo "<td class='oddGrey' style='width:3%;'>Discount</td>";
							echo "<td class='oddGrey' style='width:8%;'>Sector</td>";
							echo "<td class='oddGrey' style='width:8%;'>Category</td>";
							echo "<td class='oddGrey' style='width:5%;'>Revenue</td>";
						echo "</tr>";

						echo "<tr style='font-size:14px;'>";
								for ($t=0; $t <sizeof($total) ; $t++){ 
									echo "<td class='darkBlue center'>Total</td>";
									echo "<td class='darkBlue' colspan='9'></td>";
									echo "<td class='darkBlue center'>".number_format($total[$t]['averageDiscount'])."%</td>";
									echo "<td class='darkBlue' colspan='2'></td>";
									if ($value == 'GROSS') {
										echo "<td class='darkBlue center' >".number_format($total[$t]['sumGrossRevenue'],0,",",".")."</td>";
									}else{
										echo "<td class='darkBlue'>".number_format($total[$t]['sumNetRevenue'],0,",",".")."</td>";
									}
								}	
						echo"</tr>";						
								
						

						for ($m=0; $m <sizeof($mtx) ; $m++) {
							if ($m%2 == 0) {
								$color = 'even';
							}else{
								$color = 'medBlue';
							}
							echo "<tr class='center' style='font-size:11px;'>";
								echo "<td class='$color' > ".$mtx[$m]['mapNumber']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['piNumber']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['month']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['brand']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['salesRep']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['agency']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['client']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['product']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['segment']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['mediaType']."</td>";
								/*echo "<td class='$color'> ".$mtx[$m]['log']."</td>";*/
								echo "<td class='$color'> ".$mtx[$m]['discount']."%</td>";
								echo "<td class='$color'> ".ucwords(strtolower($mtx[$m]['sector']))."</td>";
								echo "<td class='$color'> ".ucwords(strtolower($mtx[$m]['category']))."</td>";
								if ($value == 'GROSS') {
									echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
								}else{
									echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
								}
								

							echo "</tr>";
						}
					}/*elseif ($source == 'BTS') {
						echo "<tr class='center'>";
							echo "<td class='grey' style='width:6%; '>Order Reference</td>";
							echo "<td class='grey' style='width:5%;'>Campaign Reference</td>";
							echo "<td class='grey' style='width:3%;'>Year</td>";
							echo "<td class='grey' style='width:3%;'>Month</td>";
							echo "<td class='grey' style='width:5%;'>Brand</td>";
							echo "<td class='grey' style='width:5%;'>Sales Rep</td>";
							echo "<td class='grey' style='width:3%;'>Agency</td>";
							echo "<td class='grey' style='width:5%;'>Client</td>";
							echo "<td class='grey' style='width:5%;'>Client Product</td>";
							echo "<td class='grey' style='width:5%;'>Spot Duration</td>";
							echo "<td class='grey' style='width:5%;'>impression Duration</td>";
							echo "<td class='grey' style='width:5%;'>Num Spot</td>";							
							echo "<td class='grey' style='width:5%;'>Revenue</td>";
						echo "</tr>";	

						for ($m=0; $m <sizeof($mtx) ; $m++) {
							if ($m%2 == 0) {
								$color = 'even';
							}else{
								$color = 'medBlue';
							}	

						echo "<tr class='center' style='font-size:15px;'>";
								echo "<td class='$color' > ".$mtx[$m]['orderReference']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['campaignReference']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['year']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['month']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['brand']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['salesRepName']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['agency']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['client']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['clientProduct']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['spotDuration']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['impressionDuration']."</td>";
								echo "<td class='$color'> ".$mtx[$m]['numSpot']."</td>";
								if ($value == 'GROSS') {
									echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
								}else{
									echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
								}
						echo "</tr>";

						}			
					}elseif ($source = 'FreeWheel') {
						echo "<tr class='center'>";
							echo "<td class='grey' style='width:6%; '>Insertion Order</td>";
							echo "<td class='grey' style='width:5%;'>Insertion Order ID</td>";
							echo "<td class='grey' style='width:3%;'>Year</td>";
							echo "<td class='grey' style='width:3%;'>Month</td>";
							echo "<td class='grey' style='width:5%;'>Io Start Date</td>";
							echo "<td class='grey' style='width:5%;'>Io End Date</td>";
							echo "<td class='grey' style='width:3%;'>Brand</td>";
							echo "<td class='grey' style='width:5%;'>Placement</td>";
							echo "<td class='grey' style='width:5%;'>Sales Rep</td>";
							echo "<td class='grey' style='width:3%;'>Agency</td>";
							echo "<td class='grey' style='width:5%;'>Client</td>";
							echo "<td class='grey' style='width:5%;'> Ad Unit</td>";
							echo "<td class='grey' style='width:5%;'>Buy Type</td>";
							echo "<td class='grey' style='width:5%;'>Agency Commission</td>";
							echo "<td class='grey' style='width:5%;'> Sales Rep Commission</td>";
							echo "<td class='grey' style='width:5%;'>Commission</td>";
							echo "<td class='grey' style='width:5%;'> Revenue
							</td>";
						
						echo "</tr>";

						for ($m=0; $m <sizeof($mtx) ; $m++) {
							if ($m%2 == 0) {
								$color = 'even';
							}else{
								$color = 'medBlue';
							}

						echo "<tr class='center' style='font-size:10px;'>";
							echo "<td class='$color' > ".$mtx[$m]['insertionOrder']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['insertionOrderId']."</td>";	
							echo "<td class='$color'>".$mtx[$m]['year']."</td>";
							echo "<td class='$color'>".$mtx[$m]['month']."</td>";
							echo "<td class='$color'>".$mtx[$m]['ioStartDate']."</td>";
							echo "<td class='$color'>".$mtx[$m]['ioEndDate']."</td>";
							echo "<td class='$color'>".$mtx[$m]['brand']."</td>";
							echo "<td class='$color'>".$mtx[$m]['placement']."</>";
							echo "<td class='$color'>".$mtx[$m]['salesRep']."</td>";
							echo "<td class='$color'>".$mtx[$m]['agency']."</td>";
							echo "<td class='$color'>".$mtx[$m]['client']."</td>";
							echo "<td class='$color'>".$mtx[$m]['adUnit']."</td>";
							echo "<td class='$color'>".$mtx[$m]['buyType']."</td>";
							echo "<td class='$color'>".$mtx[$m]['agencyCommissionPercentage']."%</td>";
							echo "<td class='$color'>".$mtx[$m]['repCommissionPercentage']."%</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['commission'],0,",",".")."</td>";
							if ($value == 'GROSS') {
								echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
							}else{
								echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
							}

						echo "</tr>";

						}

					}elseif ($source == 'SalesForce') {
						echo "<tr class='center'>";
							echo "<td class='grey' style='width:6%; '>Oppid</td>";
							echo "<td class='grey' style='width:6%; '>Oportunity Name</td>";


						echo "</tr>";

						for ($m=0; $m <sizeof($mtx) ; $m++) {
							if ($m%2 == 0) {
								$color = 'even';
							}else{
								$color = 'medBlue';
							}
						echo "<tr class='center' style='font-size:10px;'>";
							echo "<td class='$color' > ".$mtx[$m]['oppid']."</td>";

						}

					}*/	
				
			echo "</table>";

	}
}
