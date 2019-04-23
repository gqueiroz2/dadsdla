<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class import extends Model{
    public function base(){

		$import = new importSpreadsheet();
		
		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if( isset($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes) ){

			$arr_file = explode('.', $_FILES['file']['name']);
		    $extension = end($arr_file);
		 
		    if('csv' == $extension) {
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		    } else {
		        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		    }
		 
		    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		     
		    $sheetData = $spreadsheet->getActiveSheet()->toArray();
		}else{
			$sheetData = false;
		}

		return $sheetData;
	}
}
