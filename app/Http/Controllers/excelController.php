<?php

namespace App\Http\Controllers;

use App\Exports\summaryExport;
use App\Exports\monthExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\ytd;
use App\digital;
use App\planByBrand;

use App\dataBase;
use App\region;
use App\brand;
use App\base;
use App\generateExcel;

class excelController extends Controller{
    
        /**
        * @return \Illuminate\Support\Collection
        */

        public function resultsSummary(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = Request::get("regionExcel");
                $r = new region();
                $salesRegion = $r->getRegion($con, array($region));
                $salesRegion = $salesRegion[0]['name'];

                $b = new brand();
                $brands = $b->getBrand($con);

                $tmpCurrency = Request::get("currencyExcel");
                $auxCurrency = json_decode(base64_decode($tmpCurrency));
                $currency[0]['id'] = $auxCurrency[0]->id;
                $currency[0]['name'] = $auxCurrency[0]->name;

                $value = Request::get("valueExcel");

                $year = date('Y');

                $years = array($year, $year-1);

                $base = new base();
                $months = $base->month;

                $ge = new generateExcel();

                if ($salesRegion == "Brazil") {
                        $form = "cmaps";
                }else{
                        $form = "ytd";
                }

                $values = $ge->selectData($con, $region, $years[0], $brands, $form, $currency, $value, $months);
                
                $pValues = $ge->selectData($con, $region, $years[1], $brands, "ytd", $currency, $value, $months);

                for ($p=0; $p < sizeof($pValues[1]); $p++) { 
                        array_push($values[1], $pValues[1][$p]);
                }

                unset($pValues[1]);

                $plan = array("TARGET", "CORPORATE", "ACTUAL");
                
                $valuesPlan = $ge->selectData($con, $region, $years[0], $brands, $plan, $currency, $value, $months);
                
                $final = array($form => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan[0], 'pYtd' => $pValues[0]);

                $title = $salesRegion." - Summary.xlsx";                

                $plans = implode(",", $plan);

                $report[0] = "$salesRegion - TV Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$plans.") Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[3] = "$salesRegion - TV Summary : BKGS - ".$years[1]." (".$currency[0]['name']."/".strtoupper($value).")";

                $BKGS[0] = "TV - ".$years[0];
                $BKGS[1] = "TV - ".$years[1];

                return Excel::download(new summaryExport($final, $report, $salesRegion, $BKGS), $title);
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
