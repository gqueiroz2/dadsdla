<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\agency;
use App\client;
use App\import;
use App\dataBase;

class fileUploadController extends Controller{
    
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

		//$a->handlerGroup($con,$spreadSheet);
		//$a->handler($con,$spreadSheet);
		//$a->handlerUnit($con,$spreadSheet);

		var_dump("JA FOI FEITO");
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
		//$c->handlerGroup($con,$spreadSheet);
		//$c->handler($con,$spreadSheet);
		//$c->handlerUnit($con,$spreadSheet);
		
		var_dump("JA FOI FEITO");
	}

}
