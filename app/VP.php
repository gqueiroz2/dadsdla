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
    	
        $cYear = 2019;
        $pYear = $cYear - 1;

        $currentMonth = intval( date('m') );

    	$fcstInfo = $this->getForecast($con,$sql,$regionID);

        if(!$fcstInfo){
            return false;
        }
        
    	$listOfClients = $this->listFCSTClients($con,$sql,$base,$fcstInfo,$regionID);
        
        $bookingscYTDByClient = $this->currentYTDByClient($con,$sql,"ytd",$regionID,$cYear,$currentMonth,$listOfClients);
        $bookingspYTDByClient = $this->currentYTDByClient($con,$sql,"ytd",$regionID,$pYear,$currentMonth,$listOfClients);
        $varAbsYTDByClient = $this->subArrays($bookingscYTDByClient,$bookingspYTDByClient);

        $fcstcMonthByClient = $this->currentMonthByClient($con,$sql,"fcst",$regionID,$cYear,$currentMonth,$listOfClients);
        $bookingscMonthByClient = $this->currentMonthByClient($con,$sql,"bkg",$regionID,$cYear,$currentMonth,$listOfClients);
        $totalcYearMonthByClient = $this->sumArrays($fcstcMonthByClient,$bookingscMonthByClient);
        $bookingspMonthByClient = $this->currentMonthByClient($con,$sql,"bkg",$regionID,$pYear,$currentMonth,$listOfClients);
        $varAbsMonthByClient = $this->subArrays($totalcYearMonthByClient,$bookingspMonthByClient);

        $closedFullYearByClient = $this->fullYearByClient($con,$sql,"fcstClosed",$regionID,$cYear,$listOfClients);
        $fcstFullYearByClient = $this->fullYearByClient($con,$sql,"fcst",$regionID,$cYear,$listOfClients);
        $bookingscYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$cYear,$listOfClients);
        $bookingspYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$pYear,$listOfClients);
        $bookedPercentageFullYearByClient = $this->varPer($closedFullYearByClient,$bookingscYearByClient);
        $totalFullYearByClient = $this->sumArrays($closedFullYearByClient,$fcstFullYearByClient);
        $varAbsFullYearByClient = $this->subArrays($totalFullYearByClient,$bookingspYearByClient);
        $varPerFullYearByClient = $this->varPer($totalFullYearByClient,$bookingspYearByClient);

        $bookingscYTD = $this->consolidadeColumn($bookingscYTDByClient);
        $bookingspYTD = $this->consolidadeColumn($bookingspYTDByClient);

        $varAbsYTD = $this->subArrays(array($bookingscYTD),array($bookingspYTD))[0];
        $varPerYTD = $this->varPer(array($bookingscYTD),array($bookingspYTD))[0];
        
        $fcstcMonth = $this->consolidadeColumn($fcstcMonthByClient);
        $bookingscMonth = $this->consolidadeColumn($bookingscMonthByClient);
        $totalcYearMonth = $this->consolidadeColumn($totalcYearMonthByClient);
        $bookingspMonth = $this->consolidadeColumn($bookingspMonthByClient);

        $varAbscMonth = $this->subArrays(array($totalcYearMonth),array($bookingspMonth))[0];
        $varPercMonth = $this->varPer(array($totalcYearMonth),array($bookingspMonth))[0];

        $bookingscYear = $this->consolidadeColumn($bookingscYearByClient);
        $bookingspYear = $this->consolidadeColumn($bookingspYearByClient);

        $closedFullYear = $this->consolidadeColumn($closedFullYearByClient);
        $fcstFullYear = $this->consolidadeColumn($fcstFullYearByClient);



        $bookingscYear = $this->consolidadeColumn($bookingscYearByClient);
        $bookingspYear = $this->consolidadeColumn($bookingspYearByClient);
        $bookedPercentageFullYear = $this->consolidadeColumn($bookedPercentageFullYearByClient);
        $totalFullYear = $this->consolidadeColumn($totalFullYearByClient);      

        $bookingsOverclosed = $this->varPer(array($bookingscYear ),array($closedFullYear))[0];
        $closedFullYearPercentage = $this->varPer(array($closedFullYear ),array($totalFullYear))[0];
        $bookingscYearPercentage = $this->varPer(array($bookingscYear),array($totalFullYear))[0];
        $fcstFullYearPercentage = $this->varPer(array($fcstcMonth),array($totalFullYear))[0];

        $varAbsFullYear = $this->subArrays( array($totalFullYear) , array($bookingspYear) )[0];
        $varPerFullYear = $this->varPer(array($totalFullYear),array($bookingspYear))[0];

        $rtr = array(   
                        "client" => $listOfClients,

                        "bookingscYTDByClient" => $bookingscYTDByClient,
                        "bookingspYTDByClient" => $bookingspYTDByClient,
                        "varAbsYTDByClient" => $varAbsYTDByClient,

                        "fcstcMonthByClient" => $fcstcMonthByClient,
                        "bookingscMonthByClient" => $bookingscMonthByClient,
                        "totalcYearMonthByClient" => $totalcYearMonthByClient,
                        "bookingspMonthByClient" => $bookingspMonthByClient,
                        "varAbsMonthByClient" => $varAbsMonthByClient,                        

                        "closedFullYearByClient" => $closedFullYearByClient,
                        "fcstFullYearByClient" => $fcstFullYearByClient,
                        "bookingscYearByClient" => $bookingscYearByClient,
                        "bookingspYearByClient" => $bookingspYearByClient,
                        "bookedPercentageFullYearByClient" => $bookedPercentageFullYearByClient,
                        "totalFullYearByClient" => $totalFullYearByClient,
                        "varAbsFullYearByClient" => $varAbsFullYearByClient,
                        "varPerFullYearByClient" => $varPerFullYearByClient,

                        "bookingscYTD" => $bookingscYTD,
                        "bookingspYTD" => $bookingspYTD,
                        "varAbsYTD" => $varAbsYTD,
                        "varPerYTD" => $varPerYTD,

                        "fcstcMonth" => $fcstcMonth,
                        "bookingscMonth" => $bookingscMonth,
                        "totalcYearMonth" => $totalcYearMonth,
                        "bookingspMonth" => $bookingspMonth,

                        "varAbscMonth" => $varAbscMonth,
                        "varPercMonth" => $varPercMonth,

                        "closedFullYear" => $closedFullYear,
                        "fcstFullYear" => $fcstFullYear,
                        "bookingscYear" => $bookingscYear,
                        "bookingspYear" => $bookingspYear,
                        "bookedPercentageFullYear" => $bookedPercentageFullYear,
                        "totalFullYear" => $totalFullYear,

                        "fcstFullYearPercentage" => $fcstFullYearPercentage,

                        "varAbsFullYear" => $varAbsFullYear,
                        "varPerFullYear" => $varPerFullYear,

                        "bookingsOverclosed" => $bookingsOverclosed,
                        "closedFullYearPercentage" => $closedFullYearPercentage,
                        "bookingscYearPercentage" => $bookingscYearPercentage,
                        "fcstFullYearPercentage" => $fcstFullYearPercentage

                    );

        return $rtr;
        
    }

    public function consolidadeColumn($array){
        $sum = 0.0;
        for ($a=0; $a < sizeof($array); $a++) { 
            $sum += $array[$a];

        }

        return $sum;
    }

    public function varPer($array1,$array2){
        for ($a=0; $a < sizeof($array1); $a++) { 
            if($array2[$a] > 0){
                $varPer[$a] = ($array1[$a] / $array2[$a]) * 100;
            }else{
                $varPer[$a] = 0.0;
            }
        }
        return $varPer;
    }

    public function subArrays($array1,$array2){
        for ($a=0; $a < sizeof($array1); $a++) { 
            $sub[$a] = $array1[$a]- $array2[$a];
        }
        return $sub;
    }

    public function sumArrays($array1,$array2){
        for ($a=0; $a < sizeof($array1); $a++) { 
            $sum[$a] = $array1[$a] + $array2[$a];
        }
        return $sum;
    }

    public function makeWhereIN(){

        $cMonth = date('m');

        $string = "";

        for ($m=1; $m < $cMonth; $m++) { 
            $string .= $m;
            if($m != ($cMonth-1)){
                $string .= ",";
            }
        }
        //var_dump($string);
        return $string;

    }

    public function currentYTDByClient($con,$sql,$kind,$regionID,$year,$currentMonth,$listOfClients){

        $whereIN = $this->makeWhereIN();

        switch ($kind) {
            case 'ytd':

                $revenue = "gross_revenue_prate";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $sumRevenue[$c] = 0.0;
                    $selectSum[$c] = "SELECT SUM($revenue) AS 'revenue' 
                                         FROM ytd
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month IN ($whereIN))
                                         AND (year = \"".$year."\")                                         
                                 ";
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $tmp = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                    $sumRevenue[$c] += $tmp;                    
                }

                $revenueFW = "gross_revenue";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSumFW[$c] = "SELECT SUM($revenueFW) AS 'revenue' 
                                         FROM fw_digital
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month IN ($whereIN))
                                         AND (year = \"".$year."\")                                         
                                 ";
                    $resFW[$c] = $con->query($selectSumFW[$c]);
                    $fromFW = array("revenue");
                    $tmpFW = doubleval($sql->fetch($resFW[$c],$fromFW,$fromFW)[0]['revenue']);
                    $sumRevenue[$c] += $tmpFW;                    
                }




                break;
        }
        
        return $sumRevenue;
    }

    public function currentMonthByClient($con,$sql,$kind,$regionID,$year,$currentMonth,$listOfClients){
        switch ($kind) {
            case 'fcst':
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT SUM(value) AS 'revenue' 
                                         FROM forecast_client
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month = \"".$currentMonth."\")
                                 ";   
                    $res[$c] = $con->query($selectSum[$c]);

                    $from = array("revenue");
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                }
                break;

            case 'bkg':
                $revenue = "gross_revenue_prate";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $sumRevenue[$c] = 0.0;
                    $selectSum[$c] = "SELECT SUM($revenue) AS 'revenue' 
                                         FROM ytd
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month = \"".$currentMonth."\")
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

    public function fullYearByClient($con,$sql,$kind,$regionID,$year,$listOfClients){

        switch ($kind) {
            case 'fcst':
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT SUM(fc.value) AS 'revenue' 
                                         FROM forecast_client fc
                                         JOIN forecast f ON f.ID = fc.forecast_id
                                         WHERE (fc.client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (f.read_q = (SELECT MAX(read_q) AS 'read' FROM forecast))
                                         AND (f.type_of_forecast = 'AE')
                                 ";       
                    var_dump($selectSum[$c]);
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                    var_dump($sumRevenue[$c]);
                }
                break;
            case 'fcstClosed':
                $revenue = "gross_revenue";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT SUM($revenue) AS 'revenue' 
                                         FROM sf_pr
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (stage = '5')
                                 ";            
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue']);
                }
                break;

            case 'bkg':
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

    public function listFCSTClients($con,$sql,$base,$fcstInfo,$regionID){
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

        $selectYTD = "SELECT DISTINCT c.name AS 'client',
                          c.ID AS 'clientID'
                    FROM ytd y
                    LEFT JOIN client c ON c.ID = y.client_id
                    WHERE (sales_representant_office_id = \"".$regionID."\")
                    AND (sales_representant_office_id = \"".$regionID."\")
                  ";
        
        $resYTD = $con->query($selectYTD);

        $listCYTD = $sql->fetch($resYTD,$from,$from);

        $selectFW = "SELECT DISTINCT c.name AS 'client',
                          c.ID AS 'clientID'
                    FROM fw_digital y
                    LEFT JOIN client c ON c.ID = y.client_id
                    WHERE (region_id = \"".$regionID."\")
                  ";
        
        $resFW = $con->query($selectFW);

        $listCFW = $sql->fetch($resFW,$from,$from);

    	$cc = 0;
        if($listC){
        	for ($c=0; $c < sizeof($listC); $c++) { 
            	if($listC[$c]){
                	for ($d=0; $d < sizeof($listC[$c]); $d++) { 
            			$list[$cc] = $listC[$c][$d];
            			$cc++; 
            		}
                }
        	}
        }

        for ($d=0; $d < sizeof($listCYTD); $d++) { 
            $list[$cc] = $listCYTD[$d];
            $cc++;
        }

        $list = $base->superUnique($list,'clientID');

        usort($list, array($this,'orderClient'));

	    return $list;

    }

    private static function orderClient($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['client'] < $b['client']) ? -1 : 1;
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
