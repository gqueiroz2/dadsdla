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

use App\excelSheets;


class fileUploadController extends Controller{
    
	public function excelGet(){

		$report = array(
							array( 'table' => 'cmaps', 'show' => 'CMAPS'),
							array( 'table' => 'ytd', 'show' => 'YTD'),
							array( 'table' => 'digital', 'show' => 'Digital - SAP'),
							array( 'table' => 'mini_header', 'show' => 'Mini-Header'),
							array( 'table' => 'plan_by_brand', 'show' => 'Plan By Brand'),
							array( 'table' => 'plan_by_sales', 'show' => 'Plan By Sales'),
							array( 'table' => 'rolling_forecast', 'show' => 'Rolling Forecast'),
							array( 'table' => 'sales_rep', 'show' => 'Sales Rep'),
							array( 'table' => 'sales_rep_unit', 'show' => 'Sales Rep Unit'),
							array( 'table' => 'sales_rep_status', 'show' => 'Sales Rep Status'),
							array( 'table' => 'brand', 'show' => 'Brand'),
							array( 'table' => 'brand_unit', 'show' => 'Brand Unit')

						);

		return view("dataManagement.excelGet",compact('report'));
	}

	public function excelPost(){
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$i = new import();
		$spreadSheet = $i->base();
		unset($spreadSheet[0]);
		$spreadSheet = array_values($spreadSheet);
		$eS = new excelSheets();		
		$table = Request::get('table');
		var_dump($table);
		$eS->handler($con,$table,$spreadSheet);
		//$eS->cmaps($con,$spreadSheet); 
	}

	public function ytdLatam(){
		$c = new client();
		$ag = new agency();
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
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
		/*
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$i = new import();
		$a = new agency();

		$spreadSheet = $i->base();

		unset($spreadSheet[0]);
		
		$spreadSheet = array_values($spreadSheet);

		$eS = new excelSheets();

		$eS->cmaps($con,$spreadSheet);
		*/
	}


	public function client(){
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
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
