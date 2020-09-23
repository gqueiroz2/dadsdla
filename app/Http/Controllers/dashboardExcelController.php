<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;

use App\renderDashboards;
use Validator;

use App\dashboards;
use App\makeChart;

use App\Exports\bvExport;

class dashboardExcelController extends Controller{

      public function dashBV(){

        	$db = new dataBase();
            $region = new region();
            $dash = new dashboards();
            $currency = new pRate();
            $b = new brand();      
            $render = new renderDashboards();
            $p = new pRate();
            $mc = new makeChart();
            $base = new base();
            $default = $db->defaultConnection();
            $con = $db->openConnection($default);

            $region = Request::get("regionExcel");

            $temp = json_decode(base64_decode(Request::get("agencyExcel")));

            $currency = Request::get("currencyExcel");

            $value = Request::get("valueExcel");

            $title = Request::get("title");

            $typeExport = Request::get("typeExport");
            
            $currencyShow = $p->getCurrency($con, array($currency))[0]['name'];
            $valueShow = strtoupper($value);

            $cYear = intval(date("Y"));
            $pYear = $cYear - 1;
            
            $years = array($cYear);
            $yearsP = array($pYear);
            $yearsBand = array($cYear,$pYear);
            $type = "agencyGroup";

            $agencyGroup = $temp->id;
            $agencyGroupName = $temp->name;
                  
            $startMonthFcst = intval(date('m')) - 1;

            $mountBV = $dash->mountBV($con,$p,$type,$region,$currency,$value,$agencyGroup,$years,"cmaps");
            $graph = $dash->excelBV($base,$mc,$mountBV,$cYear);
            $mountBV = $dash->someTotals($mountBV);
            $forecast = $dash->forecastBV($con,$p,$type,$region,$currency,$value,$agencyGroup,$years,$startMonthFcst);
            $bands = $dash->bandsBV($con,$p,$type,$region,$currency,$value,$agencyGroup,$yearsBand);
            $bvAnalisis = $dash->bvAnalisis($mountBV['current'],$bands[0]);
            $infoPreviousYear = $dash->analisisPreviousYear($con,$p,$type,$region,$currency,$value,$agencyGroup,$yearsP,"cmaps",$bands);
            
            $monthsMidName = array("Jan",
                                    "Feb",
                                    "Mar",
                                    "Apr",
                                    "May",
                                    "Jun",
                                    "Jul",
                                    "Aug",
                                    "Sep",
                                    "Oct",
                                    "Nov",
                                    "Dec"
                                   );
            $data = array('mountBV' => $mountBV, 'graph' => $graph, 'forecast'  => $forecast, 'bands' => $bands, 'bvAnalisis' => $bvAnalisis, 'infoPreviousYear' => $infoPreviousYear, 'monthsMidName' => $monthsMidName, 'ragion' => $region, 'temp' => $temp, 'currency' => $currency, 'value' => $value, 'currencyShow' => $currencyShow, 'valueShow' => $valueShow, 'cYear' => $cYear, 'pYear' => $pYear, 'years' => $years, 'yearsP' => $yearsP, 'yearsBand' => $yearsBand, 'type' => $type, 'agencyGroup' => $agencyGroup, 'agencyGroupName' => $agencyGroupName, 'startMonthFcst' => $startMonthFcst);

            $label = 'exports.dashboards.bvExport';

            $auxTitle = $title;

           return Excel::download(new bvExport($data, $label, $typeExport, $auxTitle), $title);
      }
}
