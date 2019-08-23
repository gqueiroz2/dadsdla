<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\origin;
use App\region;
use App\sql;

class client extends Management{
    public function getClientGroupID($con,$sql,$group,$region){
        $table = "client_group";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = '$group' AND region_id = '$region'";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $clientGroupID = $sql->fetch($res,$from,$to);
        return $clientGroupID[0]['id'];
    }

    public function getClientIDbyClientUnit($con,$sql,$parent,$region,$regionName){
        

        $table = "client c";
        $columns = "c.ID";
        $join = "LEFT JOIN client_unit cu ON cu.client_id = c.ID
                 LEFT JOIN client_group cg ON c.client_group_id = cg.ID
                 LEFT JOIN region r ON r.ID = cg.region_id
        ";
        //$where = "WHERE cu.name = \"".addslashes($parent)."\"";


        $where = "WHERE ( cu.name = \"".addslashes($parent)."\" ) AND (r.name = \"".$regionName."\") " ;
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $clientGroupID = $sql->fetch($res,$from,$to);

        if(!$clientGroupID){
            var_dump(" INICIO CLIENT");
            var_dump($regionName);
            var_dump($parent);
            var_dump( $clientGroupID );
            var_dump("FIM CLIENT");
            //$res = $sql->larica($con,$columns,$table,$join,$where,1,$limit);

        }

        return $clientGroupID[0]['id'];        
    }

    public function getClientID($con,$sql,$parent){
        $table = "client";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = '$parent'";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $clientGroupID = $sql->fetch($res,$from,$to);
        return $clientGroupID[0]['id'];
    }

    public function getClientIDByRegion($con,$sql,$parent, $clientRegion){
        
        $table = "client c";
        
        $columns = "c.ID AS 'id'";
        
        $where = "WHERE c.name = '$parent' ";

        if($clientRegion){
            $clientRegions = implode(",", $clientRegion);
            $where .= "AND region_id IN ('$clientRegions')";
        }
        
        $join = "LEFT JOIN client_group cg ON cg.ID = c.client_group_id 
                 LEFT JOIN region r ON r.ID = cg.region_id";
                 
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("id");
        $to = array('id');
        $clientGroupID = $sql->fetch($res,$from,$to);
        return $clientGroupID[0]['id'];
    }

