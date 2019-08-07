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
    
    public function base($con,$r,$pr){
    	$sr = new salesRep();        
        $br = new brand();
        $base = new base();        
        $sql = new sql();

	   	$cYear = intval( date('Y') );
	   	$pYear = $cYear - 1;

        
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
        var_dump($div);
        $div = 1/$div;
        var_dump($currencyID);


        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear);

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
                $targetValues[$b][$m] = $this->generateValue($con,$sql,$regionID,$cYear,$brand[$b],$salesRep,$month[$m][1],"value","plan_by_sales",$value);
                //$values[$b][$m] = $this->generateValue($con,$sql,$region,$cYear,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
            	//$pYear = $this->
                
            }
        }

        $mergeTarget = $this->mergeTarget($targetValues,$month);

        $targetValues = $mergeTarget;
        
        var_dump($targetValues);


        $rtr = array(	
        				"cYear" => $cYear,
        				"pYear" => $pYear,
        				"salesRep" => $salesRep[0],
        				"client" => $listOfClients,
        				"targetValues" => $targetValues
                    );

        return $rtr;

    }

    public function getTargetByAE($con,$sql,$regionID,$cYear,$salesRep,$month,$div,$value){

    }

    public function listClientsByAE($con,$sql,$salesRepID,$cYear){
    	$tmp = $salesRepID[0];
    	
    	//GET FROM SALES FORCE
    	$sf = "SELECT c.name AS 'clientName',
    				   c.ID AS 'clientID'
    				FROM rolling_forecast s
    				LEFT JOIN client c ON c.ID = s.client_id
    				WHERE (s.sales_rep_id = \"$tmp\" )
    				ORDER BY 1
    	       ";   	

    	//var_dump($sf);
    	$resSF = $con->query($sf);
    	$from = array("clientName","clientID");
    	$listSF = $sql->fetch($resSF,$from,$from);
    	var_dump($listSF);

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
    		var_dump("TEM CLIENT SF");
    	}

    	if($listYTD){
    		var_dump("TEM CLIENT YTD");
    		for ($y=0; $y < sizeof($listYTD); $y++) { 
    			$list[$count] = $listYTD[$y];
    			$count ++;
    		}
    	}

    	$list = array_map("unserialize", array_unique(array_map("serialize", $list)));

    	return $list;

    }

    public function mergeTarget($plan,$month){

    	for ($m=0; $m < sizeof($month); $m++) { 
    		$mergeTarget[$m] = 0.0;
    	}

    	for ($m=0; $m < sizeof($mergeTarget); $m++) { // SIZE OF MONTH
    		for ($c=0; $c < sizeof($plan); $c++) { //SIZE OF BRAND
    		$mergeTarget[$m] += $plan[$c][$m][0];    			
    		}
    	}

    	$mergeTarget = $this->addQuartersAndTotal($mergeTarget);

    	return $mergeTarget;
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
