<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\sql;
use App\base;
use App\brand;

class pipeline extends Model{

    public function getOptions($con){
        $sql =  new sql();

        $result = array();

        $holding = $this->getHolding($con);

        $cluster = $this->getCluster($con);

        $project = $this->getProp($con);
        
        $quota = $this->getQuota($con);

        $status = array('0 - Exploração','6 - Negado/Perdido','1 - Proposta Submetida','2 - Proposta em Análise','3 - Proposta em Negociação','4 - Aprovação','5 - Fechado');

        array_push($result, $holding);
        array_push($result,$cluster);
        array_push($result,$quota);
        array_push($result,$project);
        array_push($result,$status);
       // var_dump($result);
        return $result;
    }

    public function getHolding($con){
        $sql = new sql();

        $select = "SELECT b.ID as id, b.abv as holding
            FROM brand_group b
        ";

        $selectQuery = $con->query($select);
        $from = array('id','holding');
        $holding = $sql->fetch($selectQuery, $from, $from);

        return $holding;
    }

    public function getCluster($con){
        $sql = new sql();

        $select = "SELECT DISTINCT p.cluster as cluster
            FROM projects p
        ";

        $selectQuery = $con->query($select);
        $from = array('cluster');
        $cluster = $sql->fetch($selectQuery, $from, $from);

        return $cluster;
    }

    public function getProp($con){
        $sql = new sql();

        $select = "SELECT DISTINCT p.project as project
            FROM projects p
        ";

        $selectQuery = $con->query($select);
        $from = array('project');
        $project = $sql->fetch($selectQuery, $from, $from);

        return $project;
    }

    public function getQuota($con){
        $sql = new sql();

        $select = "SELECT DISTINCT q.name as quota
            FROM quotas q
        ";

        $selectQuery = $con->query($select);
        $from = array('quota');
        $quota = $sql->fetch($selectQuery, $from, $from);

        return $quota;
    }

    public function insertNewLines($con,$sql,$info){

        $register = $info['newRegister'];
        $cluster = $info['newCluster'];
        $project = $info['newProject'];
        $client = $info['newClient'];
        $agency = $info['newAgency'];
        $product = $info['newProduct'];
        $ae1 = $info['newAe1'];
        $ae2 = $info['newAe2'];
        $tv = $info['newTv'];
        $digital = $info['newDigital'];
        $quota = $info['newQuota'];
        $status = $info['newStatus'];
        $notes = $info['newNotes'];

        $insertQuery = "INSERT INTO  pipeline
                        SET register = '$register', 
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            product = '$product',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            tv_value = '$tv',
                            digital_value = '$digital',
                            quota = '$quota',
                            status = '$status',
                            notes = '$notes'";
            //print_r($insertQuery);  

        $resultInsertQuery = $con->query($insertQuery);
        //var_dump($resultInsertQuery);

    }

     public function updateLines($con,$sql,$id,$register,$cluster,$project,$client,$agency,$product,$ae1,$ae2,$tv,$digital,$quota,$status,$notes){

         $query = "UPDATE pipeline
                        SET register = '$register', 
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            product = '$product',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            tv_value = '$tv',
                            digital_value = '$digital',
                            quota = '$quota',
                            status = '$status',
                            notes = '$notes'
                        WHERE ID = $id";

        $resultQuery = $con->query($query);

    }

    public function table($con,$sql){

        $select = "SELECT DISTINCT c.ID as packetID, register,cluster,property as project,client,agency,product,sr.name as primary_ae, ss.name as second_ae,tv_value,digital_value,quota,status,notes
                        FROM pipeline c
                        LEFT JOIN sales_rep sr ON (sr.ID = c.primary_ae_id) 
                        LEFT JOIN sales_rep ss ON (ss.ID = second_ae_id)
                         ";

        $selectQuery = $con->query($select);
        $from = array('packetID','register','cluster','project','client','agency','product','primary_ae','second_ae','tv_value','digital_value','quota','status','notes');
        $result = $sql->fetch($selectQuery, $from, $from);

        //var_dump($select);
     
        return $result;
    }

    public function makeTotal($table){


        for ($t=0; $t <sizeof($table); $t++) { 
            
            $total[$t] = $table[$t]['tv_value'] + $table[$t]['digital_value'];
            
        }



        return $total;
    }

    
}

