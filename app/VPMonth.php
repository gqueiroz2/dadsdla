<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\pAndR;
use App\sql;
use App\base;
use App\pRate;

class VPMonth extends pAndR {

    public function base($con, $region, $regionID, $currencyID, $year, $value){
        
        $base = new base();
        $sql = new sql();
        $pr = new pRate();

        $select = "SELECT oppid,ID,type_of_value,currency_id FROM forecast ORDER BY last_modify_date DESC";

        $result = $con->query($select);

        $from = array("ID","oppid","type_of_value","currency_id");

        $save = $sql->fetch($result,$from,$from);
        
        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
        }else{
            $save = $save[0];
            
            if ($currencyID == $save['currency_id']) {
                $currencyCheck = false;
            }else{
                $newCurrency = $pr->getPrateByCurrencyAndYear($con,$currencyID,$year);
                $oldCurrency = $pr->getPrateByCurrencyAndYear($con,$save['currency_id'],$year);
                $currencyCheck = true;
            }
            
            if ($value ==  strtolower($save["type_of_value"])) {
                $valueCheck = false;
            }else{
                
                $valueCheck = true;
                $tmp = array($regionID);
                $mult = $base->getAgencyComm($con,$tmp);

                if ($value == "net") {
                    $multValue = (100 - $mult)/100;
                }elseif($value == "gross"){
                    $multValue = 1/(1-($mult/100));
                }
            }

        }

        $regionName = $region;

        $br = new brand();
        $brand = $br->getBrandBinary($con);

        $month = $base->getMonth();

        $tmp = array($year);
        $pRate = $base->generateDiv($con,$pr,$regionID,$tmp,$currencyID);

        $readable = $this->monthAnalise($base);

        $listOfClients = $this->listClientsByVPMonth($con,$sql,$year,$regionID);

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

        for ($m=0; $m < sizeof($month); $m++) {
            $lastYear[$m] = $this->generateValueWB($con,$sql,$regionID,($year-1),$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$pRate;
        }

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

    private static function orderClient($a, $b){
        
        if ($a == $b)
            return 0;
        
        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }

}
