<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;


use App\brand;
use App\region;
use App\User;
use App\queries;
use App\salesRep;
use App\origin;
use App\matchingClientAgency;
use App\sql;
use App\pRate;

class dataManagementController extends Controller{
    public function home(){
        /*
        $sql = new sql(); 
        $db = new dataBase();
        $con = $db->openConnection('DLA');


        $qr = "SELECT 
                    cu.ID AS 'clientUnityID',
                    cu.name AS 'clientUnity',
                    c.name AS 'client',
                    cg.name AS 'clientGroup',
                    r.name AS 'region',
                    o.name AS 'origin'
                FROM client_unit cu
                LEFT JOIN client c ON c.ID = cu.client_id
                LEFT JOIN client_group cg ON cg.ID = c.client_group_id                
                LEFT JOIN region r ON r.ID = cg.region_id
                LEFT JOIN origin o ON o.ID = cu.origin_id 

              ";

        echo($qr)."<br>";
        $res = $con->query($qr);

        $from = array('clientUnityID','clientUnity','client','clientGroup','region','origin');

        $agencies = $sql->fetch($res,$from,$from);

        var_dump($res);
        var_dump($agencies);
        */

        /*
        $sql = new sql(); 
        $db = new dataBase();
        $con = $db->openConnection('DLA');


        $qr = "SELECT 
                    au.ID AS 'agencyUnityID',
                    au.name AS 'agencyUnity',
                    au.status AS 'status',
                    a.name AS 'agency',
                    ag.name AS 'agencyGroup',
                    r.name AS 'region',
                    o.name AS 'origin'
                FROM agency_unit au
                LEFT JOIN agency a ON a.ID = au.agency_id
                LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id                
                LEFT JOIN region r ON r.ID = ag.region_id
                LEFT JOIN origin o ON o.ID = au.origin_id 

              ";

        $res = $con->query($qr);

        $from = array('agencyUnityID','agencyUnity','status','agency','agencyGroup','region','origin');

        $agencies = $sql->fetch($res,$from,$from);

        var_dump($res);
        var_dump($agencies);
*/
    	return view('dataManagement.home');
    }

    /*START OF REGIONS FUNCTIONS*/

    public function regionAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
		$con = $db->openConnection('DLA');
        $region = $r->getRegion($con,false);
    	$render = new dataManagementRender();
    	return view('dataManagement.regionGet',compact('region','render'));
    }

    public function regionEditGet(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $region = $r->getRegion($con,false);
        $render = new dataManagementRender();
        return view('dataManagement.edit.editRegion',compact('region','render'));
    }

    public function regionEditPost(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $bool = $usr->addUser($con);
        if($bool){
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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        
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

        $user = $usr->getUser($con,$filters);

        for ($i=0; $i <sizeof($region) ; $i++) { 
            $salesGroup[$region[$i]["name"]] = $sr->getSalesRepGroup($con,array($region[$i]["id"]));
        }

        return view('dataManagement.edit.editUser',compact('user','region','render','userType','salesGroup','bool'));
    }

    public function UserTypeAdd(){
        $usr = new User();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $userType = $usr->getUserType($con);
        
        return view('dataManagement.edit.editUserType',compact('userType','render'));
    }

    public function userTypeEditPost(){
        $usr = new User();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.pRateGet',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $region = $r->getRegion($con,"");
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con);
        $cYear = date('Y');
        $render = new dataManagementRender();

        return view('dataManagement.edit.editPRate',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditPost(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $p->editPRate($con);         
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function currencyAdd(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.edit.editCurrency',compact('region','currency','pRate','cYear','render'));
    }

    public function currencyEditPost(){
        $db = new dataBase();
        $p = new pRate();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $sr = new salesRep();
        $region = $r->getRegion($con,false);
        $salesRepGroup = $sr->getSalesRepGroup($con,false);
        $salesRep = $sr->getSalesRep($con,false);       
        $salesRepUnit = $sr->getSalesRepUnit($con,false);       
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.salesRepGet',compact('region','salesRepGroup','salesRep','salesRepUnit','origin','render'));
    }

    public function salesRepAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $sr = new salesRep();
        $bool = $sr->addSalesRepUnit($dm,$con);

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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
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

    public function agencyAdd(){

    }

    public function agencyGet(){

        return view('dataManagement.agencyGet');

    }




    /*END OF SALES AGENCY FUNCTIONS*/


    /*START OF CLIENT FUNCTIONS*/

    public function clientAdd(){


    }

    public function clientGet(){

        return view('dataManagement.clientGet');

    }


    

    /*END OF SALES CLIENT FUNCTIONS*/
    
    /*START OF ORIGIN FUNCTIONS*/

    public function originAdd(){
        $o = new origin();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.originGet',compact('origin','render'));
    }

    /*END OF ORIGIN FUNCTIONS*/

    /*START OF BRAND FUNCTIONS*/

    public function brandAdd(){
        $sql = new sql();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $bool = $b->addBrandUnit($con);        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    /*END OF BRAND FUNCTIONS*/
/*
    public function truncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $con = $db->openCOnnection("DLA");

        return view('dataManagement.truncateCheck');
    }

    public function trueTruncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $con = $db->openCOnnection("DLA");

        $queries->truncateAll($con);

        return view('dataManagement.home');
    }
*/
    

    

    

    

    

    

    

    
}
