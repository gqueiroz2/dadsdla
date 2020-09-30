<?php

namespace App\Http\Controllers;
/*
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
*/

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Exports\bvExport;

class dashboardExcelController extends Controller{

      public function dashBV(){
/*
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
*/
           //return Excel::download(new bvExport($data, $label, $typeExport, $auxTitle), $title);


// Change these values to select the Rendering library that you wish to use
Settings::setChartRenderer(\PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph::class);

$inputFileType = 'Xlsx';
$inputFileNames = __DIR__ . '/../templates/32readwrite*[0-9].xlsx';

if ((isset($argc)) && ($argc > 1)) {
    $inputFileNames = [];
    for ($i = 1; $i < $argc; ++$i) {
        $inputFileNames[] = __DIR__ . '/../templates/' . $argv[$i];
    }
} else {
    $inputFileNames = glob($inputFileNames);
}
foreach ($inputFileNames as $inputFileName) {
    $inputFileNameShort = basename($inputFileName);

    if (!file_exists($inputFileName)) {
        $helper->log('File ' . $inputFileNameShort . ' does not exist');

        continue;
    }

    $helper->log("Load Test from $inputFileType file " . $inputFileNameShort);

    $reader = IOFactory::createReader($inputFileType);
    $reader->setIncludeCharts(true);
    $spreadsheet = $reader->load($inputFileName);

    $helper->log('Iterate worksheets looking at the charts');
    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
        $sheetName = $worksheet->getTitle();
        $helper->log('Worksheet: ' . $sheetName);

        $chartNames = $worksheet->getChartNames();
        if (empty($chartNames)) {
            $helper->log('    There are no charts in this worksheet');
        } else {
            natsort($chartNames);
            foreach ($chartNames as $i => $chartName) {
                $chart = $worksheet->getChartByName($chartName);
                if ($chart->getTitle() !== null) {
                    $caption = '"' . implode(' ', $chart->getTitle()->getCaption()) . '"';
                } else {
                    $caption = 'Untitled';
                }
                $helper->log('    ' . $chartName . ' - ' . $caption);

                $jpegFile = $helper->getFilename('35-' . $inputFileNameShort, 'png');
                if (file_exists($jpegFile)) {
                    unlink($jpegFile);
                }

                try {
                    $chart->render($jpegFile);
                    $helper->log('Rendered image: ' . $jpegFile);
                } catch (Exception $e) {
                    $helper->log('Error rendering chart: ' . $e->getMessage());
                }
            }
        }
    }

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
}




      }
}
