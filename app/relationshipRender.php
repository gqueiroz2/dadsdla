<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;


class relationshipRender extends Render{
    
	public function construct($type,$structure){
		echo "<table style ='width:100%;'>";
			if($type == "client"){
				$this->constructClient($structure);
			}else{
				$this->constructAgency($structure);
			}

		echo "</table>";


	}

	public function constructAgency($structure){
		echo "<tr>";
			echo "<th class='darkBlue'><center> Agency Group </center></th>";
			echo "<th class='darkBlue'><center> Agency </center></th>";
			echo "<th class='darkBlue'><center> Agency Unit </center></th>";
		echo "</tr>";

		for ($s=0; $s < sizeof($structure); $s++) { 
			if($s%2 == 0){
				$clr = 'rcBlue';
			}else{
				$clr = 'odd';
			}
			
			if($structure[$s]['agency']){
				if($structure[$s]['agency']){
					$tam[$s] = 0;
				}else{
					$tam[$s] = 1;
				}

				$sizeU = 0;

				for ($t=0; $t < sizeof($structure[$s]['agency']); $t++) { 					
					if($structure[$s]['agency'][$t]['agencyUnit']){
						$tam[$s] += sizeof($structure[$s]['agency'][$t]['agencyUnit']);
						$sizeU++;
					}
				}

				if(sizeof($structure[$s]['agency']) != $sizeU){
					$xiz = sizeof($structure[$s]['agency']) - $sizeU;
					$tam[$s] += $xiz;


				}

				if($structure[$s]['agencyGroup']){
					for ($t=0; $t < sizeof($structure[$s]['agency']); $t++) { 					
						
						if($structure[$s]['agency'][$t]['agencyUnit']){
							$tam2[$s][$t] = sizeof($structure[$s]['agency'][$t]['agencyUnit']);
						}else{
							$tam2[$s][$t] = "";
						}

						if($structure[$s]['agency'][$t]['agencyUnit']){
							for ($u=0; $u < sizeof($structure[$s]['agency'][$t]['agencyUnit']); $u++) { 
								
								if(sizeof($structure[$s]['agency']) == $sizeU){
									echo "<tr>";
								}
									if($t == 0 && $u == 0){	
										echo "<td class='".$clr."' rowspan='".($tam[$s])."'>
													<center>".$structure[$s]['agencyGroup']."</center>
											  </td>";
									}
									if($u == 0){
										echo "<td class='".$clr."' rowspan='".$tam2[$s][$t]."'>
													<center>".$structure[$s]['agency'][$t]['name']."</center>
											  </td>";
									}

									echo "<td class='".$clr."'><center>".$structure[$s]['agency'][$t]['agencyUnit'][$u]."</center></td>";
								
								echo "</tr>";
							}
							if(sizeof($structure[$s]['agency']) != $sizeU){
								echo "</tr>";
							}
						}else{
							if($sizeU == 0){
								echo "<tr>";
									echo "<td class='".$clr."'><center>".$structure[$s]['agencyGroup']."</center></td>";
									echo "<td class='".$clr."'><center>".$structure[$s]['agency'][$t]['name']."</center></td>";
									echo "<td class='".$clr."'><center> - </center></td>";				
								echo "</tr>";
							}else{
								if(sizeof($structure[$s]['agency']) != $sizeU){									
										echo "<tr>";
											if($t==0){
												echo "<td class='".$clr."' rowspan='".($tam[$s])."'>
																<center>".$structure[$s]['agencyGroup']."</center>
														  </td>";
											}
										echo "<td class='".$clr."' rowspan='".$tam2[$s][$t]."'>
														<center>".$structure[$s]['agency'][$t]['name']."</center>
												  </td>";
										echo "<td class='".$clr."'><center> - </center></td>";	
										echo "</tr>";									
								}else{

								}
								
							}
							
						}
					}
				}
			}else{
				
				echo "<tr>";
					echo "<td class='".$clr."' rowspan='".$tam[$s]."'><center>".$structure[$s]['agencyGroup']."</center></td>";				
					echo "<td class='".$clr."'><center> - </center></td>";				
					echo "<td class='".$clr."'><center> - </center></td>";				
				echo "</tr>";
			}
		}
	}

