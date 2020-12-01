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

class AgencyAGViewer extends AE{
    public function baseLoad($con,$r,$pr,$cYear,$pYear){

        $sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();
       
        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE sales_rep_id = \"".$salesRepID[0]."\" AND (submitted = \"0\" OR submitted = \"1\") AND month = \"$actualMonth\" AND year = \"$cYear\" AND type_of_forecast = \"AE\"";

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

        $listOfAgencies = $this->dealWithAgency($listOfClients);

        $secondary = $listOfAgencies;

        $rollingFCST = $this->sumClientsOnAgency($listOfClients,$listOfAgencies,$rollingFCST);
        $lastRollingFCST = $this->sumClientsOnAgency($listOfClients,$listOfAgencies,$lastRollingFCST);

        $clientRevenueCYear = $this->sumClientsOnAgency($listOfClients,$listOfAgencies,$clientRevenueCYear);
        $clientRevenuePYear = $this->sumClientsOnAgency($listOfClients,$listOfAgencies,$clientRevenuePYear);

        $fcstAmountByStage = $this->sumClientsOnAgencyFcstAmount($listOfClients,$listOfAgencies,$fcstAmountByStage);

        $nSecondary = $this->mergeSecondary($secondary,$rollingFCST,$lastRollingFCST,$clientRevenueCYear,$clientRevenuePYear,$fcstAmountByStage);

        $rtr = array(   
                        "cYear" => $cYear,
                        "pYear" => $pYear,
                        "readable" => $readable,

                        "salesRep" => $salesRep[0],
                        "client" => $listOfClients,
                        "agency" => $listOfAgencies,
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
                        "fcstAmountByStage" => $fcstAmountByStage, // ***
                        "fcstAmountByStageEx" => $fcstAmountByStageEx,
                        "brandsPerClient" => $brandsPerClient,
                        "sourceSave" => $sourceSave,
                        "emptyCheck" => $emptyCheck,
                        "nSecondary" => $nSecondary,
                    );

        return $rtr;
        

    }

    public function sumClientsOnAgency($listC,$listA,$array){

        for ($A=0; $A < sizeof($listA); $A++) { 
            for ($i=0; $i < 17; $i++) { 
                $newArray[$A][$i] = 0.0;
            }

            for ($C=0; $C < sizeof($listC); $C++) { 
                if($listA[$A]['agencyID'] == $listC[$C]['agencyID']){
                    for ($i=0; $i < 17; $i++) { 
                        $newArray[$A][$i] += $array[$C][$i];
                    }
                }
            }
        }

        return $newArray;

    }

    public function sumClientsOnAgencyFcstAmount($listC,$listA,$array){
        
        for ($A=0; $A < sizeof($listA); $A++) { 
            $newArray[$A][0] = $array[0][0];
            for ($i=0; $i < 8; $i++) { 
                $newArray[$A][1][$i] = 0.0;

            }
            
            for ($C=0; $C < sizeof($listC); $C++) { 
                if($listA[$A]['agencyID'] == $listC[$C]['agencyID']){
                    for ($i=0; $i < 8; $i++) { 
                        $newArray[$A][1][$i] += $array[$C][1][$i];
                    }
                }
            }
            
        }
        return $newArray;

    }

    public function dealWithAgency($list){

        for ($l=0; $l < sizeof($list); $l++) { 
            $newList[$l]['agencyID'] = $list[$l]['agencyID'];
            $newList[$l]['agencyName'] = $list[$l]['agencyName'];
        }

        $input = array_values(array_map("unserialize", array_unique(array_map("serialize", $newList))));
        return $input;
    }

    
    public function base($con,$r,$pr,$cYear,$pYear){

    	$sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();
       
        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        
        $type = Request::get('type');

        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        $listOfAgencies = $this->listAGorAGGByAE($con,$sql,$salesRepID,$cYear,$regionID,$type);
        //var_dump($listOfAgencies);

        if(sizeof($listOfAgencies) == 0){
            return false;
        }       

        $regionName = $reg->getRegion($con,array($regionID))[0]['name'];
        var_dump($regionName);
        $salesRep = $sr->getSalesRepById($con,$salesRepID);        
        var_dump($salesRep);

        $brand = $br->getBrandBinary($con);
        var_dump($brand);
        $month = $base->getMonth();
        var_dump($month);
        $tmp = array($cYear);

        //valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        var_dump($div);

        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];
        var_dump($currency);

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

