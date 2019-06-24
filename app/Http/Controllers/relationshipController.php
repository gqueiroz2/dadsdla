<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\agency;
class relationshipController extends Controller{
    
	public function relationshipsClientGet(){

	}

	public function relationshipAgencyGet(){
		$db = new dataBase();
        $con = $db->openConnection('DLA');

        $ag = new agency();

        $agencies = $ag->getAllAgencies($con);

		return view('dataManagement.AgencyClient.relationshipAgencyGet',compact('agencies'));
	}



}
