<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\renderYoY;
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
use App\base;

class ajaxController extends Controller{

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
        $con = $db->openConnection("DLA");
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
                            FROM ytd y
                            LEFT JOIN agency a ON a.ID = y.agency_id
                            WHERE (sales_representant_office_id = \"".$regionID."\" )
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
                                FROM ytd y
                                LEFT JOIN client c ON c.ID = y.client_id
                                WHERE (sales_representant_office_id = \"".$regionID."\" )
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
                            FROM agency a
                            LEFT JOIN agency ag ON ag.ID = a.agency_group_id
                            WHERE (a.agency_group_id = \"".$baseFilter->id."\" )
                            
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
                echo "<option value=\"".$second[$s][$showID]."\" selected='true'>".$second[$s][$showName]."</option>";
            }
        }else{
            echo "<option value='' selected='true'> No Values Found !!! </option>";
        }

    }

    public function baseFilter(){
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $bs = new base();
        $type = Request::get('type');
        $regionID = Request::get('region');
        switch ($type) {

            case 'client':
                $clss  = new client();
                $base = $clss->getClientByRegion($con,array($regionID));
                $tralala = "clientGroup";
                break;
            default:
                $clss = new agency();
                if($type == "agency"){
                    $base = $clss->getAgencyByRegion($con,array($regionID));
                    $tralala = "agencyGroup";
                }else{
                    $base = $clss->getAgencyGroupByRegion($con,array($regionID));
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
                if($type != "client"){
                    echo " - ".$base[$b][$tralala];
                }
                echo "</option>";
            }
        }
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
            $con = $db->openConnection("DLA");

            $b = new brand();
            $brands = $b->getBrand($con);
            for ($t=0; $t < sizeof($tiers); $t++) {
                for ($b=0; $b < sizeof($brands); $b++) { 
                    $value[$b] = base64_encode(json_encode(array($brands[$b]['id'],$brands[$b]['name'])));
                    if ($tiers[$t] == 'T1') {
                        if ($brands[$b]['name'] == 'DC' || $brands[$b]['name'] == 'HH' || $brands[$b]['name'] == 'DK'){
                            echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";            
                        }
                    }elseif ($tiers[$t] == 'T2') {
                        if ($brands[$b]['name'] == 'AP' || $brands[$b]['name'] == 'TLC' || $brands[$b]['name'] == 'ID' || $brands[$b]['name'] == 'DT' || $brands[$b]['name'] == 'FN' || $brands[$b]['name'] == 'ONL' || $brands[$b]['name'] == 'VIX' || $brands[$b]['name'] == 'HGTV'){
                                echo "<option selected='true' value='".$value[$b]."'>".$brands[$b]['name']."</option>";               
                        }
                    }else{
                        if ($brands[$b]['name'] == "OTH") {
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
            $con = $db->openConnection("DLA");
            $cYear = intval(date('Y'));
            $sr = new salesRep();

            $regionID = array($regionID);

            $resp = $sr->getSalesRepByRegion($con,$regionID,true,$cYear);

            echo "<option selected='true'>Select Sales Rep.</option>";

            for ($s=0; $s <sizeof($resp) ; $s++) { 
                echo "<option value='".$resp[$s]["id"]."'> ".$resp[$s]["salesRep"]." </option>";
            }
        }
    }

    public function yearByRegion(){
        
        $regionID = Request::get('regionID');
        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;
        if($regionID == 1){
            $year = array($cYear,$pYear);           
        }else{
            $year = array($cYear);
        }
        for ($y=0; $y < sizeof($year); $y++) { 
            if($y == 0){
                echo "<option selected='true' value='".$year[$y]."'> ".$year[$y]." </option>";    
            }else{
                echo "<option value='".$year[$y]."'> ".$year[$y]." </option>";    
            }   
        }        

    }

    public function firstPosByRegion(){

        $form = Request::get("form");

        
        if($form == "ytd"){
            $showForm = "IBMS";
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
        $con = $db->openConnection("DLA");

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
        $con = $db->openConnection("DLA");

        $regionID = Request::get("regionID");

        $regions = new region();
        $region = $regions->getRegion($con, array($regionID));

        $renderYoY = new renderYoY();

        $renderYoY->sourceYoY($region[0]['name']);
    }


    public function salesRepGroupByRegion(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $regionID = array(Request::get('regionID'));
        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con,$regionID);
        $userLevel = Request::session()->get('userLevel');

        if ($userLevel == "L3" || $userLevel == "L4") {
            $groupID = Request::session()->get('userSalesRepGroupID');
            $groupName = Request::session()->get('userSalesRepGroup');
            echo "<option value='".$groupID."' selected='true'>".$groupName."</option>";
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

    public function salesRepBySalesRepGroup(){
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $sr = new salesRep();
        $regionID = Request::get('regionID');

        $salesRepGroupID = Request::get('salesRepGroupID');         
        $year = Request::get('year');        
        $source = Request::get('source');
        $userLevel = Request::session()->get('userLevel');

        $salesRep = $sr->getSalesRep($con,$salesRepGroupID);

        $salesRep = $sr->getSalesRepStatus($con,$salesRep,$year);
        if ($userLevel == "L4") {
            $userName = Request::session()->get('userName');
            $check = false;            
            for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                if($salesRep[$s]["salesRep"] == $userName){
                    echo "<option value='".$salesRep[$s]["id"]."' selected='true'> ".$salesRep[$s]["salesRep"]." </option>";
                    $check = true;
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
        $con = $db->openConnection('DLA');
        
        $pr = new pRate();
        $regionID = array(Request::get('regionID'));
        
        $currency = $pr->getCurrencyByRegion($con,$regionID);

        if ($currency) {
            for ($c=0; $c <sizeof($currency); $c++) {
                if ($currency[$c]["name"] != "USD" && $currency[$c]['id'] <= 6) {
                    echo "<option value='".$currency[$c]["id"]."'>".$currency[$c]["name"]."</option>";
                }
            }
            echo "<option value='4'>USD</option>";
        }else{
            echo "<option value=''> There is no Currency for this Region </option>";
        }
        //echo $regionID;
    }

    public function sourceByRegion(){
        $region = Request::get('regionID');
        if ($region == 1) {
            echo "<option value='IBMS'> IBMS </option>";
            echo "<option value='CMAPS'> CMAPS </option>";
        }else{
            echo "<option value='IBMS'> IBMS </option>";
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
        var_dump($bool);
        echo "<option value=''> Select </option>";

        if ($bool == "false") {
            echo "<option value='agencyGroup'> Agency Group </option>";
        }

        echo "<option value='agency'> Agency </option>";
        echo "<option value='client'> Client </option>";

        if ($bool == "true") {
            echo "<option value='products'> Products </option>";            
        }
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

        echo "<option value='0'> EMPTY </option>";

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

        echo "<option value='0'> EMPTY </option>";

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

    function typeHandler($con, $name, $group, $region){

        if ($name == "agency") {
            $a = new agency();

            if ($group == 1) {
                $resp = $a->getAgencyGroupByRegion($con, array($region));
                $var = "agencyGroup";
            }else{
                $resp = $a->getAgencyByRegion($con, array($region));
                $var = "agency";
            }

        }else{
            $c = new client();

            $resp = $c->getClientByRegion($con, array($region));
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
        
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $fun = substr($name, 0, 6);

        if (strlen($name) > 6) {
            $resp = $this->typeHandler($con, $fun, 1, $region);
        }else{
            $resp = $this->typeHandler($con, $fun, 0, $region);
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
        $con = $db->openConnection("DLA");

        $brands = Request::get("brands");
        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $years = Request::get("years");
        $name = Request::get("name");
        $pos = Request::get("pos");

        $sr = new subRankings();

        $subValues = $sr->getSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $name);
        $matrix = $sr->assembler($subValues, $years, $type);

        $mtx = $matrix[0];
        $total = $matrix[1];

        if ($type == "agencyGroup") {
            $newType = "agency";
        }elseif ($type == "agency") {
            $newType = "client";
        }else{
            $newType = "client";
        }

        $sr->renderSubRankings($mtx, $total, $newType, sizeof($mtx[0]));
    }

    public function brandSubRanking(){
        
        $db = new dataBase();   
        $con = $db->openConnection("DLA");

        $type = Request::get("type");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $name = Request::get("name");

        $sbr = new subBrandRanking();

        $res = $sbr->getSubResults($con, $type, $region, $value, $months, $currency, $name);

        $types = array();

        for ($r=0; $r < sizeof($res); $r++) { 
            for ($r2=0; $r2 < sizeof($res[$r]); $r2++) { 
                if (!in_array($res[$r][$r2][$type], $types)) {
                    array_push($types, $res[$r][$r2][$type]);  
                }
            }
        }

        $matrix = $sbr->assemble($types, $res, $type);
        
    }
}
