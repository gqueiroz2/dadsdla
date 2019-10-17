<?php

namespace App\Http\Controllers;

use App\Exports\summaryExport;
use App\Exports\monthExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class excelController extends Controller{

        public function resultsSummary(){

                $title = Request::get("title");
                $region = Request::get("regionExcel");
                $years = json_decode(base64_decode(Request::get("yearExcel")));
                $currency = json_decode(base64_decode(Request::get("currencyExcel")));
                $final = json_decode(base64_decode(json_encode(Request::get("finalExcel"))), true);
                $value = Request::get("valueExcel");
                $plan = json_decode(base64_decode(Request::get("planExcel")));

                $plans = implode(",", $plan);

                $report[0] = "$region - TV Summary : BKGS - ".$years[0]." (".$currency[0]->name."/".strtoupper($value).")";
                $report[1] = "$region - Digital Summary : BKGS - ".$years[0]." (".$currency[0]->name."/".strtoupper($value).")";
                $report[2] = "$region - (".$plans.") Summary : BKGS - ".$years[0]." (".$currency[0]->name."/".strtoupper($value).")";
                $report[3] = "$region - TV Summary : BKGS - ".$years[1]." (".$currency[0]->name."/".strtoupper($value).")";

                $BKGS[0] = "TV - ".$years[0];
                $BKGS[1] = "TV - ".$years[1];

                return Excel::download(new summaryExport($final, $report, $region, $BKGS), $title);
        }

	public function resultsMonth(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = Request::get("regionExcel");
                $r = new region();
                $salesRegion = $r->getRegion($con, array($region));
                $salesRegion = $salesRegion[0]['name'];

                $year = Request::get("yearExcel");

                $years = array($year, $year-1);

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                $firstPos = Request::get("firstPosExcel");
                $secondPos = Request::get("secondPosExcel");

                $tmpCurrency = Request::get("currencyExcel");
                $auxCurrency = json_decode(base64_decode($tmpCurrency));
                $currency[0]['id'] = $auxCurrency[0]->id;
                $currency[0]['name'] = $auxCurrency[0]->name;

                $value = Request::get("valueExcel");

                $ge = new generateExcel();
                
                $values = $ge->selectData($con, $region, $years, $brands, $secondPos, $currency, $value, $months);
                $valuesPlan = $ge->selectData($con, $region, $years, $brands, $firstPos, $currency, $value, $months);
                
                $final = array($secondPos => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan[0]);

                $title = $salesRegion." - Month.xlsx";

                $report[0] = "$salesRegion - TV Month : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Month : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$firstPos.") Month : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new monthExport($final, $report, $salesRegion), $title);

	}

}
