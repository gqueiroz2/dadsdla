<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\sql;

class RootController extends Controller{

    public function dataCurrentThrough(){

    	$db = new dataBase();

    	$con = $db->openConnection("DLA");

    	$tables = array("ytd","cmaps","digital");

    	for ($t=0; $t < sizeof($tables); $t++) { 
    		$status[$t] = "SHOW TABLE STATUS FROM DLA LIKE '".$tables[$t]."'";
    		$res[$t] = $con->query($status[$t]);

    		if($res[$t] && $res[$t]->num_rows > 0){
    			$row = $res[$t]->fetch_assoc();
    			$updateTime[$t] = $row['Update_time'];
    		}else{
    			$updateTime = false;
    		}

    	}



    	var_dump($status);
    	var_dump($res);
    	var_dump($updateTime);

    	return view("dataCurrentThrough");
    }

}
