<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\dataBase;

use App\resultsResume;
use App\resultsMQ;

use App\Exports\summaryExport;
use App\Exports\monthExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class resultsExcelController extends Controller{

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

                $resume = new resultsResume();

                $currentMonth = intval(date('m'));

                $brands = json_decode(base64_decode(Request::get("brandsExcel")));

                $cYear = intval(date('Y') );
                $pYear = $cYear - 1;

                if (empty($brands[0])) {
                        $Digital = $resume->generateVectorDigital($con, $brands[1], $month, $currentMonth, $value, $cYear, $pYear, $region, $currency[0]['id'], $salesRegion);

                        $matrixDigital = $resume->assembler($month,$Digital["salesCYear"],$Digital["actual"],$Digital["target"],$Digital["corporate"]/*$pAndR,$finance*/,$Digital["previousYear"]);

                        $DN = $resume->grouper(null,$Digital);

                        $matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);

                        $data = array('Digital' => $matrixDigital, 'DN' => $matrixDN);
                        $auxData = array("Digital", "DN");
                }

                elseif (empty($brands[1])) {
                        $TV = $resume->generateVectorsTV($con, $brands[0], $month, $currentMonth, $value, $cYear, $pYear, $region, $currency[0]['id'], $salesRegion);

                        $matrixTV = $resume->assembler($month,$TV["salesCYear"],$TV["actual"],$TV["target"],$TV["corporate"]/*$pAndR,$finance*/,$TV["previousYear"]);   

                        $DN = $resume->grouper($TV,null);

                        $matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);

                        $data = array('TV' => $matrixTV, 'DN' => $matrixDN);
                        $auxData = array("TV", "DN");
                }else{
                        $TV = $resume->generateVectorsTV($con, $brands[0], $month, $currentMonth, $value, $cYear, $pYear, $region, $currency[0]['id'], $salesRegion);

                        $matrixTV = $resume->assembler($month,$TV["salesCYear"],$TV["actual"],$TV["target"],$TV["corporate"]/*$pAndR,$finance*/,$TV["previousYear"]);
                        
                        $Digital = $resume->generateVectorDigital($con, $brands[1], $month, $currentMonth, $value, $cYear, $pYear, $region, $currency[0]['id'], $salesRegion);

                        $matrixDigital = $resume->assembler($month,$Digital["salesCYear"],$Digital["actual"],$Digital["target"],$Digital["corporate"]/*$pAndR,$finance*/,$Digital["previousYear"]);

                        $DN = $resume->grouper($TV,$Digital);

                        $matrixDN = $resume->assembler($month,$DN["salesCYear"],$DN["actual"],$DN["target"],$DN["corporate"]/*$pAndR,$finance*/,$DN["previousYear"]);

                        $data = array('TV' => $matrixTV, 'Digital' => $matrixDigital, 'DN' => $matrixDN);
                        $auxData = array("TV", "Digital", "DN");
                }

                $data['currency'] = $currency;
                $data['region'] = $salesRegion;
                $data['value'] = $value;
                $data['year'] = $cYear;

                $labels = "exports.results.summary.summaryExport";

                return Excel::download(new summaryExport($data, $labels, $auxData), $title);
        }

        public function resultsMonth(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                //id da região
                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{
                        $salesRegion = $tmp['name'];
                }

                //ano consultado
                $years = json_decode(base64_decode(Request::get("yearExcel")));

                $base = new base();
                $months = $base->month;

                //primeira posição (target ou corporate)
                $firstPos = Request::get("firstPosExcel");
                //segunda posição (booking ou cmaps)
                $secondPos = Request::get("secondPosExcel");

                //currency da pesquisa
                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));
                $currency[0]['id'] = $tmp[0]->id;
                $currency[0]['name'] = $tmp[0]->name;
                $currency[0]['region'] = $tmp[0]->region;

                //gross ou net
                $value = Request::get("valueExcel");

                $brands = json_decode(base64_decode(Request::get("brandsExcel")));

                //nome do excel e do relatorio
                $title = Request::get("title");
                var_dump($brands);
                //return Excel::download(new monthExport($final, $report, $salesRegion), $title);

        }

	public function resultsMQ(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                //id da região
                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{
                        $salesRegion = $tmp['name'];
                }

                //ano consultado
                $years = json_decode(base64_decode(Request::get("yearExcel")));

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                //primeira posição (target ou corporate)
                $firstPos = Request::get("firstPosExcel");
                //segunda posição (booking ou cmaps)
                $secondPos = Request::get("secondPosExcel");

                //currency da pesquisa
                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));
                $currency[0]['id'] = $tmp[0]->id;
                $currency[0]['name'] = $tmp[0]->name;
                $currency[0]['region'] = $tmp[0]->region;

                //gross ou net
                $value = Request::get("valueExcel");
                //nome do excel e do relatorio
                $title = Request::get("title");

                $ge = new generateExcel();
                
                $values = $ge->selectDataResults($con, $region, $years, $brands, $secondPos, $currency, $value, $months);
                $valuesPlan = $ge->selectDataResults($con, $region, $years, $brands, $firstPos, $currency, $value, $months);
                
                $final = array($secondPos => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan[0]);

                $name = Request::get("name");

                $report[0] = "$salesRegion - TV $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$firstPos.") $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new monthExport($final, $report, $salesRegion), $title);

	}

        public function resultsYoY(){

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

                //ano consultado
                $years = json_decode(base64_decode(Request::get("yearExcel")));

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                //primeira posição (target ou corporate)
                $firstPos = Request::get("firstPosExcel");
                //segunda posição (booking ou cmaps)
                $secondPos = Request::get("secondPosExcel");

                //currency da pesquisa
                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

                $currency[0]['id'] = $tmp->id;
                $currency[0]['name'] = $tmp->name;
                $currency[0]['region'] = $tmp->region;

                //gross ou net
                $value = Request::get("valueExcel");
                //nome do excel e do relatorio
                $title = Request::get("title");

                $ge = new generateExcel();
                
                $values = $ge->selectDataResults($con, $region, $years, $brands, $firstPos, $currency, $value, $months);
                $values2 = $ge->selectDataResults($con, $region, ($years-1), $brands, $firstPos, $currency, $value, $months);
                $valuesPlan = $ge->selectDataResults($con, $region, $years, $brands, $secondPos, $currency, $value, $months);
                
                for ($v2=0; $v2 < sizeof($values2[0]); $v2++) {
                        array_push($values[0], $values2[0][$v2]); 
                }

                for ($v2=0; $v2 < sizeof($values2[1]); $v2++) {
                        array_push($values[1], $values2[1][$v2]); 
                }

                unset($values2);

                $final = array($firstPos => $values[0], 'digital' => $values[1], 'plan' => $valuesPlan[0]);

                $title = $salesRegion." - ".$title;

                $name = Request::get("name");

                $report[0] = "$salesRegion - TV YoY $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital YoY $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[2] = "$salesRegion - (".$secondPos.") YoY $name : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new yoyExport($final, $report, $salesRegion), $title);
        }

        public function resultsShare(){

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

                //ano consultado
                $years = Request::get("yearExcel");

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

                $currency[0]['id'] = $tmp->id;
                $currency[0]['name'] = $tmp->name;

                //gross ou net
                $value = Request::get("valueExcel");
                //nome do excel e do relatorio
                $title = Request::get("title");
                $source = Request::get("sourceExcel");

                $ge = new generateExcel();

                if ($source == "IBMS") {
                        $newSource = "ytd";
                }

                $values = $ge->selectDataResults($con, $region, $years, $brands, $newSource, $currency, $value, $months);

                $final = array($newSource => $values[0], 'digital' => $values[1]);                

                $report[0] = "$salesRegion - TV Share : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Share : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new shareExport($final, $report, $salesRegion), $title);
        }

        public function performanceCore(){
                
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

                //ano consultado
                $years = Request::get("yearExcel");

                $base = new base();
                $months = $base->month;

                $b = new brand();
                $brands = $b->getBrand($con);

                $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

                $currency[0]['id'] = $tmp->id;
                $currency[0]['name'] = $tmp->name;

                //gross ou net
                $value = Request::get("valueExcel");
                //nome do excel e do relatorio
                $title = Request::get("title");

                $ge = new generateExcel();

                $values = $ge->selectDataResults($con, $region, $years, $brands, "ytd", $currency, $value, $months);

                $valuesPlan = $ge->selectDataResults($con, $region, $years, $brands, "sales", $currency, $value, $months);

                $final = array("ytd" => $values[0], 'digital' => $values[1], "sales" => $valuesPlan[0]);

                $report[0] = "$salesRegion - TV Performance Core : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";
                $report[1] = "$salesRegion - Digital Performance Core : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                $report[2] = "$salesRegion - Plan by Sales Performance Core : BKGS - ".$years." (".$currency[0]['name']."/".strtoupper($value).")";

                return Excel::download(new coreExport($final, $report, $salesRegion), $title);
        }

}
