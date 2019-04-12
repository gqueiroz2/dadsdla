<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dataManagement;


class dataManagementController extends Controller
{
    public function home(){

    	return view('dataManagement.home');
    }

    public function regionGet(){
    	
    	$dm = new dataManagement();

    	$region = $dm->getRegions();

    	return view('dataManagement.regionGet',compact('region'));
    }

    public function userGet(){

    	$dm = new dataManagement();

    	$region = $dm->getRegions();
    	$user = $dm->getUsers();

    	return view('dataManagement.userGet',compact('user','region'));

    }

    public function salesRepresentativeGet(){
    	$dm = new dataManagement();

    	$region = $dm->getRegions();
    	$salesRepresentativeGroup = $dm->getSalesRepresentativeGroup();
    	$salesRepresentative = $dm->getSalesRepresentative();    	
    	$salesRepresentativeUnit = $dm->getSalesRepresentativeUnit();    	

    	return view('dataManagement.salesRepresentativeGet',compact('region','salesRepresentativeGroup','salesRepresentative','salesRepresentativeUnit'));
    }

    public function pRateGet(){
    	$dm = new dataManagement();

    	$region = $dm->getRegions();
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

    }

    public function addUser(){

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
