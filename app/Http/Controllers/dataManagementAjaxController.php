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
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $regionID = array(Request::get('regionID'));
      $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
      echo "<option value=''> Select </option>";
      for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
         echo "<option value='".$salesRepGroup[$s]['id']."'>".$salesRepGroup[$s]['name']."</option>";
      }
   }

   public function salesRepBySalesRepGroup(){
      $db = new dataBase();
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $sr = new salesRep();
      $regionID = Request::get('regionID');
      $salesRepGroupID = array( Request::get('salesRepGroupID') );         
      $salesRep = $sr->getSalesRep($con,$salesRepGroupID);
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
		$default = $db->defaultConnection();
      $con = $db->openConnection($default);
		$regionID = array(Request::get('regionID'));
		$sr = new salesRep();
		$salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
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
