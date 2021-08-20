<?php

namespace App\Http\Controllers;

use App\region;
use App\salesRep;
use App\forecast;
use App\base;
use App\PAndRRender;
use App\forecastRender;

use App\pRate;
use App\sql;
use App\dataBase;

//use App\excel;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\forecastExport;


class forecastExcelController extends Controller{


	public function forecastAE(){
                $db = new dataBase();
                $render = new forecastRender();
                $r = new region();
                $pr = new pRate();
                $fcst = new forecast();        
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

                $cYear = intval( Request::get('yearExcel') );
                $pYear = $cYear - 1;
                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);

                $regionID = Request::get('regionExcel');
                
                $salesRepID = array(Request::get('salesRepExcel'));
                
                $currencyID = Request::get('currencyExcel');
                
                $value = Request::get('valueExcel');

                $regionName = Request::get('userRegionExcel');

                $typeExport = 'Excel';

                $title = Request::get('title');

                $auxTitle = $title;

                $tmp = $fcst->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);

                $forRender = $tmp;

                $totalTarget = 0.0;

                //$totalTarget += $forRender['targetValues'];

                $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

                $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');
                
                $head = array('Closed','$Cons.','Prop','Fcast','Total');

                $data = array('regionName' => $regionName,'regionID' => $regionID, 'salesRepID' => $salesRepID, 'currencyID' => $currencyID, 'value' => $value, 'regionName' => $regionName, 'tmp' => $tmp, 'forRender' => $forRender, 'cYear' => $cYear, 'pYear' => $pYear, 'month' => $month, 'channel' => $channel, 'head' => $head, 'totalTarget' => $totalTarget);

                $label = "exports.PandR.ForecastAE.forecastExport";

                return Excel::download(new forecastExport($data, $label, $typeExport, $auxTitle), $title);
	}
    
}
