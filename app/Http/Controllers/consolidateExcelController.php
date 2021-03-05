<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\brand;
use App\pRate;
use App\Render;
use App\quarterRender;
use App\resultsMQ;
use App\renderMQ;
use App\client;
use App\agency;
use Validator;
use App\consolidateResults;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\consolidateExport;
use App\Exports\consolidateOfficeExport;
use App\Exports\consolidateDLAExport;


class consolidateExcelController extends Controller{
   
    public function consolidateDLA(){

       $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $b = new brand();
        $pr = new pRate();
        $cl = new client();
        $ag = new agency();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
        $sr = new salesRep();


        $regionCurrencies = $base->currenciesByRegion();

        $cR = new consolidateResults();

        $regionID = json_decode(base64_decode(Request::get('regionExcel')));
        $type = Request::get('typeExcel');

        $title = Request::get("title");
        
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        $typeSelect = json_decode(base64_decode(Request::get("typeSelectExcel")));

        $currencyID = Request::get("currencyExcel");
        $tmp = $pr->getCurrency($con,array($currencyID));
        $currencyIDs = $tmp;

        $value = Request::get( "valueExcel");       

        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;

        $cYear = date('Y');
        $pYear = $cYear - 1;

        switch ($type) {
            case 'brand':
                $brandTmp = $typeSelect;
                $brandID = $base->handleBrand($brandTmp);
                $typeSelect = $brandID;
                $typeSelectS = false;
                break;
            case 'ae':                
                $typeSelect = $typeSelect;
                for ($t=0; $t < sizeof($typeSelect); $t++) { 
                    $typeSelectS[$t] = $sr->getSalesRepById($con,array($typeSelect[$t]))[0];
                }
                break;
            case 'advertiser':                
                $typeSelectS = $cl->getClientByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
                break;
            case 'agency':                               
                $typeSelectS = $ag->getAgencyByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
                break; 
            case 'agencyGroup':  
                $typeSelectS = $ag->getAgencyGroupByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
            default:
                
                break;
        }     

        $years = array($cYear,$pYear);

        $month = $base->getMonth();

        $mtx = $cR->construct($con,$currencyIDs,$month,$type,$typeSelect,$regionID,$value);

        $mtx = $cR->assemble($mtx);

        if($type == 'advertiser' || $type == 'agency' || $type == 'agencyGroup'){
            $newMtx = $cR->newOrder($mtx);           
        }else{
            $newMtx = false;
        }

        $mtxDN = $cR->addDN($mtx);      

        $currencyS = $pr->getCurrencyByRegion($con,array(4))[0]['name'];

        $monthView = array("January","February","March","April","May","June","July","August","September","October","November","December");
        $quarter = array("Q1","Q2","Q3","Q4");

        $years = array($cYear, $pYear);

        $data = array('mtx' => $mtx, 'mtxDN' => $mtxDN, 'currencyS' => $currencyS, 'cYear' => $cYear, 'pYear' => $pYear, 'value' => $value, 'quarter' => $quarter, 'monthView' => $monthView, 'years' => $years,  'typeSelect' => $typeSelect,  'typeSelectS' => $typeSelectS, 'type' => $type, 'brandID' => $brandID);

        $label = 'exports.results.consolidate.consolidateDLAExport';

        return Excel::download(new consolidateDLAExport($data, $label, $typeExport, $auxTitle), $title);
   }


   public function consolidateOffice(){

        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);        
        $cR = new consolidateResults();

        $regionID = json_decode(base64_decode(Request::get('regionExcel')));
        $currencyID = Request::get("currencyExcel");
        $value = Request::get('valueExcel');  

        $title = Request::get("title");
        
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");      

        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;

        $years = array($cYear,$pYear);

        $month = $base->getMonth();

        $typeSelectN = $cR->typeSelectN($con,$r,$regionID);

        $mtx = $cR->constructOffice($con,$currencyID,$month,$regionID,$value,$years);
        $mtx = $cR->assemble($mtx);
        $mtxDN = $cR->addDN($mtx);        

