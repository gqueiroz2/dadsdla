<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\pAndR;
use App\sql;
use App\base;
use App\pRate;

class VPMonth extends pAndR {

    public function insertUpdate($con, $oppid, $region, $currency, $value, $user, $year, $read, $date, $time, $fcstMonth, $manualEstimation, $manualEstimantionByClient, $list, $submit, $brandPerClient, $totalClient){
        
        $sql = new sql();
        $tmp = explode("-", $date);

        if ($tmp && isset($tmp[1])) {
            $month = $tmp[1];
        }else{
            $month = 0;
        }

        if ($submit == "submit") {
            
            $submit = 1;

            $selectSubmit = "SELECT ID FROM forecast WHERE submitted = \"1\" AND month = \"".intval($month)."\" AND type_of_forecast = 'V2' AND region_id = '$region'";

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
        
        $tableFCST = "forecast";
        $tableFCSTClient = "forecast_client";
        $tableFCSTSalesRep = "forecast_sales_rep";

        $select = "SELECT ID FROM forecast WHERE oppid = '$oppid' AND type_of_forecast = 'V2'";
        
        $from = array("ID");

        $result = $con->query($select);
        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        if ($id && !is_null($id) && $submit == 0) {

            $update = "UPDATE $tableFCST SET read_q = \"".$read."\", 
                                            last_modify_date = \"".$date."\", 
                                            last_modify_time = \"".$time."\", 
                                            oppid = \"".$oppid."\",
                                            currency_id = \"".$currency['id']."\", 
                                            year = \"".$year."\", 
                                            type_of_value = \"".$value."\",
                                            month = \"".$month."\" WHERE ID = \"".$id."\"";
            
            echo "<pre>".($update)."</pre>";

            if($con->query($update) === true){

            }else{
                var_dump($con->error);
                return false;
            }

            $updateFCSTSalesRep = $this->updateFCSTSalesRep($con,null,$manualEstimation,$tableFCSTSalesRep);
            $updateFCSTClient = $this->updateFCSTClient($con,$oppid,$manualEstimantionByClient,$tableFCSTClient,$list,$brandPerClient,$totalClient);

            return "Updated";

        }else{
            $columns = "(oppid, region_id, sales_rep_id, year,
                         month, read_q, date_m, currency_id,
                         type_of_value, last_modify_by,
                         last_modify_date, last_modify_time,
                         submitted, type_of_forecast
                        )";

            $salesRepID = null;

            $values = "(
                        \"".$oppid."\",
                        \"".$region."\",NULL,
                        \"".$year."\",\"".$month."\",\"".$read."\",\"".$date."\",
                        \"".$currency['id']."\",\"".$value."\",
                        \"".$user."\",\"".$date."\",\"".$time."\",
                        \"".$submit."\", \"V2\"
                      )";

            $insertFCST = "INSERT INTO $tableFCST $columns VALUES $values";

            echo "<pre>".($insertFCST)."</pre>";

            if ($con->query($insertFCST) === true) {
                var_dump("TRUE");
            }else{
                var_dump("ELSE");
                var_dump($con->error);
                return false;
            }

            $insertFCSTSalesRep = $this->FCSTSalesRep($con,$oppid,$manualEstimation,$tableFCSTSalesRep);
            $insertFCSTClient = $this->FCSTClient($con,$oppid,$manualEstimantionByClient,$tableFCSTClient,$list,$brandPerClient, $totalClient);

            return "Created";
        }
    }

    public function updateFCSTSalesRep($con,$salesRep,$manualEstimantion,$table){
        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE type_of_forecast = 'V2' ORDER BY ID DESC LIMIT 1";
        
        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        for ($m=0; $m < sizeof($manualEstimantion); $m++) { 
            $update[$m] = "UPDATE $table SET value = \"".$manualEstimantion[$m]."\" WHERE month = \"".($m+1)."\" AND forecast_id = \"".$id."\"";
            
            if ($con->query($update[$m]) === true) {
            
            }else{
                var_dump($con->error);
                return false;
            }
        }
    }

    public function FCSTSalesRep($con,$oppid,$manualEstimantionBySalesRep,$table){

        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$oppid."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        $columns = "(forecast_id,month,value)";
        for ($m=0; $m <sizeof($manualEstimantionBySalesRep); $m++) { 
            $values[$m] = "(\"".$id."\" ,\"".($m+1)."\",\"".$manualEstimantionBySalesRep[$m]."\")";

            $insert[$m] = "INSERT INTO $table $columns VALUES ".$values[$m]."";

            if ($con->query($insert[$m]) === true) {
      
           }else{
                var_dump($con->error);
                return false;
            }
        }
    }

    public function updateFCSTClient($con,$oppid,$manualEstimantion,$table,$list,$brandPerClient,$totalClient){

        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$oppid."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        for ($c=0; $c < sizeof($list); $c++) { 
            
            $div = 1;
            
            for ($m=0; $m < sizeof($manualEstimantion[$c]); $m++) {

                if ($manualEstimantion[$c][$m] == 0 || $totalClient[$c] == 0) {
                    $valueClient = 0;
                }else{
                    $valueClient = ($manualEstimantion[$c][$m]/$totalClient[$c])*100;
                }

                $update[$c][$m] = "UPDATE $table SET value = \"".($valueClient)."\", brand = \"".$brandPerClient[$c]."\" WHERE month = \"".($m+1)."\" AND forecast_id = \"".$id."\" AND client_id = \"".$list[$c]->clientID."\"";

                if ($con->query($update[$c][$m]) === true) {
                    
                }else{
                    var_dump($con->error);
                    return false;
                }
            }
            
        }
    }

    public function FCSTClient($con,$oppid,$manualEstimantion,$table,$list,$brandPerClient,$totalClient){

        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$oppid."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        $columns = "(forecast_id,month,value,client_id,brand)";

        for ($c=0; $c < sizeof($list); $c++) {
            
            $div = 1;

            for ($m=0; $m < sizeof($manualEstimantion[$c]); $m++) {

                if ($manualEstimantion[$c][$m] == 0 || $totalClient[$c] == 0) {
                    $valueClient = 0;
                }else{
                    $valueClient = ($manualEstimantion[$c][$m]/$totalClient[$c])*100;
                }

                $values[$c][$m] = "(\"".$id."\" ,\"".($m+1)."\",\"".($valueClient)."\",\"".$list[$c]->clientID."\",\"".$brandPerClient[$c]."\")";

                $insert[$c][$m] = "INSERT INTO $table $columns VALUES ".$values[$c][$m]."";

                if ($con->query($insert[$c][$m]) === true) {
                    
                }else{
                    var_dump($con->error);
                    return false;
                }
            }
        }
    }

    public function base($con, $region, $regionID, $currencyID, $year, $value){
        
        $base = new base();
        $sql = new sql();
        $pr = new pRate();

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE region_id = '$regionID' AND month = \"$actualMonth\" AND year = \"$year\" AND (type_of_forecast = 'V1' OR type_of_forecast = 'V1')";

        if ($regionID == "1") {
            $select .= " AND read_q = \"$week\"";
        }

        $select .= " ORDER BY last_modify_date DESC";
        
        $result = $con->query($select);

        $from = array("oppid","ID","type_of_value","currency_id");

        $save = $sql->fetch($result,$from,$from);
        
        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
        }else{

            $temp = $base->adaptCurrency($con,$pr,$save,$currencyID,$year);

            $currencyCheck = $temp['currencyCheck'][0];
            $newCurrency = $temp['newCurrency'][0];
            $oldCurrency = $temp['oldCurrency'][0];

            $temp2 = $base->adaptValue($value,$save,$regionID);

            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];
        }

        $regionName = $region;

        $br = new brand();
        $brand = $br->getBrandBinary($con);

        $month = $base->getMonth();

        $tmp = array($year);
        $pRate = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);

        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $this->monthAnalise($base);
        $listOfClients = $this->listClientsByVPMonth($con,$sql,$year,$regionID);

        for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }

        for ($m=0; $m < sizeof($month); $m++) {
            $lastYear[$m] = $this->generateValueWB($con,$sql,$regionID,($year-1),$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$pRate;
        }

        $lastYear = $this->addQuartersAndTotalOnArray(array($lastYear))[0];

        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m < sizeof($table[$b]); $m++){
                $targetValues[$b][$m] = $this->generateValueWithOutSalesRep($con,$sql,$regionID,$year,$brand[$b],$month[$m][1],"value","plan_by_sales",$value)*$pRate;
            }
        }

        $mergeTarget = $this->mergeTarget($targetValues,$month);
        $targetValues = $mergeTarget;

        $clientRevenueCYear = $this->revenueByClient($con,$sql,$base,$pr,$regionID,$year,$month,$currency,$currencyID,$value,$listOfClients,$year);

        $clientRevenueCYear = $this->addQuartersAndTotalOnArray($clientRevenueCYear);

        $clientRevenuePYear = $this->revenueByClient($con,$sql,$base,$pr,$regionID,($year-1),$month,$currency,$currencyID,$value,$listOfClients,$year);

        $clientRevenuePYear = $this->addQuartersAndTotalOnArray($clientRevenuePYear);

        $tmp = $this->getBookingExecutive($con,$sql,$month,$regionID,$year,$value,$currency,$pr);

        $executiveRevenueCYear = $this->addQuartersAndTotal($tmp);

        $executiveRevenuePYear = $this->consolidateAEFcst($clientRevenuePYear);

        if ($save) {
            $select = array();
            $result = array();

            $from = "value";

            for ($m=0; $m < 12; $m++) { 
                $manualRolling[$m] = 0;
                $fPastRollingFCST[$m] = 0;
            }
            
            $cYear = date('Y');
            $cMonth = date('n');

            $auxSelect = "SELECT read_q FROM forecast WHERE (type_of_forecast = 'V2') AND (submitted = '1') AND read_q = (SELECT (MAX(read_q)-1) FROM forecast) AND month = \"".$cMonth."\" AND year = '$year' ORDER BY ID DESC limit 1";
            $auxResult = $con->query($auxSelect);
            $auxFrom = array("read_q");
            $auxSaida = $sql->fetch($auxResult, $auxFrom, $auxFrom);
            
            if (!$auxSaida) {
                if ($cMonth == 1) {
                    $forecastMonth = 12;
                    $forecastYear = $year-1;
                }else{
                    $forecastYear = $year;
                    $forecastMonth = $cMonth-1;
                }
            }else{
                $forecastYear = $year;
                $forecastMonth = $cMonth;
            }
            
            $id = $this->verifySaves($con,$sql,$regionID);

            if ($id) {
                $type = 'V2';

                for ($m=0; $m < sizeof($month); $m++) { 
                
                    $idSelect = "SELECT ID FROM forecast WHERE year = \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\"";
                    if ($regionID == "1") {
                        $idSelect .= " AND read_q = (SELECT (MAX(read_q)) FROM forecast WHERE year = \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\")";
                    }
                    
                    $idResult = $con->query($idSelect);
                    $idSaida = $sql->fetch($idResult, array("ID"), array("ID"));

                    $selectV2[$m] = "SELECT SUM(value) AS value FROM forecast_sales_rep f WHERE f.month = \"".($m+1)."\" AND f.forecast_id = '".$idSaida[0]['ID']."'";
                    //var_dump($selectV2[$m]);
                    $resultV2[$m] = $con->query($selectV2[$m]);
                    $saidaV2[$m] = $sql->fetchSum($resultV2[$m],$from);                    

                    if ($saidaV2[$m]) {
                        $rollingFCSTV2[$m] = floatval($saidaV2[$m]['value']);
                        $manualRolling[$m] += $rollingFCSTV2[$m];
                    }else{
                        $rollingFCSTV2[$m] = 0;
                        $manualRolling[$m] = 0;
                    }
                    
                    if ($valueCheck) {
                        $rollingFCSTV2[$m] = $rollingFCSTV2[$m]*$multValue;
                        $manualRolling[$m] = $manualRolling[$m]*$multValue;
                    }

                    if ($currencyCheck) {
                        $rollingFCSTV2[$m] = ($rollingFCSTV2[$m]*$newCurrency)/$oldCurrency;
                        $manualRolling[$m] = ($manualRolling[$m]*$newCurrency)/$oldCurrency;
                    }

                }

            }else{
                $type = 'V1';
            }

            $idSelect = "SELECT ID FROM forecast WHERE year = \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\"";
            if ($regionID == "1") {
                $idSelect .= " AND read_q = (SELECT (MAX(read_q)) FROM forecast WHERE year = \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\")";
            }
            
            $idResult = $con->query($idSelect);
            $idSaida = $sql->fetch($idResult, array("ID"), array("ID"));

            $idPSelect = "SELECT ID FROM forecast WHERE year= \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\"";
            if ($regionID == "1") {
                $idPSelect .= " AND read_q = (SELECT (MAX(read_q)-1) FROM forecast WHERE year = \"".$year."\" AND type_of_forecast = \"".$type."\" AND month = \"".$cMonth."\" AND region_id = \"".$regionID."\")";
            }
            
            $idPResult = $con->query($idPSelect);
            $idPSaida = $sql->fetch($idPResult, array("ID"), array("ID"));

            for ($c=0; $c < sizeof($listOfClients); $c++) {
                
                $mul = 1;

                for ($m=0; $m < 12; $m++) {

                    $select[$c][$m] = "SELECT SUM(value) AS value FROM forecast_client f WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\" AND f.month = \"".($m+1)."\" AND  forecast_id = \"".$idSaida[0]['ID']."\"";
                    
                    $pastSelect[$c][$m] = "SELECT SUM(value) AS value FROM forecast_client f WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\" AND f.month = \"".($m+1)."\" AND  forecast_id = \"".$idPSaida[0]['ID']."\"";

                    //var_dump($pastSelect[$c][$m]);

                    /*if ($listOfClients[$c]["clientID"] == 865) {
                        var_dump($select[$c][$m]);
                    }*/

                    $result[$c][$m] = $con->query($select[$c][$m]);
                    $pastResult[$c][$m] = $con->query($pastSelect[$c][$m]);

                    $saida[$c][$m] = $sql->fetchSum($result[$c][$m],$from);
                    $pastSaida[$c][$m] = $sql->fetchSum($pastResult[$c][$m],$from);
                }

                if ($saida[$c]) {
                    for ($m=0; $m < sizeof($saida[$c]); $m++) { 
                        $rollingFCST[$c][$m] = floatval($saida[$c][$m]['value']);
                        $pastRollingFCST[$c][$m] = floatval($pastSaida[$c][$m]['value']);
                        $fPastRollingFCST[$m] += $pastRollingFCST[$c][$m];

                        if ($type == "V1") {
                            $manualRolling[$m] += $rollingFCST[$c][$m];
                        }
                    }
                }else{
                    for ($m=0; $m < 12; $m++) { 

                        $rollingFCST[$c][$m] = 0;
                        $pastRollingFCST[$c][$m] = 0;
                        $fPastRollingFCST[$m] = 0;

                        if ($type == 'V1') {
                            $manualRolling[$m] = 0;
                        }
                        
                    }
                }
                
                if ($valueCheck) {
                    for ($m=0; $m < sizeof($rollingFCST[$c]); $m++) { 
                        $rollingFCST[$c][$m] = $rollingFCST[$c][$m]*$multValue;
                        $pastRollingFCST[$c][$m] = $pastRollingFCST[$c][$m]*$multValue;
                    }
                }

                if ($currencyCheck) {
                    for ($m=0; $m < sizeof($rollingFCST[$c]); $m++) { 
                        $rollingFCST[$c][$m] = ($rollingFCST[$c][$m]*$newCurrency)/$oldCurrency;
                        $pastRollingFCST[$c][$m] = ($pastRollingFCST[$c][$m]*$newCurrency)/$oldCurrency;
                    }
                }

            }

            for ($m=0; $m < sizeof($month); $m++) { 
                
                if ($valueCheck) {
                    $fPastRollingFCST[$m] = $fPastRollingFCST[$m]*$multValue;

                    if ($type == 'V1') {
                        $manualRolling[$m] = $manualRolling[$m]*$multValue;
                    }
                }

                if ($currencyCheck) {
                    
                    $fPastRollingFCST[$m] = ($fPastRollingFCST[$m]*$newCurrency)/$oldCurrency;

                    if ($type == 'V1') {
                        $manualRolling[$m] = ($manualRolling[$m]*$newCurrency)/$oldCurrency;
                    }
                }

            }
            $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$listOfClients,$rollingFCST,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];
            
            $fcstAmountByStage = $this->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage

            $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage);

            $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);
            $pastRollingFCST = $this->addQuartersAndTotalOnArray($pastRollingFCST);
            
            $manualRolling = $this->addQuartersAndTotal($manualRolling);
            $fPastRollingFCST = $this->addQuartersAndTotal($fPastRollingFCST);
            
            $lastRollingFCST = $this->rollingFCSTByClient($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$listOfClients);//Ibms meses fechados e fw total

            $tmp1 = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$listOfClients,$lastRollingFCST,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $tmp2 = $tmp1['fcstAmount'];

            $lastRollingFCST = $this->addQuartersAndTotalOnArray($lastRollingFCST);

            $lastRollingFCST = $this->addFcstWithBooking($lastRollingFCST,$tmp2);

            $fcstAmountByStage = $this->addLost($con,$listOfClients,$fcstAmountByStage,$value);

            $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage);

            $executiveRF = $this->consolidateAEFcst($rollingFCST);

            $executiveRF = $this->closedMonthEx($executiveRF,$executiveRevenueCYear);
            $pending = $this->subArrays($executiveRF,$executiveRevenueCYear);
            $RFvsTarget = $this->subArrays($executiveRF,$targetValues);
            $targetAchievement = $this->divArrays($executiveRF,$targetValues);

            $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

            $fcstAmountByStage = $this->adjustFcstAmountByStage($fcstAmountByStage);

            $fcstAmountByStageEx = $this->adjustFcstAmountByStageEx($fcstAmountByStageEx);

            if ($value == 'gross') {
                $valueView = 'Gross';
            }elseif($value == 'net'){
                $valueView = 'Net';
            }else{
                $valueView = 'Net Net';
            }

            $rtr = array(
                            "cYear" => $year,
                            "pYear" => ($year-1),
                            "readable" => $readable,

                            "client" => $listOfClients,
                            "targetValues" => $targetValues,

                            "manualRolling" => $manualRolling,
                            "rollingFCST" => $rollingFCST,
                            "lastRollingFCST" => $lastRollingFCST,
                            "clientRevenueCYear" => $clientRevenueCYear,
                            "clientRevenuePYear" => $clientRevenuePYear,

                            "pastExecutiveRF" => $fPastRollingFCST,
                            "executiveRF" => $executiveRF,
                            "executiveRevenuePYear" => $executiveRevenuePYear,
                            "executiveRevenueCYear" => $executiveRevenueCYear,

                            "pending" => $pending,
                            "RFvsTarget" => $RFvsTarget,
                            "targetAchievement" => $targetAchievement,
                        
                            "currency" => $currency, 
                            "value" => $value,
                            "region" => $regionID,

                            "currencyName" => $currencyName,
                            "valueView" => $valueView,
                            "currency" => $currencyName,
                            "value" => $valueView,
                            "fcstAmountByStage" => $fcstAmountByStage,
                            "fcstAmountByStageEx" => $fcstAmountByStageEx,
                        );

        }else{
            $rtr = null;
        }

        return $rtr;

    }

    public function adjustFcstAmountByStageEx($fcstAmountByStageEx){

        $fcstAmountByStageEx[0][6] = 'Total';
        $fcstAmountByStageEx[0][7] = 'Var(%)';

        $fcstAmountByStageEx[1][6] = $fcstAmountByStageEx[1][0]+$fcstAmountByStageEx[1][1]+$fcstAmountByStageEx[1][2]+$fcstAmountByStageEx[1][3]+$fcstAmountByStageEx[1][4];

        if ($fcstAmountByStageEx[1][6] == 0) {
            $fcstAmountByStageEx[1][7] = 0.0;
        }else{
            $fcstAmountByStageEx[1][7] = ($fcstAmountByStageEx[1][4]/$fcstAmountByStageEx[1][6])*100;
        }

        return $fcstAmountByStageEx;   
    }

    public function adjustFcstAmountByStage($fcstAmountByStage){

        for ($c=0; $c < sizeof($fcstAmountByStage); $c++) {
            if ($fcstAmountByStage[$c]) {
                $fcstAmountByStage[$c][0][6] = 'Total';
                $fcstAmountByStage[$c][1][6] = $fcstAmountByStage[$c][1][0] + $fcstAmountByStage[$c][1][1] + $fcstAmountByStage[$c][1][2] + $fcstAmountByStage[$c][1][3] + $fcstAmountByStage[$c][1][4];

                $fcstAmountByStage[$c][0][7] = 'Var(%)';
                if ($fcstAmountByStage[$c][1][6] != 0) {
                    $fcstAmountByStage[$c][1][7] = ($fcstAmountByStage[$c][1][4]/$fcstAmountByStage[$c][1][6])*100;
                }else{
                    $fcstAmountByStage[$c][1][7] = 0;
                }
            }
        }

        return $fcstAmountByStage;
    }

    /*
        START ARRAY FUNCTIONS
    */

    public function subArrays($array1,$array2){
        $exit = array();

        for ($a=0; $a < sizeof($array1); $a++) { 
            $exit[$a] = $array1[$a] - $array2[$a];
        }

        return $exit;
    }

    public function divArrays($array1,$array2){
        $exit = array();

        for ($a=0; $a <sizeof($array1) ; $a++) { 
            if ($array2[$a] != 0) {
                $exit[$a] = ($array1[$a] / $array2[$a])*100;
            }else{
                $exit[$a] = 0;
            }
        }

        return $exit;
    }

    public function addQuartersAndTotalOnArray($array){
        
        for ($a=0; $a < sizeof($array); $a++) { 
            $newArray[$a] = $this->addQuartersAndTotal($array[$a]);
        }

        return $newArray;
    }

    /*
        END ARRAY FUNCTIONS
    */

    /*
        START DATE BASED FUNCTIONS
    */

    public function closedMonthEx($fcst,$booking){
        $date = date('n')-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }

        for ($m=0; $m < $date; $m++) {
            $fcst[$m] = $booking[$m];
        }

        $fcst[3] = $fcst[0] + $fcst[1] + $fcst[2];
        $fcst[7] = $fcst[4] + $fcst[5] + $fcst[6];
        $fcst[11] = $fcst[8] + $fcst[9] + $fcst[10];
        $fcst[15] = $fcst[12] + $fcst[13] + $fcst[14];

        $fcst[16] = $fcst[3] + $fcst[7] + $fcst[11] + $fcst[15];

        return $fcst;
    }

    public function matchMonthWithArray($monthOPP){
        $base = new base();

        $month = $base->month;
        $monthWQ = $base->monthWQ;
        
        for ($m=0; $m < sizeof($monthOPP); $m++) { 
            for ($o=0; $o < sizeof($month); $o++) { 
                if($monthOPP[$m] == $month[$o][1]){
                    $seek[$m] = $month[$o][0];
                    break;
                }
            }

            for ($n=0; $n < sizeof($monthWQ); $n++) { 
                if( $seek[$m] == strtoupper($monthWQ[$n]) ){
                    $pivot[$m] = $n;
                    break;
                }
            }
        }
        return $pivot;
        
    }

    public function periodOfOpp($opp,$year){
        if($opp){
            for ($o=0; $o < sizeof($opp); $o++){                 
                $period[$o] = $this->monthOPP($opp[$o],$year);       
            }
        }else{
            $period = false;
        }

        return $period;
    }

    public function monthOPP($opp,$year){
        
        $start = intval( $opp['fromDate'] );
        $end = intval( $opp['toDate'] );

        $yearStart = intval( $opp['yearFrom'] );
        $yearEnd = intval( $opp['yearTo'] );

        if($yearStart == $yearEnd){
            $month = array();
            for ($m = $start; $m <= $end; $m++) { 
                array_push($month, $m);
            }   
        }else{
            $month = false;
            if($year == $yearStart){
                $month = array();
                for ($m = $start; $m <= 12; $m++) { 
                    array_push($month, $m);
                }    
            }else{
                $month = array();
                for ($m = 1; $m <= $end; $m++) { 
                    array_push($month, $m);
                }
            }
        }        

        $month = $this->matchMonthWithArray($month);
        
        return $month;

    }

    public function monthAnalise($base){
        
        $month = date('M');

        $tmp = false;
        
        for ($m=0; $m < sizeof($base->monthWQ); $m++) { 
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

    /*
        END DATE BASED FUNCTIONS
    */

    /*
        START FORECAST FUNCTIONS
    */

    public function calculateForecast($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$rollingFCST,$lastYearRevClient,$lastYearRevSalesRep,$lastYearRevCompany){

        if($currency == "USD"){
            $div = 1;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($regionID),array($year));
        }

        if($value == "gross"){
            $fwColumn = "gross_revenue";
            $sfColumn = $fwColumn;
        }else{
            $fwColumn = "net_revenue";
            $sfColumn = $fwColumn;
        }        

        for ($c=0; $c < sizeof($clients); $c++) {
            // PERIOD OF FCST , VALUES AND STAGE
            $someFCST[$c] = $this->getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients[$c],$div); 
            //var_dump($someFCST);
            $monthOPP[$c] = $this->periodOfOPP($someFCST[$c],$year); // MONTHS OF THE FCST
            
            if($monthOPP[$c]){
                $shareSalesRep[$c] = $this->salesRepShareOnPeriod($lastYearRevCompany,$lastYearRevSalesRep,$lastYearRevClient[$c],$monthOPP[$c],$someFCST[$c]);
                $fcst[$c] = $this->fillFCST($someFCST[$c],$monthOPP[$c],$shareSalesRep[$c]);
            }else{
                $shareSalesRep[$c] = false;
                $fcst[$c] = false;
            }
            if($fcst[$c]){
                $fcst[$c] = $this->adjustValues($fcst[$c]);
                $fcstAmountByStage[$c] = $this->fcstAmountByStage($fcst[$c],$monthOPP[$c]);
                $fcstAmount[$c] = $this->fcstAmount($fcst[$c],$monthOPP[$c]);
                $fcstAmount[$c] = $this->adjustValuesForecastAmount($fcstAmount[$c]);
            }else{
                $fcstAmountByStage[$c] = false;
                $fcstAmount[$c] = false;
            }
        
            if ($fcstAmountByStage[$c]) {
                if ($fcstAmountByStage[$c][1][4] > 0) {
                    var_dump("expression");
                }
            }

        }
        
        $rtr = array("fcstAmount" => $fcstAmount ,"fcstAmountByStage" => $fcstAmountByStage);


        return $rtr;        
    }

    public function makeFcstAmountByStageEx($fcstAmountByStage){
        
        $resp[0] = array('1','2','3','4','5','6');
        $resp[1] = array(0.0,0.0,0.0,0.0,0.0,0.0);

        for ($c=0; $c < sizeof($fcstAmountByStage); $c++) { 
            
            $resp[1][0] += $fcstAmountByStage[$c][1][0];
            $resp[1][1] += $fcstAmountByStage[$c][1][1];
            $resp[1][2] += $fcstAmountByStage[$c][1][2];
            $resp[1][3] += $fcstAmountByStage[$c][1][3];
            $resp[1][4] += $fcstAmountByStage[$c][1][4];
            $resp[1][5] += $fcstAmountByStage[$c][1][5];
        }

        return $resp;
    }

    public function fcstAmount($fcst,$mOPP){
        $base = new base();
        $monthWQ = $base->monthWQ;
        for ($m=0; $m < sizeof($monthWQ); $m++) { 
            $fcstAmount[$m] = 0.0;
        }
       for ($m=0; $m < sizeof($mOPP); $m++) { 
           for ($n=0; $n < sizeof($mOPP[$m]); $n++) { 
                    
               $fcstAmount[$mOPP[$m][$n]] += ($fcst[$m][$mOPP[$m][$n]]['value']);
           }
       }

       return $fcstAmount;

    }

    public function fcstAmountByStage($fcst,$mOPP){

        $stages = array( 0 => "1" , 1 => "2" , 2 => array("3a","3b") , 3 => "4" , 4 => "5" , 5 => "6" );
        $stagesToView = array( 0 => "1" , 1 => "2" , 2 => "3" , 3 => "4" , 4 => "5" , 5 => "6" );
        for ($s=0; $s < sizeof($stages); $s++) { 
            $amountByStage[$s] = 0.0;
        }

        for ($s=0; $s < sizeof($stages); $s++) { 
            if(isset($stages[$s]) && is_array($stages[$s])){                
                for ($m=0; $m < sizeof($mOPP); $m++) { 
                    for ($f=0; $f < sizeof($mOPP[$m]); $f++) { 
                        if($fcst[$m][$mOPP[$m][$f]]['stage'] == "3a" ||
                           $fcst[$m][$mOPP[$m][$f]]['stage'] == "3b"
                        ){
                            $amountByStage[$s] += $fcst[$m][$mOPP[$m][$f]]['value'];
                        }
                    }
                }
            }else{
                for ($m=0; $m < sizeof($mOPP); $m++) { 
                    for ($f=0; $f < sizeof($mOPP[$m]); $f++) { 
                        if( $fcst[$m][$mOPP[$m][$f]]['stage'] == $stages[$s] ){
                            $amountByStage[$s] += $fcst[$m][$mOPP[$m][$f]]['value'];
                        }
                    }
                }
            }
        }

        $rtr = array($stagesToView,$amountByStage);

        return $rtr;

    }

    public function fillFCST($sFCST,$mOPP,$sRP){

        $base = new base();

        $monthWQ = $base->monthWQ;

        for ($i=0; $i < sizeof($sFCST); $i++){
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $fcst[$i][$m]['stage'] = false;
                $fcst[$i][$m]['value'] = 0.0;
            }
        }

        for ($i=0; $i < sizeof($sFCST); $i++){
            
            $factor = 1;

            $adjustedValue = $sFCST[$i]['sumValue']*$factor;
            for ($j=0; $j < sizeof($mOPP[$i]); $j++) { 
                $fcst[$i][$mOPP[$i][$j]]['stage'] = $sFCST[$i]['stage'];

                $fcst[$i][$mOPP[$i][$j]]['value'] = ( $adjustedValue * $sRP[$i][$j] );
                
            }   

        }
        

        return $fcst;
    }

    public function consolidateAEFcst($matrix){
        $return = array();

        $test = intval( date('n') );
        //var_dump($matrix);

        if ($test < 4) {
            $test++;
        }elseif ($test < 7) {
            $test += 2;
        }elseif ($test < 10) {
            $test += 3;
        }else{
            $test += 4;
        }

        for ($m=0; $m < sizeof($matrix[0]); $m++) { 
            $return[$m] = 0;
        }

        for ($c=0; $c < sizeof($matrix); $c++) { 
            for ($m=0; $m < sizeof($matrix[$c]); $m++) { 
                $return[$m] += $matrix[$c][$m];
            }
        }

        //$return[16] = $return[3] + $return[7] + $return[11] + $return[15];        

        return $return;
    }

    /*
        END FORECAST FUNCTIONS
    */

    /*
        START ROLLING FORECAST FUNCTIONS
    */

    public function rollingFCSTByClient($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients){

        $currentYear = intval(date('Y'));
        $currentMonth = intval( date("m") );

        if($currency == "USD"){
            $div = 1;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($regionID),array($year));
        }

        //var_dump($div);

        if($value == "gross"){
            $ytdColumn = "gross_revenue_prate";
            $fwColumn = "gross_revenue";
        }else{
            $ytdColumn = "net_revenue_prate";
            $fwColumn = "net_revenue";
        }

        $table = "ytd";

        for ($c=0; $c < sizeof($clients); $c++) {
            
            $mult = 1;

            for ($m=0; $m < sizeof($month); $m++) {                 
                /*
                        FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
                */
                if($year == $currentYear){

                    $from = array("sumValue");

                    if( $month[$m][1] < $currentMonth ){
                        $select[$c][$m] = "
                                            SELECT SUM($ytdColumn) AS sumValue
                                            FROM $table
                                            WHERE (client_id = \"".$clients[$c]['clientID']."\")
                                            AND (month = \"".$month[$m][1]."\")
                                            AND (year = \"".$year."\")
                                            ";

                        $res[$c][$m] = $con->query($select[$c][$m]);
                        $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div*$mult;

                        $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                            FROM fw_digital
                                            WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                            AND (month = \"".$month[$m][1]."\")
                                            AND (year = \"".$year."\")
                                            ";

                        $resFW[$c][$m] = $con->query($selectFW[$c][$m]);
                        $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div*$mult; 

                    }else{
                        $revACT[$c][$m] = 0.0;
                        $revFW[$c][$m] = 0.0;
                    }                    
                    
                    $rev[$c][$m] = $revACT[$c][$m];

                    if( !is_null($revFW[$c][$m]) ){
                        $rev[$c][$m] += $revFW[$c][$m];
                    }       

                }else{
                    $from = array("sumValue");
                    $select[$c][$m] = "
                                        SELECT SUM($ytdColumn) AS sumValue
                                        FROM $table
                                        WHERE (client_id = \"".$clients[$c]['clientID']."\")
                                        AND (month = \"".$month[$m][1]."\")
                                        AND (year = \"".$year."\")

                                      ";                                                
                    $res[$c][$m] = $con->query($select[$c][$m]);
                    $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   

                    $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                        FROM fw_digital
                                        WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                        AND (month = \"".$month[$m][1]."\")
                                        AND (year = \"".$year."\")
                                        ";

                    $resFW[$c][$m] = $con->query($selectFW[$c][$m]);
                    $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;   

                    $rev[$c][$m] = $revACT[$c][$m];

                    if( !is_null($revFW[$c][$m]) ){
                        $rev[$c][$m] += $revFW[$c][$m];
                    }       
                }
            }

        }

        return $rev;

    }

    /*
        END ROLLING FORECAST FUNCTIONS
    */

    /*
        START CONTROL VALUES FUNCTIONS
    */

    public function addLost($con,$clients,$fcstStages,$value){

        $sql = new sql();

        if ($value == "gross") {
            $sum = "gross_revenue";
        }else{
            $sum = "net_revenue";
        }

        for ($c=0; $c < sizeof($clients); $c++) { 
            $select[$c] = "SELECT SUM($sum) AS value FROM sf_pr WHERE stage = \"6\" AND client_id = \"".$clients[$c]["clientID"]."\"";

            $res = $con->query($select[$c]);

            $result[$c] = $sql->fetchSum($res,"value");
            
            $fcstStages[$c][1][5] = $result[$c]['value'];
        }

        return $fcstStages;
    }

    public function addFcstWithBooking($booking,$fcst){

        for ($c=0; $c < sizeof($booking); $c++) { 
            for ($f=0; $f < sizeof($booking[$c]); $f++) { 
                
                $sum[$c][$f] = $booking[$c][$f];
                
                if($fcst[$c]){
                    $sum[$c][$f] += $fcst[$c][$f];
                }
                
            }
        }        

        return $sum;
    }

    public function addClosed($fcstAmountByStage,$rollingFCST){

        $fechado = date('n') - 1;

        if ($fechado < 3) {
        }elseif ($fechado < 6) {
            $fechado ++;
        }elseif ($fechado < 9) {
            $fechado += 2;
        }else{
            $fechado += 3;
        }

        for ($c=0; $c < sizeof($fcstAmountByStage); $c++) { 
            if (!$fcstAmountByStage[$c]) {
                $fcstAmountByStage[$c][0] = array('1','2','3','4','5','6');
                $fcstAmountByStage[$c][1] = array(0.0,0.0,0.0,0.0,0.0,0.0);
            }

            for ($m=0; $m < $fechado; $m++) {
                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 || $m == 16) {
                }else{
                    $fcstAmountByStage[$c][1][4] += $rollingFCST[$c][$m];
                }
            }
        }
        
        return $fcstAmountByStage;
    }

    public function adjustValuesForecastAmount($fcst){

        $fcst[3]  =  $fcst[0] + $fcst[1] + $fcst[2]; // Q1

        $fcst[7]  =  $fcst[4] + $fcst[5] + $fcst[6]; // Q2

        $fcst[11] =  $fcst[8] + $fcst[9] + $fcst[10]; // Q3

        $fcst[15] =  $fcst[12] + $fcst[13] + $fcst[14]; // Q4

        $fcst[16] =  $fcst[3] + $fcst[7] + 
                                  $fcst[11] + $fcst[15];// TOTAL            

        return $fcst;
        
    }

    public function adjustValues($fcst){

        for ($f=0; $f < sizeof($fcst); $f++) { 
            
            $fcst[$f][3]['value']  =  $fcst[$f][0]['value'] + $fcst[$f][1]['value'] + $fcst[$f][2]['value']; // Q1
            $fcst[$f][3]['stage'] = true;

            $fcst[$f][7]['value']  =  $fcst[$f][4]['value'] + $fcst[$f][5]['value'] + $fcst[$f][6]['value']; // Q2
            $fcst[$f][7]['stage'] = true;

            $fcst[$f][11]['value'] =  $fcst[$f][8]['value'] + $fcst[$f][9]['value'] + $fcst[$f][10]['value']; // Q3
            $fcst[$f][11]['stage'] = true;

            $fcst[$f][15]['value'] =  $fcst[$f][12]['value'] + $fcst[$f][13]['value'] + $fcst[$f][14]['value']; // Q4
            $fcst[$f][15]['stage'] = true;

            $fcst[$f][16]['value'] =  $fcst[$f][3]['value'] + $fcst[$f][7]['value'] + 
                                      $fcst[$f][11]['value'] + $fcst[$f][15]['value'];// TOTAL            
            $fcst[$f][16]['stage'] = true;
        }

        return $fcst;
    }

    public function addQuartersAndTotal($tgt){
        //JAN,FEB,MAR
        $tgtWQ[0] = $tgt[0];
        $tgtWQ[1] = $tgt[1];
        $tgtWQ[2] = $tgt[2];

        // Q1
        $tgtWQ[3] = $tgtWQ[0]+$tgtWQ[1]+$tgtWQ[2];

        //APR,MAI,JUN
        $tgtWQ[4] = $tgt[3];
        $tgtWQ[5] = $tgt[4];
        $tgtWQ[6] = $tgt[5];

        // Q2
        $tgtWQ[7] = $tgtWQ[4]+$tgtWQ[5]+$tgtWQ[6];

        //JUL,AUG,SEP
        $tgtWQ[8] = $tgt[6];
        $tgtWQ[9] = $tgt[7];
        $tgtWQ[10] = $tgt[8];

        // Q3
        $tgtWQ[11] = $tgtWQ[8]+$tgtWQ[9]+$tgtWQ[10];

        //OCT,NOV,DEC
        $tgtWQ[12] = $tgt[9];
        $tgtWQ[13] = $tgt[10];
        $tgtWQ[14] = $tgt[11];

        // Q4
        $tgtWQ[15] = $tgtWQ[12]+$tgtWQ[13]+$tgtWQ[14];  

        $tgtWQ[16] = $tgtWQ[3]+$tgtWQ[7]+$tgtWQ[11]+$tgtWQ[15];  

        return $tgtWQ;

    }

    public function mergeTarget($plan,$month){

        for ($m=0; $m < sizeof($month); $m++) { 
            $mergeTarget[$m] = 0.0;
        }

        for ($m=0; $m < sizeof($mergeTarget); $m++) { // SIZE OF MONTH
            for ($c=0; $c < sizeof($plan); $c++) { //SIZE OF BRAND
              $mergeTarget[$m] += $plan[$c][$m];                
            }
        }

        $mergeTarget = $this->addQuartersAndTotal($mergeTarget);

        return $mergeTarget;
    }

    /*
        END CONTROL VALUES FUNCTIONS
    */

    public function generateID($con,$kind,$region,$year,$currency,$value,$week,$month,$user){

        $pr = new pRate();
        $sql = new sql();

        if($kind == "save"){
            $string = "SAV";
        }else{
            $string = "TRS";
        }
       
        $value = strtoupper($value);

        $string .= "-".preg_replace('/\s+/', '', $region).
                   "-".$year.
                   "-".$month.                   
                   "-WEEK-".$week.                   
                   "-".preg_replace('/\s+/', '', $user).
                   "-".$currency.
                   "-".$value
                   
                ;

        return $string;
    }

    public function salesRepShareOnPeriod($lyRCompany ,$lyRSP,$lyRClient,$monthOPP,$someF){
        
        /*

            GET INFO FROM 2018 AND MAKE SHARE BY MONTH WHEN THERE IS NO CLIENT OR SALES REP

        */        
            
        
        for ($l=0; $l < sizeof($monthOPP); $l++){
            $amount[$l] = 0.0;
            for ($m=0; $m < sizeof($monthOPP[$l]); $m++) { 
                if($lyRClient[$monthOPP[$l][$m]] > 0 && $lyRClient[16] > 0){
                    /*
                        GET THE SHARE OF THE CLIENT ON THE SAME MONTH ON LAST YEAR
                    */
                    $share[$l][$m] = $lyRClient[$monthOPP[$l][$m]];//$lyRClient[16];
                    $amount[$l] += $share[$l][$m];
                }elseif($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                    /*
                        IF THE CLINET DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE REP ON THE SAME MONTH ON LAST YEAR
                    */
                    if($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                        $share[$l][$m] = $lyRSP[$monthOPP[$l][$m]];//$lyRSP[16];  
                        $amount[$l] += $share[$l][$m];
                    }else{
                        /*
                            IF THE SALES REP DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE MONTH ON THE  ON LAST YEAR
                        */
                        $share[$l][$m] = $lyRCompany[$monthOPP[$l][$m]];//$lyRCompany[16];
                        $amount[$l] += $share[$l][$m];
                    }
                }
            }

            $newAmount[$l] = $amount[$l];// / sizeof($monthOPP[$l]);
        }
       
        for ($s=0; $s < sizeof($share); $s++) { 
            for ($t=0; $t < sizeof($share[$s]); $t++) { 
               
                $share[$s][$t] = $share[$s][$t] / ( $newAmount[$s] );

            }
        }        



        return $share;
    }

    public function getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$div){
        
        $from = array($sfColumn,'from_date','to_date','year_from','year_to','stage','oppid');
        $to = array("sumValue",'fromDate','toDate','yearFrom','yearTo','stage','oppid');
        
        
        /* SF FCST FROM OTHER REGIONS , WHERE THERE IS NOT AE SPLITT SALES */
        $select = " SELECT oppid, from_date , to_date,year_from, year_to,stage , stage , $sfColumn
                    FROM sf_pr
                    WHERE (client_id = \"".$clients['clientID']."\")
                    AND (stage != '5' && stage != '6')
                  ";
    
        $res = $con->query($select);
        $rev = $sql->fetch($res,$from,$to);

        if ($rev) {
            for ($r=0; $r < sizeof($rev); $r++) { 
                $rev[$r]["sumValue"] = doubleval($rev[$r]["sumValue"])*$div;
            }
        }

        return $rev;

    }

    public function getBookingExecutive($con,$sql,$month,$region,$year,$value,$currency,$pr){

        if($value == "gross"){
            $ytdColumn = "gross_revenue_prate";
            $fwColumn = "gross_revenue";
        }else{
            $ytdColumn = "net_revenue_prate";
            $fwColumn = "net_revenue";
        }

        if($currency == "USD"){
            $div = 1;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($region),array($year));
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            $select[$m] = "SELECT SUM($ytdColumn) AS sumValue
                                FROM ytd
                                WHERE  (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")";

            $selectFW[$m] = "SELECT SUM($fwColumn) AS sumValue 
                                FROM fw_digital
                                WHERE (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")";            

            $res[$m] = $con->query($select[$m]);
            $resFW[$m] = $con->query($selectFW[$m]);

            $from = array("sumValue");

            $rev[$m] = $sql->fetch($res[$m],$from,$from)[0]['sumValue']*$div;                    
            $revFW[$m] = $sql->fetch($resFW[$m],$from,$from)[0]['sumValue']*$div;  

            if ($revFW[$m]) {
                $rev[$m] += $revFW[$m];
            }                  
        
        }

        return $rev;
    }

    public function revenueByClient($con,$sql,$base,$pr,$regionID,$year,$month,$currency,$currencyID,$value,$clients,$cYear){

        if($currency == "USD"){
            $div = 1;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($regionID),array($cYear));
        }

        if($value == "gross"){
            $ytdColumn = "gross_revenue_prate";
            $fwColumn = "gross_revenue";            
        }else{
            $ytdColumn = "net_revenue_prate";
            $fwColumn = "net_revenue";
        }

        $table = "ytd";
        $tableFW = "fw_digital"; 


        for ($c=0; $c < sizeof($clients); $c++) { 
              
            for ($m=0; $m < sizeof($month); $m++) {                 
                /*
                        FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
                */
                $select[$c][$m] = "
                                SELECT SUM($ytdColumn) AS sumValue
                                FROM $table
                                WHERE (client_id = \"".$clients[$c]['clientID']."\")
                                AND (month = \"".$month[$m][1]."\")                                    
                                AND (year = \"".$year."\")

                              ";
                $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                FROM $tableFW
                                WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                AND (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")
                                ";

                $res[$c][$m] = $con->query($select[$c][$m]);
                $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

                $from = array("sumValue");

                $rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   
                $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;                    

                if(!is_null($revFW[$c][$m])){
                    $rev[$c][$m] += ($revFW[$c][$m]);
                }

            }
        }

        return $rev;

    }

    public function listClientsByVPMonth($con,$sql,$year,$regionID){

        //GET FROM SALES FORCE
        $sf = "SELECT DISTINCT c.name AS 'clientName',
               c.ID AS 'clientID'
               FROM sf_pr s
               LEFT JOIN client c ON c.ID = s.client_id
               WHERE ( region_id = \"".$regionID."\") 
               AND ( stage != \"6\") AND ( stage != \"5\")
               ORDER BY 1
        ";

        $resSF = $con->query($sf);
        $from = array("clientName","clientID");
        $listSF = $sql->fetch($resSF,$from,$from);

        //GET FROM IBMS/BTS
        $ytd = "SELECT DISTINCT c.name AS 'clientName',
                c.ID AS 'clientID'
                FROM ytd y
                LEFT JOIN client c ON c.ID = y.client_id
                LEFT JOIN region r ON r.ID = y.sales_representant_office_id
                    WHERE (y.year = \"$year\" )
                    AND (r.ID = \"".$regionID."\")
                    ORDER BY 1
        ";

        $resYTD = $con->query($ytd);
        $from = array("clientName","clientID");
        $listYTD = $sql->fetch($resYTD,$from,$from);
        
        $count = 0;
        if($listSF){
            for ($sff=0; $sff < sizeof($listSF); $sff++) { 
                $list[$count] = $listSF[$sff];
                $count ++;
            }
        }
        if($listYTD){
            for ($y=0; $y < sizeof($listYTD); $y++) { 
                $list[$count] = $listYTD[$y];
                $count ++;
            }
        }
        
        $list = array_map("unserialize", array_unique(array_map("serialize", $list)));
        
        $list = array_values($list);

        usort($list, array($this,'orderClient'));

        return $list;

    }

    private static function orderClient($a, $b){
        
        if ($a == $b)
            return 0;
        
        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }

    public function verifySaves($con,$sql,$regionID){
        $date = date('Y-m-d');
        $tmp = explode("-", $date);
        $month = $tmp[1];

        $from = array("ID");

        $select = "SELECT ID from forecast WHERE month = \"".$month."\" AND type_of_forecast = \"V2\" AND region_id = \"".$regionID."\"";

        if ($regionID == 1) {
            $week = $this->weekOfMonth($date);
            $select .= " AND read_q = \"".$week."\"";
        }

        $res = $con->query($select);

        $resp = $sql->fetch($res,$from,$from)[0]["ID"];

        return $resp;

    }

}
