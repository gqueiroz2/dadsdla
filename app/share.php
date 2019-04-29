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

        //definindo onde tem que pegar cada informação ONL e VIX sempre é digital, OTH sempre IBMS

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
            $month = $base->getMonthNumber();
        }elseif($month[0] == 'ytd'){
            $month = $base->getYtdMonthNumber();
        }

        //verificar pessoas
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
            }
        }
        for ($b=0; $b <sizeof($fontBrand) ; $b++) { 
            $table[$b] = $this->defineTable($fontBrand[$b]);
        }

        //$where = $this->createWhere($sql,$font,$region,$year,$brand,$salesRep,$month);
        
        for ($b=0; $b < sizeof($brand); $b++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) {
                $where[$b][$s] = $this->createWhere($sql,$fontBrand[$b],$region,$year,$brand[$b],$salesRep[$s],$month);
            }
        }


        $columns = $this->generateColumns($font,$value);


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
        }
    
        return $where;
    }


}
