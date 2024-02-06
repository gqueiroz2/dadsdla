<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;
use App\base;
use App\brand;

class packets extends Model{
    

    public function getOptions($con){
        $sql =  new sql();

        $result = array();

        $letter = array('Yes','No');

        $cluster = $this->getCluster($con);

        $project = $this->getProp($con);
        
        $quota = $this->getQuota($con);  

        $client = $this->listOFClients($con);

        $agency = $this->listOFAgencies($con);

        $payment = array('','Installments','Anticipated','30 DFM');      

        array_push($result,$letter);
        array_push($result,$cluster);
        array_push($result,$quota);
        array_push($result,$project);
        array_push($result,$payment);
        array_push($result,$client);
        array_push($result,$agency);

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
        $client = $info['newClient'][0];
        $agency = $info['newAgency'][0];
        $segment = $info['newSegment'];
        $ae1 = $info['newAe1'];
        $ae2 = $info['newAe2'];
        $dsc_tv = $info['new_dsc_tv'];
        $dsc_digital = $info['new_dsc_digital'];
        $wm_tv = $info['new_wm_tv'];
        $wm_digital = $info['new_wm_digital'];
        $spt_tv = $info['new_spt_tv'];
        $spt_digital = $info['new_spt_digital'];
        $wbd_max = $info['new_wbd_max'];
        $first = $info['newFirstMonth'][0];
        $end = $info['newEndMonth'][0];
        $payment = $info['newPayment'][0];
        $installments = $info['newInstallments'];
        $quota = $info['newQuota'];
        $letter = $info['newLetter'];
        $notes = $info['newNotes'];

        $insertQuery = "INSERT INTO  closed_packets
                        SET register = '$register', 
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            segment = '$segment',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            dsc_tv = '$dsc_tv',
                            dsc_digital = '$dsc_digital',
                            wm_tv = '$wm_tv',
                            wm_digital = '$wm_digital',
                            spt_tv = '$spt_tv',
                            spt_digital = '$spt_digital',
                            wbd_max = '$wbd_max',
                            start_month = '$first',
                            end_month = '$end',
                            payment = '$payment',
                            installments = '$installments',
                            quota = '$quota',
                            letter = '$letter',
                            notes = '$notes'";
            //print_r($insertQuery);  

        $resultInsertQuery = $con->query($insertQuery);
        //var_dump($resultInsertQuery);

    }
    public function updateLines($con,$sql,$id,$register,$holding,$cluster,$project,$client,$agency,$segment,$ae1,$ae2,$dsc_tv,$dsc_digital,$wm_tv,$wm_digital,$spt_tv,$spt_digital,$wbd_max,$startMonth,$endMonth,$payment,$installments,$quota,$letter,$notes){

         $query = "UPDATE  closed_packets
                        SET register = '$register', 
                            holding_id = '$holding',
                            cluster = '$cluster',
                            property = '$project',
                            client = '$client',
                            agency = '$agency',
                            segment = '$segment',
                            primary_ae_id = '$ae1',
                            second_ae_id = '$ae2',
                            dsc_tv = '$dsc_tv',
                            dsc_digital = '$dsc_digital',
                            wm_tv = '$wm_tv',
                            wm_digital = '$wm_digital',
                            spt_tv = '$spt_tv',
                            spt_digital = '$spt_digital',
                            wbd_max = '$wbd_max',
                            start_month = '$startMonth',
                            end_month = '$endMonth',
                            payment = '$payment',
                            installments = '$installments',
                            quota = '$quota',
                            letter = '$letter',
                            notes = '$notes'
                        WHERE ID = $id";

        $resultQuery = $con->query($query);

    }

    public function table($con,$sql){

        $select = "SELECT DISTINCT c.ID as packetID, register,cluster,property as project,client,agency,segment,sr.name as primary_ae, ss.name as second_ae,dsc_tv,dsc_digital,wm_tv,wm_digital,spt_tv,spt_digital,wbd_max,start_month,end_month,payment,installments,quota,letter,notes,product
                        FROM closed_packets c
                        LEFT JOIN sales_rep sr ON (sr.ID = c.primary_ae_id) 
                        LEFT JOIN sales_rep ss ON (ss.ID = second_ae_id)
                         ";
        //echo "<pre>$select</pre>";
        $selectQuery = $con->query($select);
        $from = array('packetID','register','cluster','project','client','agency','segment','primary_ae','second_ae','dsc_tv','dsc_digital','wm_tv','wm_digital','spt_tv','spt_digital','wbd_max','start_month','end_month','payment','installments','quota','letter','notes','product');
        $result = $sql->fetch($selectQuery, $from, $from);

        //var_dump($select);
     
        return $result;
    }

    public function makeTotal($table,$type){


        for ($t=0; $t <sizeof($table); $t++) { 
            
            $total[$t] = $table[$t]['dsc_'.$type] + $table[$t]['wm_'.$type] + $table[$t]['spt_'.$type];
            
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
