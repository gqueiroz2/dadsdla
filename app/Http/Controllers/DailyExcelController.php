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
                $pRate = $pr->getPrateByCurrencyAndYear($con, $currencyID, $year = date('Y'));
                //var_dump($pRate);

                // == Objetos que constroem a matriz para população da tabela == //
                // -- Real Date -- //
                $realDate = $dr->getLog($con, $log, $regionID);
                //var_dump($realDate);
                $total = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "total");
                //var_dump($total);
                $disc = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "discovery");
                //var_dump($disc);
                $sony = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "sony");
                //var_dump($total);
                //var_dump($sony);

                $month = date('m', strtotime($log));
                $day = date('d', strtotime($log));
                $cYear = date('Y', strtotime($log));
                $pYear = $cYear - 1;
                $ppYear = $pYear - 1;     

                $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];	

                $data = array('region' => $region, 'currency' => $currency,'month' => $month,'log' => $log, 'day' => $day,'currencyName' => $currencyName, 'value' => $value, 'cYear' => $cYear, 'pYear' => $pYear, 'ppYear' => $ppYear, 'total' => $total, 'disc' => $disc, 'sony' => $sony, 'realDate' => $realDate,'base' => $base);   

                //var_dump($data['total'][0][0]['currentYTD']);

                $label = 'exports.results.Daily.dailyExport';

                $title = Request::get('title');

                $typeExport = Request::get("typeExport");
                $auxTitle = Request::get("auxTitle");

                return Excel::download(new DailyExport($data, $label, $typeExport, $auxTitle), $title);

	}

	
    
}
