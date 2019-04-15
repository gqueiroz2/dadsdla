<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;

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
    	$salesRepresentativeGroup = $dm->getSalesRepresentativeGroup();
    	$salesRepresentative = $dm->getSalesRepresentative();    	
    	$salesRepresentativeUnit = $dm->getSalesRepresentativeUnit();    	

    	return view('dataManagement.salesRepresentativeGet',compact('region','salesRepresentativeGroup','salesRepresentative','salesRepresentativeUnit'));
    }

    public function pRateGet(){
    	$dm = new dataManagement();
        $db = new dataBase();
        $con = $db->openConnection('DLA');

        $region = $dm->getRegions($con);
    	$currency = $dm->getCurrency();
    	$pRate = $dm->getPRate();

    	$cYear = date('Y');
    	return view('dataManagement.pRateGet',compact('region','currency','cYear'));
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
        $bool = $dm->addRegion($con);

        $bool = $dm->addCurrency($dm,$con);

    }

    public function addPRate(){
    	
    }

    public function addSalesRepresentativeGroup(){
    	
    }

    public function addSalesRepresentative(){

    }

    public function addBrand(){

    }

    public function addBrandUnit(){

    }

    public function addOrigin(){
    	
    }
}
