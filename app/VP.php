<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;

class VP extends pAndR{

	public function base($con,$regionID){
    	$sql = new sql();
    	
    	$fcstInfo = $this->getForecast($con,$sql,$regionID);
    	$listOfClients = $this->listFCSTClients($con,$sql,$fcstInfo);
    	var_dump($listOfClients);

    }

    public function listFCSTClients($con,$sql,$fcstInfo){
    	$from = array("clientID","client");
    	for ($f=0; $f < sizeof($fcstInfo); $f++) { 

    		$select[$f] = "SELECT DISTINCT c.ID AS 'clientID', 
    		                      c.name AS 'client'
    		                    FROM forecast_client fc
    		                    LEFT JOIN client c ON c.ID = fc.client_id
    							WHERE(forecast_id = \"".$fcstInfo[$f]['ID']."\")
    							ORDER BY client

    		              ";
    		$res[$f] = $con->query($select[$f]);
    		$listC[$f] = $sql->fetch($res[$f],$from,$from);
    	}

    	$cc = 0;

    	for ($c=0; $c < sizeof($listC); $c++) { 
    		for ($d=0; $d < sizeof($listC[$c]); $d++) { 
    			$list[$cc] = $listC[$c][$d];
    			$cc++; 
    		}
    	}

    	$list = array_map("unserialize", array_unique(array_map("serialize", $list)));

	    return $list;

    }

	public function getForecast($con,$sql,$regionID){
		$select = " SELECT * FROM forecast
                           WHERE(region_id = \"".$regionID."\")
                           GROUP BY oppid
                           ORDER BY ID
        ";
        				
        $res = $con->query($select);
        $from = array('ID','oppid','region_id','currency_id',
        	          'type_of_value','read_q','year',
        	          'date_m','last_modify_by','last_modify_date','last_modify_time');

        $to = array('ID','oppid','regionID','currencyID',
        	        'typeOfValue','readQ','year',
        	        'dateM','lastModifyBy','lastModifyDate','lastModifyTime');

        $fcstInfo = $sql->fetch($res,$from,$to);
        
        return $fcstInfo;

	}


}
