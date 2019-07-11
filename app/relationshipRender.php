<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;


class relationshipRender extends Model{
    
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
					echo "<select class='form-control' style='width:100% !important;'  name='newAgency'>";
					
					for ($aa=0; $aa < sizeof($agency); $aa++) { 
						echo "<option> Select </option>";
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
		echo "<div class='row justify-content-center'>";
			echo "<div class='col-sm-1'>"; // NUMER OF THE LINE
				echo "<span>".($a+1)."</span>";
			echo "</div>";
		echo "</div>";
	}

}
