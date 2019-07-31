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
        $con = $db->openConnection("DLA");
        $as = "sum";
        $currentMonth = intval(date('m'));
        $cYear = date('Y');
        $pRate = new pRate();
        
        $div = $base->generateDiv($con,$pRate,$region,array($cYear),$currency);

        if ($table == "digital") {
            
            $sum = "revenue";
            $div = 1.0;
        }elseif($table == "cmaps"){
            if($year == $cYear){
                $sum = $value;
            }else{
                $sum = $value."_revenue";
            }
        }elseif($table == "plan_by_brand"){
            $sum = "revenue";
            $div = 1.0;
        }elseif($table = 'ytd'){
            $sum = $value."_revenue_prate";
        }else{
            $sum = $value."_value_prate";
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            $vector[$m] = 0;
        }

        for ($m=0; $m < sizeof($month); $m++) { 
            for ($b=0; $b < sizeof($brand); $b++) { 
                
                if($table == "digital"){
                    var_dump($where[$m][$b]);
                }


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

                $vector[$m] = $vector[$m]/$pRateCMAPS;//
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
                //$table = "mini_header"; 
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
                    /*if ($brands[$b][1] == 'FN' && $region == 1) {
                        $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                    }else*/
                    if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
                        if ($form == "mini_header") {
                            if (($year == $cYear) && ($months[$m][1] < $cMonth)) {
                                $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }else{
                                $where[$b][$m] = $this->defineValues($con, "mini_header", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }
                        }elseif ($form == "cmaps") {
                            if ($year == $cYear) {
                                $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }else{
                                $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                            }
                        }else{
                            $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);
                        }
                    }else{
                        $where[$b][$m] = $this->defineValues($con, "digital", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear);       
                    }
                }else{
                    $where[$b][$m] = $this->defineValues($con, "plan_by_brand", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $keyYear, $source);
                }
            }
        }

        return $where;
    }

    public function defineValues($con, $table, $currency, $brand, $month, $year, $region, $value, $keyYear, $source=false){

        if ($table != "plan_by_brand" && $table != "digital") {
            $p = new pRate();

            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                }else{
                    $pRate = 1.0;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                }else{
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                }                
            }    
        }else{            
            $pRate = 1.0;
        }

        switch ($table) {

            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;

            case 'ytd':
                $columns = array("sales_representant_office_id"/*,"campaign_currency_id"*/,"brand_id", "year", "month");
                $columnsValue = array($region/*,$currency[0]['id']*/, $brand, $year, $month);
                $value .= "_revenue_prate";
                break;

            case 'mini_header':
                $sql = new sql();

                $columns = array("sales_representant_office_id","campaign_currency_id","brand_id", "year", "month");
                $columnsValue = array($region, $currency[0]['id'], $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':
                if($currency[0]['name'] == 'USD'){
                    $seek = 4;
                }else{
                    $seek = $region;
                }

                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month", "currency_id");
                $columnsValue = array($region, "ACTUAL", $value, $brand, $year, $month, $seek);
                $value = "revenue";
                break;
/*
    
    DIGITAL ESTA VINDO DO ACTUAL DO PLAN BY BRAND

                $columns = array("sales_representant_office_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                break;
*/
            case 'plan_by_brand':

                if($currency[0]['name'] == 'USD'){
                    $seek = 4;
                }else{
                    $seek = $region;
                }

                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $brand, $year, $month, $seek);
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

            $where = $sql->where($columns, $columnsValue);

            if($table == "digital"){
                $table = "plan_by_brand";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else{
                $rtr = $tmp*$pRate;
            }           


        }

        return $rtr;

    }    

}
