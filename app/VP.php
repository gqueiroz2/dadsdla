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
    
    public function saveValues($con,$date,$cYear,$value,$submit,$currency,$percentage,$totalFCST,$region,$clients){

        $base = new base();
        $sql = new sql();
        $ID = $this->generateID($date,$cYear,$value,$currency,$region,$submit);
        $time = date('H:i');
        $fcstMonth = date('m');
        $tmp = explode("-", $date);
        $month = $tmp[1];
    
        $type = "V1";

        $user = Request::session()->get('userName');

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$ID."\" AND type_of_forecast = \"".$type."\"";

        $from = array("ID");

        $read = $this->weekOfMonth($date);

        $res = $con->query($select);

        $resp = $sql->fetch($res,$from,$from)[0]["ID"];

        if($submit == "submit") {
            $submit = 1;
            $selectSubmit = "SELECT ID FROM forecast WHERE  submitted = \"1\" AND month = \"".intval($month)."\" AND type_of_forecast = \"V1\"";
            if ($region == '1') {
                $selectSubmit .=  " AND read_q = \"".intval($read)."\"";
            }

            $from = array("ID");

            $resultSubmit = $con->query($selectSubmit);
            
            $resSubmit = $sql->fetch($resultSubmit,$from,$from)[0]["ID"];

            if ($resSubmit != null) {
                return "Already Submitted";
            }
        }else{
            $submit = 0;
        }

        if ($resp) {
            $update = "UPDATE forecast SET read_q = \"".$read."\", 
                                            last_modify_by = \"".$user."\",
                                            last_modify_date = \"".$date."\", 
                                            last_modify_time = \"".$time."\", 
                                            oppid = \"".$ID."\",
                                            currency_id = \"".$currency['id']."\", 
                                            year = \"".$cYear."\", 
                                            type_of_value = \"".$value."\",
                                            month = \"".$month."\" WHERE ID = \"".$resp."\"";


            if ($con->query($update) === true) {

            }else{
                $error = ($con->error);
                return $error;
            }

            $bool = $this->FcstClient($con,$sql,$ID,$percentage,$totalFCST,$clients,"update"); 

            return $bool;

        }else{
            $columns = "(oppid,
                        region_id, sales_rep_id,
                        year, month, read_q, date_m,
                        currency_id, type_of_value,
                        last_modify_by, last_modify_date, last_modify_time,
                        submitted, type_of_forecast)";
   
            $values = "(\"".$ID."\",
                        \"".$region['id']."\",NULL,
                        \"".$cYear."\", \"".$month."\", \"".$read."\", \"".$date."\",
                        \"".$currency['id']."\", \"".$value."\",
                        \"".$user."\", \"".$date."\", \"".$time."\",
                        \"".$submit."\", \"V1\")";

            $insert = "INSERT INTO forecast $columns VALUES $values";

            if ($con->query($insert) === true) {

            }else{
                $error = ($con->error);
                return $error;
            }

            $bool = $this->FcstClient($con,$sql,$ID,$percentage,$totalFCST,$clients,"insert");  

            return $bool;
        }
 
    }

    public function FcstClient($con,$sql,$ID,$percentage,$fcstValue,$clients,$type){
        $select = "SELECT ID FROM forecast WHERE oppid = \"".$ID."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        for ($c=0; $c <sizeof($fcstValue); $c++) {
            if ($percentage[$c]) {
                for ($m=0; $m <sizeof($percentage[$c]) ; $m++) { 
                    $input[$c][$m] = $fcstValue[$c]*$percentage[$c][$m];
                }
            }else{
                for ($m=0; $m <12 ; $m++) { 
                    $input[$c][$m] = 0;
                }
            }
        }

        switch ($type) {
            case 'insert':

                $columns = "(forecast_id,month,value,client_id,brand)";

                for ($c=0; $c <sizeof($clients); $c++) { 
                    for($m=0; $m <sizeof($input[$c]); $m++) { 
                        $input[$c][$m] = "INSERT INTO forecast_client $columns VALUES (\"".$id."\",\"".($m+1)."\", \"".$input[$c][$m]."\",\"".$clients[$c]->clientID."\",NULL)";
                        
                        if ($con->query($input[$c][$m]) === true) {

                        }else{
                            $error = ($con->error);
                            return $error;
                        }
                    }
                }
                return "Saved";

                break;
            
            case 'update':
                for ($c=0; $c <sizeof($clients); $c++) { 
                    for ($m=0; $m <sizeof($input[$c]); $m++) { 
                        $update[$c][$m] = "UPDATE forecast_client SET value = \"".$input[$c][$m]."\" WHERE month = \"".($m+1)."\" AND client_id = \"".$clients[$c]->clientID."\"";

                        if ($con->query($update[$c][$m]) === true) {

                        }else{
                            $error = ($con->error);
                            return $error;
                        }
                    }
                }
                
                return "Updated";

                break;


        }

    }


    public function generateID($date,$cYear,$value,$currency,$region,$submit){
        $week = $this->weekOfMonth($date);
        if ($submit == "save") {
            $submitted = "SAV";
        }else{
            $submitted = "TRS";
        }
        $month = explode("-", $date)[1];
        $id = "$submitted-".$region["name"]."-$cYear-$month-WEEK-$week-VP-".strtoupper($currency["name"])."-".strtoupper($value);
        return $id;
    }
    public function weekOfMonth($date) {
        $date = strtotime($date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        if ((intval(date("W", $date)) - intval(date("W", $firstOfMonth))) == 0) {
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }else{
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth));
        }
    }
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

        $id = $this->verifySaves($con,$sql,$regionID);

        if ($id) {
            $fcstFullYearByClient = $this->getFcstFromDatabase($con,$r,$pr,$cYear,$pYear,$listOfClients);
            $fcstFullYearByClientAE = $this->fullYearByClient($con,$sql,"fcst",$regionID,$cYear,$listOfClients,$adjust,$div,$value,$fcstInfo);
        }else{
            $fcstFullYearByClient = $this->fullYearByClient($con,$sql,"fcst",$regionID,$cYear,$listOfClients,$adjust,$div,$value,$fcstInfo);
            $fcstFullYearByClientAE = $fcstFullYearByClient;
        }

        
        $bookingscYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$cYear,$listOfClients,false,$div,$value,$fcstInfo);
        $bookingspYearByClient = $this->fullYearByClient($con,$sql,"bkg",$regionID,$pYear,$listOfClients,false,$div,$value,$fcstInfo);
        $bookedPercentageFullYearByClient = $this->varPer($closedFullYearByClient,$bookingscYearByClient);
        //$totalFullYearByClient = $this->sumArrays($closedFullYearByClient,$fcstFullYearByClient);
        $totalFullYearByClient = $this->calculateTotalYear($closedFullYearByClient,$bookingscYearByClient,$fcstFullYearByClient);
        $totalFullYearByClientAE = $this->calculateTotalYear($closedFullYearByClient,$bookingscYearByClient,$fcstFullYearByClientAE);
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
        $fcstFullYearAE = $this->consolidadeColumn($fcstFullYearByClientAE);
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
                        "cYear" => $cYear,
                        "fcstFullYearByClientAE" => $fcstFullYearByClientAE,
                        "totalFullYearByClientAE" => $totalFullYearByClientAE,
                        "fcstFullYearAE" => $fcstFullYearAE
                    );
        return $rtr;
      
    }

    public function getFcstFromDatabase($con,$r,$pr,$cYear,$pYear,$listOfClients){
        $sr = new salesRep();        
        $br = new brand();
        $base = new base();
        $sql = new sql();
        $reg = new region();
       
        $regionID = Request::get('region');
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted 
                        FROM forecast 
                        WHERE (submitted = \"0\" OR submitted = \"1\") 
                        AND month = \"$actualMonth\" 
                        AND year = \"$cYear\"
                        AND type_of_forecast = \"V1\"
                  ";

        if ($regionID == "1") {
            $select .= "AND read_q = \"$week\"";
        }

        $select .= "ORDER BY ID DESC";

        $result = $con->query($select);

        $from = array("oppid","ID","type_of_value","currency_id", "submitted");

        $save = $sql->fetch($result,$from,$from);

        $temp = $base->adaptCurrency($con,$pr,$save,$currencyID,$cYear);


        $currencyCheck = $temp["currencyCheck"][0];
        $newCurrency = $temp["newCurrency"][0];
        $oldCurrency = $temp["oldCurrency"][0];

        $temp2 = $base->adaptValue($value,$save,$regionID);

        $valueCheck = $temp2["valueCheck"][0];
        $multValue = $temp2["multValue"][0];

        $regionName = $reg->getRegion($con,array($regionID))[0]['name'];

        $brand = $br->getBrandBinary($con);
        $month = $base->getMonth();

        $tmp = array($cYear);
        //valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        
        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $this->monthAnalise($base);

        for ($c=0; $c <sizeof($listOfClients) ; $c++) { 
            $selectClient[$c] = "SELECT SUM(value) AS value FROM forecast_client WHERE forecast_id = \"".$save[0]['ID']."\" AND client_id = \"".$listOfClients[$c]['clientID']."\"";
            $res[$c] = $con->query($selectClient[$c]);

            $resp[$c] = $sql->fetchSum($res[$c],"value")["value"];

            if ($currencyCheck) {
                $resp[$c] = (($resp[$c]*$newCurrency)/$oldCurrency);
            }

            if ($valueCheck) {
                $resp[$c] = ($resp[$c]*$multValue);
            }

        }

        return $resp;
    }

    public function monthAnalise($base){
        $month = date('M');

        $tmp = false;

        for ($m=0; $m <sizeof($base->monthWQ) ; $m++) { 
            if ($month == $base->monthWQ[$m]) {
                $tmp = true;
            }

            if ($tmp) {
                $tfArray[$m] = "";
                $odd[$m] = "odd";
                $even[$m] = "rcBlue";
                $manualEstimation[$m] = "background-color:#235490;";
                $color[$m] = "color:white;";
            }else{
                $tfArray[$m] = "readonly='true'";
                $odd[$m] = "oddGrey";
                $even[$m] = "evenGrey";
                $manualEstimation[$m] = "";
                $color[$m] = "";
            }
        } 

        $rtr = array("tfArray" => $tfArray , "odd" => $odd , "even" => $even, "manualEstimation" => $manualEstimation, "color" => $color);    

        return $rtr;
    }

    public function verifySaves($con,$sql,$regionID){
        $date = date('Y-m-d');
        $tmp = explode("-", $date);
        $month = $tmp[1];

        $from = array("ID");

        $select = "SELECT ID from forecast WHERE month = \"".$month."\" AND type_of_forecast = \"V1\" AND region_id = \"".$regionID."\"";

        if ($regionID == 1) {
            $week = $this->weekOfMonth($date);
            $select .= " AND read_q = \"".$week."\"";
        }

        $res = $con->query($select);

        $resp = $sql->fetch($res,$from,$from)[0]["ID"];

        return $resp;

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
