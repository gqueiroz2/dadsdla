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

    public function generateValueWB($con,$sql,$region,$year,$month,$sum,$table,$value=null){
        $where = $this->createWhere($sql,"ytdWB",$region,$year,false,false,$month,$value);
        $results = $sql->selectSum($con,$sum,"sum",$table,false,$where);
        $values = $sql->fetchSum($results,"sum")["sum"]; 
        
        return $values;
    }

    public function generateValue($con,$sql,$region,$year,$brand,$salesRep,$month,$sum,$table,$value=null){
        for ($s=0; $s <sizeof($salesRep); $s++) {
            $where[$s] = $this->createWhere($sql,$table,$region,$year,$brand[0],$salesRep[$s],$month,$value);
            $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
            $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; 
        }
        return $values;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month,$value=null){
        if ($source == "ytd") {
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "ytdWB") {
            $columns = array("sales_representant_office_id","year","month");
            $arrayWhere = array($region,$year,$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif ($source == "digital"){
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
        }elseif($source == "plan_by_sales"){
            $columns = array("region_id","year","month","sales_rep_id","brand_id","currency_id","type_of_revenue");
            $arrayWhere = array($region,$year,$month,$salesRep["id"],$brand,'4',$value);
            $where = $sql->where($columns,$arrayWhere);
        }else{
            $where = false;
        }

        return $where;
    }
}
