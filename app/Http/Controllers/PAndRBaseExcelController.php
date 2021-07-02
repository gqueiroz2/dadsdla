<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\AE;
use App\sql;
use App\baseReportPandR;
use App\PAndRBaseReportRender;

use App\Exports\PandRBaseExport;

class PAndRBaseExcelController extends Controller{

    public function baseReport(){

    	$db = new dataBase();
        $render = new PAndRBaseReportRender();                
        $r = new region();
        $pr = new pRate();
        $br = new baseReportPandR();        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $regionID = Request::get('regionExcel');
        $salesRepID = json_decode(base64_decode(Request::get('salesRepExcel')));
        $currencyID = Request::get('currencyExcel');
        $value = Request::get('valueExcel');
        $baseReport = Request::get('baseReportExcel'); 
        $cYear = Request::get('yearExcel');
        $title = Request::get("title");
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");
        $userRegion = Request::get('userRegionExcel');


        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $forRender = $br->baseLoadReport($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value,$baseReport);

        switch ($baseReport) {
            case 'brand':
                $baseReportView = "Brand";
                break;
            case 'ae':
                $baseReportView = "Account Executives"; 
                break;
            case 'client':
                $baseReportView = "Advertiser";
                break;
            case 'agency':
                $baseReportView = "Agency";
                break;
            case 'agencyGroup':
                $baseReportView = "Agency Group";
                break;
        }

        $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

	    $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');

	    $head = array('Closed','$Cons.','Prop','Fcast','Total');

       	$data = array('forRender' => $forRender, 'currency' => $currency, 'region' => $region, 'baseReportView' => $baseReportView, 'pYear' => $pYear, "cYear" => $cYear, 'month' => $month, 'channel' => $channel, 'head' => $head,'baseReport' => $baseReport, 'userRegion' => $userRegion);

       	$label = "exports.PandR.baseReport.PandRBaseExport";

       	return Excel::download(new PandRBaseExport($data, $label, $typeExport, $auxTitle), $title);
    }
}