        var_dump($sum);

        
    }

    public function listAGorAGGByAE($con,$sql,$salesRepID,$cYear,$regionID,$type){
		var_dump($type);
    	$date = date('n')-1;

        $tmp = $salesRepID[0];
    	//GET FROM SALES FORCE

        if($type == 'agency'){
        	$sf = "SELECT DISTINCT 
        			   a.name AS 'agencyName',
    				   a.ID AS 'agencyID',
                       ag.ID AS 'agencyGroupID',
                       ag.name AS 'agencyGroupName'
    				FROM sf_pr s
                    LEFT JOIN agency a ON a.ID = s.agency_id
    				LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
    				WHERE ((s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\"))
                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
    				ORDER BY 1
    	       "; 
	        
	        $resSF = $con->query($sf);
	    	$from = array("agencyName","agencyID","agencyGroupID","agencyGroupName");
	    	$listSF = $sql->fetch($resSF,$from,$from);

            $ytd = "SELECT DISTINCT                        
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName',
                       ag.ID AS 'agencyGroupID',
                       ag.name AS 'agencyGroupName'
                    FROM ytd y
                    LEFT JOIN client c ON c.ID = y.client_id
                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
                    LEFT JOIN agency a ON a.ID = y.agency_id
                    LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
                    WHERE (y.sales_rep_id = \"$tmp\" )
                    AND (y.year = \"$cYear\" )
                    AND (r.ID = \"".$regionID."\")
                    ORDER BY 1
               ";

            $resYTD = $con->query($ytd);
            $from = array("agencyName","agencyID","agencyGroupID","agencyGroupName");
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

        }else{
        	$tempSf = "SELECT DISTINCT         			   
                       ag.ID AS 'agencyGroupID',
                       ag.name AS 'agencyGroupName'
    				FROM sf_pr s
                    LEFT JOIN agency a ON a.ID = s.agency_id
    				LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
    				WHERE ((s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\"))
                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
    				ORDER BY 1
    	       "; 
	        $temResSF = $con->query($tempSf);
	    	$from = array("agencyGroupID","agencyGroupName");
	    	$tempListSF = $sql->fetch($temResSF,$from,$from);

	    	for ($t=0; $t < sizeof($tempListSF); $t++) { 	    		
		    	$sf = "SELECT DISTINCT 
	        			   a.name AS 'agencyName',
	    				   a.ID AS 'agencyID'	                       
	    				FROM sf_pr s
	                    LEFT JOIN agency a ON a.ID = s.agency_id
	    				LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
	    				WHERE (ag.ID = ".$tempListSF[$t]['agencyGroupID'].") 
	    				AND ((s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\"))
	                    AND ( s.region_id = \"".$regionID."\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
	                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
	    				ORDER BY 1
	    	       "; 		        
		        $resSF = $con->query($sf);
		    	$from = array("agencyName","agencyID");
		    	$x = $sql->fetch($resSF,$from,$from);
		    	$tempListSF[$t]['agency'] = $x;
	    	}

	    	$listSF = $tempListSF;

            $tempYTD = "SELECT DISTINCT                     
                       ag.ID AS 'agencyGroupID',
                       ag.name AS 'agencyGroupName'
                    FROM ytd y
                    LEFT JOIN agency a ON a.ID = y.agency_id
                    LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
                    WHERE (y.sales_rep_id = \"$tmp\")
                    AND ( y.sales_representant_office_id = \"".$regionID."\") 
                    AND (y.year = \"$cYear\") 
                    ORDER BY 1
               "; 

            //echo "<pre>".$tempYTD."</pre>";
            $temResYTD = $con->query($tempYTD);
            $from = array("agencyGroupID","agencyGroupName");
            $tempListYTD = $sql->fetch($temResYTD,$from,$from);

            for ($t=0; $t < sizeof($tempListYTD); $t++) {                
                $ytd = "SELECT DISTINCT 
                           a.name AS 'agencyName',
                           a.ID AS 'agencyID'                          
                        FROM ytd y
                        LEFT JOIN agency a ON a.ID = y.agency_id
                        LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
                        WHERE (ag.ID = ".$tempListYTD[$t]['agencyGroupID'].") 
                        AND (y.sales_rep_id = \"$tmp\")
                        AND (y.sales_representant_office_id = \"".$regionID."\") 
                        AND (y.year = \"$cYear\") 
                        ORDER BY 1
                   ";  
                //echo "<pre>".$ytd."</pre>";
                $resYTD = $con->query($ytd);
                $from = array("agencyName","agencyID");
                $x = $sql->fetch($resYTD,$from,$from);
                $tempListYTD[$t]['agency'] = $x;
            }

            $listYTD = $tempListYTD;

            $list = array();
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
            
            $saved = array();

            for ($l=0; $l < sizeof($list); $l++) {
                for ($l2= $l+1; $l2 < sizeof($list); $l2++) { 
                    if( $list[$l]['agencyGroupID'] == $list[$l2]['agencyGroupID'] ){
                        array_push($saved,$l2);
                        for ($m=0; $m < sizeof($list[$l2]['agency']); $m++) { 
                           array_push($list[$l]['agency'], $list[$l2]['agency'][$m]);
                        }
                    }                    
                }
            }

            for ($s=0; $s < sizeof($saved); $s++) { 
                unset($list[$saved[$s]]);
            }

            $list = array_values($list);

            for ($l=0; $l < sizeof($list); $l++) { 
                $list[$l]['agency'] = array_map("unserialize", array_unique(array_map("serialize", $list[$l]['agency'])));
            }
        }

        return $list;
       
    }
    

}
