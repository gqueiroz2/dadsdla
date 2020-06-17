<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\agency;
use App\region;
use App\Render;
use App\pRate;
use App\base;
use App\relationship;
use App\relationshipRender;

class relationshipController extends Controller{
    
	public function get(){
        	$base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $r = new region();
                $region = $r->getRegion($con,false);  
                $render = new Render();

                return view('relationship.get',compact('render','region'));

	}

	public function post(){
        	$base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $r = new region();
                $region = $r->getRegion($con,false);  
                $render = new relationshipRender();

                $rel = new relationship();

                $type = Request::get('type');
                $regionID = Request::get('region');
                $structure = $rel->getStructure($con,$regionID,$type);

                return view('relationship.post',compact('render','region','structure','type'));

	}

	public function relationshipAgencyGet(){
		
        	$db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $render = new relationshipRender();
                $ag = new agency();
                $r = new region();
                $region = $r->getRegion($con);
		return view('dataManagement.AgencyClient.relationshipAgencyGet',compact('region','render'));
	}

	public function relationshipAgencyPost(){		

        	$regionID = Request::get('region');
        	$alphabetLetter = Request::get('alphabetLetter');
        	$db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $render = new relationshipRender();
                $ag = new agency();
                $agencies = $ag->getAllAgenciesByFirstLetter($con,$regionID,$alphabetLetter);
                $agency = $ag->getAgencyByRegion($con,array($regionID));
                $r = new region();
                $region = $r->getRegion($con);
		return view('dataManagement.AgencyClient.relationshipAgencyPost',compact('agencies','agency','region','render'));
		

	}



}
