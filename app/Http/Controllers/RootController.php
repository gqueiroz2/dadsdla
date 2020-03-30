<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\sql;
use App\base;

class RootController extends Controller{

    public function test(){
        
        $db = new dataBase();
        $base = new base();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();


        $select = "SELECT * FROM sources_date";

        $res = $con->query($select);

        $from = array("source","current_throught");

        $list = $sql->fetch($res,$from,$from);

        for ($l=0; $l < sizeof($list); $l++) { 
            echo "<div class='row'>
                <div class='col' style='margin-top: -10px !important;'>                                 
                    <span style='width: 100%; font-size: 10px; padding: 0px;'>".$list[$l]['source']." | ".$list[$l]['current_throught']."</span>
                </div>                              
            </div>";    
        }

        //var_dump($list);



    }

    public function dataCurrentThrough(){

    	$db = new dataBase();
        $base = new base();
    	$default = $db->defaultConnection();
        $con = $db->openConnection($default);

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
