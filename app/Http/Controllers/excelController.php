<?php

namespace App\Http\Controllers;

use App\Exports\summaryExport;
use App\Exports\monthExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\generateExcel;
use App\dataBase;
use App\region;
use App\brand;
use App\base;

class excelController extends Controller{

        public function resultsSummary(){

                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{
                        $salesRegion = $tmp['name'];
                }

                $years = json_decode(base64_decode(Request::get("yearExcel")));
                
                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));
                $currency[0]['id'] = $tmp[0]->id;
                $currency[0]['name'] = $tmp[0]->name;
                $currency[0]['region'] = $tmp[0]->region;

                $value = Request::get("valueExcel");
                $title = Request::get("title");

                $b = new brand();
                $brand = $b->getBrand($con);

                $base = new base();
                $month = $base->getMonth();

                $ge = new generateExcel();

                if ($salesRegion == "Brazil") {
                        $form = "cmaps";
                }else{
                        $form = "ytd";
                }

                $firstTable = $ge->selectData($con, $region, $years[0], $brand, $form, $currency, $value, $month);
                        
                $secondTable = $ge->selectData($con, $region, $years[1], $brand, "ytd", $currency, $value, $month);

                for ($p=0; $p < sizeof($secondTable[1]); $p++) { 
                        array_push($firstTable[1], $secondTable[1][$p]);
                }

                unset($secondTable[1]);

                $plan = array("TARGET", "CORPORATE", "ACTUAL");
                
                $planTable = $ge->selectData($con, $region, $years[0], $brand, $plan, $currency, $value, $month);
                
                $final = array($form => $firstTable[0], 'digital' => $firstTable[1], 'plan' => $planTable[0], 'pYtd' => $secondTable[0]);
                
                
                $plans = implode(",", $plan);

                $report[0] = "$salesRegion - TV Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$plans.") Summary : BKGS - ".$years[0]." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[3] = "$salesRegion - TV Summary : BKGS - ".$years[1]." (".$currency[0]['name']."/".strtoupper($value).")";

                $BKGS[0] = "TV - ".$years[0];
                $BKGS[1] = "TV - ".$years[1];

                return Excel::download(new summaryExport($final, $report, $salesRegion, $BKGS), $title);
        }

        public function resultsQuarter(){
                $this->resultsMonth();
        }

	public function resultsMonth(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{
                        $salesRegion = $tmp['name'];
                }

                $years = json_decode(base64_decode(Request::get("yearExcel")));

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                $firstPos = Request::get("firstPosExcel");
                $secondPos = Request::get("secondPosExcel");

                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

                var_dump($tmp);
                /*
                $currency[0]['id'] = $tmp[0]->id;
                $currency[0]['name'] = $tmp[0]->name;
                $currency[0]['region'] = $tmp[0]->region;

                $value = Request::get("valueExcel");
                $title = Request::get("title");

                $ge = new generateExcel();
                
                $values = $ge->selectData($con, $region, $years, $brands, $secondPos, $currency, $value, $months);
                
                $valuesPlan = $ge->selectData($con, $region, $years, $brands, $firstPos, $currency, $value, $months);
                
                $final = array($secondPos => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan[0]);

                $title = $salesRegion." - Month.xlsx";

                $report[0] = "$salesRegion - TV Month : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Month : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$firstPos.") Month : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new monthExport($final, $report, $salesRegion), $title);
                */

	}



}
