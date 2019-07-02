<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\sql;
use App\base;

class RootController extends Controller{

    public function dataCurrentThrough(){

    	$db = new dataBase();
        $base = new base();
    	$con = $db->openConnection("DLA");

        $current = array("IBMS","CMAPS","Digital");
    	$tables = array("ytd","cmaps","digital");

    	for ($t=0; $t < sizeof($tables); $t++) { 
    		$status[$t] = "SHOW TABLE STATUS FROM DLA LIKE '".$tables[$t]."'";
    		$res[$t] = $con->query($status[$t]);

    		if($res[$t] && $res[$t]->num_rows > 0){
    			$row = $res[$t]->fetch_assoc();
    			//if(isset($row['Update_time'])){
                    $updateTime[$t] = $row['Update_time'];
                //}else{
                  //  $updateTime[$t] = '2019-07-02 18:09:35';
                //}
    		}else{
    			$updateTime = false;
    		}

    	}

    	return view("dataCurrentThrough",compact("updateTime",'current','base'));
    }

}
