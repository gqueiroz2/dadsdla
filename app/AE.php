<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;
class AE extends pAndR{
    
    public function base($con,$r,$pr,$cYear,$pYear){
    	$sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = new region();
       
        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

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
        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear);        

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
        
        $clientRevenueCYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear");
        $clientRevenueCYear = $this->addQuartersAndTotalOnArray($clientRevenueCYear);

        $clientRevenuePYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear");
        $clientRevenuePYear = $this->addQuartersAndTotalOnArray($clientRevenuePYear);

        $tmp = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr);
        
        $executiveRevenueCYear = $this->addQuartersAndTotal($tmp);
        $executiveRevenuePYear = $this->consolidateAE($clientRevenuePYear);

        $rollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0]);

        $fcst = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCST,$splitted,$clientRevenuePYear,$executiveRevenuePYear,$lastYear);

        $fcstAmountByStage = $fcst['fcstAmountByStage'];

        $toRollingFCST = $fcst['fcstAmount'];

        $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);        

        $rollingFCST = $this->addFcstWithBooking($rollingFCST,$toRollingFCST);
       	
        $executiveRF = $this->consolidateAE($rollingFCST);
        $pending = $this->subArrays($executiveRF,$executiveRevenueCYear);
        $RFvsTarget = $this->subArrays($executiveRF,$targetValues);
        $targetAchievement = $this->divArrays($executiveRF,$targetValues);

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

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
        				"clientRevenueCYear" => $clientRevenueCYear,
        				"clientRevenuePYear" => $clientRevenuePYear,

                        "executiveRF" => $executiveRF,
                        "executiveRevenuePYear" => $executiveRevenuePYear,
                        "executiveRevenueCYear" => $executiveRevenueCYear,

                        "pending" => $pending,
                        "RFvsTarget" => $RFvsTarget,
                        "targetAchievement" => $targetAchievement,
                    

                        "currency" => $currencyName,
                        "value" => $valueView,
                    );

        return $rtr;
        
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
            }else{
                $tfArray[$m] = "readonly='true'";
                $odd[$m] = "oddGrey";
                $even[$m] = "evenGrey";
            }
        } 

        $rtr = array("tfArray" => $tfArray , "odd" => $odd , "even" => $even);    

        return $rtr;
    }

    public function isSplitted($con,$sql,$sR,$list,$cY,$pY){
        $soma = 0;
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

        $select = "SELECT DISTINCT order_reference , sales_rep_id , client_id
                        FROM ytd
                        WHERE (client_id = \"".$list['clientID']."\")
                        AND (year = \"".$year."\")                       
                  ";

        $res = $con->query($select);
        $from = array("order_reference","sales_rep_id","client_id");
        $orderRef = $sql->fetch($res,$from,$from);

        $cc = 0;
        if($orderRef){
            for ($o=0; $o < sizeof($orderRef); $o++) { 
                $splitted[$cc] = $orderRef[$o]['sales_rep_id'];
                $cc++;
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

        $selectSF = "SELECT DISTINCT oppid , sales_rep_owner_id , sales_rep_splitter_id , client_id
                        FROM sf_pr
                        WHERE (client_id = \"".$list['clientID']."\") 
                        AND (sales_rep_splitter_id != sales_rep_owner_id)                       
                  ";

        $resSF = $con->query($selectSF);
        $fromSF = array("oppid","sales_rep_owner_id","sales_rep_splitter_id","client_id");
        $oppid = $sql->fetch($resSF,$fromSF,$fromSF);

        if($oppid){
            $rtr = array( "splitted" => true , "owner" => false );    
            for ($o=0; $o < sizeof($oppid); $o++) {                 
                if($sR == $oppid[$o]['sales_rep_owner_id']){
                    $rtr = array( "splitted" => true , "owner" => true );
                    break;
                }
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
            $someFCST = $this->getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients[$c],$salesRepID,$splitted[$c]); // PERIOD OF FCST , VALUES AND STAGE
            $monthOPP = $this->periodOfOPP($someFCST); // MONTHS OF THE FCST
            if($monthOPP){
                $shareSalesRep = $this->salesRepShareOnPeriod($lastYearRevCompany,$lastYearRevSalesRep,$lastYearRevClient[$c],$monthOPP,$someFCST);
                $fcst[$c] = $this->fillFCST($someFCST,$monthOPP,$shareSalesRep);
            }else{
                $shareSalesRep = false;
                $fcst[$c] = false;
            }
            if($fcst[$c]){
                $fcst[$c] = $this->adjustValues($fcst[$c]);
                $fcstAmountByStage[$c] = $this->fcstAmountByStage($fcst[$c],$monthOPP);
                $fcstAmount[$c] = $this->fcstAmount($fcst[$c],$monthOPP);
                $fcstAmount[$c] = $this->adjustValuesForecastAmount($fcstAmount[$c]);
            }else{
                $fcstAmountByStage[$c] = false;
                $fcstAmount[$c] = false;
            }
        }

        $rtr = array("fcstAmount" => $fcstAmount ,"fcstAmountByStage" => $fcstAmountByStage);

        return $rtr;        
    }

    public function fcstAmount($fcst,$mOPP){
        $base = new base();

        $monthWQ = $base->monthWQ;

        for ($m=0; $m < sizeof($monthWQ); $m++) { 
            $fcstAmount[$m] = 0.0;
        }

       for ($m=0; $m < sizeof($mOPP); $m++) { 
           for ($n=0; $n < sizeof($mOPP[$m]); $n++) { 
                
               $fcstAmount[$mOPP[$m][$n]] += $fcst[$m][$mOPP[$m][$n]]['value'];
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
            for ($j=0; $j < sizeof($mOPP[$i]); $j++) { 
                $fcst[$i][$mOPP[$i][$j]]['stage'] = $sFCST[$i]['stage'];
                $fcst[$i][$mOPP[$i][$j]]['value'] = $sFCST[$i]['sumValue']*$sRP[$j];
            }    
        }

        return $fcst;
    }

    public function salesRepShareOnPeriod($lyRCompany ,$lyRSP,$lyRClient,$monthOPP,$someF){
        /*

            GET INFO FROM 2018 AND MAKE SHARE BY MONTH WHEN THERE IS NO CLIENT OR SALES REP

        */        
            
        $amount = 0.0;
        for ($l=0; $l < sizeof($monthOPP); $l++){
            for ($m=0; $m < sizeof($monthOPP[$l]); $m++) { 
                if($lyRClient[$monthOPP[$l][$m]] > 0 && $lyRClient[16] > 0){
                    /*
                        GET THE SHARE OF THE CLIENT ON THE SAME MONTH ON LAST YEAR
                    */
                    $share[$m] = $lyRClient[$monthOPP[$l][$m]];//$lyRClient[16];
                    $amount += $share[$m];
                }elseif($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                    /*
                        IF THE CLINET DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE REP ON THE SAME MONTH ON LAST YEAR
                    */
                    if($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                        $share[$m] = $lyRSP[$monthOPP[$l][$m]];//$lyRSP[16];  
                        $amount += $share[$m];
                    }else{
                        /*
                            IF THE SALES REP DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE MONTH ON THE  ON LAST YEAR
                        */
                        $share[$m] = $lyRCompany[$monthOPP[$l][$m]];//$lyRCompany[16];
                        $amount += $share[$m];
                    }
                }
            }
        }

        for ($s=0; $s < sizeof($share); $s++) { 
            $share[$s] = $share[$s]/$amount;
        }        

        return $share;
    }

    public function periodOfOpp($opp){
        if($opp){
            for ($o=0; $o < sizeof($opp); $o++){                 
                $period[$o] = $this->monthOPP($opp[$o]);       
            }
        }else{
            $period = false;
        }



        return $period;
    }

    public function monthOPP($opp){
        $start = intval( $opp['fromDate'] );
        $end = intval( $opp['toDate'] );
        $month = array();
        for ($m = $start; $m <= $end; $m++) { 
            array_push($month, $m);
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


    public function getValuePeriodAndStageFromOPP($con,$sql,$base,$pr,$sfColumn,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$splitted){
        
        $from = array($sfColumn,'from_date','to_date','stage');
        $to = array("sumValue",'fromDate','toDate','stage');
        if($splitted){ /* SF FCST FROM BRAZIL, WHERE THERE IS AE SPLITT SALES */
                $select = "
                                SELECT from_date , to_date, stage , $sfColumn
                                FROM sf_pr
                                WHERE (client_id = \"".$clients['clientID']."\")
                                AND ( sales_rep_splitter_id = \"".$salesRepID."\" )
                                AND (stage != '5' && stage != '6')
                                AND (year_from = \"".$year."\")
                              "; 
            if($splitted['splitted']){ /* SF FCST FROM A BRAZIL CLIENT , WHERE THERE IS AE SPLITT SALES */
                

            }else{ /* SF FCST FROM A BRAZIL CLIENT , WHERE THERE IS NOT AE SPLITT SALES */
                
            }
        }else{/* SF FCST FROM OTHER REGIONS , WHERE THERE IS NOT AE SPLITT SALES */
            $select = "
                            SELECT from_date , to_date, stage , $sfColumn
                            FROM sf_pr
                            WHERE (client_id = \"".$clients['clientID']."\")
                            AND ( sales_rep_splitter_id = \"".$salesRepID."\" )
                            AND (stage != '5' && stage != '6')
                          ";
        }
        $res = $con->query($select);
        $rev = $sql->fetch($res,$from,$to);
        return $rev;

    }

    public function rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID){
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
                                            AND (sales_rep_id = \"".$salesRepID."\")
                                            AND (year = \"".$year."\")

                                          ";  
                        $res[$c][$m] = $con->query($select[$c][$m]);
                        $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;                   
                    }else{
                        $revACT[$c][$m] = 0.0;
                    }                    
                    
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

    public function getBookingExecutive($con,$sql,$salesRep,$month,$region,$year,$value,$currency,$pr){

        if ($value == "gross") {
            $ytdColumn = "gross_revenue_prate";
        }else{
            $ytdColumn = "net_revenue_prate";
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
                            AND (sales_rep_id = \"".$salesRep[0]."\")";

            $res[$m] = $con->query($select[$m]);

            $from = array("sumValue");

            $rev[$m] = $sql->fetch($res[$m],$from,$from)[0]['sumValue']*$div;                    
        
        }

        return $rev;
    }


    public function revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$splitted,$currency,$currencyID,$value,$clients,$typeOfYear){

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
        $tableFW = "fw_digital"; 


    	for ($c=0; $c < sizeof($clients); $c++) { 
    		  
            if($splitted){
                if($splitted[$c]['splitted']){
                    $factor = 2; 
                }else{
                    $factor = 1; 
                }
            }else{
                $factor = 1;
            }

            for ($m=0; $m < sizeof($month); $m++) {     			
    			/*
						FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
    			*/
                if($typeOfYear == "cYear"){
    			    $select[$c][$m] = "
    								SELECT SUM($ytdColumn) AS sumValue
    								FROM $table
    								WHERE (client_id = \"".$clients[$c]['clientID']."\")
    								AND (month = \"".$month[$m][1]."\")
                                    AND (sales_rep_id = \"".$salesRep."\")
    								AND (year = \"".$year."\")

    			                  ";
                    $selectFW[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                    FROM $tableFW
                                    WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                    AND (month = \"".$month[$m][1]."\")
                                    AND (sales_rep_id = \"".$salesRep."\")
                                    AND (year = \"".$year."\")
                                    ";
                }else{
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
                }                   

    			$res[$c][$m] = $con->query($select[$c][$m]);
                $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

    			$from = array("sumValue");

    			$rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*2;	    			
                
                $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue']*2;                    

                if( !is_null($revFW[$c][$m]) ){
                    
                    $rev[$c][$m] += ( $revFW[$c][$m] * $div );
                    
                }

    		}
    	}

    	return $rev;

    }

    private static function orderClient($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }

    public function listClientsByAE($con,$sql,$salesRepID,$cYear){

        $tmp = $salesRepID[0];
    	//GET FROM SALES FORCE
    	$sf = "SELECT DISTINCT c.name AS 'clientName',
    				   c.ID AS 'clientID'
    				FROM sf_pr s
    				LEFT JOIN client c ON c.ID = s.client_id
    				WHERE (      (s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\")      )
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
    				WHERE (y.sales_rep_id = \"$tmp\" )
    				AND (y.year = \"$cYear\" )
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
