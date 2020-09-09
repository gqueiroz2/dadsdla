<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resultsPacing extends Model{
    
	public function construct($con,$currency,$months,$brands,$region,$value){

		$form = "bts";
		$year = date('Y');

		for ($b=0; $b < sizeof($brands); $b++) { 
            for ($m=0; $m < sizeof($months); $m++) { 
                if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
                    if ($form == "cmaps") {
                        $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                    }else{
                        $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value);
                    }
                }else{
                    if($year < 2020){
                        $where[$b][$m] = $this->defineValues($con, "digital", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $year);       
                    }else{
                        if ($form == "cmaps") {
                            $where[$b][$m] = $this->defineValues($con, "cmaps", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $year);
                        }else{
                            $where[$b][$m] = $this->defineValues($con, "ytd", $currency, $brands[$b][0], $months[$m][1], $year, $region, $value, $year);
                        }   
                    }
                }                
            }
        }
	}

	public function defineValues($con, $table, $currency, $brand, $month, $region, $value, $year, $source=false){

        $p = new pRate();

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($year));
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
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($year));
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
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($year));
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

            $selectSum = $sql->selectSum2($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }           


        }

        var_dump($rtr);

        return $rtr;

    }

}
