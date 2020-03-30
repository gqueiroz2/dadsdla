<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\dataBase;

use App\rankingBrand;
use App\subBrandRanking;

use App\rankingMarket;
use App\subMarketRanking;

use App\rankingChurn;
use App\subChurnRanking;

use App\rankingNew;
use App\subNewRanking;

use App\rankings;
use App\subRankings;

use App\Exports\rankingBrandExport;
use App\Exports\rankingMarketExport;
use App\Exports\rankingChurnExport;
use App\Exports\rankingNewExport;
use App\Exports\rankingExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class rankingExcelController extends Controller {
    
	public function brand(){

        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionExcel");
      
        $r = new region();
        $tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $salesRegion = $tmp[0]['name'];
        }else{  
            $salesRegion = $tmp['name'];
        }

        $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

        $currency[0]['id'] = $tmp[0]->id;
        $currency[0]['name'] = $tmp[0]->name;
        $currency[0]['region'] = $tmp[0]->region;

        $type = Request::get("typeExcel");

        $months = json_decode(base64_decode(Request::get("monthsExcel")));

        $brands = json_decode(base64_decode(Request::get("brandsExcel")));

        $value = Request::get("valueExcel");

        $rankingBrand = new rankingBrand();

        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;

        $years = array($cYear, $pYear);

        $brandsFinal = $rankingBrand->mountBrands($brands);

        $values = $rankingBrand->getAllResults($con, $salesRegion, $region, $brandsFinal, $value, $months, $currency, $years);

        $mtx = $rankingBrand->assembler($values, $years, $brands);

        array_push($years, $pYear-1);

        $title = Request::get("title");
        
        $subBrandRanking = new subBrandRanking();

        for ($b=0; $b < sizeof($brands); $b++) { 
        	$brandsMtx[$b] = $subBrandRanking->getSubResults($con, $type, $region, $value, $months, $currency, $brands[$b][1], $brands);
        }
        
        array_push($brandsMtx, $subBrandRanking->getSubResults($con, $type, $region, $value, $months, $currency, "DN", $brands));

        $types = array();

        for ($b=0; $b < sizeof($brandsMtx); $b++) {
        	$types[$b] = array();
        	for ($m=0; $m < sizeof($brandsMtx[$b]); $m++) { 
		        if (is_array($brandsMtx[$b][$m])) {
		            for ($r2=0; $r2 < sizeof($brandsMtx[$b][$m]); $r2++) { 
		                if (!in_array($brandsMtx[$b][$m][$r2][$type], $types[$b])) {
		                    array_push($types[$b], $brandsMtx[$b][$m][$r2][$type]);
		                }
		            }   
		        }
		    }
        }

        for ($b=0; $b < sizeof($brandsMtx); $b++) { 
        	$brandsMatrix[$b] = $subBrandRanking->assemble($types[$b], $brandsMtx[$b], $type);
        	$brandMtx[$b] = $brandsMatrix[$b][0];
        	$brandTotal[$b] = $brandsMatrix[$b][1];
        }

        $tmp = array(13, 'DN');
        array_push($brands, $tmp);
        
        $data = array('mtx' => $mtx, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brand' => $brands, 'brandsMtx' => $brandMtx, 'brandsTotal' => $brandTotal, 'type' => $type);
        $labels = array("exports.ranking.brand.allBrandsExport", "exports.ranking.brand.brandExport");

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new rankingBrandExport($data, $labels, $typeExport, $auxTitle), $title);
    }

    public function market(){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionExcel");

        $r = new region();
        $tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $salesRegion = $tmp[0]['name'];
        }else{  
            $salesRegion = $tmp['name'];
        }

        $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

        $currency[0]['id'] = $tmp[0]->id;
        $currency[0]['name'] = $tmp[0]->name;
        $currency[0]['region'] = $tmp[0]->region;

        $type = Request::get("typeExcel");

        $months = json_decode(base64_decode(Request::get("monthsExcel")));

        $brands = json_decode(base64_decode(Request::get("brandsExcel")));

        $value = Request::get("valueExcel");
        
        $years = json_decode(base64_decode(Request::get("yearsExcel")));

        $rm = new rankingMarket();
        
        $values = $rm->getAllResults($con, $brands, $type, $region, $salesRegion, $value, $currency, $months, $years);
        
        $matrix = $rm->assembler($values, $years, $type);

        $mtx = $matrix[0];
        $total = $matrix[1];

        $headNames = $rm->createNames($type, $months, $salesRegion, $brands);

        if ($type == "sector") {
            $auxNames = json_decode(base64_decode(Request::get("names")));
            for ($n=0; $n < sizeof($auxNames); $n++) { 
                $names[$n][0] = null;
                $names[$n][1] = $auxNames[$n];
            }
        }else{
            $auxNames = Request::get("names");
            if (!is_null($auxNames)) {
                for ($n=0; $n < sizeof($auxNames); $n++) { 
                    $names[$n] = json_decode(base64_decode($auxNames[$n]));
                    if ($names[$n][0] == "-") {
                        $names[$n][0] = "Others";
                    }
                }
            }else{
                $names = null;
            }
        }
        
        if (is_null($names)) {
            $val = null;
            $subMtx = null;
            $subTotal = null;
        }else{

            $smr = new subMarketRanking();

            if ($type == "agency" || $type == "sector" || $type == "category") {
                $val = "client";
            }else{
                $val = "brand";
            }

            $subValues = array();

            for ($n=0; $n < sizeof($names); $n++) { 
                array_push($subValues, $smr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $names[$n][1], $val, $names[$n][0]));
            }

            if ($type != "client") {
                
                $base = new base();

                $months2 = array();
                for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
                    array_push($months2, $m);
                }

                $subValuesTotal = array();

                for ($n=0; $n < sizeof($names); $n++) { 
                    array_push($subValuesTotal, $smr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $names[$n][1], $val, $names[$n][0]));
                }
            }else{
                $subValuesTotal = array();

                for ($n=0; $n < sizeof($names); $n++) { 
                    array_push($subValuesTotal, null);
                }
            }

            for ($n=0; $n < sizeof($names); $n++) { 
                $subMatrix[$n] = $smr->subMarketAssembler($subValues[$n], $subValuesTotal[$n], $type, $brands, $val);

                if (is_string($subMatrix[$n])) {
                    $subMtx[$n] = $subMatrix[$n];
                    $subTotal[$n] = false;
                }else{
                    $subMtx[$n] = $subMatrix[$n][0];
                    $subTotal[$n] = $subMatrix[$n][1];
                }
            }
        }

        $title = Request::get("title");

        $data = array('mtx' => $mtx, 'total' => $total, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brands' => $brands, 'type' => $type, 'subMtx' => $subMtx, 'subTotal' => $subTotal, 'headNames' => $headNames, 'market' => $names, 'type' => $type, 'val' => $val, 'years' => $years);

        $labels = array("exports.ranking.market.allMarketExport", "exports.ranking.market.marketExport");

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new rankingMarketExport($data, $labels, $typeExport, $auxTitle), $title);
    }

    public function churn(){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionExcel");

        $r = new region();
        $tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $salesRegion = $tmp[0]['name'];
        }else{  
            $salesRegion = $tmp['name'];
        }

        $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

        $currency[0]['id'] = $tmp[0]->id;
        $currency[0]['name'] = $tmp[0]->name;
        $currency[0]['region'] = $tmp[0]->region;

        $type = Request::get("typeExcel");

        $months = json_decode(base64_decode(Request::get("monthsExcel")));

        $brands = json_decode(base64_decode(Request::get("brandsExcel")));

        $value = Request::get("valueExcel");
        
        $years = json_decode(base64_decode(Request::get("yearsExcel")));

        $rc = new rankingChurn();

        $values = $rc->getAllResults($con, $brands, $type, $region, $salesRegion, $value, $currency, $months, $years);
        
        $finalValues = array();

        if ($type != "agency" && $type != "client") {
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rc->existInArray($finalValues, $values[$r][$r2][$type], $type)) {
                            array_push($finalValues, $values[$r][$r2]);
                        }
                    }
                }
            }
        }else{
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rc->existInArray($finalValues, $values[$r][$r2][$type."ID"], $type, true)) {
                            array_push($finalValues, $values[$r][$r2]);  
                        }
                    }
                }
            }
        }
        
        $base = new Base();
        $months2 = array();
        for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
            array_push($months2, $m);
        }

        $valuesTotal = $rc->getAllResults($con, $brands, $type, $region, $salesRegion, $value, $currency, $months2, $years);

        $matrix = $rc->assembler($values, $finalValues, $valuesTotal, $years, $type);

        $mtx = $matrix[0];
        $total = $matrix[1];

        $headNames = $rc->createNames($type, $months, $salesRegion, $brands);

        if ($type == "sector") {
            $auxNames = json_decode(base64_decode(Request::get("names")));
            for ($n=0; $n < sizeof($auxNames); $n++) { 
                $names[$n][0] = null;
                $names[$n][1] = $auxNames[$n];
            }
        }else{
            $auxNames = Request::get("names");
            if (!is_null($auxNames)) {
                for ($n=0; $n < sizeof($auxNames); $n++) { 
                    $names[$n] = json_decode(base64_decode($auxNames[$n]));
                    if ($names[$n][0] == "-") {
                        $names[$n][0] = "Others";
                    }
                }
            }else{
                $names = null;
            }
        }

        $data = array('mtx' => $mtx, 'total' => $total, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brands' => $brands, 'type' => $type, 'headNames' => $headNames, 'type' => $type, 'years' => $years);

        if (!is_null($names)) {
            $scr = new subChurnRanking();

            if ($type == "client") {
                $val = "agency";
            }else{
                $val = "client";
            }

            $subValues = array();

            for ($n=0; $n < sizeof($names); $n++) { 
                array_push($subValues, $scr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $names[$n][1], $val, $names[$n][0]));
            }

            if ($type == "client") {
                $filterType = "agency";
            }else{
                $filterType = "client";
            }

            $subFinalValues = array();

            for ($n=0; $n < sizeof($names); $n++) { 
                for ($v=0; $v < sizeof($subValues[$n]); $v++) { 
                    if (is_array($subValues[$n][$v])) {
                        for ($v2=0; $v2 < sizeof($subValues[$n][$v]); $v2++) { 
                            if ($scr->existInArray($subFinalValues, $subValues[$n][$v][$v2][$filterType."ID"], $filterType, true)) {
                                array_push($subFinalValues, $subValues[$n][$v][$v2]);
                            }
                        }
                    }
                }
            }

            $cYear = intval(date('Y'));
            $subYears = array($cYear, $cYear-1, $cYear-2);

            $base = new base();

            $subMonths2 = array();
            for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
                array_push($subMonths2, $m);
            }

            $subValuesTotal = array();

            for ($n=0; $n < sizeof($names); $n++) { 
                array_push($subValuesTotal, $scr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $names[$n][1], $val, $names[$n][0]));

                $subMatrix[$n] = $scr->assembler($subValues[$n], $subFinalValues, $subValuesTotal[$n], $subYears, $filterType);

                $subMtx[$n] = $subMatrix[$n][0];
                $subTotal[$n] = $subMatrix[$n][1];
            }

            $data = array('mtx' => $mtx, 'total' => $total, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brands' => $brands, 'type' => $type, 'subMtx' => $subMtx, 'subTotal' => $subTotal, 'headNames' => $headNames, 'churn' => $names, 'type' => $type, 'val' => $val, 'years' => $years);

        }

        $title = Request::get("title");

        $labels = array("exports.ranking.churn.allChurnExport", "exports.ranking.churn.churnExport");

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new rankingChurnExport($data, $labels, $typeExport, $auxTitle), $title);
    }

    public function new(){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionExcel");

        $r = new region();
        $tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $salesRegion = $tmp[0]['name'];
        }else{  
            $salesRegion = $tmp['name'];
        }

        $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

        $currency[0]['id'] = $tmp[0]->id;
        $currency[0]['name'] = $tmp[0]->name;
        $currency[0]['region'] = $tmp[0]->region;

        $type = Request::get("typeExcel");

        $months = json_decode(base64_decode(Request::get("monthsExcel")));

        $brands = json_decode(base64_decode(Request::get("brandsExcel")));

        $value = Request::get("valueExcel");
        
        $years = json_decode(base64_decode(Request::get("yearsExcel")));

        $rn = new rankingNew();

        $values = $rn->getAllResults($con, $brands, $type, $region, $salesRegion, $value, $currency, $months, $years);
        
        $finalValues = array();

        if ($type != "agency" && $type != "client") {
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rn->existInArray($finalValues, $values[$r][$r2][$type], $type)) {
                            array_push($finalValues, $values[$r][$r2]);
                        }
                    }
                }
            }
        }else{
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rn->existInArray($finalValues, $values[$r][$r2][$type."ID"], $type, true)) {
                            array_push($finalValues, $values[$r][$r2]);  
                        }
                    }
                }
            }
        }

        $base = new Base();
        $months2 = array();
        for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
            array_push($months2, $m);
        }

        $valuesTotal = $rn->getAllResults($con, $brands, $type, $region, $salesRegion, $value, $currency, $months2, $years);

        $matrix = $rn->assembler($values, $finalValues, $valuesTotal, $years, $type);

        $mtx = $matrix[0];
        $total = $matrix[1];

        $headNames = $rn->createNames($type, $months, $salesRegion, $brands);

        if ($type == "sector") {
            $auxNames = json_decode(base64_decode(Request::get("names")));
            for ($n=0; $n < sizeof($auxNames); $n++) { 
                $names[$n][0] = null;
                $names[$n][1] = $auxNames[$n];
            }
        }else{
            $auxNames = Request::get("names");
            if (!is_null($auxNames)) {
                for ($n=0; $n < sizeof($auxNames); $n++) { 
                    $names[$n] = json_decode(base64_decode($auxNames[$n]));
                    if ($names[$n][0] == "-") {
                        $names[$n][0] = "Others";
                    }
                }
            }else{
                $names = null;
            }
        }

        $data = array('mtx' => $mtx, 'total' => $total, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brands' => $brands, 'type' => $type, 'headNames' => $headNames, 'type' => $type, 'years' => $years);

        if (!is_null($names)) {
            $snr = new subNewRanking();

            if ($type == "client") {
                $val = "agency";
            }else{
                $val = "client";
            }

            for ($n=0; $n < sizeof($names); $n++) { 
                $subValues[$n] = $snr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $names[$n][1], $val, $names[$n][0]);
            }

            if ($type == "client") {
                $filterType = "agency";
            }else{
                $filterType = "client";
            }

            $subFinalValues = array();

            for ($n=0; $n < sizeof($names); $n++) { 
                for ($v=0; $v < sizeof($subValues[$n]); $v++) { 
                    if (is_array($subValues[$n][$v])) {
                        for ($v2=0; $v2 < sizeof($subValues[$n][$v]); $v2++) { 
                            if ($snr->existInArray($subFinalValues, $subValues[$n][$v][$v2][$filterType."ID"], $filterType, true)) {
                                array_push($subFinalValues, $subValues[$n][$v][$v2]);
                            }
                        }
                    }
                }
            }

            $cYear = intval(date('Y'));
            $subYears = array($cYear, $cYear-1, $cYear-2);

            $base = new base();

            $subMonths2 = array();
            for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
                array_push($subMonths2, $m);
            }

            for ($n=0; $n < sizeof($names); $n++) { 
                $subValuesTotal[$n] = $snr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $names[$n][1], $val, $names[$n][0]);

                $subMatrix[$n] = $snr->assembler($subValues[$n], $subFinalValues, $subValuesTotal[$n], $subYears, $filterType);

                $subMtx[$n] = $subMatrix[$n][0];
                $subTotal[$n] = $subMatrix[$n][1];
            }

            $data = array('mtx' => $mtx, 'total' => $total, 'currency' => $currency, 'value' => $value, 'region' => $salesRegion, 'brands' => $brands, 'type' => $type, 'subMtx' => $subMtx, 'subTotal' => $subTotal, 'headNames' => $headNames, 'new' => $names, 'type' => $type, 'val' => $val, 'years' => $years);
        }

        $title = Request::get("title");

        $labels = array("exports.ranking.new.allNewExport", "exports.ranking.new.newExport");

        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new rankingNewExport($data, $labels, $typeExport, $auxTitle), $title);
    }

    public function ranking(){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $region = Request::get("regionExcel");

        $r = new region();
        $tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $salesRegion = $tmp[0]['name'];
        }else{  
            $salesRegion = $tmp['name'];
        }

        $tmp = json_decode(base64_decode(Request::get("currencyExcel")));

        $currency[0]['id'] = $tmp[0]->id;
        $currency[0]['name'] = $tmp[0]->name;
        $currency[0]['region'] = $tmp[0]->region;

        $type = Request::get("typeExcel");
        
        $tmp = json_decode(base64_decode(Request::get("type2Excel")));

        for ($t=0; $t < sizeof($tmp); $t++) {
            $type2[$t] = json_decode(base64_decode($tmp[$t]));
        }

        $nPos = Request::get("nPosExcel");

        $months = json_decode(base64_decode(Request::get("monthsExcel")));

        $brands = json_decode(base64_decode(Request::get("brandsExcel")));

        $value = Request::get("valueExcel");

        $firstForm = Request::get("firstFormExcel");
        $secondForm = Request::get("secondFormExcel");
        $thirdForm = Request::get("thirdFormExcel");

        $r = new rankings();

        $years = $r->createPositions($firstForm, $secondForm, $thirdForm);

        $values = $r->getAllResults($con, $brands, $type, $region, $value, $currency, $months, $years, $type2);

        $filterValues = $r->filterValues($values, $type2, $type);

        $mtx = $r->assembler($values, $type2, $years, $type, $filterValues);

        if ($nPos == 'All') {
            $size = sizeof($mtx[0]);
        }else{
            $size = ($nPos+1);
        }

        $total = $r->assemblerTotal($mtx, $years, $size);
        
        $head = $r->createNames2($type, $months, $years);

        $data = array("region" => $salesRegion, "currency" => $currency, "type" => $type, "nPos" => $size, "months" => $months, "brands" => $brands, "value" => $value, "firstForm" => $firstForm, "secondForm" => $secondForm, "thirdForm" => $thirdForm, "years" => $years, "mtx" => $mtx, "total" => $total, "names" => $head);

        $labels = array("exports.ranking.ranking.allRankingExport");

        $auxNames = Request::get("names");
        if (!is_null($auxNames)) {
            for ($n=0; $n < sizeof($auxNames); $n++) { 
                $names[$n] = json_decode(base64_decode($auxNames[$n]));
                if ($names[$n][0] == "-") {
                    $names[$n][0] = "Others";
                }
            }
        }else{
            $names = null;
        }

        if (!is_null($names)) {
            $sr = new subRankings();

            for ($n=0; $n < sizeof($names); $n++) {
                $subValues[$n] = $sr->getSubResults($con, $brands, $type, $region, $value, $currency, $months, $years, $names[$n][1], $names[$n][0]);

                $matrix =  $sr->assembler($subValues[$n], $years, $type);

                $subMtx[$n] = $matrix[0];
                $subTotal[$n] = $matrix[1];

                if ($type == "agencyGroup") {
                    $subType = "agency";
                }elseif ($type == "agency") {
                    $subType = "client";
                }else{
                    $subType = "agency";
                }
            }

            $data = array("region" => $salesRegion, "currency" => $currency, "type" => $type, "nPos" => $size, "months" => $months, "brands" => $brands, "value" => $value, "firstForm" => $firstForm, "secondForm" => $secondForm, "thirdForm" => $thirdForm, "years" => $years, "mtx" => $mtx, "total" => $total, "names" => $head, "subMtx" => $subMtx, "subTotal" => $subTotal, "subType" => $subType, "type2" => $names);

            $labels = array("exports.ranking.ranking.allRankingExport", "exports.ranking.ranking.rankingExport");
        }

        $title = Request::get("title");
        
        $typeExport = Request::get("typeExport");
        $auxTitle = Request::get("auxTitle");

        return Excel::download(new rankingExport($data, $labels, $typeExport, $auxTitle), $title);
    }
}