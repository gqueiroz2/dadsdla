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
