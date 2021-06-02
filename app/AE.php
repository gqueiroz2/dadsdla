<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;
use App\pRate;

class AE extends pAndR{
    
    public function insertUpdate($con,$oppid,$region,$salesRep,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClient,$list,$splitted,$submit,$brandPerClient){
        
        $sql = new sql();
        $sr = new salesRep();
        $tmp = explode("-", $date);

        if($tmp && isset($tmp[1])){
            $month = $tmp[1];
        }else{
            $month = 0;
        }

        $user = Request::session()->get('userName');

        if ($submit == "submit") {
            $submit = 1;
            $selectSubmit = "SELECT ID FROM forecast WHERE  sales_rep_id = \"".$salesRep->id."\" and submitted = \"1\" AND month = \"".intval($month)."\" AND year = \"".$year."\"";
            if ($region == '1') {
                $selectSubmit .=  " AND read_q = \"".intval($read)."\"";
            }

            $from = array("ID");

            $resultSubmit = $con->query($selectSubmit);
            
            $resSubmit = $sql->fetch($resultSubmit,$from,$from)[0]["ID"];

            if ($resSubmit != null) {
                return "Already Submitted";
            }

            $selectVP = "SELECT ID FROM forecast WHERE year = \"".$year."\" AND month = \"".intval($month)."\" AND submitted = \"1\" AND region_id = \"".$region."\" AND type_of_forecast = \"V1\"";

            if ($region == '1') {
                $selectVP .=  " AND read_q = \"".intval($read)."\"";
            }

            $resultsVP = $con->query($selectVP);
            
            $resVP = $sql->fetch($resultsVP,$from,$from)[0]["ID"];


            if ($resVP != null) {
                return "Already Submitted";
            }

        }else{
            $submit = 0;
        }
        
        $tableFCST = "forecast";
        $tableFCSTClient = "forecast_client";
        $tableFCSTSalesRep = "forecast_sales_rep";

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$oppid."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        if ($id && !is_null($id) && $submit == 0 ) {
            $update = "UPDATE $tableFCST SET read_q = \"".$read."\", 
                                            last_modify_by = \"".$user."\",
                                            last_modify_date = \"".$date."\", 
                                            last_modify_time = \"".$time."\", 
                                            oppid = \"".$oppid."\",
                                            currency_id = \"".$currency['id']."\", 
                                            year = \"".$year."\", 
                                            type_of_value = \"".$value."\",
                                            month = \"".$month."\" WHERE ID = \"".$id."\"";
            
            if($con->query($update) === true){

            }else{
                var_dump($con->error);
                return false;
            }

            $updateFCSTSalesRep = $this->updateFCSTSalesRep($con,$salesRep,$manualEstimantionBySalesRep,$tableFCSTSalesRep);

            $updateFCSTClient = $this->updateFCSTClient($con,$salesRep,$manualEstimantionByClient,$tableFCSTClient,$list,$splitted, $brandPerClient);

            return "Updated";

        }else{

            $columns = "(
                         oppid,
                         region_id, sales_rep_id,
                         year,month, read_q,date_m,
                         currency_id, type_of_value,
                         last_modify_by, last_modify_date, last_modify_time,
                         submitted, type_of_forecast)";

