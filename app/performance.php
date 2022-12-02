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
        }else if($value && $source == "crm"){
            $columns = $value."_revenue";;
        }else if ($value && $source == "cmaps") {
            $columns = $value;
        }else if($value){
            $columns = $value."_revenue";
        }else{
            $columns = false;
        }

        return $columns;
    }

    public function generateValueCmaps($con,$sql,$region,$year,$salesRep,$month,$sum,$table,$value=null,$type){

        $select = "SELECT SUM($value) AS sum 
                   FROM $table c
                   LEFT JOIN brand b ON b.ID = c.brand_id 
                   WHERE (year = \"".$year."\")
                   AND (month = \"".$month."\")
                   AND (b.brand_group_id  = \"".$type."\")
                   AND (sales_rep_id = \"".$salesRep[0]."\")
                   ORDER BY 1";
        //var_dump($select);
        $result = $con->query($select);
        $values = $sql->fetchSum($result,"sum")["sum"];
        //var_dump($values);

            
        return $values;
    }

    public function generateValueWB($con,$sql,$region,$year,$month,$sum,$table,$value=null){
        $where = $this->createWhere($sql,"ytdWB",$region,$year,false,false,$month,$value);
        $results = $sql->selectSum($con,$sum,"sum",$table,false,$where);
        $values = $sql->fetchSum($results,"sum")["sum"]; 
        
        return $values;
    }

    public function generateValueWithOutSalesRep($con,$sql,$region,$year,$brand,$month,$sum,$table,$value=null){
        
        $where = $this->createWhere($sql,$table,$region,$year,$brand[0], null, $month,$value);
        $results = $sql->selectSum($con,$sum,"sum",$table,false,$where);
        $values = $sql->fetchSum($results,"sum")["sum"]; 
        
        return $values;
    }   

     public function generateValueCompany($con,$sql,$region,$year,$brand,$month,$sum,$table,$value=null){
        
        $where = $this->createWhere($sql,"company",$region,$year,$brand['brandID'], null, $month,$value);
        $results = $sql->selectSum($con,$sum,"sum",$table,false,$where);
        $values = $sql->fetchSum($results,"sum")["sum"]; 
        
        return $values;
    }    

    public function generateValue($con,$sql,$region,$year,$brand,$salesRep,$month,$sum,$table,$value=null){

        if (is_array($salesRep)) {
            for ($s=0; $s < sizeof($salesRep); $s++) {
                $where[$s] = $this->createWhere($sql,$table,$region,$year,$brand[0],$salesRep[$s],$month,$value);
                $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
                $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; 
            }   
        }else{
            $values = null;
        }
        return $values;
    }

    public function generateValueS($con,$sql,$region,$year,$brand,$salesRep,$month,$sum,$table,$value=null){

        if (is_array($salesRep)) {
            for ($s=0; $s < sizeof($salesRep); $s++) {
                $where[$s] = $this->createWhere($sql,$table,$region,$year,$brand,$salesRep[$s],$month,$value);
                $results[$s] = $sql->selectSum($con,$sum,"sum",$table,false,$where[$s]);
                $values[$s] = $sql->fetchSum($results[$s],"sum")["sum"]; 
            }   
        }else{
            $values = null;
        }
        return $values;
    }

    public function createWhere($sql,$source,$region,$year,$brand,$salesRep,$month,$value=null){
        if ($source == "ytd") {
            $columns = array("sales_representant_office_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "aleph") {
            $columns = array("sales_office_id","year","brand_id","current_sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "ytdWB") {
            $columns = array("sales_representant_office_id","year","month");
            $arrayWhere = array($region,$year,$month);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "cmaps") {
            $columns = array("year","month","sales_rep_id","brand_id");
            $arrayWhere = array($year,$month,$salesRep,$brand);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "fw_digital"){
            $columns = array("region_id","year","brand_id","sales_rep_id","month");
            $arrayWhere = array($region,$year,$brand,$salesRep["id"],$month);
            $where = $sql->where($columns,$arrayWhere);
            if ($brand == '9') {
                $where = "WHERE (region_id = \"$region\") AND (year = \"$year\") AND (brand_id != \"10\") AND (month = \"$month\") AND (sales_rep_id = \"".$salesRep['id']."\")";
            }
        }elseif($source == "plan_by_sales"){

            if (is_null($salesRep)) {
                $columns = array("region_id","year","month","brand_id","currency_id","type_of_revenue");
                $arrayWhere = array($region,$year,$month,$brand,'4',$value);
                $where = $sql->where($columns,$arrayWhere);                
            }else{
                $columns = array("region_id","year","month","sales_rep_id","brand_id","currency_id","type_of_revenue");
                $arrayWhere = array($region,$year,$month,$salesRep["id"],$brand,'4',$value);
                $where = $sql->where($columns,$arrayWhere);
            }
        }elseif ($source == "company") {
            $columns = array("sales_representant_office_id","year","brand_id","month");
            $arrayWhere = array($region,$year,$brand,$month);
            if($brand == 9){
                $where = $sql->whereONLAdjust($columns,$arrayWhere);
            }else{
                $where = $sql->where($columns,$arrayWhere);
            }
        }else{
            $where = false;
        }

        return $where;
    }
}