	public function constructClient($structure){
		echo "<tr>";
			echo "<th class='darkBlue' style='width:40%;'><center> Client </center></th>";
			echo "<th class='darkBlue' style='width:60%;'><center> Client Unit </center></th>";
		echo "</tr>";
		for ($s=0; $s < sizeof($structure); $s++) { 
			if($structure[$s]['clientUnit']){
				$tam[$s] = sizeof($structure[$s]['clientUnit']);
			}else{
				$tam[$s] = "";
			}

			if($s%2 == 0){
				$clr = 'rcBlue';
			}else{
				$clr = 'odd';
			}

			if($structure[$s]['clientUnit']){
				for ($t=0; $t < sizeof($structure[$s]['clientUnit']); $t++) { 
					echo "<tr>";
						if($t == 0){
							echo "<td class='".$clr."' rowspan='".$tam[$s]."'><center>".$structure[$s]['client']."</center></td>";				
						}
						echo "<td class='".$clr."'><center>".$structure[$s]['clientUnit'][$t]."</center></td>";
					echo "</tr>";
				}
			}else{
				echo "<tr>";
					echo "<td class='".$clr."' rowspan='".$tam[$s]."'><center>".$structure[$s]['client']."</center></td>";				
					echo "<td class='".$clr."'><center> - </center></td>";				
				echo "</tr>";
			}
		}
	}

	public function base($agencies,$agency){

		echo "<div class='row justify-content-center mt-4'>";
			echo "<div class='col-sm-1'><b> # </b></div>";
			//echo "<div class='col'><b> Current Region </b></div>";
			//echo "<div class='col'><b> New Region </b></div>";
			echo "<div class='col'><b> Currrent Agency.G </b></div>";
			echo "<div class='col'><b> New Agency.G </b></div>";
			echo "<div class='col'><b> Current Agency </b></div>";
			echo "<div class='col'><b> New Agency </b></div>";
			echo "<div class='col'><b> Current Agency.U </b></div>";
		echo "</div>";
		for( $a=0; $a<sizeof($agencies); $a++){
			echo "<div class='row justify-content-center'>";

				echo "<div class='col-sm-1'>"; // NUMER OF THE LINE
					echo "<span>".($a+1)."</span>";
				echo "</div>";
/*
				echo "<div class='col'>"; // CURRENT REGION
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='currentRegion' value='".$agencies[$a]['region']."' readonly='true'>";
				echo "</div>";

				echo "<div class='col'>"; // NEW REGION
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='newRegion' value='' readonly='true'>";
				echo "</div>";	
*/
				echo "<div class='col'>";// CURRENT AGENCY GROUP
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='currentAgencyGroup' value='".$agencies[$a]['agencyGroup']."' readonly='true'>";
					echo "<input type='hidden' name='currentAgencyGroupID' value='".$agencies[$a]['agencyGroupID']."'>";
				echo "</div>";

				echo "<div class='col'>";// NEW AGENCY GROUP
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='agencyGroup' value='' readonly='true'>";
					echo "<input type='hidden' name='newAgencyGroupID' value=''>";
				echo "</div>";

				echo "<div class='col'>";// CURRENT AGENCY
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='currentAgency' value='".$agencies[$a]['agency']."' readonly='true'>";
					echo "<input type='hidden' name='currentAgencyID' value='".$agencies[$a]['agencyID']."'>";
				echo "</div>";

				echo "<div class='col'>";// NEW AGENCY
					echo "<select class='form-control' style='width:100% !important;' id='newAgency-".$a."'  name='newAgency-".$a."'>";
						echo "<option> Select </option>";
					for ($aa=0; $aa < sizeof($agency); $aa++) { 						
						echo "<option value='".$agency[$aa]['id']."'>".$agency[$aa]['agency']." - ".$agency[$aa]['agencyGroup']." </option>";
					}
					
					echo "</select>";
					echo "<input type='hidden' name='newAgencyID' value='".$agencies[$a]['agencyUnitID']."'>";
				echo "</div>";

				echo "<div class='col'>";// AGENCY UNIT
					echo "<input type='text' class='form-control' style='width:100% !important;'  name='agencyUnit' value='".$agencies[$a]['agencyUnit']."' readonly='true'>";
					echo "<input type='hidden' name='agencyUnitID' value='".$agencies[$a]['agencyUnitID']."'>";
				echo "</div>";
					
			echo "</div>";
		}	
		
	}

}
