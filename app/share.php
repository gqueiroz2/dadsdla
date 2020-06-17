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

class share extends results{

    public function generateShare($con){

        $b = new brand();
        $r = new region();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();
        $pr = new pRate();

    	//Começando a pegar as informações necessarias
    	$region = Request::get('region');
    	$year = array(Request::get('year'));
    	$brand = $base->handleBrand(Request::get('brand'));
    	$source = Request::get('source');

    	$salesRepGroup = Request::get('salesRepGroup');
    	$salesRep = Request::get('salesRep');
        $currency = Request::get('currency');
        $value = Request::get('value');
        $month = Request::get('month');

        if( strtolower( $source ) == "cmaps" ){
            $div = $base->generateDivCMAPS($con,$pr,$region,$year,$currency);
            $div = 1*$div;
        }else{
            $div = $base->generateDiv($con,$pr,$region,$year,$currency);            
            $div = 1/$div;
        }     


        $tmp = array($currency);
        
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        if ($value == "gross") {
            $valueView = "Gross";
        }else{
            $valueView = "Net";
        }

        $yearView = $year[0];

        $tmp = array($region);
        $regionView = $r->getRegion($con,$tmp)[0]["name"];

        $vlau = $pr->getCurrency($con);

        for ($p=0; $p <sizeof($vlau) ; $p++) {
            if ($regionView == $vlau[$p]["region"]) {
                $crack = $vlau[$p]["id"];
                break;
             } 
        }

        //se for todos os canais, ele já pesquisa todos os canais atuais
        
        $brandName = array();
        
        for ($b=0; $b <sizeof($brand) ; $b++) {
            array_push($brandName, $brand[$b][1]);
        }

        //definindo a source de cada canal, Digital, VIX e OTH são diferentes do normal
        $actualMonth = date("m");

        $yy = $year[0];

        for ($m=0; $m <sizeof($month) ; $m++) {
            for ($b=0; $b <sizeof($brand); $b++) {
                if ($m > $actualMonth-1 && ($source == "CMAPS")) {
                    if( ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") && $year < 2020 ) {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($region == "1") {
                        $sourceBrand[$m][$b] = "CMAPS";
                    }else{
    
                    }
                }else{
                    if( ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") && $year < 2020 ) {
                        $sourceBrand[$m][$b] = "Digital";
                    }elseif ($brand[$b][1] == "OTH") {
                        $sourceBrand[$m][$b] = "IBMS";
                    }/*elseif($brand[$b][1] == "FN" && $region == "1"){
                        $sourceBrand[$m][$b] = "IBMS";
                    }elseif ($brand[$b][1] == "FN" && $region != "1"){
                        $sourceBrand[$m][$b] = "IBMS";
                    }*/else{
                        $sourceBrand[$m][$b] = $source;
                    }
                 }
             }
         }

        
        $tmp = $base->getMonth();
        $monthName = array();
        for ($m=0; $m <sizeof($month) ; $m++) { 
            for ($t=0; $t <sizeof($tmp) ; $t++) { 
                if ($month[$m] == $tmp[$t][1]) {
                    array_push($monthName, $tmp[$t][0]);
                }
            }
        }
        
        //verificar Executivos, se todos os executivos são selecionados, pesquisa todos do salesGroup, se seleciona todos os SalesGroup, seleciona todos os executivos da regiao

        $salesRepGroupTmp = $sr->getSalesRepGroup($con,array($region));
        $tmp = $sr->getSalesRep($con,$salesRepGroup);
        $salesRepTmp = $sr->getSalesRepStatus($con,$tmp,$year[0]);

        //MEXER AINDA PELA VIEW DOS SALES REPS
        $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);


        if(sizeof($salesGroup) == sizeof($salesRepGroupTmp)){
            $salesRepGroupView = "All";
        }else{
            $salesRepGroupView = "";
            for ($sg=0; $sg < sizeof($salesGroup); $sg++) { 
                if ($sg == 0) {
                    $salesRepGroupView .= $salesGroup[$sg]["name"];
                }else{
                    $salesRepGroupView .= ", ".$salesGroup[$sg]["name"];
                }
            }    
        }


