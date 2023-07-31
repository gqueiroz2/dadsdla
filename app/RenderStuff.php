<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;

class RenderStuff extends Model{
    public function base($con,$base,$table,$newValues,$dependencies,$region){
    	
    	$ag = new agency();
    	$cli = new client();

    	echo "<div class='container-fluid'>";


    		if($base->truncateTableName($table) == 'CMAPS'){
    			echo "<div class='row mt-2 justify-content-end'>
					<div class='col-3'>
						<button class='btn btn-primary' style='width: 100%;' id='agency'> 
							<a href='".route('chainCmaps')."' style='color: white'>
								Chain
							</a>
						</button>
					</div>
				</div>";	
    		}else{
    			echo "<div class='row mt-2 justify-content-end'>
					<div class='col-3'>
						<button class='btn btn-primary' style='width: 100%;' id='agency'> 
							<a href='".route('chain')."' style='color: white'>
								Chain
							</a>
						</button>
					</div>
				</div>";	
    		}    	


			echo "<div class='row'> 
					<div class='col'><center> Table : ".$base->truncateTableName($table)."</center></div>
			      <div>";
		echo "</div class='container-fluid'>";
			for ($d=0; $d < sizeof($dependencies); $d++){ 		
				echo "<div class='container-fluid'>";
					
				if($newValues[$dependencies[$d]]){
					echo "<div class='row row mt-1'>
							<div class='col'> <center> <span style='font-size:22px; font-weight:bold;'> ". ucfirst($dependencies[$d]) ." </span> </center> </div>
					      </div>";
					
					if($dependencies[$d] == 'clients' || $dependencies[$d] == 'agencies'){
						echo "<div class='row'>";
						echo "<div class='col-1'> <center> Register </center> </div>";
						echo "<div class='col-2'> <center> ".ucfirst($dependencies[$d])." Group </center> </div>";
						echo "<div class='col'> <center> ".ucfirst($dependencies[$d])." </div>";
						echo "<div class='col'> <center> ".ucfirst($dependencies[$d])." Unit </center> </div>";
						if($table != "cmaps"){
							echo "<div class='col'> <center> Region </center> </div>";
						}
					    echo "</div>";
					    if($dependencies[$d] == 'clients'){
							echo "<form method='POST' action='".route('insertClient')."'>";
					    }else{
					    	echo "<form method='POST' action='".route('insertAgency')."'>";
					    	
					    }
					    echo "<input type='hidden' name='size' value='".sizeof($newValues[$dependencies[$d]])."'>";
					    echo "<input type='hidden' name='table' value='".$table."'>";
					    echo "<input type='hidden' name='region' value='".$region."'>";
					}else{
						echo "<div class='row'>
							<div class='col-2'> <center> Register </center> </div>
							<div class='col-2'> <center> ".ucfirst($dependencies[$d])." Group </center> </div>
							<div class='col-4'> <center> ".ucfirst($dependencies[$d])." </div>
							<div class='col-4'> <center> ".ucfirst($dependencies[$d])." Unit </center> </div>
					      </div>";
					}
					echo "<input type='hidden' name='_token' value='".csrf_token()."'>";
					
					for ($n=0; $n < sizeof($newValues[$dependencies[$d]]); $n++) { 
						if($dependencies[$d] == 'clients' || $dependencies[$d] == 'agencies' ){
							if($dependencies[$d] == 'agencies'){
								/*
										AGÊNCIAS
								*/
								$agency = $ag->getAllAgenciesByRegion($con,array($region));

								echo "<div class='row mt-1'>";
									echo "<div class='col-1'><center> ".($n+1)." </center></div>";
									echo "<div class='col-2'>";
										echo "<input type='text' readonly='true' class='form-control' name='$dependencies[$d]-group-$n' id='$dependencies[$d]-group-$n' style='width:100%'>";
											
										echo "</select>";
									echo "</div>";
									echo "<div class='col'>";	
										echo "<select class='selectpicker' name='$dependencies[$d]-$n' id='$dependencies[$d]-$n' data-live-search='true' data-width='100%'>";
											echo "<option value=''> Select </option>";
											for ($a=0; $a < sizeof($agency); $a++) { 
												$agencyArray[$a] = 	base64_encode(
																		json_encode(
																 			array(
												                    			'ID' => $agency[$a]['id'],
												                    			'agency_group_id' => $agency[$a]['agencyGroupID'],
												                    			'agencyGroup' => $agency[$a]['agencyGroup']
														                    )
																 		)
																	);
												echo "<option value='".$agencyArray[$a]."'>".
														$agency[$a]['agency']." - ".$agency[$a]['agencyGroup']." - (".$agency[$a]['region'].
												     ")</option>";
											}
											
										echo "</select>";
									echo "</div>";
									if ($table == 'forecast') {
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' name='$dependencies[$d]-unit-$n' value='".$newValues[$dependencies[$d]][$n]['agency_id']."'></div>";
									}else{
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' name='$dependencies[$d]-unit-$n' value='".$newValues[$dependencies[$d]][$n]['agency']."'></div>";	
									}
									
									if($table != "cmaps"){
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' value='".$newValues[$dependencies[$d]][$n]['region']."'></div>";
									}

								echo "</div>";
							}else{
								/*
										CLIENTES
								*/

								$client = $cli->getAllClientsByRegion($con,array($region));

								echo "<div class='row mt-1'>";
									echo "<div class='col-1'><center> ".($n+1)." </center></div>";
									echo "<div class='col-2'>";
										echo "<input type='text' readonly='true' class='form-control' name='$dependencies[$d]-group-$n' id='$dependencies[$d]-group-$n' style='width:100%'>";
											
										echo "</select>";
									echo "</div>";
									echo "<div class='col'>";	
										echo "<select class='selectpicker' name='$dependencies[$d]-$n' id='$dependencies[$d]-$n' data-live-search='true' data-width='100%'>";
											echo "<option value=''> Select </option>";
											for ($a=0; $a < sizeof($client); $a++) { 
												$clientArray[$a] = 	base64_encode(
																		json_encode(
																 			array(
												                    			'ID' => $client[$a]['id'],
												                    			'client_group_id' => $client[$a]['clientGroupID'],
												                    			'clientGroup' => $client[$a]['clientGroup']
														                    )
																 		)
																	);
												echo "<option value='".$clientArray[$a]."'>".
														$client[$a]['client']." - ".$client[$a]['clientGroup']." - (".$client[$a]['region'].
												     ")</option>";
											}
											
										echo "</select>";
									echo "</div>";
									if ($table == 'forecast') {
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' name='$dependencies[$d]-unit-$n' value=\"". $newValues[$dependencies[$d]][$n]['client_id']."\"></div>";
									}else{
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' name='$dependencies[$d]-unit-$n' value=\"". $newValues[$dependencies[$d]][$n]['client']."\"></div>";
									}
									
									if($table != "cmaps"){
										echo "<div class='col'><input type='text' class='form-control' readonly='true' style='width:100%;' value='".$newValues[$dependencies[$d]][$n]['region']."'></div>";
									}

								echo "</div>";
							}
						}else{
							//var_dump($newValues[$dependencies[$d]]);
						}
					}
					echo "<div class='row mt-2'>
							<div class='col'>
								<input type='submit' value='Save' class='btn btn-primary' style='width: 100%;'>	
							</div>
					      </div>";
					echo "</form>";

					echo "<br><hr><br>";
					
				}else{
					echo "<div class='row'><div class='col'><center><span style='color:green'> There are no values of ".$dependencies[$d]." to be Created </span></center></div></div>";
				}			
				echo "</div>";
			}
		
    }
}
