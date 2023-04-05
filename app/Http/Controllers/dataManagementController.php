<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;

use App\agency;
use App\client;
use App\brand;
use App\region;
use App\User;
use App\queries;
use App\salesRep;
use App\origin;
use App\matchingClientAgency;
use App\sql;
use App\pRate;
use App\emailDivulgacao;
use App\import;
use App\bvBand;
use App\chain;
use App\excel;

class dataManagementController extends Controller{

    public function insertPayTV(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $i = new import();
        $bb = new bvBand();

        $spreadSheet = $i->base();
        $table = Request::get('table');
        $columns = $bb->columnPayTv;
        $year = date("Y")-1;

        unset($spreadSheet[0]);
        $spreadSheet = array_values($spreadSheet);

        for ($i=0; $i <sizeof($spreadSheet); $i++) { 
            
            $spreadSheet[$i][1] = (str_replace("%","",$spreadSheet[$i][1] ))/100;
            
            
            $ins[$i] = "INSERT INTO $table (station,percentage,year)
                    VALUES  (\"".$spreadSheet[$i][0]."\",
                            \"".$spreadSheet[$i][1]."\",
                                $year)";

            if($con->query($ins[$i]) === TRUE ){
                $error = false;
                //var_dump($ins);
            }else{
                var_dump($spreadSheet[$i]);            
                echo "<pre>".($ins[$i])."</pre>";
                var_dump($con->error);
                $error = true;
            }     

            
        }
        
            
        return back()->with('paytvSuccess',"The Excel Data Was Succesfully Inserted :)");
        
    }

