<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\PAndRRender;
use App\AE;

use App\pRate;
use App\sql;
use App\dataBase;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\aeExport;


class aeExcelController extends Controller{
    
    public function aeView(){
        $db = new dataBase();
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = intval( Request::get('yearExcel') );
        $pYear = $cYear - 1;

        $title = Request::get("title");

        $typeExport = Request::get("typeExport");
    
        $auxTitle = Request::get("auxTitle");

        $currencyID = Request::get('currencyExcel');

        $regionID = Request::get('regionExcel');
        
        $value = Request::get('valueExcel');
        
        $salesRepID = array(Request::get('salesRepExcel'));

        $userRegion = Request::get('userRegionExcel');

        $tmp = $ae->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);

        $forRender = $tmp; 

        $sourceSave = $forRender['sourceSave'];
        $tfArray = array();
        $odd = array();
        $even = array();

        $error = false;

        //lines of sales rep table
        /*$rollingSalesRep = $forRender['executiveRevenueCYear'];
        $pending = $forRender['pending'];
        $RFvsTarget = $forRender['RFvsTarget'];*/

        $totalTarget = 0.0;

     	$month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

        $splitted = $forRender['splitted'];

     	for ($c=0; $c <sizeof($forRender['client']) ; $c++) {
		 	 if($splitted){
                if($splitted[$c]['splitted']){
                    $clr = "lightBlue";
                }else{
                    $clr = "lightBlue";
                }                        
            }else{
                $clr = "lightBlue";                    
            }

            if($splitted){
                if($splitted[$c]['splitted']){
                    if(is_null($splitted[$c]['owner'])){
                        $ow = "(?)";
                    }else{
                        if($splitted[$c]['owner']){
                            $ow = "(P)";
                        }else{
                            $ow = "(S)";
                        }
                    }
                }else{
                    $ow = "";
                }
            }else{
                $ow = false;
            }
     	}
     	
        $label = "exports.PandR.AE.aeExport";

       	$data = array('forRender' => $forRender, 'tfArray' => $tfArray, 'error' => $error, 'cYear' => $cYear, "pYear" => $pYear, "odd" => $odd, "even" => $even, "tfArray" => $tfArray, "month" => $month, 'ow' => $ow, 'userRegion' => $userRegion);

       	return Excel::download(new aeExport($data, $label, $typeExport, $auxTitle), $title);
    }
}
