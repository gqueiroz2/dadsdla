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

	public function base($con,$r,$pr,$cYear,$pYear){
    	$sql = new sql();
        $base = new base();
    	
        $regionID = Request::get('region');
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $curr = $pr->getCurrency($con,array($currencyID))[0]['name'];
        if($curr == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currencyID),array($cYear));
        }

        $currentMonth = intval( date('m') );

    	$fcstInfo = $this->getForecast($con,$sql,$regionID);

        if(!$fcstInfo){
            return false;
        }else{
            $save = $fcstInfo;
            $temp = $base->adaptCurrency($con,$pr,$save,$currencyID,$cYear,true);
            $currencyCheck = $temp["currencyCheck"];
            $newCurrency = $temp["newCurrency"];
            $oldCurrency = $temp["oldCurrency"];

            $temp2 = $base->adaptValue($value,$save,$regionID,true);

            $valueCheck = $temp2["valueCheck"];
            $multValue = $temp2["multValue"];
        }

        for ($c=0; $c < sizeof($fcstInfo); $c++) { 
            $adjust[$c]['salesRepID'] = $fcstInfo[$c]['salesRepID']; 
            $adjust[$c]['checkCurrency'] = $currencyCheck[$c]; 
            $adjust[$c]['newCurrency'] = $newCurrency[$c]; 
            $adjust[$c]['oldCurrency'] = $oldCurrency[$c]; 
            $adjust[$c]['checkValue'] = $valueCheck[$c]; 
            $adjust[$c]['multValue'] = $multValue[$c]; 
        }

        $salesRepListOfSubmit = $this->salesRepListOfSubmit($fcstInfo);
    	$listOfClients = $this->listFCSTClients($con,$sql,$base,$fcstInfo,$regionID);
        
        $bookingscYTDByClient = $this->currentYTDByClient($con,$sql,"ytd",$regionID,$cYear,$currentMonth,$listOfClients,$div,$value);
        $bookingspYTDByClient = $this->currentYTDByClient($con,$sql,"ytd",$regionID,$pYear,$currentMonth,$listOfClients,$div,$value);
        $varAbsYTDByClient = $this->subArrays($bookingscYTDByClient,$bookingspYTDByClient);

        $fcstcMonthByClient = $this->currentMonthByClient($con,$sql,"fcst",$regionID,$cYear,$currentMonth,$listOfClients,$div,$value,$fcstInfo);
        
        $tmp = $this->fcstMonths($con,$sql,$regionID,$cYear,$currentMonth,$listOfClients,$div,$value,$fcstInfo);

        $percentage = $this->getPercentage($tmp);

        $bookingscMonthByClient = $this->currentMonthByClient($con,$sql,"bkg",$regionID,$cYear,$currentMonth,$listOfClients,$div,$value,$fcstInfo);
        $totalcYearMonthByClient = $this->sumArrays($fcstcMonthByClient,$bookingscMonthByClient);
        $bookingspMonthByClient = $this->currentMonthByClient($con,$sql,"bkg",$regionID,$pYear,$currentMonth,$listOfClients,$div,$value,$fcstInfo);
        $varAbsMonthByClient = $this->subArrays($totalcYearMonthByClient,$bookingspMonthByClient);
        $closedFullYearByClient = $this->fullYearByClient($con,$sql,"fcstClosed",$regionID,$cYear,$listOfClients,false,$div,$value,$fcstInfo);
        $fcstFullYearByClient = $this->fullYearByClient($con,$sql,"fcst",$regionID,$cYear,$listOfClients,$adjust,$div,$value,$fcstInfo);
        $bookingscYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$cYear,$listOfClients,false,$div,$value,$fcstInfo);
        $bookingspYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$pYear,$listOfClients,false,$div,$value,$fcstInfo);
        $bookedPercentageFullYearByClient = $this->varPer($closedFullYearByClient,$bookingscYearByClient);
        //$totalFullYearByClient = $this->sumArrays($closedFullYearByClient,$fcstFullYearByClient);
        $totalFullYearByClient = $this->calculateTotalYear($closedFullYearByClient,$bookingscYearByClient,$fcstFullYearByClient);
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
                        "fcstFullYearPercentage" => $fcstFullYearPercentage,

                        "salesRepListOfSubmit" => $salesRepListOfSubmit,
                        "percentage" => $percentage,
                        "region" => $regionID,
                        "currency" => $currencyID,
                        "value" => $value,
                        "cYear" => $cYear

                    );

        return $rtr;
      
    }

    public function salesRepListOfSubmit($fcstInfo){

        for ($f=0; $f < sizeof($fcstInfo); $f++) { 
            $array[$f]['salesRepID'] = $fcstInfo[$f]['salesRepID'];
            $array[$f]['salesRepName'] = $fcstInfo[$f]['name'];
            $array[$f]['lastModifyDate'] = $fcstInfo[$f]['lastModifyDate'];
            $array[$f]['lastModifyTime'] = $fcstInfo[$f]['lastModifyTime'];
        }

        return $array;
    }

    public function calculateTotalYear($closed,$booking,$fcst){
        for ($a=0; $a < sizeof($closed); $a++) { 
            
            if($closed[$a] >= $booking[$a] ){
                $sum[$a] = $closed[$a] + $fcst[$a];
            }else{
                $sum[$a] = $booking[$a] + $fcst[$a];
            }


        }
        return $sum;
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

    public function currentYTDByClient($con,$sql,$kind,$regionID,$year,$currentMonth,$listOfClients,$div,$value){

        $whereIN = $this->makeWhereIN();

        switch ($kind) {
            case 'ytd':
                $revenue = $value."_revenue_prate";
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
                    $tmp = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue'])*$div;
                    $sumRevenue[$c] += $tmp;  
                }

                $revenueFW = $value."_revenue";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSumFW[$c] = "SELECT SUM($revenueFW) AS 'revenue' 
                                         FROM fw_digital
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month IN ($whereIN))
                                         AND (year = \"".$year."\")                                         
                                 ";
                    $resFW[$c] = $con->query($selectSumFW[$c]);
                    $fromFW = array("revenue");
                    $tmpFW = doubleval($sql->fetch($resFW[$c],$fromFW,$fromFW)[0]['revenue'])*$div;
                    $sumRevenue[$c] += $tmpFW; 
                }
                break;
        }
        
        return $sumRevenue;
    }

    public function fcstMonths($con,$sql,$regionID,$year,$currentMonth,$listOfClients,$div,$value,$fcstInfo){

        $tmp = array();

        for ($f=0; $f <sizeof($fcstInfo); $f++) {
            if ($f == 0) {
                array_push($tmp, $fcstInfo[$f]);
            }else{
                $check = true;
                for ($t=0; $t <sizeof($tmp) ; $t++) { 
                    if ($fcstInfo[$f]['name'] == $tmp[$t]['name']) {
                        $check = false;
                        break;
                    }
                }
                if ($check) {
                    array_push($tmp, $fcstInfo[$f]);
                }
            }
        }

        $fcstInfo = $tmp;

        $from = array("value","month","client_id");

        for ($f=0; $f <sizeof($fcstInfo); $f++) { 
            for ($c=0; $c <sizeof($listOfClients); $c++) { 
                $select[$f][$c] = "SELECT value, month, client_id FROM forecast_client WHERE (client_id = \"".$listOfClients[$c]['clientID']."\") AND (forecast_id = \"".$tmp[$f]['ID']."\")";

                $res[$f][$c] = $con->query($select[$f][$c]);
                $resp[$f][$c] = $sql->fetch($res[$f][$c],$from,$from);
            }
        }

        $saida = array();


        for ($f=0; $f <sizeof($resp); $f++) { 
            for ($c=0; $c <sizeof($resp[$f]) ; $c++) { 
                if ($f == 0) {
                    $saida[$c] = $resp[$f][$c];
                }else{
                    if ($saida[$c]) {
                    }else{
                        $saida[$c] = $resp[$f][$c];
                    }
                }
            }
        }

        return $saida;
    }

    public function getPercentage($saida){

        $date = date('n')-1;

        for ($c=0; $c <sizeof($saida); $c++) {
            if ($saida[$c]) {
                $total[$c] = 0;
                for ($m=0; $m <sizeof($saida[$c]); $m++) { 
                    $saida[$c][$m]=floatval($saida[$c][$m]['value']);
                    if ($m >= $date) {
                        $total[$c] += $saida[$c][$m];
                    }
                }
                for ($m=0; $m <sizeof($saida[$c]) ; $m++) { 
                    if ($m >= $date) {
                        if ($total[$c] != 0) {
                            $percentage[$c][$m] = $saida[$c][$m]/$total[$c];
                        }else{
                            $percentage[$c][$m] = 0;
                        }
                    }else{
                        $percentage[$c][$m] = 0;
                    }
                }
            }else{
                $total[$c] = false;
                $percentage[$c] = false;
            } 
        }

        return $percentage;
    }


    public function currentMonthByClient($con,$sql,$kind,$regionID,$year,$currentMonth,$listOfClients,$div,$value,$fcstInfo){
        switch ($kind) {
            case 'fcst':

                $tmp = array();

                for ($f=0; $f <sizeof($fcstInfo); $f++) {
                    if ($f == 0) {
                        array_push($tmp, $fcstInfo[$f]);
                    }else{
                        $check = true;
                        for ($t=0; $t <sizeof($tmp) ; $t++) { 
                            if ($fcstInfo[$f]['name'] == $tmp[$t]['name']) {
                                $check = false;
                                break;
                            }
                        }
                        if ($check) {
                            array_push($tmp, $fcstInfo[$f]);
                        }
                    }
                }

                $fcstInfo = $tmp;

                $whereIn = "AND ( forecast_id IN (";

                for ($f=0; $f <sizeof($fcstInfo) ; $f++) { 
                    if ($f==0) {
                        $whereIn .= "\"".$fcstInfo[$f]['ID']."\"";
                    }else{
                        $whereIn .= ",\"".$fcstInfo[$f]['ID']."\"";
                    }
                }
                $whereIn .= "))";

                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT SUM(value) AS 'revenue' 
                                         FROM forecast_client
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (month = \"".$currentMonth."\") $whereIn
                                 ";

                    $res[$c] = $con->query($selectSum[$c]);

                    $from = array("revenue");
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue'])*$div;
                }
                break;

            case 'bkg':
                $revenue = $value."_revenue_prate";
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
                    $tmp = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue'])*$div;
                    $sumRevenue[$c] += $tmp;                    
                }
                break;
            
            default:
                # code...
                break;
        }
        return $sumRevenue;
    }

    public function fullYearByClient($con,$sql,$kind,$regionID,$year,$listOfClients,$adjust=false,$div,$value,$fcstInfo){

        $currentMonth = date('m');
        switch ($kind) {
            case 'fcst':

                $tmp = array();

                for ($f=0; $f <sizeof($fcstInfo); $f++) {
                    if ($f == 0) {
                        array_push($tmp, $fcstInfo[$f]);
                    }else{
                        $check = true;
                        for ($t=0; $t <sizeof($tmp) ; $t++) { 
                            if ($fcstInfo[$f]['name'] == $tmp[$t]['name']) {
                                $check = false;
                                break;
                            }
                        }
                        if ($check) {
                            array_push($tmp, $fcstInfo[$f]);
                        }
                    }
                }

                $fcstInfo = $tmp;

                $whereIn = "AND ( fc.forecast_id IN (";

                for ($f=0; $f <sizeof($fcstInfo) ; $f++) { 
                    if ($f==0) {
                        $whereIn .= "\"".$fcstInfo[$f]['ID']."\"";
                    }else{
                        $whereIn .= ",\"".$fcstInfo[$f]['ID']."\"";
                    }
                }
                $whereIn .= "))";

                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $selectSum[$c] = "SELECT f.sales_rep_id AS 'salesRepID', 
                                             SUM(fc.value) AS 'revenue' 
                                         FROM forecast_client fc
                                         JOIN forecast f ON f.ID = fc.forecast_id
                                         WHERE (fc.client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (f.read_q = (SELECT MAX(read_q) AS 'read' FROM forecast))
                                         AND (f.type_of_forecast = 'AE')
                                         AND (fc.month >= ".intval($currentMonth).") $whereIn
                                         AND (f.submitted = '1')
                                         GROUP BY salesRepID
                                 "; 
                    //echo "<pre>".($selectSum[$c])."</pre>";
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("salesRepID","revenue");
                    $temp = $sql->fetch($res[$c],$from,$from)[0];
                    $salesRepRev = $temp['salesRepID'];
                    $sumRevenue[$c] = doubleval($temp['revenue']);

                    for ($a=0; $a < sizeof($adjust); $a++) { 
                        if($adjust[$a]["salesRepID"] == $salesRepRev){
                            if($adjust[$a]['checkCurrency']){
                                $sumRevenue[$c] = ($sumRevenue[$c]*$adjust[$a]['newCurrency'])/$adjust[$a]['oldCurrency'];
                            }

                            if($adjust[$a]['checkValue']){
                                $sumRevenue[$c] = $sumRevenue[$c]*$adjust[$a]['multValue'];
                            }
                        }
                    }                    
                    
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
                    $sumRevenue[$c] = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue'])*$div;
                }
                break;

            case 'bkg':               

                $revenue = $value."_revenue_prate";
                for ($c=0; $c < sizeof($listOfClients); $c++) { 
                    $sumRevenue[$c] = 0.0;
                    $selectSum[$c] = "SELECT SUM($revenue) AS 'revenue' 
                                         FROM ytd
                                         WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                                         AND (year = \"".$year."\")                                         
                                 ";
                    $res[$c] = $con->query($selectSum[$c]);
                    $from = array("revenue");
                    $tmp = doubleval($sql->fetch($res[$c],$from,$from)[0]['revenue'])*$div;
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
		$select = " SELECT f.ID AS 'ID',
                           f.oppid AS 'oppid',
                           f.region_id AS 'region_id',
                           f.sales_rep_id AS 'sales_rep_id',
                           f.currency_id AS 'currency_id',
                           f.type_of_value AS 'type_of_value',
                           f.read_q AS 'read_q',
                           f.year AS 'year',
                           f.date_m AS 'date_m',
                           f.last_modify_by AS 'last_modify_by',
                           f.last_modify_date AS 'last_modify_date',
                           f.last_modify_time AS 'last_modify_time',
                           f.month AS 'month',
                           f.submitted AS 'submitted',
                           f.type_of_forecast AS 'type_of_forecast',
                           sr.name AS 'name'
                           FROM forecast f
                           LEFT JOIN sales_rep sr ON f.sales_rep_id = sr.ID
                           WHERE(region_id = \"".$regionID."\") 
                           AND (submitted = '1')
                           ORDER BY ID DESC
                  ";
        //echo "<pre>".($select)."</pre>";
        $res = $con->query($select);
        //var_dump($res);
        $from = array('ID','oppid','region_id','sales_rep_id','currency_id',
        	          'type_of_value','read_q','year',
        	          'date_m','last_modify_by','last_modify_date','last_modify_time','month','submitted','type_of_forecast','name');

        $to = array('ID','oppid','regionID','salesRepID','currencyID',
        	        'typeOfValue','readQ','year',
        	        'dateM','lastModifyBy','lastModifyDate','lastModifyTime','month','submitted','type_of_value','name');

        $fcstInfo = $sql->fetch($res,$from,$to);
        
        return $fcstInfo;

	}


}
