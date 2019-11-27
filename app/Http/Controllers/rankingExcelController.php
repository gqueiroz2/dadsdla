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

use App\Exports\rankingBrandExport;
use App\Exports\rankingMarketExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class rankingExcelController extends Controller {
    
	public function brand(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

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

        return Excel::download(new rankingBrandExport($data, $labels), $title);
    }

    public function market(){
        
        $db = new dataBase();
        $con = $db->openConnection("DLA");

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
            $names = json_decode(base64_decode(Request::get("names")));
        }else{
            $names = Request::get("names");
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
                array_push($subValues, $smr->getSubResults($con, $type, $region, $value, $months, $brands, $currency, $names[$n], $val));
            }

            if ($type != "client") {
                
                $base = new base();

                $months2 = array();
                for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
                    array_push($months2, $m);
                }

                $subValuesTotal = array();

                for ($n=0; $n < sizeof($names); $n++) { 
                    array_push($subValuesTotal, $smr->getSubResults($con, $type, $region, $value, $months2, $brands, $currency, $names[$n], $val));
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

        return Excel::download(new rankingMarketExport($data, $labels), $title);
    }

}