    public function insertCurrentTarget(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $conFM = $db->openConnection('firstmatch');
        $i = new import();
        $bb = new bvBand();

        $spreadSheet = $i->base();
        $table = Request::get('table');
        $columns = $bb->columnCurrentTarget;
        $year = date("Y")-1;

        unset($spreadSheet[0]);
        $spreadSheet = array_values($spreadSheet);
        //var_dump($table);
        for ($i=0; $i <sizeof($spreadSheet); $i++) {                         
            
            $insFM[$i] = "INSERT INTO $table (agency_group,dsc_target,spt_target,year,company)
                    VALUES  (\"".$spreadSheet[$i][0]."\",
                            \"".$spreadSheet[$i][1]."\",
                            \"".$spreadSheet[$i][2]."\",
                            \"".$spreadSheet[$i][3]."\",
                            \"".$spreadSheet[$i][4]."\")";

            $conFM->query($insFM[$i]);
        }


        $select = "SELECT * FROM $table";

        $res = $conFM->query($select);

        $from = array('agency_group','dsc_target','spt_target','year','company');

        $list = $sql->fetch($res,$from,$from);
        for ($l=0; $l < sizeof($list); $l++) { 
            $seek[$l] = "SELECT agency_group_id FROM agency_group_unit WHERE (name = '".$list[$l]['agency_group']."')";

            $resultSeek[$l] = $con->query($seek[$l]);
            $from = array('agency_group_id');
            $agencyGroupSeek[$l] = $sql->fetch($resultSeek[$l],$from,$from)[0]['agency_group_id'];
            //var_dump($agencyGroupSeek);

            $ins[$l] = "INSERT INTO $table (agency_group_id,dsc_target,spt_target,year,company) 
                                            VALUES (\"".$agencyGroupSeek[$l]."\",
                                                  \"".$list[$l]['dsc_target']."\",
                                                  \"".$list[$l]['spt_target']."\",
                                                  \"".$list[$l]['year']."\",
                                                  \"".$list[$l]['company']."\")";
        
            if($con->query($ins[$l])===TRUE){
                $error = false;
            }else{
                $temp = "Error: " . $sql . "<br>" . $con->error;

                var_dump($agencyGroupSeek[$l]);
                var_dump($temp);
                var_dump($seek[$l]);
                var_dump($agencyGroupSeek[$l]);
                var_dump($ins[$l]);
            } 

        }    
        
        //var_dump($ins);
        return back()->with('targetSuccess',"The Excel Data Was Succesfully Inserted :)");
    }
    

    public function insertMonthTarget(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $conFM = $db->openConnection('firstmatch');
        $i = new import();
        $bb = new bvBand();

        $spreadSheet = $i->base();
        $table = Request::get('table');
        $columns = $bb->columnCurrentTarget;
        $year = date("Y")-1;

        unset($spreadSheet[0]);
        $spreadSheet = array_values($spreadSheet);
        //var_dump($table);
        for ($i=0; $i <sizeof($spreadSheet); $i++) {                         
            
            $insFM[$i] = "INSERT INTO $table (agency_group,dsc_target,spt_target,year,company)
                    VALUES  (\"".$spreadSheet[$i][0]."\",
                            \"".$spreadSheet[$i][1]."\",
                            \"".$spreadSheet[$i][2]."\",
                            \"".$spreadSheet[$i][3]."\",
                            \"".$spreadSheet[$i][4]."\")";

            $conFM->query($insFM[$i]);
        }


        $select = "SELECT * FROM $table";

        $res = $conFM->query($select);

        $from = array('agency_group','dsc_target','spt_target','year','company');

        $list = $sql->fetch($res,$from,$from);
        for ($l=0; $l < sizeof($list); $l++) { 
            $seek[$l] = "SELECT agency_group_id FROM agency_group_unit WHERE (name = '".$list[$l]['agency_group']."')";

            $resultSeek[$l] = $con->query($seek[$l]);
            $from = array('agency_group_id');
            $agencyGroupSeek[$l] = $sql->fetch($resultSeek[$l],$from,$from)[0]['agency_group_id'];
            var_dump($agencyGroupSeek);

            $ins[$l] = "INSERT INTO $table (agency_group_id,dsc_target,spt_target,year,company) 
                                            VALUES (\"".$agencyGroupSeek[$l]."\",
                                                  \"".$list[$l]['dsc_target']."\",
                                                  \"".$list[$l]['spt_target']."\",
                                                  \"".$list[$l]['year']."\",
                                                  \"".$list[$l]['company']."\")";
        
            if($con->query($ins[$l])===TRUE){
                $error = false;
            }else{
                $temp = "Error: " . $sql . "<br>" . $con->error;

                var_dump($agencyGroupSeek[$l]);
                var_dump($temp);
                var_dump($seek[$l]);
                var_dump($agencyGroupSeek[$l]);
                var_dump($ins[$l]);
            } 

        }    
        
        return back()->with('targetMonthSuccess',"The Excel Data Was Succesfully Inserted :)");
    }

    public function insertBvBandAfterCheck(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $conFM = $db->openConnection('firstmatch');

        $insert = json_decode(base64_decode(Request::get('insert')));

        $delete = "DELETE FROM bv_band";

        $deleted = false;

        if($conFM->query($delete) === TRUE) {
            $deleted = true;
        }

        if($deleted){

            $count = 0;
            $check = false;

            for ($i=0; $i < sizeof($insert); $i++) { 
                if($conFM->query($insert[$i]) === TRUE) {
                    $count ++;
                }else{
                    echo "Error deleting record: " . $con->error;
                }
            }

            if($count == sizeof($insert)){
                $check = true;
            }

            if($check){

                $select = "SELECT * FROM bv_band";

                $res = $conFM->query($select);

                $from = array('agency_group','from_value','to_value','percentage','year','company','platform');

                $list = $sql->fetch($res,$from,$from);

                $cc = 0;

                for ($l=0; $l < sizeof($list); $l++) { 
                    $seek[$l] = "SELECT agency_group_id FROM agency_group_unit WHERE (name = '".$list[$l]['agency_group']."')";

                    $resultSeek[$l] = $con->query($seek[$l]);
                    $from = array('agency_group_id');
                    $agencyGroupSeek[$l] = $sql->fetch($resultSeek[$l],$from,$from)[0]['agency_group_id'];
                    $list[$l]['agency_group_id'] = $agencyGroupSeek[$l];

                    $insertSeek[$l] = "INSERT INTO bv_band (agency_group_id,from_value,to_value,percentage,year,company,platform) 
                                            VALUES (\"".$list[$l]['agency_group_id']."\",
                                                  \"".$list[$l]['from_value']."\",
                                                  \"".$list[$l]['to_value']."\",
                                                  \"".$list[$l]['percentage']."\",
                                                  \"".$list[$l]['year']."\",
                                                  \"".$list[$l]['company']."\",
                                                  \"".$list[$l]['platform']."\")";

                    
                    if($con->query($insertSeek[$l])===TRUE){
                        $cc++;
                    }else{
                        $temp = "Error: " . $sql . "<br>" . $con->error;

                        var_dump($agencyGroupSeek[$l]);
                        var_dump($temp);
                        var_dump($seek[$l]);
                        var_dump($agencyGroupSeek[$l]);
                        var_dump($insertSeek[$l]);
                    }


                    

                }

                if($cc == sizeof($list)){
                    var_dump("TODOS OS DADOS INSERIDOS");
                }

            }

        }



    }

    public function insertAgencyGroupBV(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $size = Request::get('size');
        var_dump($size);

        $count = 0;

        for ($s=0; $s < $size; $s++) { 
            if( !is_null(Request::get("agencyGroup$s")) ){
                $agency[$count]['group'] = Request::get("agencyGroup$s");
                $agency[$count]['groupUnit'] = Request::get("agencyGroupUnit$s");
                $count++;
            }
        }

        $check = 0;

        for ($a=0; $a < sizeof($agency); $a++) { 
            $insert[$a] = "INSERT INTO agency_group_unit (agency_group_id,name) 
                                  VALUES (\"".$agency[$a]['group']."\",
                                          \"".$agency[$a]['groupUnit']."\")";

            if($con->query($insert[$a]) === TRUE){
                $check++;
            }else{
                $temp = "Error: " . $sql . "<br>" . $conn->error;
                var_dump($temp);
            }            
        }

        if($check == sizeof($insert)){
            var_dump("VALORES INSERIDOS");
        }
    }

    public function agencyGroupCheck(){
        $db = new dataBase();
        $sql = new sql();
        $conFM = $db->openConnection('firstmatch');
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $ag = new agency();
        $table = 'bv_band';
        $from = array("agency_group");

        $agencyG = $ag->getAgencyGroupBrazil($con);
        $select = "SELECT DISTINCT agency_group FROM $table ORDER BY agency_group ASC";

        $res = $conFM->query($select);

        $tmp = $sql->fetch($res,$from,$from);

        for ($t=0; $t < sizeof($tmp); $t++){ 
            $list[$t] = $tmp[$t]['agency_group'];
            $seek[$t] = "SELECT name FROM agency_group_unit WHERE (name = '".$list[$t]."')";
            $resCheck[$t] = $con->query($seek[$t]);
            if($resCheck[$t]->num_rows > 0){
                $check[$t] = true;
            }else{
                $check[$t] = false;
            }
        }   
        $countCheck = 0;
        for ($c=0; $c < sizeof($check) ; $c++) { 
            if($check[$c]){
                $countCheck ++;
            }
        }

        if($countCheck == sizeof($check)){
            $checkCheck = false;
        }else{
            $checkCheck = true;
        }


        if($checkCheck){
            for ($t=0; $t < sizeof($tmp); $t++){ 
                if(!$check[$t]){
                    echo "<div class='row justify-content-center mt-2'>";
                        echo "<div class='col'>";
                            echo "<select class='form-control' name='agencyGroup$t' data-live-search='true'>";
                                echo "<option value=''> Select </option>";
                                for ($aa=0; $aa < sizeof($agencyG); $aa++) { 
                                    
                                    echo "<option value=".$agencyG[$aa]['id'].">".$agencyG[$aa]['agencyGroup']."</option>";
                                } 
                            echo "</select>";
                        echo "</div>";           
                        echo "<div class='col'>";         
                            echo "<input type='text' class='form-control' name='agencyGroupUnit$t' value='".$list[$t]."'>";        
                        echo "</div>";
                    echo "</div>";
                }
            }
            echo "<div class='row justify-content-center mt-2'>";
                echo "<div class='col'>";
                    echo "<input type='text' class='form-control' name='size' value='".$t."'>";        
                echo "</div>";
            echo "</div>";
            echo "<input type='hidden' value='0' id='forward' class='form-control' style='width: 100%;'>";
        }else{
            echo "<div class='row justify-content-center mt-2'>";
                echo "<div class='col'>";
                    echo "Não existem Grupos de Agência a serem criados !";        
                echo "</div>";
            echo "</div>";
            echo "<input type='hidden' value='1' id='forward' class='form-control' style='width: 100%;'>";

        }


    }

    public function insertBvBandG(){
        return view('dataManagement.insert.bvBandGet');
    }

    public function insertBvBandP(){

        $bb = new bvBand();
        $i = new import();
        $chain = new chain();
        $db = new dataBase();
        
        $con = $db->openConnection('firstmatch');

        $table = "bv_band";

        $spreadSheet = $i->base();
        unset($spreadSheet[0]);
        $spreadSheet = array_values($spreadSheet);

        $column = $bb->column;
        $into = $chain->into($column); 

        $mtx = $bb->workOnSheet($spreadSheet);        
        
        $mtx = $bb->fixColumnWorkSheet($column,$mtx);
        //var_dump($mtx);
        $count = 0;

        for ($m=0; $m < sizeof($mtx); $m++) { 
           // var_dump($column);
            $values[$m] = $chain->values($mtx[$m],$column);
            //var_dump($column);
            $ins[$m] = "INSERT INTO $table ($into) VALUES (".$values[$m].")";
            
            if($con->query($ins[$m]) === TRUE ){
                $count ++;   
            }else{
                var_dump($spreadSheet[$m]);
                var_dump($ins[$m]);
                echo "<pre>".($ins[$m])."</pre>";
                var_dump($con->error);
            } 
            
        }

        if($count == sizeof($mtx)){
            return view('dataManagement.insert.bvBandPost',compact('ins'));
        }
    }


    public function dataCurrentThroughtG(){
        
        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $sql = new sql();

        $select = "SELECT * FROM sources_date";

        $res = $con->query($select);
        $from = array("source","current_throught");

        $list = $sql->fetch($res,$from,$from);

        for ($l=0; $l < sizeof($list); $l++) { 
            if($list[$l]['source'] == "CMAPS"){
                $cmaps = $list[$l]['current_throught'];
            }elseif($list[$l]['source'] == "SF"){
                $sf = $list[$l]['current_throught'];
            }elseif($list[$l]['source'] == "FW"){
                $fw = $list[$l]['current_throught'];
            }elseif ($list[$l]['source'] == 'INSIGHTS') {
                $insights = $list[$l]['current_throught'];
            }elseif ($list[$l]['source'] == 'ALEPH / WBD') {
                $aleph = $list[$l]['current_throught'];
            }else{
                $bts = $list[$l]['current_throught'];
            }
        }

        $newList = array("cmaps" => $cmaps, "bts" => $bts, "fw" => $fw, "sf" => $sf, "insights" => $insights, "aleph" => $aleph);

        return view('dataManagement.dataCurrentThrought',compact('newList'));
    }

    public function dataCurrentThroughtP(){
        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cmapsInfo = Request::get('cmapsInfo');
        $crmInfo = Request::get('crmInfo');
        $freeWheelInfo = Request::get('freeWheelInfo');
        $btsInfo = Request::get('btsInfo');
        $insightsInfo = Request::get('insightsInfo');
        $alephInfo = Request::get('alephInfo');

        $list = array( 
                        array("name" => "BTS","value" => $btsInfo),
                        array("name" => "CMAPS","value" => $cmapsInfo),
                        array("name" => "FW","value" => $freeWheelInfo),
                        array("name" => "SF","value" => $crmInfo),
                        array('name' => "INSIGHTS","value" => $insightsInfo),
                        array('name' => "ALEPH / WBD","value" => $alephInfo)

        );

        $count = 0;
        $error = array();
        for ($l=0; $l < sizeof($list); $l++) { 
            $up[$l] = "UPDATE sources_date SET current_throught = \"".$list[$l]['value']."\" WHERE (source = \"".$list[$l]['name']."\") ";
            
            if( $con->query($up[$l]) === TRUE ){
                echo "Record updated successfully <br>";
                $count ++;
            }else{
                $err = "Error updating record: " . $con->error;
                array_push($error, $err);
                echo "Error updating record: " . $con->error ."<br>";
            }
        }

        if( $count == (sizeof($list)) ){
            $rtr = array("success" => true ,"error" => false);
            $message = "Dates was successfully update !!!";
        }else{
            $rtr = array("success" => false ,"error" => $error);
            $message = "Error on update !!!";
        }

        return back()->with('currentThrought',$message);
    }

    public function fixCRM(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $select = "SELECT oppid , gross_revenue , net_revenue , fcst_amount_gross , fcst_amount_net , COUNT(oppid) AS 'repeat'
                   FROM sf_pr 
                   GROUP BY oppid , gross_revenue , net_revenue , fcst_amount_gross , fcst_amount_net
                  ";

        $result = $con->query($select);

        $from = array("oppid","gross_revenue","net_revenue","fcst_amount_gross","fcst_amount_net","repeat");

        $toFix = $sql->fetch($result,$from,$from);

        $cc = 0;

        $updated = 0;
        for ($f=0; $f < sizeof($toFix) ; $f++) { 
            if($toFix[$f]['repeat'] > 0 && $toFix[$f]['repeat'] != 1){
                $update[$cc] = "UPDATE sf_pr 
                                    SET 
                                        net_revenue = \"".($toFix[$f]['net_revenue']/$toFix[$f]['repeat'])."\",
                                        fcst_amount_gross = \"".($toFix[$f]['fcst_amount_gross']/$toFix[$f]['repeat'])."\",
                                        fcst_amount_net = \"".($toFix[$f]['fcst_amount_net']/$toFix[$f]['repeat'])."\"
                                    WHERE(oppid = \"".$toFix[$f]['oppid']."\")
                               ";
                
                if( $con->query($update[$cc]) === TRUE ){
                    $updated ++;
                }
                $cc ++;
            }
        }

        if($updated == $cc){
            $rtr  = TRUE;
        }else{
            $rtr = FALSE;            
        }

        if($rtr){
            return back()->with('CRMFixSuccess',"The sf_pr Table was succesfully updated :)");
        }        
    }

    public function home(){
    	return view('dataManagement.home');
    }
    
    public function relationships(){
        
    }    

    public function ytdLatamGet(){
        return view('dataManagement.ytdLatamGet');
    }

    /*START OF REGIONS FUNCTIONS*/

    public function regionAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $r->addRegion($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function regionGet(){
    	$sql = new sql();
    	$r = new region();
    	$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
    	$render = new dataManagementRender();
    	return view('dataManagement.regionGet',compact('region','render'));
    }

    public function regionEditGet(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $render = new dataManagementRender();
        return view('dataManagement.edit.editRegion',compact('region','render'));
    }

    public function regionEditPost(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $bool = $r->editRegion($con);        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }
    /*END OF REGIONS FUNCTIONS*/
    /*START OF USER FUNCTIONS*/
    public function userAdd(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $usr->addUser($con);

        if($bool['bool']){
            return back()->with('addUser',$bool['msg']);
        }else{
            return back()->with('errorAddUser',$bool['msg']);
        }
    }

    public function userGet(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $usr = new User();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $user = $usr->getUser($con, null);
        $userType = $usr->getUserType($con);
        $render = new dataManagementRender();
    	return view('dataManagement.userGet',compact('user','userType','region','render'));
    }

    public function userEditFilter(){
        $sql = new sql();
        $sr = new salesRep();
        $r = new region();
        $db = new dataBase();
        $usr = new User();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        if (!is_null(Request::get('filterRegion'))) {
            $filter = array(Request::get('filterRegion'));
        }else{
            $filter = null;
        }

        $region = $r->getRegion($con,null);
        $regionFilter = $r->getRegion($con,$filter);
        
        if (!is_null(Request::get('filterRegion'))) {
            $filters = array();
            for ($i=0; $i <sizeof($regionFilter) ; $i++) { 
                array_push($filters, $regionFilter[$i]["id"]);
            }
        }else{
            $filters = null;
        }

        $render = new dataManagementRender();
        $userType = $usr->getUserType($con);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $usr->editUser($con);
        }else{
            $bool = false;
        }

        for ($i=0; $i <sizeof($region) ; $i++) { 
            $salesGroup[$region[$i]["name"]] = $sr->getSalesRepGroup($con,array($region[$i]["id"]));
        }
        
        $user = $usr->getUser($con,$filters);        
        return view('dataManagement.edit.editUser',compact('user','region','render','userType','salesGroup','bool'));
    }

    public function UserTypeAdd(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $usr->addUserType($con);

        if($bool){
            return back()->with('addUserType',$bool['msg']);
        }else{
            return back()->with('errorUserType',$bool['msg']);
        }

    }

    public function userTypeEditGet(){
        $usr = new User();
        $db = new dataBase();
        $render = new dataManagementRender();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $userType = $usr->getUserType($con);
        
        return view('dataManagement.edit.editUserType',compact('userType','render'));
    }

    public function userTypeEditPost(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $bool = $usr->editUserType($con);


        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }


    }

    /*END OF USER FUNCTIONS*/

    /*START OF P-RATE FUNCTIONS*/

    public function pRateAdd(){
        $sql = new sql();
        $db = new dataBase();
        $p = new pRate();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->addPRate($con,false);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function pRateGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.pRateGet',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,"");
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();

        return view('dataManagement.edit.editPRate',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditPost(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->editPRate($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            //return back()->with('error',$bool['msg']);
        }
    }

    public function currencyAdd(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->addCurrency($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function currencyEditGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.edit.editCurrency',compact('region','currency','pRate','cYear','render'));
    }

    public function currencyEditPost(){
        $db = new dataBase();
        $p = new pRate();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $bool = $p->editCurrency($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }   
    }

    /*END OF P-RATE FUNCTIONS*/


    /*START OF SALES REP FUNCTIONS*/
    public function salesRepGroupAdd(){
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRepGroup($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepGet(){
        $o = new origin();
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $region = $r->getRegion($con,false);
        $salesRepGroup = $sr->getSalesRepGroup($con,false);
        $salesRep = $sr->getSalesRep($con,false);       
        $salesRepGroupingReps = $sr->getSalesRepGroupingReps($con,false);   

        $salesRepUnit = $sr->getSalesRepUnit($con,false);       
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.salesRepGet',compact('region','salesRepGroup','salesRep','salesRepUnit','origin','render'));
    }

    public function salesRepAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRep($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepEditFilter(){
        $db = new dataBase();
        $r = new region();
        $sr = new salesRep();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $render = new dataManagementRender();


        $temp = Request::get("filterRegion");
        $filter = array();
        if ($temp != null) {
            array_push($filter, $temp);
        }else{
            $filter = null;
        }

        $region = $r->getRegion($con,null);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $sr->editSalesRep($con);
        }else{
            $bool = false;
        }

        $salesRep = $sr->getSalesRepByRegion($con,$filter); 

        for ($i=0; $i <sizeof($region) ; $i++) { 
            $salesGroup[$region[$i]["name"]] = $sr->getSalesRepGroup($con,array($region[$i]["id"]));
        }

        return view('dataManagement.edit.editSalesRep',compact('salesRep','render','region','salesGroup'));
    }

    public function salesRepUnitAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRepUnit($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepUnitEditFilter(){
        $o = new origin();
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $region = $r->getRegion($con,false);
        $salesRepGroup = $sr->getSalesRepGroup($con,false);
        $salesRep = $sr->getSalesRep($con,false);       
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        if (!is_null(Request::get('filterRep'))) {
            $filter = array(Request::get('filterRep'));
        }else{
            $filter = null;
        }


        if (!is_null(Request::get('size'))) {
            $bool = $sr->editSalesRepUnit($con);
        }else{
            $bool = null;
        }




        $salesRepUnit = $sr->getSalesRepUnit($con,$filter);       

        return view('dataManagement.edit.editSalesRepUnit',compact('salesRep','salesRepUnit','salesRepGroup','origin','render','region'));       
    }

    public function salesRepGroupEditFilter(){
        $dm = new dataManagement();
        $db = new dataBase();
        $r = new region();
        $sr = new salesRep();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $render = new dataManagementRender();

        $temp = Request::get("filterRegion");
        $filter = array();
        if ($temp != null) {
            array_push($filter, $temp);
        }

        $region = $r->getRegion($con,null);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $sr->editSalesRepGroup($con);
        }else{
            $bool = false;
        }

        $salesRepGroup = $sr->getSalesRepGroup($con,$filter);

        return view('dataManagement.edit.editSalesRepGroup',compact('salesRepGroup','region','render'));

    } 


    /*END OF SALES REP FUNCTIONS*/

    /*START OF AGENCY FUNCTIONS*/

    public function newAgencyAdd(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $ag = new agency();
        $agencyGroupID = array( Request::get('agencyGroup') );
        $agencyGroup = $ag->getAgencyGroup($con,$agencyGroupID);
        


        var_dump($agencyGroup);
        var_dump("New Agency Add");

    }

    public function newAgencyGroupAdd(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $ag = new agency();

        $regionID = Request::get('region');
        $agencyGroupName = Request::get('createAgencyGroup');

        $table = 'agency_group';
        $columns = 'region_id,name';
        $values = " \" ".$regionID." \" , \" ".$agencyGroupName." \"  ";

        $bool = $sql->insert($con,$table,$columns,$values);

        if($bool){

            return view("dataManagement.ytdLatamPost",compact('tmpSheet','clientMissMatches','agencyMissMatches','region','agency','client','agencyGroup','clientGroup'));
            
        }else{

        }
    }

    public function agencyAdd(){

    }

    public function agencyGetFromExcel(){

        return view('dataManagement.agencyGetFromExcel');

    }

    

    /*END OF SALES AGENCY FUNCTIONS*/

    /*START OF CLIENT FUNCTIONS*/

    public function clientGetFromExcel(){

        return view('dataManagement.clientGetFromExcel');

    }

    


    

    public function newClientAdd(){
        var_dump("New Client Add");
    }

    public function newClientGroupAdd(){
        var_dump("New Client Group Add");
    }
    

    /*END OF SALES CLIENT FUNCTIONS*/
    
    /*START OF ORIGIN FUNCTIONS*/

    public function originAdd(){
        $o = new origin();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $o->addOrigin($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function originGet(){
        $o = new origin();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.originGet',compact('origin','render'));
    }

    /*END OF ORIGIN FUNCTIONS*/

    /*START OF BRAND FUNCTIONS*/

    public function brandAdd(){
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $b = new brand();
        $bool = $b->addBrand($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }        
    }

    public function brandGet(){
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $b = new brand();
        $o = new origin();
        $brand = $b->getBrand($con,false);
        $brandUnit = $b->getBrandUnit($con,false);
        $origin = $o->getOrigin($con,false);
        if(!$origin && !$brand){
            $state = "disabled='true'";
        }else{
            $state = false;
        }
        $render = new dataManagementRender();
        return view('dataManagement.brandGet',compact('brand','brandUnit','origin','state','render'));
    }

    public function brandUnitAdd(){
        $b = new brand();
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $b->addBrandUnit($con);        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function emailDivulgacaoGet(){
        
        $email = new emailDivulgacao();

        $to = "guilherme_costa@discoverybrasil.com";
        $subject = "teste";
        $message = $email->getMessage();
        
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: TesteChangePassword <d_ads@discovery.com>';

        $res = mail($to, $subject, $message, implode("\r\n", $headers));

        var_dump($res);
    }

    
    
}
