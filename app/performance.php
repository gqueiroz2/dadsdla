<?php

namespace App;

use App\region;
use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class performance extends Model{
    
    public function makeCore($con){
    	
        $b = new brand();
        $r = new region();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();
        $pr = new pRate();


 		$region = Request::get('region');
 		$year = Request::get('year');
 		$brand = $base->handleBrand(Request::get('brand'));
 		$source = Request::get('source');
        $salesRepGroup = Request::get('salesRepGroup');
 		$salesRep = Request::get('salesRep');
 		$currency = Request::get('currency');
 		$month = Request::get('month');
 		$value = Request::get('value');
        $tier = Request::get('tier');

 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$region,$year,$currency);

        //nome da moeda pra view
        $tmp = array($currency);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        //valor para view
        if ($value == "gross") {
            $valueView = "Gross";
        }else{
            $valueView = "Net";
        }

        //year view
        $yearView = $year;
    	

    	//nome da região na view
    	$tmp = array($region);
        $regionView = $r->getRegion($con,$tmp)[0]["name"];

        //definindo nome dos brands
        $brandName = array();
        for ($b=0; $b <sizeof($brand) ; $b++) { 
            array_push($brandName, $brand[$b][1]);
        }

        //define de onde vai se tirar as informações do banco, sendo as opções ytd(IBMS), cmaps, header ou digital.
        $actualMonth = date("m");
        for ($b=0; $b <sizeof($brand); $b++) {
            for ($m=0; $m <sizeof($month) ; $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
            }
        }

		//olha quais nucleos serão selecionados
        $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);

        //pega informações dos representantes do nucleo(s)
        for ($b=0; $b <sizeof($table); $b++) { 
            for ($m=0; $m <sizeof($table[$b]) ; $m++) { 
                //gera as colunas para o Where
                $sum[$b][$m] = $this->generateColumns($table[$b][$m],$value);
            }
        }


        for ($b=0; $b < sizeof($table); $b++) { 
            for ($m=0; $m <sizeof($table[$b]) ; $m++) {
                $values[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
                $planValues[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales");
            }
        }

        $mtx = $this->assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier);

        return $mtx;
    }

    public function assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier){
        $base = new base();

        $tmp1["values"] = array();
        $tmp1["planValues"] = array();
        $tmp2["values"] = array();
        $tmp2["planValues"] = array();

        

        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($m=0; $m <sizeof($month); $m++) { 
                for ($s=0; $s <sizeof($salesRep); $s++) { 
                    $tmp[$s][$b][$m] = 0; 
                    $tmp_2[$s][$b][$m] = 0; 
                }
            }
        }

        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($m=0; $m <sizeof($month); $m++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    $tmp[$s][$b][$m] = $values[$b][$m][$s]; 
                    $tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]; 
                }
            }
        }

        $values = $tmp;
        $planValues = $tmp_2;


        $mtx["oldValues"] = $values;
        $mtx["oldPlanValues"] = $planValues;
        $mtx["salesRep"] = $salesRep;
        $mtx["salesGroup"] = $salesGroup;
        $mtx["brand"] = $brand;
        $mtx["tier"] = $tier;
        $mtx["quarters"] = $base->monthToQuarter($month);


        for ($s=0; $s <sizeof($values);  $s++) { 
            for ($b=0; $b <sizeof($values[$s]) ; $b++) { 
                for ($m=0; $m <sizeof($values[$s][$b]) ; $m++) { 
                    $oldVarAbs[$s][$b][$m] = 0;
                    $oldVarPrc[$s][$b][$m] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($values) ; $s++) { 
            for ($b=0; $b <sizeof($values[$s]) ; $b++) { 
                for ($m=0; $m <sizeof($values[$s][$b]) ; $m++) { 
                    $oldVarAbs[$s][$b][$m] = $values[$s][$b][$m] - $planValues[$s][$b][$m];
                    if ($planValues[$s][$b][$m] != 0) {
                        $oldVarPrc[$s][$b][$m] = $values[$s][$b][$m] / $planValues[$s][$b][$m];
                    }else{
                        $oldVarPrc[$s][$b][$m] = 0;
                    }
                }
            }
        }

        $mtx["oldVarAbs"] = $oldVarAbs;
        $mtx["oldVarPrc"] = $oldVarPrc;

        //começa a agrupar por sales Group
        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) {
            for ($b=0; $b <sizeof($brand); $b++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    $tmp1["values"][$sg][$b][$m] = 0;
                    $tmp1["planValues"][$sg][$b][$m] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) {
            for ($s=0; $s <sizeof($salesRep); $s++) { 
                if ($salesRep[$s]["salesRepGroup"] == $salesGroup[$sg]["name"]) {
                    for ($b=0; $b <sizeof($brand); $b++) { 
                        for ($m=0; $m <sizeof($month); $m++) { 
                            $tmp1["values"][$sg][$b][$m] += $values[$s][$b][$m];
                            $tmp1["planValues"][$sg][$b][$m] += $planValues[$s][$b][$m];
                        }
                    }
                }
            }
        }
        //Termina de Agrupar
        
        //Começou a agrupar por tier
        for ($sg=0; $sg <sizeof($salesGroup); $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    $tmp2["values"][$sg][$t][$m] = 0;
                    $tmp2["planValues"][$sg][$t][$m] = 0;
                }
                $mtx["totalValueTier"][$sg][$t] = 0;
                $mtx["totalPlanValueTier"][$sg][$t] = 0;
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup); $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    for ($b=0; $b <sizeof($brand); $b++) { 
                        if (($brand[$b][1] == 'DC' || $brand[$b][1] == 'HH' || $brand[$b][1] == 'DK') && $mtx["tier"][$t] == "T1") {
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif($brand[$b][1] == 'OTH' && $mtx["tier"][$t] == "T3"){
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif($mtx["tier"][$t] == "T2"){
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }
                    }
                }
            }
        }
        //terminou
        //Começou a agrupar mes
        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $mtx["value"][$sg][$t][$q] = 0;
                    $mtx["planValue"][$sg][$t][$q] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    for ($m=0; $m <sizeof($month) ; $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $mtx["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $mtx["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $mtx["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $mtx["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }

                    } 
                }
            }
        }
        //terminou

        for ($sg=0; $sg <sizeof($mtx["value"]); $sg++) { 
            for ($t=0; $t <sizeof($mtx["value"][$sg]); $t++) { 
                for ($q=0; $q <sizeof($mtx["value"][$sg][$t]); $q++) { 
                    $mtx["varAbs"][$sg][$t][$q] = $mtx["value"][$sg][$t][$q] - $mtx["planValue"][$sg][$t][$q]; 
                    if ($mtx["planValue"][$sg][$t][$q] != 0) {
                        $mtx["varPrc"][$sg][$t][$q] = $mtx["value"][$sg][$t][$q] / $mtx["planValue"][$sg][$t][$q];
                    }else{
                        $mtx["varPrc"][$sg][$t][$q] = 0 ;
                    }
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["value"]); $sg++) { 
            for ($t=0; $t <sizeof($mtx["value"][$sg]); $t++) { 
                $mtx["totalVarAbs"][$sg][$t] = $mtx["totalValueTier"][$sg][$t] - $mtx["totalPlanValueTier"][$sg][$t];
                if ($mtx["totalPlanValueTier"][$sg][$t] != 0) {
                    $mtx["totalVarPrc"][$sg][$t] = $mtx["totalValueTier"][$sg][$t] / $mtx["totalPlanValueTier"][$sg][$t];
                }else{
                    $mtx["totalVarPrc"][$sg][$t] = 0;
                }
            }
        }

        return $mtx;
    }



    public function generateColumns($source,$value){
        $columns = false;
        if($source == "ytd"){
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }elseif ($source == "digital") {
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }

        return $columns;
    }

    public function generateValue($con,$sql,$region,$year,$brand,$salesRep,$month,$sum,$table){
        for ($s=0; $s <sizeof($salesRep) ; $s++) {
            $where[$s] = $this->createWhere($sql,$table,$region,$year,$brand[0],$salesRep[$s],$month);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
        }
        return $values;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month){
        if ($source == "ytd") {
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "digital"){
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif($source == "plan_by_sales"){
            $columns = array("region_id","year","month","sales_rep_id","brand_id");
            $arrayWhere = array($region,$year,$month,$salesRep["id"],$brand);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }
    
        return $where;
    }
}