            $salesRepID = $sr->getSalesRepByName($con,$salesRep->salesRep)[0]['id'];
            $values = "(
                        \"".$oppid."\",
                        \"".$region."\",\"".$salesRepID."\",
                        \"".$year."\",\"".$month."\",\"".$read."\",\"".$date."\",
                        \"".$currency['id']."\",\"".$value."\",
                        \"".$user."\",\"".$date."\",\"".$time."\",
                        \"".$submit."\", \"AE\"
                      )";


            $insertFCST = " INSERT INTO $tableFCST $columns VALUES $values";

            if ($con->query($insertFCST) === true) {

            }else{
                var_dump($con->error);
                return false;

            }

            $insertFCSTSalesRep = $this->FCSTSalesRep($con,$oppid,$manualEstimantionBySalesRep,$tableFCSTSalesRep);

            $insertFCSTClient = $this->FCSTClient($con,$oppid,$manualEstimantionByClient,$tableFCSTClient,$list,$splitted,$brandPerClient);
         
            return "Created";
        }
    }

    public function updateFCSTSalesRep($con,$salesRep,$manualEstimantion,$table){
        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE sales_rep_id = \"".$salesRep->id."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        for ($m=0; $m <sizeof($manualEstimantion) ; $m++) { 
            $update[$m] = "UPDATE $table SET value = \"".$manualEstimantion[$m]."\" WHERE month = \"".($m+1)."\" AND forecast_id = \"".$id."\"";

            if ($con->query($update[$m]) === true) {
            
            }else{
                var_dump($con->error);
                return false;
            }
        }
    }

    public function updateFCSTClient($con,$salesRep,$manualEstimantion,$table,$list,$splitted,$brandPerClient){

        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE sales_rep_id = \"".$salesRep->id."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];


        for ($c=0; $c <sizeof($list) ; $c++) { 
            if ($splitted) {
                if ($splitted[$c]->splitted) {
                    $div = 2;
                }else{
                    $div = 1;
                }
            }else{
                $div = 1;
            }
            if (!$splitted || !$splitted[$c]->splitted || $splitted[$c]->owner) {
                for ($m=0; $m <sizeof($manualEstimantion[$c]); $m++) { 
                    $update[$c][$m] = "UPDATE $table SET value = \"".($manualEstimantion[$c][$m])."\", brand = \"".$brandPerClient[$c]."\" WHERE month = \"".($m+1)."\" AND forecast_id = \"".$id."\" AND client_id = \"".$list[$c]->clientID."\" AND agency_id = \"".$list[$c]->agencyID."\"";

                    if ($con->query($update[$c][$m]) === true) {
                        
                    }else{
                        //var_dump($con->error);
                        return false;
                    }
                }
            }
        }
    }

    public function FCSTSalesRep($con,$oppid,$manualEstimantionBySalesRep,$table){
        $currentMonth = intval(date('m')) -1;

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
                //var_dump($con->error);
                return false;
            }
        }
    }

    public function FCSTClient($con,$oppid,$manualEstimantion,$table,$list,$splitted,$brandPerClient){
        $currentMonth = intval(date('m')) -1;
        $sql = new sql();

        $select = "SELECT ID FROM forecast WHERE oppid = \"".$oppid."\"";

        $from = array("ID");

        $result = $con->query($select);

        $id = $sql->fetch($result,$from,$from)[0]["ID"];

        $columns = "(forecast_id,month,value,client_id,brand,agency_id)";
        for ($c=0; $c <sizeof($list) ; $c++) {
            if ($splitted) {
                if ($splitted[$c]->splitted) {
                    $div = 2;
                }else{
                    $div = 1;
                }
            }else{
                $div = 1;
            }
            if (!$splitted || !$splitted[$c]->splitted || $splitted[$c]->owner) {
                for ($m=0; $m <sizeof($manualEstimantion[$c]); $m++) { 
                    $values[$c][$m] = "(\"".$id."\" ,\"".($m+1)."\",\"".($manualEstimantion[$c][$m])."\",\"".$list[$c]->clientID."\",\"".$brandPerClient[$c]."\",\"".$list[$c]->agencyID."\")";

                    $insert[$c][$m] = "INSERT INTO $table $columns VALUES ".$values[$c][$m]."";

                    if ($con->query($insert[$c][$m]) === true) {
                        
                    }else{
                        return false;
                    }
                }
            }
        }
    }

    public function weekOfMonth($date) {
        $date = strtotime($date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        if ((intval(date("W", $date)) - intval(date("W", $firstOfMonth))) == 0) {
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }else{
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }
    }

    public function generateID($con,$sql,$pr,$kind,$region,$year,$salesRep,$currency,$value,$week,$month){

        if($kind == "save"){
            $string = "SAV";
        }else{
            $string = "TRS";
        }
       
        $value = strtoupper($value);

        $string .= "-".preg_replace('/\s+/', '', $salesRep->region).    
                   "-".$year.
                   "-".$month.                   
                   "-WEEK-".$week.                   
                   "-".preg_replace('/\s+/', '', $salesRep->salesRep).
                   "-".$currency.
                   "-".$value
                   
                ;

        return $string;
    }

    public function baseSaved($con,$r,$pr,$cYear,$regionID,$salesRepID,$currencyID,$value,$manualEstimantionClient){
        $sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();
        
        $pYear = $cYear-1;

        $currencyID = $pr->getCurrencybyName($con,$currencyID)['id'];

        $salesRepID = array($salesRepID);

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE sales_rep_id = \"".$salesRepID[0]."\" AND (submitted = \"0\" OR submitted = \"1\") AND month = \"$actualMonth\" AND year = \"$cYear\"";

        if ($regionID == "1") {
            $select .= "AND read_q = \"$week\"";
        }

        $select .= "ORDER BY last_modify_date DESC";

        $result = $con->query($select);

        $from = array("oppid","ID","type_of_value","currency_id", "submitted");

        $save = $sql->fetch($result,$from,$from);

        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear,$regionID);

        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
        }else{
            $submitted = 0;

            for ($s=0; $s < sizeof($save); $s++) { 
                if ($save[$s]['submitted'] == 1) {
                    $submitted = 1;
                }
            }

            $temp = $base->adaptCurrency($con,$pr,$save,$currencyID,$cYear);
            
            $currencyCheck = $temp["currencyCheck"][0];
            $newCurrency = $temp["newCurrency"][0];
            $oldCurrency = $temp["oldCurrency"][0];

            $temp2 = $base->adaptValue($value,$save,$regionID,$listOfClients);
            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];

        }

        $regionName = $reg->getRegion($con,array($regionID))[0]['name'];

        $salesRep = $sr->getSalesRepById($con,$salesRepID);        

        $brand = $br->getBrandBinary($con);
        $month = $base->getMonth();

        $tmp = array($cYear);
        //valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $this->monthAnalise($base);

        if($regionName == "Brazil"){
            $splitted = $this->isSplitted($con,$sql,$salesRepID,$listOfClients,$cYear,$pYear);
        }else{
            $splitted = false;
        }

        for ($b=0; $b <sizeof($brand); $b++) {
            for ($m=0; $m <sizeof($month) ; $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }

        for ($m=0; $m <sizeof($month) ; $m++) {
            $lastYear[$m] = $this->generateValueWB($con,$sql,$regionID,$pYear,$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$div;
        }
        $lastYear = $this->addQuartersAndTotalOnArray( array($lastYear) )[0];

        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $targetValues[$b][$m] = $this->generateValue($con,$sql,$regionID,$cYear,$brand[$b],$salesRep,$month[$m][1],"value","plan_by_sales",$value)[0]*$div;            
            }
        }

        $mergeTarget = $this->mergeTarget($targetValues,$month);
        $targetValues = $mergeTarget;
        
        $clientRevenueCYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear);

        $clientRevenueCYearTMP = $clientRevenueCYear;

        $clientRevenueCYear = $this->addQuartersAndTotalOnArray($clientRevenueCYear);

        //$clientRevenueDiscovery = $this->revenueByDiscoveryClient($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear);
        //var_dump($clientRevenueDiscovery);

        $clientRevenuePYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear);
        $clientRevenuePYear = $this->addQuartersAndTotalOnArray($clientRevenuePYear);

        $tmp = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr);

        $executiveRevenueCYear = $this->addQuartersAndTotal($tmp);

        $executiveRevenuePYear = $this->consolidateAEFcst($clientRevenuePYear,$splitted);

        if ($save) {

            if ($submitted == 1) {
                $sourceSave = "LAST SUBMITTED";                
            }else{
                $sourceSave = "LAST SAVED";
            }

            $select = array();
            $result = array();

            $from = "value";

            $from2 = array("sales_reps");

            $select2 = "SELECT DISTINCT sales_rep_owner_id AS sales_reps FROM sf_pr WHERE sales_rep_splitter_id = \"".$salesRepID[0]."\"";

            $result2 = $con->query($select2);

            $salesReps = $sql->fetch($result2,$from2,$from2);

            $salesRepsOR = "( f2.sales_rep_id = \"".$salesReps[0]['sales_reps']."\"";

            for ($s=1; $s < sizeof($salesReps) ; $s++) { 
                $salesRepsOR .= " OR f2.sales_rep_id = \"".$salesReps[$s]['sales_reps']."\"";
            }

            $salesRepsOR .= ")";

            $auxYear = date('Y');
            $cMonth = date('n');

            for ($c=0; $c <sizeof($listOfClients) ; $c++) {
                if ($splitted) {
                    if ($splitted[$c]["splitted"]) {
                        $mul = 2;
                    }else{
                        $mul = 1;
                    }
                }else{
                    $mul = 1;
                }


                for ($m=0; $m <12 ; $m++) {
                    $select[$c][$m] = "SELECT SUM(value) AS value FROM forecast_client f LEFT JOIN forecast f2 ON f.forecast_id = f2.ID 
                                        WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\"
                                        AND f.agency_id = \"".$listOfClients[$c]["agencyID"]."\"
                                        AND f.month = \"".($m+1)."\" 
                                        AND f2.month = \"".$cMonth."\"  
                                        AND f2.year = \"".$cYear."\"
                                        AND f2.submitted = \"".$submitted."\"";

                    if ($regionID == "1") {
                        $select[$c][$m] .= " AND read_q = \"".$week."\" AND ".$salesRepsOR[$c]." ";
                    }else{
                        $select[$c][$m] .= " AND ".$salesRepsOR." ";
                    }

                    $result[$c][$m] = $con->query($select[$c][$m]);
                    $saida[$c][$m] = $sql->fetchSum($result[$c][$m],$from);
                }


                if ($saida[$c]) {
                    for ($m=0; $m <sizeof($saida[$c]) ; $m++) { 
                        $rollingFCST[$c][$m] = floatval($saida[$c][$m]['value']);                
                    }
                }else{
                    for ($m=0; $m <12; $m++) { 
                        $rollingFCST[$c][$m] = 0;
                    }
                }
                
                if ($valueCheck) {
                    for ($m=0; $m <sizeof($rollingFCST[$c]) ; $m++) { 
                        $rollingFCST[$c][$m] = $rollingFCST[$c][$m]*$multValue[$c];
                    }
                }

                if ($currencyCheck) {
                    for ($m=0; $m <sizeof($rollingFCST[$c]) ; $m++) { 
                        $rollingFCST[$c][$m] = ($rollingFCST[$c][$m]*$newCurrency)/$oldCurrency;
                    }
                }
                
            }

            $tmpRollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmpRollingFCST = $this->addQuartersAndTotalOnArray($tmpRollingFCST);

            $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $tmpRollingFCST = $this->addFcstWithBooking($tmpRollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);

            for ($r=0; $r <sizeof($rollingFCST) ; $r++) { 
                if ($rollingFCST[$r][16] == 0) {
                    $rollingFCST[$r]=$tmpRollingFCST[$r];
                }
            }

            $rollingFCST = $this->addClosedFcst($rollingFCST,$tmpRollingFCST);

            $rollingFCST = $this->adjustFCST($rollingFCST);

            $fcstAmountByStage = $this->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage
            
            $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage,$splitted);

            $lastRollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmp1 = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$lastRollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $tmp2 = $tmp1['fcstAmount'];

            $emptyCheck = $this->checkEmpty($tmp2);

            $lastRollingFCST = $this->addQuartersAndTotalOnArray($lastRollingFCST);

            $lastRollingFCST = $this->addFcstWithBooking($lastRollingFCST,$tmp2);

            $lastRollingFCST = $this->adjustFCST($lastRollingFCST);


            //$lastRollingFCST = $this->closedMonth($lastRollingFCST,$clientRevenueCYear);
            //$lastRollingFCST = $this->adjustFCST($lastRollingFCST);
            
            //$rollingFCST = $this->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $this->adjustFCST($rollingFCST);

        }else{
            $rollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $emptyCheck = $this->checkEmpty($toRollingFCST);

            $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);

            $rollingFCST = $this->addFcstWithBooking($rollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $this->adjustFCST($rollingFCST);
            
            $fcstAmountByStage = $this->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage
            
            //$rollingFCST = $this->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $this->adjustFCST($rollingFCST);

            $lastRollingFCST = $rollingFCST;
            
        }

        $rollingFCST = $manualEstimantionClient;

        $rollingFCST = $this->adjustFCST($rollingFCST);

        $fcstAmountByStage = $this->addLost($con,$listOfClients,$fcstAmountByStage,$value,$div);
           
        $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage,$splitted);

        $executiveRF = $this->consolidateAEFcst($rollingFCST,$splitted);
        $executiveRF = $this->closedMonthEx($executiveRF,$executiveRevenueCYear);
        $pending = $this->subArrays($executiveRF,$executiveRevenueCYear);
        $RFvsTarget = $this->subArrays($executiveRF,$targetValues);
        $targetAchievement = $this->divArrays($executiveRF,$targetValues);

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $fcstAmountByStage = $this->adjustFcstAmountByStage($fcstAmountByStage);

        $fcstAmountByStageEx = $this->adjustFcstAmountByStageEx($fcstAmountByStageEx);

        $brandsPerClient = $this->getBrandsClient($con, $listOfClients, $salesRep);

        if ($value == 'gross') {
            $valueView = 'Gross';
        }elseif($value == 'net'){
            $valueView = 'Net';
        }else{
            $valueView = 'Net Net';
        }

        $rtr = array(   
                        "cYear" => $cYear,
                        "pYear" => $pYear,
                        "readable" => $readable,

                        "salesRep" => $salesRep[0],
                        "client" => $listOfClients,
                        "splitted" => $splitted,
                        "targetValues" => $targetValues,

                        "rollingFCST" => $rollingFCST,
                        "lastRollingFCST" => $lastRollingFCST,
                        "clientRevenueCYear" => $clientRevenueCYear,
                        "clientRevenuePYear" => $clientRevenuePYear,

                        //"clientRevenueDiscovery" => $clientRevenueDiscovery,

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
                        "brandsPerClient" => $brandsPerClient,
                        "emptyCheck" => $emptyCheck,
                    );

        return $rtr;

    }

    public function baseLoad($con,$r,$pr,$cYear,$pYear, $regionID,$salesRepID,$currencyID,$value){
    	
        $sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();

        $actualMonth = date('n');
        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        //$select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE sales_rep_id = \"".$salesRepID[0]."\" AND (submitted = \"0\" OR submitted = \"1\") AND month = \"$actualMonth\" AND year = \"$cYear\" AND type_of_forecast = \"AE\"";
        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE sales_rep_id = \"".$salesRepID[0]."\"  AND month = \"$actualMonth\" AND year = \"$cYear\" AND type_of_forecast = \"AE\"";

        if ($regionID == "1") {
            $select .= "AND read_q = \"$week\"";
        }

        $select .= "ORDER BY last_modify_date DESC";
        
        $result = $con->query($select);

        $from = array("oppid","ID","type_of_value","currency_id", "submitted");

        $save = $sql->fetch($result,$from,$from);
        
        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear,$regionID);

        if(sizeof($listOfClients) == 0){
            return false;
        }

        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
        }else{
            $submitted = 0;


            for ($s=0; $s < sizeof($save); $s++) { 
                if ($save[$s]['submitted'] == 1) {
                    $submitted = 1;
                }
            }

            $temp[0] = $base->adaptCurrency($con,$pr,$save,$currencyID,$cYear);
            
            $currencyCheck = $temp[0]["currencyCheck"][0];
            $newCurrency = $temp[0]["newCurrency"][0];
            $oldCurrency = $temp[0]["oldCurrency"][0];

            $temp2 = $base->adaptValue($value,$save,$regionID,$listOfClients);

            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];
        }

        $regionName = $reg->getRegion($con,array($regionID))[0]['name'];

        $salesRep = $sr->getSalesRepById($con,$salesRepID);        

        $brandIDS = $this->getBrandsWithOutSony($con,$sql,$salesRepID,$cYear,$regionID);

        $brand = $br->getBrandBinary($con);
        //$brandId = $br->getBrand($con);
        
        $month = $base->getMonth();

        $tmp = array($cYear);
 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        
        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $this->monthAnalise($base);

        if($regionName == "Brazil"){
            $splitted = $this->isSplitted($con,$sql,$salesRepID,$listOfClients,$cYear,$pYear);
        }else{
            $splitted = false;
        }

        for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }

        for ($m=0; $m <sizeof($month) ; $m++) {
            $lastYear[$m] = $this->generateValueWB($con,$sql,$regionID,$pYear,$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$div;
        }
        $lastYear = $this->addQuartersAndTotalOnArray( array($lastYear) )[0];

        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $targetValues[$b][$m] = $this->generateValue($con,$sql,$regionID,$cYear,$brand[$b],$salesRep,$month[$m][1],"value","plan_by_sales",$value)[0]*$div;            
            }
        }

        $mergeTarget = $this->mergeTarget($targetValues,$month);
        $targetValues = $mergeTarget;

        $clientRevenueCYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear);

        $clientRevenueCYearTMP = $clientRevenueCYear;

        $clientRevenueCYear = $this->addQuartersAndTotalOnArray($clientRevenueCYear);

        $revenueDiscovery = $this->revenueByDiscoveryClient($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear,$brandIDS);

        $revenueDiscovery = $this->addQuartersAndTotalOnArray($revenueDiscovery);

        $revenueDiscoveryPYear = $this->revenueByDiscoveryClient($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear,$brandIDS);
        var_dump($revenueDiscoveryPYear);

        $revenueDiscoveryPYear = $this->addQuartersAndTotalOnArray($revenueDiscoveryPYear);

        $revenueSony = $this->revenueBySonyClient($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear,$brand);

        $revenueSony = $this->addQuartersAndTotalOnArray($revenueDiscovery);

        $revenueSonyPYear = $this->revenueBySonyClient($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear,$brand);

        $revenueSonyPYear = $this->addQuartersAndTotalOnArray($revenueSonyPYear);


        $clientRevenuePYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear);

        $clientRevenuePYear = $this->addQuartersAndTotalOnArray($clientRevenuePYear);

        $tmp = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr);

        $executiveRevenueCYear = $this->addQuartersAndTotal($tmp);

        $executiveRevenuePYear = $this->consolidateAEFcst($clientRevenuePYear,$splitted);

        if ($save){

            if ($submitted == 1) {
                $sourceSave = "LAST SUBMITTED";                
            }else{
                $sourceSave = "LAST SAVED";
            }

            $select = array();
            $result = array();

            $from = "value";

            if ($regionID == "1") {
                $from2 = array("sales_reps");

                for ($c=0; $c <sizeof($listOfClients); $c++) { 
                    $select2[$c] = "SELECT DISTINCT sales_rep_owner_id AS sales_reps FROM sf_pr WHERE sales_rep_splitter_id = \"".$salesRepID[0]."\" AND client_id = \"".$listOfClients[$c]["clientID"]."\" AND stage != '5' AND stage != '6' AND stage != '7'";

                    $result2[$c] = $con->query($select2[$c]);

                    $salesReps[$c] = $sql->fetch($result2[$c],$from2,$from2);

                    if ($salesReps[$c]) {
                        $salesRepsOR[$c] = "( f2.sales_rep_id = \"".$salesReps[$c][0]['sales_reps']."\"";
        
                        if (sizeof($salesReps[$c])>1) {

                            for ($s=1; $s < sizeof($salesReps[$c]) ; $s++) { 
                                $salesRepsOR[$c] .= " OR f2.sales_rep_id = \"".$salesReps[$c][$s]['sales_reps']."\"";
                            }
                        }

                        $salesRepsOR[$c] .= ")";
                    }else{
                        $salesRepsOR[$c] = "";
                    }
                }
            }else{
                $salesRepsOR = "sales_rep_id = \"".$salesRepID[0]."\"";
            }

            $auxYear = date('Y');
            $cMonth = date(('n'));

            for ($c=0; $c < sizeof($listOfClients); $c++) {
                if ($splitted) {
                    if ($splitted[$c]["splitted"]) {
                        $mul = 2;
                    }else{
                        $mul = 1;
                    }
                }else{
                    $mul = 1;
                }


                for ($m=0; $m <12 ; $m++) { 
                    $select[$c][$m] = "SELECT SUM(value) AS value FROM forecast_client f LEFT JOIN forecast f2 ON f.forecast_id = f2.ID 
                                        WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\"
                                        AND f.agency_id = \"".$listOfClients[$c]["agencyID"]."\"
                                        AND f.month = \"".($m+1)."\" 
                                        AND f2.month = \"".$cMonth."\"  
                                        AND f2.year = \"".$cYear."\"
                                        AND f2.submitted = \"".$submitted."\"";

                    if ($regionID == "1") {
                        $select[$c][$m] .= " AND read_q = \"".$week."\" AND ".$salesRepsOR[$c]." ";
                    }else{
                        $select[$c][$m] .= " AND ".$salesRepsOR." ";
                    }
                    
                    #echo "<pre>".$select[$c][$m]."</pre>";

                    $result[$c][$m] = $con->query($select[$c][$m]);
                    $saida[$c][$m] = $sql->fetchSum($result[$c][$m],$from);
                }

                //var_dump($saida);

                if ($saida[$c]) {
                    for ($m=0; $m < sizeof($saida[$c]); $m++) { 
                        $rollingFCST[$c][$m] = floatval($saida[$c][$m]['value']);                
                    }
                }else{
                    for ($m=0; $m < 12; $m++) { 
                        $rollingFCST[$c][$m] = 0;
                    }
                }

                if ($valueCheck) {
                    for ($m=0; $m < sizeof($rollingFCST[$c]); $m++) { 
                        $rollingFCST[$c][$m] = $rollingFCST[$c][$m]*$multValue[$c];
                    }
                }

                if ($currencyCheck) {
                    for ($m=0; $m < sizeof($rollingFCST[$c]); $m++) { 
                        $rollingFCST[$c][$m] = ($rollingFCST[$c][$m]*$newCurrency)/$oldCurrency;
                    }
                }
                
            }

            //var_dump($rollingFCST);

            $tmpRollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmpRollingFCST = $this->addQuartersAndTotalOnArray($tmpRollingFCST);

            $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $tmpRollingFCST = $this->addFcstWithBooking($tmpRollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);

            for ($r=0; $r <sizeof($rollingFCST) ; $r++) { 
                if ($rollingFCST[$r][16] == 0) {
                    $rollingFCST[$r]=$tmpRollingFCST[$r];
                }
            }

            $rollingFCST = $this->addClosedFcst($rollingFCST,$tmpRollingFCST);

            $rollingFCST = $this->adjustFCST($rollingFCST);

            $fcstAmountByStage = $this->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage

            $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage,$splitted);
            
            $lastRollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmp1 = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$lastRollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $tmp2 = $tmp1['fcstAmount'];

            $lastRollingFCST = $this->addQuartersAndTotalOnArray($lastRollingFCST);

            $lastRollingFCST = $this->addFcstWithBooking($lastRollingFCST,$tmp2);

            $lastRollingFCST = $this->adjustFCST($lastRollingFCST);

            $emptyCheck = $this->checkEmpty($tmp2);

            //$lastRollingFCST = $this->closedMonth($lastRollingFCST,$clientRevenueCYear);
            //$lastRollingFCST = $this->adjustFCST($lastRollingFCST);
            
            //$rollingFCST = $this->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $this->adjustFCST($rollingFCST);

        }else{
            $sourceSave = "DISCOVERY CRM";
            $rollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);

            $rollingFCST = $this->addFcstWithBooking($rollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $this->adjustFCST($rollingFCST);
            
            $fcstAmountByStage = $this->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage

            $emptyCheck = $this->checkEmpty($toRollingFCST);

            //$rollingFCST = $this->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $this->adjustFCST($rollingFCST);

            $lastRollingFCST = $rollingFCST;
            
        }

        $fcstAmountByStage = $this->addLost($con,$listOfClients,$fcstAmountByStage,$value,$div);
           
        $fcstAmountByStageEx = $this->makeFcstAmountByStageEx($fcstAmountByStage,$splitted);

        $executiveRF = $this->consolidateAEFcst($rollingFCST,$splitted);
        $executiveRF = $this->closedMonthEx($executiveRF,$executiveRevenueCYear);
        $executiveRF = $this->addBookingRollingFCST($executiveRF,$executiveRevenueCYear);
        $pending = $this->subArrays($executiveRF,$executiveRevenueCYear);
        $RFvsTarget = $this->subArrays($executiveRF,$targetValues);
        $targetAchievement = $this->divArrays($executiveRF,$targetValues);

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $fcstAmountByStage = $this->adjustFcstAmountByStage($fcstAmountByStage);

        $fcstAmountByStageEx = $this->adjustFcstAmountByStageEx($fcstAmountByStageEx);

        $brandsPerClient = $this->getBrandsClient($con, $listOfClients, $salesRep);

        if ($value == 'gross') {
            $valueView = 'Gross';
        }elseif($value == 'net'){
            $valueView = 'Net';
        }else{
            $valueView = 'Net Net';
        }

        $secondary = $listOfClients;

        $nSecondary = $this->mergeSecondary($secondary,$rollingFCST,$lastRollingFCST,$clientRevenueCYear,$clientRevenuePYear,$fcstAmountByStage,$revenueDiscovery,$revenueDiscoveryPYear,$revenueSony,$revenueSonyPYear);

        $rtr = array(	
        				"cYear" => $cYear,
        				"pYear" => $pYear,
                        "readable" => $readable,

        				"salesRep" => $salesRep[0],
        				"client" => $listOfClients,
                        "splitted" => $splitted,
        				"targetValues" => $targetValues,

        				"rollingFCST" => $rollingFCST, // ***
                        "lastRollingFCST" => $lastRollingFCST, // ***
        				"clientRevenueCYear" => $clientRevenueCYear, // ***
        				"clientRevenuePYear" => $clientRevenuePYear, // ***

                        "executiveRF" => $executiveRF,
                        "executiveRevenuePYear" => $executiveRevenuePYear,
                        "executiveRevenueCYear" => $executiveRevenueCYear,

                        "revenueDiscovery" => $revenueDiscovery,
                        "revenueDiscoveryPYear" => $revenueDiscoveryPYear,

                        "revenueSony" => $revenueSony,
                        "revenueSonyPYear" => $revenueSonyPYear,

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
                        "fcstAmountByStage" => $fcstAmountByStage, // ***
                        "fcstAmountByStageEx" => $fcstAmountByStageEx,
                        "brandsPerClient" => $brandsPerClient,
                        "sourceSave" => $sourceSave,
                        "emptyCheck" => $emptyCheck,
                        "nSecondary" => $nSecondary,
                    );

        return $rtr;

    }

    public function getBrandsWithOutSony($con,$sql,$salesRepID,$cYear,$regionID){

        $date = date('n')-1;
        $pYear = $cYear - 1;

        $tmp = $salesRepID[0];
        //GET FROM SALES FORCE
       /* $sf = "SELECT DISTINCT s.brand AS 'brand'
                    FROM sf_pr s
                    WHERE ( (s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\")      )
                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
                    ORDER BY 1
               ";       
        $resSF = $con->query($sf);
        $from = array("brand");
        $listSF = $sql->fetch($resSF,$from,$from);*/
        //GET FROM IBMS/BTS
        $ytd = "SELECT DISTINCT b.name AS 'brandName',
                       b.ID AS 'brandID'
                    FROM ytd y
                    LEFT JOIN brand b ON b.ID = y.brand_id
                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
                    WHERE (y.sales_rep_id = \"$tmp\" )
                    AND ((y.year = \"$cYear\") OR (y.year = \"$pYear\") )
                    AND (r.ID = \"".$regionID."\")
                    AND (b.ID != \"21\") AND (b.ID != \"22\") AND (b.ID != \"23\") AND (b.ID != \"24\") AND (b.ID != \"25\") AND (b.ID != \"26\") AND (b.ID != \"27\")
                    ORDER BY 1
               ";
        $resYTD = $con->query($ytd);
        $from = array("brandName","brandID");
        $listYTD = $sql->fetch($resYTD,$from,$from);
        $count = 0;

        $list = array();

       /* if($listSF){
            for ($sff=0; $sff < sizeof($listSF); $sff++) { 
                $list[$count] = $listSF[$sff];
                $count ++;
            }
        }*/
        if($listYTD){
            for ($y=0; $y < sizeof($listYTD); $y++) { 
                $list[$count] = $listYTD[$y];
                $count ++;
            }
        }

        $list = array_map("unserialize", array_unique(array_map("serialize", $list)));
        
        $list = array_values($list);
        
        return $list;

    }

    public function mergeSecondary($secondary,$rollingFCST,$lastRollingFCST,$clientRevenueCYear,$clientRevenuePYear,$fcstAmountByStage,$revenueDiscovery,$revenueDiscoveryPYear,$revenueSony,$revenueSonyPYear){

        
        $nSecondary = $secondary;

        for ($n=0; $n < sizeof($nSecondary); $n++) { 
            $nSecondary[$n]['rollingFCST'] = $rollingFCST[$n];
            $nSecondary[$n]['lastRollingFCST'] = $lastRollingFCST[$n];
            $nSecondary[$n]['clientRevenueCYear'] = $clientRevenueCYear[$n];
            $nSecondary[$n]['clientRevenuePYear'] = $clientRevenuePYear[$n];  
            $nSecondary[$n]['fcstAmountByStage'] = $fcstAmountByStage[$n]; 
            $nSecondary[$n]['revenueDiscovery'] = $revenueSony[$n];
            $nSecondary[$n]['revenueDiscoveryPYear'] = $revenueDiscoveryPYear[$n];
            $nSecondary[$n]['revenueSony'] = $revenueSony[$n];
            $nSecondary[$n]['revenueSonyPYear'] = $revenueSonyPYear[$n];
            $nSecondary[$n]['higherValue'] = $rollingFCST[$n][16];            
        }

        usort($nSecondary, function($a, $b) {
            return $b['higherValue'] <=> $a['higherValue'];
        });
        
        //var_dump($nSecondary);

        return $nSecondary;
        
    }


    public function addBookingRollingFCST($fcst,$booking){
        $date = intval(date('n'))-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }

        for ($d=$date; $d <sizeof($fcst); $d++) {
            $fcst[$d] += $booking[$d];
        }

        $fcst[3] = $fcst[0] + $fcst[1] + $fcst[2];
        $fcst[7] = $fcst[4] + $fcst[5] + $fcst[6];
        $fcst[11] = $fcst[8] + $fcst[9] + $fcst[10];
        $fcst[15] = $fcst[12] + $fcst[13] + $fcst[14];

        $fcst[16] = $fcst[3] + $fcst[7] + $fcst[11] + $fcst[15];

        return $fcst;
    }

    public function checkEmpty($array){
        $outArray = array();

        for ($c=0; $c<sizeof($array); $c++) { 
            if (!$array[$c]) {
                $outArray[$c] = false;
            }else{
                $temp = 0;
                for ($m=0; $m <sizeof($array[$c]);$m++) { 
                    $temp += $array[$c][$m];
                }    
                if ($temp == 0) {
                    $outArray[$c] = false;
                }else{
                    $outArray[$c] = true;
                }
            }
        }

        return $outArray;
    }


    public function getBrandsClient($con,$clients,$salesRep){

        $sql = new sql();

        $from = array("brand");

        for ($c=0; $c < sizeof($clients) ; $c++) { 
            $select[$c] = "SELECT brand FROM sf_pr WHERE client_id = \"".$clients[$c]['clientID']."\" AND sales_rep_splitter_id = \"".$salesRep[0]["id"]."\" AND (stage != \"5\" AND stage != \"6\" AND stage != \"7\")";

            $res[$c] = $con->query($select[$c]);

            $saida[$c] = $sql->fetch($res[$c],$from,$from);
        } 

        return $saida;
    }

    public function addClosedFcst($rolling,$tmpRolling){
        $date = date('n')-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }
        for ($r=0; $r <sizeof($rolling) ; $r++) { 
            for ($m=0; $m <sizeof($rolling[$r]) ; $m++) { 
                if ($m < $date) {
                    $rolling[$r][$m] = $tmpRolling[$r][$m];
                }
            }
        }

        return $rolling;
    }


    public function addLost($con,$clients,$fcstStages,$value,$div){

        $sql = new sql();

        if ($value == "gross") {
            $sum = "gross_revenue";
        }else{
            $sum = "net_revenue";
        }

        for ($c=0; $c <sizeof($clients) ; $c++) { 
            $select[$c] = "SELECT SUM($sum) AS value FROM sf_pr WHERE stage = \"6\" AND client_id = \"".$clients[$c]["clientID"]."\"";

            $res = $con->query($select[$c]);

            $result[$c] = $sql->fetchSum($res,"value");
            
            $fcstStages[$c][1][5] = $result[$c]['value']*$div;
        }

        return $fcstStages;
    }

    public function closedMonth($fcst,$booking){
        $date = date('n')-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }

        for ($c=0; $c <sizeof($fcst) ; $c++) { 
            for ($m=0; $m <$date ; $m++) { 
                $fcst[$c][$m] += $booking[$c][$m];   
            }
        }
        return $fcst;
    }

    public function closedMonthEx($fcst,$booking){
        $date = date('n')-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }

        for ($m=0; $m <$date ; $m++) { 
            $fcst[$m] = $booking[$m];
        }

        $fcst[3] = $fcst[0] + $fcst[1] + $fcst[2];
        $fcst[7] = $fcst[4] + $fcst[5] + $fcst[6];
        $fcst[11] = $fcst[8] + $fcst[9] + $fcst[10];
        $fcst[15] = $fcst[12] + $fcst[13] + $fcst[14];

        $fcst[16] = $fcst[3] + $fcst[7] + $fcst[11] + $fcst[15];


        return $fcst;
    }

    public function adjustFCST($fcst){
        for ($c=0; $c <sizeof($fcst) ; $c++) { 
            $fcst[$c][3] = $fcst[$c][0] + $fcst[$c][1] + $fcst[$c][2];
            $fcst[$c][7] = $fcst[$c][4] + $fcst[$c][5] + $fcst[$c][6];
            $fcst[$c][11] = $fcst[$c][8] + $fcst[$c][9] + $fcst[$c][10];
            $fcst[$c][15] = $fcst[$c][12] + $fcst[$c][13] + $fcst[$c][14];

            $fcst[$c][16] = $fcst[$c][3] + $fcst[$c][7] + $fcst[$c][11] + $fcst[$c][15];
        }

        return $fcst;
    }

    public function addFcstWithBooking($booking,$fcst){

        $date = date('n')-1;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }

        for ($c=0; $c < sizeof($booking); $c++) { 
            for ($f=0; $f < sizeof($booking[$c]); $f++) { 
                if ($f<$date) {
                    $sum[$c][$f] = $booking[$c][$f];
                }else{
                    $sum[$c][$f] = $fcst[$c][$f];
                }
            }
        }        

        return $sum;
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

    public function makeFcstAmountByStageEx($fcstAmountByStage,$splitted){
        $resp[0] = array('1','2','3','4','5','6');
        $resp[1] = array(0.0,0.0,0.0,0.0,0.0,0.0);

        for ($c=0; $c <sizeof($fcstAmountByStage) ; $c++) { 
            if ($splitted) {
                if ($splitted[$c]['splitted']) {
                    $div = 2;
                }else{
                    $div = 1;
                }
                $resp[1][0] += $fcstAmountByStage[$c][1][0]/$div;
                $resp[1][1] += $fcstAmountByStage[$c][1][1]/$div;
                $resp[1][2] += $fcstAmountByStage[$c][1][2]/$div;
                $resp[1][3] += $fcstAmountByStage[$c][1][3]/$div;
                $resp[1][4] += $fcstAmountByStage[$c][1][4]/$div;
                $resp[1][5] += $fcstAmountByStage[$c][1][5]/$div;

            }else{
                $resp[1][0] += $fcstAmountByStage[$c][1][0];
                $resp[1][1] += $fcstAmountByStage[$c][1][1];
                $resp[1][2] += $fcstAmountByStage[$c][1][2];
                $resp[1][3] += $fcstAmountByStage[$c][1][3];
                $resp[1][4] += $fcstAmountByStage[$c][1][4];
                $resp[1][5] += $fcstAmountByStage[$c][1][5];
            }
        }

        return $resp;
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

    public function adjustFcstAmountByStage($fcstAmountByStage){

        for ($c=0; $c <sizeof($fcstAmountByStage) ; $c++) {
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

    public function subArrays($array1,$array2){
        $exit = array();

        for ($a=0; $a <sizeof($array1) ; $a++) { 
            $exit[$a] = $array1[$a] - $array2[$a];
        }

        return $exit;
    }

    public function consolidateAE($matrix){
        $return = array();

        for ($m=0; $m <sizeof($matrix[0]) ; $m++) { 
            $return[$m] = 0;
        }

        for ($c=0; $c <sizeof($matrix); $c++) { 
            for ($m=0; $m <sizeof($matrix[$c]); $m++) { 
                $return[$m] += $matrix[$c][$m];
            }
        }

        return $return;

    }

    public function consolidateAEFcst($matrix,$splitted){
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

        for ($m=0; $m <sizeof($matrix[0]) ; $m++) { 
            $return[$m] = 0;
        }
        if ($splitted) {
            for ($c=0; $c <sizeof($matrix); $c++) {
                
                if ($splitted[$c]['splitted']) {
                    $div = 2;
                }else{
                    $div = 1;
                }

                for ($m=0; $m <sizeof($matrix[$c]); $m++) {
                    //if ($test > ($m+1)) {
                    //    $return[$m] += $matrix[$c][$m];
                    //}else{
                        $return[$m] += $matrix[$c][$m]/$div;
                    //}
                }
            }
        $return[16] = $return[3] + $return[7] + $return[11] + $return[15];
        }else{
            for ($c=0; $c <sizeof($matrix); $c++) { 
                for ($m=0; $m <sizeof($matrix[$c]); $m++) { 
                    $return[$m] += $matrix[$c][$m];
                }
            }
        }



        return $return;
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

    public function isSplitted($con,$sql,$sR,$list,$cY,$pY){
        $soma = 0;

        $splitted = array();
        for ($l=0; $l < sizeof($list); $l++) { 
            $splitted[$l] = $this->boolSplitted($con,$sql,$sR[0],$list[$l],$cY);
        }        
        return $splitted;        
    }

    public function boolSplitted($con,$sql,$sR,$list,$year){
        $rtr = array( "splitted" => false , "owner" => null );
        
        /*
        
        CHECKING FOR SPLITTED ACCOUNTS ON BI / BTS

        */
        $date = date('n')-1;

        $select = "SELECT DISTINCT order_reference , sales_rep_id , client_id ,agency_id
                        FROM ytd
                        WHERE (client_id = \"".$list['clientID']."\")
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (year = \"".$year."\") 
                        AND (from_date > \"".$date."\")                 
                  ";


        $res = $con->query($select);
        $from = array("order_reference","sales_rep_id","client_id","agency_id");
        $orderRef = $sql->fetch($res,$from,$from);
        $cc = 0;
        if($orderRef){
            for ($o=0; $o < sizeof($orderRef); $o++) { 
                if($o == 0){
                    $comp[$cc]['sales_rep_id'] = $orderRef[$o]['sales_rep_id'];
                    $comp[$cc]['agency_id'] = $orderRef[$o]['agency_id'];
                }

                if($comp[0]['agency_id'] == $orderRef[$o]['agency_id']){
                    $splitted[$cc] = $orderRef[$o]['sales_rep_id'];
                    $cc++;    
                }                
                
            }
        }

        if( isset( $splitted ) ){
            $splitted = array_values(array_unique($splitted));
            if(sizeof($splitted) > 1){
                $rtr = array( "splitted" => true , "owner" => null );
            }
        } 

        /*
        
        CHECKING FOR SPLITTED ACCOUNTS ON BI / BTS

        */

        $selectSF = "SELECT DISTINCT oppid , sales_rep_owner_id , sales_rep_splitter_id , client_id, brand
                        FROM sf_pr
                        WHERE (client_id = \"".$list['clientID']."\") 
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (sales_rep_splitter_id != sales_rep_owner_id)
                        AND (stage != \"5\")                      
                        AND (stage != \"6\")                      
                        AND (stage != \"7\")                      
                  ";

        $resSF = $con->query($selectSF);
        $fromSF = array("oppid","sales_rep_owner_id","sales_rep_splitter_id","client_id", "brand");
        $oppid = $sql->fetch($resSF,$fromSF,$fromSF);

        if($oppid){
            $rtr = array( "splitted" => true , "owner" => false );    
            for ($o=0; $o < sizeof($oppid); $o++) {                 
                if($sR == $oppid[$o]['sales_rep_owner_id']){
                    $rtr = array( "splitted" => true , "owner" => true );
                    break;
                }
            }
        }else{
            $selectSF = "SELECT DISTINCT oppid , sales_rep_owner_id , sales_rep_splitter_id , client_id, brand
                        FROM sf_pr
                        WHERE (client_id = \"".$list['clientID']."\") 
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (sales_rep_splitter_id = sales_rep_owner_id)
                        AND (stage != \"5\")                      
                        AND (stage != \"6\")                      
                        AND (stage != \"7\")                      
                  ";

            $resSF = $con->query($selectSF);
            $fromSF = array("oppid","sales_rep_owner_id","sales_rep_splitter_id","client_id", "brand");
            $oppid = $sql->fetch($resSF,$fromSF,$fromSF);

            if($oppid){
                $rtr = array( "splitted" => false , "owner" => null );    
            }

        }        

        /*

        FIND A WAY TO USE YEAR TO CHECK FOR SPLIITING

        */
       
        return $rtr;
        
    }

    public function calculateForecast($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$rollingFCST,$splitted,$lastYearRevClient,$lastYearRevSalesRep,$lastYearRevCompany){

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
            $someFCST[$c] = $this->getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients[$c],$salesRepID,$splitted[$c],$div); // PERIOD OF FCST , VALUES AND STAGE
            
            $monthOPP[$c] = $this->periodOfOPP($someFCST[$c],$year); // MONTHS OF THE FCST

            if($monthOPP[$c]){
                $shareSalesRep[$c] = $this->salesRepShareOnPeriod($lastYearRevCompany,$lastYearRevSalesRep,$lastYearRevClient[$c],$monthOPP[$c],$someFCST[$c]);
                $fcst[$c] = $this->fillFCST($someFCST[$c],$monthOPP[$c],$shareSalesRep[$c],$salesRepID,$splitted[$c]);
            }else{
                $shareSalesRep[$c] = false;
                $fcst[$c] = false;
            }
            if($fcst[$c]){
                $fcst[$c] = $this->adjustValues($fcst[$c]);
                $fcstAmountByStage[$c] = $this->fcstAmountByStage($fcst[$c],$monthOPP[$c]);
                $fcstAmount[$c] = $this->fcstAmount($fcst[$c],$monthOPP[$c],$splitted[$c],$salesRepID);
                $fcstAmount[$c] = $this->adjustValuesForecastAmount($fcstAmount[$c]);
            }else{
                $fcstAmountByStage[$c] = false;
                $fcstAmount[$c] = false;
            }
            
        }

        $rtr = array("fcstAmount" => $fcstAmount ,"fcstAmountByStage" => $fcstAmountByStage);


        return $rtr;        
    }

    public function fcstAmount($fcst,$mOPP,$splitted,$salesRepUser){
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
                        if($fcst[$m][$mOPP[$m][$f]]['stage'] == "3" ||
                            $fcst[$m][$mOPP[$m][$f]]['stage'] == "3a" ||
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

    public function fillFCST($sFCST,$mOPP,$sRP,$salesRepUser,$splitted){

        $base = new base();

        $monthWQ = $base->monthWQ;

        for ($i=0; $i < sizeof($sFCST); $i++){
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $fcst[$i][$m]['stage'] = false;
                $fcst[$i][$m]['value'] = 0.0;
            }
        }

        for ($i=0; $i < sizeof($sFCST); $i++){
            if($splitted == null || !$splitted['splitted']){
                $factor = 1;
            }else{
                $factor = 2;
            }

            $adjustedValue = $sFCST[$i]['sumValue']* $factor;
            for ($j=0; $j < sizeof($mOPP[$i]); $j++) { 
                $fcst[$i][$mOPP[$i][$j]]['stage'] = $sFCST[$i]['stage'];

                $fcst[$i][$mOPP[$i][$j]]['value'] = ( $adjustedValue * $sRP[$i][$j] );
                
            }   

        }
        
        return $fcst;
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
                }else{
                    $share[$l][$m] = 0;
                    $amount[$l] += 0;
                }
            }

            $newAmount[$l] = $amount[$l];// / sizeof($monthOPP[$l]);
        }
       
        for ($s=0; $s < sizeof($share); $s++) { 
            for ($t=0; $t < sizeof($share[$s]); $t++) { 
                if ($newAmount[$s] == 0) {
                    $share[$s][$t] = 0;
                }else{
                    $share[$s][$t] = $share[$s][$t] / ( $newAmount[$s] );
                }

            }
        }        



        return $share;
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


    public function getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$splitted,$div){
        
        $date = date("n")-1;

        $from = array($sfColumn,'from_date','to_date','year_from','year_to','stage','oppid','salesRepOwner');
        $to = array("sumValue",'fromDate','toDate','yearFrom','yearTo','stage','oppid','salesRepOwner');
        if($splitted){ /* SF FCST FROM BRAZIL, WHERE THERE IS AE SPLITT SALES */
                $select = "
                                SELECT oppid, from_date , to_date, year_from, year_to, stage , $sfColumn , sales_rep_owner_id AS 'salesRepOwner'
                                FROM sf_pr
                                WHERE (client_id = \"".$clients['clientID']."\")
                                AND (agency_id = \"".$clients['agencyID']."\")
                                AND ( sales_rep_splitter_id = \"".$salesRepID."\" )
                                AND ( stage != '5')
                                AND ( stage != '6')
                                AND ( stage != '7')
                                AND (year_from = \"".$year."\")                                
                                AND (from_date > \"".$date."\")";

        }else{/* SF FCST FROM OTHER REGIONS , WHERE THERE IS NOT AE SPLITT SALES */
            $select = "
                            SELECT oppid, from_date , to_date,year_from, year_to,stage , stage , $sfColumn , sales_rep_owner_id AS 'salesRepOwner'
                            FROM sf_pr
                            WHERE (client_id = \"".$clients['clientID']."\")
                            AND (agency_id = \"".$clients['agencyID']."\")
                            AND ( sales_rep_splitter_id = \"".$salesRepID."\" )
                            AND ( stage != '5')
                            AND ( stage != '6')
                            AND ( stage != '7')
                            AND (from_date > \"".$date."\") 
                            AND (year_from = \"".$year."\")";
        }

        $res = $con->query($select);
        $rev = $sql->fetch($res,$from,$to);

        if ($rev) {
            for ($r=0; $r <sizeof($rev); $r++) { 
                $rev[$r]["sumValue"] = doubleval($rev[$r]["sumValue"])*$div;
            }
        }

        /*
            AJUSTE DAS PREVISÕES QUE POSSUEM MAIS DE 1 ANO DE PREVISÃO
        */
        if($rev){
            for ($r=0; $r < sizeof($rev); $r++) { 
                if($rev[$r]['yearFrom'] != $rev[$r]['yearTo']){
                    $fromArray = $this->makeMonths("from",$rev[$r]['fromDate']);
                    $toArray = $this->makeMonths("to",$rev[$r]['toDate']);
                    $fromShare = $this->calculateRespectiveShare($con,$sql,$regionID,$value,$rev[$r]['yearFrom'],$fromArray);
                    $toShare = $this->calculateRespectiveShare($con,$sql,$regionID,$value,$rev[$r]['yearTo'],$toArray);
                    $shareFromCYear = $this->aggregateShare($fromShare,$toShare);

                    $rev[$r]['sumValue'] = $rev[$r]['sumValue']*$shareFromCYear;
                }                
            }
        }

        return $rev;

    }

    public function aggregateShare($from,$to){
        $sum = 0.0;
        $sumFrom = 0.0;

        for ($f=0; $f < sizeof($from); $f++) { 
            $sum += $from[$f];
            $sumFrom += $from[$f];
        }        

        for ($t=0; $t < sizeof($to); $t++) { 
            $sum += $to[$t];
        }

        for ($f=0; $f < sizeof($from); $f++) { 
            $shareByMonth[$f] = $from[$f]/$sum;
        } 

        $share = $sumFrom/$sum;

        return $share;
        var_dump($share);


    }

    public function calculateRespectiveShare($con,$sql,$regionID,$value,$year,$month){
        $pastYear = intval($year) - 1;

        $from = array("amount");

        for ($m=0; $m < sizeof($month); $m++) { 
            /*
                SE O ANO DO FORECAST FOR O ANO SEGUINTE VERIFICA SE O MES DE FORECAST É MENOR QUE O MES ATUAL, SE FOR MAIOR DIMINUI O ANO PASSADO PARA PEGAR O VALOR NO ANO ANTERIOR E NAO NO CORRENTE
            */
            if($pastYear == date('Y')){
                if($month[$m] >= date('m')){
                    $pastYear--;
                }
            }

            $select[$m] = "SELECT SUM(".$value."_revenue_prate) AS 'amount'
                       FROM ytd
                       WHERE (sales_representant_office_id = \"".$regionID."\")
                       AND (year = \"".$pastYear."\")
                       AND (month = \"".$month[$m]."\")

                      ";

            $selectFW[$m] = "SELECT SUM(".$value."_revenue) AS 'amount'
                       FROM fw_digital
                       WHERE (region_id = \"".$regionID."\")
                       AND (year = \"".$pastYear."\")
                       AND (month = \"".$month[$m]."\")

                      ";

            $result[$m] = $con->query($select[$m]);
            $resultFW[$m] = $con->query($selectFW[$m]);
            $shareTV[$m] = $sql->fetch($result[$m],$from,$from)[0];
            $shareFW[$m] = $sql->fetch($resultFW[$m],$from,$from)[0];
            $share[$m] = $shareTV[$m]['amount'] + $shareFW[$m]['amount'];

            /*
                RETORNA O VALOR DO ANO PASSADO AO VALOR INICIAL
            */
            if($pastYear == date('Y')){
                if($month[$m] >= date('m')){
                    $pastYear++;
                }
            }
        }

        return $share;

    }

    public function makeMonths($param,$m){

        $temp = intval($m);
        $mm = array();

        if($param == "from"){
            while($temp <= 12){
                array_push($mm, $temp);
                $temp++;
            }
        }else{
            $start = 1;
            while($start <= $temp){
                array_push($mm, $start);
                $start++;
            }
        }

        return $mm;
    }

    public function rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$splitted){
        $currentYear = intval(date('Y'));
        $currentMonth = intval( date("m") );

    	if($currency == "USD"){
    		$div = 1;
    	}else{
    		$div = $pr->getPRateByRegionAndYear($con,array($regionID),array($year));
    	}

    	if($value == "gross"){
            $ytdColumn = "gross_revenue_prate";
    		$fwColumn = "gross_revenue";
    	}else{
    		$ytdColumn = "net_revenue_prate";
            $fwColumn = "net_revenue";
    	}

    	$table = "ytd";

    	for ($c=0; $c < sizeof($clients); $c++) {
            if ($splitted) {
                if ($splitted[$c]['splitted'] == true) {
                    $mult = 2;
                }else{
                    $mult = 1;
                }
            }else{
                $mult = 1;
            }

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
                                            AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                            AND (month = \"".$month[$m][1]."\")
                                            AND (year = \"".$year."\")
                                            AND (sales_rep_id = \"".$salesRepID."\")
                                          ";  

                        $res[$c][$m] = $con->query($select[$c][$m]);
                        $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div*$mult;
                        /*
                            THIS PART IS COMMENTED, BECAUSE IN 2020 DIGITAL IS INCLUDED IN BTS
                        */
                        /*
                        $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                            FROM fw_digital
                                            WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                            AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                            AND (month = \"".$month[$m][1]."\")
                                            AND (year = \"".$year."\")
                                            AND (sales_rep_id = \"".$salesRepID."\")
                                            ";

                        $resFW[$c][$m] = $con->query($selectFW[$c][$m]);
                        $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div*$mult; 
                        */

                    }else{
                        $revACT[$c][$m] = 0.0;
                        //$revFW[$c][$m] = 0.0;
                    }                    
                    
                    $rev[$c][$m] = $revACT[$c][$m];
                    /*
                    if( !is_null($revFW[$c][$m]) ){
                        $rev[$c][$m] += $revFW[$c][$m];
                    }       
                    */
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
                    /*
                    $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   

                    $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                        FROM fw_digital
                                        WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                        AND (month = \"".$month[$m][1]."\")
                                        AND (year = \"".$year."\")
                                        ";

                    $resFW[$c][$m] = $con->query($selectFW[$c][$m]);
	    			$revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;	
                    */
                    $rev[$c][$m] = $revACT[$c][$m];
                    /*
                    if( !is_null($revFW[$c][$m]) ){
                        $rev[$c][$m] += $revFW[$c][$m];
                    }		
                    */
    			}
    		}

    	}

    	return $rev;

    }

    public function getBookingExecutive($con,$sql,$salesRep,$month,$region,$year,$value,$currency,$pr){

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

        for ($m=0; $m <sizeof($month) ; $m++) { 
            $select[$m] = "SELECT SUM($ytdColumn) AS sumValue
                                FROM ytd
                                WHERE  (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")
                                AND (sales_rep_id = \"".$salesRep."\")";

            $selectFW[$m] = "SELECT SUM($fwColumn) AS sumValue 
                                FROM fw_digital
                                WHERE (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")
                                AND (sales_rep_id = \"".$salesRep."\")";            

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


    public function revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$splitted,$currency,$currencyID,$value,$clients,$typeOfYear,$cYear){

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
                                AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                AND (month = \"".$month[$m][1]."\")                                    
                                AND (year = \"".$year."\")
                              ";

                $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                FROM $tableFW
                                WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                AND (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")
                                ";


    			$res[$c][$m] = $con->query($select[$c][$m]);
                $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

    			$from = array("sumValue");

    			$rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;	    			
                $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;                    

                if( !is_null($revFW[$c][$m]) ){
                    
                    $rev[$c][$m] += ( $revFW[$c][$m] );
                    
                }


    		}
    	}

    	return $rev;

    }

    public function revenueByDiscoveryClient($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$splitted,$currency,$currencyID,$value,$clients,$typeOfYear,$cYear,$brand){

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

                 for ($b=0; $b < sizeof($brand); $b++) { 
                    /*
                            FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
                    */
                    $select[$c][$m] = "
                                    SELECT SUM($ytdColumn) AS sumValue
                                    FROM $table
                                    WHERE (client_id = \"".$clients[$c]['clientID']."\")
                                    AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                    AND (month = \"".$month[$m][1]."\")
                                    AND (year = \"".$year."\")
                                    AND (brand_id != \"".$brand[$b]['brandID']."\")
                                  ";

                    $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                    FROM $tableFW
                                    WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                    AND (agency_id = \"".$clients[$c]['agencyID']."\")
                                    AND (month = \"".$month[$m][1]."\")
                                    AND (year = \"".$year."\")
                                    AND (brand_id != \"".$brand[$b]['brandID']."\")
                                    ";


                    $res[$c][$m] = $con->query($select[$c][$m]);
                    $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

                    $from = array("sumValue");

                    $rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   
                    $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;                    
                    
                    if( !is_null($revFW[$c][$m]) ){
                        
                        $rev[$c][$m] += ( $revFW[$c][$m] );
                        
                    }
                }
            }
        }
        return $rev;

    }

    public function revenueBySonyClient($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$splitted,$currency,$currencyID,$value,$clients,$typeOfYear,$cYear,$brand){

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

        $brands = array("21","22","23","24","25","26","27");

        $brands = $base->arrayToString($brands,false,0);

        for ($c=0; $c < sizeof($clients); $c++) {               
            for ($m=0; $m < sizeof($month); $m++) {
                    /*
                            FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
                    */
                    $select[$c][$m] = "
                                    SELECT SUM($ytdColumn) AS sumValue
                                    FROM ytd y
                                    LEFT JOIN brand b ON b.id = y.brand_id
                                    WHERE (y.client_id = \"".$clients[$c]['clientID']."\")
                                    AND (y.agency_id = \"".$clients[$c]['agencyID']."\")
                                    AND (y.month = \"".$month[$m][1]."\")                               
                                    AND (y.year = \"".$year."\")
                                    AND (b.id = $brands)                                   
                                  ";

                    $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                    FROM fw_digital f
                                    LEFT JOIN brand b ON b.id = f.brand_id
                                    WHERE (f.client_id = \"".$clients[$c]["clientID"]."\")
                                    AND (f.agency_id = \"".$clients[$c]['agencyID']."\")
                                    AND (f.month = \"".$month[$m][1]."\")
                                    AND (f.year = \"".$year."\")
                                    AND (b.id = \"".$brands."\")                               
                                    ";


                    $res[$c][$m] = $con->query($select[$c][$m]);
                    $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

                    $from = array("sumValue");

                    $rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   
                    $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*$div;

                    if( !is_null($revFW[$c][$m]) ){
                        
                        $rev[$c][$m] += ( $revFW[$c][$m] );
                        
                    }
            }
        }
        return $rev;

    }

    public static function orderClient($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }

    public function listOfBrands($con,$sql,$salesRepID,$cYear,$regionID){
        $date = date('n')-1;

        $tmp = $salesRepID[0];
        //GET FROM SALES FORCE
        $sf = "SELECT DISTINCT s.brand AS 'brandName',
                               b.ID AS 'brandID'
                    FROM sf_pr s
                    LEFT JOIN brand b ON s.brand = b.name 
                    WHERE (      (s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\")      )
                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
                    ORDER BY 1
               ";       
        $resSF = $con->query($sf);
        $from = array("brandName","brandID");
        $listSF = $sql->fetch($resSF,$from,$from);
        //GET FROM IBMS/BTS
        $ytd = "SELECT DISTINCT b.name AS 'brandName',
                       y.brand_id AS 'brandID'
                    FROM ytd y
                    LEFT JOIN brand b ON b.ID = y.brand_id
                    WHERE (y.sales_rep_id = \"$tmp\" )
                    AND (y.year = \"$cYear\" )
                    AND (r.ID = \"".$regionID."\")
                    ORDER BY 1
               ";
        $resYTD = $con->query($ytd);
        $from = array("brandName","brandID");
        $listYTD = $sql->fetch($resYTD,$from,$from);
        $count = 0;

        $list = array();

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

        return $list;

    }

    public function listClientsByAE($con,$sql,$salesRepID,$cYear,$regionID){

        $date = date('n')-1;
        $pYear = $cYear - 1;

        $tmp = $salesRepID[0];
    	//GET FROM SALES FORCE
    	$sf = "SELECT DISTINCT c.name AS 'clientName',
    				   c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
    				FROM sf_pr s
                    LEFT JOIN client c ON c.ID = s.client_id
    				LEFT JOIN agency a ON a.ID = s.agency_id
    				WHERE (      (s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\")      )
                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
    				ORDER BY 1
    	       ";   	
    	$resSF = $con->query($sf);
    	$from = array("clientName","clientID","agencyID","agencyName");
    	$listSF = $sql->fetch($resSF,$from,$from);
    	//GET FROM IBMS/BTS
    	$ytd = "SELECT DISTINCT c.name AS 'clientName',
    				   c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
    				FROM ytd y
    				LEFT JOIN client c ON c.ID = y.client_id
                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
                    LEFT JOIN agency a ON a.ID = y.agency_id
    				WHERE (y.sales_rep_id = \"$tmp\" )
    				AND ((y.year = \"$cYear\") OR (y.year = \"$pYear\") )                    
                    AND (r.ID = \"".$regionID."\")
    				ORDER BY 1
    	       ";
    	$resYTD = $con->query($ytd);
    	$from = array("clientName","clientID","agencyID","agencyName");
    	$listYTD = $sql->fetch($resYTD,$from,$from);
    	$count = 0;

        $list = array();

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

    public function addQuartersAndTotalOnArray($array){
    	for ($a=0; $a < sizeof($array); $a++) { 
    		$newArray[$a] = $this->addQuartersAndTotal($array[$a]);
    	}
    	return $newArray;
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

}
