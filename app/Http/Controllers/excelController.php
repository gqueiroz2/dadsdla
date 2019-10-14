<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\dataBase;
use App\region;
use App\brand;
use App\generateExcel;

class excelController extends Controller{
    
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
                
        	$spreadsheet = new Spreadsheet();

                $numbers = $ge->formatValuesArray($value, $form);
                $numbersPlan = $ge->formatValuesArray($value, $plan);
                
                $sheet = $ge->month($spreadsheet, $values, $valuesPlan, $currency, $value, $years[0], $salesRegion, "month", $plan, $numbers, $numbersPlan);
                
                $writer = new Xlsx($spreadsheet);
         
                $filename = 'Results Month';
         
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); 
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output'); // download file*/

	}


}
