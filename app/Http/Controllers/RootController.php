<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\documentsHead;
use App\importSpreadsheet;
use App\Imports\testImport;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class RootController extends Controller
{

	public function getTest(){
		return view('getTest');

	}

	public function postTest(){

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

		    $mtx = $import->import($sheetData);

		    var_dump($mtx);
		}
	}

    public function home(){

        $db = new dataBase();

        $con = $db->openConnection("root");

        $sql = "SELECT * FROM teste";

        $res = $con->query($sql);       

        return view("welcome");

    }
}
