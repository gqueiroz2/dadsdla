<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\dataBase;
use App\base;
use App\region;
use App\resultsMQ;
use App\generateExcel;

class excelController extends Controller{
    
	public function resultsMonth(){
                
                $db = new dataBase();
                $con = $db->openConnection("DLA");

                $region = Request::get("region");
                $r = new region();
                $salesRegion = $r->getRegion($con, array($region));
                $salesRegion = $salesRegion[0]['name'];

                $year = Request::get("year");

                $tmpBrands = Request::get("brand");
                $brands = json_decode(base64_decode($tmpBrands));

                $firstPos = Request::get("firstPos");
                $secondPos = Request::get("secondPos");

                $tmpCurrency = Request::get("currency");
                $auxCurrency = json_decode(base64_decode($tmpCurrency));
                $currency[0]['id'] = $auxCurrency[0]->id;
                $currency[0]['name'] = $auxCurrency[0]->name;

                $value = Request::get("value");

                $mq = new resultsMQ();

                $ge = new generateExcel();

                $values = $ge->selectDataMonth($con, $region, $year, $brands, $secondPos, $currency, $value);
                //var_dump($values);

                $form = $mq->TruncateName($secondPos);

        	$spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet = $ge->month($sheet, $values, $brands, $currency, $value, $year, $form, $salesRegion);
                
                $writer = new Xlsx($spreadsheet);
         
                $filename = 'Results Month';
         
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); 
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output'); // download file*/

	}


}
