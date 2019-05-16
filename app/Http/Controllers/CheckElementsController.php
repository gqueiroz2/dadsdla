<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\CheckElements;

class CheckElementsController extends Controller{
    
	public function base(){

		$db = new dataBase();
		$cE = new CheckElements();

		$conDLA = $db->openConnection('DLA');	
		$con = $db->openConnection('firstMatch');	

		$table = Request::get('table');

		$newValues = $cE->newValues($conDLA,$con,$table);

		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

		echo "<table class='table' style='width:100%;'>";
		for ($d=0; $d < sizeof($dependencies); $d++) { 
		
			if($newValues[$dependencies[$d]]){
				for ($n=0; $n < sizeof($newValues[$dependencies[$d]]); $n++) { 
					echo "<tr>";
						echo "<td> Create the value <span style='color:red'>".$newValues[$dependencies[$d]][$n]."</span> for the table ".$dependencies[$d]."</td>";
					echo "</tr>";
				}
				
			}else{
				echo "<tr><td><span style='color:green'> There are values of ".$dependencies[$d]." to be Created </span></td></tr>";
			}

			
		}
		echo "</table>";


	}
/*
	public function basicPost(){

	}
*/
}
