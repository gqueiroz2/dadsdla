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
			$spreadSheetV2[$s]['sales_group_id'] = trim($spreadSheet[$s][0]);
			$spreadSheetV2[$s]['sales_rep_id'] = trim($spreadSheet[$s][1]);
			$spreadSheetV2[$s]['client_id'] = trim($spreadSheet[$s][2]);	
			$spreadSheetV2[$s]['agency_id'] = trim($spreadSheet[$s][3]);
			$spreadSheetV2[$s]['brand_id'] = trim($spreadSheet[$s][4]);
			$spreadSheetV2[$s]['decode'] = trim($spreadSheet[$s][5]);
			$spreadSheetV2[$s]['year'] = trim($spreadSheet[$s][6]);
			$spreadSheetV2[$s]['month'] = trim($spreadSheet[$s][7]);
			$spreadSheetV2[$s]['map_number'] = trim($spreadSheet[$s][8]);			
			$spreadSheetV2[$s]['package'] = trim($spreadSheet[$s][9]);
			$spreadSheetV2[$s]['product'] = trim($spreadSheet[$s][10]);
			$spreadSheetV2[$s]['segment'] = trim($spreadSheet[$s][11]);			
			$spreadSheetV2[$s]['pi_number'] = trim($spreadSheet[$s][12]);
			$spreadSheetV2[$s]['gross'] = trim($spreadSheet[$s][13]);
			$spreadSheetV2[$s]['net'] = trim($spreadSheet[$s][14]);			
			$spreadSheetV2[$s]['market'] = trim($spreadSheet[$s][15]);
			$spreadSheetV2[$s]['discount'] = trim($spreadSheet[$s][16]);
			$spreadSheetV2[$s]['client_cnpj'] = trim($spreadSheet[$s][17]);			
			$spreadSheetV2[$s]['agency_cnpj'] = trim($spreadSheet[$s][18]);
			$spreadSheetV2[$s]['media_type'] = trim($spreadSheet[$s][19]);
			$spreadSheetV2[$s]['log'] = trim($spreadSheet[$s][20]);			
			$spreadSheetV2[$s]['ad_sales_support'] = trim($spreadSheet[$s][21]);
			$spreadSheetV2[$s]['obs'] = trim($spreadSheet[$s][22]);
			$spreadSheetV2[$s]['sector'] = trim($spreadSheet[$s][23]);			
			$spreadSheetV2[$s]['category'] = trim($spreadSheet[$s][24]);			

		}

		$spreadSheet = $spreadSheetV2;

		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			$insert[$s] = "INSERT INTO cmaps
								  (
								  sales_group_id,
								  sales_rep_id,
								  client_id,
								  agency_id,
								  brand_id,
								  decode,
								  year,
									month,
									map_number,
									package,
									product,
									segment,
									pi_number,
									gross,
									net,
									market,
									discount,
									client_cnpj,
									agency_cnpj,
									media_type,
									log,
									ad_sales_support,
									obs,
									sector,
									category

								  
								  ) 
						      VALUES ( 

						      		\" ".$spreadSheet[$s]['sales_group_id']." \" ,
									\" ".$spreadSheet[$s]['sales_rep_id']." \" ,
									\" ".$spreadSheet[$s]['client_id']." \" ,
									\" ".$spreadSheet[$s]['agency_id']." \" ,
									\" ".$spreadSheet[$s]['brand_id']." \" ,
									\" ".$spreadSheet[$s]['decode']." \" ,
									\" ".$spreadSheet[$s]['year']." \" ,
									\" ".$spreadSheet[$s]['month']." \" ,
									\" ".$spreadSheet[$s]['map_number']." \" ,
									\" ".$spreadSheet[$s]['package']." \" ,
									\" ".$spreadSheet[$s]['product']." \" ,
									\" ".$spreadSheet[$s]['segment']." \" ,
									\" ".$spreadSheet[$s]['pi_number']." \" ,
									\" ".$spreadSheet[$s]['gross']." \" ,
									\" ".$spreadSheet[$s]['net']." \" ,
									\" ".$spreadSheet[$s]['market']." \" ,
									\" ".$spreadSheet[$s]['discount']." \" ,
									\" ".$spreadSheet[$s]['client_cnpj']." \" ,
									\" ".$spreadSheet[$s]['agency_cnpj']." \" ,
									\" ".$spreadSheet[$s]['media_type']." \" ,
									\" ".$spreadSheet[$s]['log']." \" ,
									\" ".$spreadSheet[$s]['ad_sales_support']." \" ,
									\" ".$spreadSheet[$s]['obs']." \" ,
									\" ".$spreadSheet[$s]['sector']." \" ,
									\" ".$spreadSheet[$s]['category']." \" 



						              )";

			//echo($insert[$s])."<br>";

			if( $con->query($insert[$s]) === TRUE ){

				var_dump(" FOI  $s ");

			}else{

				var_dump(" Não foi  ".$spreadSheet[$s]['decode']." ");

			}
		}

		

/*
		$a->handlerGroup($con,$spreadSheet);
		$a->handler($con,$spreadSheet);
		$a->handlerUnit($con,$spreadSheet);

		var_dump("TERMINOU");*/
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
