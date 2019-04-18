<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;

use App\User;
use App\queries;
use App\matchingClientAgency;

class dataManagementController extends Controller
{
    public function home(){
    	return view('dataManagement.home');
    }

    public function regionGet(){
    	
    	$dm = new dataManagement();
    	$db = new dataBase();
		$con = $db->openConnection('DLA');
    	$region = $dm->getRegions($con);

    	$render = new dataManagementRender();

    	return view('dataManagement.regionGet',compact('region','render'));
    }

    public function userGet(){

    	$dm = new dataManagement();
        $db = new dataBase();
        $usr = new User();
        $con = $db->openConnection('DLA');

    	$region = $dm->getRegions($con);
    	$user = $usr->getUser($con);
        $userType = $usr->getUserType($con);

        $render = new dataManagementRender();
    	return view('dataManagement.userGet',compact('user','userType','region','render'));

    }

    public function salesRepresentativeGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
    	$salesRepresentativeGroup = $dm->getSalesRepresentativeGroup($con);
    	$salesRepresentative = $dm->getSalesRepresentative($con);    	
    	$salesRepresentativeUnit = $dm->getSalesRepresentativeUnit($con);    	

        $origin = $dm->getOrigin($con);

        $render = new dataManagementRender();

    	return view('dataManagement.salesRepresentativeGet',compact('region','salesRepresentativeGroup','salesRepresentative','salesRepresentativeUnit','origin','render'));
    }

    public function pRateGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
    	$currency = $dm->getCurrency($con);
    	$pRate = $dm->getPRate($con);
    	$cYear = date('Y');
        $render = new dataManagementRender();

    	return view('dataManagement.pRateGet',compact('region','currency','pRate','cYear','render'));
    }


    public function agencyGet(){

        return view('dataManagement.agencyGet');

    }


    public function originGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

    	$origin = $dm->getOrigin($con);

        $render = new dataManagementRender();

    	return view('dataManagement.originGet',compact('origin','render'));
    }

    public function brandGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

    	$brand = $dm->getBrand($con);
    	$brandUnit = $dm->getBrandUnit($con);
    	$origin = $dm->getOrigin($con);

    	if(!$origin && !$brand){
    		$state = "disabled='true'";
    	}else{
    		$state = false;
    	}

        $render = new dataManagementRender();

    	return view('dataManagement.brandGet',compact('brand','brandUnit','origin','state','render'));
    }

    public function addRegion(){
    	$dm = new dataManagement();

    	$db = new dataBase();
		$con = $db->openConnection('DLA');
    	$bool = $dm->addRegion($con);

    	if($bool){
    		return back()->with('response',$bool['msg']);
    	}else{
    		return back()->with('error',$bool['msg']);
    	}

    }

    public function addUser(){
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



    public function addUserType(){
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

    public function addCurrency(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $bool = $dm->addCurrency($dm,$con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }

    }

    public function addPRate(){
    	$dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addPRate($dm,$con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function addSalesRepresentativeGroup(){
    	$dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addSalesRepresentativeGroup($dm,$con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function addSalesRepresentative(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addSalesRepresentative($dm,$con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }

    }

    public function addSalesRepresentativeUnit(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addSalesRepresentativeUnit($dm,$con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }

    }

    public function addBrand(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addBrand($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }        
    }

    public function addBrandUnit(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addBrandUnit($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function addOrigin(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $bool = $dm->addOrigin($dm,$con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

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

    public function addAgency(){

        $dm = new dataManagement();
        $agency = new matchingClientAgency();

        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $agency->match($con);
    }

    public function editRegionGet(){

        $dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $region = $dm->getRegions($con);

        $render = new dataManagementRender();

        return view('dataManagement.edit.editRegion',compact('region','render'));
    }

    public function editCurrencyGet(){

        $dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
        $currency = $dm->getCurrency($con);
        $pRate = $dm->getPRate($con);
        $cYear = date('Y');
        $render = new dataManagementRender();

        return view('dataManagement.edit.editCurrency',compact('region','currency','pRate','cYear','render'));
    }

    public function editPRateGet(){
        $dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
        $currency = $dm->getCurrency($con);
        $pRate = $dm->getPRate($con);
        $cYear = date('Y');
        $render = new dataManagementRender();

        return view('dataManagement.edit.editPRate',compact('region','currency','pRate','cYear','render'));
    }

    public function editRegionPost(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');
        $region = $dm->getRegions($con);

        $bool = $dm->editRegion($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function editCurrencyPost(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $bool = $dm->editCurrency($con); 
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }   
    }

    public function editPRatePost(){
        $dm = new dataManagement();

        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $bool = $dm->editPRate($con); 
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }
}
