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
use App\AE;

class bybrandReport extends Model{

	public function baseLoad($con,$r,$pr,$cYear,$pYear){
    	
        $sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();
        $b = new brand();
       
        $regionID = Request::get('region');
        $currencyID = Request::get('currency');
        $value = Request::get('value');
        $ae = new AE();
        $brands = $b->getBrand($con);
        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $ae->weekOfMonth($data);

        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE (submitted = \"0\" OR submitted = \"1\") AND month = \"$actualMonth\" AND year = \"$cYear\" AND type_of_forecast = \"AE\"";

        if ($regionID == "1") {
            $select .= "AND read_q = \"$week\"";
        }

        $select .= "ORDER BY last_modify_date DESC";
        
        $result = $con->query($select);

        $from = array("oppid","ID","type_of_value","currency_id", "submitted");

        $save = $sql->fetch($result,$from,$from);
        
        $listOfClients = $this->listClientsByAE($con,$sql,$cYear,$regionID);

        if(sizeof($listOfClients) == 0){
            return false;
        }

        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
            $multValue = false;
            $newCurrency = false;
            $oldCurrency = false;
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


        $brand = $br->getBrandBinary($con);
        $month = $base->getMonth();

        $tmp = array($cYear);
 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        
        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $ae->monthAnalise($base);

        for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $ae->generateColumns($value,$table[$b][$m]);
            }
        }
        
