<?php

namespace App;

use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class share extends results
{


    public function generateShare($con){

        $b = new brand();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();

    	//Começando a pegar as informações necessarias
    	$region = Request::get('region');
    	$year = Request::get('year');
    	$brand = Request::get('brand');
    	$font = Request::get('font');
    	$salesRepGroup = Request::get('salesRepGroup');
    	$salesRep = Request::get('salesRep');
        $currency = Request::get('currency');
        $value = Request::get('value');
        $month = Request::get('month');



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
        
        //adiciona DN se existir mais de um canal na pesquisa

        //definindo a fonte de cada canal, Digital, VIX e OTH são diferentes do normal
        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($t=0; $t <sizeof($tmp); $t++) { 
                if ($brand[$b] == $tmp[$t]["id"]) {
                    if ($tmp[$t]["name"] == "ONL" || $tmp[$t]["name"] == "VIX") {
                        $fontBrand[$b] = "Digital";
                    }elseif ($tmp[$t]["name"] == "OTH") {
                        $fontBrand[$b] = "IBMS";
                    }else{
                        $fontBrand[$b] = $font;
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
            for ($m=0; $m <sizeof($month) ; $m++) { 
                $tmp[$m] = $month[$m];
                $monthName[$m] = $month[$m];
                $month = $tmp;
            }
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


        for ($b=0; $b <sizeof($fontBrand) ; $b++) { 
            //procura tabela para fazer a consulta (digital e OTH são em tabelas diferentes)
            $table[$b] = $this->defineTable($fontBrand[$b]);
            //gera as colunas para o Where
            $sum[$b] = $this->generateColumns($fontBrand[$b],$value);
        }

        //$where = $this->createWhere($sql,$font,$region,$year,$brand,$salesRep,$month);
        for ($b=0; $b < sizeof($brand)+1; $b++) { 
            for ($s=0; $s <sizeof($salesRep)+1 ; $s++) {
                $values[$b][$s] = 0;
            }
        }


        //gera o where, puxa do banco, gera o total por executivo, e gera DN se tiver mais de um canal
        for ($b=0; $b < sizeof($brand); $b++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) {
                $where[$b][$s] = $this->createWhere($sql,$fontBrand[$b],$region,$year,$brand[$b],$salesRep[$s],$month);
                $results[$b][$s] = $sql->selectSum($con,$sum[$b],"sum",$table[$b],false,$where[$b][$s]);
                $values[$b][$s] = $sql->fetchSum($results[$b][$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
            }
        }


        $mtx = $this->assembler($brandName,$salesRepName,$values);

        return $mtx;
    }

    public function generateColumns($font,$value){
        if ($font == "CMAPS") {
            if ($value == "gross") {
                $columns = "gross";
            }else{
                $columns = "net";
            }
        }elseif($font == "IBMS"){
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }elseif($font == "Header"){
            $columns = "gross_revenue";
        }elseif ($font == "Digital") {
            if ($value == "gross") {
                $columns = "gross_revenue";
            }else{
                $columns = "net_revenue";
            }
        }

        return $columns;
    }

    public function defineTable($font){
        if ($font == "CMAPS") {
            $table = "cmaps";
        }elseif($font == "IBMS"){
            $table = "ytd";
        }elseif($font == "Header"){
            $table = "mini-header";
        }elseif($font == "Digital"){
            $table = "digital";
        }

        return $table;
    }

    public function createWhere($sql,$font,$region,$year,$brand,$salesRep,$month){
        if ($font == "CMAPS") {
            $columns = array("year","brand_id","sales_rep_id","month");
            $arrayWhere = array($year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($font == "IBMS") {
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($font == "Header") {
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($font == "Digital"){
            $columns = array("campaing_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep,$month);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }
    
        return $where;
    }

    public function assembler($brand,$salesRep,$values){

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
