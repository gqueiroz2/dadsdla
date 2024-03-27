<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\renderYoY;
use App\renderDashboards;
use App\region;
use App\dataBase;
use App\salesRep;
use App\pRate;
use App\sql;
use App\brand;
use App\agency;
use App\client;
use App\subRankings;
use App\subBrandRanking;
use App\subMarketRanking;
use App\subChurnRanking;
use App\subNewRanking;
use App\base;

class ajaxController extends Controller{
    
    public function typeSelectConsolidateDLA(){
        $type = Request::get('type');
        $region = Request::get('region');

        switch ($type) {
            case 'brand':                    
                $base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $b = new brand();
                $brand = $b->getBrand($con);
                for ($i = 0; $i < sizeof($brand); $i++) { 
                    if ($brand[$i]["name"] != "DN") {
                        $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'],$brand[$i]['brand_group_id'])));
                        echo "<option selected='true' value='".$value[$i]."'>".$brand[$i]["name"]."</option>";   
                    }
                }
                break;

            case 'ae':                                
                $regionID = $region;
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $cYear = intval(date('Y'));
                $sr = new salesRep();
                $regionID = $regionID;                
                
                $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);
                
                for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." - ".$resp[$s]["region"]." </option>";
                }
                
                break;

            default:
                echo "<option selected='true' value='all'> All </option>";
                
