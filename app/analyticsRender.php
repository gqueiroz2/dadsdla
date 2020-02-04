<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class analyticsRender extends Model{
    
	public function panel($info){

		$lastHourV = $info["lastHourV"];
        $lastDayV = $info["lastDayV"];
        $lastWeekV = $info["lastWeekV"];
        $last15DaysV = $info["last15DaysV"];
        $allV = $info["allV"];

        $last15Days = $info["last15Days"];
        $lastFifteenDaysV = $info["lastFifteenDaysV"];
        $lastSevenDays = $info["lastSevenDays"];
        $lastSevenDaysV = $info["lastSevenDaysV"];
        $lastSevenDaysA = $info["lastSevenDaysA"];
        $lastDay = $info["lastDay"];
        $lastHour = $info["lastHour"];

        $regions = $info["regions"];
        $visitsByRegion = $info["visitsByRegion"];

        $all = $info["all"];

        echo "<div class='container-fluid'>";
        	echo "<div class='row mt-2'>";        		
			    
        		echo "<div class='col-6'>";
        			echo "<div class='container-fluid'>";
		        		echo "<div class='row justify-content-center'>";
		        			echo "<div class='col'>";
			        			echo "<center>";
			        				echo "<span style='font-size:20px; font-weight:bold;'> Visits of Last : </span>";
			        			echo "</center>";
			        		echo "</div>";
			        	echo "</div>";
        				echo "<div class='row mt-2'>";
			        		echo "<div class='col-3'>";
				        		echo "<div class='card'>";
				                    echo "<div class='card-header'>";
				                        echo "<center><span> <b> 15 <br> Days </b> </span></center>";
				                    echo "</div>";
				                    echo "<div class='card-body' style='text-align:center;'>";
				                    	echo "<span style='font-size:22px; font-weight:bold;'>".$last15DaysV."</span>";
				                	echo "</div>";
				                echo "</div>";
			        		echo "</div>";
			        		echo "<div class='col-3'>";
				        		echo "<div class='card'>";
				                    echo "<div class='card-header'>";
				                        echo "<center><span> <b> 7 <br> Days </b> </span></center>";
				                    echo "</div>";
				                    echo "<div class='card-body' style='text-align:center;'>";
				                    	echo "<span style='font-size:22px; font-weight:bold;'>".$lastSevenDaysV."</span>";
				                	echo "</div>";
				                echo "</div>";
			        		echo "</div>";
			        		echo "<div class='col-3'>";
				        		echo "<div class='card'>";
				                    echo "<div class='card-header'>";
				                        echo "<center><span> <b> 24 <br> Hours </b> </span></center>";
				                    echo "</div>";
				                    echo "<div class='card-body' style='text-align:center;'>";
				                    	echo "<span style='font-size:22px; font-weight:bold;'>".$lastDayV."</span>";
				                	echo "</div>";
				                echo "</div>";
			        		echo "</div>";
			        		echo "<div class='col-3'>";
				        		echo "<div class='card'>";
				                    echo "<div class='card-header'>";
				                        echo "<center><span> <b> Last <br> Hour </b> </span></center>";
			                    	echo "</div>";
				                    echo "<div class='card-body' style='text-align:center;'>";
				                    	echo "<span style='font-size:22px; font-weight:bold;'>".$lastHourV."</span>";
				                	echo "</div>";
				                echo "</div>";
			        		echo "</div>";

			        	echo "</div>";
			        echo "</div>";
			    echo "</div>";

			    echo "<div class='col-6'>";
        			echo "<div class='container-fluid'>";
        				echo "<div class='row justify-content-center'>";
		        			echo "<div class='col'>";
			        			echo "<center>";
			        				echo "<span style='font-size:20px; font-weight:bold;'> Visits By Region: </span>";
			        			echo "</center>";
			        		echo "</div>";
			        	echo "</div>";
        				echo "<div class='row mt-2'>";
			        		$head = array("Country","15 Days","7 Days","24 Hours","Last Hour");
			        		echo "<div class='col'>";
				        		echo "<div class='card'>";
				                	echo "<div class='card-body' style='text-align:center;'>";
				                    	echo "<table class='table' style='width:100%;'>";
				                    		echo "<tr>";
					                    		for ($h=0; $h < sizeof($head); $h++) { 
					                    			echo "<td>".$head[$h]."<td>";	                    			
					                    		}
				                    		echo "</tr>";
					                    	for ($r=0; $r < sizeof($regions); $r++) { 
					                    		echo "<tr>";

					                    			if($regions[$r] == "Brazil"){
					                    				$source = '/flags/brasilFlag.png';
					                    			}elseif($regions[$r] == "Argentina"){
					                    				$source = '/flags/argFlag.png';
					                    			}elseif($regions[$r] == "Colombia"){
					                    				$source = '/flags/colombiaFlag.png';
					                    			}elseif($regions[$r] == "Mexico"){
					                    				$source = '/flags/mexicoFlag.png';
					                    			}elseif($regions[$r] == "Miami"){
					                    				$source = '/flags/usaFlag.png';
					                    			}

					                    			echo "<td>";

					                    			echo "<img src='".$source."' width='25'>";

					                    			echo "<td>";
					                    			echo "<td>".$visitsByRegion['last15DaysVR'][$r]."<td>";
					                    			echo "<td>".$visitsByRegion['lastSevenDaysVR'][$r]."<td>";
					                    			echo "<td>".$visitsByRegion['lastDayVR'][$r]."<td>";
					                    			echo "<td>".$visitsByRegion['lastHourVR'][$r]."<td>";
					                    		echo "</tr>";
					                    	}
				                    	echo "</table>";
				                	echo "</div>";
				                echo "</div>";
			        		echo "</div>";
			        	echo "</div>";
			        echo "</div>";
			    echo "</div>";

			    

        	echo "</div>";
        echo "</div>";


	}


}
