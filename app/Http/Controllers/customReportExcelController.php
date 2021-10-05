<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\AE;
use App\sql;
use App\salesManagement;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use App\Exports\customReportExport;

class customReportExcelController extends Controller{

	public function customReport(){

		$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$sM = new salesManagement();

		$temp = $sM->customReportV1($con);

		//var_dump($temp);

		$typeExport = Request::get("typeExport");

		$title = "Custom Report V1.xlsx";

		$auxTitle = $title;

		$label = array("exports.salesManagement.customReport.customReport","exports.salesManagement.customReport.customReportTab2");

		$data = array('temp' => $temp);

		return Excel::download(new customReportExport($data, $label, $typeExport, $auxTitle), $title);
	}
}
