<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\agency;
use App\client;
use App\region;

use App\import;

use App\dataBase;
use App\ytdLatam;
use App\Management;

class fileUploadController extends Controller{
    
	public function ytdLatam(){
		$c = new client();
		$ag = new agency();
		$db = new dataBase();
		$con = $db->openConnection('DLA');	
		$i = new import();
		$r = new region();
		$ytd = new ytdLatam();
		$m = new Management();
		
		$region = $r->getRegion($con,false);

		$spreadSheet = $i->base();
		$tmpSheet = $m->putIndex($spreadSheet,'YTD');
		
		if($tmpSheet){
			for ($t=0; $t < sizeof($tmpSheet); $t++) { 
				$agencyUnits[$t] = $tmpSheet[$t]['Agency'];
				$clientUnits[$t] = $tmpSheet[$t]['Client'];
			}
			sort($clientUnits);
			sort($agencyUnits);
			$clientUnits = array_values(array_unique($clientUnits));
			$agencyUnits = array_values(array_unique($agencyUnits));
			$clientMissMatches = $m->checkForMissMatches($con,$c,"client",$clientUnits);
			$agencyMissMatches = $m->checkForMissMatches($con,$ag,"agency",$agencyUnits);
		}else{
			$clientMissMatches = false;
			$agencyMissMatches = false;
		}

		$agency = $ag->getAgency($con,false);
		$client = $c->getClient($con,false);

		$agencyGroup = $ag->getAgencyGroup($con,false);
		$clientGroup = $c->getClientGroup($con,false);

		return view("dataManagement.ytdLatamPost",compact('tmpSheet','clientMissMatches','agencyMissMatches','region','agency','client','agencyGroup','clientGroup'));

	}

	public function agency(){
		$db = new dataBase();
		$con = $db->openConnection('DLA');	
		$i = new import();
		$a = new agency();

		$spreadSheet = $i->base();
		unset($spreadSheet[0]);

		$spreadSheet = array_values($spreadSheet);


		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$spreadSheetV2[$s]['source'] = trim($spreadSheet[$s][0]);
			$spreadSheetV2[$s]['region'] = trim($spreadSheet[$s][1]);
			$spreadSheetV2[$s]['type'] = trim($spreadSheet[$s][2]);
			$spreadSheetV2[$s]['group'] = trim($spreadSheet[$s][3]);
			$spreadSheetV2[$s]['parent'] = trim($spreadSheet[$s][4]);
			$spreadSheetV2[$s]['child'] = trim($spreadSheet[$s][5]);
		}

		$spreadSheet = $spreadSheetV2;

		$a->handlerGroup($con,$spreadSheet);
		$a->handler($con,$spreadSheet);
		$a->handlerUnit($con,$spreadSheet);

		var_dump("TERMINOU");
	}


	public function client(){
		$db = new dataBase();
		$con = $db->openConnection('DLA');	
		$i = new import();
		$c = new client();

		$spreadSheet = $i->base();
		unset($spreadSheet[0]);

		$spreadSheet = array_values($spreadSheet);


		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$spreadSheetV2[$s]['source'] = trim($spreadSheet[$s][0]);
			$spreadSheetV2[$s]['region'] = trim($spreadSheet[$s][1]);
			$spreadSheetV2[$s]['type'] = trim($spreadSheet[$s][2]);
			$spreadSheetV2[$s]['group'] = trim($spreadSheet[$s][3]);
			$spreadSheetV2[$s]['parent'] = trim($spreadSheet[$s][4]);
			$spreadSheetV2[$s]['child'] = trim($spreadSheet[$s][5]);
		}

		$spreadSheet = $spreadSheetV2;
		$c->handlerGroup($con,$spreadSheet);
		$c->handler($con,$spreadSheet);
		$c->handlerUnit($con,$spreadSheet);
		
		var_dump("JA FOI FEITO");
	}

}
