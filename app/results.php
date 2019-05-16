<?php

namespace App;

use App\sql;
use App\pRate;
use Illuminate\Database\Eloquent\Model;

class results extends Model{    
    
    public function generateVector($con,$table,$region,$year,$month,$brand,$currency,$value,$join,$where,$souce = false){
        $sql = new sql();
        if($table == "cmaps"){
            if($value == "gross"){
                $sum = $value;
            }else{
                $sum = $value;
            }
        }elseif($table == "plan_by_brand"){
            $sum = "revenue";
        }else{
            if($value == "gross"){
                $sum = $value."_value";
            }else{
                $sum = $value."_value";
            }
        }
        $as = "sum";
        for ($m=0; $m < sizeof($month); $m++) { 
            $res[$m] = $sql->selectSum($con,$sum,$as,$table,$join,$where[$m]);
            $vector[$m] = $sql->fetchSum($res[$m],$as)["sum"];
        }
        return $vector;
    }

    public function salesTable($regionID,$year){
        if($year){
            if($regionID == 1){ 
                $table = "cmaps"; 
            }else{  
                $table = "mini_header"; 
            }
        }else{
            $table = 'ytd';
        }
        return $table;
    }
    
    public function matchBrandMonth($con, $currency, $form, $brands, $months, $year, $region, $value, $source=false){
        
        $cMonth = intval(date('m'));
        $cYear = intval(date('Y'));
        for ($b=0; $b < sizeof($brands); $b++) { 
            for ($m=0; $m < sizeof($months); $m++) { 
                if (!$source){
                    if ($form == "mini_header"){
                        if($year == $cYear){
                            if (($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') && ($months[$m][1] < $cMonth) ) {
                                $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                            }else{
                                $where[$b][$m] = $this->defineValues($con, "mini_header", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                            }    
                        }else{
                            $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                        }
                    }elseif(($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX')){
                        $where[$b][$m] = $this->defineValues($con, $form, $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                    }else{
                        $where[$b][$m] = $this->defineValues($con, "digital", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);       
                    }
                }else{
                    $where[$b][$m] = $this->defineValues($con, "plan_by_brand", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $source);
                }

            }
        }

        return $where;
    }

    public function defineValues($con, $table, $currency, $brand, $month, $year, $region, $value, $source=false){

        if ($table != "plan_by_brand") {
            $p = new pRate();

            if ($currency == "USD") {
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
            }else{
                $pRate = 1.0;
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
                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                break;

            case 'mini_header':
                $sql = new sql();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':
                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                break;

            case 'plan_by_brand':

                if($currency == 'USD'){
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

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            $rtr = $sql->fetchSum($selectSum, $as)["sum"]/$pRate;
            //var_dump(number_format($rtr));
        }

        return $rtr;
    }

    public function TruncateName($form){
        
        if ($form == 'mini_header') {
            $newForm = "Header";
        }elseif ($form == 'cmaps') {
            $newForm = "CMAPS";
        }else{
            $newForm = "IBMS";
        }

        return $newForm;
    }
}
