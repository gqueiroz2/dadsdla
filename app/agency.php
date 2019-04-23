<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\origin;
use App\region;
use App\sql;

class agency extends Management{
    public function getAgencyGroupID($con,$sql,$group,$region){
        $table = "agency_group";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = '$group' AND region_id = '$region'";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $agencyGroupID = $sql->fetch($res,$from,$to);
        return $agencyGroupID[0]['id'];
    }

    public function getAgencyID($con,$sql,$parent){
        $table = "agency";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = '$parent'";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $agencyGroupID = $sql->fetch($res,$from,$to);
        return $agencyGroupID[0]['id'];
    }

    public function handlerGroup($con,$spreadSheet){
        $sql = new sql();
        $r = new region();
        $region = $r->getRegion($con,false);
        $sheet = $this->removeDuplicates($spreadSheet,array('region','group','type'));
        for ($s=0; $s < sizeof($sheet); $s++) { 
            if($sheet[$s]['type'] == 'Agency'){
                $bool[$s] = $this->insertFromExcelGroup($con,$sql,$sheet[$s],$region);           
            }
        }
    }

    public function insertFromExcelGroup($con,$sql,$sheet,$region){
        for ($r=0; $r < sizeof($region); $r++) { 
            if($sheet['region'] == $region[$r]['name']){
                $regionID = $region[$r]['id'];
            }
        }
        $table ='agency_group';
        $columns = 'region_id,name';
        $values = "'$regionID','".$sheet['group']."'";
        $bool = $sql->insert($con,$table,$columns,$values);
    }

    public function handler($con,$spreadSheet){
        $sql = new sql();
        $r = new region();
        $region = $r->getRegion($con,false);
        
        $sheet = $this->removeDuplicates($spreadSheet,array('region','group','parent','type'));
        
        for ($s=0; $s < sizeof($sheet); $s++) {
            if($sheet[$s]['type'] == 'Agency'){ 
                $bool[$s] = $this->insertFromExcel($con,$sql,$sheet[$s],$region);
            }
        }
    }

    public function insertFromExcel($con,$sql,$sheet,$region){
        for ($r=0; $r < sizeof($region); $r++) { 
            if($sheet['region'] == $region[$r]['name']){
                $regionID = $region[$r]['id'];
            }
        }

        $agencyGroupID = $this->getAgencyGroupID($con,$sql,$sheet['group'],$regionID);
        
        $table = 'agency';
        $columns = 'agency_group_id,name';
        $values = "'$agencyGroupID','".$sheet['parent']."'";

        $bool = $sql->insert($con,$table,$columns,$values);
    }

    public function handlerUnit($con,$spreadSheet){
        $sql = new sql();
        $o = new origin();
        $origins = $o->getOrigin($con,false);
        
        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $this->insertFromExcelUnit($con,$sql,$spreadSheet[$s],$origins);
        }

    }


    public function insertFromExcelUnit($con,$sql,$sheet,$origins){
        $agencyID = $this->getAgencyID($con,$sql,$sheet['parent']);

        for ($or=0; $or < sizeof($origins); $or++) { 
            if($sheet['source'] == $origins[$or]['name']){
                $originID = $origins[$or]['id'];
            }
        }

        $table = 'agency_unit';
        $columns = 'agency_id,origin_id,name,status';
        $values = "'".$agencyID."','".$originID."','".$sheet['child']."','1' ";

        $bool = $sql->insert($con,$table,$columns,$values);

        return (" FIM AGÊNCIA  !!! ");
    }


}
