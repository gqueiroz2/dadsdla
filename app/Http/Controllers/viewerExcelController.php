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

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

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

	    $table = $viewer->getTables($con,$region,$source,$month,$brand,$year,$currencies['id'],$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client,false);

        //$total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies['name'],$region,$agency,$client);

        $total = $viewer->totalFromTable($table);

        $mtx = $viewer->assemble($table,$currencies['id'],$source,$con,$region,$currencies['name']);

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

    	$salesRegion = Request::get('regionExcel');
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

    	$client = json_decode(base64_decode(Request::get('clientExcel')));

    	$month = json_decode(base64_decode(Request::get('monthExcel')));

    	$brands = json_decode(base64_decode(Request::get('brandExcel')));

    	$salesRep = json_decode(base64_decode(Request::get('salesRepExcel')));

    	$currency = Request::get("currencyExcel");
	    $p = new pRate();
        $currencies = $p->getCurrencybyName($con,$currency); 

    	$value = Request::get('valueExcel');

        $in = new insights();

    	$mtx = $in->assemble($con,$sql,$client,$month,$brands,$salesRep,$currency,$value);

        $total = $in->total($con,$sql,$client,$month,$brands,$salesRep,$currencies,$salesRegion,$value);

        //INICIO ID NUMBER

        for ($c=0; $c <sizeof($mtx); $c++) { 
                        
            $clients[$c] = $mtx[$c]['client']; 
        }

        $clients = array_values(array_unique($clients));

        for ($c=0; $c <sizeof($clients); $c++) { 
            $idNumber[$c] =  array(); 
            for ($m=0; $m <sizeof($mtx); $m++) { 

                $temp[$m] = array($mtx[$m]['copyKey'], $mtx[$m]['mediaItem'], $mtx[$m]['client']);


                if ($clients[$c] == $mtx[$m]['client']){
                    array_push($idNumber[$c], $temp[$m]);
                }
            }
        }

        for ($i=0; $i <sizeof($idNumber); $i++) { 
            $idNumber[$i] = array_map('unserialize', array_values( array_unique(array_map('serialize', $idNumber[$i]))));

        }

        $names = array('Copy Key', 'Media Item');

        //FIM ID NUMBER

        $data = array('mtx' => $mtx,'total' => $total, 'idNumber' => $idNumber, 'currency' => $currencies['name'], 'region' => $regions, 'clientExcel' => $client, 'month' => $month, 'brand' => $brands, 'salesRep' => $salesRep, 'value' => $value, 'client' => $clients, 'names' => $names);

        $label = array('exports.viewer.insights.insightsExport','exports.viewer.insights.idNumberExport');

        $title = Request::get('title');

        $typeExport = Request::get("typeExport");

        $auxTitle = Request::get("auxTitle");

        return Excel::download(new insightsExport($data, $label,$auxTitle,$typeExport), $title);

    }
}
