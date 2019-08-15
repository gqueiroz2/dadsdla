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
       
        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $salesRep = $sr->getSalesRepById($con,$salesRepID);        

        $brand = $br->getBrandBinary($con);
        $month = $base->getMonth();

        $tmp = array($cYear);
 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);
        //div = 1/$div;

        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $readable = $this->monthAnalise($base);
        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear);        

        $splitted = $this->isSplitted($con,$sql,$salesRepID,$listOfClients,$cYear,$pYear);
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

        for ($b=0; $b < sizeof($table); $b++){ 


            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $targetValues[$b][$m] = $this->generateValue($con,$sql,$regionID,$cYear,$brand[$b],$salesRep,$month[$m][1],"value","plan_by_sales",$value)[0]*$div;
                
                //var_dump($targetValues[$b][$m]);
                //$values[$b][$m] = $this->generateValue($con,$sql,$region,$cYear,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
            	//$pYear = $this->
                
            }
        }

        
        $mergeTarget = $this->mergeTarget($targetValues,$month);
        $targetValues = $mergeTarget;
        
        $rollingFCST = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$brand,$currency,$currencyID,$value,$listOfClients);
        //var_dump($rollingFCST);
        $rollingFCST = $this->addQuartersAndTotalOnArray($rollingFCST);


        $clientRevenueCYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$currency,$currencyID,$value,$listOfClients);
        $clientRevenueCYear = $this->addQuartersAndTotalOnArray($clientRevenueCYear);

       	$clientRevenuePYear = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$currency,$currencyID,$value,$listOfClients);
       	$clientRevenuePYear = $this->addQuartersAndTotalOnArray($clientRevenuePYear);
       	
        $tmp = $this->getBookingExecutive($con,$sql,$salesRepID,$month,$regionID,$cYear,$value,$currency,$pr);

        $executiveRevenueCYear = $this->addQuartersAndTotal($tmp);
        $executiveRevenuePYear = $this->consolidateAE($clientRevenuePYear);
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

        //var_dump($rollingFCST);

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

/*
        var_dump($list);
        var_dump($sR);
        var_dump($oppid);
*/

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

    public function rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$brand,$currency,$currencyID,$value,$clients){

    	//var_dump($currency);
    	//var_dump($value);


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
    		//var_dump($clients[$c]);
    		for ($m=0; $m < sizeof($month); $m++) {     			
    			//for ($b=0; $b < sizeof($brand); $b++) { 
    			/*
						FAZER A DIFERENCIAÇÃO ENTRE OS CANAIS
    			*/
					/*
	    			$select[$c][$m] = "
	    								SELECT SUM($ytdColumn) AS sumValue
	    								FROM $table
	    								WHERE (client_id = \"".$clients[$c]['clientID']."\")
	    								AND (month = \"".$month[$m][1]."\")
	    								AND (year = \"".$year."\")

	    			                  ";
					*/
	    			//var_dump($select[$c][$m]);
	    			
	    			//$res[$c][$m] = $con->query($select[$c][$m]);
	    			//var_dump($res[$c][$m]);

                    $select[$c][$m] = "SELECT SUM($fwColumn) AS sumValue 
                                        FROM fw_digital
                                        WHERE (client_id = \"".$clients[$c]["clientID"]."\")
                                        AND (month = \"".$month[$m][1]."\")
                                        AND (year = \"".$year."\")
                                        ";

                    //var_dump($select[$c][$m]);
                    $res[$c][$m] = $con->query($select[$c][$m]);
                    //var_dump($res[$c][$m]);

	    			$from = array("sumValue");

	    			$rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue']*$div;	    			
	    			//var_dump($rev[$c][$m]);
                    //var_dump($rev[$c][$m]);
                    //var_dump($clients[$c]);
                    //var_dump($month[$m][0]);
	    		//}
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


    public function revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$year,$month,$salesRep,$currency,$currencyID,$value,$clients){



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
                                    AND (sales_rep_id = \"".$salesRep."\")
                                    AND (year = \"".$year."\")
                                    ";

    			$res[$c][$m] = $con->query($select[$c][$m]);
                $resFW[$c][$m] = $con->query($selectFW[$c][$m]);

    			$from = array("sumValue");

    			$rev[$c][$m] = $sql->fetch($res[$c][$m],$from,$from)[0]['sumValue'];	    			
                $revFW[$c][$m] = $sql->fetch($resFW[$c][$m],$from,$from)[0]['sumValue'];                    


                if( !is_null($revFW[$c][$m]) ){
                    var_dump($select[$c][$m]);
                    var_dump($selectFW[$c][$m]);
                    var_dump($clients[$c]);
                    var_dump($month[$m][1]);
                    var_dump($salesRep);
                    var_dump("IBMS");
                    var_dump($rev[$c][$m]);
                    var_dump("FREEWHEEL");
                    var_dump($revFW[$c][$m]);
                    $rev[$c][$m] += $revFW[$c][$m];
                    var_dump($rev[$c][$m]);
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
