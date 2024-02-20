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


    public function agencyByClientAndRegion($con,$sql,$region,$year,$client){

        $table = 'wbd c';

        $columns = "cl.name AS 'client',
                    cl.ID AS 'clientID',
                    a.ID AS 'agencyID',
                    a.name AS 'agency'
                   ";

        $where = "WHERE (c.client_id IN ($client)) AND (year = '$year')";


        $join = "LEFT JOIN client cl ON cl.ID = c.client_id
                 LEFT JOIN agency a ON a.ID = c.agency_id                 
                ";

        $res = $sql->selectGroupByDistinct($con,$columns,$table,$join,$where, "cl.name");

        $from = array('clientID','client','agencyID','agency');

        $client = $sql->fetch($res,$from,$from);

        return $client;
    }

    public function getAgencyGroupByID($con,$group,$region){
        $sql = new sql();
        
        $table = "agency_group";
        $columns = "name";
        $join = false;
        $where = "WHERE id = '$group' AND region_id = '$region'";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("name");
        $to = array('name');
        $agencyGroupID = $sql->fetch($res,$from,$to);

        return $agencyGroupID[0]['name'];
    }

    public function getAgencyIDbyAgencyUnit($con,$sql,$parent,$region,$regionName){
        
        $table = "agency a";
        $columns = "a.ID";
        $join = "LEFT JOIN agency_unit au ON au.agency_id = a.ID
                 LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
                 LEFT JOIN region r ON r.ID = ag.region_id
        ";

        $where = "WHERE ( au.name = \"".addslashes($parent)."\" ) AND (r.name = \"".$regionName."\") " ;
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $agencyGroupID = $sql->fetch($res,$from,$to);
        
        if(!$agencyGroupID){
            var_dump(" INICIO AGENCY");
            var_dump($regionName);
            var_dump($parent);
            var_dump( $agencyGroupID );
            var_dump("FIM AGENCY");
        }

        return $agencyGroupID[0]['id'];        
    }

    public function getAgencyID($con,$sql,$parent){
        $table = "agency";
        $columns = "ID";
        $join = false;
        $where = "WHERE name = \"".addslashes($parent)."\"";
        $limit = "LIMIT 1";
        $res = $sql->select($con,$columns,$table,$join,$where,1,$limit);
        $from = array("ID");
        $to = array('id');
        $agencyGroupID = $sql->fetch($res,$from,$to);
        return $agencyGroupID[0]['id'];
    }

    public function getAllAgenciesByName($con,$sql,$parent, $aux=false){
        $table = "agency";
        $columns = "agency.ID";

        if (!$aux) {
            $join = false;
        }else{
            $join = "LEFT JOIN agency_group ag ON agency.agency_group_id = ag.ID";
        }

        $where = "WHERE agency.name = \"".addslashes($parent)."\" AND ag.name = \"".addslashes($aux)."\"";
        $res = $sql->select($con,$columns,$table,$join,$where,1);
        $from = array("ID");
        $to = array('id');
        $agencyGroupID = $sql->fetch($res,$from,$to);
        
        return $agencyGroupID;
    }

    public function getAllAgenciesByClient($con,$sql,$parent,$region){
        $sel = "SELECT c.ID AS 'clientID' 
                    FROM client c
                    LEFT JOIN client_group cg ON cg.ID = c.client_group_id 
                    WHERE ( c.name = '$parent' )
                    AND (cg.region_id = '$region')

               ";

        $result = $con->query($sel);

        $fr = array("clientID","clientID");

        $clientIDs = $sql->fetch($result,$fr,$fr)[0];

        $implodedClients = "";

        if($clientIDs){
            $implodedClients = implode(",", $clientIDs);
        }

        $table = "ytd y";
        $columns = "DISTINCT agency_id AS 'ID'";

        $join = "LEFT JOIN client c ON c.ID = y.client_id";

        $where = "WHERE c.ID IN (\"".addslashes($implodedClients)."\")";
                
        $res = $sql->select($con,$columns,$table,$join,$where,1);
                
        $from = array("ID");
        $to = array('id');
        $agencyID = $sql->fetch($res,$from,$to);

        if (!$agencyID) {
            $table = "fw_digital f";
            $columns = "DISTINCT agency_id AS 'ID'";

            $join = "LEFT JOIN client c ON c.ID = f.client_id";

            $where = "WHERE c.ID IN (\"".addslashes($implodedClients)."\")";
                    
            $res = $sql->select($con,$columns,$table,$join,$where,1);
                    
            $from = array("ID");
            $to = array('id');
            $agencyID = $sql->fetch($res,$from,$to);            
        }
        
        return $agencyID;
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
        $values =  " \" $region \" ,  \" ".$sheet['group']." \" ";
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
        $values = " \" ".$agencyGroupID." \" , \" ".$sheet['parent']." \" ";//    "'$agencyGroupID','".$sheet['parent']."'";

        $bool = $sql->insert($con,$table,$columns,$values);

        var_dump($bool['bool']);
        echo $bool['msg']."<br>";
    }

    public function handlerUnit($con,$spreadSheet){
        $sql = new sql();
        $o = new origin();
        $origins = $o->getOrigin($con,false);
        for ($s=0; $s < sizeof($spreadSheet); $s++) {             
            if($sheet[$s]['type'] == 'Agency'){ 
                $this->insertFromExcelUnit($con,$sql,$spreadSheet[$s],$origins);
            }
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
        $columns = 'agency_id,origin_id,name';
        $values =  " \" ".$agencyID." \" , \" ".$originID." \" , \" ".$sheet['child']." \" ";
        $bool = $sql->insert($con,$table,$columns,$values);
        var_dump($bool['bool']);
        echo $bool['msg']."<br>";
        return (" FIM AGÊNCIA  !!! ");
    }

    public function getAgencyGroup($con,$agencyID=false){

        $sql = new sql();

        $table = "agency_group ag";

        $columns = "ag.name AS 'agencyGroup',
                    ag.ID AS 'id',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyID){
            $agencyIDS = implode(",", $agencyID);
            $where .= "WHERE ag.ID IN ('$agencyIDS')";
        }

        $join = "LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->selectDistinct($con,$columns,$table,$join,$where,2);

        $from = array('id','agencyGroup','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyGroupBrazil($con,$agencyID=false){

        $sql = new sql();

        $table = "agency_group ag";

        $columns = "ag.name AS 'agencyGroup',
                    ag.ID AS 'id',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyID){
            $agencyIDS = implode(",", $agencyID);
            $where .= "WHERE ag.ID IN ('$agencyIDS') AND (r.ID = 1)";
        }else{
            $where .= "WHERE (r.ID = 1)";
        }

        $join = "LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->selectDistinct($con,$columns,$table,$join,$where,2);

        $from = array('id','agencyGroup','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAllAgencies($con,$id = false){
        
        $sql = new sql();
        $table = "agency_unit au";

        $columns = "a.name AS 'agency',
                    a.ID AS 'agencyID',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    au.name AS 'agencyUnit',
                    au.ID AS 'agencyUnitID',
                    r.name AS 'region'
                   ";

        $where = "WHERE (r.ID = '$id')";

        $join = "LEFT JOIN agency a ON a.ID = au.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 ";


        $res = $sql->select($con,$columns,$table,$join,$where,"7,5,3,1");

        $from = array('agencyID','agency','agencyGroup','agencyGroupID','agencyUnit','agencyUnitID','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
    }

    public function getAllAgenciesByFirstLetter($con,$id = false,$letter){
        $sql = new sql();
        $table = "agency_unit au";

        $columns = "a.name AS 'agency',
                    a.ID AS 'agencyID',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    au.name AS 'agencyUnit',
                    au.ID AS 'agencyUnitID',
                    r.name AS 'region'
                   ";

        $where = "WHERE (r.ID = '$id')";

        if($letter == "%"){
            $where .= "AND ( au.name NOT RLIKE '^[A-Z]' )";
        }else{
            $where .= "AND ( (au.name LIKE '$letter%') OR  (au.name LIKE '".strtolower($letter)."%') )";
        }

        $join = "LEFT JOIN agency a ON a.ID = au.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->select($con,$columns,$table,$join,$where,"7,5,3,1");

        $from = array('agencyID','agency','agencyGroup','agencyGroupID','agencyUnit','agencyUnitID','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
    }

    public function getAgencyGroupByRegionCMAPS($con,$year=false,$agencyRegion=false){

        $sql = new sql();

        $table = "cmaps y";

        $columns = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);

            if ($year) {
                $where .= "WHERE year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";                
            }
        }

        $join = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "ag.name", "ag.id");

        $from = array('id','agencyGroup');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyGroupByRegionCMAPSWithValuesBV($con,$year=false,$agencyRegion=false,$salesRep=false){
        $sql = new sql();

        // ========================================= // 

        $tableAleph = "wbd y";

        $columnsAleph = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup'
                   ";

        $whereAleph = "";
        //var_dump($salesRep);
        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);

            if ($year) {
                $whereAleph .= "WHERE (ag.id != (130)) AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $whereAleph .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $whereAleph .= ",";
                    }
                }

                $whereAleph .= ")";  
                $whereAleph .= " AND sr.ID IN (";
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    $whereAleph .= " '".$salesRep[$s]."' ";
                    if($s < ( sizeof($salesRep) - 1) ){
                        $whereAleph .= ",";
                    }
                }

                $whereAleph .= ")";                
            }else{
                $whereAleph = "WHERE (sr.ID IN ($salesRep))";
            }
        }
        //var_dump($whereAleph);
        $joinAleph = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id
                 LEFT JOIN sales_rep sr ON sr.ID = y.current_sales_rep_id
                 ";
        
        $resAleph = $sql->selectGroupBy($con,$columnsAleph,$tableAleph,$joinAleph,$whereAleph, "ag.name", "ag.id");

        $fromAleph = array('id','agencyGroup');

        $agencyAleph = $sql->fetch($resAleph,$fromAleph,$fromAleph);

      
        $agency = $agencyAleph;
               

        return $agency;

    }

    public function getAgencyGroupByRegionCMAPSWithValues($con,$year=false,$agencyRegion=false){
        $sql = new sql();

        $table = "cmaps y";

        $columns = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    SUM(gross) AS 'revenue'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);

            if ($year) {
                $where .= "WHERE year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";                
            }else{
                //$where = "WHERE (sr.ID IN ($salesRep))";
            }
        }

        $join = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id
                 LEFT JOIN sales_rep sr ON sr.ID = c.sales_rep_id
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "ag.name", "ag.id");

        $from = array('id','agencyGroup','revenue');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyGroupByRegion($con, $year=false,$agencyRegion=false){

        $sql = new sql();

        $table = "wbd y";

        $columns = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE sales_representant_office_id IN ('$agencyRegions')";

            if ($year) {
               $years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";                
            }
        }
        $where .= "WHERE ag.region_id = 1";

        $join = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "ag.name", "ag.id");

        $from = array('id','agencyGroup');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyGroupByRegionWithValue($con,$agencyRegion=false, $year=false){

        $sql = new sql();
        $cYear = $year;
        $pYear = $cYear - 1;
        $table = "ytd y";

        $columns = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE sales_representant_office_id IN ('$agencyRegions')
                        AND (
                            (gross_revenue_prate > 0) 
                            AND ( (year = $cYear) OR (year = $pYear) )
                        )";
            /*
            if ($year) {
               //$years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";                
            }*/
        }

        $join = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id
                 LEFT JOIN region r ON r.ID = y.sales_representant_office_id";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "ag.name", "ag.id");

        $from = array('id','agencyGroup', 'region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyGroupByRegionWithValueAleph($con,$agencyRegion=false, $year=false){

        $sql = new sql();
        $cYear = $year;
        $pYear = $cYear - 1;
        $table = "wbd y";

        $columns = "ag.ID AS 'id',
                    ag.name AS 'agencyGroup'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE ((gross_value > 0) 
                            AND ( (year = $cYear) OR (year = $pYear) )
                            AND (ag.region_id = $agencyRegion[0])
                        )";
            /*
            if ($year) {
               //$years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";                
            }*/
        }

        $join = "LEFT JOIN agency a ON a.ID = y.agency_id
                 LEFT JOIN agency_group ag ON ag.id = a.agency_group_id
                ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "ag.name", "ag.id");

        $from = array('id','agencyGroup');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgency($con,$agencyID=false){

        $sql = new sql();

        $table = "agency a";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyID){
            $agencyIDS = implode(/*",",*/ $agencyID);
            $where .= "WHERE a.ID IN ('$agencyIDS')";
        }

        $join = "LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('id','agency','agencyGroup','agencyGroupID','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyByAgencyGroupID($con,$agencyID = false){

        $sql = new sql();

        $table = "agency a";
        $columns = "a.ID AS 'agencyID',
                    a.name AS 'agency'";

        $where = "";
        $where .= "WHERE ( ag.ID = '$agencyID')";
        

        $join = "LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id                 
        ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('agency','agencyID');

        $agency = $sql->fetch($res,$from,$from);
        return $agency;
    }

    public function getAgencyUnitByAgencyID($con,$agencyID = false){

        $sql = new sql();

        $table = "agency_unit au";
        $columns = "au.name AS 'agencyUnit'";

        $where = "";
        $where .= "WHERE ( a.ID = '$agencyID')";
        

        $join = "LEFT JOIN agency a ON a.ID = au.agency_id                 
        ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('agencyUnit');

        $agency = $sql->fetch($res,$from,$from);
        return $agency;
    }

    public function getAgencyByRegion($con,$agencyRegion=false,$year=false){

        $sql = new sql();

        $table = "wbd y";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            //$where .= "WHERE sales_representant_office_id IN ('$agencyRegions')";

            if ($year) {
                //$years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";
            }
        }

        $join = "LEFT JOIN agency a ON a.id = y.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON ag.region_id = r.ID
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyByRegionSF($con,$agencyRegion=false,$year=false){

        $sql = new sql();

        $table = "sf_pr sf";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE region_id IN ('$agencyRegions')";

            if ($year) {
                //$years = implode(",", $year);
                $where .= " AND year_from IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";
            }
        }

        $join = "LEFT JOIN agency a ON a.id = sf.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON ag.region_id = r.ID
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyByRegionWithValue($con,$agencyRegion=false,$year=false){

        $sql = new sql();

        $cYear = $year;
        $pYear = $cYear - 1;

        $table = "ytd y";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE sales_representant_office_id IN ('$agencyRegions')
                        AND (
                            (gross_revenue_prate > 0) 
                            AND ( (year = $cYear) OR (year = $pYear) )
                        )";
            /*
            if ($year) {
                //$years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";
            }
            */
        }

        $join = "LEFT JOIN agency a ON a.id = y.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON ag.region_id = r.ID
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyByRegionWithValueAleph($con,$agencyRegion=false,$year=false){

        $sql = new sql();

        $cYear = $year;
        $pYear = $cYear - 1;

        $table = "wbd y";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID'
                    ";

        $where = "";

        if($agencyRegion){
            $agencyRegions = implode(",", $agencyRegion);
            $where .= "WHERE ((gross_value > 0) 
                            AND ( (year = $cYear) OR (year = $pYear) )
                            AND (ag.region_id = $agencyRegion[0])
                        )";
            /*
            if ($year) {
                //$years = implode(",", $year);
                $where .= " AND year IN (";
                for ($y=0; $y < sizeof($year); $y++) { 
                    $where .= "'".$year[$y]."'";
                    if($y < ( sizeof($year) - 1) ){
                        $where .= ",";
                    }
                }
                $where .= ")";
            }
            */
        }

        $join = "LEFT JOIN agency a ON a.id = y.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;

    }

    public function getAgencyByRegionCMAPS($con,$year=false){

        $sql = new sql();

        $table = "cmaps y";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID'
                   ";
        
        if($year){
            $where = "WHERE year = '$year'";
        }else{
            $where = false;
        }
        
        $join = "LEFT JOIN agency a ON a.id = y.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID');

        $agencyCmaps = $sql->fetch($res,$from,$from);

       /* if ($agencyCmaps != null) {
            return $agencyCmaps;
        }else{
            $table = "ytd y";

            $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID'
                   ";
        
            if($year){
                $where = "WHERE year = '$year'";
            }else{
                $where = false;
            }
            
            $join = "LEFT JOIN agency a ON a.id = y.agency_id
                     LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                     ";
            
            $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

            $from = array('id','agency','agencyGroup','agencyGroupID');

            $agency = $sql->fetch($res,$from,$from);*/

            return $agencyCmaps;
       // }

        
        
    }

    public function getAgencyByRegionBySource($con,$source=false,$year=false){

        $sql = new sql();
        $source = strtolower($source);

        if ($source == 'sf') {
            $source = 'sf_pr';
        }elseif($source == 'bts'){
            $source = 'ytd';
        }

        $table = "$source y";

        $columns = "a.name AS 'agency',
                    a.ID AS 'id',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID'
                   ";
        
        if($year){
            if ($source == 'sf_pr') {
                $where = "WHERE year_from = '$year' OR year_to = '$year'";    
            }else{
                $where = "WHERE year = '$year'";    
            }            
        }else{
            $where = false;
        }
        
        $join = "LEFT JOIN agency a ON a.id = y.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 ";
        
        $res = $sql->selectGroupBy($con,$columns,$table,$join,$where, "a.name", "a.id");

        $from = array('id','agency','agencyGroup','agencyGroupID');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
        
    }

    public function getAllAgenciesByRegion($con,$agencyRegion=false){
     
        $sql = new sql();

        $table = "agency a";

        $columns = "DISTINCT a.ID AS 'id',
                    a.name AS 'agency',
                    ag.ID AS 'agencyGroupID',
                    ag.name AS 'agencyGroup',
                    r.name AS 'region'
                    ";

        $where = "";

        if($agencyRegion){
            $agencyRegion = implode(",",$agencyRegion);
            $where .= "WHERE r.ID IN ('$agencyRegion')";
        }

        $join = "LEFT JOIN agency_unit au ON a.ID = au.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('id','agency',  'agencyGroupID', 'agencyGroup','region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
    }

    public function getRelationshipAgencyGroup($con,$region=false){        
        $sql = new sql();

        $table = "agency_group ag";

        $columns = "DISTINCT 
                    ag.ID AS 'agencyGroupID',
                    ag.name AS 'agencyGroup',                                        
                    r.name AS 'region'
                    ";

        $where = "";

        if($region){
            $regions = implode(",",$region);
            $where .= "WHERE r.ID IN ('$regions')";
        }

        $join = "LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->select($con,$columns,$table,$join,$where,false,false,false);

        $from = array('agencyGroupID','agencyGroup', 'region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
    }

    public function getRelationshipAgency($con,$region=false){        
        $sql = new sql();

        $table = "agency a";

        $columns = "DISTINCT 
                    a.ID AS 'agencyID',
                    a.name AS 'agency',                                        
                    r.name AS 'region'
                    ";

        $where = "";

        if($region){
            $regions = implode(",",$region);
            $where .= "WHERE r.ID IN ('$regions')";
        }

        $join = "LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 ";

        $res = $sql->select($con,$columns,$table,$join,$where,false,false,false);

        $from = array('agencyID','agency', 'region');

        $agency = $sql->fetch($res,$from,$from);

        return $agency;
    }


    public function getAgencyUnit($con,$agencyID=false){

        $sql = new sql();

        $table = "agency_unit au";

        $columns = "au.name AS 'agencyUnit',
                    au.ID AS 'id',
                    o.name AS 'origin',
                    a.name AS 'agency',
                    a.ID AS 'agencyID',
                    ag.name AS 'agencyGroup',
                    ag.ID AS 'agencyGroupID',
                    r.name AS 'region'
                   ";

        $where = "";

        if($agencyID){
            $agencyIDS = implode(",", $agencyID);
            $where .= "WHERE au.agency_id IN ('$agencyIDS')";
        }

        $join = "LEFT JOIN agency a ON a.ID = au.agency_id
                 LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                 LEFT JOIN region r ON r.ID = ag.region_id
                 LEFT JOIN origin o ON o.ID = au.origin_id
                ";

        $res = $sql->select($con,$columns,$table,$join,$where);

        $from = array('agencyUnit','id','origin','agency','agencyID','agencyGroup','agencyGroupID','region');

        $agencyUnit = $sql->fetch($res,$from,$from);

        return $agencyUnit;

    }

    public function checkAgencyUnit($con,$agencySearch){
        $sql = new sql();
        $select = "SELECT id,name FROM agency_unit WHERE ( name = '$agencySearch')";
        $res = $con->query($select);

        $from = array("id","name");

        $agencyBack = $sql->fetch($res,$from,$from);

        if($agencyBack){
            return true;
        }else{
            return false;
        }
    }




}
