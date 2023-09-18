<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\Base;

class baseRender extends Render{

	public function assemble($mtx,$months,$year,$regions,$brand,$source,$currencies,$total){
		//var_dump($currencies);

		//$newValue = strtoupper($value);

		if ($source == 'SF') {
			$source = "SalesForce";
		}elseif ($source == 'FW') {
			$source = "FreeWheel";
		}elseif ($source == 'CMAPS') {
			$source = "Cmaps";
		}elseif ($source == 'IBMS/BTS') {
			$source = "BTS";
		}elseif($source == 'ALEPH'){
			$source = 'Aleph';
		}
		
		echo "<table style='width: 100%;'>";
						
				if ($source == 'Cmaps') {
					echo "<tr>";	
						echo "<th class='newBlue center' colspan='15' style='font-size:22px; width:100%;'> $regions - Viewer $source $year - ($currencies) </th>";
					echo "</tr>";


					echo "<tr class='center'>";
						echo "<td class='rcBlue' style='width:8%; '>Map Number</td>";
						echo "<td class='rcBlue' style='width:8%;'>Pi Number</td>";
						echo "<td class='rcBlue' style='width:3%;'>Month</td>";
						echo "<td class='rcBlue' style='width:3%;'>Brand</td>";
						echo "<td class='rcBlue' style='width:8%;'>Sales Rep</td>";
						echo "<td class='rcBlue' style='width:10%;'>Agency</td>";
						echo "<td class='rcBlue' style='width:10%;'>Client</td>";
						echo "<td class='rcBlue' style='width:8%;'>Product</td>";
						echo "<td class='rcBlue' style='width:8%;'>Media Type</td>";
						echo "<td class='rcBlue' style='width:3%;'>Discount</td>";
						echo "<td class='rcBlue' style='width:8%;'>Sector</td>";
						echo "<td class='rcBlue' style='width:8%;'>Category</td>";						
						echo "<td class='rcBlue' style='width:8%;'>Revenue</td>";
						echo "<td class='rcBlue' style='width:8%;'>Net Revenue</td>";
					echo "</tr>";

					echo "<tr style='font-size:14px;'>";
						//for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td class='darkBlue center'>Total</td>";
							echo "<td class='darkBlue' colspan='8'></td>";
							echo "<td class='darkBlue center'>".number_format($total['averageDiscount'])."%</td>";
							echo "<td class='darkBlue' colspan='2'></td>";							
							echo "<td class='darkBlue center' >".number_format($total['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td class='darkBlue center' >".number_format($total['sumNetRevenue'],0,",",".")."</td>";
						//}	
					echo"</tr>";

					for ($m=0; $m < sizeof($mtx); $m++) {
						
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}

						echo "<tr class='center' style='font-size:13px;'>";
							echo "<td class='$color' > ".$mtx[$m]['mapNumber']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['piNumber']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['month']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['brand']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['salesRep']."</td>";							
							echo "<td class='$color'> ".$mtx[$m]['agency']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['client']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['product']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['mediaType']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['discount']."%</td>";
							echo "<td class='$color'> ".ucwords(strtolower($mtx[$m]['sector']))."</td>";
							echo "<td class='$color'> ".ucwords(strtolower($mtx[$m]['category']))."</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
						echo "</tr>";
					}
				}elseif ($source == 'BTS') {
					echo "<tr>";	
						echo "<th class='newBlue center' colspan='12' style='font-size:22px; width:100%;'> $regions - Viewer $source $year - ($currencies) </th>";
					echo "</tr>";

					echo "<tr class='center'>";
						echo "<td class='rcBlue' style='width:6%; '>Order Reference</td>";
						echo "<td class='rcBlue' style='width:5%;'>Campaign Reference</td>";
						echo "<td class='rcBlue' style='width:3%;'>Year</td>";
						echo "<td class='rcBlue' style='width:3%;'>Month</td>";
						echo "<td class='rcBlue' style='width:5%;'>Brand</td>";
						echo "<td class='rcBlue' style='width:5%;'>Sales Rep</td>";
						echo "<td class='rcBlue' style='width:3%;'>Agency</td>";
						echo "<td class='rcBlue' style='width:5%;'>Client</td>";
						echo "<td class='rcBlue' style='width:5%;'>Client Product</td>";
						//echo "<td class='rcBlue' style='width:5%;'>Spot Duration</td>";
						//echo "<td class='rcBlue' style='width:5%;'>Impression Duration</td>";
						echo "<td class='rcBlue' style='width:5%;'>Num Spot</td>";							
						echo "<td class='rcBlue' style='width:5%;'>Gross Revenue</td>";
						echo "<td class='rcBlue' style='width:5%;'>Net Revenue</td>";
					echo "</tr>";	

					echo "<tr style='font-size:14px;'>";
						//for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td class='darkBlue center'>Total</td>";
							echo "<td class='darkBlue' colspan='9'></td>";
							echo "<td class='darkBlue center' >".number_format($total['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td class='darkBlue center' >".number_format($total['sumNetRevenue'],0,",",".")."</td>";
						//}	
					echo"</tr>";

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
							//echo "<td class='$color'> ".$mtx[$m]['spotDuration']."</td>";
							//echo "<td class='$color'> ".$mtx[$m]['impressionDuration']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['numSpot']."</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
						
					echo "</tr>";


					}			
				}elseif ($source == 'SalesForce') {
					echo "<tr>";	
						echo "<th class='newBlue center' colspan='16' style='font-size:22px; width:100%;'> $regions - Viewer $source $year - ($currencies) </th>";
					echo "</tr>";

					echo "<tr class='center'>";
						echo "<td class='rcBlue' style='width:6%; '>Oppid</td>";
						echo "<td class='rcBlue' style='width:6%; '>Oportunity Name</td>";
						echo "<td class='rcBlue' style='width:6%; '>Brand</td>";
						echo "<td class='rcBlue' style='width:3%;'>Agency</td>";
						echo "<td class='rcBlue' style='width:5%;'>Client</td>";
						echo "<td class='rcBlue' style='width:5%;'>Sales Rep Owner</td>";
						echo "<td class='rcBlue' style='width:5%;'>Sales Rep Splitted</td>";
						echo "<td class='rcBlue' style='width:5%;'>From Date</td>";
						echo "<td class='rcBlue' style='width:5%;'>To Date</td>";
						echo "<td class='rcBlue' style='width:5%;'>Stage</td>";
						echo "<td class='rcBlue' style='width:5%;'>Agency Commission</td>";
						echo "<td class='rcBlue' style='width:5%;'>Amount Gross</td>";
						echo "<td class='rcBlue' style='width:5%;'>Amount Net</td>";
						/*echo "<td class='rcBlue' style='width:5%;'>Year From</td>";
						echo "<td class='rcBlue' style='width:5%;'>Year To</td>";*/
					echo "</tr>";

					echo "<tr style='font-size:14px;'>";
						//for ($t=0; $t <sizeof($total) ; $t++){ 
							echo "<td class='darkBlue center'>Total</td>";
							echo "<td class='darkBlue' colspan='10'></td>";
							echo "<td class='darkBlue center' >".number_format($total['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td class='darkBlue center' >".number_format($total['sumNetRevenue'],0,",",".")."</td>";
						//}	
					echo"</tr>";

					for ($m=0; $m <sizeof($mtx) ; $m++) {
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}
						echo "<tr class='center' style='font-size:10px;'>";
							echo "<td class='$color' > ".$mtx[$m]['oppid']."</td>";
							echo "<td class='$color' > ".$mtx[$m]['opportunityName']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['brand']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['agency']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['client']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['salesRepOwner']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['salesRepSplitter']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['fromDate']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['toDate']."</td>";
							if ($mtx[$m]['stage'] == 1) {
								echo "<td class='$color'> 1 - Qualification </td>";
							}elseif ($mtx[$m]['stage'] == 2) {
								echo "<td class='$color'> 2 - Proposal </td>";
							}elseif ($mtx[$m]['stage'] == 3){
								echo "<td class='$color'> 3 - Negotiation </td>";
							}elseif ($mtx[$m]['stage'] == 4){
								echo "<td class='$color'> 4 - Verbal </td>";
							}elseif ($mtx[$m]['stage'] == 5){
								echo "<td class='$color'> 5 - Closed Won </td>";
							}
							echo "<td class='$color'> ".$mtx[$m]['agencyCommission']."%</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['fcstAmountGross'],0,",",".")."</td>";
							echo "<td class='$color'>".number_format($mtx[$m]['fcstAmountNet'],0,",",".")."</td>";
							/*echo "<td class='$color'> ".$mtx[$m]['yearFrom']."</td>";
							echo "<td class='$color'> ".$mtx[$m]['yearTo']."</td>";*/

						echo "</tr>";
						

					}

				}elseif ($source == 'Aleph') {
					echo "<tr>";	
						echo "<th class='newBlue center' colspan='11' style='font-size:22px; width:100%;'> $regions - Viewer $source $year - ($currencies) </th>";
					echo "</tr>";

					echo "<tr class='center'>";
						echo "<td class='rcBlue' style='width:3%;'>Year</td>";
						echo "<td class='rcBlue' style='width:3%;'>Month</td>";
						echo "<td class='rcBlue' style='width:3%;'>Brand</td>";
						echo "<td class='rcBlue' style='width:5%;'>Previous AE</td>";
						echo "<td class='rcBlue' style='width:5%;'>Current AE</td>";
						echo "<td class='rcBlue' style='width:5%;'>Client</td>";
						echo "<td class='rcBlue' style='width:3%;'>Agency</td>";
						echo "<td class='rcBlue' style='width:5%;'>Feed Type</td>";
						echo "<td class='rcBlue' style='width:5%;'>Feed Code</td>";
						echo "<td class='rcBlue' style='width:5%;'>Gross Revenue</td>";
						echo "<td class='rcBlue' style='width:5%;'>Net Revenue</td>";					
					echo "</tr>";

					echo "<tr style='font-size:14px;'>";
							echo "<td class='darkBlue center'>Total</td>";
							echo "<td class='darkBlue' colspan='8'></td>";
							echo "<td class='darkBlue center' >".number_format($total['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td class='darkBlue center' >".number_format($total['sumNetRevenue'],0,",",".")."</td>";
					echo"</tr>";

					for ($m=0; $m <sizeof($mtx) ; $m++) {
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}

					echo "<tr class='center' style='font-size:12px;'>";
						echo "<td class='$color'>".$mtx[$m]['year']."</td>";
						echo "<td class='$color'>".$mtx[$m]['month']."</td>";
						echo "<td class='$color'>".$mtx[$m]['brand']."</td>";
						echo "<td class='$color'>".$mtx[$m]['oldRep']."</>";
						echo "<td class='$color'>".$mtx[$m]['salesRep']."</td>";
						echo "<td class='$color'>".$mtx[$m]['client']."</td>";
						echo "<td class='$color'>".$mtx[$m]['agency']."</td>";
						echo "<td class='$color'>".$mtx[$m]['feedType']."</td>";
						echo "<td class='$color'>".$mtx[$m]['feedCode']."</td>";
						echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
						echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
					}
					echo "</tr>";


				}elseif ($source == 'WBD') {
					echo "<tr>";	
						echo "<th class='newBlue center' colspan='16' style='font-size:22px; width:100%;'> $regions - Viewer $source $year - ($currencies) </th>";
					echo "</tr>";

					echo "<tr class='center'>";
						echo "<td class='rcBlue' style='width:5%;'>Company</td>";
						echo "<td class='rcBlue' style='width:3%;'>Year</td>";
						echo "<td class='rcBlue' style='width:3%;'>Month</td>";
						echo "<td class='rcBlue' style='width:5%;'>Previous AE</td>";
						echo "<td class='rcBlue' style='width:5%;'>Client</td>";
						echo "<td class='rcBlue' style='width:3%;'>Agency</td>";
						echo "<td class='rcBlue' style='width:3%;'>Platform</td>";
						echo "<td class='rcBlue' style='width:3%;'>Brand</td>";
						echo "<td class='rcBlue' style='width:3%;'>Feed Code</td>";
						echo "<td class='rcBlue' style='width:3%;'>Order</td>";
						echo "<td class='rcBlue' style='width:3%;'>Pi Number</td>";
						echo "<td class='rcBlue' style='width:3%;'>Property</td>";
						echo "<td class='rcBlue' style='width:5%;'>Core</td>";
						echo "<td class='rcBlue' style='width:5%;'>Current AE</td>";
						echo "<td class='rcBlue' style='width:5%;'>Gross Revenue</td>";
						echo "<td class='rcBlue' style='width:5%;'>Net Revenue</td>";					
					echo "</tr>";

					echo "<tr style='font-size:14px;'>";
							echo "<td class='darkBlue center'>Total</td>";
							echo "<td class='darkBlue' colspan='13'></td>";
							echo "<td class='darkBlue center' >".number_format($total['sumGrossRevenue'],0,",",".")."</td>";
							echo "<td class='darkBlue center' >".number_format($total['sumNetRevenue'],0,",",".")."</td>";
					echo"</tr>";

					for ($m=0; $m <sizeof($mtx) ; $m++) {
						if ($m%2 == 0) {
							$color = 'even';
						}else{
							$color = 'medBlue';
						}

					echo "<tr class='center' style='font-size:12px;'>";
						echo "<td class='$color'>".$mtx[$m]['company']."</td>";
						echo "<td class='$color'>".$mtx[$m]['year']."</td>";
						echo "<td class='$color'>".$mtx[$m]['month']."</td>";
						echo "<td class='$color'>".$mtx[$m]['oldRep']."</>";
						echo "<td class='$color'>".$mtx[$m]['client']."</td>";
						echo "<td class='$color'>".$mtx[$m]['agency']."</td>";
						echo "<td class='$color'>".$mtx[$m]['feedType']."</td>";
						echo "<td class='$color'>".$mtx[$m]['brand']."</td>";
						echo "<td class='$color'>".$mtx[$m]['feedCode']."</td>";
						echo "<td class='$color'>".$mtx[$m]['internalCode']."</td>";
						echo "<td class='$color'>".$mtx[$m]['piNumber']."</td>";
						echo "<td class='$color'>".$mtx[$m]['property']."</td>";
						echo "<td class='$color'>".$mtx[$m]['manager']."</td>";
						echo "<td class='$color'>".$mtx[$m]['salesRep']."</td>";						
						echo "<td class='$color'>".number_format($mtx[$m]['grossRevenue'],0,",",".")."</td>";
						echo "<td class='$color'>".number_format($mtx[$m]['netRevenue'],0,",",".")."</td>";
					}
					echo "</tr>";


				}
			
		echo "</table>";

	}
}
