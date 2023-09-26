<?php

namespace App\Http\Controllers;

use App\dataBase;
use App\base;
use App\pRate;

use App\region;

use App\DailyResults;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\DailyExport;

class DailyExcelController extends Controller{

	public function dailyExcel(){
    	$base = new base();
    	$pr = new pRate();

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $r = new region();
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $dr = new DailyResults();

        $regionID = Request::get('regionExcel');
        $currencyID = Request::get('currencyExcel');
        $value = Request::get('valueExcel');
        $log = Request::get('logExcel');
        //var_dump(Request::all());

        // == Gera o valor do pRate com base na moeda(currency) e o ano atual == //
        if ($currencyID == '4') {
            $pRate = 1.0;
        }else{
            $pRate = $pr->getPrateByCurrencyAndYear($con, $currencyID, $year = date('Y'));    
        }

        $brlPRate = $pr->getPrateByCurrencyAndYear($con, 1, $year = date('Y'));

        // == Objetos que constroem a matriz para população da tabela == //
        // -- Real Date -- //
        $realDate = $dr->getLog($con, $log, $regionID);
        //var_dump($realDate);
        $total = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate,"total", $currencyID);
        //var_dump($total);
        $disc = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "discovery", $currencyID);
        //var_dump($disc);
        $sony = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "sony", $currencyID);
        //var_dump($total);
        $wm = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "wm", $currencyID);
        //var_dump($sony);
        //$total[] = $disc + $sony + $wm;

        $month = $dr->getActiveMonth();
        $day = date('d', strtotime($log));
        $cYear = date('Y', strtotime($log));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;     

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $actualMonth = $monthForm = $base->intToMonth(array($month))[0];

        if($month == 12 || $month == 11){
            $month = 10;
        }

        $data = array('region' => $region, 'currency' => $currency,'month' => $month,'log' => $log, 'day' => $day,'currencyName' => $currencyName, 'value' => $value, 'cYear' => $cYear, 'pYear' => $pYear, 'ppYear' => $ppYear, 'total' => $total, 'disc' => $disc, 'sony' => $sony, 'realDate' => $realDate,'base' => $base,'wm' => $wm,'actualMonth' => $actualMonth);   

        //var_dump($data['total'][0][0]['currentYTD']);

        $label = 'exports.results.Daily.dailyExport';

        $title = Request::get('title');

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new DailyExport($data, $label, $typeExport, $auxTitle), $title);

	}

	
    
}
