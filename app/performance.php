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

class performance extends base{
    
    public function generateColumns($value,$source){

        if($value && $source == "ytd"){
            $columns = $value."_revenue_prate";
        }elseif($value){
            $columns = $value."_revenue";
        }else{
            $columns = false;
        }

        return $columns;
    }

    public function generateValue($con,$sql,$region,$year,$brand,$salesRep,$month,$sum,$table,$currency=null,$value=null){
        for ($s=0; $s <sizeof($salesRep) ; $s++) {
            $where[$s] = $this->createWhere($sql,$table,$region,$year,$brand[0],$salesRep[$s],$month,$currency,$value);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; 
        }
        return $values;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month,$currency=null,$value=null){
        if ($source == "ytd") {
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "digital"){
            $columns = array("campaign_sales_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif($source == "plan_by_sales"){
            $columns = array("region_id","year","month","sales_rep_id","brand_id","currency_id","type_of_revenue");
            $arrayWhere = array($region,$year,$month,$salesRep["id"],$brand,$currency,$value);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }

        return $where;
    }
}
