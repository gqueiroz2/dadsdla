<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\Request;
use App\renderResume;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\brand;
use App\pRate;
use App\sql;
use App\resultsResume;

use App\generateExcel;

class resultsResumeController extends Controller{
    
	public function get(){
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $b = new brand();
        $pr = new pRate();
		$render = new renderResume();

		$region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
        
		return view('adSales.results.0resumeGet',compact('render','region','brand','currency'));

	}

	public function post(){
		$sql = new sql();
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new renderResume();
		$cYear = intval(date('Y') );
		$pYear = $cYear - 1;
		$region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);

        $validator = Validator::make(Request::all(),[
        	'region' => 'required',
        	'brand' => 'required',
        	'currency' => 'required',
        	'value' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

		$regionID = Request::get('region');

		$tmp = $r->getRegion($con,array($regionID));

		if(is_array($tmp)){
			$salesRegion = $tmp[0]['name'];
		}else{
			$salesRegion = $tmp['name'];
		}

		$salesShow = 'BKGS';

		$tmp = Request::get("brand");
		$brandID = $base->handleBrand($tmp);
		$currencyID = Request::get('currency');

		$value = Request::get('value');		
		$month = $base->getMonth();
		$tmp = $pr->getCurrency($con,array($currencyID));
		if($tmp){$currencyS = $tmp[0]['name'];}else{$currencyS = "ND";}

		$valueS = strtoupper($value);
		$resume = new resultsResume();
		
		$currentMonth = intval(date('m'));

		$brands = $resume->divideBrands($brandID);

		if (empty($brands[0])) {
			$Digital = $resume->generateVectorDigital($con, $brands[1], $month, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion);

			$matrixDigital = $resume->assembler($month,$Digital["salesCYear"],$Digital["actual"],$Digital["target"],$Digital["corporate"]/*$pAndR,$finance*/,$Digital["previousYear"]);

			$DN = $resume->grouper(null,$Digital);

			$matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);
		}elseif (empty($brands[1])) {
			$TV = $resume->generateVectorsTV($con, $brands[0], $month, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion);

			$matrixTV = $resume->assembler($month,$TV["salesCYear"],$TV["actual"],$TV["target"],$TV["corporate"]/*$pAndR,$finance*/,$TV["previousYear"]);	

			$DN = $resume->grouper($TV,null);

			$matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);
		}else{
			$TV = $resume->generateVectorsTV($con, $brands[0], $month, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion);

			$matrixTV = $resume->assembler($month,$TV["salesCYear"],$TV["actual"],$TV["target"],$TV["corporate"]/*$pAndR,$finance*/,$TV["previousYear"]);
			
			$Digital = $resume->generateVectorDigital($con, $brands[1], $month, $currentMonth, $value, $cYear, $pYear, $regionID, $currencyID, $salesRegion);

			$matrixDigital = $resume->assembler($month,$Digital["salesCYear"],$Digital["actual"],$Digital["target"],$Digital["corporate"]/*$pAndR,$finance*/,$Digital["previousYear"]);	

			$DN = $resume->grouper($TV,$Digital);

			$matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);
		}

		$rName = $resume->TruncateRegion($salesRegion);

		if (!isset($matrixTV)) {
			$matrix = array($matrixDigital, $matrixDN);
			$names = array("Digital", "DN");
		}elseif (!isset($matrixDigital)) {
			$matrix = array($matrixTV, $matrixDN);	
			$names = array("TV", "DN");
		}else{
			$matrix = array($matrixTV, $matrixDigital, $matrixDN);
			$names = array("TV", "Digital", "DN");
		}
		
		/*--Excel steps--*/

		$ge = new generateExcel();

		$years = array($cYear, $pYear);

		if ($salesRegion == "Brazil") {
                $form = "cmaps";
        }else{
                $form = "ytd";
        }

        $firstTable = $ge->selectData($con, $regionID, $years[0], $brandID, $form, $tmp, $value, $month);
                
        $secondTable = $ge->selectData($con, $regionID, $years[1], $brandID, "ytd", $tmp, $value, $month);

        for ($p=0; $p < sizeof($secondTable[1]); $p++) { 
                array_push($firstTable[1], $secondTable[1][$p]);
        }

        unset($secondTable[1]);

        $plan = array("TARGET", "CORPORATE", "ACTUAL");
        
        $planTable = $ge->selectData($con, $regionID, $years[0], $brandID, $plan, $tmp, $value, $month);

        $finalExcel = array($form => $firstTable[0], 'digital' => $firstTable[1], 'plan' => $planTable[0], 'pYtd' => $secondTable[0]);
		$regionExcel = $salesRegion;
		$currencyExcel = $tmp;
		$yearExcel = $years;
		$valueExcel = $value;

		return view('adSales.results.0resumePost',compact('render','region','brand','currency','matrix','currencyS','valueS','cYear','pYear','salesShow', 'salesRegion', 'rName', 'names', 'regionExcel', 'currencyExcel', 'yearExcel', 'finalExcel', 'valueExcel', 'plan'));

	}

}