        $salesRepName = array();
        $salesRepAB = array();
        if (sizeof($salesRepTmp) == sizeof($salesRep)) {
            $salesRepView = "All";
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                array_push($salesRepName, $salesRep[$s]["salesRep"]);
                array_push($salesRepAB, $salesRep[$s]["abName"]);
            }
        }else{
            $salesRepView = "";
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                array_push($salesRepName, $salesRep[$s]["salesRep"]);
                array_push($salesRepAB, $salesRep[$s]["abName"]);
                if ($s == 0) {
                    $salesRepView .= $salesRep[$s]["salesRep"];
                }else{
                    $salesRepView .= ", ".$salesRep[$s]["salesRep"];
                }
            }
        }

        for ($m=0; $m < sizeof($sourceBrand); $m++) { 
            for ($b=0; $b < sizeof($sourceBrand[$m]); $b++) { 
                //procura tabela para fazer a consulta (digital e OTH são em tabelas diferentes)
                $table[$m][$b] = $this->defineTable($sourceBrand[$m][$b]);
                //gera as colunas para o Where
                $sum[$m][$b] = $this->generateColumns($sourceBrand[$m][$b],$value);
            }
        }


        //$where = $this->createWhere($sql,$source,$region,$year,$brand,$salesRep,$month);
        for ($m=0; $m < sizeof($sourceBrand); $m++) { 
            for ($b=0; $b < sizeof($sourceBrand[$m]); $b++) {
                $values[$m][$b] = 0;
            }
        }


        //gera o where, puxa do banco, gera o total por executivo, e gera DN se tiver mais de um canal
        
        for ($m=0; $m <sizeof($sourceBrand) ; $m++) {
            for ($b=0; $b < sizeof($sourceBrand[$m]); $b++) { 
                $values[$m][$b] = $this->generateValue($con,$sql,$sourceBrand[$m][$b],$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$m][$b],$table[$m][$b],$crack);
            }
        }

        $mtx = $this->assembler($brandName,$salesRepName,$values,$div,$currency,$valueView,$salesRepGroupView,$salesRepView,$regionView,$yearView,$source,$salesRepAB);

        return $mtx;
    }

    public function generateValue($con,$sql,$sourceBrand,$region,$year,$brand,$salesRep,$month,$sum,$table,$currency){
        for ($s=0; $s <sizeof($salesRep) ; $s++) {
            $where[$s] = $this->createWhere($sql,$sourceBrand,$region,$year,$brand[0],$salesRep[$s],$month,$currency);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; //Ele sempre retorna um array de um lado "sum", então coloquei uma atribuição ["sum"] para tirar do array
        }
        return $values;
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
                $columns = "gross_revenue_prate";
            }else{
                $columns = "net_revenue_prate";
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
            $table = "fw_digital";
        }

        return $table;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month,$currency){
        if ($source == "CMAPS") {
            $columns = array("year","brand_id","sales_rep_id","month");
            $arrayWhere = array($year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "IBMS") { // BTS WORKS THROUGHT HERE
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "Header") {
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "Digital"){
            $columns = array("region_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
            if ($brand == '9') {
                $where = "WHERE (region_id = \"$region\") AND (year = \"$year[0]\") AND (brand_id != \"10\") AND (month = \"$month\") AND (sales_rep_id = \"".$salesRep['id']."\")";
            }
        }else{
            $where = false;
        }
    
        return $where;
    }

    public function assembler($brand,$salesRep,$values,$div,$currency,$value,$salesRepGroup,$salesRepView,$region,$year,$source,$salesRepAB){

        $base = new base();

        $mtx["value"] = $value;
        $mtx["currency"] = $currency;
        $mtx["salesRepGroup"] = $salesRepGroup;
        $mtx["salesRepView"] = $salesRepView;
        $mtx["region"] = $region;
        $mtx["year"] = $year;

        if($source == "CMAPS"){
            $newSource = "CMAPS";
        }else{
            $newSource = "BKGS";
        }


        $mtx["source"] = $newSource;
        $mtx["salesRepAB"] = $salesRepAB;

        for ($m=0; $m <sizeof($values) ; $m++) { 
            for ($b=0; $b <sizeof($values[$m]) ; $b++) { 
                for ($s=0; $s <sizeof($values[$m][$b]) ; $s++) {                     
                    $values[$m][$b][$s] = $values[$m][$b][$s]/$div;
                }
            }
        }

        $tmp = array();
        for ($b=0; $b <sizeof($brand) ; $b++) { 
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                $tmp[$b][$s] = 0;
            }
        }
        for ($m=0; $m <sizeof($values) ; $m++) { 
            for ($b=0; $b <sizeof($brand) ; $b++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    $tmp[$b][$s] += $values[$m][$b][$s];
                }
            }
        }

        $brandColor = array();
        $brandTextColor = array();

        $mtx["brand"] = $brand;
        $mtx["salesRep"] = $salesRep;
        $mtx["values"] = $tmp;

        for ($b=0; $b <sizeof($brand) ; $b++) { 
            $brandColor[$b] = $base->getBrandColor($brand[$b]);
            $brandTextColor[$b] = $base->getBrandTextColor($brand[$b]);
        }

        $mtx["brandColor"] = $brandColor;    
        $mtx["brandTextColor"] = $brandTextColor;

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
                $total[$b] += $mtx["values"][$b][$s];
                $dn[$s] += $mtx["values"][$b][$s];
                $totalT += $mtx["values"][$b][$s];
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


        //remove linha zerada 
        /*
        $check = false;
        for ($d=0; $d <sizeof($mtx['dn']) ; $d++) { 
            if ($mtx['dn'][$d] == 0) {
                unset($mtx['salesRep'][$d]);
                for ($v=0; $v <sizeof($mtx['values']) ; $v++) { 
                    unset($mtx['values'][$v][$d]);
                }
                unset($mtx['dn'][$d]);
                unset($mtx['share'][$d]);
                $check = true;
            }
        }

        if ($check) {
            $mtx['salesRep'] = array_values($mtx['salesRep']);
            for ($v=0; $v <sizeof($mtx['values']) ; $v++) { 
                $mtx['values'][$v] = array_values($mtx['values'][$v]);
            }
            $mtx['dn'] = array_values($mtx['dn']);
            $mtx['share'] = array_values($mtx['share']);

        }*/



        return $mtx;
    }




    //antiga função pra pegar o header, deixei aq por recordação
    /*public function Header($con,$sql,$salesRep,$region,$year,$month,$brand,$table,$sourceBrand,$sum){
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
    }*/


}
