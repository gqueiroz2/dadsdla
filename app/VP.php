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
        $base = new base();
    	
        $year = 2019;

    	$fcstInfo = $this->getForecast($con,$sql,$regionID);
    	$listOfClients = $this->listFCSTClients($con,$sql,$base,$fcstInfo);
        
        $fcstFullYearByClient = $this->fullYearByClient($con,$sql,"fcst",$regionID,$year,$listOfClients);
        
        $bookingsYTDcYearByClient = $this->fullYearByClient($con,$sql,"bkgYTD",$regionID,$year,$listOfClients);
        $rtr = array(   
                        "client" => $listOfClients,
                        "fcstFullYearByClient" => $fcstFullYearByClient,
                        "bookingsYTDcYearByClient" => $bookingsYTDcYearByClient
                    );

        return $rtr;

    }

    public function fullYearByClient($con,$sql,$kind,$regionID,$year,$listOfClients){

        switch ($kind) {
            case 'fcst':
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT SUM(value) AS 'revenue' 
                                         FROM forecast_client
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                 ";            
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                }
                break;

            case 'bkgYTD':
                $revenue = "gross_revenue_prate";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $sumRevenue[$c] = 0.0;
                    $selectSum[$c] = "SELECT SUM($revenue) AS 'revenue' 
                                         FROM ytd
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (year = \"".$year."\")                                         
                                 ";            
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $tmp = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                    $sumRevenue[$c] += $tmp;                    
                }
                break;
            
            default:
                # code...
                break;
        }
        
        return $sumRevenue;
    }

    public function listFCSTClients($con,$sql,$base,$fcstInfo){
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

        $list = $base->superUnique($list,'clientID');

	    return $list;

    }

	public function getForecast($con,$sql,$regionID){
		$select = " SELECT * FROM forecast
                           WHERE(region_id = \"".$regionID."\")                           
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
