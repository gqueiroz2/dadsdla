<?php

namespace App\Http\Controllers;

use App\dataBase;

use App\performanceExecutive;

use App\Exports\performanceExecutiveExport;
use App\Exports\performanceBonusExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class performanceExcelController extends Controller{
  	
  	public function performanceExecutive(){

		$db = new dataBase();
		$con = $db->openConnection("DLA");

		$p = new performanceExecutive();

		$region = Request::get('region');
		$year = Request::get('year');
		$brands = Request::get("brands");
		$salesRepGroup = Request::get('salesRepGroup');
		$salesRep = Request::get('salesRep');
		$currency = Request::get('currency');
		$month = Request::get('month');
		$value = Request::get('value');
		$tier = Request::get('tier');

		$mtx = $p->makeMatrix($con, $region, $year, $brands, $salesRepGroup, $salesRep, $currency, $month, $value, $tier);

		$cYear = Request::get('year');

		$data = array('mtx' => $mtx, 'cYear' => $cYear);

		$labels = array("exports.performance.executive.executiveCase1Export", "exports.performance.executive.executiveCase2Export", "exports.performance.executive.executiveCase3Export", "exports.performance.executive.executiveCase4Export");

		$title = Request::get('title');

		return Excel::download(new performanceExecutiveExport($data,$labels), $title);
  	}

  	public function bonus(){
  		
  		$db = new dataBase();
		$con = $db->openConnection("DLA");

		$p = new performanceExecutive();

		$region = Request::get('region');
		$year = Request::get('year');
		$brands = Request::get("brands");
		$currency = Request::get('currency');
		$month = Request::get('month');
		$tier = array("T1", "T2");
		$userName = Request::get('userName');

		for ($b=0; $b < sizeof($brands); $b++) { 
			if ($brands[$b][1] == "OTH") {
				unset($brands[$b]);
			}
		}

		$brands = array_values($brands);

		$mtx = $p->makeBonus($con, $region, $year, $brands, $userName, $currency, $month, $tier);

		$data = $mtx;

		$labels = "exports.performance.bonus.bonusExport";

		$title = Request::get('title');

		return Excel::download(new performanceBonusExport($data,$labels), $title);
  	}
}
