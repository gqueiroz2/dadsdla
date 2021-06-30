<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class forecastBase extends pAndR{
    
    public function sumNetworks($array,$array1){
        for ($i=0; $i < sizeof($array); $i++) { 
            $tt[$i] = $array[$i] + $array1[$i];
        }

        return $tt;
    }

	public function calculateForecast($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$rollingFCST,$splitted,$lastYearRevClient,$lastYearRevSalesRep,$lastYearRevCompany){

        if($currency == "USD"){ $div = 1; }else{ $div = $pr->getPRateByRegionAndYear($con,array($regionID),array($year)); }
        if($value == "gross"){ $fwColumn = "gross_revenue"; $sfColumn = $fwColumn; }else{ $fwColumn = "net_revenue"; $sfColumn = $fwColumn; }        

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

    public function adjustValuesForecastAmount($fcst){

        $fcst[3]  =  $fcst[0] + $fcst[1] + $fcst[2]; // Q1

        $fcst[7]  =  $fcst[4] + $fcst[5] + $fcst[6]; // Q2

        $fcst[11] =  $fcst[8] + $fcst[9] + $fcst[10]; // Q3

        $fcst[15] =  $fcst[12] + $fcst[13] + $fcst[14]; // Q4

        $fcst[16] =  $fcst[3] + $fcst[7] + 
                                  $fcst[11] + $fcst[15];// TOTAL            

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

    public function salesRepShareOnPeriod($lyRCompany ,$lyRSP,$lyRClient,$monthOPP,$someF){
        
        /* GET INFO FROM 2018 AND MAKE SHARE BY MONTH WHEN THERE IS NO CLIENT OR SALES REP */        

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

	public function rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients,$salesRepID,$splitted){
		
		$brandString = $this->brandArrayToString($brand);
		
        $currentYear = intval(date('Y'));
        $currentMonth = intval( date("m") );
    	if($currency == "USD"){ $div = 1; }else{ $div = $pr->getPRateByRegionAndYear($con,array($regionID),array($year)); }
    	if($value == "gross"){ $ytdColumn = "gross_revenue_prate"; $fwColumn = "gross_revenue"; }else{ $ytdColumn = "net_revenue_prate"; $fwColumn = "net_revenue"; }
    	$table = "ytd";
    	for ($c=0; $c < sizeof($clients); $c++) {
            if ($splitted) {
                if ($splitted[$c]['splitted'] == true) { $mult = 2; }else{ $mult = 1; }
            }else{
                $mult = 1;
            }

    		for ($m=0; $m < sizeof($month); $m++) {     			
    			/* FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS */
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
                                            AND (brand_id IN ($brandString))
                                          ";  
                        $res[$c][$m] = $con->query($select[$c][$m]);
                        $revACT[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div*$mult;
                    }else{
                        $revACT[$c][$m] = 0.0;
                    }                    
                    
                    $rev[$c][$m] = $revACT[$c][$m];
                }else{
	    			$from = array("sumValue");
                    $select[$c][$m] = "
	    								SELECT SUM($ytdColumn) AS sumValue
	    								FROM $table
	    								WHERE (client_id = \"".$clients[$c]['clientID']."\")
	    								AND (month = \"".$month[$m][1]."\")
	    								AND (year = \"".$year."\")
	    								AND (brand_id IN ($brandString))
	    			                  ";    					    	    			
	    			$res[$c][$m] = $con->query($select[$c][$m]);
                    $rev[$c][$m] = $revACT[$c][$m];
    			}
    		}

    	}

    	return $rev;

    }

    public function consolidateAEFcst($matrix,$splitted){
        $return = array();
        $test = intval( date('n') );
        if ($test < 4) { $test++; }
        elseif ($test < 7) { $test += 2; }
        elseif ($test < 10) { $test += 3; }
        else{ $test += 4; }
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
                    $return[$m] += $matrix[$c][$m]/$div;
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

	public function getBookingExecutive($con,$sql,$salesRep,$month,$region,$year,$value,$currency,$pr,$brand){
        if($value == "gross"){ $ytdColumn = "gross_revenue_prate"; $fwColumn = "gross_revenue"; }else{ $ytdColumn = "net_revenue_prate"; $fwColumn = "net_revenue"; }
        if($currency == "USD"){ $div = 1; }else{ $div = $pr->getPRateByRegionAndYear($con,array($region),array($year)); }
        $brandString = $this->brandArrayToString($brand);
        for ($m=0; $m <sizeof($month) ; $m++) { 
            $select[$m] = "SELECT SUM($ytdColumn) AS sumValue
                                FROM ytd
                                WHERE  (month = \"".$month[$m][1]."\")
                                AND (year = \"".$year."\")
                                AND (sales_rep_id = \"".$salesRep."\")
                                AND (brand_id IN ($brandString))
                                ";                       

            $res[$m] = $con->query($select[$m]);
            $from = array("sumValue");
            $rev[$m] = $sql->fetch($res[$m],$from,$from)[0]['sumValue']*$div;                    
        
        }

        return $rev;
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

    public function brandArrayToString($array){
    	$string = null;
    	for ($a=0; $a < sizeof($array); $a++) { 
    		//$string .= "'";
    		$string .= $array[$a]['brandID'];
    		//$string .= "'";
    		if($a < sizeof($array)-1){
    			$string .= ",";
    		}

    	}
    	return $string;
    }

    public function revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$splitted,$currency,$currencyID,$value,$clients,$typeOfYear,$cYear,$brand){

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

    	$brandString = $this->brandArrayToString($brand);

    	for ($c=0; $c < sizeof($clients); $c++) {     		  
            for ($m=0; $m < sizeof($month); $m++) {
    			/* FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS */
                $select[$c][$m] = "SELECT SUM($ytdColumn) AS sumValue
                                		FROM $table
                                		WHERE (client_id = \"".$clients[$c]['clientID']."\")
			                                AND (agency_id = \"".$clients[$c]['agencyID']."\")
			                                AND (month = \"".$month[$m][1]."\")                                    
			                                AND (year = \"".$year."\")
			                                AND (brand_id IN ($brandString))
                                  ";
    			$res[$c][$m] = $con->query($select[$c][$m]);
    			$from = array("sumValue");
    			$rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;	   
    		}
    	}
    	return $rev;
    }

	public function isSplitted($con,$sql,$sR,$list,$cY,$pY){
        $soma = 0;

        $splitted = array();
        for ($l=0; $l < sizeof($list); $l++) { 
            $splitted[$l] = $this->boolSplitted($con,$sql,$sR[0],$list[$l],$cY);
        }        
        return $splitted;        
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

    public function listClientsByAE($con,$sql,$salesRepID,$cYear,$pYear,$regionID){

        // ----> Retirada a verificação de pegar apesar forecast de meses abertos -- 2021-06-16 $date = date('n')-1; // Último mês fechado ( mês atual - 1 )

        $tmp = $salesRepID[0];
        //GET FROM SALES FORCE
        $sf = "SELECT DISTINCT c.name AS 'clientName',
                       c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
                    FROM sf_pr s
                    LEFT JOIN client c ON c.ID = s.client_id
                    LEFT JOIN agency a ON a.ID = s.agency_id
                    WHERE ((s.sales_rep_owner_id = \"$tmp\") OR (s.sales_rep_splitter_id = \"$tmp\"))
                    AND ( s.region_id = \"".$regionID."\") 
                    AND ( s.stage != \"6\") 
                    AND ( s.stage != \"5\") 
                    AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\")
                    ORDER BY 1
               ";
        // AND (s.from_date > \"$date\")    ----> Retirada a verificação de pegar apesar forecast de meses abertos -- 2021-06-16
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

        /*
        	Juntando clientes do CRM e do BTS
        */

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

    public static function orderClient($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }

    public function getSeparatedBrands($con,$sql,$salesRepID,$cYear,$regionID){
        $selectB = "SELECT 
        				b.name AS 'brandName',
                        b.ID AS 'brandID'
                        FROM brand b
                        WHERE(brand_group_id = 1)
                   ";
        $resB = $con->query($selectB);
        $fromB = array("brandName","brandID");
        $listB = $sql->fetch($resB,$fromB,$fromB);

        $selectWS = "SELECT 
        				b.name AS 'brandName',
                        b.ID AS 'brandID'
                        FROM brand b
                        WHERE(brand_group_id = 2)
                   ";
        $resWS = $con->query($selectWS);
        $fromWS = array("brandName","brandID");
        $listWS = $sql->fetch($resWS,$fromWS,$fromWS);
       	
       	$list = array("discovery" => $listB , "sony" => $listWS);

        return $list;

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
