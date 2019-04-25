<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\salesRep;
use App\region;
use App\sql;

class dataManagementAjaxController extends Controller{
   	
      public function subLevelGroupByRegion(){
         $region = new region();
         $sr = new SalesRep();
         $sql = new sql();
         $db = new dataBase();
         $con = $db->openConnection('DLA');         
         $regionID = array(Request::get('regionID'));

<<<<<<< HEAD
         var_dump($regionID);

=======
         //$result = 

         //$to = array('id','name');
         //$from = $to;

>>>>>>> 1d73c4be6a9953f481648e9dd6a713facbadf1f4
         $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);

         echo "<option value=''> Select </option>";
         for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
            echo "<option value='".$salesRepGroup[$s]['id']."'>".$salesRepGroup[$s]['name']."</option>";
         }

      }

      public function salesRepBySalesRepGroup(){
         $db = new dataBase();
         $con = $db->openConnection('DLA');

         $sr = new salesRep();

         $regionID = Request::get('regionID');
         $salesRepGroupID = array( Request::get('salesRepGroupID') );
         
         $result = $sr->getSalesRep($con,$salesRepGroupID);

         if($result && $result->num_rows > 0){
            $count = 0;
            while($row = $result->fetch_assoc()){

               $salesRep[$count]['id'] = $row['id'];
               $salesRep[$count]['region'] = $row['region'];
               $salesRep[$count]['salesRepGroup'] = $row['salesRepGroup'];
               $salesRep[$count]['salesRep'] = $row['salesRep'];

               $count ++;
            }

         }else{
            $salesRep = false;
         }

         if($salesRep){
            echo "<option value=''> Select </option>";
            for ($s=0; $s < sizeof($salesRep); $s++) { 
               echo "<option value='".$salesRep[$s]["id"]."'>"
                  .$salesRep[$s]["salesRep"].
               "</option>";
            }
         }else{
            echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
         }

      }

   	public function salesRepGroupByRegion(){
   		$db = new dataBase();
   		$con = $db->openConnection('DLA');

   		$regionID = array(Request::get('regionID'));

   		$sr = new salesRep();

   		$result = $sr->getSalesRepGroup($con,$regionID);

   		if($result && $result->num_rows > 0){
   			$count = 0;
   			while($row = $result->fetch_assoc()){
   				$salesRepGroup[$count]["id"] = $row["id"];
   				$salesRepGroup[$count]["name"] = $row["name"];

   				$count ++;
   			}
   		}else{
   			$salesRepGroup = false;
   		}

   		if($salesRepGroup){
   			echo "<option value=''> Select </option>";
	   		for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
	   			echo "<option value='".$salesRepGroup[$s]["id"]."'>"
	   				.$salesRepGroup[$s]["name"].
	   			"</option>";
	   		}
	   	}else{
	   		echo "<option value=''> There is no Sales Rep. Groups for this region. </option>";
	   	}
   	}

}
