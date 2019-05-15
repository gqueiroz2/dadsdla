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
        for ($m=0; $m <sizeof($month) ; $m++) {
            for ($b=0; $b <sizeof($brand); $b++) {
                if ($m > $actualMonth-1) {
                    if($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($region == "1") {
                        $sourceBrand[$m][$b] = "CMAPS";
                    }else{
                        $sourceBrand[$m][$b] = "Header";
                    }
                }else{
                    if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($brand[$b][1] == "OTH") {
                        $sourceBrand[$m][$b] = "IBMS";
                    }elseif($brand[$b][1] == "FN" && $region == "1"){
                        $sourceBrand[$m][$b] = "CMAPS";
                    }else{
                        $sourceBrand[$m][$b] = $source;
                    }
                }
            }
        }

		//olha quais nucleos serão selecionados        
        $salesRepName = array();
        if ($salesRepGroup == 'all') {
                
            $tmp = array($region);
        
            $salesRepGroup = $sr->getSalesRepGroup($con,$tmp);
        
            $tmp = array();
            
            for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
                array_push($tmp, $salesRepGroup[$i]["id"]);
            }

            $salesRepGroup = $tmp;
        
            $salesRepGroupView = "All";   
        }else{

            $salesRepGroup = array($salesRepGroup);

            $salesRepGroupView = $sr->getSalesRepGroupById($con,$salesRepGroup)["name"];

        }


        //pega informações dos representantes do nucleo(s)
        $tmp = $sr->getSalesRepFilteredYear($con,$salesRepGroup,$region,$year,$source);
        $salesRep = array();
        $salesRepView = "All";
        for ($i=0; $i <sizeof($tmp) ; $i++) { 
            array_push($salesRep, $tmp[$i]["id"]);
            array_push($salesRepName, $tmp[$i]["salesRep"]);
        }


        for ($m=0; $m <sizeof($sourceBrand); $m++) { 
            for ($b=0; $b <sizeof($sourceBrand[$m]) ; $b++) { 
                //procura tabela para fazer a consulta (digital e OTH são em tabelas diferentes)
                $table[$m][$b] = $this->defineTable($sourceBrand[$m][$b]);
                //gera as colunas para o Where
                $sum[$m][$b] = $this->generateColumns($sourceBrand[$m][$b],$value);
            }
        }

        for ($m=0; $m < sizeof($sourceBrand); $m++) { 
            for ($b=0; $b <sizeof($sourceBrand[$m]) ; $b++) {
                $values[$m][$b] = 0;
            }
        }

        for ($m=0; $m <sizeof($sourceBrand) ; $m++) {
            for ($b=0; $b < sizeof($sourceBrand[$m]); $b++) { 
                $values[$m][$b] = $this->generateValue($con,$sql,$sourceBrand[$m][$b],$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$m][$b],$table[$m][$b]);
                $planValues[$m][$b] = $this->generateValue($con,$sql,"Plan",$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales");
            }
        }

        $mtx = $this->assembler($values,$planValues);

    }

    public function assembler($values,$planValues){

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
        $table = false;
        if ($source == "CMAPS") {
            $table = "cmaps";
        }elseif($source == "IBMS"){
            $table = "ytd";
        }elseif($source == "Header"){
            $table = "mini_header";
        }elseif($source == "Digital"){
            $table = "digital";
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
        if ($source == "CMAPS") {
            $columns = array("year","brand_id","sales_rep_id","month");
            $arrayWhere = array($year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "IBMS") {
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "Header") {
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
