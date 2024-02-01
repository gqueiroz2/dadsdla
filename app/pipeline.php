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

        $client = $this->listOFClients($con);

        $agency = $this->listOFAgencies($con);

        $status = array('0 - Exploração','1 - Proposta Submetida','2 - Proposta em Análise','3 - Proposta em Negociação','4 - Aprovação','5 - Fechado','6 - Negado/Perdido');

        $manager = array('FM','BP','RA');

        array_push($result, $holding);
        array_push($result,$cluster);
        array_push($result,$quota);
        array_push($result,$project);
        array_push($result,$status);
        array_push($result,$client);
        array_push($result,$agency);
        array_push($result,$manager);

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

        $select = "SELECT DISTINCT TRIM(p.cluster) as cluster
            FROM clusters p
        ";

        $selectQuery = $con->query($select);
        $from = array('cluster');
        $cluster = $sql->fetch($selectQuery, $from, $from);

        return $cluster;
    }

    public function getProp($con){
        $sql = new sql();

        $select = "SELECT DISTINCT TRIM(p.project) as project
            FROM clusters p
        ";
        //var_dump($select);
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
        $cluster = $info['newCluster'][0];
        $project = $info['newProject'][0];
        $client = $info['newClient'][0];
        $agency = $info['newAgency'][0];
        $product = $info['newProduct'];
        $ae1 = $info['newAe1'][0];
        $ae2 = $info['newAe2'][0];
        $manager = $info['newManager'][0];
        $tv = $info['newTv'];
        $digital = $info['newDigital'];
        $start = $info['newFirstMonth'][0];
        $end = $info['newEndMonth'][0];
        $quota = $info['newQuota'];
        $status = $info['newStatus'];
        $notes = $info['newNotes'];
        //var_dump($manager);
        $insertQuery = "INSERT INTO  pipeline
                        SET register = '$register', 
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            product = '$product',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            manager = '$manager',
                            tv_value = '$tv',
                            digital_value = '$digital',
                            start_month = '$start',
                            end_month = '$end',
                            quota = '$quota',
                            status = '$status',
                            notes = '$notes'";
            //print_r($insertQuery);  

        $resultInsertQuery = $con->query($insertQuery);
        //var_dump($resultInsertQuery);

    }

     public function updateLines($con,$sql,$id,$register,$cluster,$project,$client,$agency,$product,$ae1,$ae2,$manager,$tv,$digital,$start,$end,$quota,$status,$notes){
        //var_dump($manager);
         $query = "UPDATE pipeline
                        SET register = '$register', 
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            product = '$product',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            manager = '$manager',
                            tv_value = '$tv',
                            digital_value = '$digital',
                            start_month = '$start',
                            end_month = '$end',
                            quota = '$quota',
                            status = '$status',
                            notes = '$notes'
                        WHERE ID = $id";
            //print_r($query);  
        $resultQuery = $con->query($query);

    }

    public function table($con,$sql){

        $select = "SELECT DISTINCT c.ID as packetID, register,cluster,property as project,cl.name as client,a.name as agency,product,sr.name as primary_ae, ss.name as second_ae,manager,tv_value,digital_value,start_month,end_month,quota,status,notes
                        FROM pipeline c
                        LEFT JOIN sales_rep sr ON (sr.ID = c.primary_ae_id) 
                        LEFT JOIN sales_rep ss ON (ss.ID = second_ae_id)
                        LEFT JOIN client cl ON (cl.ID = c.client)
                        LEFT JOIN agency a ON (a.ID = c.agency)
                         ";

        $selectQuery = $con->query($select);
        $from = array('packetID','register','cluster','project','client','agency','product','primary_ae','second_ae','manager','tv_value','digital_value','start_month','end_month','quota','status','notes');
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


     //make a list of clients for the front-end button to add a new client basis on existing clients
    public function listOFAgencies(Object $con){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

        $select = "SELECT DISTINCT  a.ID as aID, a.name as agency
                    FROM agency a                    
                    left join agency_group ag on ag.ID = a.agency_group_id
                    and ag.region_id = 1
                    ORDER BY a.name ASC";
        //var_dump($select);
        $from = array('aID','agency');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        return $client;
    }

     //make a list of clients for the front-end button to add a new client basis on existing clients
    public function listOFClients(Object $con){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

        $select = "SELECT DISTINCT c.ID AS clientId ,c.name as client
                    FROM client c                   
                    WHERE c.client_group_id = 1
                    ORDER BY c.name ASC";
        //var_dump($select);
        $from = array('clientId','client');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        return $client;
    }
    
}

