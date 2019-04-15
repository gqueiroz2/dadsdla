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

    public function home(){

        $db = new dataBase();

        $con = $db->openConnection("root");

        $sql = "SELECT * FROM teste";

        $res = $con->query($sql);       

        return view("welcome");

    }
}
