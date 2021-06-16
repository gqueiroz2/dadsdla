<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class forecastBase extends pAndR{
    
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
