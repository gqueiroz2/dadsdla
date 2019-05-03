<?php

namespace App;

use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class share extends results
{


    public function generateShare($con){

        $b = new brand();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();
        $pr = new pRate();

    	//Começando a pegar as informações necessarias
    	$region = Request::get('region');
    	$year = array(Request::get('year'));
    	$brand = Request::get('brand');
    	$source = Request::get('source');
    	$salesRepGroup = Request::get('salesRepGroup');
    	$salesRep = Request::get('salesRep');
        $currency = Request::get('currency');
        $value = Request::get('value');
        $month = Request::get('month');





        $div = $base->generateDiv($con,$pr,$region,$year,$currency);

        //se for todos os canais, ele já pesquisa todos os canais atuais
        $tmp = $b->getBrand($con);
        if($brand[0] == 'dn') {
            for ($b=0; $b <sizeof($tmp) ; $b++) { 
                $brand[$b] = $tmp[$b]["id"];
            }
        }

        $brandName = array();
        for ($t=0; $t <sizeof($tmp) ; $t++) {
            for ($b=0; $b <sizeof($brand) ; $b++) { 
                if ($brand[$b] == $tmp[$t]["id"]) {
                    array_push($brandName, $tmp[$t]["name"]);
                }
            }
        }

        //definindo a source de cada canal, Digital, VIX e OTH são diferentes do normal
        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($t=0; $t <sizeof($tmp); $t++) { 
                if ($brand[$b] == $tmp[$t]["id"]) {
                    if ($tmp[$t]["name"] == "ONL" || $tmp[$t]["name"] == "VIX") {
                        $sourceBrand[$b] = "Digital";
                    }elseif ($tmp[$t]["name"] == "OTH") {
                        $sourceBrand[$b] = "IBMS";
                    }else{
                        $sourceBrand[$b] = $source;
                    }
                }
            }
        }


        //se for todos os meses já pega todos os meses, e se for YTD ele pega todos os meses, até o mes atual
        if($month[0] == 'all'){
            $month = $base->getMonth();
            $tmp = array();
            $monthName = array();
            for ($m=0; $m <sizeof($month) ; $m++) { 
                $tmp[$m] = $month[$m][1];
                $monthName[$m] = $month[$m][0];
            }
            $month = $tmp;
        }elseif($month[0] == 'ytd'){
            $month = $base->getYtdMonth();
            $tmp = array();
            for ($m=0; $m <sizeof($month) ; $m++) { 
                $tmp[$m] = $month[$m][1];
                $monthName[$m] = $month[$m];
            }
            $month = $tmp;

        }else{
            $tmp = $base->getMonth();
            $monthName = array();
            for ($m=0; $m <sizeof($month) ; $m++) { 
                for ($t=0; $t <sizeof($tmp) ; $t++) { 
                    if ($month[$m] == $tmp[$t][1]) {
                        array_push($monthName, $tmp[$t][0]);
                    }
                }
            }
        }

        //verificar Executivos, se todos os executivos são selecionados, pesquisa todos do salesGroup, se seleciona todos os SalesGroup, seleciona todos os executivos da regiao
        $salesRepName = array();

        if ($salesRep == 'all') {
            
            if ($salesRepGroup == 'all') {
                $tmp = array($region);
                $salesRepGroup = $sr->getSalesRepGroup($con,$tmp);
                $tmp = array();
                for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
                    array_push($tmp, $salesRepGroup[$i]["id"]);
                }

                $salesRepGroup = $tmp;
            }else{
                $salesRepGroup = array($salesRepGroup);
            }
            $tmp = $sr->getSalesRep($con,$salesRepGroup);

            $salesRep = array();

            for ($i=0; $i <sizeof($tmp) ; $i++) { 
                array_push($salesRep, $tmp[$i]["id"]);
                array_push($salesRepName, $tmp[$i]["salesRep"]);
            }
        }else{
            $salesRep = array($salesRep);
            $tmp = $sr->getSalesRep($con,null);
            for ($t=0; $t <sizeof($tmp) ; $t++) { 
                if(is_array($salesRep)){
                    for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                        if ($tmp[$t]["id"] == $salesRep[$s]) {
                            array_push($salesRepName, $tmp[$t]["salesRep"]);
                        }
                    }
                }
            }
        }


        for ($b=0; $b <sizeof($sourceBrand) ; $b++) { 
            //procura tabela para fazer a consulta (digital e OTH são em tabelas diferentes)
            $table[$b] = $this->defineTable($sourceBrand[$b]);
            //gera as colunas para o Where
            $sum[$b] = $this->generateColumns($sourceBrand[$b],$value);
        }

        //$where = $this->createWhere($sql,$source,$region,$year,$brand,$salesRep,$month);
        for ($b=0; $b < sizeof($brand)+1; $b++) { 
            for ($s=0; $s <sizeof($salesRep)+1 ; $s++) {
                $values[$b][$s] = 0;
            }
        }


        //gera o where, puxa do banco, gera o total por executivo, e gera DN se tiver mais de um canal
        for ($b=0; $b < sizeof($brand); $b++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) {
                $where[$b][$s] = $this->createWhere($sql,$sourceBrand[$b],$region,$year,$brand[$b],$salesRep[$s],$month);
                $results[$b][$s] = $sql->selectSum($con,$sum[$b],"sum",$table[$b],false,$where[$b][$s]);
                $values[$b][$s] = $sql->fetchSum($results[$b][$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
            }
        }

        $mtx = $this->assembler($brandName,$salesRepName,$values,$div);

        return $mtx;
    }

    public function generateColumns($source,$value){
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
            $columns = "gross_revenue";
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
        if ($source == "CMAPS") {
            $table = "cmaps";
        }elseif($source == "IBMS"){
            $table = "ytd";
        }elseif($source == "Header"){
            $table = "mini-header";
        }elseif($source == "Digital"){
            $table = "digital";
        }

        return $table;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month){
        if ($source == "CMAPS") {
            $columns = array("year","brand_id","sales_rep_id","month");
            $arrayWhere = array($year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "IBMS") {
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "Header") {
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "Digital"){
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }
    
        return $where;
    }

    public function assembler($brand,$salesRep,$values,$div){

        var_dump($values);

        for ($b=0; $b <sizeof($values) ; $b++) { 
            for ($s=0; $s <sizeof($values[$b]) ; $s++) { 
                $values[$b][$s] = $values[$b][$s]/$div;
            }
        }

        $mtx["brand"] = $brand;
        $mtx["salesRep"] = $salesRep;
        $mtx["values"] = $values;

        $dn = array();
        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            $dn[$s] = 0;
        }

        $total = array();
        for ($b=0; $b <sizeof($brand) ; $b++) { 
            $total[$b] = 0;
        }

        $totalT = 0;

        for ($b=0; $b <sizeof($brand) ; $b++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                $total[$b] += $values[$b][$s];
                $dn[$s] += $values[$b][$s];
                $totalT += $values[$b][$s];
            }
        }

        $mtx["total"] = $total;
        $mtx["dn"] = $dn;
        $mtx["totalT"] = $totalT;

        $share = array();

        for ($d=0; $d <sizeof($dn) ; $d++) { 
            if ($totalT != 0) {
                $share[$d] = ($dn[$d]/$totalT)*100;
            }else{
                $share[$d] = 0;
            }
        }

        $mtx["share"] = $share;

        return $mtx;
    }

}
