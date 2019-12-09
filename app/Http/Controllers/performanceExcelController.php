<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\performanceExecutive;

class performanceExcelController extends Controller{
  	
  	public function performanceExecutive(){

  		var_dump(Request::all());

		/*$db = new dataBase();
		$con = $db->openConnection("DLA");

		$p = new performanceExecutive();

		$region = Request::get('regionExcel');

		$currency = Request::get('currencyExcel');

		$year = Request::get('yearExcel');

		$value = Request::get('valueExcel');

		$salesRep = Request::get('salesRepExcel');

		$mtx = $p->makeMatrix($con);

		$data = array('mtx' => $mtx,'currency' => $currency, 'region' => $region, 'year' => $year, 'value' => $value,'salesRep' => $salesRep);

		$label = "exports.performance.individual.executiveExport";

		$title = Request::get('title');

		return Excel::download(new executiveExport($data,$label), $title);*/

  	}
}
