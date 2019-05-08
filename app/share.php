<?php

namespace App;

use Validator;
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

        $tmp = array($currency);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];
        if ($value == "gross") {
            $valueView = "Gross";
        }else{
            $valueView = "Net";
        }


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
                    }elseif($tmp[$t]["name"] == "FN" && $region == "1"){
                        $sourceBrand[$b] = "CMAPS";
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
                
                $salesRepView = "All";

                $tmp = array($region);
            
                $salesRepGroup = $sr->getSalesRepGroup($con,$tmp);
            
                $tmp = array();
                
                for ($i=0; $i <sizeof($salesRepGroup) ; $i++) { 
                    array_push($tmp, $salesRepGroup[$i]["id"]);
                }

                $salesRepGroup = $tmp;
            
            }else{

                $salesRepGroup = array($salesRepGroup);

                $salesRepGroupView = $sr->getSalesRepGroupById($con,$salesRepGroup)["name"];

                                
            }

            $salesRepGroupView = "All";                
            
            $tmp = $sr->getSalesRep($con,$salesRepGroup);

            $salesRep = array();

            for ($i=0; $i <sizeof($tmp) ; $i++) { 
                array_push($salesRep, $tmp[$i]["id"]);
                array_push($salesRepName, $tmp[$i]["salesRep"]);
            }
        }else{
            
            $salesRep = array($salesRep);
            
            $salesRepGroup = array($salesRepGroup);

            $salesRepGroupView = $sr->getSalesRepGroupById($con,$salesRepGroup)["name"];
            
            $tmp = $sr->getSalesRep($con,null);
            


            for ($t=0; $t <sizeof($tmp) ; $t++) { 
                if(is_array($salesRep)){
                    for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                        if ($tmp[$t]["id"] == $salesRep[$s]) {
                            array_push($salesRepName, $tmp[$t]["salesRep"]);
                            $salesRepView = $tmp[$t]["salesRep"];
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
            if ($sourceBrand[$b] == "Header") {
                $values[$b] = $this->Header($con,$sql,$salesRep,$region,$year,$month,$brand[$b],$table[$b],$sourceBrand[$b],$sum[$b]);
            }else{
                $values[$b] = $this->CMAPS_IBMS($con,$sql,$sourceBrand[$b],$region,$year,$brand[$b],$salesRep,$month,$sum[$b],$table[$b]);       
            }
        }

        $mtx = $this->assembler($brandName,$salesRepName,$values,$div,$currency,$valueView,$salesRepGroupView);

        return $mtx;
    }

    public function Header($con,$sql,$salesRep,$region,$year,$month,$brand,$table,$sourceBrand,$sum){
        $col = "sales_rep_role, order_reference";
        
        $columnsWhere = array("campaign_sales_office_id","brand_id","month","year");

        $vars_Where = array($region,$brand,$month,$year);

        $where = $sql->where($columnsWhere,$vars_Where);

        $tmp = $sql->select($con,$col,$table,null,$where);

        $from = array("sales_rep_role","order_reference");


        $res = $sql->fetch($tmp,$from,$from);

        $orders = array();

        if ($res) {
            for ($r=0; $r <sizeof($res) ; $r++) { 
                if ($res[$r]["sales_rep_role"] == "Sales Representitive") {
                    array_push($orders, $res[$r]["order_reference"]);
                }
            }    
        }

        $orders = array_unique($orders);
            
        $values = array();

        $nOrders = "";


        if ($res) {
            for ($o=0; $o <sizeof($orders) ; $o++) { 
                if ($o == 0) {
                    $nOrders .= "'".$orders[$o]."'";
                }else{
                    $nOrders .= ",'".$orders[$o]."'";
                }
            }
        }else{
            $nOrders = "false";
        }


        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            $values[$s] = 0;

            $where = $this->createWhere($sql,$sourceBrand,$region,$year,$brand,$salesRep[$s],$month);

            $select[$s] = "SELECT SUM(IF($sum IN ($nOrders), $sum*1/2, $sum)) AS sum FROM mini_header $where";

            $resp[$s] = $con->query($select[$s]);

            $from = array("sum");

            $values[$s] = doubleval($sql->fetch($resp[$s],$from,$from)[0]["sum"]);


        }
        
        return $values;
    }


    public function CMAPS_IBMS($con,$sql,$sourceBrand,$region,$year,$brand,$salesRep,$month,$sum,$table){
        for ($s=0; $s <sizeof($salesRep) ; $s++) {
            $where[$s] = $this->createWhere($sql,$sourceBrand,$region,$year,$brand,$salesRep[$s],$month);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
        }
        return $values;
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
        }else{
            $where = false;
        }
    
        return $where;
    }

    public function assembler($brand,$salesRep,$values,$div,$currency,$value,$salesRepGroup){

        $base = new base();

        $mtx["value"] = $value;
        $mtx["currency"] = $currency;
        $mtx["salesRepGroup"] = $salesRepGroup;

        for ($b=0; $b <sizeof($values) ; $b++) { 
            for ($s=0; $s <sizeof($values[$b]) ; $s++) { 
                $values[$b][$s] = $values[$b][$s]/$div;
            }
        }

        $brandColor = array();

        $mtx["brand"] = $brand;
        $mtx["salesRep"] = $salesRep;
        $mtx["values"] = $values;

        for ($b=0; $b <sizeof($brand) ; $b++) { 
            $brandColor[$b] = $base->getBrandColor($brand[$b]);
        }

        $mtx["brandColor"] = $brandColor;        

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
        if (sizeof($brand)>1) {
            $mtx["dn"] = $dn;
        }else{
            $mtx["dn"] = false;
        }
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
