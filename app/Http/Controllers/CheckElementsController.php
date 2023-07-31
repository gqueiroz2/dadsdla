<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\CheckElements;
use App\RenderStuff;
use App\base;

class CheckElementsController extends Controller{
    
	public function base(){
		$rS = new RenderStuff();
		$db = new dataBase();
		$cE = new CheckElements();
		$base = new base();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$fM = $db->matchesConnection("first");
		$conFM = $db->openConnection($fM);	

		$table = Request::get('table');
		$region = Request::get('region');
		$newValues = $cE->newValues($con,$conFM,$region,$table);
		
		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');
		return view('dataManagement.Chain.pendingStuff',compact('base','rS','con','newValues','dependencies','table','region'));
	}

	public function check(){

		$rS = new RenderStuff();
		$db = new dataBase();
		$cE = new CheckElements();
		$base = new base();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $fM = $db->matchesConnection("first");
		$conFM = $db->openConnection($fM);	
		$table = Request::get('tableToCheck');

		$newValues = $cE->newValuesNoRegion($con,$conFM,$table);

		//var_dump($newValues);

		echo "<center>
				<table class='table table-triped'>";
		echo "<tr> 
				<td><center> Type </center></td>
				<td><center> Status </center></td>
		      </tr>";
		echo "<tr> 
				<td><center> Brands </center></td>
				<td><center>";
				if(!$newValues['brands']){
					echo "NO";
				}else{
					if($table != 'sf_pr'){
						echo "<div class='container-fluid'>";
						for ($b=0; $b < sizeof($newValues['brands']); $b++) { 
							echo "<div class='row'>";
								echo "<div class='col'>".$newValues['brands'][$b]."</div>";
							echo "</div>";
						}
						echo "</div>";
					}
				}
				echo "</center></td>";
      	echo "</tr>";

      	echo "<tr> 
				<td><center> Sales Rep </center></td>
				<td><center>";
				if(!$newValues['salesReps']){
					echo "NO";
				}else{
					echo "<div class='container-fluid'>";
					for ($s=0; $s < sizeof($newValues['salesReps']); $s++) { 
						echo "<div class='row'>";
							echo "<div class='col'>".$newValues['salesReps'][$s]."</div>";
						echo "</div>";
					}
					echo "</div>";
				}
				echo "</center></td>";
      	echo "</tr>";

		echo "<tr> 
				<td><center> Clients </center></td>
				<td><center>";
				if(!$newValues['clients']){
					echo "NO";
				}else{
					echo "<div class='container-fluid'>";
					for ($c=0; $c < sizeof($newValues['clients']); $c++) { 
						echo "<div class='row'>";
						if ($table == 'cmaps') {
							echo "<div class='col'>".$newValues['clients'][$c]['client']."</div>";
						}else{
							echo "<div class='col'>".$newValues['clients'][$c]."</div>";
						}
						echo "</div>";
					}
					echo "</div>";
				}
				echo "</center></td>";
		      echo "</tr>";
		echo "<tr> 
				<td><center> Agencies </center></td>
				<td><center>";
				if(!$newValues['agencies']){
					echo "NO";
				}else{
					echo "<div class='container-fluid'>";
					for ($a=0; $a < sizeof($newValues['agencies']); $a++) { 
						echo "<div class='row'>";
						if ($table == 'cmaps') {
							echo "<div class='col'>".$newValues['agencies'][$a]['agency']."</div>";
						}else{
							echo "<div class='col'>".$newValues['agencies'][$a]."</div>";
						}
						echo "</div>";
					}
					echo "</div>";
				}
				echo "</center></td>";
		      echo "</tr>";
		echo "</table>
		      </center>";

		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

	}
}
