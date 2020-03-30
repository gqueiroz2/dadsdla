<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\dataBase;

use App\resultsResume;
use App\resultsMQ;
use App\resultsYoY;
use App\resultsMonthlyYoY;

use App\Exports\summaryExport;
use App\Exports\monthExport;
use App\Exports\quarterExport;
use App\Exports\yoyBrandExport;
use App\Exports\yoyMonthExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class resultsExcelController extends Controller{

        public function resultsSummary(){

                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

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
                $auxTitle = Request::get("auxTitle");

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

                $typeExport = Request::get("typeExport");

                return Excel::download(new summaryExport($data, $labels, $auxData, $typeExport, $auxTitle), $title);
        }

        public function resultsMonth(){
                
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

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
                $year = json_decode(base64_decode(Request::get("yearExcel")));

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
                
                $mq = new resultsMQ();
                $lines = $mq->lines($con,$currency,$months,$secondPos,$brands,$year,$region,$value,$firstPos);

                $mtx = $mq->assembler($con,$brands,$lines,$months,$year,$firstPos);

                $data = array('mtx' => $mtx, 'currency' => $currency, 'region' => $salesRegion, 'year' => $year, 'value' => $value);

                $label = "exports.results.month.monthExport";

                $typeExport = Request::get("typeExport");
                $auxTitle = Request::get("auxTitle");

                return Excel::download(new monthExport($data, $label, $typeExport, $auxTitle), $title);
        }

	public function resultsQuarter(){
                
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

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
                $year = Request::get("yearExcel");

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

                $brands = json_decode(base64_decode(Request::get("brandsExcel")));

                //nome do excel e do relatorio
                $title = Request::get("title");

                $mq = new resultsMQ();
                
                $lines = $mq->lines($con,$currency,$months,$secondPos,$brands,$year,$region,$value,$firstPos);

                $matrix = $mq->assemblerQuarters($con,$brands,$lines,$months,$year,$firstPos);

                $data = array("mtx" => $matrix, "currency" => $currency, "value" => $value, "year" => $year, "form" => $firstPos, "region" => $salesRegion);

                $label = "exports.results.quarter.quarterExport";

                $typeExport = Request::get("typeExport");
                $auxTitle = Request::get("auxTitle");

                return Excel::download(new quarterExport($data, $label, $typeExport, $auxTitle), $title);
	}

        public function resultsYoYBrand(){

                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{  
                        $salesRegion = $tmp['name'];
                }

                $brands = json_decode(base64_decode(Request::get("brandsExcel")));
                
                unset($brands[sizeof($brands)-1]);

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
                
                $yoy = new resultsYoY();

                $lines = $yoy->lines($con, $currency, $months, $firstPos, $brands, $years, $region, $value, $secondPos);

                //criando matriz que será renderizada     
                $matrix = $yoy->assemblers($brands, $lines, $months, $years, $secondPos);
                
                $data = array("mtx" => $matrix, "currency" => $currency, "value" => $value, "year" => $years, "form" => $firstPos, "region" => $salesRegion, "brands" => $brands);

                $label = "exports.results.yoy.brand.brandExport";

                //nome do excel e do relatorio
                $title = Request::get("title");
                //var_dump($matrix);

                $typeExport = Request::get("typeExport");
                $auxTitle = Request::get("auxTitle");

                return Excel::download(new yoyBrandExport($data, $label, $typeExport, $auxTitle), $title);
        }

        public function resultsYoYMonth(){

                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

                $region = Request::get("regionExcel");

                $r = new region();
                $tmp = $r->getRegion($con,array($region));

                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{  
                        $salesRegion = $tmp['name'];
                }

                $brands = json_decode(base64_decode(Request::get("brandsExcel")));
                
                //unset($brands[sizeof($brands)-1]);

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
                
                $monthlyYoY = new resultsMonthlyYoY();

                //pegando valores das colunas das tabelas
                $lines = $monthlyYoY->lines($con, $currency, $months, $firstPos, $brands, $years, $region, $value, $secondPos);

                //criando matriz que será renderizada     
                $matrix = $monthlyYoY->assemblers($brands, $lines, $months, $years, $secondPos);
                
                $data = array("mtx" => $matrix[0], "quarter" => $matrix[1], "currency" => $currency, "value" => $value, "year" => $years, "form" => $secondPos, "region" => $salesRegion, "brands" => $brands, "months" => $months);

                $labels = array("exports.results.yoy.month.monthExport", "exports.results.yoy.month.semesterExport");

                //nome do excel e do relatorio
                $title = Request::get("title");
                //var_dump($matrix[1]);

                $typeExport = Request::get("typeExport");
                $auxTitle = Request::get("auxTitle");

                return Excel::download(new yoyMonthExport($data, $labels, $typeExport, $auxTitle), $title);
        }

        public function resultsShare(){

                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);

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

}
