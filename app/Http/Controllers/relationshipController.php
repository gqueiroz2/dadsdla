<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\agency;
use App\relationshipRender;
use App\region;


class relationshipController extends Controller{
    
	public function relationshipsClientGet(){

	}

	public function relationshipAgencyGet(){
		
		$db = new dataBase();
        $con = $db->openConnection('DLA');
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
        $con = $db->openConnection('DLA');
        $render = new relationshipRender();
        $ag = new agency();
        $agencies = $ag->getAllAgenciesByFirstLetter($con,$regionID,$alphabetLetter);
        $agency = $ag->getAgencyByRegion($con,array($regionID));
        $r = new region();
        $region = $r->getRegion($con);
		return view('dataManagement.AgencyClient.relationshipAgencyPost',compact('agencies','agency','region','render'));
		

	}



}
