<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\dataBase;
use App\viewer;
use App\pRate;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Exports\baseExport;

class viewerExcelController extends Controller{


    public function viewerBase(){
    	$viewer = new viewer();

	    $db =  new dataBase();
	    $con = $db->openConnection("DLA");

	    $region = Request::get("regionExcel");

	    $r =  new region();
	    $regions = $r->getRegion($con,array($region))[0]['name'];

	    $source = Request::get("sourceExcel");

	    $year = json_decode(base64_decode(Request::get("yearExcel")));

	    $month = json_decode(base64_decode(Request::get("monthExcel")));

	    $brand = json_decode(base64_decode(Request::get("brandExcel")));

	    $salesRep = json_decode(base64_decode(Request::get("salesRepExcel")));

	    $agency = json_decode(base64_decode(Request::get("agencyExcel")));

	    $client = json_decode(base64_decode(Request::get("clientExcel")));

	    $currency = Request::get("currencyExcel");
	    /*$p = new pRate();
        $currencies = $p->getCurrency($con,array($salesCurrency))[0]['name']; */


	    $value = Request::get("valueExcel");

	    $table = $viewer->getTables($con,$salesRegion,$source,$month,$brand,$value,$year,$salesCurrency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client);

        $total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies,$salesRegion);

        $mtx = $viewer->assemble($table,$salesCurrency,$source,$con,$salesRegion,$currencies);

        $data = array('mtx' => $mtx, 'currency' => $currency, 'region' => $regions, 'source' => $source, 'year' => $year, 'month' => $month, 'brand' => $brand, 'salesRep' => $salesRep, 'agency' => $agency, 'client' => $client, 'value' => $value, 'total' => $total);

        $label = "exports.viewer.base.baseExport";

	    $title = Request::get("title");

	    return Excel::download(new baseExport($data,$label),$title);

    }
}
