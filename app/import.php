<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class import extends Model{

	public function spread(){

		$import = new importSpreadsheet();
		
		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if( isset($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes) ){

			$arr_file = explode('.', $_FILES['file']['name']);
		    $extension = end($arr_file);
		 
		    if('csv' == $extension) {
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		    }elseif('xls' == $extension) {
		       	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		    }else{
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		    }
		    
		    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		     
		    $sheetData = $spreadsheet->getActiveSheet();
		}else{
			$sheetData = false;
		}

		return $sheetData;

	}


    public function base(){

		$import = new importSpreadsheet();
		
		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if( isset($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes) ){

			$arr_file = explode('.', $_FILES['file']['name']);
		    $extension = end($arr_file);
		 
		    if('csv' == $extension) {
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		    }elseif('xls' == $extension) {
		       	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		    }else{
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		    }
		    
		    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		     
		    $sheetData = $spreadsheet->getActiveSheet()->toArray();
		}else{
			$sheetData = false;
		}

		return $sheetData;
	}

	public function baseControle(){

		$import = new importSpreadsheet();
		
		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if( isset($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes) ){

			$arr_file = explode('.', $_FILES['file']['name']);
		    $extension = end($arr_file);

		    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

		    $reader->setLoadSheetsOnly(["JAN"]);

		    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

		    $loadedSheetNames = $spreadsheet->getSheetNames();

		    var_dump($loadedSheetNames);

		    $count = 0;

		    foreach ($loadedSheetNames as $loadedSheetNames) {
			    $sheetData[$count] = $spreadsheet->getActiveSheet($loadedSheetNames)->toArray();
			    $count++;
		    }

		    //$sheetData = $spreadsheet->getSheet(0)->toArray();
		}else{
			$sheetData = false;
		}

		return $sheetData;

	}
}