        $currencyS = $pr->getCurrencyByRegion($con,array(4))[0]['name'];

        $monthView = array("January","February","March","April","May","June","July","August","September","October","November","December");
        $quarter = array("Q1","Q2","Q3","Q4");

        $years = array($cYear, $pYear);

        $data = array('typeSelectN' => $typeSelectN, 'mtx' => $mtx, 'mtxDN' => $mtxDN, 'currencyS' => $currencyS, 'cYear' => $cYear, 'pYear' => $pYear, 'value' => $value, 'quarter' => $quarter, 'monthView' => $monthView, 'years' => $years);

        $label = 'exports.results.consolidate.consolidateOfficeExport';

        return Excel::download(new consolidateOfficeExport($data, $label, $typeExport, $auxTitle), $title);
   }

   public function consolidate(){

   		$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $b = new brand();
        $pr = new pRate();
        $cl = new client();
        $ag = new agency();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
        $sr = new salesRep();

        $regionCurrencies = $base->currenciesByRegion();

        $cR = new consolidateResults();

        $regionID = Request::get('regionExcel');
        $type = Request::get('typeExcel');

	    $title = Request::get("title");
	    
	    $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        $typeSelect = json_decode(base64_decode(Request::get("typeSelectExcel")));

        $currencyID = Request::get("currencyExcel");
        $value = Request::get( "valueExcel"); 

        $cYear = date('Y');
        $pYear = $cYear - 1;

        $brandID = 0;

        switch ($type) {
            case 'brand':
                $brandTmp = $typeSelect;
                $brandID = $base->handleBrand($brandTmp);
                $typeSelect = $brandID;
                $typeSelectS = false;
                break;
            case 'ae':                
                $typeSelect = $typeSelect;
                for ($t=0; $t < sizeof($typeSelect); $t++) { 
                    $typeSelectS[$t] = $sr->getSalesRepById($con,array($typeSelect[$t]))[0];
                }
                break;
            case 'advertiser':                
                $typeSelectS = $cl->getClientByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
                break;
            case 'agency':                               
                $typeSelectS = $ag->getAgencyByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
                break; 
            case 'agencyGroup':                               
                $typeSelectS = $ag->getAgencyGroupByRegionWithValue($con,array($regionID),$cYear);
                $typeSelect = $typeSelectS;
                break; 
            default:
                $typeSelect = "all";
                $typeSelectS = false;
                break;
        }  

        $years = array($cYear,$pYear);

        $months = $base->getMonth();

        $mtx = $cR->construct($con,$currency,$months,$type,$typeSelect,$regionID,$value);

        $mtx = $cR->assemble($mtx);

        if($type == 'advertiser' || $type == 'agency' || $type == 'agencyGroup'){
            $newMtx = $cR->newOrder($mtx);           
        }else{
            $newMtx = false;
        }

        $mtxDN = $cR->addDN($mtx);

        $tmp = $r->getRegion($con,array($regionID));
        if(is_array($tmp)){
                $salesRegion = $tmp[0]['name'];
        }else{
                $salesRegion = $tmp['name'];
        }

        $currencyS = $pr->getCurrencyByRegion($con,array($regionID))[0]['name'];

        $month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$quarter = array("Q1","Q2","Q3","Q4");

        $data = array('month' => $month, 'quarter' => $quarter, 'render' => $render, 'region' => $region, 'brand' => $brand, 'currency' => $currency, 'regionCurrencies' => $regionCurrencies,'mtx' => $mtx, 'years' => $years, 'typeSelect' => $typeSelect, 'mtxDN' => $mtxDN, 'salesRegion' => $salesRegion, 'currencyS' => $currencyS, 'value' => $value, 'type' => $type, 'typeSelectS' => $typeSelectS, 'brandID' => $brandID, 'newMtx' => $newMtx);

        $label = 'exports.results.consolidate.consolidateExport';

	    return Excel::download(new consolidateExport($data, $label, $typeExport, $auxTitle), $title);

	}
}
