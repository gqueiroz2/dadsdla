<?php

namespace App\Http\Controllers;

use App\Exports\ytdExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\dataBase;
use App\region;
use App\brand;
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

                $ge = new generateExcel();

                if ($salesRegion == "Brazil") {
                        $form = "cmaps";
                }else{
                        $form = "ytd";
                }

                $values = $ge->selectData($con, $region, $years, $brands, $form, $currency, $value);
                //var_dump($values[0]);
                return Excel::download(new ytdExport($values[0]), "Summary.xlsx");

                /*$valuesPlan = array($ge->selectData($con, $region, $years, $brands, "TARGET", $currency, $value),
                                    $ge->selectData($con, $region, $years, $brands, "CORPORATE", $currency, $value),
                                    $ge->selectData($con, $region, $years, $brands, "ACTUAL", $currency, $value));


                $finalValuesPlan = $valuesPlan[0][0];

                for ($p=0; $p < sizeof($valuesPlan[1][0]); $p++) { 
                        array_push($finalValuesPlan, $valuesPlan[1][0][$p]);
                }

                for ($p=0; $p < sizeof($valuesPlan[2][0]); $p++) { 
                        array_push($finalValuesPlan, $valuesPlan[2][0][$p]);
                }
                
                /*$styles = $ge->getSheetStyles();

                $spreadsheet = new Spreadsheet();

                $numbers = $ge->formatValuesArray($value, $form);
                $numbersPlan = $ge->formatValuesArray($value, "TARGET");*/

                //return Excel::download(new );

                //$sheet = $ge->summary($spreadsheet, $styles, $values, $finalValuesPlan, $currency, $value, $years[0], $salesRegion, "summary", array("TARGET", "CORPORATE", "ACTUAL"), $numbers, $numbersPlan);

                /*$writer = new Xlsx($spreadsheet);
         
                $filename = 'Summary Month';
         
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); 
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output'); // download file*/

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
