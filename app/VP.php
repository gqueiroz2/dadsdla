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

	public function getForecast($con,$sql,$regionID){
		$select = " SELECT * FROM forecast
                           WHERE(region_id = \"".$regionID."\")
                           GROUP BY oppid
        ";
        				
        $res = $con->query($select);
        $from = array('oppid','region_id','currency_id',
        	          'type_of_value','read_q','year',
        	          'date_m','last_modify_by','last_modify_date','last_modify_time');

        $to = array('oppid','regionID','currencyID',
        	        'typeOfValue','readQ','year',
        	        'dateM','lastModifyBy','lastModifyDate','lastModifyTime');

        $fcst = $sql->fetch($res,$from,$to);
        
        var_dump($fcst);

	}

    public function base($con,$regionID){
    	$sql = new sql();
    	$this->getForecast($con,$sql,$regionID);

    }
}