                break;
        }

    }

     public function getAgencyPipeline(){
        $sql = new sql();
        $db = new dataBase();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $select = "SELECT DISTINCT a.id AS aID, a.name as agency 
                    from pipeline p
                    left join agency a on p.agency = a.id
                    where p.agency != 0
                    ORDER BY a.name ASC";
        //var_dump($select);
        $from = array('aID','agency');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        //var_dump($project);
        if ($client != '') {        
            for($p=0; $p<sizeof($client);$p++){
                echo "<option style='font-size: 16px; width:100%;' value='".$client[$p]['aID']."' selected='true'>".$client[$p]['agency']."</option>";
            }                
        }else{
           echo "<option value=''> There is no Data for this. </option>";
        }
    }

     public function getClientPipeline(){
        $sql = new sql();
        $db = new dataBase();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $select = "SELECT DISTINCT c.ID as clientId, c.name as client  
                    from pipeline p
                    left join client c on p.client = c.id
                    where (c.client_group_id = 1)
                    and p.client != 0
                    ORDER BY c.name ASC";
        //var_dump($select);
        $from = array('clientId','client');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        //var_dump($project);
        if ($client != '') {        
            for($p=0; $p<sizeof($client);$p++){
                echo "<option style='font-size: 16px; width:100%;' value='".$client[$p]['clientId']."' selected='true'>".$client[$p]['client']."</option>";
            }                
        }else{
           echo "<option value=''> There is no Data for this. </option>";
        }
    }

     public function getPacketsFilter(){
        $db = new dataBase();
        $sql = new sql();

        $regionID = '1';

        $cluster = Request::get('cluster');
        //var_dump($cluster);
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
       
      
        $select = "SELECT DISTINCT project as packet From projects p ORDER BY project ASC";

        $selectQuery = $con->query($select);
        $from = array('packet');
        $project = $sql->fetch($selectQuery, $from, $from);
        //var_dump($project);
        if ($project != '') {        
            for($p=0; $p<sizeof($project);$p++){
                echo "<option style='font-size: 16px; width:100%;' value='".$project[$p]['packet']."' selected='true'>".$project[$p]['packet']."</option>";
            }                
        }else{
           echo "<option value=''> There is no Data for this. </option>";
        }
    }

    public function getPackets(){
        $db = new dataBase();
        $sql = new sql();

        $regionID = '1';

        $cluster = Request::get('cluster');
        //var_dump($cluster);
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
       
      
        $select = "SELECT DISTINCT project as packet From projects p where p.cluster = ('$cluster') ORDER BY project ASC";

        $selectQuery = $con->query($select);
        $from = array('packet');
        $project = $sql->fetch($selectQuery, $from, $from);
        //var_dump($project);
        if ($project != '') {        
            for($p=0; $p<sizeof($project);$p++){
                echo "<option style='font-size: 16px; width:100%;' value='".$project[$p]['packet']."'>".$project[$p]['packet']."</option>";
            }                
        }else{
           echo "<option value=''> There is no Data for this. </option>";
        }
    }

    public function getManager(){
        $db = new dataBase();
        $sql = new sql();

        $regionID = '1';

        $salesRep = Request::get('salesRep');
        //var_dump($cluster);
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
       
      
        $select = "SELECT DISTINCT ss.ab_name as manager From sales_rep s left join sales_rep_group ss ON s.sales_group_id = ss.id where s.id  = ('$salesRep')";

        $selectQuery = $con->query($select);
        $from = array('manager');
        $manager = $sql->fetch($selectQuery, $from, $from);
        //var_dump($project);
        if ($manager != '') {        
            for($p=0; $p<sizeof($manager);$p++){
                echo "<option style='font-size: 16px; width:100%;' value='".$manager[$p]['manager']."'>".$manager[$p]['manager']."</option>";
            }                
        }else{
           echo "<option value=''> There is no Data for this. </option>";
        }
    }

    public function typeSelectConsolidate(){
        $type = Request::get('type');
        $region = 1;
        //var_dump($region);
        switch ($type) {
            case 'brand':                    
                $base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $b = new brand();
                $brand = $b->getBrand($con);
                for ($i = 0; $i < sizeof($brand); $i++) { 
                    if ($brand[$i]["name"] != "DN") {
                        $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'],$brand[$i]['brand_group_id'])));
                        echo "<option selected='true' value='".$value[$i]."'>".$brand[$i]["name"]."</option>";   
                    }
                }
                break;
            case 'ae':                                
                $regionID = $region;
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $cYear = intval(date('Y'));
                $sr = new salesRep();
                $regionID = array($regionID);
                $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);

                $userLevel = Request::session()->get('userLevel');
                $special = Request::session()->get('special');
                $userRegionID = Request::session()->get('userRegionID');
                $user = Request::session()->get('userName');

                //var_dump($user);

                if($userLevel == "L4"){
                    $userName = Request::session()->get('userName');
                    $performanceName = Request::session()->get('performanceName');
                    $check = false;            
                    for ($s=0; $s <sizeof($resp) ; $s++) { 
                        if (!is_null($performanceName)) {
                            if($resp[$s]["salesRep"] == $performanceName){
                                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                                $check = true;
                            }
                        }else{
                            setlocale(LC_ALL, "en_US.utf8");
                            $output = iconv("utf-8", "ascii//TRANSLIT", $userName);
                            if( strpos($resp[$s]["salesRep"], $output)  !== false){
                                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                                $check = true;
                            }
                        }
                    }
                    if (!$check) {
                        echo "<option value=''> Sales Rep Not Found </option>";
                    }
                }elseif($userLevel == 'L8') {
                //$regionID = array($regionID);
                //$resp = $sr->getSalesRepRepresentativeByRegion($con,$regionID,true,$year);
                //for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$user."' selected='true'> ".$user." </option>";

                }elseif($userLevel == "L6"){
                    if($regionID[0] == $userRegionID){
                        $userName = Request::session()->get('userName');
                        $performanceName = Request::session()->get('performanceName');
                        $check = false; 
                        for ($s=0; $s <sizeof($resp) ; $s++) {  
                            $salesRepWithNoSpecialCharacters = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$resp[$s]['salesRep']);
                            $salesRepWithNoSpecialCharacters1 = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$userName);
                            if($salesRepWithNoSpecialCharacters == $salesRepWithNoSpecialCharacters1){                        
                                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                            }
                        }
                    }else{
                        if($resp){
                            for ($s=0; $s < sizeof($resp); $s++) { 
                                echo "<option value='".$resp[$s]["id"]."' selected='true'>"
                                    .$resp[$s]["salesRep"].
                                "</option>";
                            }
                        }else{
                            echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
                        }   
                    }
                }else{
                    for ($s=0; $s < sizeof($resp); $s++) { 
                        echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                    }
                }

                
                
                break;

            default:
                echo "<option selected='true' value='all'> All </option>";
                
                break;
        }


    }

    public function typeConsolidate(){
        $type = array("Brand","AE","Advertiser","Agency","Agency Group");
        $typeA = array("brand","ae","advertiser","agency","agencyGroup");

        echo "<option value=''> Select </option>";
        for ($t=0; $t < sizeof($type); $t++) { 
            echo "<option value='".$typeA[$t]."'>".$type[$t]."</option>";
        }

    }

    public function BVAgencyGroupNoRep(){

        $db = new dataBase();
        $sr = new salesRep();
        $agency = new agency(); 

        $regionID = '1';

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = (date('Y'));
        $pYear = (date('Y')-1);
        $ppYear = (date('Y')-2);
        $pppYear = (date('Y')-3);

        $years = array($cYear,$pYear,$ppYear,$pppYear);
        $rID = array();

        $agencies = $agency->getAgencyGroupByRegion($con,$years,false);
        //var_dump($agencies);
        if ($agencies != '') {
            echo "<option value=''> Select </option>";
            for ($a=0; $a < sizeof($agencies); $a++){ 
                echo "<option value=".$agencies[$a]['id'].">".strtoupper($agencies[$a]['agencyGroup'])."</option>";
            }
        }else{
           echo "<option value=''> There is no Data for this Sales Rep. </option>";
        }
        
    }

    public function BVAgencyGroup(){

        $db = new dataBase();
        $sr = new salesRep();
        $agency = new agency(); 

        $regionID = '1';

        $salesRep = Request::get('salesRep');
        //var_dump($salesRep);
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = (date('Y'));
        $pYear = (date('Y')-1);
        $ppYear = (date('Y')-2);

        $years = array($cYear,$pYear,$ppYear);
        $rID = array();

        $agencies = $agency->getAgencyGroupByRegionCMAPSWithValuesBV($con,$years,array($regionID),array($salesRep));
        //var_dump($agencies);
        if ($agencies != '') {
            echo "<option value=''> Select </option>";
            for ($a=0; $a < sizeof($agencies); $a++){ 
                echo "<option value=".$agencies[$a]['id'].">".strtoupper($agencies[$a]['agencyGroup'])."</option>";
            }
        }else{
           echo "<option value=''> There is no Data for this Sales Rep. </option>";
        }
        
    }

    public function brandBySource(){
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $b = new brand();
        $brand = $b->getBrand($con);
        $source = Request::get('source');

        for ($b=0; $b < sizeof($brand); $b++) { 
            if($source == "SF"){
                echo "<option value='".$brand[$b]['id']."' selected='true'>".$brand[$b]['name']."</option>";
            }else if($source == "FW"){
                if($brand[$b]['type'] == "Non-Linear"){
                   echo "<option value='".$brand[$b]['id']."' selected='true'>".$brand[$b]['name']."</option>";
                }
            }else{
                if($brand[$b]['type'] == "Linear"){
                   echo "<option value='".$brand[$b]['id']."' selected='true'>".$brand[$b]['name']."</option>"; 
                }
            }
        }        
    }

    public function getAgencyByRegion(){
        
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionID");
        $agency = $a->getAgencyByRegion($con,array($region));

        for ($a=0; $a < sizeof($agency); $a++) { 
            echo "<option value='".$agency[$a]["id"]."' selected='true'>".$agency[$a]["agency"]."</option>";
        }
    }

    public function getClientByRegion(){
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionID");
        $client = $c->getClientByRegion($con,array($region));

        if ($client) {
            for ($c=0; $c < sizeof($client); $c++) { 
                echo "<option value='".$client[$c]["id"]."' selected='true'>".$client[$c]["client"]."</option>";
            }
        }else{
            echo "<option value='' selected='true'> There is no Clients for those agencies </option>";
        }


        
    }

    public function getAgencyByRegionSF(){
        
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionID");
        $agency = $a->getAgencyByRegionSF($con,array($region));

        for ($a=0; $a < sizeof($agency); $a++) { 
            echo "<option value='".$agency[$a]["id"]."' selected='true'>".$agency[$a]["agency"]."</option>";
        }
    }

    public function getClientByRegionSF(){
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionID");
        $client = $c->getClientByRegionSF($con,array($region));

        for ($c=0; $c < sizeof($client); $c++) { 
            echo "<option value='".$client[$c]["id"]."' selected='true'>".$client[$c]["client"]."</option>";
        }
    }

    public function getAgencyByRegionAndYear(){        
        
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $year  = Request::get("year");

        $agency = $a->getAgencyByRegionCMAPS($con,$year);

        if ($agency) {
            for ($a=0; $a < sizeof($agency); $a++){ 
                echo "<option value='".$agency[$a]["id"]."' selected='true'>".$agency[$a]["agency"]."</option>";
            }
        }else{
            echo "<option value='' selected='true'> There is no Clients for those agencies on $year </option>";
        }
        
        
    }

    public function getClientByRegionAndYear(){

        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $year  = Request::get("year");

        $client = $c->getClientByRegionCMAPS($con,$year);
        if ($client) {
            for ($c=0; $c < sizeof($client); $c++) { 
                echo "<option value='".$client[$c]["id"]."' selected='true'>".$client[$c]["client"]."</option>";
            }    
        }else{
            echo "<option value='' selected='true'> There is no Clients for those agencies on $year </option>";
        }    

        
    }

    public function getClientByRegionAndAgency(){
        $base = new base();
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $agency = Request::get('agency');
        $region = Request::get("region");
        $year = Request::get("year");
        if( !is_null($agency) ){
            $agencyString = $base->arrayToString($agency,false,0);
        }else{
            $agencyString = false;
        }

        $client = $c->clientByAgencyAndRegion($con,$sql,$region,$year,$agencyString);

        if($client){
            for ($c=0; $c < sizeof($client); $c++) { 
                echo "<option value='".$client[$c]["clientID"]."' selected='true'>".$client[$c]["client"]."</option>";
            }    
        }else{
            echo "<option value='' selected='true'> There is no Clients for those agencies on $year </option>";
        }
    }

     public function getAgencyByRegionAndClient(){
        $base = new base();
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $client = Request::get('client');
        $region = Request::get("region");
        $year = Request::get("year");
        if( !is_null($agency) ){
            $clientString = $base->arrayToString($client,false,0);
        }else{
            $clientString = false;
        }

        $agency = $a->agencyByClientAndRegion($con,$sql,$region,$year,$clientString);

        if($agency){
            for ($c=0; $c < sizeof($agency); $c++) { 
                echo "<option value='".$agency[$c]["agencyID"]."' selected='true'>".$agency[$c]["agency"]."</option>";
            }    
        }else{
            echo "<option value='' selected='true'> There is no agencies for those agencies on $year </option>";
        }
    }

    public function getAgencyByRegionAndClientSize(){
        $base = new base();
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $client = Request::get('client');
        $region = Request::get("region");
        $year = Request::get("year");
        if( !is_null($client) ){
            $clientString = $base->arrayToString($client,false,0);
        }else{
            $clientString = false;
        }

       $agency = $a->agencyByClientAndRegion($con,$sql,$region,$year,$clientString);



        if($agency){
            echo sizeof($agency);
        }else{
            echo 0;
        }
    }

    public function getClientByRegionAndAgencySize(){
        $base = new base();
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $agency = Request::get('agency');
        $region = Request::get("region");
        $year = Request::get("year");
        if( !is_null($agency) ){
            $agencyString = $base->arrayToString($agency,false,0);
        }else{
            $agencyString = false;
        }

        $client = $c->clientByAgencyAndRegion($con,$sql,$region,$year,$agencyString);



        if($client){
            echo sizeof($client);
        }else{
            echo 0;
        }
    }

    public function getClientByRegionSize(){
        $base = new base();
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $region = Request::get("region");
        $year = Request::get("year");
        /*
        if( !is_null($agency) ){
            $agencyString = $base->arrayToString($agency,false,0);
        }else{
            $agencyString = false;
        }*/

        $client = $c->getClientByRegion($con,$region,$year);

        if($client){
            echo sizeof($client);
        }else{
            echo 0;
        }
    }

     public function getAgencyByRegionSize(){
        $base = new base();
        $a = new agency;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $region = Request::get("region");
        $year = Request::get("year");
        /*
        if( !is_null($agency) ){
            $agencyString = $base->arrayToString($agency,false,0);
        }else{
            $agencyString = false;
        }*/

        $agency = $a->getAgencyByRegion($con,$region,$year);

        if($agency){
            echo sizeof($agency);
        }else{
            echo 0;
        }
    }

    public function getClientByRegionInsights(){
        $c = new client;
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $region = Request::get("regionID");
        $selectInsight = "SELECT DISTINCT 
                                c.ID AS 'id',
                                c.name AS 'client' 
                                FROM insights i
                                LEFT JOIN client c ON c.ID = i.client_id
                                ";

        $res = $con->query($selectInsight);
        $from = array("id","client");
        $clientInsights = $sql->fetch($res,$from,$from);

        $client = $clientInsights;

        for ($c=0; $c < sizeof($client); $c++) { 
            echo "<option value='".$client[$c]["id"]."' selected='true'>".$client[$c]["client"]."</option>";
        }
    }

    public function secondaryFilterTitle(){        
        $type = Request::get('type');
        if($type == "agency"){
            echo "Client:";
        }else{
            echo "Agency:";
        }               
    }

    public function baseFilterTitle(){
        $type = Request::get('type');
        if($type == "agencyGroup"){
            $type = "Agency Group";
        }else{
            $type = ucfirst( $type );
        }

        
        echo $type.":";
    }

    public function secondaryFilter(){
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sql = new sql();

        $type = Request::get('type');
        $regionID = Request::get('region');
        $baseFilter = json_decode( base64_decode( Request::get('baseFilter')  ));

        switch ($type) {
            case 'client':
                $showID = "agencyID";
                $showName = "agency";

                $sql = "SELECT DISTINCT a.ID AS 'agencyID',
                                        a.name AS 'agency'
                            FROM wbd y
                            LEFT JOIN agency a ON a.ID = y.agency_id
                            AND (client_id = \"".$baseFilter->id."\")
                ";
                $res = $con->query($sql);
                if($res && $res->num_rows > 0){
                    $count = 0;
                    while($row = $res->fetch_assoc()){
                        $second[$count]['agencyID'] = $row['agencyID'];
                        $second[$count]['agency'] = $row['agency'];

                        $count++;
                    }
                }else{
                    $second = false;
                }
                break;            
            default:
                if($type == "agency"){                
                    $showID = "clientID";
                    $showName = "client";
                    
                    $sql = "SELECT DISTINCT c.ID AS 'clientID',
                                   c.name AS 'client'
                                FROM wbd y
                                LEFT JOIN client c ON c.ID = y.client_id
                                AND (agency_id = \"".$baseFilter->id."\")
                    ";
                    $res = $con->query($sql);
                    if($res && $res->num_rows > 0){
                        $count = 0;
                        while($row = $res->fetch_assoc()){
                            $second[$count]['clientID'] = $row['clientID'];
                            $second[$count]['client'] = $row['client'];

                            $count++;
                        }
                    }else{
                        $second = false;
                    }
                }else{
                    $showID = "agencyID";
                    $showName = "agency";
                    

                    $sql = "SELECT DISTINCT a.ID AS 'agencyID',
                               a.name AS 'agency'
                            FROM wbd y
                            LEFT JOIN agency a ON a.ID = y.agency_id
                            LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                            WHERE (ag.ID = \"".$baseFilter->id."\" )
                            
                ";
                    $res = $con->query($sql);

                    if($res && $res->num_rows > 0){
                        $count = 0;
                        while($row = $res->fetch_assoc()){
                            $second[$count]['agencyID'] = $row['agencyID'];
                            $second[$count]['agency'] = $row['agency'];

                            $count++;
                        }
                    }else{
                        $second = false;
                    }
                }
                break;
        }
        if($second){
            for ($s=0; $s < sizeof($second); $s++) { 
                if (is_null($second[$s][$showID])) {
                    unset($second[$s]);
                    $second = array_values($second);
                }
                
                echo "<option value=\"".$second[$s][$showID]."\" selected='true'>".$second[$s][$showName]."</option>";
            }
        }else{
            echo "<option value='' selected='true'> No Values Found !!! </option>";
        }

    }

    public function BVBaseFilter(){
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bs = new base();
        $type = Request::get('type');
        $regionID = Request::get('region');

        $years = false;

        switch ($type) {

            case 'client':
                $clss = new client();
                $base = $clss->getClientByRegion($con,array($regionID),$years);
                $tralala = "clientGroup";
                break;
            default:
                $clss = new agency();
                if($type == "agency"){
                    $base = $clss->getAgencyByRegionCMAPS($con,$years);
                    $tralala = "agencyGroup";
                }else{
                    $base = $clss->getAgencyGroupByRegionCMAPS($con,$years);
                    $tralala = "region";
                }
                break;
        }

        for ($bb=0; $bb < sizeof($base); $bb++) { 
            
            $forVerify[$bb] = $base[$bb]['id'];

        }

        $verified = $bs->verifyOnBaseCMAPS($con,$type,$forVerify);

        echo "<option value=''> Select </option>";
        for ($b=0; $b < sizeof($base); $b++) { 
            if($verified[$b]){
                echo "<option value=\"". base64_encode(json_encode($base[$b]))."\">"
                    .$base[$b][$type];
                if($type != "client" && $type != 'agencyGroup'){
                    echo " - ".$base[$b][$tralala];
                }
                echo "</option>";
            }
        }
 
    }

    public function baseFilter(){
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bs = new base();
        $type = Request::get('type');
        $regionID = Request::get('region');

        //$cYear = intval(date('Y'));
        //$years = array($cYear, $cYear-1, $cYear-2);
        $years = false;

        switch ($type) {

            case 'client':
                $clss = new client();
                $base = $clss->getClientByRegion($con,array($regionID),$years);
                $tralala = "clientGroup";
                break;
            default:
                $clss = new agency();
                if($type == "agency"){
                    $base = $clss->getAgencyByRegion($con,array($regionID),$years);
                    $tralala = "agencyGroup";
                }else{
                    $base = $clss->getAgencyGroupByRegion($con,array($regionID),$years);
                    $tralala = "region";
                }
                break;
        }
        
        for ($bb=0; $bb < sizeof($base); $bb++) { 
            
            $forVerify[$bb] = $base[$bb]['id'];

        }

        $verified = $bs->verifyOnBase($con,$type,$forVerify);

        echo "<option value=''> Select </option>";
        for ($b=0; $b < sizeof($base); $b++) { 
            if($verified[$b]){
                echo "<option value=\"". base64_encode(json_encode($base[$b]))."\">"
                    .$base[$b][$type];
                if($type != "client" && $type != 'agencyGroup'){
                    echo " - ".$base[$b][$tralala];
                }
                echo "</option>";
            }
        }
    }

    public function Product(){
        
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $render = new renderDashboards();

        $last3YearsByProduct = Request::get("handle")['last3YearsByProduct'];
        $years = Request::get("years");
        $type = Request::get("type");

        echo "<span style='width:100%;'> ".$render->renderLast3ByProduct($con,$last3YearsByProduct,$years,$type)." </span>";
    }

    public function clientGroupByClient(){
        $clients = json_decode(base64_decode(Request::get('clients')));
        echo "Client Group - ".$clients->clientGroup;
        //echo "<option value='".$clients->client_group_id."'> Clients Group ".$clients->clientGroup."</option>";
    }


    public function agencyGroupByAgency(){
        $agency = json_decode(base64_decode(Request::get('agencies')));

        echo "Agency Group - ".$agency->agencyGroup;

        //echo "<option value='".$clients->client_group_id."'> Clients Group ".$clients->clientGroup."</option>";
    }

    public function tierByRegion(){

        echo "<option selected='true' value='T1'>T1</option>";
        echo "<option selected='true' value='T2'>T2</option>";
        echo "<option selected='true' value='TOTH'>OTH</option>";
    }

    public function brandsByTier(){
        
        $tiers = Request::get('tiers');

        if (is_null($tiers)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
            $con = $db->openConnection($default);

            $b = new brand();
            $brands = $b->getBrand($con);


            for ($t=0; $t < sizeof($tiers); $t++) {
                for ($b=0; $b < sizeof($brands); $b++) { 
                    $value[$b] = base64_encode(json_encode(array($brands[$b]['id'],$brands[$b]['name'])));
                    if ($tiers[$t] == 'T1') {
                        if (
                            $brands[$b]['name'] == 'DC' || 
                            $brands[$b]['name'] == 'HH' || 
                            $brands[$b]['name'] == 'DK' || 
                            $brands[$b]['name'] == 'AXN' || 
                            $brands[$b]['name'] == 'AXD'  
                            
                           ){
                            echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";            
                        }
                    }elseif ($tiers[$t] == 'T2') {
                        if (    
                            $brands[$b]['name'] == 'AP' || 
                            $brands[$b]['name'] == 'TLC' || 
                            $brands[$b]['name'] == 'ID' || 
                            $brands[$b]['name'] == 'DT' || 
                            $brands[$b]['name'] == 'FN' || 
                            $brands[$b]['name'] == 'ONL' || 
                            $brands[$b]['name'] == 'VIX' || 
                            $brands[$b]['name'] == 'HGTV' || 
                            $brands[$b]['name'] == 'VOD'|| 
                            $brands[$b]['name'] == 'GC'|| 
                            $brands[$b]['name'] == 'HO' || 
                            $brands[$b]['name'] == 'SON' || 
                            $brands[$b]['name'] == 'SD' || 
                            $brands[$b]['name'] == 'ES' ||
                            $brands[$b]['name'] == 'AC'                           

                           ){
                                echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";               
                        }
                    }else{
                        if ($brands[$b]['name'] == "OTH" || $brands[$b]['name'] == "IAS") {
                            echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";    
                        }                        
                    }
                }  

            }    
        }
        
    }

    public function getSalesRepByRegion(){
        $regionID = Request::get('regionID');

        if (is_null($regionID)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
            $con = $db->openConnection($default);
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            $regionID = array($regionID);

            $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);

            echo "<option selected='true' value=''>Select Sales Rep.</option>";

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["id"]."'> ".$resp[$s]["salesRep"]." </option>";
            }
        }
    }

    public function getSalesRepByRegionByYear(){
        $regionID = Request::get('regionID');
        $cYear = Request::get('year');

        if (is_null($regionID)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
            $con = $db->openConnection($default);
            $sr = new salesRep();

            $regionID = array($regionID);

            $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);

            echo "<option selected='true' value=''>Select Sales Rep.</option>";

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["id"]."'> ".$resp[$s]["salesRep"]." </option>";
            }
        }
    }

    public function getNewSalesRepByRegion(){
        $regionID = Request::get('regionID');

        $permission = Request::session()->get('userLevel');
        //$regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');

        if (is_null($regionID)) {
            
        }elseif($permission == 'L8') {
                //$regionID = array($regionID);
                //$resp = $sr->getSalesRepRepresentativeByRegion($con,$regionID,true,$year);
                //for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$user."' selected='true'> ".$user." </option>";
                //} 
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
            $con = $db->openConnection($default);
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            $regionID = array($regionID);

            $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
            }
        }
    }

    
    public function getDirector(){
        $regionID = Request::get('regionID');

        $year = Request::get('year');
        //var_dump($year);
        if (is_null($regionID)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
            $con = $db->openConnection($default);
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            $regionID = array($regionID);

            $director = $sr->getDirectorWBD($con,$year);

            for ($s=0; $s < sizeof($director); $s++) {
                echo "<option value='".$director[$s]['director']."' selected='true'> ".$director[$s]["director"]." </option>";
            }
        }
    }


    public function getRepByRegionAndYear(){
        $regionID = Request::get('regionID');

        $userLevel = Request::session()->get('userLevel');
        $special = Request::session()->get('special');
        $userRegionID = Request::session()->get('userRegionID');
        $user = Request::session()->get('userName');

        $year = Request::get('year');

        if (is_null($regionID)) {
            
        }else{
            if ($userLevel == 'L8') {
                echo "<option value='".$user."' selected='true'> ".$user." </option>";
            }else{
                $db = new dataBase();

                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $cYear = intval(date('Y'));
                $sr = new salesRep();

                $regionID = array($regionID);

                $resp = $sr->getSalesRepByRegion($con,$regionID,true,$year);

                for ($s=0; $s < sizeof($resp); $s++) {
                    echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                }
            }
                
        }
    }

    public function getNewSalesRepRepresentativesByRegionAndYear(){
        $rr = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $cYear = intval(date('Y'));
        $sr = new salesRep();

        $regionID = Request::get('regionID');
        //var_dump($regionID);
        $year = Request::get('year');
        $source = Request::get('source');
        $permission = Request::session()->get('userLevel');
        //$regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');


        //var_dump($source);

        $regionName = $rr->getRegion($con,array($regionID))[0]['name'];
        //var_dump($regionName);
        
        if($regionName == 'Brazil'){
            if (is_null($regionID)) {
                
            }elseif($permission == 'L8') {
                //$regionID = array($regionID);
                //$resp = $sr->getSalesRepRepresentativeByRegion($con,$regionID,true,$year);
                //for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$user."' selected='true'> ".$user." </option>";
                //}
            }else{
                $regionID = array($regionID);
                $resp = $sr->getSalesRepRepresentativeByRegion($con,$regionID,true,$year);
                for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                }
            }

        }else{
            if (is_null($regionID)) {
                
            }else{
                $regionID = array($regionID);
                $resp = $sr->getSalesRepByRegion($con,$regionID,true,$year);
                for ($s=0; $s < sizeof($resp); $s++) { 
                    echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
                }
            }
        }

        /*



        if (is_null($regionID)) {
            
        }else{
            

            $regionID = array($regionID);

            $resp = $sr->getSalesRepRepresentativeByRegion($con,$regionID,true,$year);

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
            }
        }

        */
    }

    public function getNewSalesRepUnitByRegionAndYear(){
        $regionID = Request::get('regionID');

        $year = Request::get('year');

        if (is_null($regionID)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
        $con = $db->openConnection($default);
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            $regionID = array($regionID);

            $resp = $sr->getSalesRepUnitByRegion($con,$regionID,true,$year);

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["id"]."' selected='true'> ".$resp[$s]["salesRepUnit"]." </option>";
            }
        }
    }

    public function getSalesRepByRegionAndYear(){
        $regionID = Request::get('regionID');

        $year = Request::get('year');

        if (is_null($regionID)) {
            
        }else{
            $db = new dataBase();

            $default = $db->defaultConnection();
        $con = $db->openConnection($default);
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            //$regionID = array($regionID);

            $resp = $sr->getSalesRepByRegionCMAPS($con,$regionID,$year);

            for ($s=0; $s < sizeof($resp); $s++) { 
                echo "<option value='".$resp[$s]["salesRepID"]."' selected='true'> ".$resp[$s]["salesRep"]." </option>";
            }
        }
    }

    public function yearByRegion(){
        $currentMonth = date('m');
        $regionID = 1;
        $cYear = intval(date('Y'));
        $nYear = $cYear + 1;
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;
        $pppYear = $ppYear - 1;

        if($regionID == 1){
            if($currentMonth == 12){
                $year = array($cYear,$nYear,$pYear,$ppYear,$pppYear);           
            }else{
                $year = array($cYear,$pYear,$ppYear,$pppYear);           
            }
        }else{
            if($currentMonth == 12){
                $year = array($cYear,$nYear,$pYear);
            }else{
                $year = array($cYear,$pYear);                
            }
        }


        for ($y=0; $y < sizeof($year); $y++) { 
            if($y == 0){
                echo "<option selected='true' value='".$year[$y]."'> ".$year[$y]." </option>";    
            }else{
                echo "<option value='".$year[$y]."'> ".$year[$y]." </option>";    
            }   
        }        

    }

    public function yearOnFcst(){
        
        $cYear = intval(date('Y'));
        $pYear = $cYear - 1 ;
        $nYear = $cYear + 1;

        $years = array($cYear,$pYear,$nYear);

        for ($y=0; $y < sizeof($years); $y++) { 
            echo "<option value='".$years[$y]."'> ".$years[$y]." </option>";    
        }      

    }

    public function firstPosByRegion(){

        $form = Request::get("form");

        
        if($form == "ytd"){
            $showForm = "BKGS";
        }elseif($form == "cmaps"){
            $showForm = "CMAPS";
        }elseif($form == "mini_header"){
            $showForm = "HEADER";
        }else{
            $showForm = false;
        }
        

        echo "<select name='font' style='width:100%;'>";
            echo "<option value='$form'> $showForm </option>";
        echo "</select>";   
    }

    public function secondPosByRegion(){

        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $source = $sql->select($con, "DISTINCT source", "plan_by_brand");
        $valueSource = $sql->fetch($source, array("source"), array("source"));
        
        echo "<select id='firstPos' value='firstPos' style='width:100%;'>";
        for ($i=0; $i < sizeof($valueSource); $i++) {
            $showSource = strtolower($valueSource[$i]['source']);
            $showSource = ucfirst($showSource);
            if ($valueSource[$i]['source'] != 'ACTUAL') {
                if($valueSource[$i]['source'] == "TARGET"){
                    echo "<option value='".$valueSource[$i]['source']."' selected='true'> ".$showSource." </option>";
                }else{
                    echo "<option value='".$valueSource[$i]['source']."'> ".$showSource." </option>";
                } 
            }
        }
        echo "</select>";  
    }

    public function thirdPosByRegion(){
        
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $regionID = Request::get("regionID");

        $regions = new region();
        $region = $regions->getRegion($con, array($regionID));

        $renderYoY = new renderYoY();

        $renderYoY->sourceYoY($region[0]['name']);
    }


    public function salesRepGroupByRegion(){
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $regionID = array(Request::get('regionID'));
        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
        $regionIDUser = Request::session()->get('regionID');
        $userLevel = Request::session()->get('userLevel');
        $special = Request::session()->get('special');

        if (!is_null($special) && $regionIDUser != $regionID[0]) {
            if($salesRepGroup){
                for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
                    echo "<option value='".$salesRepGroup[$s]["id"]."' selected='true'>"
                        .$salesRepGroup[$s]["name"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. Groups for this region. </option>";
            }
        }else{
           
            if ($userLevel == "L3" || $userLevel == "L4") {

                $groupID = Request::session()->get('userSalesRepGroupID');
                $groupName = Request::session()->get('userSalesRepGroup');

                $ier = date('Y');
                echo "<option value='".$groupID."' selected='true'>".$groupName."</option>";

                if( Request::session()->get('userName') == "João Romano" && $ier == 2019 ){
                    echo "<option value='4' selected='true'>PME</option>";
                }
            }else{
                if($salesRepGroup){
                    for ($s=0; $s < sizeof($salesRepGroup); $s++) { 
                        echo "<option value='".$salesRepGroup[$s]["id"]."' selected='true'>"
                            .$salesRepGroup[$s]["name"].
                        "</option>";
                    }
                }else{
                    echo "<option value=''> There is no Sales Rep. Groups for this region. </option>";
                }
            }
        }        

        
    }

    public function salesRepByRegionFiltered(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $year = Request::get('year');        
        $userLevel = Request::session()->get('userLevel');
        $special = Request::session()->get('special');
        $userRegionID = Request::session()->get('userRegionID');
        $userSalesRepGroupID = Request::session()->get('userSalesRepGroupID');

        $salesRepGroupID = $sr->getSalesRepGroup($con,array($regionID));
        for ($s=0; $s <sizeof($salesRepGroupID); $s++) { 
            $salesRepGroupID[$s] = $salesRepGroupID[$s]['id'];
        }

        $salesRep = $sr->getSalesRep($con,$salesRepGroupID);
        $salesRep = $sr->getSalesRepStatus($con,$salesRep,$year);
        $salesRepL3 = $sr->getSalesRepByGroup($con,$userSalesRepGroupID,$year);

        
        if ($salesRep) {
            echo "<option selected='true' value=''>Select Sales Rep.</option>";
        }

        if ($userLevel == "L4") {
            $userName = Request::session()->get('userName');
            $performanceName = Request::session()->get('performanceName');
            $check = false;            
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                if (!is_null($performanceName)) {
                    if($salesRep[$s]["salesRep"] == $performanceName){
                        echo "<option value='".$salesRep[$s]["id"]."' > ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }else{
                    setlocale(LC_ALL, "en_US.utf8");
                    $output = iconv("utf-8", "ascii//TRANSLIT", $userName);
                    if( strpos($salesRep[$s]["salesRep"], $output)  !== false){
                    //if($salesRep[$s]["salesRep"] == $userName){
                        echo "<option value='".$salesRep[$s]["id"]."' > ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }
            }
            if (!$check) {
                echo "<option value=''> Sales Rep Not Found </option>";
            }
        }else if($userLevel == "L6"){

            if($regionID == $userRegionID){
                $userName = Request::session()->get('userName');
                $performanceName = Request::session()->get('performanceName');
                $check = false; 
                for ($s=0; $s <sizeof($salesRep) ; $s++) {  
                    $salesRepWithNoSpecialCharacters = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$salesRep[$s]['salesRep']);
                    $salesRepWithNoSpecialCharacters1 = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$userName);
                    if($salesRepWithNoSpecialCharacters == $salesRepWithNoSpecialCharacters1){                        
                        echo "<option value='".$salesRep[$s]["id"]."' > ".$salesRep[$s]["salesRep"]." </option>";
                    }
                }
            }else{
                if($salesRep){
                    for ($s=0; $s < sizeof($salesRep); $s++) { 
                        echo "<option value='".$salesRep[$s]["id"]."'>"
                            .$salesRep[$s]["salesRep"].
                        "</option>";
                    }
                }else{
                    echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
                }   
            }
        }else if($userLevel == "L3"){
            if($salesRepL3){
                for ($s=0; $s < sizeof($salesRepL3); $s++) { 
                    echo "<option value='".$salesRepL3[$s]["ID"]."'>"
                        .$salesRepL3[$s]["name"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
            }   
        }else{

            if($salesRep){
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    echo "<option value='".$salesRep[$s]["id"]."'>"
                        .$salesRep[$s]["salesRep"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
            }
        }
    }

    public function salesRepByRegionFilteredMult(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $year = Request::get('year');        
        $userLevel = Request::session()->get('userLevel');
        $special = Request::session()->get('special');
        $salesRepGroupID = $sr->getSalesRepGroup($con,array($regionID));
        for ($s=0; $s <sizeof($salesRepGroupID); $s++) { 
            $salesRepGroupID[$s] = $salesRepGroupID[$s]['id'];
        }

        $salesRep = $sr->getSalesRep($con,$salesRepGroupID);
        $salesRep = $sr->getSalesRepStatus($con,$salesRep,$year);

        if ($userLevel == "L4") {
            $userName = Request::session()->get('userName');
            $performanceName = Request::session()->get('performanceName');
            $check = false;            
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                if (!is_null($performanceName)) {
                    if($salesRep[$s]["salesRep"] == $performanceName){
                        echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }else{
                    setlocale(LC_ALL, "en_US.utf8");
                    $output = iconv("utf-8", "ascii//TRANSLIT", $userName);
                    if( strpos($salesRep[$s]["salesRep"], $output)  !== false){
                    //if($salesRep[$s]["salesRep"] == $userName){
                        echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }
            }
            if (!$check) {
                echo "<option value=''> Sales Rep Not Found </option>";
            }
        }else{
            if($salesRep){
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    echo "<option value='".$salesRep[$s]["id"]."' selected='true'>"
                        .$salesRep[$s]["salesRep"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
            }
        }
    }


    public function salesRepBySalesRepGroup(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $salesRepGroupID = Request::get('salesRepGroupID');         
        $year = Request::get('year');        
        $source = Request::get('source');
        $userLevel = Request::session()->get('userLevel');
        $special = Request::session()->get('special');
        $regionIDUser = Request::session()->get('regionID');

        $salesRep = $sr->getSalesRep($con,$salesRepGroupID);
        $salesRep = $sr->getSalesRepStatus($con,$salesRep,$year);
        
        if ($userLevel == "L4") {
            $userName = Request::session()->get('userName');
            $performanceName = Request::session()->get('performanceName');
            $check = false;            
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                if (!is_null($performanceName)) {
                    if($salesRep[$s]["salesRep"] == $performanceName){
                        echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }else{
                    setlocale(LC_ALL, "en_US.utf8");
                    $output = iconv("utf-8", "ascii//TRANSLIT", $userName);
                    if( strpos($salesRep[$s]["salesRep"], $output)  !== false){
                    //if($salesRep[$s]["salesRep"] == $userName){
                        echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                        $check = true;
                    }
                }
            }
            if (!$check) {
                echo "<option value=''> Sales Rep Not Found </option>";
            }
        }else{

            if($salesRep){
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    echo "<option value='".$salesRep[$s]["id"]."' selected='true'>"
                        .$salesRep[$s]["salesRep"].
                    "</option>";
                }
            }else{
                echo "<option value=''> There is no Sales Rep. for this Sales Rep. Group. </option>";
            }
        }
    }

    public function currencyByRegion(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $pr = new pRate();
        $regionID = array(Request::get('regionID'));
        
        $currency = $pr->getCurrencyByRegion($con,$regionID);

        if ($currency) {
            for ($c=0; $c <sizeof($currency); $c++) {
                if ($currency[$c]["name"] != "USD" && $currency[$c]['id'] <= 6) {
                    echo "<option value='".$currency[$c]["id"]."' selected='true'>".$currency[$c]["name"]."</option>";
                }
            }
            echo "<option value='4'>USD</option>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
        //echo $regionID;
    }

    public function newCurrencyByRegion(){
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $pr = new pRate();
        $regionID = array(Request::get('regionID'));
        
        $currency = $pr->getCurrencyByRegion($con,$regionID);

        if ($currency) {
            for ($c=0; $c <sizeof($currency); $c++) {
                if ($currency[$c]["name"] != "USD" && $currency[$c]['id'] <= 6) {
                    echo "<option value='".$currency[$c]["name"]."'>".$currency[$c]["name"]."</option>";
                }
            }
            echo "<option value='4'>USD</option>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
        //echo $regionID;
    }

    public function newSourceByRegion(){
        $region = Request::get('regionID');


        if ($region == 1) {
            $source = array("WBD"/*,"CMAPS","BTS","ALEPH"*/);            
        }


        echo "<option value=''> Select </option>";
        for ($s=0; $s < sizeof($source); $s++) { 
            echo "<option value='".$source[$s]."' selected='true'>".$source[$s]."</option>";
        }
    }
    public function platform(){
        
        $platform = array("Pay Tv", "Digital"/*,"CMAPS","BTS","ALEPH"*/);            

        echo "<option value=''> Select </option>";
        for ($s=0; $s < sizeof($platform); $s++) { 
            echo "<option value='".$platform[$s]."' selected='true'>".$platform[$s]."</option>";
        }
    }

    public function sourceByRegion(){
        $region = Request::get('regionID');
        if ($region == 1) {
            echo "<option value='IBMS'> BOOKINGS </option>";
            echo "<option value='CMAPS'> CMAPS </option>";
        }else{
            echo "<option value='IBMS'> BOOKINGS </option>";
        }
    }

    public function valueBySource(){
        $source = Request::get('source');
        //var_dump($source);

        if ($source == "mini_header" || $source == "Header") {
            echo "<option value='gross'> Gross </option>";
        }else{
            echo "<option value='gross'> Gross </option>";
            echo "<option value='net'> Net </option>";
        }    
    }

    public function typeByRegion(){

        $bool = Request::get("bool");
        $region = Request::get("regionID");
        
        echo "<option value=''> Select </option>";

        if ($bool == "false") {
            echo "<option value='agencyGroup'> Agency Group </option>";    
        }
        
        echo "<option value='agency'> Agency </option>";
        echo "<option value='client'> Client </option>";

        if ($bool == "true" && $region == 1) {
            echo "<option value='sector'> Sector </option>";
            echo "<option value='category'> Category </option>";
        }
    }

    public function typeByRegionBV(){       
        echo "<option value=''> Select </option>";
        echo "<option value='agencyGroup'> Agency Group </option>";    
        echo "<option value='agency'> Agency </option>";
    }

    public function company(){
        echo "<select id='company' class='selectpicker form-control' data-selected-text-format='count' multiple='true'  multiple data-actions-box='true' data-size='2' data-width='100%' >";
            echo "<option value='1' selected='true'> DSC </option>";   
            echo "<option value='2' selected='true'> SPT </option>";
            echo "<option value='3' selected='true'> WM </option>";   
        echo "</select>";
    }

    public function firstPosYear(){
        
        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1, $cYear-2);

        for ($y=0; $y < sizeof($years); $y++) { 
            echo "<option value='".$years[$y]."'>".$years[$y]. "</option>";
        }
    }

    public function secondPosYear(){
        
        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1, $cYear-2);

        for ($y=0; $y < sizeof($years); $y++) { 
            if ($y == 1) {
                echo "<option value='".$years[$y]."' selected='true'>".$years[$y]. "</option>";    
            }else{
                echo "<option value='".$years[$y]."'>".$years[$y]. "</option>";
            }
        }

        echo "<option value='0'> Empty </option>";

    }

    public function thirdPosYear(){
        
        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1, $cYear-2);

        for ($y=0; $y < sizeof($years); $y++) { 
            if ($y == 2) {
                echo "<option value='".$years[$y]."' selected='true'>".$years[$y]. "</option>";    
            }else{
                echo "<option value='".$years[$y]."'>".$years[$y]. "</option>";
            }
        }

        echo "<option value='0'> Empty </option>";

    }

    public function typeNameByType(){
        
        $type = Request::get("type");

        if ($type == "agencyGroup") {
            $resp = substr($type, 0, 6);
            $resp .= " ".substr($type, 6, 5);
        }elseif ($type == "clientGroup") {
            $resp = substr($type, 0, 6);
            $resp .= " ".substr($type, 6, 5);
        }else{
            $resp = $type;
        }

        $resp = ucfirst($resp);

        echo "$resp";
    }

    function typeHandler($con, $name, $group, $region, $year){

        if ($name == "agency") {
            $a = new agency();

            if ($group == 1) {
                $resp = $a->getAgencyGroupByRegion($con, array($region), $year);
                $var = "agencyGroup";
            }else{
                $resp = $a->getAgencyByRegion($con, array($region), $year);
                $var = "agency";
            }

        }else{
            $c = new client();

            $resp = $c->getClientByRegion($con, array($region), $year);
            $var = "client";
            
        }

        for ($n=0; $n < sizeof($resp); $n++) { 
            
            $names[$n]['id'] = $resp[$n]["id"];
            $names[$n]['name'] = $resp[$n][$var];

            if ($name == "agency") {
                $names[$n]['agencyGroup'] = $resp[$n]['agencyGroup'];
            }

        }
        
        $rtr = array_map("unserialize", array_unique(array_map("serialize", $names)));

        return $rtr;
        
    }

    public function type2ByType(){
        
        $name = Request::get("type");
        $region = Request::get("region");
        $year = intval( Request::get("year") );
        $pYear = $year - 1;

        if (is_null($year)) {
            $year = false;        
        }else{
            $year = array($year,$pYear);
        }

        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $fun = substr($name, 0, 6);

        if (strlen($name) > 6) {
            $resp = $this->typeHandler($con, $fun, 1, $region, $year);
        }else{
            $resp = $this->typeHandler($con, $fun, 0, $region, $year);
        }

        for ($r=0; $r < sizeof($resp); $r++) { 
            $auxVal = base64_encode(json_encode($resp[$r]));
            if ($name == "agency") {
                echo "<option selected='true' value='".$auxVal."'>".$resp[$r]['name']." - ".$resp[$r]['agencyGroup']."</option>";
            }else{
                echo "<option selected='true' value='".$auxVal."'>".$resp[$r]['name']."</option>";
            }
            
        }
       
    }

    public function topsByType2(){
        
        $num = Request::get("type2");

        echo "<option selected='true' value='All'>All</option>";

        if (sizeof($num) > 10) {
         
            echo "<option value='10'>10</option>";
            echo "<option value='15'>15</option>";
            echo "<option value='25'>25</option>";
        }
    }

    public function subRanking(){

        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $brands = Request::get("brands");
        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $years = Request::get("years");
        
        $name = Request::get("name");
        $auxName = Request::get("agencyGroup");

        if ($auxName == "-") {
            $auxName = "Others";
        }

        $sr = new subRankings();

        $subValues = $sr->getSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $name, $auxName);
        $matrix = $sr->assembler($subValues, $years, $type);
        
        $mtx = $matrix[0];
        $total = $matrix[1];
        
        if ($type == "agencyGroup") {
            $newType = "agency";
        }elseif ($type == "agency") {
            $newType = "client";
        }else{
            $newType = "agency";
        }

        $sr->renderSubRankings($mtx, $total, $newType, sizeof($mtx[0]));
    }

    public function brandSubRanking(){
        
        $db = new dataBase();   

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $name = Request::get("name");
        
        $brands = Request::get("brands");

        for ($i=0; $i < sizeof($brands); $i++) { 
            if ($brands[$i][1] == "DN") {
                unset($brands[$b]);
            }
        }

        $sbr = new subBrandRanking();
        
        $res = $sbr->getSubResults($con, $type, $region, $value, $months, $currency, $name, $brands);

        if ($type == "agency" || $type == "sector" || $type == "category") {
            $val = "client";
        }else{
            $val = "brand";
        }
        
        $types = array();

        for ($r=0; $r < sizeof($res); $r++) { 
            if (is_array($res[$r])) {
                for ($r2=0; $r2 < sizeof($res[$r]); $r2++) { 
                    if (!in_array($res[$r][$r2][$type], $types)) {
                        array_push($types, $res[$r][$r2][$type]);  
                    }
                }   
            }
        }
        
        $matrix = $sbr->assemble($types, $res, $type);
        $mtx = $matrix[0];
        $total = $matrix[1];
        
        $sbr->renderSubAssembler($mtx, $total, $type, $name, $brands);
    }

    public function marketSubRanking(){
        
        $db = new dataBase();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $brands = Request::get("brands");

        $name = Request::get("name");
        $auxName = Request::get("agencyGroup");

        if ($auxName == "-") {
            $auxName = "Others";
        }

        $sbm = new subMarketRanking();

        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1);

        if ($type == "agency" || $type == "sector" || $type == "category") {
            $val = "client";
        }else{
            $val = "brand";
        }
        
        $values = $sbm->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $name, $val, $auxName);

        if ($type != "client") {

            $base = new base();

            $months2 = array();
            for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
                array_push($months2, $m);
            }

            $valuesTotal = $sbm->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $name, $val, $auxName);
    
        }else{
            $valuesTotal = null;
        }
        
        $matrix = $sbm->subMarketAssembler($values, $valuesTotal, $type, $brands, $val);

        if (is_string($matrix)) {
            $mtx = $matrix;
            $total = false;
        }else{
            $mtx = $matrix[0];
            $total = $matrix[1];
        }
        

        $sbm->renderSubAssembler($mtx, $total, $type, $years);
    }

    public function churnSubRanking(){
        
        $db = new dataBase();   

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $brands = Request::get("brands");
        
        $name = Request::get("name");
        $auxName = Request::get("agencyGroup");

        if ($auxName == "-") {
            $auxName = "Others";
        }

        $scr = new subChurnRanking();

        $year = Request::get('year');

        $cYear = $year;// intval(date('Y'));
        $years = array($cYear, $cYear-1, $cYear-2);

        if ($type == "client") {
            $val = "agency";
        }else{
            $val = "client";
        }
        
        

        $values = $scr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $name, $val, $auxName,$year );
        
        if ($type == "client") {
            $filterType = "agency";
        }else{
            $filterType = "client";
        }

        $finalValues = array();

        for ($v=0; $v < sizeof($values); $v++) { 
            if (is_array($values[$v])) {
                for ($v2=0; $v2 < sizeof($values[$v]); $v2++) { 
                    if ($scr->existInArray($finalValues, $values[$v][$v2][$filterType."ID"], $filterType, true)) {
                        array_push($finalValues, $values[$v][$v2]);
                    }
                }   
            }
        }

        $base = new base();

        $months2 = array();
        for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
            array_push($months2, $m);
        }
        
        $valuesTotal = $scr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $name, $val, $auxName ,$year);
        
        $matrix = $scr->assembler($values, $finalValues, $valuesTotal, $years, $filterType);

        $mtx = $matrix[0];
        $total = $matrix[1];

        $scr->renderSubAssembler($mtx, $total, $type, $years);
    }

    public function newSubRanking(){
        
        $db = new dataBase();   

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $brands = Request::get("brands");
        
        $name = Request::get("name");
        $auxName = Request::get("agencyGroup");

        if ($auxName == "-") {
            $auxName = "Others";
        }

        $snr = new subNewRanking();

        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1);

        if ($type == "client") {
            $val = "agency";
        }else{
            $val = "client";
        }

        $values = $snr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $name, $val, $auxName);

        $finalValues = array();

        for ($v=0; $v < sizeof($values); $v++) { 
            if (is_array($values[$v])) {
                for ($v2=0; $v2 < sizeof($values[$v]); $v2++) { 
                    if ($snr->existInArray($finalValues, $values[$v][$v2][$val."ID"], $val, true)) {
                        array_push($finalValues, $values[$v][$v2]);
                    }
                }   
            }
        }

        $base = new base();

        $months2 = array();
        for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
            array_push($months2, $m);
        }
        
        $valuesTotal = $snr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $name, $val, $auxName);

        $matrix = $snr->assembler($values, $finalValues, $valuesTotal, $years, $val);
        
        $mtx = $matrix[0];
        $total = $matrix[1];
        
        $snr->renderSubAssembler($mtx, $total, $val, $years);
    }
}
