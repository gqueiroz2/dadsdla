<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;
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

    	$region = $dm->getRegions();
    	$user = $dm->getUsers();

    	return view('dataManagement.userGet',compact('user','region'));

    }

    public function salesRepresentativeGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
    	$salesRepresentativeGroup = $dm->getSalesRepresentativeGroup($con);
    	$salesRepresentative = $dm->getSalesRepresentative($con);    	
    	$salesRepresentativeUnit = $dm->getSalesRepresentativeUnit($con);    	

        $render = new dataManagementRender();

    	return view('dataManagement.salesRepresentativeGet',compact('region','salesRepresentativeGroup','salesRepresentative','salesRepresentativeUnit','render'));
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

    	$origin = $dm->getOrigin();

    	return view('dataManagement.originGet',compact('origin'));
    }

    public function brandGet(){
    	$dm = new dataManagement();

    	$brand = $dm->getBrands();
    	$brandUnit = $dm->getBrandUnits();
    	$origin = $dm->getOrigin();

    	if(!$origin && !$brand){
    		$state = "disabled='true'";
    	}else{
    		$state = false;
    	}

    	return view('dataManagement.brandGet',compact('brand','brandUnit','origin','state'));
    }

    public function truncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $con = $db->openCOnnection("DLA");

        //$queries->truncateAll($con);

        return view('dataManagement.truncateCheck');
    }

    public function trueTruncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $con = $db->openCOnnection("DLA");

        $queries->truncateAll($con);

        return view('dataManagement.home');
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

    }

    public function addBrand(){

    }

    public function addBrandUnit(){

    }

    public function addOrigin(){
    	
    }

    public function addAgency(){

        $dm = new dataManagement();
        $agency = new matchingClientAgency();

        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $agency->match($con);
    }
}
