<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\region;
use App\dataBase;

use App\rankingBrand;
use App\subBrandRanking;

use App\Exports\rankingBrandExport;

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
        $labels = array("exports.ranking.rankingBrand.allBrandsExport", "exports.ranking.rankingBrand.brandExport");

        return Excel::download(new rankingBrandExport($data, $labels), $title);
    }

}
