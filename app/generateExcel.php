<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\sql;
use App\planByBrand;
use App\planBySales;
use App\ytd;
use App\cmaps;
use App\digital;
use App\base;
use App\pRate;

class generateExcel extends Model {

	public function selectData($con, $region, $years, $brands, $form, $currency, $value, $months){

		$onlCheck = false;

		for ($b=0; $b < sizeof($brands); $b++) { 
			$brand_id[$b] = $brands[$b]['id'];
			if ($brand_id[$b] == 9) {
				$onlCheck = true;
			}
		}

		if ($onlCheck) {
			array_push($brand_id, '13');
			array_push($brand_id, '14');
			array_push($brand_id, '15');
			array_push($brand_id, '16');
		}

		$sql = new sql();

		if (($form == "TARGET" || $form == "CORPORATE" || $form == "ACTUAL") || is_array($form)) {

			$cols = array("sales_office_id", "year", "brand_id", "source", "currency_id", "type_of_revenue");
			$colsValue = array($region, $years, $brand_id, $form, '4', $value);

			$where = $sql->where($cols, $colsValue);

			$p = new planByBrand();

			$values = $p->getWithFilter($con, $where, $currency, $region, $months);

		}elseif ($form == "sales") {
			
			$cols = array("region", "year", "brand_id", "currency_id", "type_of_revenue");

			$colsValue = array($region, $years, $brand_id, $currency, $value);

			$where = $sql->where($cols, $colsValue);

			$p = new planBySales();

			$values = $p->getWithFilter($con, $where, $currency, $region, $months);

		}elseif ($form == "ytd") {
			
			$cols = array("sales_representant_office_id", "year", "brand_id");
			$colsValue = array($region, $years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$y = new ytd();

			$values = $y->getWithFilter($con, $value, $currency, $region, $where, $months);

		}else{

			$cols = array("year", "brand_id");
			$colsValue = array($years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$c = new cmaps();

			$values = $c->getWithFilter($con, $value, $region, $currency, $where, $months);
		}

		if ($form != "TARGET" && $form != "CORPORATE" && $form != "ACTUAL" && !is_array($form)) {
			$cols = array("d.region_id", "year", "brand_id");
			$colsValue = array($region, $years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$d = new digital();

			$valuesDigital = $d->getWithFilter($con, $value, $where, $currency, $region, $months);

		}else{
			$valuesDigital = null;
		}

		return array($values, $valuesDigital);

	}

}