        for ($m=0; $m <sizeof($month) ; $m++) {
            $lastYear[$m] = $ae->generateValueWB($con,$sql,$regionID,$pYear,$month[$m][1], $ae->generateColumns($value,"ytd") ,"ytd",$value)*$div;
        }
        $lastYear = $ae->addQuartersAndTotalOnArray( array($lastYear) )[0];

        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $targetValues[$b][$m] = $ae->generateValue($con,$sql,$regionID,$cYear,$brand[$b],$month[$m][1],"value","plan_by_sales",$value)[0]*$div;            
            }
        }

        /*$mergeTarget = $ae->mergeTarget($targetValues,$month);
        $targetValues = $mergeTarget;

        $clientRevenueCYear = $ae->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,false,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear);

        $clientRevenueCYearTMP = $clientRevenueCYear;

        $clientRevenueCYear = $ae->addQuartersAndTotalOnArray($clientRevenueCYear);

        $clientRevenuePYear = $ae->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,false,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear);
        $clientRevenuePYear = $ae->addQuartersAndTotalOnArray($clientRevenuePYear);

        $tmp = $ae->getBookingExecutive($con,$sql,$month,$regionID,$cYear,$value,$currency,$pr);

        $executiveRevenueCYear = $ae->addQuartersAndTotal($tmp);

        $executiveRevenuePYear = $ae->consolidateAEFcst($clientRevenuePYear,false);

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

            $tmpRollingFCST = $ae->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmpRollingFCST = $ae->addQuartersAndTotalOnArray($tmpRollingFCST);

            $fcst = $ae->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $tmpRollingFCST = $ae->addFcstWithBooking($tmpRollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $ae->addQuartersAndTotalOnArray($rollingFCST);

            for ($r=0; $r <sizeof($rollingFCST) ; $r++) { 
                if ($rollingFCST[$r][16] == 0) {
                    $rollingFCST[$r]=$tmpRollingFCST[$r];
                }
            }

            $rollingFCST = $ae->addClosedFcst($rollingFCST,$tmpRollingFCST);

            $rollingFCST = $ae->adjustFCST($rollingFCST);

            $fcstAmountByStage = $ae->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage

            $fcstAmountByStageEx = $ae->makeFcstAmountByStageEx($fcstAmountByStage,$splitted);
            
            $lastRollingFCST = $ae->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmp1 = $ae->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$lastRollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $tmp2 = $tmp1['fcstAmount'];

            $lastRollingFCST = $ae->addQuartersAndTotalOnArray($lastRollingFCST);

            $lastRollingFCST = $ae->addFcstWithBooking($lastRollingFCST,$tmp2);

            $lastRollingFCST = $ae->adjustFCST($lastRollingFCST);

            $emptyCheck = $ae->checkEmpty($tmp2);

            //$lastRollingFCST = $ae->closedMonth($lastRollingFCST,$clientRevenueCYear);
            //$lastRollingFCST = $ae->adjustFCST($lastRollingFCST);
            
            //$rollingFCST = $ae->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $ae->adjustFCST($rollingFCST);

        }else{
            $sourceSave = "DISCOVERY CRM";
            $rollingFCST = $ae->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $fcst = $ae->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

            $fcstAmountByStage = $fcst['fcstAmountByStage'];

            $toRollingFCST = $fcst['fcstAmount'];

            $rollingFCST = $ae->addQuartersAndTotalOnArray($rollingFCST);

            $rollingFCST = $ae->addFcstWithBooking($rollingFCST,$toRollingFCST);//Meses fechados e abertos

            $rollingFCST = $ae->adjustFCST($rollingFCST);
            
            $fcstAmountByStage = $ae->addClosed($fcstAmountByStage,$rollingFCST);//Adding Closed to fcstByStage

            $emptyCheck = $ae->checkEmpty($toRollingFCST);

            //$rollingFCST = $ae->closedMonth($rollingFCST,$clientRevenueCYear);
            //$rollingFCST = $ae->adjustFCST($rollingFCST);

            $lastRollingFCST = $rollingFCST;
            
        }

        $fcstAmountByStage = $ae->addLost($con,$listOfClients,$fcstAmountByStage,$value,$div);
           
        $fcstAmountByStageEx = $ae->makeFcstAmountByStageEx($fcstAmountByStage,false);

        $executiveRF = $ae->consolidateAEFcst($rollingFCST,false);
        $executiveRF = $ae->closedMonthEx($executiveRF,$executiveRevenueCYear);
        $executiveRF = $ae->addBookingRollingFCST($executiveRF,$executiveRevenueCYear);
        $pending = $ae->subArrays($executiveRF,$executiveRevenueCYear);
        $RFvsTarget = $ae->subArrays($executiveRF,$targetValues);
        $targetAchievement = $ae->divArrays($executiveRF,$targetValues);

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $fcstAmountByStage = $ae->adjustFcstAmountByStage($fcstAmountByStage);

        $fcstAmountByStageEx = $ae->adjustFcstAmountByStageEx($fcstAmountByStageEx);

        //booking ano atual para o fcst
	    $brandValue = $this->getBookingPerBrand($con,$sql,$pr,$brands,$cYear,$value,$currency,$regionID,$currencyID);

        $brandValueCYear = $ae->addQuartersAndTotalOnArray($brandValue);

        $brandValuePYear = $this->getBookingPerBrand($con,$sql,$pr,$brands,$pYear,$value,$currency,$regionID,$currencyID);

        $brandValuePYear = $ae->addQuartersAndTotalOnArray($brandValuePYear);*/

	    //booking do ano passando para calculo de porcentagem
	    $brandsValueLastYear = $this->lastYearBrand($con,$sql,$pr,$brands,($pYear),$value,$currency,$regionID,$currencyID);

        $sourceSave = "DISCOVERY CRM";

        $brandsPerRep = $this->getBrandsPerSalesRep($con, $listOfClients,$value,$currency,$brands,$brandsValueLastYear,$currencyID, $cYear);


        //$rollingFcst = $this->addBookingRollingFCST($brandsPerRep,$brandValueCYear);
        //var_dump($rollingFCST);
        //$rolling = $ae->addQuartersAndTotalOnArray($brandsPerRep);
        //var_dump($rolling);
        if ($value == 'gross') {
            $valueView = 'Gross';
        }elseif($value == 'net'){
            $valueView = 'Net';
        }else{
            $valueView = 'Net Net';
        }

        $secondary = $listOfClients;

        //$nSecondary = $ae->mergeSecondary($secondary,$rollingFCST,$lastRollingFCST,$clientRevenueCYear,$clientRevenuePYear,$fcstAmountByStage);


        $rtr = array(	
        				/*"cYear" => $cYear,
        				"pYear" => $pYear,
                        "readable" => $readable,

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
                        "fcstAmountByStage" => $fcstAmountByStage, */
                       // "fcstAmountByStageEx" => $fcstAmountByStageEx,
                        "brandsPerRep" => $brandsPerRep,
                        "sourceSave" => $sourceSave
                        //"emptyCheck" => $emptyCheck,
                        //"nSecondary" => $nSecondary,
                        //"bookingPYear" => $brandsValueLastYear,
                        //"brandValueCYear" => $brandValueCYear,
                        //"brandValuePYear" => $brandValuePYear
                    );

        return $rtr;

    }

    public function listClientsByAE($con,$sql,$cYear,$regionID){

        $date = date('n')-1;

        //GET FROM SALES FORCE
        $sf = "SELECT DISTINCT c.name AS 'clientName',
                       c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
                    FROM sf_pr s
                    LEFT JOIN client c ON c.ID = s.client_id
                    LEFT JOIN agency a ON a.ID = s.agency_id
                    WHERE ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
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
                    WHERE (y.year = \"$cYear\" )
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

        return $list;

    }

    public function getBrandsPerSalesRep($con,$clients,$value,$currency,$brands,$lastYearBrand,$currencyID,$year){

        $checkNochannel = 0;
        $temp = 0;
        $sql = new sql();
        $pr = new pRate();

        $saida = array();
        $from = array("brand", "value","fromDate", "toDate", "yearFrom", "yearTo");

        if ($value == "gross") {
            $col = "fcst_amount_gross";
        }else{
            $col = "fcst_amount_net"; 
        }
        
        $startMonthFcst = intval(date('m')) - 1;
        
        if($currency == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currencyID),array($year));
        }

        for ($m=0; $m <12 ; $m++) { 
            for ($b=0; $b <sizeof($brands); $b++) { 
                $saida[$b][$m] = 0;
            }
        }        

        for ($c=0; $c < sizeof($clients) ; $c++) { 
            for ($m=0; $m <12; $m++) { 
                $select[$c][$m] = "SELECT brand, SUM($col) AS 'value', from_date AS 'fromDate', to_date AS 'toDate', year_from AS 'yearFrom', year_to AS 'yearTo' FROM sf_pr WHERE (client_id = \"".$clients[$c]["clientID"]."\") AND (stage != \"5\" AND stage != \"6\" AND stage != \"7\") AND (from_date =  \"".($m+1)."\")";

                $res[$c][$m] = $con->query($select[$c][$m]);

                $saida[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0];
                $saida[$c][$m]['total'] =0.0;
                $totalPrc[$c][$m] = 0;
                $total[$c][$m] = 0;
                $prcTemp[$c][$m] = array();

            	$saida[$c][$m]['brand'] = explode(";", $saida[$c][$m]['brand']);

                if ($saida[$c][$m]['brand'] == 'NOCHANNELS' || $saida[$c][$m]['brand'] == '') {
                        $checkNochannel += $saida[$c][$m]['value'];
                }

                for ($i=0; $i <sizeof($saida[$c][$m]['brand']); $i++) { 
                    if ($saida[$c][$m]['brand'][$i] == 'ONL-G9' || $saida[$c][$m]['brand'][$i] == 'ONL-DSS') {
                        $saida[$c][$m]['brand'][$i] = 'ONL';
                    }elseif($saida[$c][$m]['brand'][$i] == 'NOCHANNELS' || $saida[$c][$m]['brand'][$i] == ''){
                        unset($saida[$c][$m]['brand'][$i]);
                    }
                }
                
                if(!empty($saida[$c][$m]['brand'])){
                    if (sizeof($saida[$c][$m]['brand']) == 1) {
                        for ($i=0; $i <sizeof($brands); $i++) { 
                            if ($saida[$c][$m]['brand'][0] == $brands[$i]['name']) {
                                //$saida[$c][$m] += $saida[$c][$m]['value'];
                            }
                        }
                    }else{
                        for ($b=0; $b <sizeof($brands) ; $b++) { 
                            for ($d=0; $d < sizeof($saida[$c][$m]['brand']); $d++) { 
                                if ($saida[$c][$m]['brand'][$d] == $brands[$b]['name']) {
                                   $saida[$c][$m]['total'] += $lastYearBrand[$b];
                                   $saida[$c][$m]['lastYearValue'][$d] = $lastYearBrand[$b];
                                } 
                            }
                        }   

                        for ($b=0; $b <sizeof($brands) ; $b++) {  
                            for ($d=0; $d < sizeof($saida[$c][$m]['brand']); $d++) {               
                           
                                if ( $saida[$c][$m]['total'] == 0) {
                                    $saida[$c][$m]['prc'][$d] = 0.0;
                                }else{
                                    $saida[$c][$m]['prc'][$d] = $saida[$c][$m]['lastYearValue'][$d]/ $saida[$c][$m]['total'];
                                }
                                
                                $totalPrc[$c][$m] += $saida[$c][$m]['prc'][$d];
                                $prcTemp[$c][$m][$b] = $saida[$c][$m]['prc'][$d];

                                
                                if ($b == 0){
                                    $saida[$c][$m]['value2'][$d] = ($saida[$c][$m]['value']*$saida[$c][$m]['prc'][$d])*$div;    
                                }else{
                                    $saida[$c][$m]['value2'][$d] += ($saida[$c][$m]['value']*$saida[$c][$m]['prc'][$d])*$div; 
                                }

                                if ($saida[$c][$m]['brand'] == $saida[$c][$m]['brand']) {
                                    $saida[$c][$m]['brand'] = array_unique($saida[$c][$m]['brand']);
                                }
                            }
                        }
                    }    
                }
            
            //var_dump($saida[$c][$m]);  
            }
        }

        for ($b=0; $b <sizeof($saida); $b++) { 
            for ($m=0; $m <sizeof($saida[$b]); $m++) { 
                $temp += $saida[$b][$m];
                var_dump($temp);
            }
        }

        //var_dump($saida);
        return $saida;
    }

    public function periodOfOpp($opp,$year){
        $period = 0;
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
        
        //$month = $this->matchMonthWithArray($month);
        
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
            var_dump($seek);
            for ($n=0; $n < sizeof($monthWQ); $n++) { 
                if( $seek[$m] == strtoupper($monthWQ[$n]) ){
                    $pivot[$m] = $n;
                    break;
                }
            }
        }
        return $pivot;
        
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

	public function lastYearBrand($con,$sql,$pr,$brands,$year,$value,$currency,$region,$currencyID){
		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		$date = date('n')-1;

		if($currency == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currencyID),array($year));
        }

        for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
			    if ($m>=$date) {
					if ($brands[$b]['name'] == 'ONL') {
						//pegar ONL do FW
						$select[$b] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\")";
					}elseif($brands[$b]['name'] == 'VIX'){
						//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
						$select[$b] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\")  AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
					}else{
						$select[$b] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\")";
					}

					$res[$b] = $con->query($select[$b]);
					$resp[$b] = $sql->fetchSum($res[$b], "value")['value']*$div;
				}/*else{
					$resp[$b][$m] = 0;
				}*/
			}
		}
		
        //var_dump($select);
		return $resp;
	}

	public function getBooking($con,$sql,$pr,$brands,$year,$value,$currency,$region,$currencyID,$salesRep){
		
		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		if($currency == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currencyID),array(date('Y')));
        }

		for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
				if ($brands[$b]['name'] == 'ONL') {
					//pegar ONL do FW
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}elseif($brands[$b]['name'] == 'VIX'){
					//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}else{
					$select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}

				$res[$b][$m] = $con->query($select[$b][$m]);
				$resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value']*$div;
			}
		}
		//var_dump($select);
		return $resp;		
	}


	public function getBookingPerBrand($con,$sql,$pr,$brands,$year,$value,$currency,$region,$currencyID,$salesRep){

		if ($value == "gross") {
			$col = "gross_revenue_prate";
			$colFW = "gross_revenue";
		}else{
			$col = "net_revenue_prate"; 
			$colFW = "net_revenue";
		}

		$date = date('n')-1;

		if($currency == "USD"){
            $div = 1.0;
        }else{
            $div = $pr->getPRateByRegionAndYear($con,array($currencyID),array($year));
        }

		for ($b=0; $b <sizeof($brands); $b++) { 
			for ($m=0; $m <12; $m++){
				if ($brands[$b]['name'] == 'ONL') {
					//pegar ONL do FW
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id != \"10\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}elseif($brands[$b]['name'] == 'VIX'){
					//pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
					$select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}else{
					$select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"".$region."\") AND (month = \"".($m+1)."\") AND (brand_id = \"".$brands[$b]['id']."\") AND (year = \"".$year."\") AND (sales_rep_id = \"".$salesRep[0]["id"]."\")";
				}

				$res[$b][$m] = $con->query($select[$b][$m]);
				$resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value']*$div;
			}
		}
		//var_dump($select);
		return $resp;
	}
 	
}
