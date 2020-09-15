<?php

namespace App;

use App\sql;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use App\base;

class results extends base{    
    
    public function generateVector($con,$table,$region,$year,$month,$brand,$currency,$value,$join,$where,$souce = false){

        $base = new base();
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $as = "sum";
        $currentMonth = intval(date('m'));
        $cYear = date('Y');
        $pRate = new pRate();
        
        $div = $base->generateDiv($con,$pRate,$region,array($cYear),$currency);

        $coin = $pRate->getCurrency($con,array($currency))[0]['name'];

        if ($table == "digital") {
            $sum = "revenue";
            
            if ($coin == "USD") {
                $div = 1.0;
            }else{
                $div = $pRate->getPRateByRegionAndYear($con,array($region),array($year));
            }
            
        }elseif($table == "cmaps"){
            if($year == $cYear){
                $sum = $value;
            }else{
                $sum = $value."_revenue";
            }
        }elseif($table == "plan_by_brand"){
            $sum = "revenue";
            
            if ($coin == "USD") {
                $div = 1.0;
            }else{
                $div = $pRate->getPRateByRegionAndYear($con,array($region),array($year));
            }
        }elseif($table == 'ytd'){
            $sum = $value."_revenue_prate";
        }elseif($table == 'fw_digital'){
            $sum = $value."_revenue";
        }else{
            $sum = $value."_value_prate";
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            $vector[$m] = 0;
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            for ($b=0; $b < sizeof($brand); $b++) { 
                
                $res[$m][$b] = $sql->selectSum($con,$sum,$as,$table,$join,$where[$m][$b]);

                $valueSum = $sql->fetchSum($res[$m][$b],$as)["sum"];
                
                $vector[$m] += $valueSum;
            }

            if($table == "cmaps"){
                $coin = $pRate->getCurrency($con,array($currency))[0]['name'];
                if($coin == "USD"){
                    $pRateCMAPS = $pRate->getPRateByRegionAndYear($con,array($region),array($year));
                }else{
                    $pRateCMAPS = 1;
                }
                
                $vector[$m] = $vector[$m]/$pRateCMAPS;
            }else{
                $vector[$m] = $vector[$m]*$div;
            }

        }

        return $vector;
    }


    public function salesTable($regionID,$year){
        if($year){
            if($regionID == 1){ 
                $table = "cmaps"; 
            }else{  
                $table = "ytd"; 
            }
        }else{
            $table = 'ytd';
        }
        return $table;
    }
    
    public function matchBrandMonth($con, $currency, $form, $brands, $months, $year, $region, $value, $keyYear, $source=false){
        
        $cMonth = intval(date('m'));
        $cYear = intval(date('Y'));

        for ($b=0; $b < sizeof($brands); $b++) { 
            for ($m=0; $m < sizeof($months); $m++) { 
                if (!$source) {
                    if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
                        if ($form == "cmaps") {
                            $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                        }else{
                            $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                        }
                    }else{
                        if($year < 2020){
                            $where[$b][$m] = $this->defineValues($con, "digital", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);       
                        }else{
                            if ($form == "cmaps") {
                                $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }else{
                                $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }   
                        }
                    }
                }else{
                    $where[$b][$m] = $this->defineValues($con, "plan_by_brand", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear, $source);
                }
            }
        }

        return $where;
    }

    public function defineValues($con, $table, $currency, $brand, $month, $year, $region, $value, $keyYear, $source=false){

        $p = new pRate();

        //$keyYear = date('Y');

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }else{
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }else{
                    /*
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                    */
                    $ccYear = date('Y');
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                }                
            }    
        }else{            
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($year));
            }            
        }


        switch ($table) {

            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;

            case 'ytd':
                $columns = array("sales_representant_office_id"/*,"campaign_currency_id"*/,"brand_id", "year", "month");
                if($brand == 9){
                    $brandArray = array(9,13,14,15,16);
                    $columnsValue = array($region/*,$currency[0]['id']*/, $brandArray, $year, $month);
                }else{
                    $columnsValue = array($region/*,$currency[0]['id']*/, $brand, $year, $month);
                }
                $value .= "_revenue_prate";
                break;

            case 'mini_header':
                $sql = new sql();

                $columns = array("sales_representant_office_id","campaign_currency_id","brand_id", "year", "month");
                $columnsValue = array($region, $currency[0]['id'], $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':

                $columns = array("region_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                if ($brand == '9') {
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id != '10')";
                }else{
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id = '".$brand."')";
                }

                break;

            case 'plan_by_brand':

                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $brand, $year, $month, 4);
                $value = "revenue";
                break;

            default:
                $columns = false;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            if ($table != "digital") {
                $where = $sql->where($columns, $columnsValue);
            }            

            if($table == "digital"){
                $table = "fw_digital";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }           


        }

        return $rtr;

    }    

}
