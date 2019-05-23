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
 		$currency = Request::get('currency');
 		$month = Request::get('month');
 		$value = Request::get('value');


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
        $yearView = $year[0];
    	
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
            for ($m=0; $m <sizeof($month); $m++) {
                if ($m > $actualMonth-1) {
                    if($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$b][$m] = "Digital";
                    }elseif ($region == "1") {
                        $sourceBrand[$b][$m] = "CMAPS";
                    }else{
                        $sourceBrand[$b][$m] = "Header";
                    }
                }else{
                    if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$b][$m] = "Digital";
                    }elseif ($brand[$b][1] == "OTH") {
                        $sourceBrand[$b][$m] = "IBMS";
                    }elseif($brand[$b][1] == "FN" && $region == "1"){
                        $sourceBrand[$b][$m] = "CMAPS";
                    }else{
                        $sourceBrand[$b][$m] = $source;
                    }
                }
            }
        }

		//olha quais nucleos serão selecionados        
        $salesRepName = array();
        if ($salesRepGroup == 'all') {
                
            $tmp = array($region);
        
            $salesRepGroup = $sr->getSalesRepGroup($con,$tmp);
        
            $salesGroup = $salesRepGroup;

            $tmp = array();
            
            for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
                array_push($tmp, $salesRepGroup[$i]["id"]);
            }

            $salesRepGroup = $tmp;
        
            $salesRepGroupView = "All";   
        }else{

            $salesRepGroup = array($salesRepGroup);

            $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);

            $salesRepGroupView = $salesGroup["name"];

            $salesGroup = array($salesGroup);

        }


        //pega informações dos representantes do nucleo(s)
        $srAllInfo = $sr->getSalesRepFilteredYear($con,$salesRepGroup,$region,$year,$source);
        $salesRep = array();
        $salesRepView = "All";
        for ($i=0; $i <sizeof($srAllInfo) ; $i++) { 
            array_push($salesRep, $srAllInfo[$i]["id"]);
            array_push($salesRepName, $srAllInfo[$i]["salesRep"]);
        }


        for ($b=0; $b <sizeof($sourceBrand); $b++) { 
            for ($m=0; $m <sizeof($sourceBrand[$b]) ; $m++) { 
                //procura tabela para fazer a consulta (digital e OTH são em tabelas diferentes)
                $table[$b][$m] = $this->defineTable($sourceBrand[$b][$m]);
                //gera as colunas para o Where
                $sum[$b][$m] = $this->generateColumns($sourceBrand[$b][$m],$value);
            }
        }

        for ($b=0; $b <sizeof($sourceBrand) ; $b++) {
            for ($m=0; $m < sizeof($sourceBrand[$b]); $m++) { 
                $values[$b][$m] = 0;
            }
        }

        for ($b=0; $b < sizeof($sourceBrand); $b++) { 
            for ($m=0; $m <sizeof($sourceBrand[$b]) ; $m++) {
                $values[$b][$m] = $this->generateValue($con,$sql,$sourceBrand[$b][$m],$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
                $planValues[$b][$m] = $this->generateValue($con,$sql,"Plan",$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales");
            }
        }

        $mtx = $this->assembler($values,$planValues,$srAllInfo,$month,$brand,$salesGroup);


        return $mtx;
    }

    public function assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup){

        $tmp1["values"] = array();
        $tmp1["planValues"] = array();
        $tmp2["values"] = array();
        $tmp2["planValues"] = array();

        $mtx["oldValues"] = $values;
        $mtx["oldPlanValues"] = $values;
        $mtx["salesRep"] = $salesRep;
        $mtx["salesGroup"] = $salesGroup;
        $mtx["brand"] = $brand;
        $mtx["tier"] = array("T1","T2","T3");
        $mtx["quarters"] = array("Q1","Q2","Q3","Q4");

        //Começa o Agrupamento por Brand em tier
        for ($m=0; $m <sizeof($month) ; $m++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                $tmp1["values"][0][$m][$s] = 0;
                $tmp1["values"][1][$m][$s] = 0;
                $tmp1["values"][2][$m][$s] = 0;
                $tmp1["planValues"][0][$m][$s] = 0;
                $tmp1["planValues"][1][$m][$s] = 0;
                $tmp1["planValues"][2][$m][$s] = 0;
            }
        }

        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($m=0; $m <sizeof($month) ; $m++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    if ($brand[$b][1] == 'DC' || $brand[$b][1] == 'HH' || $brand[$b][1] == 'DK') {
                        $tmp1["values"][0][$m][$s] += $values[$b][$m][$s];
                        $tmp1["planValues"][0][$m][$s] += $planValues[$b][$m][$s];
                    }elseif ($brand[$b][1] == 'AP' || $brand[$b][1] == 'TLC' || $brand[$b][1] == 'ID' || $brand[$b][1] == 'DT' || $brand[$b][1] == 'FN' || $brand[$b][1] == 'ONL') {
                        $tmp1["values"][1][$m][$s] += $values[$b][$m][$s];
                        $tmp1["planValues"][1][$m][$s] += $planValues[$b][$m][$s];
                    }else{
                        $tmp1["values"][2][$m][$s] += $values[$b][$m][$s];
                        $tmp1["planValues"][2][$m][$s] += $planValues[$b][$m][$s];
                    }
                }
            }
        }
        //terminou de Agrupar por Tier

        //Começou a agrupar os meses em Quarter
        for ($t=0; $t <sizeof($tmp1["values"]) ; $t++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                $tmp2["values"][$t][0][$s] = 0;
                $tmp2["planValues"][$t][0][$s] = 0;
                $tmp2["values"][$t][1][$s] = 0;
                $tmp2["planValues"][$t][1][$s] = 0;
                $tmp2["values"][$t][2][$s] = 0;
                $tmp2["planValues"][$t][2][$s] = 0;
                $tmp2["values"][$t][3][$s] = 0;
                $tmp2["planValues"][$t][3][$s] = 0;
            }
        }


        //t de tier
        for ($t=0; $t <sizeof($tmp1["values"]) ; $t++) { 
            for ($m=0; $m <sizeof($month) ; $m++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    if ($month[$m] == "1" || $month[$m] == "2" || $month[$m] == "3") {
                        $tmp2["values"][$t][0][$s] += $tmp1["values"][$t][$m][$s];
                        $tmp2["planValues"][$t][0][$s] += $tmp1["planValues"][$t][$m][$s];
                    }elseif($month[$m] == "4" || $month[$m] == "5" || $month[$m] == "6") {
                        $tmp2["values"][$t][1][$s] += $tmp1["values"][$t][$m][$s];
                        $tmp2["planValues"][$t][1][$s] += $tmp1["planValues"][$t][$m][$s];
                    }elseif($month[$m] == "7" || $month[$m] == "8" || $month[$m] == "9") {
                        $tmp2["values"][$t][2][$s] += $tmp1["values"][$t][$m][$s];
                        $tmp2["planValues"][$t][2][$s] += $tmp1["planValues"][$t][$m][$s];
                    }else{
                        $tmp2["values"][$t][3][$s] += $tmp1["values"][$t][$m][$s];
                        $tmp2["planValues"][$t][3][$s] += $tmp1["planValues"][$t][$m][$s];
                    }
                }
            }
        }
        //Terminou de Agrupar em Quarter

        //Começa a agrupar por SalesGroup


        for ($t=0; $t <sizeof($tmp2["values"]) ; $t++) { 
            for ($q=0; $q <sizeof($tmp2["values"][$t]) ; $q++) { 
                for ($sg=0; $sg <sizeof($salesGroup); $sg++) { 
                    $mtx["values"][$t][$q][$sg] = 0; 
                    $mtx["planValues"][$t][$q][$sg] = 0; 
                }
            }
        }

        for ($t=0; $t <sizeof($tmp2["values"]) ; $t++) { 
            for ($q=0; $q <sizeof($tmp2["values"][$t]) ; $q++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) { 
                        if ($salesRep[$s]["salesRepGroup"] == $salesGroup[$sg]["name"]) {
                            $mtx["values"][$t][$q][$sg] += $tmp2["values"][$t][$q][$s];
                            $mtx["planValues"][$t][$q][$sg] += $tmp2["planValues"][$t][$q][$s];
                        }
                    }
                }
            }
        }

        //Terminou de Agrupar em SalesGroup


        return $mtx;
    }



    public function generateColumns($source,$value){
        $columns = false;
        if ($source == "CMAPS") {
            if ($value == "gross") {
                $columns = "gross";
            }else{
                $columns = "net";
            }
        }elseif($source == "IBMS"){
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }elseif($source == "Header"){
            $columns = "campaign_option_spend";
        }elseif ($source == "Digital") {
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }

        return $columns;
    }

    public function defineTable($source){
        
        if($source == "IBMS"){
            $table = "ytd";
        }elseif($source == "Digital"){
            $table = "digital";
        }else{
            $table = false;
        }

        return $table;
    }

    public function generateValue($con,$sql,$sourceBrand,$region,$year,$brand,$salesRep,$month,$sum,$table){
        for ($s=0; $s <sizeof($salesRep) ; $s++) {
            $where[$s] = $this->createWhere($sql,$sourceBrand,$region,$year,$brand[0],$salesRep[$s],$month);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
        }
        return $values;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month){
        
        if ($source == "IBMS") {
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "Digital"){
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif($source == "Plan"){
            $columns = array("region_id","year","month","sales_rep_id","brand_id");
            $arrayWhere = array($region,$year,$month,$salesRep,$brand);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }
    
        return $where;
    }

}
