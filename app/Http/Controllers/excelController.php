<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\dataBase;
use App\resultsMQ;

class excelController extends Controller{
    
	public function test(){
                
                $region = Request::get("region");
                $year = Request::get("year");
                
                $tmpBrands = Request::get("brand");
                $brands = json_decode(base64_decode($tmpBrands));

                $firstPos = Request::get("firstPos");
                $secondPos = Request::get("secondPos");

                $tmpCurrency = Request::get("currency");
                $currency = json_decode(base64_decode($tmpCurrency));

                $value = Request::get("value");

                $db = new dataBase();
        	$con = $db->openConnection("DLA");

                $mq = new resultsMQ();

                $lines = $mq->lines($con,$tmp,$month,$secondPos,$brandID,$year,$regionID,$value,$firstPos);

                $mtx = $mq->assembler($con,$brandID,$lines,$month,$year,$firstPos);

        	/*$spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Hello World !');
                
                $writer = new Xlsx($spreadsheet);
         
                $filename = 'name-of-the-generated-file';
         
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); 
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output'); // download file*/

	}


}
