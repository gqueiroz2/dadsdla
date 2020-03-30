<?php

namespace App\Http\Controllers;

use App\dataBase;
use App\base;
use App\pRate;
use App\region;

use App\performanceExecutive;
use App\performanceCore;
use App\quarterPerformance;

use App\Exports\performanceExecutiveExport;
use App\Exports\performanceBonusExport;
use App\Exports\performanceCoreExport;
use App\Exports\performanceQuarterExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class performanceExcelController extends Controller{
  	
  	public function executive(){

		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

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

		$typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

		return Excel::download(new performanceExecutiveExport($data, $labels, $typeExport, $auxTitle), $title);
  	}

  	public function bonus(){
  		
  		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

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

		$data = array('mtx' => $mtx, 'region' => $region, 'year' => $year, 'brand' => $brands, 'currency' => $currency, 'month' => $month, 'tier' => $tier, 'userName' => $userName);

		$labels = "exports.performance.bonus.bonusExport";

		$title = Request::get('title');

		$typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

		return Excel::download(new performanceBonusExport($data, $labels, $typeExport, $auxTitle), $title);
  	}

  	public function core(){
  		
  		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$p = new performanceCore();

		$region = Request::get('region');
		$year = Request::get('year');
		$brands = Request::get("brands");
		$salesRepGroup = Request::get('salesRepGroup');
		$salesRep = Request::get('salesRep');
		$currency = Request::get('currency');
		$month = Request::get('month');
		$value = Request::get('value');
		$tier = Request::get('tier');

		$mtx = $p->makeCore($con, $region, $year, $brands, $salesRepGroup, $salesRep, $currency, $month, $value, $tier);

		$cYear = Request::get('year');

		$data = array('mtx' => $mtx, 'cYear' => $cYear);

		$labels = array("exports.performance.core.coreCase1Export", "exports.performance.core.coreCase2Export", "exports.performance.core.coreCase3Export", "exports.performance.core.coreCase4Export");

		$title = Request::get('title');

		$typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

		return Excel::download(new performanceCoreExport($data, $labels, $typeExport, $auxTitle), $title);
  	}

  	public function quarter(){
  		
  		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

  		$regionID = Request::get('region');
        $year = Request::get('year');

        $tiers = Request::get('tiers');
       
        $brands = Request::get("brands");

        $currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));

        $value = Request::get("value");

        $salesRepGroupID = Request::get("salesRepGroup");
        $salesRepID = Request::get("salesRep");

        $base = new base();
        $qp = new quarterPerformance();

        $matrix = $qp->makeQuarter($con, $regionID, $year, $brands, $currency, $value, $base->getMonth(), $tiers, $salesRepID);

        $mtx = $matrix[0];
        $auxTiers = $matrix[1];

        $sales = $qp->createLabels($con, $salesRepGroupID, $salesRepID, $regionID, $year);

        $tmpTiers = array("T1", "T2", "TOTH");

        for ($i=0; $i < sizeof($auxTiers); $i++) { 
            if (empty($auxTiers[$i])) {
                unset($tmpTiers[$i]);
            }
        }

        $tmpTiers = array_values($tmpTiers);

        $tiersFinal = array();
        
        for ($i=0; $i < sizeof($tmpTiers); $i++) { 
            array_push($tiersFinal, $tmpTiers[$i]);
        }

        if (sizeof($brands) > 1) {
            array_push($tiersFinal, "TT");
        }

        $tiers = $tiersFinal;

        $r = new region();
        $region = $r->getRegion($con, array($regionID))[0]['name'];

        $data = array('mtx' => $mtx, 'auxTiers' => $auxTiers, 'region' => $region, 'year' => $year, 'currency' => $pRate, 'value' => $value, 'sales' => $sales, 'tiers' => $tiers);
        $labels = "exports.performance.quarter.quarterExport";

        $title = Request::get('title');

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

		return Excel::download(new performanceQuarterExport($data, $labels, $typeExport, $auxTitle), $title);
  	}

}