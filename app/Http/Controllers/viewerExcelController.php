<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;

use App\viewer;
use App\insights;

use App\pRate;
use App\sql;
use App\dataBase;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Exports\baseExport;
use App\Exports\insightsExport;

class viewerExcelController extends Controller {

    public function viewerBase(){
	    $db =  new dataBase();
	    $con = $db->openConnection("DLA");

	    $sql = new sql();

	    $region = Request::get("regionExcel");

	    $source = Request::get("sourceExcel");

	    $year = json_decode(base64_decode(Request::get("yearExcel")));

	    $month = json_decode(base64_decode(Request::get("monthExcel")));

	    $brand = json_decode(base64_decode(Request::get("brandExcel")));

	    $salesRep = json_decode(base64_decode(Request::get("salesRepExcel")));

	    $agency = json_decode(base64_decode(Request::get("agencyExcel")));

	    $client = json_decode(base64_decode(Request::get("clientExcel")));

	    $currency = Request::get("currencyExcel");
	    $p = new pRate();
        $currencies = $p->getCurrencybyName($con,$currency); 

	    $value = Request::get("valueExcel");
	    
	    $especificNumber = Request::get("especificNumber");

        if (!is_null($especificNumber) ) {
            $checkEspecificNumber = true;
        }else{
            $checkEspecificNumber = false;
        }
        
	    $viewer = new viewer();

	    $table = $viewer->getTables($con,$region,$source,$month,$brand,$value,$year,$currencies['id'],$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client);

        $total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies['name'],$region,$value,$agency,$client);

        $mtx = $viewer->assemble($table,$currencies['id'],$source,$con,$region,$currencies['name'],$value);

        $data = array('mtx' => $mtx, 'currency' => $currencies['name'], 'region' => $region, 'source' => strtolower($source), 'year' => $year, 'month' => $month, 'brand' => $brand, 'salesRep' => $salesRep, 'agency' => $agency, 'client' => $client, 'value' => $value, 'total' => $total);

        $label = "exports.viewer.base.baseExport";
        
	    $title = Request::get("title");
	    
	    $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

	    return Excel::download(new baseExport($data, $label, $typeExport, $auxTitle), $title);
    }

    public function viewerInsights(){
    	$db =  new dataBase();
	    $con = $db->openConnection("DLA");

	    $sql = new sql();

    	$region = Request::get('regionExcel');

    	$client = json_decode(base64_decode(Request::get('clientExcel')));

    	$month = json_decode(base64_decode(Request::get('monthExcel')));

    	$brand = json_decode(base64_decode(Request::get('brandExcel')));

    	$salesRep = json_decode(base64_decode(Request::get('salesRepExcel')));

    	$currency = Request::get("currencyExcel");
	    $p = new pRate();
        $currencies = $p->getCurrencybyName($con,$currency); 

    	$value = Request::get('valueExcel');

        $in = new insights();

    	$mtx = $in->assemble($con,$sql,$client,$month,$brands,$salesRep,$currency,$value);

        $header = array('Brand','Brand Feed','Sales Rep','Agency','Client','Month','Currency','Charge Type','Product','Campaign','Order Reference','Schedule Event','Spot Status','Date Event','Unit Start Time','Duration Spot','Copy Key','Media Item','Spot Type','Duration Impression','Gross Revenue','Num Spot','Net Revenue');

        $data = array('mtx' => $mtx, 'currency' => $currencies['name'], 'region' => $region, 'client' => $client, 'month' => $month, 'brand' => $brand, 'salesRep' => $salesRep, 'value' => $value, 'header' => $header);

        $label = 'exports.viewer.insights.insightsExport';

        $title = Request::get('title');

        return Excel::download(new insightsExport($data, $label), $title);

    }
}
