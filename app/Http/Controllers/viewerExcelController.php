<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\salesRep;

use App\viewer;
use App\insights;
use App\packets;
use App\pipeline;

use App\pRate;
use App\sql;
use App\dataBase;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\baseExport;
use App\Exports\insightsExport;
use App\Exports\packetsExport;
use App\Exports\pipelineExport;


class viewerExcelController extends Controller {

    public function viewerBase(){
	    $db =  new dataBase();
	    $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $base = new base();
	    $sql = new sql();
        $objPHPExcel = new Spreadsheet();


	    $region = Request::get("regionExcel");
        $r = new region();

        //$region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($region))[0]['name'];

	    $source = Request::get("sourceExcel");

	    $year = json_decode(base64_decode(Request::get("yearExcel")));

	    $month = json_decode(base64_decode(Request::get("monthExcel")));

	    $brand = json_decode(base64_decode(Request::get("brandExcel")));

	    $salesRep = json_decode(base64_decode(Request::get("salesRepExcel")));
        //var_dump($salesRep);
        $manager = json_decode(base64_decode(Request::get("managerExcel")));

	    $agency = json_decode(base64_decode(Request::get("agencyExcel")));

	    $client = json_decode(base64_decode(Request::get("clientExcel")));

        $userRegion = Request::get('userRegionExcel');

        $permission = Request::session()->get('userLevel');
        //$regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');
        //$stage = array($stage);

	    $currency = Request::get("currencyExcel");
	    $p = new pRate();
        $currencies = $p->getCurrency($con,array($currency))[0]['name']; 

	    $value = Request::get("valueExcel");
	    
	    $especificNumber = Request::get("especificNumber");

        if (!is_null($especificNumber) ) {
            $checkEspecificNumber = true;
        }else{
            $checkEspecificNumber = false;
        }

        $checkClient = false; 

	    $viewer = new viewer();

        if ($permission == "L8") {
            $table = $viewer->getTablesReps($con,$region,$source,$month,$brand,$year,$currency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client,false,$user);
        }else{
            $table = $viewer->getTables($con,$region,$source,$month,$brand,$year,$currency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client,$checkClient,$manager);
        }

	    //$table = $viewer->getTables($con,$region,$source,$month,$brand,$year,$currency,$salesRep,$db,$sql,$especificNumber,$checkEspecificNumber,$agency,$client,false);

        //$total = $viewer->total($con,$sql,$source,$brand,$month,$salesRep,$year,$especificNumber,$checkEspecificNumber,$currencies,$region,$agency,$client);
        $total = $viewer->totalFromTable($con,$table,$source,$region,$currencies);
        
        $mtx = $viewer->assemble($table,$currency,$source,$con,$region,$currencies);
        $data = array('mtx' => $mtx, 'currency' => $currency, 'region' => $region, 'source' => strtolower($source), 'year' => $year, 'month' => $month, 'brand' => $brand, 'salesRep' => $salesRep, 'agency' => $agency, 'client' => $client, 'value' => $value, 'total' => $total, 'regions' => $regions, 'currencies' => $currencies, "userRegion" => $userRegion);

        $label = "exports.viewer.base.baseExport";
        
	    $title = Request::get("title");
	    
	    $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");
        //var_dump($regions);

	    return Excel::download(new baseExport($data, $label, $typeExport, $auxTitle), $title);
    }

    public function viewerPackets(){
      // var_dump(Request::all());

        $base = new base();
        $sr = new salesRep();
        $months = $base->month;

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $totalPerPacket = 0;
        $total['digital'] = 0;
        $total['tv'] = 0;
        $total['total'] = 0;

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        $title = Request::get('title');        
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");
        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        //var_dump($rep);
        $r = new region();

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

        $b = new brand();
        $brand = $b->getBrand($con);

        $p = new packets();

        $info = $p->getOptions($con);
        $table = $p->table($con,$sql);

         $label = "exports.viewer.packets.packetsExport";

        if ($table != false) {
            $totalPerPacket = $p->makeTotal($table);

            for ($t=0; $t <sizeof($totalPerPacket); $t++) { 
                $total['digital'] += $table[$t]['digital_value'];
                $total['tv'] += $table[$t]['tv_value'];
                $total['total'] += $totalPerPacket[$t];    
            }
        }    

        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $month = array('January','February','March','April','May','June','July','August','September','October','November','December');

        $data = array('region' => $region,'brand' => $brand, 'info' => $info, 'rep' => $rep, 'table' => $table, 'base' => $base, 'total' => $total, 'totalPerPacket' => $totalPerPacket,'intMonth' => $intMonth,'month' => $month);    

        return  Excel::download(new packetsExport($data,$label,$typeExport,$auxTitle), $title);

    }

    public function viewerPipeline(){
      // var_dump(Request::all());

        $base = new base();
        $sr = new salesRep();
        $months = $base->month;

        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();

        $totalPerPacket = 0;
        $total['digital'] = 0;
        $total['tv'] = 0;
        $total['total'] = 0;

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $year = intval(date('Y'));
        $salesRegion = Request::get("region");
        $title = Request::get('title');        
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");
        $rep = $sr->getSalesRepPackets($con, array($salesRegion),false, $year);
        //var_dump($rep);
        $r = new region();
        $p = new pipeline();
        
        $info = $p->getOptions($con);
        $table = $p->table($con,$sql);

        $label = "exports.viewer.pipeline.pipelineExport";

        $region = $r->getRegion($con,null);
        $regions = $r->getRegion($con,array($salesRegion))[0]['name'];

        $b = new brand();
        $brand = $b->getBrand($con);        

        $info = $p->getOptions($con);
        $table = $p->table($con,$sql);
        //var_dump($info);
        //var_dump($table);
        if ($table != false) {
            $totalPerPacket = $p->makeTotal($table);

            for ($t=0; $t <sizeof($totalPerPacket); $t++) { 
                $total['digital'] += $table[$t]['digital_value'];
                $total['tv'] += $table[$t]['tv_value'];
                $total['total'] += $totalPerPacket[$t];    
            }
        }

        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $month = array('January','February','March','April','May','June','July','August','September','October','November','December');

        $data = array('region' => $region,'brand' => $brand, 'info' => $info, 'rep' => $rep, 'table' => $table, 'base' => $base, 'total' => $total, 'totalPerPacket' => $totalPerPacket,'intMonth' => $intMonth,'month' => $month);    
       // var_dump($table);

        return  Excel::download(new pipelineExport($data,$label,$typeExport,$auxTitle), $title);

    }
}
