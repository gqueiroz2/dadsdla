<?php

namespace App\Http\Controllers;

use App\Exports\summaryExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\ytd;
use App\cmaps;
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

                $values = $ge->selectData($con, $region, $years, $brands, $form, $currency, $value);
                
                if ($form == "cmaps") {
                        $cmaps = new cmaps();
                        $values[0] = $cmaps->formatColumns($values[0], $months, $currency[0]['name'], $value);
                }else{
                        $ytd = new ytd();
                        $values[0] = $ytd->formatColumns($values[0], $months, $currency[0]['name'], $value);
                }
                
                $digital = new digital();
                $values[1] = $digital->formatColumns($values[1], $months, $currency[0]['name'], $value);
                
                $plan = array("TARGET", "CORPORATE", "ACTUAL");

                $valuesPlan = $ge->selectData($con, $region, $years[0], $brands, $plan, $currency, $value);
                
                $planByBrand = new planByBrand();
                $valuesPlan = $planByBrand->formatColumns($valuesPlan[0], $months, $currency[0]['name']);
                
                $final = array($form => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan);

                $title = $salesRegion." - Summary.xlsx";                
                
                return Excel::download(new summaryExport($final, $salesRegion, $years[0]), $title);
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
                
                $values = $ge->selectData($con, $region, $years, $brands, $secondPos, $currency, $value);
                $valuesPlan = $ge->selectData($con, $region, $years, $brands, $firstPos, $currency, $value);

                $plan = $firstPos;
                $form = $secondPos;
                
                $styles = $ge->getSheetStyles();

        	$spreadsheet = new Spreadsheet();
                
                $numbers = $ge->formatValuesArray($value, $form);
                $numbersPlan = $ge->formatValuesArray($value, $plan);
                
                $sheet = $ge->month($spreadsheet, $styles, $values, $valuesPlan, $currency, $value, $years[0], $salesRegion, "month", $plan, $numbers, $numbersPlan);
                
                $writer = new Xlsx($spreadsheet);
                
                $filename = 'Results Month';
         
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); 
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output'); // download file*/

	}

        


}
