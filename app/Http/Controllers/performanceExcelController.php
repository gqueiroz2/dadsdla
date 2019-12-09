<?php

namespace App\Http\Controllers;

use App\dataBase;

use App\performanceExecutive;

use App\Exports\performanceExecutiveExport;

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
		
		if (sizeof($mtx['tier']) > 1) {
			array_push($mtx['tier'], "TT");
		}

		$cYear = Request::get('year');

		$data = array('mtx' => $mtx, 'cYear' => $cYear);

		$labels = array("exports.performance.executive.executiveCase1Export", "exports.performance.executive.executiveCase2Export");

		$title = Request::get('title');

		return Excel::download(new performanceExecutiveExport($data,$labels), $title);

  	}
}