    public function handlerGroup($con,$spreadSheet){
        $sql = new sql();
        $r = new region();
        $region = $r->getRegion($con,false);
        $sheet = $this->removeDuplicates($spreadSheet,array('region','group','type'));
        
        
        for ($s=0; $s < sizeof($sheet); $s++) { 
            if($sheet[$s]['type'] == 'Client'){  
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
        $table ='client_group';
        $columns = 'region_id,name';
        $values = " \" ".$regionID."  \"  ,   \" ".$sheet['group']." \"  ";
        $bool = $sql->insert($con,$table,$columns,$values);
        var_dump($bool['bool']);
        echo $bool['msg']."<br>";
    }

    public function handler($con,$spreadSheet){
        $sql = new sql();
        $r = new region();
        $region = $r->getRegion($con,false);
        $sheet = $this->removeDuplicates($spreadSheet,array('region','group','parent','type'));
        for ($s=0; $s < sizeof($sheet); $s++) {
            if($sheet[$s]['type'] == 'Client'){ 
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
        $clientGroupID = $this->getClientGroupID($con,$sql,$sheet['group'],$regionID);
        $table = 'client';
        $columns = 'client_group_id,name';
        $values = " \" ".$clientGroupID."  \"  ,  \"  ".$sheet['parent']."  \"   ";
        $bool = $sql->insert($con,$table,$columns,$values);
        var_dump($bool['bool']);
        echo $bool['msg']."<br>";
    }

    public function handlerUnit($con,$spreadSheet){
        $sql = new sql();
        $o = new origin();
        $origins = $o->getOrigin($con,false);
        for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            if($sheet[$s]['type'] == 'Client'){  
                $this->insertFromExcelUnit($con,$sql,$spreadSheet[$s],$origins);
            }
        }
    }


    public function insertFromExcelUnit($con,$sql,$sheet,$origins){
        $clientID = $this->getClientID($con,$sql,$sheet['parent']);
        for ($or=0; $or < sizeof($origins); $or++) { 
            if($sheet['source'] == $origins[$or]['name']){
                $originID = $origins[$or]['id'];
            }
        }
        $table = 'client_unit';
        $columns = 'client_id,origin_id,name';
        $values = "   \" ".$clientID."  \"  ,   \" ".$originID."  \" ,  \" ".$sheet['child']."  \" ";
        $bool = $sql->insert($con,$table,$columns,$values);
        var_dump($bool['bool']);
        echo $bool['msg']."<br>";
    }

    public function getClientGroup($con,$clientID = false){
        $sql = new sql();

        $table = "client_group cg";
        $columns = "cg.ID AS 'id',                    
                    cg.name AS 'clientGroup',
                    r.name AS 'region'
                   ";

        $where = "";

        if($clientID){
            $clientIDS = implode(",",$clientID);
            $where .= "WHERE cg.ID IN ('$clientIDS')";
        }

        $join = "LEFT JOIN region r ON r.ID = cg.region_id
        ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('id','clientGroup','region');

        $client = $sql->fetch($res,$from,$from);

        return $client;
    }

    public function getAllClientsByName($con,$sql,$parent){
        $table = "client";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = \"".addslashes($parent)."\"";
        $res = $sql->select($con,$columns,$table,$join,$where,1);
        $from = array("ID");
        $to = array('id');
        $clientGroupID = $sql->fetch($res,$from,$to);
        return $clientGroupID;
    }

    public function getClient($con,$clientID = false){
        $sql = new sql();

        $table = "client c";
        $columns = "c.name AS 'client',
                    c.ID AS 'id',
                    cg.ID AS 'clientGroupID',
                    cg.name AS 'clientGroup',
                    r.name AS 'region'
                   ";

        $where = "";

        if($clientID){
            $clientIDS = implode(",",$clientID);
            $where .= "WHERE c.ID IN ('$clientIDS')";
        }

        $join = "LEFT JOIN client_group cg ON cg.ID = c.client_group_id
                 LEFT JOIN region r ON r.ID = cg.region_id
        ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('id','client','clientGroupID','clientGroup','region');

        $client = $sql->fetch($res,$from,$from);

        return $client;
    }

    public function getClientByRegion($con,$clientRegion=false,$year=false){
     
        $sql = new sql();

        $table = "ytd y";

        $columns = "c.name AS 'client',
                    c.ID AS 'id',
                    cg.ID AS 'clientGroupID',
                    cg.name AS 'clientGroup',
                    r.name AS 'region'
                   ";

        $where = "";

        if($clientRegion){
            $clientRegions = implode(",",$clientRegion);
            $where .= "WHERE sales_representant_office_id IN ('$clientRegions')";

            if ($year) {
                $years = implode(",", $year);
                $where .= " AND year IN ('$years')";
            }
        }



        $join = "LEFT JOIN client c ON c.ID = y.client_id
                 LEFT JOIN client_group cg ON cg.ID = c.client_group_id
                 LEFT JOIN region r ON cg.region_id = r.ID
                ";

        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "c.name", "c.id");

        $from = array('id','client','clientGroupID','clientGroup','region');

        $client = $sql->fetch($res,$from,$from);

        return $client;
    }

    public function getClientUnit($con,$clientID=false){

        $sql = new sql();

        $table = "client_unit cu";
        $columns = "cu.name AS 'clientUnit',
                    cu.ID AS 'id',
                    o.name AS 'origin',
                    c.name AS 'client',
                    c.ID AS 'clientID',
                    cg.ID AS 'clientGroupID',
                    cg.name AS 'clientGroup',
                    r.name AS 'region'
                   ";

        $where = "";

        if($clientID){
            $clientIDS = implode(",",$clientID);
            $where .= "WHERE cu.ID IN ('$clientIDS')";
        }

        $join = "LEFT JOIN client c ON c.ID = cu.client_id
                 LEFT JOIN client_group cg ON cg.ID = c.client_group_id
                 LEFT JOIN region r ON r.ID = cg.region_id
                 LEFT JOIN origin o ON o.ID = cu.origin_id
        ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('clientUnit','id','origin','client','clientID','clientGroupID','clientGroup','region');

        $clientUnit = $sql->fetch($res,$from,$from);

        return $clientUnit;


    }


    public function checkClientUnit($con,$clientSearch){
        $sql = new sql();

        $select = "SELECT id,name FROM client_unit WHERE ( name = '$clientSearch')";

        $res = $con->query($select);

        $from = array("id","name");

        $clientBack = $sql->fetch($res,$from,$from);

        if($clientBack){
            return true;
        }else{
            return false;
        }
    }

}
