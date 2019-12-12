<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class  region extends Management{
    
    public function addRegion($con){
        $sql = new sql();

        $region = Request::get('region');
        $table = 'region';
        $columns = 'name';
        $values = "'$region'";
        $bool = $sql->insert($con,$table,$columns,$values);

        return $bool;
    }

    public function getIDRegion($con, $region = false){
        $sql = new sql();

        $table = "region";
        $columns = "region.ID AS id";

        $where = "";

        if ($region) {
            $regions = implode(",", $region);
            $where .= "WHERE region.name IN ('$regions')";
        }

        $res = $sql->select($con,$columns,$table,false,$where);

        $from = array('id');

        $region = $sql->fetch($res,$from,$from);

        return $region;
    }

    public function getRegionByName($con, $region){
        $sql = new sql();

        $table = "region r";
        $columns = "r.ID AS id,
                r.name AS name,
                r.role AS role";

        $where = "";

        if ($region) {
            $where .= "WHERE r.name = '$region'";
        }
        
        $res = $sql->select($con,$columns,$table,false,$where);

        $from = array('id','name','role');

        $region = $sql->fetch($res,$from,$from)[0];

        return $region;
    }

    public function getRegion ($con, $ID = false){
        $sql = new sql();

        $table = "region r";
        $columns = "r.ID AS id,
                r.name AS name,
                r.role AS role";

        $where = "";
    	if ($ID) {
    		$ids = implode(",", $ID);
    		$where .= "WHERE r.ID IN ($ids)";
    	}

        $res = $sql->select($con,$columns,$table, null, $where);

        $from = array('id','name',"role");

        $region = $sql->fetch($res,$from,$from);

        return $region;
    }

    public function editRegion($con){
        $sql = new sql();
        $size = intval(Request::get("size"));
        $table = "region";
        $columns = array("name");

        for ($i=0; $i <$size ; $i++){ 
            $old[$i] = Request::get("Old-$i");
            $new[$i] = array(Request::get("New-$i"));

            $division[$i] = Request::get("New-$i");

            $set[$i] = $sql->setUpdate($columns,$new[$i]);

            $where[$i] = "WHERE name = '$old[$i]'";
        }

        for ($i=0; $i <$size ; $i++) { 
            
            if ($division[$i] != null) {
                $bool = $sql->updateValues($con,$table,$set[$i],$where[$i]);
            }else{
                $bool = $this->deleteRegion($con,$table,$where[$i]);
            }

            if ($bool["bool"] == false) {
                break;
            }
        }

        return $bool;
    }

    public function deleteRegion($con,$table,$where){
        $sql = new sql();

        $bool = $sql->deleteValues($con,$table,$where);

        return $bool;
    }
}
