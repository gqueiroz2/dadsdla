<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
class dataManagementAjaxController extends Controller{
   	
   	public function salesRepGroupByRegion(){
   		$db = new dataBase();
   		$con = $db->openConnection('DLA');

   		$regionID = Request::get('region');

   		$sql = "SELECT name FROM region WHERE (id = '$regionID')";
   		
   		$result = $con->query($sql);

   		if($result && $result->num_rows > 0){
   			$row = $result->fetch_assoc();
   			$regionName = $row["name"];
   		}

   		var_dump($regionName);

   	}

}
